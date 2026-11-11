<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Laporan & analisis HRD — hanya membaca data pegawai, cuti & absensi yang sudah ada. */
class Mod_laphr extends CI_Model
{
    private $peg = null;

    public static function tgl_ok($t) { return $t && substr((string) $t, 0, 4) !== '0000' && strtotime($t) !== false; }
    public static function aktif($r) { return ! in_array(strtolower(trim((string) $r->status)), array('n', 'keluar', 'resign', 'nonaktif', '0'), true); }
    private function peta($tabel, $id, $nama)
    {
        $o = array(); if ($this->db->table_exists($tabel)) foreach ($this->db->get($tabel)->result() as $r) $o[$r->$id] = $r->$nama; return $o;
    }

    /** semua pegawai + nama departemen/jabatan/pendidikan */
    public function pegawai($semua = false)
    {
        if ($this->peg === null) {
            $D = $this->peta('tbl_hrd_departement', 'id_departement', 'departement'); $J = $this->peta('tbl_hrd_jabatan', 'id_jabatan', 'jabatan'); $P = $this->peta('tbl_hrd_pendidikan', 'id_pendidikan', 'pendidikan');
            $this->peg = array();
            foreach ($this->db->order_by('nama_depan')->get('tbl_hrd_pegawai')->result() as $r) {
                $r->nama = trim(trim((string) $r->gelardepan) . ' ' . trim((string) $r->nama_depan) . ' ' . trim((string) $r->nama_belakang) . (trim((string) $r->gelar) !== '' ? ', ' . trim($r->gelar) : ''));
                $r->dept = isset($D[$r->departement]) ? $D[$r->departement] : 'Belum diisi';
                $r->jab = isset($J[$r->jabatan]) ? $J[$r->jabatan] : 'Belum diisi';
                $r->pend = isset($P[$r->pendidikan]) ? $P[$r->pendidikan] : (trim((string) $r->pendidikan) !== '' && ! is_numeric($r->pendidikan) ? $r->pendidikan : 'Belum diisi');
                $k = strtoupper(trim((string) $r->kelamin)); $r->jk = in_array($k, array('L', 'LAKI-LAKI', 'PRIA', 'M'), true) ? 'Laki-laki' : (in_array($k, array('P', 'PEREMPUAN', 'WANITA', 'F'), true) ? 'Perempuan' : 'Belum diisi');
                $r->umur = self::tgl_ok($r->tgl_lahir) ? (int) floor((time() - strtotime($r->tgl_lahir)) / 31557600) : null;
                $r->masa = self::tgl_ok($r->tgl_masuk) ? (time() - strtotime($r->tgl_masuk)) / 31557600 : null;
                $r->is_aktif = self::aktif($r);
                $this->peg[] = $r;
            }
        }
        return $semua ? $this->peg : array_values(array_filter($this->peg, function ($r) { return $r->is_aktif; }));
    }

    private static function tambah(array &$a, $k, $n = 1) { $a[$k] = (isset($a[$k]) ? $a[$k] : 0) + $n; }

    public function komposisi()
    {
        $P = $this->pegawai(); $o = array('dept' => array(), 'jab' => array(), 'pend' => array(), 'jk' => array(), 'umur' => array(), 'status' => array(), 'matriks' => array());
        foreach ($P as $r) {
            self::tambah($o['dept'], $r->dept); self::tambah($o['jab'], $r->jab); self::tambah($o['pend'], $r->pend); self::tambah($o['jk'], $r->jk);
            self::tambah($o['status'], trim((string) $r->status_kerja) !== '' ? $r->status_kerja : 'Belum diisi');
            $u = $r->umur; self::tambah($o['umur'], $u === null ? 'Belum diisi' : ($u < 25 ? '< 25' : ($u < 35 ? '25-34' : ($u < 45 ? '35-44' : ($u < 55 ? '45-54' : '≥ 55')))));
            if ( ! isset($o['matriks'][$r->dept])) $o['matriks'][$r->dept] = array();
            self::tambah($o['matriks'][$r->dept], $r->jab);
        }
        foreach (array('dept', 'jab', 'pend', 'status') as $k) arsort($o[$k]);
        $urut = array('< 25', '25-34', '35-44', '45-54', '≥ 55', 'Belum diisi'); $u2 = array(); foreach ($urut as $k) if (isset($o['umur'][$k])) $u2[$k] = $o['umur'][$k]; $o['umur'] = $u2;
        $o['total'] = count($P);
        return $o;
    }

    public function masa_kerja()
    {
        $rows = $this->pegawai(); usort($rows, function ($a, $b) { return ($b->masa ?? -1) <=> ($a->masa ?? -1); });
        $kel = array('< 1 th' => 0, '1-3 th' => 0, '3-5 th' => 0, '5-10 th' => 0, '> 10 th' => 0, 'Belum diisi' => 0); $tot = 0; $n = 0; $milestone = array();
        foreach ($rows as $r) {
            if ($r->masa === null) { $kel['Belum diisi']++; continue; }
            $t = $r->masa; $kel[$t < 1 ? '< 1 th' : ($t < 3 ? '1-3 th' : ($t < 5 ? '3-5 th' : ($t < 10 ? '5-10 th' : '> 10 th')))]++; $tot += $t; $n++;
            // ulang tahun kerja 5/10/15/20/25 dalam 12 bulan ke depan (penghargaan masa kerja)
            $tahunDepan = (int) floor($t) + 1;
            if ($tahunDepan % 5 === 0) $milestone[] = (object) array('r' => $r, 'tahun' => $tahunDepan, 'tgl' => date('Y-m-d', strtotime($r->tgl_masuk . ' +' . $tahunDepan . ' year')));
        }
        usort($milestone, function ($a, $b) { return strcmp($a->tgl, $b->tgl); });
        return array('rows' => $rows, 'kelompok' => $kel, 'rata' => $n ? $tot / $n : 0, 'milestone' => $milestone);
    }

    public function ulang_tahun($bulan)
    {
        $rows = array();
        foreach ($this->pegawai() as $r) if (self::tgl_ok($r->tgl_lahir) && (int) substr($r->tgl_lahir, 5, 2) === (int) $bulan) $rows[] = $r;
        usort($rows, function ($a, $b) { return strcmp(substr($a->tgl_lahir, 8, 2), substr($b->tgl_lahir, 8, 2)); });
        $per = array_fill(1, 12, 0); foreach ($this->pegawai() as $r) if (self::tgl_ok($r->tgl_lahir)) $per[(int) substr($r->tgl_lahir, 5, 2)]++;
        return array('rows' => $rows, 'per_bulan' => $per);
    }

    public function cuti($tahun)
    {
        $kode = $this->peta('tbl_hrd_kodecuti', 'kode', 'nama_cuti');
        $peg = array(); foreach ($this->pegawai(true) as $r) $peg[$r->nip] = $r;
        $rows = array(); $bulan = array_fill(1, 12, 0); $jenis = array();
        if ($this->db->table_exists('tbl_hrd_cuti_pegawai')) foreach ($this->db->where('tgl_cuti >=', $tahun . '-01-01')->where('tgl_cuti <=', $tahun . '-12-31')->order_by('tgl_cuti')->get('tbl_hrd_cuti_pegawai')->result() as $c) {
            $k = $c->nip;
            if ( ! isset($rows[$k])) $rows[$k] = (object) array('nip' => $k, 'nama' => isset($peg[$k]) ? $peg[$k]->nama : $k, 'dept' => isset($peg[$k]) ? $peg[$k]->dept : '-', 'hari' => 0, 'jenis' => array(), 'bulan' => array_fill(1, 12, 0), 'terakhir' => '');
            $j = isset($kode[$c->status_cuti]) ? $kode[$c->status_cuti] : ($c->status_cuti ?: 'Lainnya');
            $rows[$k]->hari++; self::tambah($rows[$k]->jenis, $j); $rows[$k]->bulan[(int) substr($c->tgl_cuti, 5, 2)]++; $rows[$k]->terakhir = $c->tgl_cuti;
            $bulan[(int) substr($c->tgl_cuti, 5, 2)]++; self::tambah($jenis, $j);
        }
        usort($rows, function ($a, $b) { return $b->hari <=> $a->hari; });
        return array('rows' => $rows, 'bulan' => $bulan, 'jenis' => $jenis, 'kode' => $kode);
    }

    /** rekap absensi bulanan dari mesin absen; pin dicocokkan ke nip / nipa pegawai */
    public function absensi($tahun, $bulan, $jamMasuk = '08:00')
    {
        $a = sprintf('%04d-%02d-01', $tahun, $bulan); $b = date('Y-m-t', strtotime($a));
        $peg = array(); foreach ($this->pegawai(true) as $r) { $peg[trim((string) $r->nip)] = $r; if (trim((string) $r->nipa) !== '') $peg[trim((string) $r->nipa)] = $r; }
        $hariKerja = 0; for ($t = strtotime($a); $t <= strtotime($b); $t += 86400) if (date('N', $t) < 6) $hariKerja++;
        $rows = array(); $harian = array();
        if ($this->db->table_exists('tbl_hrd_data_absen')) foreach ($this->db->select('pin, date_time')->where('date_time >=', $a . ' 00:00:00')->where('date_time <=', $b . ' 23:59:59')->order_by('date_time')->get('tbl_hrd_data_absen')->result() as $x) {
            $p = trim((string) $x->pin); $tg = substr($x->date_time, 0, 10); $jam = substr($x->date_time, 11, 5);
            if ( ! isset($rows[$p])) $rows[$p] = (object) array('pin' => $p, 'nama' => isset($peg[$p]) ? $peg[$p]->nama : '(PIN ' . $p . ' belum terhubung)', 'dept' => isset($peg[$p]) ? $peg[$p]->dept : '-', 'hari' => array());
            if ( ! isset($rows[$p]->hari[$tg])) $rows[$p]->hari[$tg] = array($jam, $jam); else $rows[$p]->hari[$tg][1] = $jam;
        }
        foreach ($rows as $r) {
            $r->hadir = count($r->hari); $r->telat = 0; $r->menit_telat = 0; $r->jam_kerja = 0;
            foreach ($r->hari as $tg => $j) {
                if ($j[0] > $jamMasuk) { $r->telat++; $r->menit_telat += (strtotime($tg . ' ' . $j[0]) - strtotime($tg . ' ' . $jamMasuk)) / 60; }
                $r->jam_kerja += max(0, (strtotime($tg . ' ' . $j[1]) - strtotime($tg . ' ' . $j[0])) / 3600);
                self::tambah($harian, $tg);
            }
            $r->persen = $hariKerja ? min(100, $r->hadir / $hariKerja * 100) : 0;
        }
        usort($rows, function ($x, $y) { return strcmp($x->nama, $y->nama); });
        ksort($harian);
        return array('rows' => $rows, 'hari_kerja' => $hariKerja, 'harian' => $harian, 'jam_masuk' => $jamMasuk, 'tanpa_absen' => count($this->pegawai()) - count(array_filter($rows, function ($r) { return strpos($r->nama, '(PIN') !== 0; })));
    }

    /** kelengkapan data pegawai */
    public function kelengkapan()
    {
        $cek = array('tgl_lahir' => 'Tanggal lahir', 'tgl_masuk' => 'Tanggal masuk', 'kelamin' => 'Jenis kelamin', 'departement' => 'Departemen', 'jabatan' => 'Jabatan', 'pendidikan' => 'Pendidikan',
            'no_ktp' => 'No. KTP', 'npwp' => 'NPWP', 'jamsostek' => 'BPJS/Jamsostek', 'no_hp' => 'No. HP', 'alamat' => 'Alamat', 'status_kerja' => 'Status kerja');
        $rows = array(); $per = array_fill_keys(array_keys($cek), 0);
        foreach ($this->pegawai() as $r) {
            $kurang = array();
            foreach ($cek as $k => $nm) {
                $v = trim((string) $r->$k);
                $kosong = $v === '' || $v === '0' || (in_array($k, array('tgl_lahir', 'tgl_masuk'), true) && ! self::tgl_ok($v));
                if ($kosong) { $kurang[] = $nm; $per[$k]++; }
            }
            $rows[] = (object) array('r' => $r, 'kurang' => $kurang, 'persen' => round((count($cek) - count($kurang)) / count($cek) * 100));
        }
        usort($rows, function ($a, $b) { return $a->persen <=> $b->persen; });
        $lbl = array(); foreach ($per as $k => $n) $lbl[$cek[$k]] = $n;
        return array('rows' => $rows, 'per_kolom' => $lbl, 'n' => count($rows));
    }
}
