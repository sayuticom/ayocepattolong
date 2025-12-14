<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Slider extends Admin_Controller {
		
		public function __construct() {
			parent::__construct();
			$this->load->model('Slider_model');
			$this->load->helper(['form','url']);
		}
		
		public function index() {
			$data['title'] = 'Slider';
			$data['slider'] = $this->Slider_model->get_all();
			$this->render('admin/slider/index', $data);
		}
		// DATATABLE SERVERSIDE
		public function ajax_list() {
			$filterStatus = $this->input->post('filterStatus');
			$list = $this->Slider_model->get_datatables($filterStatus);
			$data = [];
			$no = $_POST['start'];
			
			foreach ($list as $s) {
				$no++;
				$row = [];
				
				// Kolom 1: Checkbox + Nomor
				$row[] = '
				<div class="flex items-center space-x-2">
                <input type="checkbox" value="'.$s->id.'" class="row-checkbox rounded text-primary-600">
                <span>'.$no.'</span>
				</div>
				';
				
				// Kolom 2: Title dengan caption - Gunakan escape untuk menghindari XSS
				$title = htmlspecialchars($s->title, ENT_QUOTES, 'UTF-8');
				$caption = $s->caption ? htmlspecialchars($s->caption, ENT_QUOTES, 'UTF-8') : '';
				$captionHtml = $caption ? '<div class="text-sm text-gray-500 truncate max-w-xs">'.$caption.'</div>' : '';
				$row[] = '
				<div>
                <div class="font-medium text-gray-800">'.$title.'</div>
                '.$captionHtml.'
				</div>
				';
				
				// Kolom 3: Image dengan preview
				$image_path = base_url('uploads/slider/'.$s->image);
				$row[] = '
				<div class="relative group">
                <img src="'.$image_path.'" 
				alt="'.$title.'" 
				class="w-16 h-12 object-cover rounded-lg cursor-pointer hover:opacity-90 transition-opacity duration-200"
				onclick="previewImage(\''.$image_path.'\')">
                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 rounded-lg transition-all duration-200"></div>
				</div>
				';
				
				// Kolom 4: Status dengan badge
				if ($s->is_active == 1) {
					$row[] = '
					<span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                    <i class="fas fa-circle mr-1 text-xs"></i> Aktif
					</span>
					';
					} else {
					$row[] = '
					<span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                    <i class="fas fa-circle mr-1 text-xs"></i> Tidak Aktif
					</span>
					';
				}
				
				// Kolom 5: Tanggal dibuat
				$created_date = $s->created_at ? date('d M Y', strtotime($s->created_at)) : '-';
				$created_time = $s->created_at ? date('H:i', strtotime($s->created_at)) : '';
				$row[] = '
				<div>
                <div class="text-sm text-gray-800">'.$created_date.'</div>
                <div class="text-xs text-gray-500">'.$created_time.'</div>
				</div>
				';
				
				// Kolom 6: Action buttons
				$row[] = '
				<div class="flex space-x-2">
                <button onclick="editData('.$s->id.')" 
				class="ripple w-8 h-8 flex items-center justify-center bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-colors duration-200"
				data-tooltip="Edit">
				<i class="fas fa-edit text-sm"></i>
                </button>
                <button onclick="toggleStatus('.$s->id.','.$s->is_active.')" 
				class="ripple w-8 h-8 flex items-center justify-center '.($s->is_active == 1 ? 'bg-yellow-100 text-yellow-600' : 'bg-green-100 text-green-600').' rounded-lg hover:opacity-90 transition-colors duration-200"
				data-tooltip="'.($s->is_active == 1 ? 'Nonaktifkan' : 'Aktifkan').'">
				<i class="fas '.($s->is_active == 1 ? 'fa-eye-slash' : 'fa-eye').' text-sm"></i>
                </button>
                <button onclick="deleteData('.$s->id.')" 
				class="ripple w-8 h-8 flex items-center justify-center bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-colors duration-200"
				data-tooltip="Hapus">
				<i class="fas fa-trash text-sm"></i>
                </button>
				</div>
				';
				
				$data[] = $row;
			}
			
			// Update stats
			$total = $this->Slider_model->count_all();
			$active = $this->Slider_model->count_active();
			$inactive = $this->Slider_model->count_inactive();
			
			$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $total,
			"recordsFiltered" => $this->Slider_model->count_filtered($filterStatus),
			"data" => $data,
			"stats" => [
            "total" => $total,
            "active" => $active,
            "inactive" => $inactive
			]
			];
			
			echo json_encode($output);
		}
		
		// GET BY ID
		public function ajax_edit($id) {
			echo json_encode($this->Slider_model->get_by_id($id));
		}
		
		// SAVE (ADD/UPDATE)
		public function ajax_save() {
			
			$id = $this->input->post('id');
			
			$data = [
			'title'     => $this->input->post('title'),
			'caption'   => $this->input->post('caption'),
			'is_active' => $this->input->post('is_active'),
			];
			
			// === Jika UPDATE, ambil data lama ===
			$old = null;
			if ($id) {
				$old = $this->Slider_model->get_by_id($id);
			}
			
			// === Jika upload gambar baru ===
			if (!empty($_FILES['image']['name'])) {
				
				// Hapus file lama
				if ($id && $old && file_exists('./uploads/slider/'.$old->image)) {
					unlink('./uploads/slider/'.$old->image);
				}
				
				// Upload file baru
				$data['image'] = $this->_upload();
			}
			
			// === Simpan atau update ===
			if ($id) {
				$this->Slider_model->update($id, $data);
				} else {
				$this->Slider_model->insert($data);
			}
			
			echo json_encode(["status" => true]);
		}
		
		
		public function ajax_delete($id) {
			$this->Slider_model->delete($id);
			echo json_encode(["status" => true]);
		}
		
		private function _upload() {
			$config['upload_path']   = './uploads/slider/';
			$config['allowed_types'] = 'jpg|jpeg|png|webp';
			$config['encrypt_name']  = true;
			
			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			$this->upload->do_upload('image');
			return $this->upload->data('file_name');
		}
		public function toggle_status($id) {
			// Cek apakah request adalah AJAX
			if (!$this->input->is_ajax_request()) {
				show_404();
			}
			
			// Validasi ID
			if (empty($id)) {
				echo json_encode([
				'success' => false,
				'message' => 'ID tidak valid'
				]);
				return;
			}
			
			// Ambil status dari POST
			$status = $this->input->post('status');
			
			// Validasi status (harus 0 atau 1)
			if (!in_array($status, ['0', '1'])) {
				echo json_encode([
				'success' => false,
				'message' => 'Status tidak valid'
				]);
				return;
			}
			
			// Cek apakah slider ada
			$slider = $this->Slider_model->get_by_id($id);
			if (!$slider) {
				echo json_encode([
				'success' => false,
				'message' => 'Slider tidak ditemukan'
				]);
				return;
			}
			
			// Update status
			$data = ['is_active' => $status];
			$updated = $this->Slider_model->update_where(['id' => $id], $data);
			
			if ($updated) {
				// Log aktivitas jika ada model log
				if (method_exists($this, '_log_activity')) {
					$action = $status == 1 ? 'mengaktifkan' : 'menonaktifkan';
					$this->_log_activity("{$action} slider: {$slider->title}");
				}
				
				echo json_encode([
				'success' => true,
				'message' => 'Status berhasil diubah',
				'new_status' => $status
				]);
				} else {
				echo json_encode([
				'success' => false,
				'message' => 'Gagal mengubah status'
				]);
			}
		}
		
		// Method untuk bulk toggle status
		public function bulk_toggle_status() {
			if (!$this->input->is_ajax_request()) {
				show_404();
			}
			
			$ids = $this->input->post('ids');
			$status = $this->input->post('status');
			
			if (empty($ids) || !is_array($ids)) {
				echo json_encode([
				'success' => false,
				'message' => 'Tidak ada data yang dipilih'
				]);
				return;
			}
			
			// Validasi status
			if (!in_array($status, ['0', '1'])) {
				echo json_encode([
				'success' => false,
				'message' => 'Status tidak valid'
				]);
				return;
			}
			
			// Update semua ID yang dipilih
			$success = 0;
			$failed = 0;
			
			foreach ($ids as $id) {
				$updated = $this->Slider_model->update_where(['id' => $id], ['is_active' => $status]);
				if ($updated) {
					$success++;
					} else {
					$failed++;
				}
			}
			
			echo json_encode([
			'success' => true,
			'message' => "Berhasil mengubah {$success} slider, gagal: {$failed}",
			'total_updated' => $success
			]);
		}
	}
