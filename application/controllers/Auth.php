<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Auth extends CI_Controller {
		public function __construct(){
			parent::__construct();
			$this->load->model('Users_model');
		}
		
		public function login(){
			if($this->input->method() == 'post'){
				$u = $this->input->post('username');
				$p = $this->input->post('password');
				$user = $this->Users_model->find_by_username($u);
				// print_r($user->password);
				// exit;
				if($user && password_verify($p, $user->password) && $user->is_active){
					// set session
					$this->session->set_userdata([
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'fullname' => $user->fullname,
                    'role_id' => $user->role_id,
                    'logged_in' => TRUE
					]);
 
					redirect('admin/dashboard');
					} else {
					$data['error'] = 'Username atau password salah';
					$this->load->view('auth/login', $data);
				}
				return;
			}
			$this->load->view('auth/login');
		}
		
		public function logout(){
			$this->session->sess_destroy();
			redirect('login');
		}
	}
