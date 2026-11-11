<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Impor / ekspor master barang (Excel .xlsx / CSV) dengan pratinjau sebelum disimpan. */
class ImportBarang extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('warehouse/Mod_impor', 'warehouse/Mod_lapgudang'));
        $this->wajib('view');
    }

    private function lok()
    {
        $l = (string) ($this->input->post('lok') ?: $this->input->get('lok'));
        if (isset(Mod_lapgudang::$LOKASI[$l])) return $l;
        $s = Mod_lapgudang::lok((string) $this->session->userdata('lokasi'));
        return $s ?: 'cbt';
    }

    public function index($tok = '')
    {
        $A = $tok ? $this->Mod_impor->ambil_cache($tok) : null;
        $data = array('page' => 'Impor Barang dari Excel', 'judul' => 'Gudang', 'lok' => $A ? $A['lok'] : $this->lok(), 'A' => $A, 'tok' => $A ? $tok : '',
            'riwayat' => $this->Mod_impor->riwayat(), 'bisa' => $this->boleh('add') || $this->boleh('upload') || $this->boleh('edit'),
            'f' => (string) $this->input->get('f'));
        $this->template->load('layoutbackend', 'warehouse/impor/index', $data);
    }

    /** template kosong (?semua=1 → seluruh barang untuk diedit lalu diimpor ulang) */
    public function template()
    {
        $semua = (bool) $this->input->get('semua');
        if ($semua) $this->wajib('print');
        $lok = $this->lok();
        $this->load->library('xlsx');
        $lebar = array(); foreach (Mod_impor::$KOLOM as $k) $lebar[] = $k[3];
        $bin = $this->xlsx->tulis($this->Mod_impor->template_baris($lok, $semua), 'Barang ' . strtoupper($lok), $lebar);
        if ($bin === false) show_error($this->xlsx->error);
        $nama = $semua ? 'Master_barang_' . strtoupper($lok) . '_' . date('Ymd') . '.xlsx' : 'Template_impor_barang.xlsx';
        $this->output->set_content_type('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->set_header('Content-Disposition: attachment; filename="' . $nama . '"')->set_output($bin);
    }

    public function unggah()
    {
        if ( ! $this->boleh('add') && ! $this->boleh('upload') && ! $this->boleh('edit')) $this->wajib('add');
        $mode = (string) $this->input->post('mode'); if ( ! isset(Mod_impor::$MODE[$mode])) $mode = 'SEMUA';
        if (empty($_FILES['file']['tmp_name']) || ! is_uploaded_file($_FILES['file']['tmp_name'])) { $this->session->set_flashdata('wh_msg', array('danger', 'Pilih file .xlsx atau .csv.')); redirect('ImportBarang'); }
        if ($_FILES['file']['size'] > 8 * 1024 * 1024) { $this->session->set_flashdata('wh_msg', array('danger', 'Ukuran file maksimal 8 MB.')); redirect('ImportBarang'); }
        $this->load->library('xlsx');
        $raw = $this->xlsx->baca($_FILES['file']['tmp_name'], $_FILES['file']['name']);
        if ($raw === false) { $this->session->set_flashdata('wh_msg', array('danger', $this->xlsx->error)); redirect('ImportBarang'); }
        $A = $this->Mod_impor->analisa($raw, $mode, $this->lok());
        if ($A === false) { $this->session->set_flashdata('wh_msg', array('danger', $this->Mod_impor->error)); redirect('ImportBarang'); }
        $A['file'] = $_FILES['file']['name'];
        redirect('ImportBarang/index/' . $this->Mod_impor->simpan_cache($A));
    }

    public function proses($tok = '')
    {
        if ( ! $this->boleh('add') && ! $this->boleh('upload') && ! $this->boleh('edit')) $this->wajib('add');
        $A = $this->Mod_impor->ambil_cache($tok);
        if ( ! $A) { $this->session->set_flashdata('wh_msg', array('warning', 'Pratinjau sudah kedaluwarsa, unggah ulang file.')); redirect('ImportBarang'); }
        $r = $this->Mod_impor->proses($A, isset($A['file']) ? $A['file'] : '');
        if ($r === false) { $this->session->set_flashdata('wh_msg', array('danger', $this->Mod_impor->error)); redirect('ImportBarang/index/' . $tok); }
        $this->Mod_impor->hapus_cache($tok);
        $this->session->set_flashdata('wh_msg', array('success', 'Impor selesai (' . $r['no_dok'] . '): ' . nf($r['baru']) . ' barang baru, ' . nf($r['update']) . ' diperbarui, ' . nf($r['harga']) . ' perubahan harga dicatat di log harga, ' . nf($r['stok']) . ' stok awal dicatat.'));
        redirect('ImportBarang');
    }

    /** unduh daftar baris bermasalah dari pratinjau */
    public function error($tok = '')
    {
        $A = $this->Mod_impor->ambil_cache($tok);
        if ( ! $A) show_404();
        $rows = array(array('baris', 'no_part', 'nama_part', 'masalah'));
        foreach ($A['rows'] as $r) if ($r['status'] === 'ERROR') $rows[] = array($r['baris'], (string) $r['v']['no_part'], isset($r['v']['nama_part']) ? (string) $r['v']['nama_part'] : '', implode('; ', $r['err']));
        $this->load->library('xlsx');
        $this->output->set_content_type('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->set_header('Content-Disposition: attachment; filename="Baris_error_impor.xlsx"')->set_output($this->xlsx->tulis($rows, 'Error', array(8, 22, 34, 60)));
    }
}
