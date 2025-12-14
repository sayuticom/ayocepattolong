<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Relawan extends Admin_Controller {
		
		public function __construct() {
			parent::__construct();
			$this->load->model('Relawan_model');
		}
		
		public function index() {
			$data['title'] = 'Relawan';
			$this->render('admin/relawan/index', $data);
		}
		
		public function ajax_list() {
			$list = $this->Relawan_model->get_datatables();
			$data = array();
			$no = $_POST['start'];
			
			foreach ($list as $r) {
				$no++;
				$row = array();
				$row[] = $no;
				$row[] = $r->nama;
				$row[] = $r->telepon;
				$row[] = $r->alamat;
				$row[] = $r->created_at;
				
				$row[] = '
                <button class="bg-yellow-500 px-3 py-1 text-white rounded" onclick="editRelawan('.$r->id.')">Edit</button>
                <button class="bg-red-600 px-3 py-1 text-white rounded" onclick="deleteRelawan('.$r->id.')">Hapus</button>
				';
				
				$data[] = $row;
			}
			
			echo json_encode([
            "draw"            => $_POST['draw'],
            "recordsTotal"    => $this->Relawan_model->count_all(),
            "recordsFiltered" => $this->Relawan_model->count_filtered(),
            "data"            => $data
			]);
		}
		
		public function get($id) {
			echo json_encode($this->Relawan_model->getById($id));
		}
		
		public function save() {
			$id = $this->input->post('id');
			
			$data = [
            'nama'       => $this->input->post('nama'),
            'telepon'    => $this->input->post('telepon'),
            'alamat'     => $this->input->post('alamat'),
            'created_at' => date('Y-m-d H:i:s')
			];
			
			if ($id == "") {
				$this->Relawan_model->save($data);
				} else {
				unset($data['created_at']);
				$this->Relawan_model->update($id, $data);
			}
			
			echo json_encode(["status" => true]);
		}
		
		public function delete($id) {
			$this->Relawan_model->delete($id);
			echo json_encode(["status" => true]);
		}
		
	}
