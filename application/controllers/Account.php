<?php
defined('BASEPATH') or exit('No direct script access allowed');

/** Daftar Akun (Chart of Accounts) */
class Account extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('accounting/Mod_account', 'Mod_menu', 'Mod_userlevel'));
        $this->load->library('akuntansi');
        $this->load->helper('tgl_indo_helper');
    }

    private function data_form()
    {
        return array(
            'dataKl' => $this->Mod_account->select_kelompok(),
            'dataType' => $this->Mod_account->select_type(),
            'dataJn' => $this->Mod_account->select_jenis(),
            'semuaAkun' => $this->akuntansi->akun(),
        );
    }

    public function index()
    {
        $data = $this->data_form();
        $data['page']  = 'Daftar Akun';
        $data['judul'] = 'Chart of Accounts';
        $data['menu']  = $this->Mod_menu->getAll()->result();
        $data['boleh'] = array('add' => $this->boleh('add'), 'edit' => $this->boleh('edit'), 'delete' => $this->boleh('delete'));
        echo show_my_modal('accounting/modals/modal_tambah_akun', 'tambah-akun', $data, 'lg');
        $this->template->load('layoutbackend', 'accounting/akun', $data);
    }

    public function showAcc()
    {
        $data['dataPk'] = $this->Mod_account->select_acc();
        $saldo = array();
        foreach ($this->akuntansi->saldo_per(date('Y-m-d')) as $k => $s) {
            $saldo[(string) $k] = $this->akuntansi->nilai_normal($k, $s['d'], $s['k']);
        }
        $data['saldo'] = $saldo;
        $data['boleh'] = array('edit' => $this->boleh('edit'), 'delete' => $this->boleh('delete'));
        $this->load->view('accounting/data_akun', $data);
    }

    private function validasi()
    {
        $this->form_validation->set_rules('kode_akun', 'Kode Akun', 'trim|required|max_length[25]');
        $this->form_validation->set_rules('nama_akun', 'Nama Akun', 'trim|required|max_length[100]');
        return $this->form_validation->run() == TRUE;
    }

    public function prosesTacc()
    {
        $this->wajib('add');
        $data = $this->input->post();
        if ( ! $this->validasi()) {
            $this->json(array('status' => 'form', 'msg' => strip_tags(validation_errors())));
        }
        if ($this->Mod_account->kode_ada(trim($data['kode_akun']))) {
            $this->json(array('status' => 'form', 'msg' => 'Kode akun ' . html_escape($data['kode_akun']) . ' sudah ada.'));
        }
        $ok = $this->Mod_account->insertAkun($data) > 0;
        $this->json(array('status' => $ok ? 'ok' : 'gagal', 'msg' => $ok ? 'Akun berhasil ditambahkan' : 'Gagal menyimpan akun'));
    }

    public function updateAkun()
    {
        $id = (int) $this->input->post('id');
        $data = $this->data_form();
        $data['dataAkun'] = $this->Mod_account->select_id_akun($id);
        echo show_my_modal('accounting/modals/modal_tambah_akun', 'update-akun', $data, 'lg');
    }

    public function prosesUakun()
    {
        $this->wajib('edit');
        $data = $this->input->post();
        if ( ! $this->validasi()) {
            $this->json(array('status' => 'form', 'msg' => strip_tags(validation_errors())));
        }
        $this->Mod_account->updateAkun($data);
        $this->json(array('status' => 'ok', 'msg' => 'Akun berhasil diperbarui'));
    }

    public function deleteAkun()
    {
        $this->wajib('delete');
        $id = (int) $this->input->post('id');
        $akun = $this->Mod_account->select_id_akun($id);
        if ( ! $akun) $this->json(array('status' => 'gagal', 'msg' => 'Akun tidak ditemukan'));
        $n = $this->Mod_account->dipakai($akun[0]->kode_akun);
        if ($n > 0) {
            $this->json(array('status' => 'gagal', 'msg' => 'Akun ' . $akun[0]->kode_akun . ' sudah dipakai di ' . $n . ' transaksi/saldo awal. Nonaktifkan saja akun ini (Edit > Status: Nonaktif).'));
        }
        $ok = $this->Mod_account->deleteAkun($id) > 0;
        $this->json(array('status' => $ok ? 'ok' : 'gagal', 'msg' => $ok ? 'Akun dihapus' : 'Gagal menghapus akun'));
    }
}
