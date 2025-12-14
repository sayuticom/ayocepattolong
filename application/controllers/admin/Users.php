<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Users extends Admin_Controller {
		
		public function __construct()
		{
			parent::__construct();
			$this->load->model('Users_model');
		}
		
		public function index()
		{
			$this->render('admin/users/index', [
            'title' => 'Manajemen User'
			]);
		}
		
		public function ajax_list()
		{
			$list = $this->Users_model->get_datatables();
			$data = [];
			$no = $_POST['start'];
			
			foreach ($list as $user) {
				$no++;
				$row = [];
				
				$badge = $user->is_active ? 
                '<span class="px-2 py-1 rounded bg-green-500 text-white text-xs">Active</span>' :
                '<span class="px-2 py-1 rounded bg-red-500 text-white text-xs">Inactive</span>';
				
				$row[] = $no;
				$row[] = $user->username;
				$row[] = $user->fullname;
				$row[] = $user->role_id == 1 ? 'Admin' : 'Staff';
				$row[] = $badge;
				$row[] = '
                <button onclick="editUser('.$user->id.')" class="px-3 py-1 bg-yellow-500 text-white rounded text-xs">Edit</button>
                <button onclick="deleteUser('.$user->id.')" class="px-3 py-1 bg-red-600 text-white rounded text-xs">Delete</button>
				';
				
				$data[] = $row;
			}
			
			echo json_encode([
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Users_model->count_all(),
            "recordsFiltered" => $this->Users_model->count_filtered(),
            "data" => $data,
			]);
		}
		
		public function get($id) {
			$user = $this->Users_model->get($id);
			
			if ($user) {
				$this->output
				->set_content_type('application/json')
				->set_output(json_encode([
                'status' => 'success',
                'data' => $user
				]));
				} else {
				$this->output
				->set_content_type('application/json')
				->set_output(json_encode([
                'status' => 'error',
                'message' => 'User tidak ditemukan'
				]));
			}
		}
		
		public function save()
		{
			$id = $this->input->post('id');
			
			$data = [
            'username' => $this->input->post('username'),
            'fullname' => $this->input->post('fullname'),
            'role_id' => $this->input->post('role_id'),
            'is_active' => $this->input->post('is_active'),
			];
			
			if ($this->input->post('password')) {
				$data['password'] = password_hash($this->input->post('password'), PASSWORD_BCRYPT);
			}
			
			if ($id)
            $this->Users_model->update($id, $data);
			else
            $this->Users_model->insert($data);
			
			echo json_encode(['status'=>true]);
		}
		
		public function delete($id) {
			// Cek apakah user bisa dihapus
			$user = $this->User_model->get($id);
			
			if (!$user) {
				echo json_encode([
				'status' => 'error',
				'message' => 'User tidak ditemukan'
				]);
				return;
			}
			
			// Cek jika user sedang login sendiri
			if ($id == $this->session->userdata('user_id')) {
				echo json_encode([
				'status' => 'error',
				'message' => 'Tidak bisa menghapus akun sendiri'
				]);
				return;
			}
			
			// Proses delete
			$delete = $this->Users_model->delete($id);
			
			if ($delete) {
				echo json_encode([
				'status' => 'success',
				'message' => 'User berhasil dihapus'
				]);
				} else {
				echo json_encode([
				'status' => 'error',
				'message' => 'Gagal menghapus user'
				]);
			}
		}
	}
	
