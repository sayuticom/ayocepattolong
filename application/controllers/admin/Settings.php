<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Settings extends Admin_Controller {
		public function __construct(){
			parent::__construct();
			$this->load->model('Settings_model');
			$this->load->library('upload');
		}
		
		public function index(){
			$s = $this->Settings_model->get();
			$this->render('admin/settings/index', ['settings'=>$s, 'title'=>'Pengaturan']);
		}
		
		public function update(){
			$data = [
            'app_name' => $this->input->post('app_name'),
            'site_desc' => $this->input->post('site_desc'),
            'site_key' => $this->input->post('site_key'),
            'wa_number' => $this->input->post('wa_number')
			];
			
			// Upload app_logo
			if(!empty($_FILES['app_logo']['name'])){
				$logo_path = $this->upload_file('app_logo');
				if($logo_path){
					// Hapus file logo lama jika ada
					$old_logo = $this->Settings_model->get_logo_path();
					if($old_logo && file_exists($old_logo)){
						unlink($old_logo);
					}
					$data['app_logo'] = $logo_path;
				}
			}
			
			// Upload app_icon
			if(!empty($_FILES['app_icon']['name'])){
				$icon_path = $this->upload_file('app_icon', 'icon');
				if($icon_path){
					// Hapus file icon lama jika ada
					$old_icon = $this->Settings_model->get_icon_path();
					if($old_icon && file_exists($old_icon)){
						unlink($old_icon);
					}
					$data['app_icon'] = $icon_path;
				}
			}
			
			$this->Settings_model->update($data);
			
			$this->session->set_flashdata('success', 'Pengaturan berhasil diperbarui!');
			redirect('admin/settings');
		}
		
		/**
			* Helper function untuk upload file
			* @param string $field_name Nama field file
			* @param string $type 'logo' atau 'icon'
			* @return string|bool Path file atau false jika gagal
		*/
		private function upload_file($field_name, $type = 'logo'){
			$upload_path = './uploads/';
			if(!is_dir($upload_path)){
				mkdir($upload_path, 0755, true);
			}
			
			$config['upload_path'] = $upload_path;
			$config['allowed_types'] = ($type == 'icon') ? 'ico|png|jpg|jpeg' : 'png|jpg|jpeg';
			$config['max_size'] = ($type == 'icon') ? 1024 : 2048; // 1MB untuk icon, 2MB untuk logo
			$config['encrypt_name'] = TRUE;
			$config['overwrite'] = FALSE;
			
			$this->upload->initialize($config);
			
			if ($this->upload->do_upload($field_name)) {
				$upload_data = $this->upload->data();
				
				// Resize untuk logo jika perlu
				if($type == 'logo'){
					$this->resize_image($upload_data['full_path'], 200, 60);
				} 
				// Resize untuk icon jika perlu
				elseif($type == 'icon'){
					$this->resize_image($upload_data['full_path'], 64, 64);
				}
				
				return 'uploads/' . $upload_data['file_name'];
				} else {
				// Tampilkan error upload
				$error = $this->upload->display_errors();
				$this->session->set_flashdata('error', 'Gagal upload ' . $type . ': ' . $error);
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
			
			if (!$this->image_lib->resize()) {
				// Log error resize, tapi jangan gagalkan proses
				log_message('error', 'Image resize failed: ' . $this->image_lib->display_errors());
			}
			
			$this->image_lib->clear();
		}
	}	