<div class="container mx-auto px-4">
	<?php $slider_has_extra_fields = !empty($slider_has_extra_fields); ?>
	<div class="mb-8">
		<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
			<div>
				<h1 class="text-3xl font-bold text-gray-800 mb-2">Hero Slider</h1>
				<p class="text-gray-600">Kelola gambar hero dinamis untuk halaman publik.</p>
			</div>
			<button onclick="addData()"
				class="ripple mt-4 md:mt-0 px-6 py-3 bg-gradient-to-r from-primary-600 to-secondary-600 text-white font-medium rounded-xl hover:from-primary-700 hover:to-secondary-700 transition-all duration-200 shadow-md hover:shadow-lg flex items-center space-x-2">
				<i class="fas fa-plus-circle"></i>
				<span>Tambah Slider</span>
			</button>
		</div>

		<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
			<div class="bg-white p-5 rounded-xl shadow-card hover-lift">
				<div class="flex items-center justify-between">
					<div>
						<p class="text-sm text-gray-500">Total Slider</p>
						<p class="text-2xl font-bold text-gray-800" id="totalSliders">0</p>
					</div>
					<div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
						<i class="fas fa-images text-blue-600 text-xl"></i>
					</div>
				</div>
			</div>
			<div class="bg-white p-5 rounded-xl shadow-card hover-lift">
				<div class="flex items-center justify-between">
					<div>
						<p class="text-sm text-gray-500">Aktif</p>
						<p class="text-2xl font-bold text-gray-800" id="activeSliders">0</p>
					</div>
					<div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
						<i class="fas fa-check-circle text-green-600 text-xl"></i>
					</div>
				</div>
			</div>
			<div class="bg-white p-5 rounded-xl shadow-card hover-lift">
				<div class="flex items-center justify-between">
					<div>
						<p class="text-sm text-gray-500">Non-Aktif</p>
						<p class="text-2xl font-bold text-gray-800" id="inactiveSliders">0</p>
					</div>
					<div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
						<i class="fas fa-times-circle text-red-600 text-xl"></i>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="bg-white rounded-2xl shadow-card overflow-hidden">
		<div class="px-6 py-4 border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between">
			<div class="mb-4 md:mb-0">
				<h2 class="text-lg font-semibold text-gray-800">Daftar Slider</h2>
				<p class="text-sm text-gray-600">Slide aktif akan dipakai sebagai hero homepage.</p>
			</div>
			<div class="flex space-x-3">
				<div class="relative">
					<select id="filterStatus" onchange="filterTable()"
						class="appearance-none pl-10 pr-8 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent text-sm">
						<option value="">Semua Status</option>
						<option value="1">Aktif</option>
						<option value="0">Tidak Aktif</option>
					</select>
					<i class="fas fa-filter absolute left-3 top-3 text-gray-400 text-sm"></i>
				</div>
				<button onclick="refreshTable()"
					class="ripple px-4 py-2 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200 text-gray-700">
					<i class="fas fa-redo"></i>
				</button>
			</div>
		</div>

		<div class="p-4 md:p-6">
			<div class="overflow-x-auto rounded-lg border border-gray-100">
				<table id="table" class="w-full">
					<thead>
						<tr class="bg-gray-50">
							<th class="py-4 px-4 text-left">
								<div class="flex items-center space-x-2">
									<input type="checkbox" id="selectAll" class="rounded text-primary-600">
									<span>#</span>
								</div>
							</th>
							<th class="py-4 px-4 text-left font-medium text-gray-700">Judul</th>
							<th class="py-4 px-4 text-left font-medium text-gray-700">Gambar</th>
							<th class="py-4 px-4 text-left font-medium text-gray-700">Status</th>
							<th class="py-4 px-4 text-left font-medium text-gray-700">Tanggal</th>
							<th class="py-4 px-4 text-left font-medium text-gray-700">Aksi</th>
						</tr>
					</thead>
					<tbody class="divide-y divide-gray-100"></tbody>
				</table>
			</div>

			<div id="bulkActions" class="hidden mt-4 p-4 bg-blue-50 rounded-lg border border-blue-100">
				<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
					<span class="text-sm text-blue-700" id="selectedCount">0 item terpilih</span>
					<div class="flex flex-wrap gap-2">
						<button onclick="bulkActivate()" class="ripple px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-colors duration-200 flex items-center space-x-1">
							<i class="fas fa-check"></i>
							<span>Aktifkan</span>
						</button>
						<button onclick="bulkDeactivate()" class="ripple px-4 py-2 bg-yellow-600 text-white text-sm rounded-lg hover:bg-yellow-700 transition-colors duration-200 flex items-center space-x-1">
							<i class="fas fa-times"></i>
							<span>Nonaktifkan</span>
						</button>
						<button onclick="bulkDelete()" class="ripple px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition-colors duration-200 flex items-center space-x-1">
							<i class="fas fa-trash"></i>
							<span>Hapus</span>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="modalSlider" class="fixed inset-0 bg-black bg-opacity-60 hidden flex items-center justify-center z-50 p-4">
	<div class="bg-white w-full max-w-4xl max-h-[92vh] rounded-2xl shadow-2xl overflow-hidden animate-fade-in flex flex-col">
		<div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gradient-to-r from-primary-50 to-secondary-50">
			<div>
				<h2 id="modalTitle" class="text-xl font-bold text-gray-800">Tambah Slider</h2>
				<p id="modalSubtitle" class="text-sm text-gray-600">Tambahkan hero slider baru</p>
			</div>
			<button onclick="closeModal()" class="ripple w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full transition-colors duration-200">
				<i class="fas fa-times"></i>
			</button>
		</div>

		<div class="p-6 overflow-y-auto">
			<form id="formData" enctype="multipart/form-data" class="space-y-5">
				<input type="hidden" name="id" id="id">
				<?php if (!$slider_has_extra_fields): ?>
				<div class="rounded-xl border border-yellow-200 bg-yellow-50 px-4 py-3 text-sm text-yellow-800">
					Migration Hero Slider belum dijalankan. Untuk sementara hanya Judul, Caption, Gambar Utama, dan Status yang akan disimpan.
				</div>
				<?php endif; ?>

				<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
					<div>
						<label class="block text-sm font-medium text-gray-700 mb-2">Judul <span class="text-red-500">*</span></label>
						<input type="text" name="title" id="title" maxlength="150"
							class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200"
							placeholder="Contoh: Bergerak cepat menolong sesama" required>
					</div>
					<div class="<?= $slider_has_extra_fields ? '' : 'opacity-50 pointer-events-none' ?>">
						<label class="block text-sm font-medium text-gray-700 mb-2">Urutan Tampil</label>
						<input type="number" name="sort_order" id="sort_order" min="0" step="1" value="0"
							class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200" <?= $slider_has_extra_fields ? '' : 'disabled' ?>>
					</div>
				</div>

				<div>
					<label class="block text-sm font-medium text-gray-700 mb-2">Caption</label>
					<textarea name="caption" id="caption" rows="3"
						class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200"
						placeholder="Teks pendek yang menjelaskan slide"></textarea>
				</div>

				<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
					<div>
						<label class="block text-sm font-medium text-gray-700 mb-2">Gambar Utama/Desktop <span class="text-red-500">*</span></label>
						<p class="text-xs text-gray-500 mb-3">Rekomendasi 1920 x 750 px. Format JPG, JPEG, PNG, WebP. Maksimal 8 MB.</p>
						<div id="imagePreview" class="mb-3 hidden">
							<div class="relative w-full aspect-[16/6] rounded-lg overflow-hidden border border-gray-200 bg-gray-50">
								<img id="previewImage" src="" alt="Preview gambar utama" class="w-full h-full object-cover cursor-pointer" onclick="triggerFile('imageInput')">
								<div id="imageMissing" class="hidden absolute inset-0 items-center justify-center bg-gray-100 text-sm text-gray-500 text-center px-4">Gambar tidak ditemukan</div>
								<button type="button" onclick="removePreview('image')" class="absolute top-2 right-2 ripple w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600">
									<i class="fas fa-times text-sm"></i>
								</button>
							</div>
							<p class="text-xs text-gray-500 mt-2">Klik gambar untuk mengganti.</p>
						</div>
						<div id="uploadArea" class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-primary-400 hover:bg-primary-50 transition-all duration-200 cursor-pointer" onclick="triggerFile('imageInput')">
							<input type="file" name="image" id="imageInput" class="hidden" accept="image/jpeg,image/png,image/webp">
							<div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-3">
								<i class="fas fa-cloud-upload-alt text-primary-600 text-xl"></i>
							</div>
							<p class="text-sm font-medium text-gray-700">Klik untuk upload gambar utama</p>
							<p class="text-xs text-gray-500 mt-1">Wajib saat tambah, opsional saat edit.</p>
						</div>
					</div>

					<div class="<?= $slider_has_extra_fields ? '' : 'opacity-50 pointer-events-none' ?>">
						<label class="block text-sm font-medium text-gray-700 mb-2">Gambar Mobile</label>
						<p class="text-xs text-gray-500 mb-3">Opsional. Rekomendasi 900 x 1200 px. Format JPG, JPEG, PNG, WebP. Maksimal 8 MB.</p>
						<div id="mobileImagePreview" class="mb-3 hidden">
							<div class="relative w-full aspect-[3/4] rounded-lg overflow-hidden border border-gray-200 bg-gray-50">
								<img id="mobilePreviewImage" src="" alt="Preview gambar mobile" class="w-full h-full object-cover cursor-pointer" onclick="triggerFile('mobileImageInput')">
								<div id="mobileImageMissing" class="hidden absolute inset-0 items-center justify-center bg-gray-100 text-sm text-gray-500 text-center px-4">Gambar tidak ditemukan</div>
								<button type="button" onclick="removePreview('mobile')" class="absolute top-2 right-2 ripple w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600">
									<i class="fas fa-times text-sm"></i>
								</button>
							</div>
							<label class="mt-2 flex items-center space-x-2 text-sm text-gray-600">
								<input type="checkbox" name="remove_mobile_image" id="remove_mobile_image" value="1" class="rounded text-red-600" <?= $slider_has_extra_fields ? '' : 'disabled' ?>>
								<span>Hapus gambar mobile saat ini</span>
							</label>
						</div>
						<div id="mobileUploadArea" class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-primary-400 hover:bg-primary-50 transition-all duration-200 cursor-pointer" onclick="triggerFile('mobileImageInput')">
							<input type="file" name="mobile_image" id="mobileImageInput" class="hidden" accept="image/jpeg,image/png,image/webp" <?= $slider_has_extra_fields ? '' : 'disabled' ?>>
							<div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-3">
								<i class="fas fa-mobile-screen text-primary-600 text-xl"></i>
							</div>
							<p class="text-sm font-medium text-gray-700">Klik untuk upload gambar mobile</p>
							<p class="text-xs text-gray-500 mt-1">Jika kosong, gambar utama dipakai di semua layar.</p>
						</div>
					</div>
				</div>

				<div class="grid grid-cols-1 md:grid-cols-2 gap-4 <?= $slider_has_extra_fields ? '' : 'opacity-50 pointer-events-none' ?>">
					<div>
						<label class="block text-sm font-medium text-gray-700 mb-2">Teks Tombol Utama</label>
						<input type="text" name="primary_button_text" id="primary_button_text" maxlength="100" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="Donasi Sekarang" <?= $slider_has_extra_fields ? '' : 'disabled' ?>>
					</div>
					<div>
						<label class="block text-sm font-medium text-gray-700 mb-2">URL Tombol Utama</label>
						<input type="text" name="primary_button_url" id="primary_button_url" maxlength="500" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="#donasi atau https://..." <?= $slider_has_extra_fields ? '' : 'disabled' ?>>
					</div>
					<div>
						<label class="block text-sm font-medium text-gray-700 mb-2">Teks Tombol Kedua</label>
						<input type="text" name="secondary_button_text" id="secondary_button_text" maxlength="100" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="Daftar Relawan" <?= $slider_has_extra_fields ? '' : 'disabled' ?>>
					</div>
					<div>
						<label class="block text-sm font-medium text-gray-700 mb-2">URL Tombol Kedua</label>
						<input type="text" name="secondary_button_url" id="secondary_button_url" maxlength="500" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="#relawan atau /news" <?= $slider_has_extra_fields ? '' : 'disabled' ?>>
					</div>
				</div>

				<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
					<div class="<?= $slider_has_extra_fields ? '' : 'opacity-50 pointer-events-none' ?>">
						<label class="block text-sm font-medium text-gray-700 mb-2">Posisi Teks</label>
						<select name="text_position" id="text_position" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500" <?= $slider_has_extra_fields ? '' : 'disabled' ?>>
							<option value="left">Kiri</option>
							<option value="center">Tengah</option>
							<option value="right">Kanan</option>
						</select>
					</div>
					<div class="<?= $slider_has_extra_fields ? '' : 'opacity-50 pointer-events-none' ?>">
						<label class="block text-sm font-medium text-gray-700 mb-2">Overlay Gelap: <span id="overlayValue">40%</span></label>
						<input type="range" name="overlay_opacity" id="overlay_opacity" min="0" max="80" value="40" class="w-full" <?= $slider_has_extra_fields ? '' : 'disabled' ?>>
					</div>
					<div>
						<label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
						<div class="flex space-x-4 pt-3">
							<label class="flex items-center space-x-2 cursor-pointer">
								<input type="radio" name="is_active" value="1" id="statusActive" class="w-4 h-4 text-primary-600 focus:ring-primary-500">
								<span class="text-gray-700">Aktif</span>
							</label>
							<label class="flex items-center space-x-2 cursor-pointer">
								<input type="radio" name="is_active" value="0" id="statusInactive" class="w-4 h-4 text-primary-600 focus:ring-primary-500">
								<span class="text-gray-700">Tidak Aktif</span>
							</label>
						</div>
					</div>
				</div>
			</form>
		</div>

		<div class="px-6 py-4 border-t border-gray-100 flex justify-end space-x-3 bg-gray-50">
			<button onclick="closeModal()" class="ripple px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-all duration-200">Batal</button>
			<button onclick="saveData()" id="saveButton" class="ripple px-6 py-2 bg-primary-600 text-white font-medium rounded-xl hover:bg-primary-700 transition-all duration-200 shadow-md hover:shadow-lg flex items-center space-x-2">
				<i class="fas fa-save"></i>
				<span>Simpan</span>
			</button>
		</div>
	</div>
</div>

<div id="previewModal" class="fixed inset-0 bg-black bg-opacity-90 hidden flex items-center justify-center z-50 p-4">
	<div class="relative w-full max-w-4xl">
		<button onclick="closePreview()" class="absolute top-4 right-4 ripple w-10 h-10 bg-white text-gray-800 rounded-full flex items-center justify-center z-10 hover:bg-gray-100">
			<i class="fas fa-times"></i>
		</button>
		<img id="fullPreview" src="" alt="Full Preview" class="w-full h-auto rounded-lg">
	</div>
</div>

<script>
	let table;
	let selectedItems = new Set();

	$(document).ready(function () {
		table = $('#table').DataTable({
			processing: true,
			serverSide: true,
			ajax: {
				url: "<?= site_url('admin/slider/ajax_list') ?>",
				type: "POST",
				data: function(d) {
					d.filterStatus = $('#filterStatus').val();
				}
			},
			columns: [
				{ data: 0, orderable: false },
				{ data: 1 },
				{ data: 2, orderable: false },
				{ data: 3 },
				{ data: 4 },
				{ data: 5, orderable: false }
			],
			order: [[1, 'asc']],
			language: {
				processing: '<div class="flex items-center"><div class="spinner-border animate-spin inline-block w-4 h-4 border-2 rounded-full border-primary-500 border-t-transparent mr-2"></div>Memproses...</div>',
				lengthMenu: "Tampilkan _MENU_ data",
				zeroRecords: "Data tidak ditemukan",
				info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
				infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
				infoFiltered: "(disaring dari _MAX_ total data)",
				search: "Cari:",
				paginate: {
					first: "<i class='fas fa-angle-double-left'></i>",
					last: "<i class='fas fa-angle-double-right'></i>",
					previous: "<i class='fas fa-angle-left'></i>",
					next: "<i class='fas fa-angle-right'></i>"
				}
			},
			drawCallback: function(settings) {
				if (settings.json && settings.json.stats) {
					$('#totalSliders').text(settings.json.stats.total);
					$('#activeSliders').text(settings.json.stats.active);
					$('#inactiveSliders').text(settings.json.stats.inactive);
				}

				$('[data-tooltip]').hover(function() {
					const tooltip = $(this).attr('data-tooltip');
					if (tooltip) {
						$(this).append(`<div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-800 text-white text-xs rounded whitespace-nowrap z-50">${tooltip}</div>`);
					}
				}, function() {
					$(this).find('div').remove();
				});

				$('.row-checkbox').off('change').on('change', function() {
					const id = $(this).val();
					if ($(this).is(':checked')) {
						selectedItems.add(id);
					} else {
						selectedItems.delete(id);
					}
					updateBulkActions();
				});
			},
			responsive: {
				details: {
					display: $.fn.dataTable.Responsive.display.childRowImmediate,
					type: ''
				}
			}
		});

		$('#selectAll').on('change', function() {
			const isChecked = $(this).is(':checked');
			$('.row-checkbox').prop('checked', isChecked).trigger('change');
		});

		$('#imageInput').on('change', function(e) {
			showLocalPreview(e.target.files[0], 'image');
		});

		$('#mobileImageInput').on('change', function(e) {
			$('#remove_mobile_image').prop('checked', false);
			showLocalPreview(e.target.files[0], 'mobile');
		});

		$('#overlay_opacity').on('input', function() {
			$('#overlayValue').text($(this).val() + '%');
		});
	});

	function triggerFile(id) {
		document.getElementById(id).click();
	}

	function showLocalPreview(file, type) {
		if (!file) return;
		const allowed = ['image/jpeg', 'image/png', 'image/webp'];
		if (allowed.indexOf(file.type) === -1) {
			showToast('warning', 'Format gambar harus JPG, JPEG, PNG, atau WebP');
			return;
		}
		const reader = new FileReader();
		reader.onload = function(e) {
			setPreview(type, e.target.result, true);
		};
		reader.readAsDataURL(file);
	}

	function setPreview(type, src, exists) {
		const isMobile = type === 'mobile';
		const preview = isMobile ? '#mobileImagePreview' : '#imagePreview';
		const upload = isMobile ? '#mobileUploadArea' : '#uploadArea';
		const img = isMobile ? '#mobilePreviewImage' : '#previewImage';
		const missing = isMobile ? '#mobileImageMissing' : '#imageMissing';

		$(preview).removeClass('hidden');
		$(upload).addClass('hidden');

		if (exists && src) {
			$(img).attr('src', src).removeClass('hidden');
			$(missing).addClass('hidden').removeClass('flex');
		} else {
			$(img).attr('src', '').addClass('hidden');
			$(missing).removeClass('hidden').addClass('flex');
		}
	}

	function removePreview(type) {
		if (type === 'mobile') {
			$('#mobileImageInput').val('');
			$('#mobileImagePreview').addClass('hidden');
			$('#mobileUploadArea').removeClass('hidden');
			$('#remove_mobile_image').prop('checked', true);
			return;
		}

		$('#imageInput').val('');
		$('#imagePreview').addClass('hidden');
		$('#uploadArea').removeClass('hidden');
	}

	function filterTable() {
		showLoading();
		table.ajax.reload(function() {
			hideLoading();
		}, false);
	}

	function refreshTable() {
		showLoading();
		table.ajax.reload(function() {
			hideLoading();
			showToast('success', 'Data diperbarui');
		}, false);
	}

	function updateBulkActions() {
		const count = selectedItems.size;
		if (count > 0) {
			$('#selectedCount').text(`${count} item terpilih`);
			$('#bulkActions').removeClass('hidden');
			$('#selectAll').prop('checked', count === $('.row-checkbox').length);
		} else {
			$('#bulkActions').addClass('hidden');
			$('#selectAll').prop('checked', false);
		}
	}

	function addData() {
		$('#formData')[0].reset();
		$('#id').val('');
		$('#sort_order').val('0');
		$('#overlay_opacity').val('40');
		$('#overlayValue').text('40%');
		$('#text_position').val('left');
		$('#statusActive').prop('checked', true);
		$('#imagePreview, #mobileImagePreview').addClass('hidden');
		$('#uploadArea, #mobileUploadArea').removeClass('hidden');
		$('#remove_mobile_image').prop('checked', false);
		$('#modalTitle').text('Tambah Slider');
		$('#modalSubtitle').text('Tambahkan hero slider baru');
		openModal();
	}

	function editData(id) {
		showLoading();
		$.get("<?= site_url('admin/slider/ajax_edit/') ?>" + id, function (data) {
			hideLoading();
			if (data.status === false) {
				showToast('error', data.message || 'Gagal memuat data');
				return;
			}

			$('#formData')[0].reset();
			$('#id').val(data.id);
			$('#title').val(data.title || '');
			$('#caption').val(data.caption || '');
			$('#primary_button_text').val(data.primary_button_text || '');
			$('#primary_button_url').val(data.primary_button_url || '');
			$('#secondary_button_text').val(data.secondary_button_text || '');
			$('#secondary_button_url').val(data.secondary_button_url || '');
			$('#text_position').val(data.text_position || 'left');
			$('#overlay_opacity').val(data.overlay_opacity || 40);
			$('#overlayValue').text((data.overlay_opacity || 40) + '%');
			$('#sort_order').val(data.sort_order || 0);
			$('#remove_mobile_image').prop('checked', false);

			if (data.image) {
				setPreview('image', data.image_url || '', data.image_exists === true || data.image_exists === '1');
			} else {
				$('#imagePreview').addClass('hidden');
				$('#uploadArea').removeClass('hidden');
			}

			if (data.mobile_image) {
				setPreview('mobile', data.mobile_image_url || '', data.mobile_image_exists === true || data.mobile_image_exists === '1');
			} else {
				$('#mobileImagePreview').addClass('hidden');
				$('#mobileUploadArea').removeClass('hidden');
			}

			if (parseInt(data.is_active, 10) === 1) {
				$('#statusActive').prop('checked', true);
			} else {
				$('#statusInactive').prop('checked', true);
			}

			$('#modalTitle').text('Edit Slider');
			$('#modalSubtitle').text('Edit slider: ' + (data.title || ''));
			openModal();
		}, 'json').fail(function() {
			hideLoading();
			showToast('error', 'Gagal memuat data');
		});
	}

	function isSafeButtonUrl(value) {
		if (!value) return true;
		return /^(https?:\/\/[^\s]+|#[A-Za-z0-9_-]+|\/[A-Za-z0-9/_.,~%?=&:+-]*)$/.test(value);
	}

	function saveData() {
		if (!$('#title').val().trim()) {
			showToast('warning', 'Judul wajib diisi');
			$('#title').focus();
			return;
		}

		if (!$('#id').val() && !$('#imageInput')[0].files[0]) {
			showToast('warning', 'Gambar utama wajib diunggah saat menambah slider');
			return;
		}

		if (!isSafeButtonUrl($('#primary_button_url').val().trim()) || !isSafeButtonUrl($('#secondary_button_url').val().trim())) {
			showToast('warning', 'URL tombol harus berupa URL http/https atau path internal yang aman');
			return;
		}

		const formData = new FormData($('#formData')[0]);
		const button = $('#saveButton');
		button.prop('disabled', true).addClass('opacity-70');
		showLoading();

		$.ajax({
			url: "<?= site_url('admin/slider/ajax_save') ?>",
			type: "POST",
			data: formData,
			contentType: false,
			processData: false,
			dataType: 'json',
			success: function(response) {
				hideLoading();
				button.prop('disabled', false).removeClass('opacity-70');
				if (!response || response.status !== true) {
					showToast('error', response && response.message ? response.message : 'Gagal menyimpan data');
					return;
				}
				closeModal();
				table.ajax.reload();
				showToast('success', response.message || 'Data berhasil disimpan');
			},
			error: function(xhr) {
				hideLoading();
				button.prop('disabled', false).removeClass('opacity-70');
				showToast('error', xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Gagal menyimpan data');
			}
		});
	}

	function deleteData(id) {
		Swal.fire({
			title: 'Hapus Slider?',
			text: 'Data yang dihapus tidak dapat dikembalikan.',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#ef4444',
			cancelButtonColor: '#6b7280',
			confirmButtonText: 'Ya, Hapus',
			cancelButtonText: 'Batal',
			reverseButtons: true
		}).then((result) => {
			if (result.isConfirmed) {
				showLoading();
				$.post("<?= site_url('admin/slider/ajax_delete/') ?>" + id, function(response) {
					hideLoading();
					if (!response || response.status !== true) {
						showToast('error', response && response.message ? response.message : 'Gagal menghapus data');
						return;
					}
					table.ajax.reload();
					showToast('success', response.message || 'Data berhasil dihapus');
				}, 'json').fail(function() {
					hideLoading();
					showToast('error', 'Gagal menghapus data');
				});
			}
		});
	}

	function toggleStatus(id, currentStatus) {
		const newStatus = currentStatus == 1 ? 0 : 1;
		const action = newStatus == 1 ? 'mengaktifkan' : 'menonaktifkan';

		Swal.fire({
			title: `${action.charAt(0).toUpperCase() + action.slice(1)} Slider?`,
			text: `Anda yakin ingin ${action} slider ini?`,
			icon: 'question',
			showCancelButton: true,
			confirmButtonColor: newStatus == 1 ? '#10b981' : '#f59e0b',
			cancelButtonColor: '#6b7280',
			confirmButtonText: `Ya, ${action}`,
			cancelButtonText: 'Batal',
			reverseButtons: true
		}).then((result) => {
			if (result.isConfirmed) {
				showLoading();
				$.post("<?= site_url('admin/slider/toggle_status/') ?>" + id, {status: newStatus}, function(response) {
					hideLoading();
					if (!response || response.status !== true) {
						showToast('error', response && response.message ? response.message : `Gagal ${action} slider`);
						return;
					}
					table.ajax.reload();
					showToast('success', response.message || `Slider berhasil di${action}`);
				}, 'json').fail(function() {
					hideLoading();
					showToast('error', `Gagal ${action} slider`);
				});
			}
		});
	}

	function bulkAction(url, confirmTitle, confirmText, successFallback, color) {
		if (selectedItems.size === 0) return;

		Swal.fire({
			title: confirmTitle,
			text: confirmText,
			icon: color === '#ef4444' ? 'warning' : 'question',
			showCancelButton: true,
			confirmButtonColor: color,
			cancelButtonColor: '#6b7280',
			confirmButtonText: 'Ya, lanjutkan',
			cancelButtonText: 'Batal',
			reverseButtons: true
		}).then((result) => {
			if (result.isConfirmed) {
				showLoading();
				const ids = Array.from(selectedItems);
				$.post(url, {ids: ids}, function(response) {
					hideLoading();
					if (!response || response.status !== true) {
						showToast('error', response && response.message ? response.message : 'Aksi bulk gagal');
						return;
					}
					selectedItems.clear();
					table.ajax.reload();
					showToast('success', response.message || successFallback);
				}, 'json').fail(function() {
					hideLoading();
					showToast('error', 'Aksi bulk gagal');
				});
			}
		});
	}

	function bulkActivate() {
		bulkAction("<?= site_url('admin/slider/bulk_activate') ?>", 'Aktifkan Slider?', `Aktifkan ${selectedItems.size} slider terpilih?`, 'Slider berhasil diaktifkan', '#10b981');
	}

	function bulkDeactivate() {
		bulkAction("<?= site_url('admin/slider/bulk_deactivate') ?>", 'Nonaktifkan Slider?', `Nonaktifkan ${selectedItems.size} slider terpilih?`, 'Slider berhasil dinonaktifkan', '#f59e0b');
	}

	function bulkDelete() {
		bulkAction("<?= site_url('admin/slider/bulk_delete') ?>", 'Hapus Slider?', `Hapus ${selectedItems.size} slider terpilih? File gambar akan dihapus setelah data berhasil dihapus.`, 'Slider berhasil dihapus', '#ef4444');
	}

	function previewImage(src) {
		$('#fullPreview').attr('src', src);
		$('#previewModal').removeClass('hidden');
		$('body').addClass('overflow-hidden');
	}

	function closePreview() {
		$('#previewModal').addClass('hidden');
		$('body').removeClass('overflow-hidden');
	}

	function openModal() {
		$('#modalSlider').removeClass('hidden');
		$('body').addClass('overflow-hidden');
	}

	function closeModal() {
		$('#modalSlider').addClass('hidden');
		$('body').removeClass('overflow-hidden');
	}

	function showLoading(title = 'Memproses...') {
		Swal.close();
		Swal.fire({
			title: title,
			html: '<div class="flex flex-col items-center"><div class="spinner-border animate-spin inline-block w-8 h-8 border-4 rounded-full border-primary-500 border-t-transparent mb-2"></div><p class="text-sm text-gray-500">Harap tunggu...</p></div>',
			allowOutsideClick: false,
			allowEscapeKey: false,
			allowEnterKey: false,
			showConfirmButton: false
		});
	}

	function hideLoading() {
		if (typeof Swal !== 'undefined') {
			setTimeout(() => Swal.close(), 100);
		}
	}

	function showToast(type, message) {
		const Toast = Swal.mixin({
			toast: true,
			position: 'top-end',
			showConfirmButton: false,
			timer: 3000,
			timerProgressBar: true,
			didOpen: (toast) => {
				toast.addEventListener('mouseenter', Swal.stopTimer);
				toast.addEventListener('mouseleave', Swal.resumeTimer);
			}
		});

		Toast.fire({
			icon: type,
			title: message
		});
	}
</script>
