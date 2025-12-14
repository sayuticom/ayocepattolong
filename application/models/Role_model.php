<?php
	class Role_model extends CI_Model {
		public function get_by_id($id){
			return $this->db->get_where('roles', ['id'=>$id])->row();
		}
		public function get_all(){ return $this->db->get('roles')->result(); }
	}
