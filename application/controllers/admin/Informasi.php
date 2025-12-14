<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Informasi extends Admin_Controller {
		
		public function __construct() {
			parent::__construct();
			$this->load->model('Informasi_model');
		}
		
		public function index() {
			$data['title'] = 'Informasi';
			$this->render('admin/informasi/index', $data);
		}
		// ================= DATATABLE SERVER-SIDE =================
		
		public function ajax_list() {
			$list = $this->Informasi_model->get_datatables();
			$data = array();
			$no = $_POST['start'];
			
			foreach ($list as $row) {
				$no++;
				$data[] = [
                'id'        => $row->id,
                'title'     => $row->title,
                'caption'   => $row->caption,
                'urutan'    => $row->urutan,
				];
			}
			
			echo json_encode([
            "draw"            => $_POST['draw'],
            "recordsTotal"    => $this->Informasi_model->count_all(),
            "recordsFiltered" => $this->Informasi_model->count_filtered(),
            "data"            => $data,
			]);
		}
		
		// ================= CRUD =================
		
		public function save() {
			$id = $this->input->post('id');
			
			$data = [
            'title'   => $this->input->post('title'),
            'caption' => $this->input->post('caption'),
            'urutan'  => $this->input->post('urutan'),
			];
			
			if ($id == "") {
				$this->Informasi_model->insert($data);
				} else {
				$this->Informasi_model->updateData($id, $data);
			}
			
			echo json_encode(["status" => true]);
		}
		
		public function get($id) {
			echo json_encode($this->Informasi_model->getById($id));
		}
		
		public function delete($id) {
			$this->Informasi_model->deleteData($id);
			echo json_encode(["status" => true]);
		}
	}
