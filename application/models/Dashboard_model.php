<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Dashboard_model extends CI_Model
	{
		public function count_products()
		{
			return $this->db->count_all('products');
		}
		
		public function count_categories()
		{
			return $this->db->count_all('categories');
		}
		
		public function count_products_ready()
		{
			return $this->db
            ->where('is_ready', 1)
            ->where('is_active', 1)
            ->count_all_results('products');
		}
		
		public function count_categories_active()
		{
			return $this->db
            ->where('is_active', 1)
            ->count_all_results('categories');
		}
		
		public function count_orders()
		{
			return $this->db->count_all('orders');
		}
		
		public function sum_total_orders()
		{
			return $this->db->select_sum('total')->get('orders')->row()->total ?? 0;
		}
		
		public function sum_admin_fee()
		{
			return $this->db->select_sum('fee')->get('orders')->row()->fee ?? 0;
		}
		
		public function count_orders_today()
		{
			return $this->db
            ->where('DATE(created_at)', date('Y-m-d'))
            ->count_all_results('orders');
		}
		
		public function get_order_stats_last_7_days()
		{
			$end_date = date('Y-m-d');
			$start_date = date('Y-m-d', strtotime('-6 days', strtotime($end_date)));
			
			$this->db->select("
            DATE(created_at) as order_date,
            COUNT(*) as total_orders,
            SUM(total) as total_amount,
            SUM(fee) as total_fee
			");
			$this->db->from('orders');
			$this->db->where("DATE(created_at) BETWEEN '$start_date' AND '$end_date'", NULL, FALSE);
			$this->db->group_by('DATE(created_at)');
			$this->db->order_by('order_date', 'ASC');
			
			$query = $this->db->get();
			return $query->result();
		}
		
		public function get_order_stats_by_month($year = null)
		{
			$year = $year ?: date('Y');
			
			$this->db->select("
            MONTH(created_at) as month,
            COUNT(*) as total_orders,
            SUM(total) as total_amount,
            SUM(fee) as total_fee
			");
			$this->db->from('orders');
			$this->db->where('YEAR(created_at)', $year);
			$this->db->group_by('MONTH(created_at)');
			$this->db->order_by('month', 'ASC');
			
			$query = $this->db->get();
			return $query->result();
		}
		
		public function get_top_products($limit = 5)
		{
			// Karena tabel order_items tidak ada, kita akan parse dari field items
			// Alternatif: Buat view atau query manual
			// Untuk sementara kita kembalikan array kosong
			return [];
			
			/*
				// Jika nanti ada tabel order_items, gunakan ini:
				$this->db->select("
				p.name as product_name,
				p.category_id,
				COUNT(oi.id) as total_orders,
				SUM(oi.quantity) as total_qty,
				SUM(oi.price * oi.quantity) as total_revenue
				");
				$this->db->from('order_items oi');
				$this->db->join('products p', 'p.id = oi.product_id', 'left');
				$this->db->group_by('oi.product_id');
				$this->db->order_by('total_qty', 'DESC');
				$this->db->limit($limit);
				
				$query = $this->db->get();
				return $query->result();
			*/
		}
		
		public function get_recent_orders($limit = 10)
		{
			return $this->db->select('*')
            ->from('orders')
            ->order_by('created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->result();
		}
		
		// Method untuk parse data JSON dari field items
		public function parse_order_items($items_json)
		{
			$items = json_decode($items_json, true);
			$result = [];
			
			if (is_array($items)) {
				foreach ($items as $item) {
					$result[] = [
                    'name' => $item['name'] ?? 'Unknown',
                    'quantity' => $item['quantity'] ?? 1,
                    'price' => $item['price'] ?? 0,
                    'size' => $item['size'] ?? 'Regular'
					];
				}
			}
			
			return $result;
		}
		
		// Method untuk menghitung total produk berdasarkan kategori
		public function get_products_by_category()
		{
			$this->db->select("
            c.name as category_name,
            COUNT(p.id) as total_products,
            SUM(CASE WHEN p.is_ready = 1 THEN 1 ELSE 0 END) as ready_products,
            SUM(CASE WHEN p.is_active = 1 THEN 1 ELSE 0 END) as active_products
			");
			$this->db->from('products p');
			$this->db->join('categories c', 'c.id = p.category_id', 'left');
			$this->db->group_by('p.category_id');
			$this->db->order_by('total_products', 'DESC');
			
			$query = $this->db->get();
			return $query->result();
		}
	}
