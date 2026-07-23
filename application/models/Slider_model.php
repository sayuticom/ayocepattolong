<?php
	class Slider_model extends CI_Model {
		
		private $table = 'image_slider';
		private $columns_cache = null;
		
		private $column_order = ['id', 'title', 'image', 'is_active', 'created_at', null];
		private $column_search = ['title', 'caption'];

		public function has_column($column)
		{
			if ($this->columns_cache === null) {
				$fields = $this->db->list_fields($this->table);
				$this->columns_cache = array_fill_keys($fields, true);
			}

			return isset($this->columns_cache[$column]);
		}

		private function filter_existing_columns($data)
		{
			$filtered = [];

			foreach ($data as $key => $value) {
				if ($this->has_column($key)) {
					$filtered[$key] = $value;
				}
			}

			return $filtered;
		}
		
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
				$order_column = (int) $_POST['order']['0']['column'];
				$order_field = isset($this->column_order[$order_column]) ? $this->column_order[$order_column] : null;

				if ($order_field === null) {
					$order_field = 'created_at';
				}

				$this->db->order_by(
				$order_field,
				$_POST['order']['0']['dir']
				);
				} else {
				if ($this->has_column('sort_order')) {
					$this->db->order_by('sort_order', 'ASC');
				}
				$this->db->order_by('id', 'DESC');
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
			$data = $this->filter_existing_columns($data);
			return $this->db->insert($this->table, $data);
		}
		
		function update($id, $data) {
			$data = $this->filter_existing_columns($data);
			return $this->db->where('id',$id)->update($this->table, $data);
		}
		
		function delete($id) {
			return $this->db->delete($this->table, ['id'=>$id]);
		}
		// Method untuk bulk update
		public function bulk_update($ids, $data) {
			$data = $this->filter_existing_columns($data);
			$this->db->where_in('id', $ids);
			return $this->db->update($this->table, $data);
		}
		public function get_all() {
			if ($this->has_column('sort_order')) {
				$this->db->order_by('sort_order', 'ASC');
			}

			return $this->db->order_by('id','DESC')->get($this->table)->result();
		}
		// Method untuk update data
		public function update_where($where, $data) {
			$data = $this->filter_existing_columns($data);
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

		public function get_active_sliders($limit = 6)
		{
			$limit = max(1, min((int) $limit, 12));

			$this->db->select('*');
			$this->db->where('is_active', 1);

			if ($this->has_column('sort_order')) {
				$this->db->order_by('sort_order', 'ASC');
			}

			$this->db->order_by('id', 'DESC');
			$this->db->limit($limit);
			$rows = $this->db->get($this->table)->result();
			$valid = [];

			foreach ($rows as $row) {
				$image = isset($row->image) ? basename((string) $row->image) : '';
				if ($image === '' || $image !== (string) $row->image) {
					continue;
				}

				if (!is_file(FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'slider' . DIRECTORY_SEPARATOR . $image)) {
					continue;
				}

				$valid[] = $row;
			}

			return $valid;
		}
	}
