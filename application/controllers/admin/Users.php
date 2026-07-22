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
				$row[] = html_escape($user->username);
				$row[] = html_escape($user->fullname);
				$row[] = $user->role_id == 1 ? 'Admin' : 'Staff';
				$row[] = $badge;
				$row[] = '
                <button onclick="editUser('.(int) $user->id.')" class="px-3 py-1 bg-yellow-500 text-white rounded text-xs">Edit</button>
                <button onclick="deleteUser('.(int) $user->id.')" class="px-3 py-1 bg-red-600 text-white rounded text-xs">Delete</button>
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
				unset($user->password);
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
			$username = strtolower(trim((string) $this->input->post('username')));
			$password = (string) $this->input->post('password');
			$role_id = (int) $this->input->post('role_id');
			$is_active = (string) $this->input->post('is_active');
			$errors = [];

			if ($username === '') {
				$errors[] = 'Username wajib diisi.';
			} elseif (strlen($username) < 3) {
				$errors[] = 'Username minimal 3 karakter.';
			} elseif (strlen($username) > 100 || !preg_match('/^[a-z0-9._-]+$/', $username)) {
				$errors[] = 'Username hanya boleh berisi huruf kecil, angka, titik, garis bawah, dan tanda minus.';
			} elseif (!$this->Users_model->is_username_unique($username, $id)) {
				$errors[] = 'Username sudah digunakan.';
			}

			if ($id === '' && $password === '') {
				$errors[] = 'Password wajib diisi untuk user baru.';
			}

			if (!in_array($role_id, [1, 2], true)) {
				$errors[] = 'Role tidak valid.';
			}

			if (!in_array($is_active, ['0', '1'], true)) {
				$errors[] = 'Status tidak valid.';
			}

			if (!empty($errors)) {
				echo json_encode([
					'status' => false,
					'message' => implode(' ', $errors)
				]);
				return;
			}
			
			$data = [
            'username' => $username,
            'fullname' => trim((string) $this->input->post('fullname', TRUE)),
            'role_id' => $role_id,
            'is_active' => (int) $is_active,
			];
			
			if ($password !== '') {
				$data['password'] = password_hash($password, PASSWORD_BCRYPT);
			}
			
			if ($id)
            $saved = $this->Users_model->update($id, $data);
			else
            $saved = $this->Users_model->insert($data);
			
			echo json_encode([
				'status' => (bool) $saved,
				'message' => $saved ? 'User berhasil disimpan.' : 'User gagal disimpan.'
			]);
		}
		
		public function delete($id) {
			// Cek apakah user bisa dihapus
			$user = $this->Users_model->get($id);
			
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
	
