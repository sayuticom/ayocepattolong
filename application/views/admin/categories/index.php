<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800"><?= $title ?></h1>
            <p class="text-gray-600 mt-1">Kelola kategori produk Anda</p>
		</div>
        <button onclick="addCategory()" 
		class="mt-4 sm:mt-0 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center">
            <i class="fas fa-plus mr-2"></i> Tambah Kategori
		</button>
	</div>
    
    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-6">
            <div class="overflow-x-auto">
                <table id="categoriesTable" class="display responsive min-w-full border border-gray-300 dark:border-gray-700">
                    <thead class="bg-gray-50">
                        <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[50px]">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Info & Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
						</tr>
					</thead>
                    <tbody class="bg-white divide-y divide-gray-200">
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<!-- Modal Form (Tailwind) -->
<div id="categoryModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-2/3 shadow-lg rounded-xl bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 id="modalTitle" class="text-lg font-semibold text-gray-800"></h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
			</button>
		</div>
        
        <form id="categoryForm">
            <input type="hidden" name="id" id="id">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Basic Info -->
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Kategori <span class="text-red-500">*</span>
						</label>
                        <input type="text" id="name" name="name" required
						class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                        <div id="name-error" class="mt-1 text-sm text-red-600 hidden"></div>
					</div>
                    
                    <div>
                        <label for="is_active" class="block text-sm font-medium text-gray-700 mb-1">
                            Status
						</label>
                        <select id="is_active" name="is_active"
						class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
						</select>
					</div>
                    
                    <div class="pt-4 border-t">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Opsi Size Configuration</label>
                        
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="checkbox" id="show_size_option" name="show_size_option" value="1"
								class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <label for="show_size_option" class="ml-2 block text-sm text-gray-700">
                                    Tampilkan opsi size di produk
								</label>
							</div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" id="has_size_option" name="has_size_option" value="1"
								class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <label for="has_size_option" class="ml-2 block text-sm text-gray-700">
                                    Produk memiliki opsi size
								</label>
							</div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" id="disable_size_option" name="disable_size_option" value="1"
								class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <label for="disable_size_option" class="ml-2 block text-sm text-gray-700">
                                    Nonaktifkan opsi size (Hide checkbox)
								</label>
							</div>
						</div>
					</div>
				</div>
                
                <!-- Size Settings -->
                <div class="space-y-4">
                    <div>
                        <label for="size_label" class="block text-sm font-medium text-gray-700 mb-1">
                            Label Size
						</label>
                        <input type="text" id="size_label" name="size_label" 
						class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
						placeholder="Contoh: Large, Jumbo, Besar">
					</div>
                    
                    <div>
                        <label for="size_price" class="block text-sm font-medium text-gray-700 mb-1">
                            Harga Tambahan Size (Rp)
						</label>
                        <input type="number" id="size_price" name="size_price" min="0"
						class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
						placeholder="3000">
					</div>
                    
                    <div class="bg-gray-50 p-4 rounded-lg mt-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Petunjuk:</h4>
                        <ul class="text-xs text-gray-600 space-y-1">
                            <li><span class="font-medium">Show Size Option:</span> Untuk menampilkan/menyembunyikan opsi size di produk kategori ini</li>
                            <li><span class="font-medium">Has Size Option:</span> Produk dalam kategori ini memiliki opsi size</li>
                            <li><span class="font-medium">Disable Size Option:</span> Menonaktifkan checkbox size (akan di-hide)</li>
                            <li><span class="font-medium">Label Size:</span> Teks yang ditampilkan untuk opsi size</li>
                            <li><span class="font-medium">Harga Size:</span> Harga tambahan ketika size dipilih</li>
						</ul>
					</div>
				</div>
			</div>
            
            <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeModal()"
				class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition duration-200">
                    Batal
				</button>
                <button type="submit" id="saveBtn"
				class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition duration-200">
                    Simpan
				</button>
			</div>
		</form>
	</div>
</div>

<!-- Delete Confirmation Modal (tetap sama) -->
<div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-96 shadow-lg rounded-xl bg-white">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
			</div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Konfirmasi Hapus</h3>
            <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menghapus kategori ini?</p>
            <input type="hidden" id="deleteId">
            
            <div class="flex justify-center space-x-3">
                <button onclick="closeDeleteModal()"
				class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition duration-200">
                    Batal
				</button>
                <button onclick="confirmDelete()"
				class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition duration-200">
                    Hapus
				</button>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		// Initialize DataTable
		var table = $('#categoriesTable').DataTable({
			"processing": true,
			"serverSide": true,
			"responsive": true,
			"ajax": {
				"url": "<?= site_url('admin/categories/ajax_list') ?>",
				"type": "POST"
			},
			"columns": [
            {"data": "id", "orderable": false, "className": "text-center"},
            {"data": "name", "className": "font-medium"},
            {"data": "is_active", "orderable": false},
            {"data": "created_at", "orderable": false},
            {"data": "action", "orderable": false, "searchable": false, "className": "text-center"}
			],
			"order": [[0, 'desc']],
			"pageLength": 10,
			"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
			language: {
				emptyTable: "Tidak ada data yang tersedia pada tabel ini",
				info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
				infoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
				infoFiltered: "(disaring dari _MAX_ entri keseluruhan)",
				lengthMenu: "Tampilkan _MENU_ entri",
				loadingRecords: "Sedang memuat...",
				processing: "Sedang memproses...",
				search: "Pencarian:",
				zeroRecords: "Tidak ditemukan data yang sesuai",
				paginate: {
					first: 'Pertama',
					last: 'Terakhir',
					next: '<i class="fas fa-chevron-right"></i>',
					previous: '<i class="fas fa-chevron-left"></i>'
				}
			},
			responsive: {
				details: {
					display: $.fn.dataTable.Responsive.display.childRowImmediate,
					type: ''
				}
			},
			initComplete: function () {
				$('div.dataTables_filter input')
				.addClass('border placeholder-gray-500 ml-2 px-3 py-2 rounded-lg border-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-600 dark:focus:border-blue-500 dark:placeholder-gray-400')
				.attr('placeholder', 'Cari data...');
				$('div.dataTables_filter').addClass('flex justify-center mb-3');
			}
		});
		
		// Form submit handler
		$('#categoryForm').submit(function(e) {
			e.preventDefault();
			saveCategory();
		});
	});
	
	// Modal Functions
	function addCategory() {
		$('#categoryForm')[0].reset();
		$('#id').val('');
		$('#modalTitle').html('Tambah Kategori Baru');
		$('#saveBtn').html('Simpan');
		
		// Set default values untuk checkbox
		$('#show_size_option').prop('checked', true);
		$('#has_size_option').prop('checked', true);
		$('#disable_size_option').prop('checked', false);
		$('#size_label').val('Large');
		$('#size_price').val(3000);
		
		$('#categoryModal').removeClass('hidden');
	}
	
	function editCategory(id) {
		$.ajax({
			url: "<?= site_url('admin/categories/ajax_edit/') ?>" + id,
			type: "GET",
			dataType: "JSON",
			success: function(data) {
				$('#id').val(data.id);
				$('#name').val(data.name);
				$('#is_active').val(data.is_active);
				
				// Set values untuk field baru
				$('#show_size_option').prop('checked', parseInt(data.show_size_option) === 1);
				$('#has_size_option').prop('checked', parseInt(data.has_size_option) === 1);
				$('#disable_size_option').prop('checked', parseInt(data.disable_size_option) === 1);
				$('#size_label').val(data.size_label || 'Large');
				$('#size_price').val(data.size_price || 3000);
				
				$('#modalTitle').html('Edit Kategori');
				$('#saveBtn').html('Update');
				$('#categoryModal').removeClass('hidden');
			},
			error: function() {
				Swal.fire({
					icon: 'error',
					title: 'Error',
					text: 'Gagal mengambil data',
					toast: true,
					position: 'top-end',
					showConfirmButton: false,
					timer: 3000
				});
			}
		});
	}
	
	function saveCategory() {
		var url, method;
		
		if($('#id').val() == '') {
			url = "<?= site_url('admin/categories/ajax_save') ?>";
			method = "POST";
			} else {
			url = "<?= site_url('admin/categories/ajax_update') ?>";
			method = "POST";
		}
		
		// Prepare form data dengan checkbox
		var formData = {
			id: $('#id').val(),
			name: $('#name').val(),
			is_active: $('#is_active').val(),
			has_size_option: $('#has_size_option').is(':checked') ? 1 : 0,
			size_label: $('#size_label').val(),
			size_price: $('#size_price').val(),
			disable_size_option: $('#disable_size_option').is(':checked') ? 1 : 0,
			show_size_option: $('#show_size_option').is(':checked') ? 1 : 0
		};
		
		$.ajax({
			url: url,
			type: method,
			data: formData,
			dataType: "JSON",
			beforeSend: function() {
				$('#saveBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...');
			},
			success: function(response) {
				if(response.status == 'success') {
					closeModal();
					Swal.fire({
						icon: 'success',
						title: 'Berhasil',
						text: response.message,
						toast: true,
						position: 'top-end',
						showConfirmButton: false,
						timer: 2000
						}).then(() => {
						$('#categoriesTable').DataTable().ajax.reload();
					});
					} else {
					
					if(response.message.includes('Nama Kategori')) {
						$('#name-error').text(response.message).removeClass('hidden');
						} else {
						Swal.fire({
							icon: 'error',
							title: 'Error',
							text: response.message,
							toast: true,
							position: 'top-end',
							showConfirmButton: false,
							timer: 3000
						});
					}
				}
				$('#saveBtn').prop('disabled', false).html('Simpan');
			},
			error: function() {
				$('#saveBtn').prop('disabled', false).html('Simpan');
				Swal.fire({
					icon: 'error',
					title: 'Error',
					text: 'Terjadi kesalahan pada server',
					toast: true,
					position: 'top-end',
					showConfirmButton: false,
					timer: 3000
				});
			}
		});
	}
	
	// Fungsi delete dan lainnya tetap sama
	function deleteCategory(id) {
		$('#deleteId').val(id);
		$('#deleteModal').removeClass('hidden');
	}
	
	function confirmDelete() {
		var id = $('#deleteId').val();
		
		$.ajax({
			url: "<?= site_url('admin/categories/ajax_delete/') ?>" + id,
			type: "GET",
			dataType: "JSON",
			beforeSend: function() {
				$('button[onclick="confirmDelete()"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> Menghapus...');
			},
			success: function(response) {
				if(response.status == 'success') {
					closeDeleteModal();
					Swal.fire({
						icon: 'success',
						title: 'Berhasil',
						text: response.message,
						toast: true,
						position: 'top-end',
						showConfirmButton: false,
						timer: 2000
						}).then(() => {
						$('#categoriesTable').DataTable().ajax.reload();
					});
					} else {
					Swal.fire({
						icon: 'error',
						title: 'Error',
						text: response.message,
						toast: true,
						position: 'top-end',
						showConfirmButton: false,
						timer: 3000
					});
				}
			},
			error: function() {
				Swal.fire({
					icon: 'error',
					title: 'Error',
					text: 'Terjadi kesalahan pada server',
					toast: true,
					position: 'top-end',
					showConfirmButton: false,
					timer: 3000
				});
			}
		});
	}
	
	function closeModal() {
		$('#categoryModal').addClass('hidden');
		$('#name-error').addClass('hidden');
	}
	
	function closeDeleteModal() {
		$('#deleteModal').addClass('hidden');
		$('button[onclick="confirmDelete()"]').prop('disabled', false).html('Hapus');
	}
	
	// Close modal ketika klik di luar modal
	window.onclick = function(event) {
		var categoryModal = document.getElementById('categoryModal');
		var deleteModal = document.getElementById('deleteModal');
		
		if (event.target == categoryModal) {
			closeModal();
		}
		if (event.target == deleteModal) {
			closeDeleteModal();
		}
	}
</script>