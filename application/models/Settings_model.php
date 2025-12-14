<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Settings_model extends CI_Model {
		
		public function __construct(){
			parent::__construct();
		}
		
		public function get(){
			$query = $this->db->get('settings');
			if($query->num_rows() > 0){
				return $query->row();
			}
			return false;
		}
		
		public function update($data){
			// Cek apakah data settings sudah ada
			$existing = $this->get();
			
			if($existing){
				// Update existing
				$this->db->where('id', $existing->id);
				return $this->db->update('settings', $data);
				} else {
				// Insert new
				return $this->db->insert('settings', $data);
			}
		}
		
		public function get_logo_path(){
			$query = $this->db->select('app_logo')->get('settings');
			if($query->num_rows() > 0){
				$row = $query->row();
				return !empty($row->app_logo) ? $row->app_logo : null;
			}
			return null;
		}
		
		public function get_icon_path(){
			$query = $this->db->select('app_icon')->get('settings');
			if($query->num_rows() > 0){
				$row = $query->row();
				return !empty($row->app_icon) ? $row->app_icon : null;
			}
			return null;
		}
	}	