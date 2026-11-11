<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Service Appointment — pendaftaran kendaraan yang akan diservis (status N pada tbl_after_sales).
 * Alur tidak berubah: Appointment → Pre Order → Work Order. Nomor WO tetap dibuat saat appointment disimpan.
 */
class ServiceAppointment extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model(array('service/Mod_service_appointment', 'Mod_menu'));
		$this->load->model(array('Mod_userlevel'));
		$this->load->helper('tgl_indo_helper');
		$this->load->model('Mod_aplikasi');
	}

	public function index()
	{
		$data['page']  = 'Service Appointment';
		$data['judul'] = 'After Sales';
		$data['dataCus'] = $this->Mod_service_appointment->select_customer();
		$data['ringkas'] = $this->Mod_service_appointment->ringkasan();
		$data['akses'] = $this->hak_akses();
		$data['form_id'] = 'form-tambah-appointment';
		$data['r'] = null;
		echo show_my_modal('service/modals/modal_tambah_appointment', 'tambah-appointment', $data, ' modal-xl');
		$this->template->load('layoutbackend', 'service/service_appointment', $data);
	}

	private static function e($s) { return html_escape((string) $s); }
	private static function tgl($t) { return $t && substr((string) $t, 0, 4) !== '0000' ? date('d/m/Y', strtotime($t)) : '-'; }

	public function ajax_list()
	{
		$a = $this->hak_akses();
		$list = $this->Mod_service_appointment->get_datatables();
		$jenis = array('C' => array('Cash', 'secondary'), 'I' => array('Internal', 'info'), 'W' => array('Warranty', 'primary'), 'GW' => array('Goodwill', 'warning'));
		$data = array(); $no = (int) $_POST['start']; $hari = date('Y-m-d');
		foreach ($list as $p) {
			$no++;
			$j = isset($jenis[$p->status_service]) ? $jenis[$p->status_service] : array($p->status_service ?: '-', 'light');
			$lewat = $p->dead_line && substr($p->dead_line, 0, 4) !== '0000' && $p->dead_line < $hari;
			$aksi = '<a class="btn btn-xs btn-outline-secondary" target="_blank" title="Cetak slip appointment" href="' . site_url('ServiceAppointment/cetak/' . $p->id) . '"><i class="fas fa-print"></i></a> ';
			if ($a->edit_level === 'Y') $aksi .= '<button class="btn btn-xs btn-outline-success update-appointment" title="Ubah" data-id="' . $p->id . '"><i class="fas fa-edit"></i></button> ';
			if ($a->delete_level === 'Y' && trim((string) $p->pre_order) === '') $aksi .= '<button class="btn btn-xs btn-outline-danger delete-appointment" title="Hapus" data-toggle="modal" data-target="#hapusAppointment" data-id="' . $p->id . '"><i class="fas fa-trash"></i></button>';
			$data[] = array(
				$no,
				'<b>' . self::e($p->wo_no) . '</b>',
				self::tgl($p->date_open_wo) . '<div class="small text-muted">' . self::e(substr((string) $p->clockin, 0, 5)) . '</div>',
				'<b>' . self::e($p->customer_name) . '</b><div class="small text-muted">' . self::e($p->customer) . '</div>',
				'<b>' . self::e($p->no_pol) . '</b><div class="small text-muted">' . self::e($p->type) . '</div><div class="small text-muted">VIN ' . self::e($p->vin) . '</div>',
				'<div style="white-space:normal;min-width:180px">' . self::e($p->customer_complain) . '</div>' . ($p->remarks !== '' && $p->remarks !== null ? '<div class="small text-muted">' . self::e($p->remarks) . '</div>' : ''),
				self::e($p->sa_name),
				'<span class="badge badge-' . ($p->free_service === 'Y' ? 'success' : 'light border') . '">' . ($p->free_service === 'Y' ? 'Free service' : 'Non free') . '</span> <span class="badge badge-' . $j[1] . '">' . self::e($j[0]) . '</span>' . ($p->storing === 'Y' ? ' <span class="badge badge-danger">Storing</span>' : ''),
				'<span class="' . ($lewat ? 'text-danger font-weight-bold' : '') . '">' . self::tgl($p->dead_line) . '</span><div class="small text-muted">' . self::e(substr((string) $p->dead_time, 0, 5)) . '</div>',
				trim((string) $p->pre_order) !== '' ? '<span class="badge badge-success">Pre Order</span><div class="small text-muted">' . self::e($p->pre_order) . '</div>' : '<span class="badge badge-warning">Menunggu pre order</span>',
				'<div class="text-nowrap">' . $aksi . '</div>',
			);
		}
		echo json_encode(array(
			'draw' => $_POST['draw'],
			'recordsTotal' => $this->Mod_service_appointment->count_all(),
			'recordsFiltered' => $this->Mod_service_appointment->count_filtered(),
			'data' => $data,
		));
	}

	/** kendaraan customer dari riwayat service (JSON) */
	public function kendaraan()
	{
		$k = explode('|', (string) $this->input->get('customer'));
		$o = array();
		foreach ($this->Mod_service_appointment->kendaraan($k[0]) as $r) {
			foreach ($r as $f => $x) $r->$f = trim((string) $x);
			$kunci = strtoupper($r->vin . '|' . $r->no_pol);
			if ( ! isset($o[$kunci])) $o[$kunci] = $r;
		}
		$this->json(array_values($o));
	}

	public function prosesTappointment()
	{
		$this->wajib('add');
		$this->form_validation->set_rules('customer', 'Customer', 'trim|required');
		$this->form_validation->set_rules('date_open_wo', 'Tanggal', 'trim|required');
		$data = $this->input->post();
		if ($this->form_validation->run() == TRUE) {
			$result = $this->Mod_service_appointment->insertAppointment($data);
			$out = array('status' => '', 'msg' => $result > 0 ? 'Appointment tersimpan' : 'Gagal menyimpan');
		} else {
			$out = array('status' => 'form', 'msg' => strip_tags(validation_errors()));
		}
		echo json_encode($out);
	}

	public function updateAppointment()
	{
		$r = $this->Mod_service_appointment->get((int) $this->input->post('id'));
		if ( ! $r) { echo '<div class="alert alert-danger">Data tidak ditemukan.</div>'; return; }
		$data = array('apl' => $this->db->get('aplikasi')->row(), 'dataCus' => $this->Mod_service_appointment->select_customer(), 'r' => $r, 'form_id' => 'form-update-appointment');
		echo show_my_modal('service/modals/modal_tambah_appointment', 'update-appointment', $data, ' modal-xl');
	}

	public function prosesUappointment()
	{
		$this->wajib('edit');
		$this->form_validation->set_rules('id', 'ID', 'trim|required');
		$data = $this->input->post();
		if ($this->form_validation->run() == TRUE) {
			$result = $this->Mod_service_appointment->updateAppointment($data);
			$out = array('status' => $result > 0 ? '' : 'form', 'msg' => $result > 0 ? 'Data berhasil diubah' : 'Data tidak dapat diubah (sudah diproses Pre Order / WO)');
		} else {
			$out = array('status' => 'form', 'msg' => strip_tags(validation_errors()));
		}
		echo json_encode($out);
	}

	public function deleteAppointment()
	{
		$this->wajib('delete');
		$r = $this->Mod_service_appointment->get((int) $this->input->post('id'));
		if ($r && trim((string) $r->pre_order) !== '') { echo json_encode(array('status' => 'form', 'msg' => 'Appointment sudah dibuat Pre Order, tidak dapat dihapus.')); return; }
		$result = $this->Mod_service_appointment->deleteAppointment((int) $this->input->post('id'));
		echo json_encode(array('status' => $result > 0 ? '' : 'form', 'msg' => $result > 0 ? 'Data dihapus' : 'Gagal menghapus'));
	}

	/** slip appointment (A5) untuk pelanggan / bengkel */
	public function cetak($id = 0)
	{
		$r = $this->Mod_service_appointment->get((int) $id);
		if ( ! $r) show_404();
		$cus = $this->db->get_where('tbl_customer', array('kode_cus' => $r->customer))->row();
		$this->load->view('service/cetak_appointment', array('r' => $r, 'cus' => $cus, 'apl' => $this->db->get('aplikasi', 1)->row()));
	}
}
