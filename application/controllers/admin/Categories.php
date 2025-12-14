<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Categories extends Admin_Controller {
		
		public function __construct() {
			parent::__construct();
			$this->load->model('Category_model');
		}
		
		public function index() {
			$data['title'] = 'Kategori Produk';
			$this->render('admin/categories/index', $data);
		}
		
		public function ajax_list() {
			$list = $this->Category_model->get_datatables();
			$data = array();
			$no = $_POST['start'];
			
			foreach ($list as $category) {
				$no++;
				$row = array();
				$row['id'] = $no;
				$row['name'] = $category->name;
				$row['is_active'] = $category->is_active ? 
                '<span class="badge badge-success">Aktif</span>' : 
                '<span class="badge badge-secondary">Nonaktif</span>';
				
				// Tambahkan info size option
				$size_info = '';
				if ($category->show_size_option == 1) {
					$size_info = '<div class="text-xs text-gray-500 mt-1">';
					$size_info .= 'Size: ' . $category->size_label . ' (+Rp ' . number_format($category->size_price, 0, ',', '.') . ')';
					if ($category->disable_size_option == 1) {
						$size_info .= ' <span class="text-red-500">(Disabled)</span>';
					}
					$size_info .= '</div>';
				}
				
				$row['created_at'] = date('d-m-Y H:i', strtotime($category->created_at)) . $size_info;
				
				// Action buttons
				$action = '<div class="flex space-x-2">';
				$action .= '<button onclick="editCategory('.$category->id.')" 
				class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition duration-200">
				<i class="fas fa-edit mr-1"></i>Edit
				</button>';
				$action .= '<button onclick="deleteCategory('.$category->id.')" 
				class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition duration-200">
				<i class="fas fa-trash mr-1"></i>Hapus
				</button>';
				$action .= '</div>';
				
				$row['action'] = $action;
				
				$data[] = $row;
			}
			
			$output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Category_model->count_all(),
            "recordsFiltered" => $this->Category_model->count_filtered(),
            "data" => $data,
			);
			
			echo json_encode($output);
		}
		
		public function ajax_save() {
			$this->form_validation->set_rules('name', 'Nama Kategori', 'required|trim');
			
			if ($this->form_validation->run() == FALSE) {
				$response = array(
                'status' => 'error',
                'message' => validation_errors()
				);
				} else {
				$data = array(
                'name' => $this->input->post('name'),
                'is_active' => $this->input->post('is_active'),
                'has_size_option' => $this->input->post('has_size_option') ?: 1,
                'size_label' => $this->input->post('size_label') ?: 'Large',
                'size_price' => $this->input->post('size_price') ?: 3000,
                'disable_size_option' => $this->input->post('disable_size_option') ?: 0,
                'show_size_option' => $this->input->post('show_size_option') ?: 1,
                'created_at' => date('Y-m-d H:i:s')
				);
				
				$insert = $this->Category_model->create($data);
				
				if ($insert) {
					$response = array(
                    'status' => 'success',
                    'message' => 'Data berhasil disimpan'
					);
					} else {
					$response = array(
                    'status' => 'error',
                    'message' => 'Gagal menyimpan data'
					);
				}
			}
			
			echo json_encode($response);
		}
		
		public function ajax_edit($id) {
			$data = $this->Category_model->get($id);
			echo json_encode($data);
		}
		
		public function ajax_update() {
			$this->form_validation->set_rules('name', 'Nama Kategori', 'required|trim');
			
			if ($this->form_validation->run() == FALSE) {
				$response = array(
                'status' => 'error',
                'message' => validation_errors()
				);
				} else {
				$id = $this->input->post('id');
				$data = array(
                'name' => $this->input->post('name'),
                'is_active' => $this->input->post('is_active'),
                'has_size_option' => $this->input->post('has_size_option'),
                'size_label' => $this->input->post('size_label'),
                'size_price' => $this->input->post('size_price'),
                'disable_size_option' => $this->input->post('disable_size_option'),
                'show_size_option' => $this->input->post('show_size_option'),
                'updated_at' => date('Y-m-d H:i:s')
				);
				
				$update = $this->Category_model->update($id, $data);
				
				if ($update) {
					$response = array(
                    'status' => 'success',
                    'message' => 'Data berhasil diperbarui'
					);
					} else {
					$response = array(
                    'status' => 'error',
                    'message' => 'Gagal memperbarui data'
					);
				}
			}
			
			echo json_encode($response);
		}
		
		public function ajax_delete($id) {
			$delete = $this->Category_model->delete($id);
			
			if ($delete) {
				$response = array(
                'status' => 'success',
                'message' => 'Data berhasil dihapus'
				);
				} else {
				$response = array(
                'status' => 'error',
                'message' => 'Gagal menghapus data'
				);
			}
			
			echo json_encode($response);
		}
	}	