<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Products extends Admin_Controller {
		
		public function __construct()
		{
			parent::__construct();
			$this->load->model('Product_model');
			$this->load->model('Category_model');
		}
		
		public function index()
		{
			$data['categories'] = $this->Category_model->get_all();
			$data['title'] = "Produk";
			
			$this->render('admin/products/index', $data);
		}
		
		public function ajax_list()
		{
			$list = $this->Product_model->get_datatables();
			$data = [];
			$no = $_POST['start'];
			
			foreach ($list as $p) {
				$no++;
				$row = [];
				
				$img = $p->image
                ? "<img src='".base_url('uploads/'.$p->image)."' class='w-12 h-12 rounded'/>"
                : "-";
				
				$row[] = $img;
				$row[] = $p->name;
				$row[] = $p->category_name;
				$row[] = number_format($p->originalPrice);
				$row[] = number_format($p->price);
				$row[] = $p->is_ready ? "Ready" : "Habis";
				
				$row[] = "
                <button onclick='editProduct(".$p->id.")' class='px-3 py-1 bg-yellow-500 text-white rounded'><i class='fas fa-edit mr-1'></i>Edit</button>
                <button onclick='deleteProduct(".$p->id.")' class='px-3 py-1 bg-red-500 text-white rounded'><i class='fas fa-trash mr-1'></i>Hapus</button>
				";
				
				$data[] = $row;
			}
			
			echo json_encode([
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Product_model->count_all(),
            "recordsFiltered" => $this->Product_model->count_filtered(),
            "data" => $data,
			]);
		}
		
		public function ajax_edit($id)
		{
			echo json_encode($this->Product_model->get($id));
		}
		
		public function ajax_save()
		{
			$id = $this->input->post('id');
			
			if ($_FILES['image']['name']) {
				$image = $this->_upload_image();
				} else {
				$image = $this->input->post('old_image');
			}
 
			$data = [
            'category_id' => $this->input->post('category_id'),
            'name'        => $this->input->post('name'),
            'price'       => $this->input->post('price'),
            'originalPrice' => $this->input->post('originalPrice'),
            'is_ready'    => $this->input->post('is_ready') ?: 0,
            'is_active'   => $this->input->post('is_active') ?: 0,
            'image'       => $image,
            'updated_at'  => date('Y-m-d H:i:s'),
			];
			
			if ($id == "") {
				$data['created_at'] = date('Y-m-d H:i:s');
				$this->Product_model->save($data);
				} else {
				$this->Product_model->update($id, $data);
			}
			
			echo json_encode(['status' => true]);
		}
		
		public function ajax_delete($id)
		{
			$product = $this->Product_model->get($id);
			if ($product->image && file_exists("./uploads/".$product->image)) {
				unlink("./uploads/".$product->image);
			}
			
			$this->Product_model->delete($id);
			
			echo json_encode(['status' => true]);
		}
		
		private function _upload_image()
		{
			$config['upload_path'] = './uploads/';
			$config['allowed_types'] = 'jpg|jpeg|png';
			$config['encrypt_name'] = TRUE;
			$config['max_size'] = 2048;
			
			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			if ($this->upload->do_upload('image')) {
				$filename = $this->upload->data('file_name');
				
				// resize 600px
				$config2['image_library'] = 'gd2';
				$config2['source_image'] = './uploads/'.$filename;
				$config2['maintain_ratio'] = TRUE;
				$config2['width'] = 600;
				
				$this->load->library('image_lib', $config2);
				$this->image_lib->resize();
				
				return $filename;
			}
			
			return null;
		}
	}
