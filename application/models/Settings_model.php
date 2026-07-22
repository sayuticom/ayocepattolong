<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Settings_model extends CI_Model {
		private $app_fields = ['app_name', 'site_desc', 'site_key', 'wa_number', 'app_logo', 'app_icon', 'hero_image'];
		
		public function __construct(){
			parent::__construct();
		}
		
		public function get(){
			$data = new stdClass();

			if ($this->db->table_exists('settingss')) {
				$query = $this->db->limit(1)->get('settingss');
				if ($query->num_rows() > 0) {
					foreach ($query->row() as $key => $val) {
						$data->$key = $val;
					}
				}
			}

			$data->app_logo = $this->normalize_upload_asset(isset($data->app_logo) ? $data->app_logo : '', 'act_logo.png');
			$data->app_icon = $this->normalize_upload_asset(isset($data->app_icon) ? $data->app_icon : '', 'icon.png');
			$data->hero_image = $this->normalize_hero_asset(isset($data->hero_image) ? $data->hero_image : '');
			$data->supports_hero_image = $this->has_app_field('hero_image');

			return $data;
		}
		
		public function update($data){
			if (!$this->db->table_exists('settingss')) {
				return false;
			}

			$data = $this->filter_existing_fields('settingss', $data);
			if (empty($data)) {
				return false;
			}

			$existing = $this->db->limit(1)->get('settingss')->row();
			if ($existing) {
				return $this->db->where('id', $existing->id)->update('settingss', $data);
			}

			return $this->db->insert('settingss', $data);
		}

		public function update_app($data){
			$table = $this->resolve_app_table();
			if (!$table) {
				log_message('error', 'Settings update failed: no settings table contains app fields.');
				return false;
			}

			$data = $this->filter_existing_fields($table, $data);
			if (empty($data)) {
				log_message('error', 'Settings update failed: no submitted fields exist in table ' . $table);
				return false;
			}

			$existing = $this->db->limit(1)->get($table)->row();
			if ($existing) {
				$id_field = isset($existing->id) ? 'id' : null;
				if ($id_field) {
					return $this->db->where($id_field, $existing->$id_field)->update($table, $data);
				}

				return $this->db->limit(1)->update($table, $data);
			}

			return $this->db->insert($table, $data);
		}
		
		public function get_logo_path(){
			$table = $this->resolve_app_table();
			if (!$table || !$this->db->field_exists('app_logo', $table)) {
				return null;
			}

			$query = $this->db->select('app_logo')->limit(1)->get($table);
			if($query->num_rows() > 0){
				$row = $query->row();
				return !empty($row->app_logo) ? $row->app_logo : null;
			}
			return null;
		}
		
		public function get_icon_path(){
			$table = $this->resolve_app_table();
			if (!$table || !$this->db->field_exists('app_icon', $table)) {
				return null;
			}

			$query = $this->db->select('app_icon')->limit(1)->get($table);
			if($query->num_rows() > 0){
				$row = $query->row();
				return !empty($row->app_icon) ? $row->app_icon : null;
			}
			return null;
		}

		public function has_app_field($field){
			$table = $this->resolve_app_table();
			return $table && $this->db->field_exists($field, $table);
		}

		private function resolve_app_table(){
			if (!$this->db->table_exists('settingss')) {
				return false;
			}

			foreach ($this->app_fields as $field) {
				if ($this->db->field_exists($field, 'settingss')) {
					return 'settingss';
				}
			}

			return false;
		}

		private function filter_existing_fields($table, $data){
			$filtered = [];
			foreach ($data as $key => $value) {
				if ($this->db->field_exists($key, $table)) {
					$filtered[$key] = $value;
				}
			}

			return $filtered;
		}

		private function normalize_upload_asset($path, $fallback){
			$file = basename((string) $path);
			if ($file !== '' && is_file(FCPATH . 'uploads' . DIRECTORY_SEPARATOR . $file)) {
				return 'uploads/' . $file;
			}

			$fallback = basename((string) $fallback);
			if ($fallback !== '' && is_file(FCPATH . 'uploads' . DIRECTORY_SEPARATOR . $fallback)) {
				return 'uploads/' . $fallback;
			}

			return '';
		}

		private function normalize_hero_asset($path){
			$file = basename((string) $path);
			if ($file !== '' && is_file(FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'hero' . DIRECTORY_SEPARATOR . $file)) {
				return 'uploads/hero/' . $file;
			}

			return '';
		}
	}
