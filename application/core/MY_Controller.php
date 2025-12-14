<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class MY_Controller extends CI_Controller
	{
		protected $settings;
		
		public function __construct()
		{
			parent::__construct();
			
			// Load settings model untuk semua controller
			$this->load->model('Settings_model');
			
			// Ambil data settings
			$this->settings = $this->Settings_model->get();
			
			// Simpan ke data untuk view
			$this->data['settings'] = $this->settings;
		}
		
		public function render($view, $data = [])
		{
			// Merge data dari controller dengan data settings
			$data = array_merge($this->data, $data);
			
			$data['content'] = $this->load->view($view, $data, TRUE);
			$this->load->view('layout/main', $data);
		}
	}
	
	class Admin_Controller extends MY_Controller
	{
		public function __construct()
		{
			parent::__construct();
			
			if (!$this->session->userdata('logged_in')) {
				redirect('auth/login');
			}
			
			// Tambahan untuk admin bisa ditambahkan di sini
		}
	}	