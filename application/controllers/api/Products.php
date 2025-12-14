<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Products extends CI_Controller {
		public function __construct(){
			parent::__construct();
			$this->load->model('Product_model');
			$this->load->config('api');
		}
		
		private function _auth(){
			$key = $this->input->get_request_header('X-API-KEY', TRUE);
			if(!$key) $key = $this->input->get('api_key');
			return $key === $this->config->item('api_key');
		}
		
		public function index(){
			if(!$this->_auth()){
				$this->output->set_status_header(401)->set_output(json_encode(['error'=>'Unauthorized']));
				return;
			}
			
			$limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
			$offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
			$category = $this->input->get('category');
			
			// Load data dengan pagination dan filter kategori
			$products = $this->Product_model->get_products_paginated($limit, $offset, $category);
			
			if ($category) {
				$total_products = $this->Product_model->count_products_by_category($category);
				} else {
				$total_products = $this->Product_model->count_all_products();
			}
			
			$response = [
			'status' => 'success',
			'products' => $products,
			'total' => $total_products,
			'limit' => $limit,
			'offset' => $offset,
			'has_more' => ($offset + $limit) < $total_products
			];
			
			$this->output->set_content_type('application/json')->set_output(json_encode($response));
		}
		
		// Endpoint untuk mendapatkan kategori saja
		public function categories() {
			if(!$this->_auth()){
				$this->output->set_status_header(401)->set_output(json_encode(['error'=>'Unauthorized']));
				return;
			}
			
			$categories = $this->Product_model->get_categories();
			
			$this->output->set_content_type('application/json')->set_output(json_encode([
            'status' => 'success',
            'categories' => $categories
			]));
		}
	}		