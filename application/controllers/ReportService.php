<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Shared\Date;
/* ------------ use PhpOffice\PhpSpreadsheet\Style\NumberFormat; ------------ */

class ReportService extends MY_Controller
{

    public $Mod_report_service;
    public $Mod_menu;
    public $Mod_userlevel;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('service/Mod_report_service', 'Mod_menu'));
        $this->load->model(array('Mod_userlevel'));
        $this->load->helper('tgl_indo_helper');
    }

    /* ================= Laporan service (tampilan baru) ================= */
    private function filter()
    {
        $this->load->model('service/Mod_lapservice');
        $d = tgl_sql((string) $this->input->get('dari')) ?: date('Y-m-01');
        $s = tgl_sql((string) $this->input->get('sampai')) ?: date('Y-m-d');
        if ($s < $d) { $t = $d; $d = $s; $s = $t; }
        $st = (string) $this->input->get('status', true); if ($st === null || ! in_array($st, array('', 'F', 'P', 'N'), true)) $st = 'F';
        if ($this->input->get('status') === null) $st = 'F';
        $ciw = (string) $this->input->get('ciw'); if ( ! isset(Mod_lapservice::$CIW[$ciw])) $ciw = '';
        return array('dari' => $d, 'sampai' => $s, 'status' => $st, 'sa' => trim((string) $this->input->get('sa')), 'ciw' => $ciw);
    }

    public function index()
    {
        $f = $this->filter();
        $rows = $this->Mod_lapservice->daftar($f);
        $data = array('page' => 'Services Report', 'judul' => 'After Sales Report', 'f' => $f, 'rows' => $rows, 'R' => $this->Mod_lapservice->ringkasan($rows),
            'sa_list' => $this->Mod_lapservice->sa_list(), 'boleh_cetak' => $this->boleh('print') || $this->boleh('view'));
        $this->template->load('layoutbackend', 'service/report_service/all_service', $data);
    }

    /** cetak daftar (A4 landscape) */
    public function cetak_daftar()
    {
        $f = $this->filter();
        $rows = $this->Mod_lapservice->daftar($f);
        $this->load->view('service/report_service/cetak_daftar', array('f' => $f, 'rows' => $rows, 'R' => $this->Mod_lapservice->ringkasan($rows), 'apl' => $this->Mod_lapservice->kop()));
    }

    /** ekspor Excel daftar */
    public function excel()
    {
        $f = $this->filter();
        $rows = $this->Mod_lapservice->daftar($f);
        $x = array(array('No', 'No. WO', 'Tgl masuk', 'Jam', 'Tgl selesai', 'Lama (hari)', 'Status', 'SA', 'Customer', 'No. polisi', 'Tipe', 'VIN', 'No. mesin', 'Km', 'Keluhan', 'Jenis (CIW/GW)', 'Free service', 'Mekanik', 'Jml operasi', 'Jam terjual', 'Nilai jasa', 'Nilai part (estimasi)', 'Total'));
        $i = 0;
        foreach ($rows as $r) $x[] = array(++$i, $r->wo_no, (string) $r->date_open_wo, substr((string) $r->clockin, 0, 5), Mod_lapservice::tgl_ok($r->date_close_wo) ? $r->date_close_wo : '', $r->hari === null ? '' : $r->hari,
            Mod_lapservice::$STATUS[$r->status] ?? $r->status, $r->sa_name, $r->customer_name, $r->no_pol, $r->type, trim((string) $r->vin), $r->engine_no, $r->mileage, $r->customer_complain,
            Mod_lapservice::$CIW[$r->status_service] ?? $r->status_service, $r->free_service === 'Y' ? 'Free' : 'Non free', $r->mekanik, $r->n_op, round($r->jam, 2), (float) $r->jasa, (float) $r->part, (float) ($r->jasa + $r->part));
        $this->load->library('xlsx');
        $bin = $this->xlsx->tulis($x, 'Services Report', array(5, 12, 11, 7, 11, 8, 11, 18, 30, 12, 16, 20, 16, 9, 40, 12, 10, 24, 9, 9, 14, 14, 14));
        if ($bin === false) show_error($this->xlsx->error);
        $this->output->set_content_type('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->set_header('Content-Disposition: attachment; filename="Services_Report_' . $f['dari'] . '_' . $f['sampai'] . '.xlsx"')->set_output($bin);
    }

    /** dokumen per WO: wo | preorder | estimasi | jobtime | workshop | ringkasan */
    public function cetak($jenis = 'wo', $wo = '')
    {
        $this->load->model('service/Mod_lapservice');
        $jenis = in_array($jenis, array('wo', 'preorder', 'estimasi', 'jobtime', 'workshop', 'ringkasan'), true) ? $jenis : 'wo';
        $r = $this->Mod_lapservice->wo(rawurldecode((string) $wo));
        if ( ! $r) show_error('Nomor WO tidak ditemukan.', 404, 'Tidak ditemukan');
        $this->load->view('service/report_service/cetak_dokumen', array('jenis' => $jenis, 'r' => $r, 'apl' => $this->Mod_lapservice->kop(),
            'oleh' => (string) $this->session->userdata('full_name')));
    }

    public function listService()
    {
        $date1                 = $_GET['date1'];
        $date2                 = $_GET['date2'];
        $tgl1 = explode('-', $date1);
        $ttmp1 = $tgl1[2] . "-" . $tgl1[1] . "-" . $tgl1[0] . "";

        $tgl2 = explode('-', $date2);
        $ttmp2 = $tgl2[2] . "-" . $tgl2[1] . "-" . $tgl2[0] . "";
        $data['dataPo'] = $this->Mod_report_service->cari_service($ttmp1, $ttmp2);
        $this->load->view('service/report_service/data_all_service', $data);
    }


    public function Detail()
    {
        $id                 = trim($_POST['id']);
        $data['dataPk'] = $this->Mod_partpk->cetak_pk($id);

        echo show_my_modal('warehouse/modals/modal_part_pk', 'part-pk', $data, ' modal-md');
    }
    public function showDetail()
    {
        $id                 = $_GET['id_keluar'];
        $data['dataDetail'] = $this->Mod_partpk->select_detail($id);
        $this->load->view('warehouse/detail_part_pk', $data);
    }
    public function detailPk()
    {

        $this->form_validation->set_rules('id_pk', 'ID PK', 'trim|required');

        $data     = $this->input->post();
        if ($this->form_validation->run() == TRUE) {
            $result = $this->Mod_partpk->detailpk($data);

            if ($result > 0) {
                $out['status'] = '';
                $out['msg'] = show_ok_msg('Success', '20px');
            } else {
                $out['status'] = '';
                $out['msg'] = show_del_msg('Filed!', '20px');
            }
        } else {
            $out['status'] = 'form';
            $out['msg'] = show_err_msg(validation_errors());
        }

        echo json_encode($out);
    }
    public function cetakJobtime()
    {
        $id                 = $_POST['id'];
        $data['dataJob'] = $this->Mod_report_service->cari_jobtime($id);
        //$data['detailJob'] = $this->Mod_report_service->select_detail($id);

        echo show_my_print('service/modals/modal_data_job_time', 'cetak-jobtime', $data, ' modal-xl');
    }
    public function cetakWorkshop()
    {
        $id                 = $_POST['id'];
        $data['dataJob'] = $this->Mod_report_service->cari_wo_jobtime($id);
        $data['detailJob'] = $this->Mod_report_service->detail_jobtime($id);

        echo show_my_print('service/modals/modal_data_wo_report', 'cetak-workshop', $data, ' modal-xl');
    }
    public function deletePo()
    {
        $id = $_POST['id'];
        $result = $this->Mod_reportwhpenawaran->deletePo($id);

        if ($result > 0) {
            //$out['datakode']=$kodeBaru;
            $out['status'] = '';
            $out['msg'] = show_del_msg('Deleted', '20px');
        } else {
            $out['status'] = '';
            $out['msg'] = show_err_msg('Filed !', '20px');
        }
        echo json_encode($out);
    }
    public function export_excel()
    {

        $date1                 = $_POST['date1'];
        $date2                 = $_POST['date2'];
        $tgl1 = explode('-', $date1);
        $ttmp1 = $tgl1[2] . "-" . $tgl1[1] . "-" . $tgl1[0] . "";

        $tgl2 = explode('-', $date2);
        $ttmp2 = $tgl2[2] . "-" . $tgl2[1] . "-" . $tgl2[0] . "";

        //$excelDate = Date::PHPToExcel(time());
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'SA');
        $sheet->setCellValue('C1', 'Date Open');
        $sheet->setCellValue('D1', 'VIN');
        $sheet->setCellValue('E1', 'Konsumen');
        $sheet->setCellValue('F1', 'Complain');
        $sheet->setCellValue('G1', 'Engine');
        $sheet->setCellValue('H1', 'Type');
        $sheet->setCellValue('I1', 'Last Service');
        $sheet->setCellValue('J1', 'Deadline');
        $sheet->setCellValue('K1', 'Date Close');
        $sheet->setCellValue('L1', 'Pembuat');

        $no = 1;
        $x = 2;
        $data= $this->Mod_report_service->cari_service($ttmp1, $ttmp2);
        foreach ($data as $row) {

            $sheet->setCellValue('A' . $x, $no++);
            $sheet->setCellValue('B' . $x, $row->wo_no);
            $sheet->setCellValue('C' . $x, $row->date_open_wo);
            $sheet->setCellValue('D' . $x, $row->vin);
            $sheet->setCellValue('E' . $x, $row->customer_name);
            $sheet->setCellValue('F' . $x, $row->customer_complain);
            $sheet->setCellValue('G' . $x, $row->engine_no);
            $sheet->setCellValue('H' . $x, $row->type);
            $sheet->setCellValue('I' . $x, $row->last_service_date);
            $sheet->setCellValue('J' . $x, $row->dead_line);
            $sheet->setCellValue('K' . $x, ($row->status == 'Y') ? 'Free' : 'Non Free');
            $sheet->setCellValue('L' . $x, $row->pembuat);
            $x++;

            $sheet->getStyle('C1')->getNumberFormat()->setFormatCode('dd-mm-yyyy');
        }
        $writer = new Xlsx($spreadsheet);
        $filename = 'Report SA';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }
}
