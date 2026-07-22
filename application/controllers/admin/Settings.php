<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Settings extends Admin_Controller {
		public function __construct(){
			parent::__construct();
			$this->load->model('Settings_model');
			$this->load->library('upload');
			$this->load->library('form_validation');
		}
		
		public function index(){
			$s = $this->Settings_model->get();
			$this->render('admin/settings/index', ['settings'=>$s, 'title'=>'Pengaturan']);
		}
		
		public function update(){
			if ($this->input->method(TRUE) !== 'POST') {
				redirect('admin/settings');
				return;
			}

			log_message('error', 'Settings update started');

			$this->form_validation->set_rules('app_name', 'Nama Website', 'trim|required');
			$this->form_validation->set_rules('wa_number', 'Nomor WhatsApp', 'trim|required');
			$this->form_validation->set_rules('site_desc', 'Deskripsi situs', 'trim');
			$this->form_validation->set_rules('site_key', 'Keywords situs', 'trim');

			if ($this->form_validation->run() === FALSE) {
				$this->session->set_flashdata('error', validation_errors('', ''));
				redirect('admin/settings');
				return;
			}

			$data = [
				'app_name' => $this->input->post('app_name', TRUE),
				'site_desc' => $this->input->post('site_desc', TRUE),
				'site_key' => $this->input->post('site_key', TRUE),
				'wa_number' => $this->input->post('wa_number', TRUE)
			];
			
			// Upload app_logo
			if($this->has_upload('app_logo')){
				$logo_path = $this->upload_file('app_logo');
				if($logo_path === false){
					redirect('admin/settings');
					return;
				}
				$data['app_logo'] = $logo_path;
			}
			
			// Upload app_icon
			if($this->has_upload('app_icon')){
				$icon_path = $this->upload_file('app_icon', 'icon');
				if($icon_path === false){
					redirect('admin/settings');
					return;
				}
				$data['app_icon'] = $icon_path;
			}
			
			$result = $this->Settings_model->update_app($data);
			log_message('error', 'Settings update result: ' . var_export($result, true));
			
			if ($result) {
				$this->session->set_flashdata('success', 'Pengaturan berhasil diperbarui!');
			} else {
				$this->session->set_flashdata('error', 'Pengaturan gagal disimpan. Periksa log aplikasi untuk detail.');
			}
			redirect('admin/settings');
		}

		private function has_upload($field_name){
			return isset($_FILES[$field_name])
				&& isset($_FILES[$field_name]['error'])
				&& $_FILES[$field_name]['error'] !== UPLOAD_ERR_NO_FILE
				&& !empty($_FILES[$field_name]['name']);
		}
		
		/**
			* Helper function untuk upload file
			* @param string $field_name Nama field file
			* @param string $type 'logo' atau 'icon'
			* @return string|bool Path file atau false jika gagal
		*/
		private function upload_file($field_name, $type = 'logo'){
			$upload_path = FCPATH . 'uploads' . DIRECTORY_SEPARATOR;
			if(!is_dir($upload_path)){
				mkdir($upload_path, 0755, true);
			}
			if(!is_writable($upload_path)){
				$this->session->set_flashdata('error', 'Folder uploads tidak writable.');
				log_message('error', 'Settings upload path is not writable: ' . $upload_path);
				return false;
			}
			
			$config['upload_path'] = $upload_path;
			$config['allowed_types'] = ($type == 'icon') ? 'ico|png|jpg|jpeg|webp' : 'png|jpg|jpeg|webp';
			$config['max_size'] = ($type == 'icon') ? 1024 : 2048; // 1MB untuk icon, 2MB untuk logo
			$config['encrypt_name'] = TRUE;
			$config['overwrite'] = FALSE;
			$config['file_ext_tolower'] = TRUE;
			$config['detect_mime'] = TRUE;
			$config['mod_mime_fix'] = TRUE;
			
			$this->upload->initialize($config);
			
			if ($this->upload->do_upload($field_name)) {
				$upload_data = $this->upload->data();
				
				// Resize untuk logo jika perlu
				if($type == 'logo'){
					$this->resize_image($upload_data['full_path'], 200, 60);
				} 
				// Resize untuk icon jika perlu
				elseif($type == 'icon' && strtolower($upload_data['file_ext']) !== '.ico'){
					$this->resize_image($upload_data['full_path'], 64, 64);
				}
				
				return 'uploads/' . $upload_data['file_name'];
				} else {
				// Tampilkan error upload
				$error = $this->upload->display_errors();
				$this->session->set_flashdata('error', 'Gagal upload ' . $type . ': ' . $error);
				log_message('error', 'Settings upload failed for ' . $field_name . ': ' . strip_tags($error));
				return false;
			}
		}
		
		/**
			* Resize image jika perlu
		*/
		private function resize_image($file_path, $width, $height){
			$config['image_library'] = 'gd2';
			$config['source_image'] = $file_path;
			$config['maintain_ratio'] = TRUE;
			$config['width'] = $width;
			$config['height'] = $height;
			$config['quality'] = '90%';
			
			$this->load->library('image_lib', $config);
			$this->image_lib->initialize($config);

			// Supresi warning libpng (iCCP sRGB profile) yang tidak berbahaya
			set_error_handler(function ($severity, $message) {
				if (strpos($message, 'libpng warning') !== false) {
					return true;
				}
				return false;
			}, E_WARNING);
			
			$result = $this->image_lib->resize();
			
			restore_error_handler();

			if (!$result) {
				log_message('error', 'Image resize failed: ' . $this->image_lib->display_errors());
			}
			
			$this->image_lib->clear();
		}
	}
