<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Slider extends Admin_Controller {

	private $slider_upload_folder = 'uploads/slider/';

	public function __construct() {
		parent::__construct();
		$this->load->model('Slider_model');
		$this->load->helper(['form','url']);
	}

	public function index() {
		$data['title'] = 'Slider';
		$data['slider'] = $this->Slider_model->get_all();
		$data['slider_has_extra_fields'] = $this->Slider_model->has_column('mobile_image');
		$this->render('admin/slider/index', $data);
	}

	public function ajax_list() {
		$filterStatus = $this->input->post('filterStatus');
		$list = $this->Slider_model->get_datatables($filterStatus);
		$data = [];
		$no = isset($_POST['start']) ? (int) $_POST['start'] : 0;

		foreach ($list as $s) {
			$no++;
			$row = [];
			$title = html_escape($s->title);
			$caption = !empty($s->caption) ? html_escape($s->caption) : '';
			$captionHtml = $caption ? '<div class="text-sm text-gray-500 truncate max-w-xs">'.$caption.'</div>' : '';
			$imageMeta = $this->_slider_image_meta(isset($s->image) ? $s->image : '');
			$sortOrder = $this->_field($s, 'sort_order', 0);

			$row[] = '
				<div class="flex items-center space-x-2">
					<input type="checkbox" value="'.(int) $s->id.'" class="row-checkbox rounded text-primary-600">
					<span>'.$no.'</span>
				</div>
			';

			$row[] = '
				<div>
					<div class="font-medium text-gray-800">'.$title.'</div>
					'.$captionHtml.'
					<div class="text-xs text-gray-400 mt-1">Urutan: '.(int) $sortOrder.'</div>
				</div>
			';

			if ($imageMeta['exists']) {
				$row[] = '
					<div class="relative group">
						<img src="'.$imageMeta['url'].'"
							alt="'.$title.'"
							class="w-20 h-12 object-cover rounded-lg cursor-pointer hover:opacity-90 transition-opacity duration-200"
							onclick="previewImage(\''.$imageMeta['url'].'\')">
						<div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 rounded-lg transition-all duration-200"></div>
					</div>
				';
			} else {
				$row[] = '
					<div class="w-20 h-12 rounded-lg border border-dashed border-gray-300 bg-gray-50 flex items-center justify-center text-[11px] text-gray-500 text-center leading-tight">
						Gambar tidak ditemukan
					</div>
				';
			}

			if ((int) $s->is_active === 1) {
				$row[] = '
					<span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
						<i class="fas fa-circle mr-1 text-xs"></i> Aktif
					</span>
				';
			} else {
				$row[] = '
					<span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
						<i class="fas fa-circle mr-1 text-xs"></i> Tidak Aktif
					</span>
				';
			}

			$created_date = !empty($s->created_at) ? date('d M Y', strtotime($s->created_at)) : '-';
			$created_time = !empty($s->created_at) ? date('H:i', strtotime($s->created_at)) : '';
			$row[] = '
				<div>
					<div class="text-sm text-gray-800">'.$created_date.'</div>
					<div class="text-xs text-gray-500">'.$created_time.'</div>
				</div>
			';

			$row[] = '
				<div class="flex space-x-2">
					<button onclick="editData('.(int) $s->id.')"
						class="ripple w-8 h-8 flex items-center justify-center bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-colors duration-200"
						data-tooltip="Edit">
						<i class="fas fa-edit text-sm"></i>
					</button>
					<button onclick="toggleStatus('.(int) $s->id.','.(int) $s->is_active.')"
						class="ripple w-8 h-8 flex items-center justify-center '.((int) $s->is_active === 1 ? 'bg-yellow-100 text-yellow-600' : 'bg-green-100 text-green-600').' rounded-lg hover:opacity-90 transition-colors duration-200"
						data-tooltip="'.((int) $s->is_active === 1 ? 'Nonaktifkan' : 'Aktifkan').'">
						<i class="fas '.((int) $s->is_active === 1 ? 'fa-eye-slash' : 'fa-eye').' text-sm"></i>
					</button>
					<button onclick="deleteData('.(int) $s->id.')"
						class="ripple w-8 h-8 flex items-center justify-center bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-colors duration-200"
						data-tooltip="Hapus">
						<i class="fas fa-trash text-sm"></i>
					</button>
				</div>
			';

			$data[] = $row;
		}

		echo json_encode([
			"draw" => isset($_POST['draw']) ? (int) $_POST['draw'] : 0,
			"recordsTotal" => $this->Slider_model->count_all(),
			"recordsFiltered" => $this->Slider_model->count_filtered($filterStatus),
			"data" => $data,
			"stats" => [
				"total" => $this->Slider_model->count_all(),
				"active" => $this->Slider_model->count_active(),
				"inactive" => $this->Slider_model->count_inactive()
			]
		]);
	}

	public function ajax_edit($id) {
		$row = $this->Slider_model->get_by_id((int) $id);
		if (!$row) {
			echo json_encode(['status' => false, 'message' => 'Slider tidak ditemukan']);
			return;
		}

		$imageMeta = $this->_slider_image_meta(isset($row->image) ? $row->image : '');
		$mobileMeta = $this->_slider_image_meta($this->_field($row, 'mobile_image', ''));

		$row->image_url = $imageMeta['url'];
		$row->image_exists = $imageMeta['exists'];
		$row->mobile_image_url = $mobileMeta['url'];
		$row->mobile_image_exists = $mobileMeta['exists'];
		$row->primary_button_text = $this->_field($row, 'primary_button_text', '');
		$row->primary_button_url = $this->_field($row, 'primary_button_url', '');
		$row->secondary_button_text = $this->_field($row, 'secondary_button_text', '');
		$row->secondary_button_url = $this->_field($row, 'secondary_button_url', '');
		$row->text_position = $this->_field($row, 'text_position', 'left');
		$row->overlay_opacity = $this->_field($row, 'overlay_opacity', 40);
		$row->sort_order = $this->_field($row, 'sort_order', 0);

		echo json_encode($row);
	}

	public function ajax_save() {
		$id = (int) $this->input->post('id');
		$old = $id ? $this->Slider_model->get_by_id($id) : null;

		if ($id && !$old) {
			$this->_json_error('Slider tidak ditemukan.');
			return;
		}

		$validation = $this->_validate_slider_input($id, $old);
		if (!$validation['status']) {
			$this->_json_error($validation['message']);
			return;
		}

		$uploadReady = $this->_ensure_slider_upload_path();
		if (!$uploadReady['status']) {
			$this->_json_error($uploadReady['message']);
			return;
		}

		$data = $validation['data'];
		$newFiles = [];

		if ($this->_has_upload('image')) {
			$upload = $this->_upload_slider_image('image', $uploadReady['path']);
			if (!$upload['status']) {
				$this->_json_error($upload['message']);
				return;
			}
			$data['image'] = $upload['file_name'];
			$newFiles[] = $upload['file_name'];
		}

		if ($this->_has_upload('mobile_image')) {
			$upload = $this->_upload_slider_image('mobile_image', $uploadReady['path']);
			if (!$upload['status']) {
				$this->_delete_slider_files($newFiles);
				$this->_json_error($upload['message']);
				return;
			}
			$data['mobile_image'] = $upload['file_name'];
			$newFiles[] = $upload['file_name'];
		} elseif ($id && $this->input->post('remove_mobile_image') === '1') {
			$data['mobile_image'] = null;
		}

		if ($id) {
			$data['updated_at'] = date('Y-m-d H:i:s');
			$saved = $this->Slider_model->update($id, $data);
		} else {
			$data['created_at'] = date('Y-m-d H:i:s');
			$saved = $this->Slider_model->insert($data);
		}

		if (!$saved) {
			$this->_delete_slider_files($newFiles);
			$this->_json_error('Data slider gagal disimpan.');
			return;
		}

		if ($id && $old) {
			if (isset($data['image']) && !empty($old->image)) {
				$this->_delete_slider_file($old->image);
			}
			$oldMobileImage = $this->_field($old, 'mobile_image', '');
			if ((isset($data['mobile_image']) || $this->input->post('remove_mobile_image') === '1') && $oldMobileImage !== '') {
				$this->_delete_slider_file($oldMobileImage);
			}
		}

		echo json_encode(['status' => true, 'success' => true, 'message' => 'Slider berhasil disimpan.']);
	}

	public function ajax_delete($id) {
		$deleted = $this->_delete_slider_record((int) $id);
		echo json_encode($deleted);
	}

	public function toggle_status($id) {
		if (!$this->input->is_ajax_request()) {
			show_404();
		}

		$status = $this->input->post('status');
		if (!in_array($status, ['0', '1'], true)) {
			$this->_json_error('Status tidak valid.');
			return;
		}

		$slider = $this->Slider_model->get_by_id((int) $id);
		if (!$slider) {
			$this->_json_error('Slider tidak ditemukan.');
			return;
		}

		$updated = $this->Slider_model->update_where(['id' => (int) $id], [
			'is_active' => (int) $status,
			'updated_at' => date('Y-m-d H:i:s'),
		]);

		echo json_encode([
			'status' => (bool) $updated,
			'success' => (bool) $updated,
			'message' => $updated ? 'Status berhasil diubah.' : 'Gagal mengubah status.',
			'new_status' => (int) $status,
		]);
	}

	public function bulk_toggle_status() {
		if (!$this->input->is_ajax_request()) {
			show_404();
		}

		$this->_bulk_set_status($this->input->post('status'));
	}

	public function bulk_activate() {
		if (!$this->input->is_ajax_request()) {
			show_404();
		}

		$this->_bulk_set_status('1');
	}

	public function bulk_deactivate() {
		if (!$this->input->is_ajax_request()) {
			show_404();
		}

		$this->_bulk_set_status('0');
	}

	private function _bulk_set_status($status)
	{
		$ids = $this->_clean_ids($this->input->post('ids'));

		if (empty($ids)) {
			$this->_json_error('Tidak ada data yang dipilih.');
			return;
		}

		if (!in_array($status, ['0', '1'], true)) {
			$this->_json_error('Status tidak valid.');
			return;
		}

		$updated = $this->Slider_model->bulk_update($ids, [
			'is_active' => (int) $status,
			'updated_at' => date('Y-m-d H:i:s'),
		]);

		echo json_encode([
			'status' => (bool) $updated,
			'success' => (bool) $updated,
			'message' => $updated ? 'Status slider terpilih berhasil diubah.' : 'Gagal mengubah status slider.',
		]);
	}

	public function bulk_delete() {
		if (!$this->input->is_ajax_request()) {
			show_404();
		}

		$ids = $this->_clean_ids($this->input->post('ids'));
		if (empty($ids)) {
			$this->_json_error('Tidak ada data yang dipilih.');
			return;
		}

		$success = 0;
		$failed = 0;

		foreach ($ids as $id) {
			$result = $this->_delete_slider_record($id);
			if (!empty($result['status'])) {
				$success++;
			} else {
				$failed++;
			}
		}

		echo json_encode([
			'status' => $failed === 0,
			'success' => $failed === 0,
			'message' => "Berhasil menghapus {$success} slider, gagal: {$failed}.",
		]);
	}

	private function _validate_slider_input($id, $old)
	{
		$title = trim((string) $this->input->post('title'));
		if ($title === '') {
			return ['status' => false, 'message' => 'Judul wajib diisi.'];
		}

		if (!$id && !$this->_has_upload('image')) {
			return ['status' => false, 'message' => 'Gambar utama wajib diunggah saat menambah slider.'];
		}

		if ($id && !$this->_has_upload('image') && (!$old || empty($old->image))) {
			return ['status' => false, 'message' => 'Gambar utama wajib tersedia.'];
		}

		$textPosition = trim((string) $this->input->post('text_position'));
		$textPosition = $textPosition !== '' ? $textPosition : 'left';
		if (!in_array($textPosition, ['left', 'center', 'right'], true)) {
			return ['status' => false, 'message' => 'Posisi teks tidak valid.'];
		}

		$overlay = (int) $this->input->post('overlay_opacity');
		if ($overlay < 0 || $overlay > 80) {
			return ['status' => false, 'message' => 'Overlay harus berada di antara 0 sampai 80%.'];
		}

		$sortOrder = $this->input->post('sort_order');
		$sortOrder = $sortOrder === '' || $sortOrder === null ? 0 : $sortOrder;
		if (!ctype_digit((string) $sortOrder)) {
			return ['status' => false, 'message' => 'Urutan tampil harus berupa bilangan bulat minimal 0.'];
		}

		$primaryUrl = trim((string) $this->input->post('primary_button_url'));
		$secondaryUrl = trim((string) $this->input->post('secondary_button_url'));
		foreach ([$primaryUrl, $secondaryUrl] as $url) {
			if ($url !== '' && !$this->_is_safe_button_url($url)) {
				return ['status' => false, 'message' => 'URL tombol harus berupa URL http/https atau path internal yang aman.'];
			}
		}

		return [
			'status' => true,
			'data' => [
				'title' => $title,
				'caption' => trim((string) $this->input->post('caption')),
				'primary_button_text' => trim((string) $this->input->post('primary_button_text')),
				'primary_button_url' => $primaryUrl,
				'secondary_button_text' => trim((string) $this->input->post('secondary_button_text')),
				'secondary_button_url' => $secondaryUrl,
				'text_position' => $textPosition,
				'overlay_opacity' => $overlay,
				'sort_order' => (int) $sortOrder,
				'is_active' => $this->input->post('is_active') === '0' ? 0 : 1,
			],
		];
	}

	private function _is_safe_button_url($url)
	{
		if (preg_match('#^https?://#i', $url)) {
			return filter_var($url, FILTER_VALIDATE_URL) !== false;
		}

		if (strpos($url, '#') === 0) {
			return (bool) preg_match('/^#[A-Za-z0-9_-]+$/', $url);
		}

		return (bool) preg_match('#^/[A-Za-z0-9/_.,~%?=&:+-]*$#', $url);
	}

	private function _upload_slider_image($field, $path)
	{
		$extension = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
		if (in_array($extension, ['heic', 'heif'], true)) {
			return ['status' => false, 'message' => 'Format HEIC/HEIF belum didukung. Pilih JPG, PNG, atau WebP.'];
		}

		$config = [
			'upload_path' => $path,
			'allowed_types' => 'jpg|jpeg|png|webp',
			'max_size' => 8192,
			'encrypt_name' => true,
			'detect_mime' => true,
		];

		$this->load->library('upload');
		$this->upload->initialize($config, true);

		if (!$this->upload->do_upload($field)) {
			return [
				'status' => false,
				'message' => strip_tags($this->upload->display_errors('', '')),
			];
		}

		$uploadData = $this->upload->data();
		$fullPath = $uploadData['full_path'];
		$imageInfo = @getimagesize($fullPath);
		if ($imageInfo === false || empty($imageInfo['mime']) || strpos($imageInfo['mime'], 'image/') !== 0) {
			@unlink($fullPath);
			return ['status' => false, 'message' => 'File yang diunggah bukan gambar valid.'];
		}

		return [
			'status' => true,
			'file_name' => $uploadData['file_name'],
			'full_path' => $fullPath,
		];
	}

	private function _ensure_slider_upload_path()
	{
		$basePath = realpath(FCPATH);
		if ($basePath === false) {
			$basePath = rtrim(FCPATH, '/\\');
		}

		$path = $basePath . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'slider' . DIRECTORY_SEPARATOR;
		if (!is_dir($path) && !mkdir($path, 0775, true) && !is_dir($path)) {
			return ['status' => false, 'message' => 'Folder upload slider tidak dapat dibuat.'];
		}

		$realPath = realpath($path);
		if ($realPath === false || !is_dir($realPath)) {
			return ['status' => false, 'message' => 'Path upload slider tidak valid.'];
		}

		if (!is_writable($realPath)) {
			return ['status' => false, 'message' => 'Folder uploads/slider tidak writable.'];
		}

		$this->_ensure_upload_htaccess($realPath);

		return ['status' => true, 'path' => $realPath . DIRECTORY_SEPARATOR];
	}

	private function _ensure_upload_htaccess($realPath)
	{
		$file = $realPath . DIRECTORY_SEPARATOR . '.htaccess';
		if (is_file($file)) {
			return;
		}

		$content = "<FilesMatch \"\\.(php|phtml|phar|cgi|pl|py|sh|asp|aspx|jsp)$\">\nRequire all denied\n</FilesMatch>\n";
		@file_put_contents($file, $content);
	}

	private function _slider_image_meta($filename)
	{
		$filename = trim((string) $filename);
		if ($filename === '') {
			return ['exists' => false, 'url' => '', 'file' => ''];
		}

		$file = basename($filename);
		if ($file === '' || $file !== $filename) {
			return ['exists' => false, 'url' => '', 'file' => $file];
		}

		$path = FCPATH . $this->slider_upload_folder . $file;
		if (!is_file($path)) {
			return ['exists' => false, 'url' => '', 'file' => $file];
		}

		return [
			'exists' => true,
			'url' => base_url($this->slider_upload_folder . $file),
			'file' => $file,
		];
	}

	private function _delete_slider_record($id)
	{
		$row = $this->Slider_model->get_by_id((int) $id);
		if (!$row) {
			return ['status' => false, 'success' => false, 'message' => 'Slider tidak ditemukan.'];
		}

		$files = [];
		if (!empty($row->image)) {
			$files[] = $row->image;
		}
		$mobileImage = $this->_field($row, 'mobile_image', '');
		if ($mobileImage !== '') {
			$files[] = $mobileImage;
		}

		$deleted = $this->Slider_model->delete((int) $id);
		if ($deleted) {
			$this->_delete_slider_files($files);
		}

		return [
			'status' => (bool) $deleted,
			'success' => (bool) $deleted,
			'message' => $deleted ? 'Slider berhasil dihapus.' : 'Slider gagal dihapus.',
		];
	}

	private function _delete_slider_files($files)
	{
		foreach ($files as $file) {
			$this->_delete_slider_file($file);
		}
	}

	private function _delete_slider_file($filename)
	{
		$filename = basename((string) $filename);
		if ($filename === '') {
			return;
		}

		$path = FCPATH . $this->slider_upload_folder . $filename;
		$uploadRoot = realpath(FCPATH . $this->slider_upload_folder);
		$realFile = is_file($path) ? realpath($path) : false;

		if ($uploadRoot && $realFile && strpos($realFile, $uploadRoot . DIRECTORY_SEPARATOR) === 0) {
			@unlink($realFile);
		}
	}

	private function _has_upload($field)
	{
		return isset($_FILES[$field]) && isset($_FILES[$field]['error']) && (int) $_FILES[$field]['error'] !== UPLOAD_ERR_NO_FILE && !empty($_FILES[$field]['name']);
	}

	private function _clean_ids($ids)
	{
		if (!is_array($ids)) {
			return [];
		}

		$clean = [];
		foreach ($ids as $id) {
			$id = (int) $id;
			if ($id > 0) {
				$clean[] = $id;
			}
		}

		return array_values(array_unique($clean));
	}

	private function _field($row, $field, $default = '')
	{
		return isset($row->{$field}) ? $row->{$field} : $default;
	}

	private function _json_error($message)
	{
		echo json_encode(['status' => false, 'success' => false, 'message' => $message]);
	}
}
