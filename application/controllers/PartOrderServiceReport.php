<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Shared\Date;
/* ------------ use PhpOffice\PhpSpreadsheet\Style\NumberFormat; ------------ */

class PartOrderServiceReport extends MY_Controller
{

    public $Mod_part_order_service_report;
    public $Mod_menu;
    public $Mod_userlevel;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('service/Mod_part_order_service_report', 'Mod_menu'));
        $this->load->model(array('Mod_userlevel'));
        $this->load->helper('tgl_indo_helper');
    }

    public function index()
    {
        $data['page']         = "Work Order Report";
        $data['judul']         = "List Service";
        $this->load->helper('url');
        $data['menu'] = $this->Mod_menu->getAll()->result();
        $this->template->load('layoutbackend', 'service/report_service/part_order_service_report', $data);
    }

    public function listService()
    {
        $date1                 = $_GET['date1'];
        $date2                 = $_GET['date2'];
        $tgl1 = explode('-', $date1);
        $ttmp1 = $tgl1[2] . "-" . $tgl1[1] . "-" . $tgl1[0] . "";

        $tgl2 = explode('-', $date2);
        $ttmp2 = $tgl2[2] . "-" . $tgl2[1] . "-" . $tgl2[0] . "";
        $data['dataPo'] = $this->Mod_part_order_service_report->cari_service($ttmp1, $ttmp2);
        $this->load->view('service/report_service/data_part_order_service_report', $data);
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
        $data = $this->Mod_part_order_service_report->cari_service($ttmp1, $ttmp2);
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
