<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Dashboard extends Admin_Controller
	{
		public function __construct()
		{
			parent::__construct();
			$this->load->model('Dashboard_model');
		}
		
		public function index()
		{
			$data = [
            'title' => 'Dashboard Admin',
            'page_header' => [
			'title' => 'Dashboard Overview',
			'description' => 'Ringkasan data sistem administrasi'
            ]
			];
			
			// Get summary data
			$data['summary'] = $this->getDashboardSummary();
			$data['recent_activities'] = $this->getRecentActivities();
			$data['chart_data'] = $this->getChartData();
			
			$this->render('admin/dashboard', $data);
		}
		private function getDashboardSummary() {
			return [
            'relawan' => [
			'total' => $this->db->count_all('relawan'),
			'today' => $this->db->where('DATE(created_at)', date('Y-m-d'))->count_all_results('relawan'),
			'icon' => 'fas fa-users',
			'color' => 'primary',
			'link' => site_url('admin/relawan')
            ],
            'informasi' => [
			'total' => $this->db->count_all('informasi'),
			'active' => $this->db->count_all('informasi'), // semua aktif
			'icon' => 'fas fa-newspaper',
			'color' => 'success',
			'link' => site_url('admin/informasi')
            ],
            'slider' => [
			'total' => $this->db->count_all('image_slider'),
			'active' => $this->db->where('is_active', 1)->count_all_results('image_slider'),
			'icon' => 'fas fa-images',
			'color' => 'warning',
			'link' => site_url('admin/slider')
            ],
            'users' => [
			'total' => $this->db->count_all('users'),
			'active' => $this->db->where('is_active', 1)->count_all_results('users'),
			'icon' => 'fas fa-user-cog',
			'color' => 'danger',
			'link' => site_url('admin/users')
            ]
			];
		}
		
		private function getRecentActivities() {
			// Get recent relawan
			$this->db->order_by('created_at', 'DESC');
			$this->db->limit(5);
			$recent_relawan = $this->db->get('relawan')->result();
			
			// Get recent informasi
			$this->db->order_by('create_at', 'DESC');
			$this->db->limit(5);
			$recent_informasi = $this->db->get('informasi')->result();
			
			// Combine and sort
			$activities = [];
			
			foreach ($recent_relawan as $relawan) {
				$activities[] = [
                'type' => 'relawan',
                'title' => 'Relawan Baru',
                'description' => $relawan->nama . ' telah bergabung',
                'time' => $this->timeAgo($relawan->created_at),
                'icon' => 'fas fa-user-plus',
                'color' => 'primary'
				];
			}
			
			foreach ($recent_informasi as $info) {
				$activities[] = [
                'type' => 'informasi',
                'title' => 'Informasi Baru',
                'description' => '"' . $info->title . '" ditambahkan',
                'time' => $this->timeAgo($info->create_at),
                'icon' => 'fas fa-file-alt',
                'color' => 'success'
				];
			}
			
			// Sort by time
			usort($activities, function($a, $b) {
				return strtotime($b['time']) - strtotime($a['time']);
			});
			
			return array_slice($activities, 0, 5);
		}
		
		private function getChartData() {
			// Data relawan per bulan
			$this->db->select("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total");
			$this->db->group_by("DATE_FORMAT(created_at, '%Y-%m')");
			$this->db->order_by('month', 'ASC');
			$relawan_chart = $this->db->get('relawan')->result();
			
			// Data informasi per bulan
			$this->db->select("DATE_FORMAT(create_at, '%Y-%m') as month, COUNT(*) as total");
			$this->db->group_by("DATE_FORMAT(create_at, '%Y-%m')");
			$this->db->order_by('month', 'ASC');
			$informasi_chart = $this->db->get('informasi')->result();
			
			return [
            'relawan_chart' => $relawan_chart,
            'informasi_chart' => $informasi_chart
			];
		}
		
		private function timeAgo($datetime) {
			$time = strtotime($datetime);
			$now = time();
			$diff = $now - $time;
			
			if ($diff < 60) {
				return 'Baru saja';
				} elseif ($diff < 3600) {
				$mins = floor($diff / 60);
				return $mins . ' menit yang lalu';
				} elseif ($diff < 86400) {
				$hours = floor($diff / 3600);
				return $hours . ' jam yang lalu';
				} elseif ($diff < 604800) {
				$days = floor($diff / 86400);
				return $days . ' hari yang lalu';
				} else {
				return date('d M Y', $time);
			}
		}
		// Controller: Dashboard.php (tambahkan method)
		public function refresh() {
			$data = [
			'summary' => $this->getDashboardSummary(),
			'chart_data' => [
            'relawan_chart' => [
			'months' => array_column($this->getChartData()['relawan_chart'], 'month'),
			'totals' => array_column($this->getChartData()['relawan_chart'], 'total')
            ],
            'informasi_chart' => [
			'months' => array_column($this->getChartData()['informasi_chart'], 'month'),
			'totals' => array_column($this->getChartData()['informasi_chart'], 'total')
            ]
			]
			];
			
			echo json_encode($data);
		}
	}					