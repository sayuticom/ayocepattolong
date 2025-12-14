<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Home extends CI_Controller {
		
		public function __construct()
		{
			parent::__construct();
			$this->load->helper(['url','form']);
			$this->load->library('session');
			$this->load->model('Relawan_model');
			$this->load->model('Informasi_model');
			$this->load->model('Slider_model');
		}
		public function index()
		{
			
			$data['info'] = $this->Informasi_model->get_info(3);
			$data['slider'] = $this->Slider_model->get_info(6);
			$this->load->view('frontend',$data);
		}
		
		// Proses Submit Form Relawan
		public function daftar()
		{
			$nama   = $this->input->post('nama');
			$telp   = $this->input->post('telp');
			$alamat = $this->input->post('alamat');
			
			$this->Relawan_model->insert([
            'nama'      => $nama,
            'telepon'   => $telp,
            'alamat'    => $alamat,
            'created_at'=> date('Y-m-d H:i:s')
			]);
			
			$this->session->set_flashdata('sukses', 'Pendaftaran relawan berhasil dikirim!');
			redirect('home');
		}
		
		
		
	}
