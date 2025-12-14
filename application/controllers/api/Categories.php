<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Categories extends CI_Controller {
		
		public function __construct()
		{
			parent::__construct();
			$this->load->model('Category_model');
		}
		
		public function index()
		{
			$data = $this->Category_model->get_all();
			
			echo json_encode([
            'status' => true,
            'data' => $data
			]);
		}
	}
