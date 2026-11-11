<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Perpajakan: ringkasan & kalender pajak, masa PPN, setoran pajak (NTPN),
 * koreksi fiskal, PPh Badan, dan pengaturan pajak perusahaan.
 */
class Pajak extends MY_Controller
{
    /** pengaturan: kunci => [label, tipe/kategori akun, keterangan] */
    private $pref = array(
        'pajak_nama_wp'     => array('Nama Wajib Pajak', 'text', 'Sesuai NPWP'),
        'pajak_npwp'        => array('NPWP (16 digit)', 'text', 'NPWP badan / NIK untuk orang pribadi'),
        'pajak_pkp'         => array('Pengusaha Kena Pajak (PKP)?', 'yn', 'Y bila sudah dikukuhkan PKP — wajib memungut PPN'),
        'pajak_rezim'       => array('Rezim PPh', 'rezim', 'UMUM = Pasal 17 + fasilitas 31E. FINAL_UMKM = 0,5% dari omzet (PP 55/2022 jo. PP 20/2026)'),
        'pajak_tarif_badan' => array('Tarif PPh Badan (%)', 'number', 'Pasal 17 ayat (2a): 22%'),
        'pajak_tarif_final' => array('Tarif PPh Final UMKM (%)', 'number', '0,5% dari peredaran bruto'),
        'ppn_persen'        => array('Tarif PPN efektif pada faktur (%)', 'number', '11 = 12% × DPP nilai lain 11/12 (barang/jasa non-mewah, PMK 131/2024)'),
        'ppn_dpp_nilai_lain'=> array('Tampilkan DPP Nilai Lain 11/12 di rekap PPN', 'yn', 'Untuk pengisian e-Faktur / Coretax'),
        'pph23_persen'      => array('Tarif PPh 23 jasa (%)', 'number', '2% (tanpa NPWP 4%)'),
        'akun_ppn_keluaran' => array('Akun PPN Keluaran', 'LIAB_PENDEK', ''),
        'akun_ppn_masukan'  => array('Akun PPN Masukan', 'ASET_LANCAR', ''),
        'akun_hutang_ppn'   => array('Akun Hutang PPN (kurang bayar)', 'LIAB_PENDEK', ''),
        'akun_hutang_pph21' => array('Akun Hutang PPh 21', 'LIAB_PENDEK', ''),
        'akun_hutang_pph23' => array('Akun Hutang PPh 23', 'LIAB_PENDEK', ''),
        'akun_hutang_pph42' => array('Akun Hutang PPh 4(2)', 'LIAB_PENDEK', ''),
        'akun_hutang_pph_badan' => array('Akun Hutang PPh Badan (Ps. 29)', 'LIAB_PENDEK', ''),
        'akun_pph22_dimuka' => array('Akun PPh 22 Dibayar Dimuka', 'ASET_LANCAR', ''),
        'akun_pph23_dimuka' => array('Akun PPh 23 Dibayar Dimuka', 'ASET_LANCAR', ''),
        'akun_pph25_dimuka' => array('Akun PPh 25 Dibayar Dimuka', 'ASET_LANCAR', ''),
        'akun_beban_pph_badan' => array('Akun Beban PPh Badan', 'PAJAK_PENGHASILAN', ''),
        'akun_beban_pph_final' => array('Akun Beban PPh Final', 'PAJAK_PENGHASILAN', ''),
    );

    public function __construct()
    {
        parent::__construct();
        $this->load->model('accounting/Mod_pajak');
        $this->load->library('akuntansi');
        $this->wajib('view');
    }

    public function index()
    {
        $tahun = (int) ($this->input->get('tahun') ?: date('Y'));
        $bulanIni = (int) date('n');
        $ppn = $this->Mod_pajak->ppn_tahun($tahun);
        $buku = $this->Mod_pajak->pph_buku($tahun);
        $hutang = 0; $dimuka = 0;
        foreach ($buku as $b) {
            if (in_array($b->kode, array($this->Mod_pajak->akun('akun_ppn_keluaran'), $this->Mod_pajak->akun('akun_ppn_masukan')), true)) continue;
            if ($b->saldo_normal === 'K') $hutang += $b->akhir; else $dimuka += $b->akhir;
        }
        $rekon = $this->Mod_pajak->rekon_fiskal($tahun);
        $this->template->load('layoutbackend', 'accounting/pajak/index', array(
            'page' => 'Perpajakan', 'judul' => 'Perpajakan', 'tahun' => $tahun,
            'profil' => $this->Mod_pajak->profil(), 'ppn' => $ppn, 'buku' => $buku, 'hutang' => $hutang, 'dimuka' => $dimuka,
            'rekon' => $rekon, 'kalender' => $this->Mod_pajak->kalender((int) date('Y'), $bulanIni),
            'setor' => $this->Mod_pajak->setor_list($tahun), 'koreksi' => $this->Mod_pajak->koreksi_list($tahun),
            'pref' => $this->pref, 'setting' => $this->akuntansi->setting(), 'akun' => $this->akuntansi->akun(),
            'boleh' => array('add' => $this->boleh('add'), 'edit' => $this->boleh('edit'), 'delete' => $this->boleh('delete'), 'print' => $this->boleh('print')),
        ));
    }

    /* ---------------- setoran ---------------- */
    public function form_setor()
    {
        $this->wajib('add');
        $jenis = (string) $this->input->get('jenis');
        $masa = (string) $this->input->get('masa') ?: date('Y-m', strtotime('first day of last month'));
        $this->load->view('accounting/pajak/setor_form', array('akun' => $this->akuntansi->akun(), 'jenis' => $jenis, 'masa' => $masa,
            'saran' => $jenis ? $this->Mod_pajak->saran_setor($jenis, $masa) : 0));
    }

    public function saran()
    {
        $this->json(array('jumlah' => $this->Mod_pajak->saran_setor((string) $this->input->get('jenis'), (string) $this->input->get('masa'))));
    }

    public function simpan_setor()
    {
        $this->wajib('add');
        $id = $this->Mod_pajak->setor_simpan($this->input->post());
        $this->json($id ? array('status' => true, 'pesan' => 'Setoran pajak tersimpan & dijurnal.') : array('status' => false, 'pesan' => $this->Mod_pajak->error));
    }

    public function hapus_setor()
    {
        $this->wajib('delete');
        $ok = $this->Mod_pajak->setor_hapus($this->input->post('id'));
        $this->json(array('status' => (bool) $ok, 'pesan' => $ok ? 'Setoran dihapus beserta jurnalnya.' : $this->Mod_pajak->error));
    }

    /* ---------------- masa PPN ---------------- */
    public function tutup_ppn()
    {
        $this->wajib('add');
        $r = $this->Mod_pajak->tutup_masa_ppn((string) $this->input->post('periode'));
        if ( ! $r) $this->json(array('status' => false, 'pesan' => $this->Mod_pajak->error));
        $this->json(array('status' => true, 'pesan' => 'Masa PPN ditutup. ' . ($r->kurang_bayar > 0 ? 'Kurang bayar ' . nf($r->kurang_bayar) . ' → setor lewat tab Setoran.' : 'Lebih bayar ' . nf($r->lebih_bayar) . ' dikompensasikan ke masa berikutnya.')));
    }

    public function batal_ppn()
    {
        $this->wajib('delete');
        $ok = $this->Mod_pajak->batal_masa_ppn((string) $this->input->post('periode'));
        $this->json(array('status' => (bool) $ok, 'pesan' => $ok ? 'Tutup masa PPN dibatalkan.' : $this->Mod_pajak->error));
    }

    /* ---------------- fiskal & PPh Badan ---------------- */
    public function simpan_koreksi()
    {
        $this->wajib('add');
        $id = $this->Mod_pajak->koreksi_simpan($this->input->post());
        $this->json($id ? array('status' => true, 'pesan' => 'Koreksi fiskal ditambahkan.') : array('status' => false, 'pesan' => $this->Mod_pajak->error));
    }

    public function hapus_koreksi()
    {
        $this->wajib('delete');
        $this->Mod_pajak->koreksi_hapus($this->input->post('id'));
        $this->json(array('status' => true, 'pesan' => 'Koreksi dihapus.'));
    }

    public function simpan_fiskal()
    {
        $this->wajib('edit');
        if ( ! $this->db->field_exists('fiskal', 'tbl_acc_akun')) $this->json(array('status' => false, 'pesan' => 'Jalankan migrasi database v3 terlebih dahulu.'));
        $f = $this->input->post('fiskal');
        $n = 0;
        if (is_array($f)) foreach ($f as $kode => $v) {
            $v = isset(Akuntansi::$FISKAL[$v]) ? $v : '';
            $this->db->where('kode_akun', (string) $kode)->update('tbl_acc_akun', array('fiskal' => $v));
            $n++;
        }
        $this->json(array('status' => true, 'pesan' => 'Perlakuan fiskal ' . $n . ' akun disimpan.'));
    }

    public function jurnal_pph_badan()
    {
        $this->wajib('add');
        $tahun = (int) $this->input->post('tahun');
        $ref = $this->Mod_pajak->jurnal_pph_badan($tahun);
        $this->json(array('status' => (bool) $ref, 'pesan' => $ref ? 'Jurnal PPh Badan ' . $tahun . ' dibuat (' . $ref . ').' : $this->Mod_pajak->error));
    }

    public function batal_pph_badan()
    {
        $this->wajib('delete');
        $ok = $this->Mod_pajak->batal_pph_badan((int) $this->input->post('tahun'));
        $this->json(array('status' => (bool) $ok, 'pesan' => $ok ? 'Jurnal PPh Badan dihapus.' : $this->Mod_pajak->error));
    }

    /* ---------------- pengaturan ---------------- */
    public function simpan_setting()
    {
        $this->wajib('edit');
        $data = array();
        foreach ($this->pref as $k => $x) {
            $v = $this->input->post($k);
            if ($v === null) continue;
            $v = trim((string) $v);
            if ($x[1] === 'number') $v = (string) to_number($v);
            elseif ($x[1] === 'yn') $v = $v === 'Y' ? 'Y' : 'N';
            elseif ($x[1] === 'rezim') $v = $v === 'FINAL_UMKM' ? 'FINAL_UMKM' : 'UMUM';
            elseif ($x[1] === 'text') $v = mb_substr($v, 0, 100);
            elseif ($v !== '' && ! $this->akuntansi->akun($v)) $this->json(array('status' => false, 'pesan' => $x[0] . ': akun tidak ditemukan.'));
            $data[$k] = $v;
        }
        $this->akuntansi->simpan_setting($data);
        $this->json(array('status' => true, 'pesan' => 'Pengaturan pajak tersimpan.'));
    }
}
