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
			$config['upload_path']   = $this->_news_upload_path();
			$config['allowed_types'] = 'jpg|jpeg|png|webp';
			$config['max_size']      = 15360;
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
			$old = $is_new ? null : $this->Informasi_model->getById($id);
			$new_image = null;
			$delete_old_image_after_save = false;

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
				$upload_ready = $this->_ensure_news_upload_path();
				if (!$upload_ready['status']) {
					echo json_encode($upload_ready);
					return;
				}

				$file_error = isset($_FILES['image']['error']) ? (int) $_FILES['image']['error'] : UPLOAD_ERR_OK;
				if ($file_error !== UPLOAD_ERR_OK) {
					echo json_encode(["status" => false, "message" => $this->_upload_error_message($file_error)]);
					return;
				}

				if ($this->_is_unsupported_heic($_FILES['image']['name'])) {
					echo json_encode(["status" => false, "message" => "Format HEIC/HEIF belum didukung. Pilih JPG, PNG, atau WebP."]);
					return;
				}

				$this->load->library('upload', $this->_upload_config());

				if ($this->upload->do_upload('image')) {
					$upload_data = $this->upload->data();
					$process_result = $this->_process_news_image($upload_data['full_path']);
					if (!$process_result['status']) {
						@unlink($upload_data['full_path']);
						echo json_encode($process_result);
						return;
					}

					$new_image = basename($upload_data['file_name']);
					$data['image'] = $new_image;
					$delete_old_image_after_save = (!$is_new && $old && !empty($old->image));
				} else {
					echo json_encode(["status" => false, "message" => strip_tags($this->upload->display_errors())]);
					return;
				}
			}

			// Handle remove image checkbox
			if ($remove_image === '1' && empty($_FILES['image']['name'])) {
				$data['image'] = null;
				$delete_old_image_after_save = (!$is_new && $old && !empty($old->image));
			}

			if ($is_new) {
				$saved = $this->Informasi_model->insert($data);
			} else {
				$data['updated_at'] = date('Y-m-d H:i:s');
				$saved = $this->Informasi_model->updateData($id, $data);
			}

			if (!$saved) {
				if (!empty($new_image)) {
					@unlink($this->_news_upload_path() . $new_image);
				}
				echo json_encode(["status" => false, "message" => "Data berita gagal disimpan. Gambar lama tetap dipertahankan."]);
				return;
			}

			if ($delete_old_image_after_save) {
				$this->_delete_image_file($old->image, $id);
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
			$deleted = $this->Informasi_model->deleteData($id);
			if ($deleted && $row && !empty($row->image)) {
				$this->_delete_image_file($row->image, $id);
			}
			echo json_encode(["status" => (bool) $deleted]);
		}

		// ================= HELPERS =================

		private function _news_upload_path() {
			return FCPATH . 'uploads/news/';
		}

		private function _ensure_news_upload_path() {
			$path = $this->_news_upload_path();
			if (!is_dir($path) && !@mkdir($path, 0755, TRUE)) {
				return ["status" => false, "message" => "Folder upload Warta tidak dapat dibuat."];
			}

			if (!is_writable($path)) {
				return ["status" => false, "message" => "Folder uploads/news tidak writable."];
			}

			return ["status" => true];
		}

		private function _upload_error_message($error_code) {
			$messages = [
				UPLOAD_ERR_INI_SIZE => 'Ukuran file melebihi batas server.',
				UPLOAD_ERR_FORM_SIZE => 'Ukuran file melebihi batas form.',
				UPLOAD_ERR_PARTIAL => 'Upload gambar tidak lengkap. Coba unggah ulang.',
				UPLOAD_ERR_NO_FILE => 'Tidak ada file gambar yang dipilih.',
				UPLOAD_ERR_NO_TMP_DIR => 'Folder temporary upload tidak tersedia di server.',
				UPLOAD_ERR_CANT_WRITE => 'Server gagal menulis file upload.',
				UPLOAD_ERR_EXTENSION => 'Upload dihentikan oleh extension PHP.',
			];

			return isset($messages[$error_code]) ? $messages[$error_code] : 'Upload gambar gagal.';
		}

		private function _is_unsupported_heic($filename) {
			$extension = strtolower(pathinfo((string) $filename, PATHINFO_EXTENSION));
			return in_array($extension, ['heic', 'heif'], TRUE);
		}

		private function _process_news_image($full_path) {
			$info = @getimagesize($full_path);
			if ($info === false || empty($info['mime'])) {
				return ["status" => false, "message" => "File yang diunggah bukan gambar valid."];
			}

			if (!in_array($info['mime'], ['image/jpeg', 'image/png', 'image/webp'], TRUE)) {
				return ["status" => false, "message" => "Format gambar tidak didukung. Pilih JPG, PNG, atau WebP."];
			}

			if ($info['mime'] === 'image/jpeg') {
				$orientation_result = $this->_fix_jpeg_orientation($full_path);
				if (!$orientation_result['status']) {
					return $orientation_result;
				}
				$info = @getimagesize($full_path);
				if ($info === false || empty($info['mime'])) {
					return ["status" => false, "message" => "Gambar JPEG gagal dibaca setelah koreksi orientasi."];
				}
			}

			if ($info[0] > 1600 || $info[1] > 1600) {
				if ($info['mime'] === 'image/webp' && !function_exists('imagecreatefromwebp')) {
					return ["status" => false, "message" => "Server belum mendukung optimasi WebP. Unggah JPG atau PNG."];
				}

				$resize_result = $this->_resize_news_image($full_path);
				if (!$resize_result['status']) {
					return $resize_result;
				}
				clearstatcache(TRUE, $full_path);
			}

			$final_info = @getimagesize($full_path);
			if ($final_info === false || empty($final_info['mime'])) {
				return ["status" => false, "message" => "Gambar gagal diproses setelah upload."];
			}

			if (filesize($full_path) > 1572864) {
				$compress_result = $this->_recompress_news_image($full_path, $final_info['mime']);
				if (!$compress_result['status']) {
					return $compress_result;
				}
			}

			return ["status" => true];
		}

		private function _fix_jpeg_orientation($full_path) {
			if (!function_exists('exif_read_data')) {
				return ["status" => true];
			}

			$exif = @exif_read_data($full_path);
			if (empty($exif['Orientation'])) {
				return ["status" => true];
			}

			$angle = 0;
			if ((int) $exif['Orientation'] === 3) {
				$angle = 180;
			} elseif ((int) $exif['Orientation'] === 6) {
				$angle = -90;
			} elseif ((int) $exif['Orientation'] === 8) {
				$angle = 90;
			}

			if ($angle === 0) {
				return ["status" => true];
			}

			if (!function_exists('imagecreatefromjpeg')) {
				return ["status" => false, "message" => "Server belum mendukung koreksi orientasi JPEG."];
			}

			$image = @imagecreatefromjpeg($full_path);
			if (!$image) {
				return ["status" => false, "message" => "Gambar JPEG gagal dibaca."];
			}

			$rotated = imagerotate($image, $angle, 0);
			imagedestroy($image);
			if (!$rotated) {
				return ["status" => false, "message" => "Orientasi gambar gagal diperbaiki."];
			}

			$saved = imagejpeg($rotated, $full_path, 90);
			imagedestroy($rotated);
			clearstatcache(TRUE, $full_path);

			return $saved ? ["status" => true] : ["status" => false, "message" => "Gambar JPEG gagal disimpan."];
		}

		private function _resize_news_image($full_path) {
			$config = [
				'image_library'  => 'gd2',
				'source_image'   => $full_path,
				'maintain_ratio' => TRUE,
				'width'          => 1600,
				'height'         => 1600,
				'quality'        => '85%',
				'create_thumb'   => FALSE,
			];

			$this->load->library('image_lib');
			$this->image_lib->clear();
			$this->image_lib->initialize($config);

			if (!$this->image_lib->resize()) {
				$error = strip_tags($this->image_lib->display_errors('', ''));
				$this->image_lib->clear();
				return ["status" => false, "message" => $error ?: "Gambar gagal dikompresi."];
			}

			$this->image_lib->clear();
			clearstatcache(TRUE, $full_path);
			return ["status" => true];
		}

		private function _recompress_news_image($full_path, $mime) {
			if ($mime === 'image/jpeg') {
				if (!function_exists('imagecreatefromjpeg')) {
					return ["status" => false, "message" => "Server belum mendukung kompresi JPEG."];
				}
				$image = @imagecreatefromjpeg($full_path);
				if (!$image) {
					return ["status" => false, "message" => "Gambar JPEG gagal dibaca."];
				}
				$saved = imagejpeg($image, $full_path, 85);
				imagedestroy($image);
				clearstatcache(TRUE, $full_path);
				return $saved ? ["status" => true] : ["status" => false, "message" => "Gambar JPEG gagal dikompresi."];
			}

			if ($mime === 'image/png') {
				if (!function_exists('imagecreatefrompng')) {
					return ["status" => false, "message" => "Server belum mendukung kompresi PNG."];
				}
				$image = @imagecreatefrompng($full_path);
				if (!$image) {
					return ["status" => false, "message" => "Gambar PNG gagal dibaca."];
				}
				imagealphablending($image, FALSE);
				imagesavealpha($image, TRUE);
				$saved = imagepng($image, $full_path, 6);
				imagedestroy($image);
				clearstatcache(TRUE, $full_path);
				return $saved ? ["status" => true] : ["status" => false, "message" => "Gambar PNG gagal dikompresi."];
			}

			if ($mime === 'image/webp') {
				if (!function_exists('imagecreatefromwebp') || !function_exists('imagewebp')) {
					return ["status" => true];
				}
				$image = @imagecreatefromwebp($full_path);
				if (!$image) {
					return ["status" => false, "message" => "Gambar WebP gagal dibaca."];
				}
				imagepalettetotruecolor($image);
				imagealphablending($image, TRUE);
				imagesavealpha($image, TRUE);
				$saved = imagewebp($image, $full_path, 85);
				imagedestroy($image);
				clearstatcache(TRUE, $full_path);
				return $saved ? ["status" => true] : ["status" => false, "message" => "Gambar WebP gagal dikompresi."];
			}

			return ["status" => true];
		}

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
