<?php
	class Slider_model extends CI_Model {
		
		private $table = 'image_slider';
		
		private $column_order = ['title', 'caption', 'image', 'is_active', 'created_at'];
		private $column_search = ['title', 'caption'];
		
		public function get_datatables($filterStatus = null) {
			$this->_get_datatables_query($filterStatus);
			
			if ($_POST['length'] != -1) {
				$this->db->limit($_POST['length'], $_POST['start']);
			}
			
			$query = $this->db->get();
			return $query->result();
		}
		
		private function _get_datatables_query($filterStatus = null) {
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
			
			// Tambahkan filter status jika ada
			if ($filterStatus !== null && $filterStatus !== '') {
				$this->db->where('is_active', $filterStatus);
			}
			
			// Order by
			if (isset($_POST['order'])) {
				$this->db->order_by(
				$this->column_order[$_POST['order']['0']['column']], 
				$_POST['order']['0']['dir']
				);
				} else {
				$this->db->order_by('created_at', 'DESC');
			}
		}
		
		public function count_filtered($filterStatus = null) {
			$this->_get_datatables_query($filterStatus);
			$query = $this->db->get();
			return $query->num_rows();
		}
		
		public function count_all() {
			$this->db->from($this->table);
			return $this->db->count_all_results();
		}
		
		public function count_active() {
			$this->db->where('is_active', 1);
			return $this->db->count_all_results($this->table);
		}
		
		public function count_inactive() {
			$this->db->where('is_active', 0);
			return $this->db->count_all_results($this->table);
		}
		function get_by_id($id) {
			return $this->db->get_where($this->table, ['id'=>$id])->row();
		}
		
		function insert($data) {
			return $this->db->insert($this->table, $data);
		}
		
		function update($id, $data) {
			return $this->db->where('id',$id)->update($this->table, $data);
		}
		
		function delete($id) {
			$row = $this->get_by_id($id);
			if ($row && file_exists('./uploads/slider/'.$row->image)) {
				unlink('./uploads/slider/'.$row->image);
			}
			return $this->db->delete($this->table, ['id'=>$id]);
		}
		// Method untuk bulk update
		public function bulk_update($ids, $data) {
			$this->db->where_in('id', $ids);
			return $this->db->update($this->table, $data);
		}
		public function get_all() {
			return $this->db->order_by('id','DESC')->get($this->table)->result();
		}
		// Method untuk update data
		public function update_where($where, $data) {
			$this->db->where($where);
			return $this->db->update($this->table, $data);
		}
		public function get_info($limit = 3)
		{
			$this->db->select('*');
			$this->db->where('is_active', 1);
			$this->db->order_by('id', 'DESC');
			$this->db->limit($limit);
			return $this->db->get($this->table)->result();
		}
	}
