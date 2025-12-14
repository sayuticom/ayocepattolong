<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Orders extends CI_Controller {
		
		public function __construct() {
			parent::__construct();
			$this->load->model('Order_model');
			$this->load->config('api');
			$this->load->helper('security');
		}
		
		private function _auth() {
			$key = $this->input->get_request_header('X-API-KEY', TRUE);
			if (!$key) $key = $this->input->get('api_key');
			return $key === $this->config->item('api_key');
		}
		
		public function index() {
			if (!$this->_auth()) {
				$this->output->set_status_header(401)->set_output(json_encode(['error' => 'Unauthorized']));
				return;
			}
			
			$orders = $this->Order_model->get_all();
			$this->output->set_content_type('application/json')->set_output(json_encode($orders));
		}
		
		public function save_order() {
			if (!$this->_auth()) {
				$this->output->set_status_header(401)->set_output(json_encode(['error' => 'Unauthorized']));
				return;
			}
			
			$input = json_decode(trim(file_get_contents('php://input')), true);
			if (!$input) {
				$this->output->set_status_header(400)->set_output(json_encode(['error' => 'Invalid input']));
				return;
			}
			
			// Validasi sederhana (boleh tambah validasi lain)
			if (empty($input['name']) || empty($input['location']) || empty($input['items'])) {
				$this->output->set_status_header(400)->set_output(json_encode(['error' => 'Missing required fields']));
				return;
			}
			
			// Bersihkan data untuk keamanan
			$data = [
            'name' => $this->security->xss_clean($input['name']),
            'location' => $this->security->xss_clean($input['location']),
            'total' => (int)$input['total'],
            'fee' => (int)$input['fee'],
            'items' => json_encode($input['items']),
			];
			
			$order_id = $this->Order_model->save_order($data);
			
			if ($order_id) {
				$this->output->set_content_type('application/json')->set_output(json_encode([
                'status' => 'success',
                'order_id' => $order_id
				]));
				} else {
				$this->output->set_status_header(500)->set_output(json_encode([
                'status' => 'error',
                'message' => 'Failed to save order'
				]));
			}
		}
	}
