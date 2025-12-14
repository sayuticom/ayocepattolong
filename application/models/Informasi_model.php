<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Informasi_model extends CI_Model {
		
		var $table = 'informasi';
		var $column_order = array(null, 'title', 'caption', 'urutan'); 
		var $column_search = array('title', 'caption'); 
		var $order = array('id' => 'DESC');
		
		private function _get_datatables_query() {
			$this->db->from($this->table);
			
			$i = 0;
			foreach ($this->column_search as $item) {
				if ($_POST['search']['value']) {
					if ($i === 0) {
						$this->db->group_start();
						$this->db->like($item, $_POST['search']['value']);
						} else {
						$this->db->or_like($item, $_POST['search']['value']);
					}
					
					if (count($this->column_search) - 1 == $i) {
						$this->db->group_end();
					}
				}
				$i++;
			}
			
			if (isset($_POST['order'])) { 
				$this->db->order_by(
                $this->column_order[$_POST['order'][0]['column']], 
                $_POST['order'][0]['dir']
				);
				} else {
				$this->db->order_by(key($this->order), $this->order[key($this->order)]);
			}
		}
		
		public function get_datatables() {
			$this->_get_datatables_query();
			
			if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
			
			return $this->db->get()->result();
		}
		
		public function count_filtered() {
			$this->_get_datatables_query();
			return $this->db->get()->num_rows();
		}
		
		public function count_all() {
			return $this->db->count_all($this->table);
		}
		
		// ===== CRUD =====
		
		public function getById($id) {
			return $this->db->get_where($this->table, ['id' => $id])->row();
		}
		
		public function insert($data) {
			return $this->db->insert($this->table, $data);
		}
		
		public function updateData($id, $data) {
			return $this->db->where('id', $id)->update($this->table, $data);
		}
		
		public function deleteData($id) {
			return $this->db->delete($this->table, ['id' => $id]);
		}
		
		public function get_info($limit = 3)
		{
			$this->db->select('*');
			$this->db->order_by('urutan', 'ASC');
			$this->db->limit($limit);
			return $this->db->get($this->table)->result();
		}
	}
