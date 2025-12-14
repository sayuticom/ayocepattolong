<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Register extends CI_Controller {
		public function __construct(){
			parent::__construct();
			$this->load->model('Settings_model');
			$this->load->model('Relawan_model');
			$this->load->config('api');
			$this->load->helper('security');
		}
		
		private function _auth(){
			$key = $this->input->get_request_header('X-API-KEY', TRUE);
			if(!$key) $key = $this->input->get('api_key');
			return $key === $this->config->item('api_key');
		}
		
		public function index() {
			if (!$this->_auth()) {
				return $this->output->set_status_header(401)
				->set_output(json_encode(['error' => 'Unauthorized']));
			}
			
			$input = json_decode(file_get_contents("php://input"), true);
			
			if (!$input) {
				return $this->output->set_status_header(400)
				->set_output(json_encode(['error' => 'Invalid JSON']));
			}
			
			// ⛔ reCAPTCHA
			if (empty($input['recaptcha_token']) || !$this->_validate_recaptcha($input['recaptcha_token'])) {
				return $this->output->set_status_header(400)
				->set_output(json_encode(['error' => 'reCAPTCHA failed']));
			}
			
			// Validasi
			if (empty($input['nama']) || empty($input['telp'])) {
				return $this->output->set_status_header(400)
				->set_output(json_encode(['error' => 'Missing fields']));
			}
			
			$data = [
			'nama'  => $this->security->xss_clean($input['nama']),
			'telepon' => $this->security->xss_clean($input['telp']),
			'alamat' => $this->security->xss_clean($input['alamat']),
			];
			
			$insert = $this->Relawan_model->insert($data);
			
			if ($insert) {
				echo json_encode([
				'status' => 'success',
				'message' => 'Berhasil mendaftar',
				'user_id' => $insert
				]);
				} else {
				$this->output->set_status_header(500)->set_output(json_encode([
				'status' => 'error',
				'message' => 'Gagal menyimpan data'
				]));
			}
		}
		
		private function _validate_recaptcha($token)
		{
			$secret = "6Ldc4SYsAAAAANqFfdfzDElIZx8_GsHKZa47GMhS";
			$url = "https://www.google.com/recaptcha/api/siteverify";
			
			$response = file_get_contents($url . "?secret=" . $secret . "&response=" . $token);
			$result = json_decode($response, true);
			
			// Bisa adjust score minimal (rekomendasi 0.5)
			if (!empty($result['success']) && $result['score'] >= 0.5) {
				return true;
			}
			
			return false;
		}
	}
