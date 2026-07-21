<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Informasi extends Admin_Controller {
		
		public function __construct() {
			parent::__construct();
			$this->load->model('Informasi_model');
			$this->load->helper(['url', 'news', 'file', 'text']);
		}
		
		public function index() {
			$data['title'] = 'Warta Kemanusiaan';
			$this->render('admin/informasi/index', $data);
		}
		
		// ================= DATATABLE SERVER-SIDE =================
		
		public function ajax_list() {
			$list = $this->Informasi_model->get_datatables();
			$data = array();
			$no = $_POST['start'];
			
			foreach ($list as $row) {
				$no++;
				$has_image = act_news_has_image_file($row);
				$image_url = $has_image ? act_news_image_url($row) : '';

				if ($has_image) {
					$image_html = '<div class="news-thumb"><img src="' . html_escape($image_url) . '" alt=""></div>';
				} else {
					$image_html = '<div class="news-thumb news-thumb-empty"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg><span>Belum ada</span></div>';
				}

				$status_badge = '';
				if ($row->status === 'publish') {
					$status_badge = '<span class="badge badge-green">Publish</span>';
				} elseif ($row->status === 'draft') {
					$status_badge = '<span class="badge badge-amber">Draft</span>';
				} else {
					$status_badge = '<span class="badge badge-red">' . html_escape($row->status) . '</span>';
				}

				$data[] = [
					'id'        => $row->id,
					'title'     => '<div class="news-title">' . html_escape($row->title) . '</div>',
					'caption'   => '<div class="news-excerpt">' . html_escape(act_news_text_limit($row->caption, 120)) . '</div>',
					'urutan'    => $row->urutan,
					'image'     => $image_html,
					'status'    => $status_badge,
					'slug'      => html_escape($row->slug),
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
		
		private function _upload_config() {
			$config['upload_path']   = FCPATH . 'uploads/news/';
			$config['allowed_types'] = 'jpg|jpeg|png|webp';
			$config['max_size']      = 3072;
			$config['encrypt_name']  = TRUE;
			$config['file_ext_tolower'] = TRUE;
			$config['detect_mime']   = TRUE;
			$config['mod_mime_fix']  = TRUE;
			return $config;
		}

		public function save() {
			$id = $this->input->post('id');
			$title = $this->input->post('title');
			$caption = $this->input->post('caption');
			$urutan = $this->input->post('urutan');
			$status = $this->input->post('status');
			$remove_image = $this->input->post('remove_image');

			if (empty($title)) {
				echo json_encode(["status" => false, "message" => "Judul wajib diisi."]);
				return;
			}

			$data = [
				'title'   => $title,
				'caption' => $caption,
				'urutan'  => (int) $urutan ?: 0,
				'status'  => in_array($status, ['draft', 'publish', 'arsip']) ? $status : 'draft',
			];

			$is_new = ($id == "");

			if ($is_new) {
				$slug = act_news_slug_from_title($title);
				$slug = $this->_make_unique_slug($slug);
				$data['slug'] = $slug;
			} else {
				$submitted_slug = $this->input->post('slug');
				if (!empty($submitted_slug) && $submitted_slug !== '') {
					$submitted_slug = url_title(convert_accented_characters((string) $submitted_slug), 'dash', TRUE);
					if ($submitted_slug !== '') {
						$data['slug'] = $this->_make_unique_slug($submitted_slug, $id);
					}
				}
			}

			// Handle image upload
			if (!empty($_FILES['image']['name'])) {
				$this->load->library('upload', $this->_upload_config());

				if ($this->upload->do_upload('image')) {
					$upload_data = $this->upload->data();
					$data['image'] = $upload_data['file_name'];

					// Delete old image if editing
					if (!$is_new) {
						$old = $this->Informasi_model->getById($id);
						if ($old && !empty($old->image)) {
							$this->_delete_image_file($old->image, $id);
						}
					}
				} else {
					echo json_encode(["status" => false, "message" => strip_tags($this->upload->display_errors())]);
					return;
				}
			}

			// Handle remove image checkbox
			if ($remove_image === '1' && empty($_FILES['image']['name'])) {
				if (!$is_new) {
					$old = $this->Informasi_model->getById($id);
					if ($old && !empty($old->image)) {
						$this->_delete_image_file($old->image, $id);
					}
				}
				$data['image'] = null;
			}

			if ($is_new) {
				$this->Informasi_model->insert($data);
			} else {
				$data['updated_at'] = date('Y-m-d H:i:s');
				$this->Informasi_model->updateData($id, $data);
			}

			echo json_encode(["status" => true, "message" => $is_new ? "Berita berhasil ditambahkan." : "Berita berhasil diperbarui."]);
		}

		public function get($id) {
			$row = $this->Informasi_model->getById($id);
			if (!$row) {
				echo json_encode(null);
				return;
			}

			$row->image_url = act_news_image_url($row);
			$row->has_image = act_news_has_image_file($row);

			echo json_encode($row);
		}
		
		public function delete($id) {
			$row = $this->Informasi_model->getById($id);
			if ($row && !empty($row->image)) {
				$this->_delete_image_file($row->image, $id);
			}
			$this->Informasi_model->deleteData($id);
			echo json_encode(["status" => true]);
		}

		// ================= HELPERS =================

		private function _delete_image_file($filename, $exclude_id = null) {
			if (empty($filename)) return;
			$filename = basename($filename);
			// Jangan hapus jika masih dipakai berita lain
			if ($this->Informasi_model->count_by_image($filename, $exclude_id) > 0) return;
			$path = FCPATH . 'uploads/news/' . $filename;
			if (is_file($path)) {
				@unlink($path);
			}
		}

		private function _make_unique_slug($slug, $exclude_id = null) {
			$original = $slug;
			$counter = 1;
			while (!$this->Informasi_model->is_slug_unique($slug, $exclude_id)) {
				$slug = $original . '-' . $counter;
				$counter++;
			}
			return $slug;
		}
	}
