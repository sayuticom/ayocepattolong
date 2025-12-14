<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	function is_logged_in(){
		$ci =& get_instance();
		return $ci->session->has_userdata('user_id');
	}
	
	function current_user(){
		$ci =& get_instance();
		if($ci->session->has_userdata('user_id')) return (object) $ci->session->userdata();
		return null;
	}
	
	function require_login(){
		$ci =& get_instance();
		if(!is_logged_in()){
			redirect('login');
			exit;
		}
	}
	
	function require_role($role_name){
		$ci =& get_instance();
		$user = current_user();
		if(!$user) redirect('login');
		// role_name: 'admin' or 'staff'
		$ci->load->model('Role_model');
		$role = $ci->Role_model->get_by_id($user['role_id']);
		if(!$role || $role->name != $role_name){
			show_error('Access denied', 403);
		}
	}
	function dump($arr) {
        echo "<textarea style='width:100%; height:300px;'>";
        print_r($arr);
        echo "</textarea>";
        exit;
	}	