<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Settings_model extends CI_Model {
		
		public function __construct(){
			parent::__construct();
		}
		
		public function get(){
			$data = new stdClass();

			$q1 = $this->db->get('settings');
			if ($q1->num_rows() > 0) {
				foreach ($q1->row() as $key => $val) {
					$data->$key = $val;
				}
			}

			$q2 = $this->db->get('settingss');
			if ($q2->num_rows() > 0) {
				foreach ($q2->row() as $key => $val) {
					$data->$key = $val;
				}
			}

			return $data;
		}
		
		public function update($data){
			$existing = $this->db->get('settings')->row();
			if ($existing) {
				$this->db->where('id', $existing->id)->update('settings', $data);
			} else {
				$this->db->insert('settings', $data);
			}
		}

		public function update_app($data){
			$existing = $this->db->get('settingss')->row();
			if ($existing) {
				$this->db->where('id', $existing->id)->update('settingss', $data);
			} else {
				$this->db->insert('settingss', $data);
			}
		}
		
		public function get_logo_path(){
			$query = $this->db->select('app_logo')->get('settingss');
			if($query->num_rows() > 0){
				$row = $query->row();
				return !empty($row->app_logo) ? $row->app_logo : null;
			}
			return null;
		}
		
		public function get_icon_path(){
			$query = $this->db->select('app_icon')->get('settingss');
			if($query->num_rows() > 0){
				$row = $query->row();
				return !empty($row->app_icon) ? $row->app_icon : null;
			}
			return null;
		}
	}
