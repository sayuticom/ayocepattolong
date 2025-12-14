<div class="container mx-auto px-4">
	<!-- Header dengan gradient dan shadow -->
	<div class="mb-8">
		<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
			<div>
				<h1 class="text-3xl font-bold text-gray-800 mb-2">Image Slider</h1>
				<p class="text-gray-600">Manage slider images for your website homepage</p>
			</div>
			<button onclick="addData()" 
			class="ripple mt-4 md:mt-0 px-6 py-3 bg-gradient-to-r from-primary-600 to-secondary-600 text-white font-medium rounded-xl hover:from-primary-700 hover:to-secondary-700 transition-all duration-200 shadow-md hover:shadow-lg flex items-center space-x-2">
				<i class="fas fa-plus-circle"></i>
				<span>Tambah Slider</span>
			</button>
		</div>
		
		<!-- Stats Cards -->
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
	
	<!-- Main Content Card -->
	<div class="bg-white rounded-2xl shadow-card overflow-hidden">
		<!-- Table Header -->
		<div class="px-6 py-4 border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between">
			<div class="mb-4 md:mb-0">
				<h2 class="text-lg font-semibold text-gray-800">Daftar Slider</h2>
				<p class="text-sm text-gray-600">Kelola semua slider yang ditampilkan</p>
			</div>
			
			<!-- Filters -->
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
		
		<!-- Table Container -->
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
							<th class="py-4 px-4 text-left font-medium text-gray-700">Title</th>
							<th class="py-4 px-4 text-left font-medium text-gray-700">Image</th>
							<th class="py-4 px-4 text-left font-medium text-gray-700">Status</th>
							<th class="py-4 px-4 text-left font-medium text-gray-700">Tanggal</th>
							<th class="py-4 px-4 text-left font-medium text-gray-700">Aksi</th>
						</tr>
					</thead>
					<tbody class="divide-y divide-gray-100">
						<!-- Data akan diisi oleh DataTables -->
					</tbody>
				</table>
			</div>
			
			<!-- Bulk Actions -->
			<div id="bulkActions" class="hidden mt-4 p-4 bg-blue-50 rounded-lg border border-blue-100">
				<div class="flex items-center justify-between">
					<div class="flex items-center space-x-3">
						<span class="text-sm text-blue-700" id="selectedCount">0 item terpilih</span>
					</div>
					<div class="flex space-x-2">
						<button onclick="bulkActivate()" 
						class="ripple px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-colors duration-200 flex items-center space-x-1">
							<i class="fas fa-check"></i>
							<span>Aktifkan</span>
						</button>
						<button onclick="bulkDeactivate()" 
						class="ripple px-4 py-2 bg-yellow-600 text-white text-sm rounded-lg hover:bg-yellow-700 transition-colors duration-200 flex items-center space-x-1">
							<i class="fas fa-times"></i>
							<span>Nonaktifkan</span>
						</button>
						<button onclick="bulkDelete()" 
						class="ripple px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition-colors duration-200 flex items-center space-x-1">
							<i class="fas fa-trash"></i>
							<span>Hapus</span>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- MODAL -->
<div id="modalSlider" class="fixed inset-0 bg-black bg-opacity-60 hidden flex items-center justify-center z-50 p-4">
	<div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden animate-fade-in">
		<!-- Modal Header -->
		<div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gradient-to-r from-primary-50 to-secondary-50">
			<div>
				<h2 id="modalTitle" class="text-xl font-bold text-gray-800">Tambah Slider</h2>
				<p id="modalSubtitle" class="text-sm text-gray-600">Tambahkan slider baru</p>
			</div>
			<button onclick="closeModal()" 
			class="ripple w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full transition-colors duration-200">
				<i class="fas fa-times"></i>
			</button>
		</div>
		
		<!-- Modal Body -->
		<div class="p-6">
			<form id="formData" enctype="multipart/form-data" class="space-y-4">
				<input type="hidden" name="id" id="id">
				
				<!-- Title -->
				<div>
					<label class="block text-sm font-medium text-gray-700 mb-2">
						Title <span class="text-red-500">*</span>
					</label>
					<input type="text" name="title" id="title" 
					class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200"
					placeholder="Masukkan judul slider" required>
				</div>
				
				<!-- Caption -->
				<div>
					<label class="block text-sm font-medium text-gray-700 mb-2">Caption</label>
					<textarea name="caption" id="caption" rows="3"
					class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200"
					placeholder="Masukkan caption atau deskripsi"></textarea>
				</div>
				
				<!-- Image Upload -->
				<div>
					<label class="block text-sm font-medium text-gray-700 mb-2">
						Gambar <span class="text-red-500">*</span>
					</label>
					
					<!-- Image Preview -->
					<div id="imagePreview" class="mb-3 hidden">
						<div class="relative w-full h-48 rounded-lg overflow-hidden border border-gray-200">
							<img id="previewImage" src="" alt="Preview" class="w-full h-full object-cover">
							<button type="button" onclick="removePreview()" 
							class="absolute top-2 right-2 ripple w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600">
								<i class="fas fa-times text-sm"></i>
							</button>
						</div>
						<p class="text-xs text-gray-500 mt-2">Klik gambar untuk mengganti</p>
					</div>
					
					<!-- File Upload Area -->
					<div id="uploadArea" 
					class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-primary-400 hover:bg-primary-50 transition-all duration-200 cursor-pointer"
					onclick="document.getElementById('imageInput').click()">
						<input type="file" name="image" id="imageInput" class="hidden" accept="image/*">
						<div class="mb-4">
							<div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-3">
								<i class="fas fa-cloud-upload-alt text-primary-600 text-xl"></i>
							</div>
							<p class="text-sm font-medium text-gray-700">Klik untuk upload gambar</p>
							<p class="text-xs text-gray-500 mt-1">PNG, JPG, JPEG maks. 5MB</p>
						</div>
						<button type="button" 
						class="ripple px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200 transition-colors duration-200">
							Pilih File
						</button>
					</div>
				</div>
				
				<!-- Status -->
				<div>
					<label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
					<div class="flex space-x-4">
						<label class="flex items-center space-x-2 cursor-pointer">
							<input type="radio" name="is_active" value="1" id="statusActive" 
							class="w-4 h-4 text-primary-600 focus:ring-primary-500">
							<span class="text-gray-700">Aktif</span>
						</label>
						<label class="flex items-center space-x-2 cursor-pointer">
							<input type="radio" name="is_active" value="0" id="statusInactive"
							class="w-4 h-4 text-primary-600 focus:ring-primary-500">
							<span class="text-gray-700">Tidak Aktif</span>
						</label>
					</div>
				</div>
			</form>
		</div>
		
		<!-- Modal Footer -->
		<div class="px-6 py-4 border-t border-gray-100 flex justify-end space-x-3 bg-gray-50">
			<button onclick="closeModal()" 
			class="ripple px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-all duration-200">
				Batal
			</button>
			<button onclick="saveData()" id="saveButton"
			class="ripple px-6 py-2  font-medium rounded-xl hover:from-primary-700 hover:to-secondary-700 transition-all duration-200 shadow-md hover:shadow-lg flex items-center space-x-2">
				<i class="fas fa-save"></i>
				<span>Simpan</span>
			</button>
		</div>
	</div>
</div>

<!-- Preview Modal -->
<div id="previewModal" class="fixed inset-0 bg-black bg-opacity-90 hidden flex items-center justify-center z-50 p-4">
	<div class="relative w-full max-w-4xl">
		<button onclick="closePreview()" 
		class="absolute top-4 right-4 ripple w-10 h-10 bg-white text-gray-800 rounded-full flex items-center justify-center z-10 hover:bg-gray-100">
			<i class="fas fa-times"></i>
		</button>
		<img id="fullPreview" src="" alt="Full Preview" class="w-full h-auto rounded-lg">
	</div>
</div>

<script>
	let table;
	let selectedItems = new Set();
	
	$(document).ready(function () {
		// Initialize DataTable
		table = $('#table').DataTable({
			processing: true,
			serverSide: true,
			ajax: {
				url: "<?= site_url('admin/slider/ajax_list') ?>",
				type: "POST",
				data: function(d) {
					// Kirim filter status ke server
					d.filterStatus = $('#filterStatus').val();
				}
			},
			columns: [
            {
                data: 0,
                orderable: false
			},
            { 
                data: 1,
			},
            { 
                data: 2,
                orderable: false
			},
            { 
                data: 3,
			},
            { 
                data: 4,
			},
            {
                data: 5,
                orderable: false
			}
			],
			order: [[4, 'desc']],
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
				// Update stats jika tersedia
				if (settings.json && settings.json.stats) {
					$('#totalSliders').text(settings.json.stats.total);
					$('#activeSliders').text(settings.json.stats.active);
					$('#inactiveSliders').text(settings.json.stats.inactive);
				}
				
				// Initialize tooltips
				$('[data-tooltip]').hover(function() {
					const tooltip = $(this).attr('data-tooltip');
					if (tooltip) {
						$(this).append(`<div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-800 text-white text-xs rounded whitespace-nowrap z-50">${tooltip}</div>`);
					}
					}, function() {
					$(this).find('div').remove();
				});
				
				// Initialize row checkboxes
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
			},
            initComplete: function () {
                // Tambah class Tailwind ke input pencarian
                $('div.dataTables_filter input')
                .addClass('border placeholder-gray-500 ml-2 px-3 py-2 rounded-lg border-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-600 dark:focus:border-blue-500 dark:placeholder-gray-400')
                .attr('placeholder', 'Cari data...');
                
                // Ubah posisi / bungkus search box jika mau
                $('div.dataTables_filter').addClass('flex justify-center mb-3');
			}
		});
		
		// Select all checkbox
		$('#selectAll').on('change', function() {
			const isChecked = $(this).is(':checked');
			$('.row-checkbox').prop('checked', isChecked).trigger('change');
		});
		
		// Image preview
		$('#imageInput').on('change', function(e) {
			const file = e.target.files[0];
			if (file) {
				const reader = new FileReader();
				reader.onload = function(e) {
					$('#previewImage').attr('src', e.target.result);
					$('#imagePreview').removeClass('hidden');
					$('#uploadArea').addClass('hidden');
				}
				reader.readAsDataURL(file);
			}
		});
	});
	
	// Fungsi filter table
	function filterTable() {
		showLoading();
		
		table.ajax.reload(function() {
			
			hideLoading();
			
			const filterValue = $('#filterStatus').val();
			if (filterValue) {
				const statusText = filterValue == 1 ? 'Aktif' : 'Tidak Aktif';
				showToast('info', `Menampilkan slider: ${statusText}`);
				} else {
				showToast('info', 'Menampilkan semua slider');
			}
			
		}, false); // false = jangan reset pagination
	}
	
	
	// Fungsi reset filter
	function resetFilter() {
		$('#filterStatus').val('');
		filterTable();
	}
	// Refresh table
	function refreshTable() {
		showLoading();    // Tampilkan loading
		
		table.ajax.reload(function() {
			hideLoading();                 // Sembunyikan loading ketika table selesai load
			showToast('success', 'Data diperbarui');
		}, false);                         // false = jangan reset pagination
	}
	
	function formatDate(dateString) {
		const date = new Date(dateString);
		return date.toLocaleDateString('id-ID', {
			day: '2-digit',
			month: 'short',
			year: 'numeric'
		});
	}
	
	function formatTime(dateString) {
		const date = new Date(dateString);
		return date.toLocaleTimeString('id-ID', {
			hour: '2-digit',
			minute: '2-digit'
		});
	}
	
	function updateStats() {
		// This function should be called after table reload
		// You can implement actual stats counting from the server
		// For now, we'll just simulate it
		const totalRows = table.data().count();
		$('#totalSliders').text(totalRows);
		$('#activeSliders').text(Math.floor(totalRows * 0.7)); // Simulate 70% active
		$('#inactiveSliders').text(Math.floor(totalRows * 0.3)); // Simulate 30% inactive
	}
	
	function updateBulkActions() {
		const count = selectedItems.size;
		if (count > 0) {
			$('#selectedCount').text(`${count} item terpilih`);
			$('#bulkActions').removeClass('hidden');
			$('#selectAll').prop('checked', count === table.data().count());
			} else {
			$('#bulkActions').addClass('hidden');
			$('#selectAll').prop('checked', false);
		}
	}
	
	function addData() {
		$('#formData')[0].reset();
		$('#id').val('');
		$('#modalTitle').text("Tambah Slider");
		$('#modalSubtitle').text("Tambahkan slider baru");
		$('#imagePreview').addClass('hidden');
		$('#uploadArea').removeClass('hidden');
		$('#statusActive').prop('checked', true);
		openModal();
	}
	
	function editData(id) {
		showLoading();
		$.get("<?= site_url('admin/slider/ajax_edit/') ?>" + id, function (data) {
			hideLoading();
			$('#id').val(data.id);
			$('#title').val(data.title);
			$('#caption').val(data.caption);
			
			if (data.image) {
				$('#previewImage').attr('src', "<?= base_url('uploads/slider/') ?>" + data.image);
				$('#imagePreview').removeClass('hidden');
				$('#uploadArea').addClass('hidden');
				} else {
				$('#imagePreview').addClass('hidden');
				$('#uploadArea').removeClass('hidden');
			}
			
			if (data.is_active == 1) {
				$('#statusActive').prop('checked', true);
				} else {
				$('#statusInactive').prop('checked', true);
			}
			
			$('#modalTitle').text("Edit Slider");
			$('#modalSubtitle').text("Edit slider: " + data.title);
			openModal();
			}, 'json').fail(function() {
			hideLoading();
			showToast('error', 'Gagal memuat data');
		});
	}
	
	function saveData() {
		const formData = new FormData($('#formData')[0]);
		
		// Validate required fields
		if (!$('#title').val().trim()) {
			showToast('warning', 'Harap isi judul slider');
			$('#title').focus();
			return;
		}
		
		if (!$('#id').val() && !$('#imageInput')[0].files[0]) {
			showToast('warning', 'Harap pilih gambar');
			return;
		}
		
		showLoading();
		$.ajax({
			url: "<?= site_url('admin/slider/ajax_save') ?>",
			type: "POST",
			data: formData,
			contentType: false,
			processData: false,
			success: function(response) {
				hideLoading();
				closeModal();
				table.ajax.reload();
				showToast('success', 'Data berhasil disimpan');
			},
			error: function() {
				hideLoading();
				showToast('error', 'Gagal menyimpan data');
			}
		});
	}
	
	function deleteData(id) {
		Swal.fire({
			title: 'Hapus Data?',
			text: "Data yang dihapus tidak dapat dikembalikan!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#ef4444',
			cancelButtonColor: '#6b7280',
			confirmButtonText: 'Ya, Hapus!',
			cancelButtonText: 'Batal',
			reverseButtons: true
			}).then((result) => {
			if (result.isConfirmed) {
				showLoading();
				$.post("<?= site_url('admin/slider/ajax_delete/') ?>" + id, function() {
					hideLoading();
					table.ajax.reload();
					showToast('success', 'Data berhasil dihapus');
					}).fail(function() {
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
				$.post("<?= site_url('admin/slider/toggle_status/') ?>" + id, {status: newStatus}, function() {
					hideLoading();
					table.ajax.reload();
					showToast('success', `Slider berhasil di${action}`);
					}).fail(function() {
					hideLoading();
					showToast('error', `Gagal ${action} slider`);
				});
			}
		});
	}
	
	function bulkActivate() {
		if (selectedItems.size === 0) return;
		
		Swal.fire({
			title: 'Aktifkan Slider?',
			text: `Anda yakin ingin mengaktifkan ${selectedItems.size} slider?`,
			icon: 'question',
			showCancelButton: true,
			confirmButtonColor: '#10b981',
			cancelButtonColor: '#6b7280',
			confirmButtonText: 'Ya, Aktifkan',
			cancelButtonText: 'Batal'
			}).then((result) => {
			if (result.isConfirmed) {
				showLoading();
				const ids = Array.from(selectedItems);
				$.post("<?= site_url('admin/slider/bulk_activate') ?>", {ids: ids}, function() {
					hideLoading();
					selectedItems.clear();
					table.ajax.reload();
					showToast('success', `${ids.length} slider berhasil diaktifkan`);
				});
			}
		});
	}
	
	function bulkDeactivate() {
		if (selectedItems.size === 0) return;
		
		Swal.fire({
			title: 'Nonaktifkan Slider?',
			text: `Anda yakin ingin menonaktifkan ${selectedItems.size} slider?`,
			icon: 'question',
			showCancelButton: true,
			confirmButtonColor: '#f59e0b',
			cancelButtonColor: '#6b7280',
			confirmButtonText: 'Ya, Nonaktifkan',
			cancelButtonText: 'Batal'
			}).then((result) => {
			if (result.isConfirmed) {
				showLoading();
				const ids = Array.from(selectedItems);
				$.post("<?= site_url('admin/slider/bulk_deactivate') ?>", {ids: ids}, function() {
					hideLoading();
					selectedItems.clear();
					table.ajax.reload();
					showToast('success', `${ids.length} slider berhasil dinonaktifkan`);
				});
			}
		});
	}
	
	function bulkDelete() {
		if (selectedItems.size === 0) return;
		
		Swal.fire({
			title: 'Hapus Slider?',
			text: `Anda yakin ingin menghapus ${selectedItems.size} slider? Tindakan ini tidak dapat dibatalkan!`,
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
				const ids = Array.from(selectedItems);
				$.post("<?= site_url('admin/slider/bulk_delete') ?>", {ids: ids}, function() {
					hideLoading();
					selectedItems.clear();
					table.ajax.reload();
					showToast('success', `${ids.length} slider berhasil dihapus`);
				});
			}
		});
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
	
	function removePreview() {
		$('#imageInput').val('');
		$('#imagePreview').addClass('hidden');
		$('#uploadArea').removeClass('hidden');
	}
	
	function openModal() {
		$('#modalSlider').removeClass('hidden');
		$('body').addClass('overflow-hidden');
	}
	
	function closeModal() {
		$('#modalSlider').addClass('hidden');
		$('body').removeClass('overflow-hidden');
	}
	// Fungsi loading yang lebih robust
	function showLoading(title = 'Memproses...') {
		// Hapus loading sebelumnya jika ada
		Swal.close();
		
		Swal.fire({
			title: title,
			html: '<div class="flex flex-col items-center"><div class="spinner-border animate-spin inline-block w-8 h-8 border-4 rounded-full border-primary-500 border-t-transparent mb-2"></div><p class="text-sm text-gray-500">Harap tunggu...</p></div>',
			allowOutsideClick: false,
			allowEscapeKey: false,
			allowEnterKey: false,
			showConfirmButton: false,
			didOpen: () => {
				// Tambahkan class untuk animasi
				Swal.getHtmlContainer().querySelector('.spinner-border').classList.add('animate-spin');
			},
			willClose: () => {
				// Cleanup jika diperlukan
			}
		});
	}
	
	function hideLoading() {
		// Pastikan Swal tersedia
		if (typeof Swal !== 'undefined') {
			// Gunakan timeout kecil untuk memastikan tidak ada race condition
			setTimeout(() => {
				Swal.close();
			}, 100);
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