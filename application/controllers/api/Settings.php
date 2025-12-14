<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Settings extends CI_Controller {
		public function __construct(){
			parent::__construct();
			$this->load->model('Settings_model');
			$this->load->config('api');
		}
		
		private function _auth(){
			$key = $this->input->get_request_header('X-API-KEY', TRUE);
			if(!$key) $key = $this->input->get('api_key');
			return $key === $this->config->item('api_key');
		}
		
		public function index(){
			if(!$this->_auth()){
				$this->output->set_status_header(401)->set_output(json_encode(['error'=>'Unauthorized']));
				return;
			}
			$products = $this->Settings_model->get();
			$this->output->set_content_type('application/json')->set_output(json_encode($products));
		}
	}
