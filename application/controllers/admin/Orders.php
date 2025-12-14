<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Orders extends Admin_Controller {
		
		public function __construct()
		{
			parent::__construct();
			$this->load->model('Order_model');
			$this->load->model('Settings_model');
		}
		
		public function index()
		{
			$data['title'] = "Orders";
			
			$this->render('admin/orders/index', $data);
		}
		
		// Endpoint DataTables AJAX server-side
		public function ajax_list()
		{
			// Ambil filter tanggal dari request POST
			$start_date = $this->input->post('start_date');
			$end_date   = $this->input->post('end_date');
			$status     = $this->input->post('status'); // Tambahkan ini
			$no = $this->input->post('start');
			$list = $this->Order_model->get_datatables($start_date, $end_date, $status); // Update parameter
			$data = [];
			
			foreach ($list as $order) {
				$no++;
				$items = json_decode($order->items, true);
				$items_text = '';
				if ($items && is_array($items)) {
					foreach ($items as $i) {
						$items_text .= "{$i['name']} ({$i['quantity']}x), ";
					}
					$items_text = rtrim($items_text, ', ');
					} else {
					$items_text = '-';
				}
				
				$row = [];
				$row[] = $no;
				$row[] = htmlspecialchars($order->name);
				$row[] = htmlspecialchars($order->location);
				$row[] = $items_text;
				$row[] = number_format($order->total);
				$row[] = number_format($order->fee);
				$row[] = $order->status_pembayaran;
				$row[] = date('d M Y H:i', strtotime($order->created_at));
				
				$data[] = $row;
			}
			
			$output = [
            "draw" => intval($this->input->post('draw')),
            "recordsTotal" => $this->Order_model->count_all($start_date, $end_date),
            "recordsFiltered" => $this->Order_model->count_filtered($start_date, $end_date),
            "data" => $data,
			];
			
			$this->output->set_content_type('application/json')->set_output(json_encode($output));
		}
		// Tambahkan metode-metode ini di class Orders
		
		public function detail($id)
		{
			$order = $this->Order_model->get_by_id($id);
			if (!$order) {
				show_404();
			}
			
			$data = [
			'title' => 'Detail Order #' . $id,
			'order' => $order,
			'settings' => $this->Settings_model->get()
			];
			
			$this->render('admin/orders/detail', $data);
		}
		
		public function update_status()
		{
			if (!$this->input->is_ajax_request()) {
				show_404();
			}
			
			$id = $this->input->post('id');
			$status = $this->input->post('status');
			$payment_method = $this->input->post('payment_method');
			
			if (!$id || !$status) {
				$this->output->set_content_type('application/json')
				->set_output(json_encode([
                'success' => false,
                'message' => 'Data tidak lengkap'
				]));
				return;
			}
			
			$result = $this->Order_model->update_status($id, $status, $payment_method);
			
			if ($result) {
				$this->output->set_content_type('application/json')
				->set_output(json_encode([
                'success' => true,
                'message' => 'Status berhasil diperbarui'
				]));
				} else {
				$this->output->set_content_type('application/json')
				->set_output(json_encode([
                'success' => false,
                'message' => 'Gagal memperbarui status'
				]));
			}
		}
		
		public function print_detail($id)
		{
			$order = $this->Order_model->get_by_id($id);
			if (!$order) {
				show_404();
			}
			
			$data = [
			'title' => 'Struk Order #' . $id,
			'order' => $order,
			'settings' => $this->Settings_model->get()
			];
			
			$this->load->view('admin/orders/print_detail', $data);
		}
		public function print()
		{
			
			$start_date = $this->input->get('start_date');
			$end_date = $this->input->get('end_date');
			
			$orders = $this->Order_model->get_filtered($start_date, $end_date);
			
			$data = [
			'title' => 'Laporan Pesanan',
			'start_date' => $this->input->get('start_date'),
			'end_date' => $this->input->get('end_date'),
			'orders' => $orders,
			'settings' => $this->Settings_model->get()
			];
			
			
			$this->load->view('admin/orders/print', $data);
		}
		public function summary()
		{
			$start_date = $this->input->post('start_date');
			$end_date = $this->input->post('end_date');
			$status = $this->input->post('status'); // Tambahkan ini
			
			$res = $this->Order_model->get_summary($start_date, $end_date, $status); 
			
			$this->output
			->set_content_type('application/json')
			->set_output(json_encode([
            'total_order' => $res->total_order ?? 0,
            'total_admin_fee' => $res->total_admin_fee ?? 0,
			]));
		}
		
	}
