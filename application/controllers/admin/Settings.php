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
			$settings = $this->Settings_model->get();
			$new_hero_image = null;
			$delete_old_hero_after_save = false;
			
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

			if ($this->has_upload('hero_image') || $this->input->post('remove_hero_image') === '1') {
				if (!$this->Settings_model->has_app_field('hero_image')) {
					$this->session->set_flashdata('error', 'Kolom hero_image belum tersedia di database. Jalankan SQL migrasi terlebih dahulu.');
					redirect('admin/settings');
					return;
				}
			}

			if ($this->has_upload('hero_image')) {
				$hero_path = $this->upload_hero_image('hero_image');
				if ($hero_path === false) {
					redirect('admin/settings');
					return;
				}
				$data['hero_image'] = $hero_path;
				$new_hero_image = $hero_path;
				$delete_old_hero_after_save = !empty($settings->hero_image);
			} elseif ($this->input->post('remove_hero_image') === '1') {
				$data['hero_image'] = null;
				$delete_old_hero_after_save = !empty($settings->hero_image);
			}
			
			$result = $this->Settings_model->update_app($data);
			log_message('error', 'Settings update result: ' . var_export($result, true));
			
			if ($result) {
				if ($delete_old_hero_after_save) {
					$this->delete_hero_image_file($settings->hero_image);
				}
				$this->session->set_flashdata('success', 'Pengaturan berhasil diperbarui!');
			} else {
				if (!empty($new_hero_image)) {
					$this->delete_hero_image_file($new_hero_image);
				}
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
				
				if($type == 'logo'){
					$processed = $this->process_logo_image($upload_data['full_path']);
				} 
				elseif($type == 'icon' && strtolower($upload_data['file_ext']) !== '.ico'){
					$processed = $this->resize_image($upload_data['full_path'], 128, 128);
				} else {
					$processed = true;
				}

				if (!$processed) {
					if (is_file($upload_data['full_path'])) {
						@unlink($upload_data['full_path']);
					}
					$this->session->set_flashdata('error', 'Gagal memproses gambar ' . $type . '.');
					return false;
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

		private function upload_hero_image($field_name){
			if ($this->is_unsupported_heic($field_name)) {
				$this->session->set_flashdata('error', 'Format HEIC/HEIF belum didukung. Pilih JPG, PNG, atau WebP.');
				return false;
			}

			$ready = $this->ensure_hero_upload_path();
			if (!$ready['status']) {
				$this->session->set_flashdata('error', $ready['message']);
				return false;
			}

			$config = [
				'upload_path' => $ready['path'],
				'allowed_types' => 'jpg|jpeg|png|webp',
				'max_size' => 15360,
				'encrypt_name' => TRUE,
				'overwrite' => FALSE,
				'file_ext_tolower' => TRUE,
				'detect_mime' => TRUE,
				'mod_mime_fix' => TRUE,
			];

			$this->upload->initialize($config, TRUE);

			if (!$this->upload->do_upload($field_name)) {
				$error = strip_tags($this->upload->display_errors('', ''));
				$this->session->set_flashdata('error', 'Gagal upload gambar hero: ' . $error);
				log_message('error', 'Hero image upload failed: ' . $error);
				return false;
			}

			$upload_data = $this->upload->data();
			$processed = $this->process_hero_image($upload_data['full_path']);
			if (!$processed['status']) {
				if (is_file($upload_data['full_path'])) {
					@unlink($upload_data['full_path']);
				}
				$this->session->set_flashdata('error', $processed['message']);
				return false;
			}

			return 'uploads/hero/' . basename($upload_data['file_name']);
		}

		private function hero_upload_path(){
			$base_path = realpath(FCPATH);

			if ($base_path === false) {
				$base_path = rtrim(FCPATH, '/\\');
			}

			return $base_path
				. DIRECTORY_SEPARATOR
				. 'uploads'
				. DIRECTORY_SEPARATOR
				. 'hero'
				. DIRECTORY_SEPARATOR;
		}

		private function ensure_hero_upload_path(){
			$path = $this->hero_upload_path();
			if (!is_dir($path)) {
				if (!mkdir($path, 0775, TRUE) && !is_dir($path)) {
					return ['status' => false, 'message' => 'Folder upload hero tidak dapat dibuat.'];
				}
			}

			$real_path = realpath($path);
			if ($real_path === false || !is_dir($real_path)) {
				return ['status' => false, 'message' => 'Path upload hero tidak valid.'];
			}

			if (!is_writable($real_path)) {
				return ['status' => false, 'message' => 'Folder uploads/hero tidak writable.'];
			}

			return [
				'status' => true,
				'path' => $real_path . DIRECTORY_SEPARATOR,
			];
		}

		private function is_unsupported_heic($field_name){
			if (empty($_FILES[$field_name]['name'])) {
				return false;
			}

			$extension = strtolower(pathinfo((string) $_FILES[$field_name]['name'], PATHINFO_EXTENSION));
			return in_array($extension, ['heic', 'heif'], TRUE);
		}

		private function process_hero_image($file_path){
			$image_info = @getimagesize($file_path);
			if (!$image_info || empty($image_info['mime'])) {
				return ['status' => false, 'message' => 'File gambar hero tidak valid.'];
			}

			if (!in_array($image_info['mime'], ['image/jpeg', 'image/png', 'image/webp'], TRUE)) {
				return ['status' => false, 'message' => 'Format gambar hero tidak didukung. Pilih JPG, PNG, atau WebP.'];
			}

			if ($image_info['mime'] === 'image/jpeg') {
				$orientation = $this->fix_jpeg_orientation($file_path);
				if (!$orientation['status']) {
					return $orientation;
				}
				$image_info = @getimagesize($file_path);
				if (!$image_info || empty($image_info['mime'])) {
					return ['status' => false, 'message' => 'Gambar hero gagal dibaca setelah koreksi orientasi.'];
				}
			}

			if ((int) $image_info[0] > 1920 || (int) $image_info[1] > 1920) {
				if ($image_info['mime'] === 'image/webp' && !function_exists('imagecreatefromwebp')) {
					return ['status' => false, 'message' => 'Server belum mendukung optimasi WebP. Unggah JPG atau PNG.'];
				}

				if (!$this->resize_image($file_path, 1920, 1920, '82%')) {
					return ['status' => false, 'message' => 'Gambar hero gagal dikompresi.'];
				}
				clearstatcache(TRUE, $file_path);
			}

			$final_info = @getimagesize($file_path);
			if (!$final_info || empty($final_info['mime'])) {
				return ['status' => false, 'message' => 'Gambar hero gagal diproses setelah upload.'];
			}

			if (filesize($file_path) > 1572864) {
				$compressed = $this->recompress_image($file_path, $final_info['mime']);
				if (!$compressed['status']) {
					return $compressed;
				}
			}

			return ['status' => true];
		}

		private function fix_jpeg_orientation($file_path){
			if (!function_exists('exif_read_data')) {
				return ['status' => true];
			}

			$exif = @exif_read_data($file_path);
			if (empty($exif['Orientation'])) {
				return ['status' => true];
			}

			$angle = 0;
			if ((int) $exif['Orientation'] === 3) {
				$angle = 180;
			} elseif ((int) $exif['Orientation'] === 6) {
				$angle = -90;
			} elseif ((int) $exif['Orientation'] === 8) {
				$angle = 90;
			}

			if ($angle === 0) {
				return ['status' => true];
			}

			if (!function_exists('imagecreatefromjpeg')) {
				return ['status' => false, 'message' => 'Server belum mendukung koreksi orientasi JPEG.'];
			}

			$image = @imagecreatefromjpeg($file_path);
			if (!$image) {
				return ['status' => false, 'message' => 'Gambar JPEG gagal dibaca.'];
			}

			$rotated = imagerotate($image, $angle, 0);
			imagedestroy($image);
			if (!$rotated) {
				return ['status' => false, 'message' => 'Orientasi gambar hero gagal diperbaiki.'];
			}

			$saved = imagejpeg($rotated, $file_path, 90);
			imagedestroy($rotated);
			clearstatcache(TRUE, $file_path);

			return $saved ? ['status' => true] : ['status' => false, 'message' => 'Gambar JPEG gagal disimpan.'];
		}

		private function recompress_image($file_path, $mime){
			if ($mime === 'image/jpeg') {
				if (!function_exists('imagecreatefromjpeg')) {
					return ['status' => false, 'message' => 'Server belum mendukung kompresi JPEG.'];
				}
				$image = @imagecreatefromjpeg($file_path);
				if (!$image) {
					return ['status' => false, 'message' => 'Gambar JPEG gagal dibaca.'];
				}
				$saved = imagejpeg($image, $file_path, 82);
				imagedestroy($image);
				clearstatcache(TRUE, $file_path);
				return $saved ? ['status' => true] : ['status' => false, 'message' => 'Gambar JPEG gagal dikompresi.'];
			}

			if ($mime === 'image/png') {
				if (!function_exists('imagecreatefrompng')) {
					return ['status' => false, 'message' => 'Server belum mendukung kompresi PNG.'];
				}
				$image = @imagecreatefrompng($file_path);
				if (!$image) {
					return ['status' => false, 'message' => 'Gambar PNG gagal dibaca.'];
				}
				imagealphablending($image, FALSE);
				imagesavealpha($image, TRUE);
				$saved = imagepng($image, $file_path, 6);
				imagedestroy($image);
				clearstatcache(TRUE, $file_path);
				return $saved ? ['status' => true] : ['status' => false, 'message' => 'Gambar PNG gagal dikompresi.'];
			}

			if ($mime === 'image/webp') {
				if (!function_exists('imagecreatefromwebp') || !function_exists('imagewebp')) {
					return ['status' => true];
				}
				$image = @imagecreatefromwebp($file_path);
				if (!$image) {
					return ['status' => false, 'message' => 'Gambar WebP gagal dibaca.'];
				}
				imagepalettetotruecolor($image);
				imagealphablending($image, TRUE);
				imagesavealpha($image, TRUE);
				$saved = imagewebp($image, $file_path, 82);
				imagedestroy($image);
				clearstatcache(TRUE, $file_path);
				return $saved ? ['status' => true] : ['status' => false, 'message' => 'Gambar WebP gagal dikompresi.'];
			}

			return ['status' => true];
		}

		private function delete_hero_image_file($path){
			$file = basename((string) $path);
			if ($file === '') {
				return;
			}

			$full_path = $this->hero_upload_path() . $file;
			if (is_file($full_path)) {
				@unlink($full_path);
			}
		}
		
		/**
			* Resize logo hanya jika dimensinya terlalu besar.
		*/
		private function process_logo_image($file_path){
			$image_info = @getimagesize($file_path);
			if (!$image_info) {
				log_message('error', 'Logo processing failed: invalid image file.');
				return false;
			}

			$width = (int) $image_info[0];
			$height = (int) $image_info[1];
			$max_dimension = 1600;

			if ($width <= $max_dimension && $height <= $max_dimension) {
				return true;
			}

			return $this->resize_image($file_path, $max_dimension, $max_dimension);
		}

		/**
			* Resize image dengan rasio tetap.
		*/
		private function resize_image($file_path, $width, $height, $quality = '90%'){
			$config['image_library'] = 'gd2';
			$config['source_image'] = $file_path;
			$config['maintain_ratio'] = TRUE;
			$config['width'] = $width;
			$config['height'] = $height;
			$config['quality'] = $quality;
			$config['create_thumb'] = FALSE;
			
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
			return $result;
		}
	}
