<div class="container mx-auto px-4 py-8">
	<div class="flex justify-between items-center mb-4">
		<div>
			<h1 class="text-2xl font-bold text-gray-800"><?= $title ?></h1>
			<p class="text-gray-600 mt-1">Kelola produk Anda</p>
		</div>
		<button id="btn-add" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
			+ Tambah Produk
		</button>
	</div>
	
	<div class="bg-white shadow p-4 rounded">
		<table id="table-products" class="display responsive min-w-full  dark:border-gray-700">
			<thead class="bg-gray-50">
				<tr class="bg-gray-100 text-left">
					<th class="p-3  w-[100px]">Gambar</th>
					<th class="p-3 ">Nama Produk</th>
					<th class="p-3 ">Kategori</th>
					<th class="p-3 ">Harga Asli</th>
					<th class="p-3 ">Harga Diskon</th>
					<th class="p-3 ">Status</th>
					<th class="p-3  text-center w-[200px]">Aksi</th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</div>
</div>

<!-- Modal for Add/Edit Product -->
<div id="product-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center">
    <div class="bg-white rounded shadow-lg w-11/12 max-w-lg p-6 relative">
        <h2 id="modal-title" class="text-lg font-semibold mb-4">Tambah Produk</h2>
        <form id="product-form" enctype="multipart/form-data">
            <input type="hidden" name="id" id="product-id" />
            
            <div class="mb-4">
                <label for="category_id" class="block font-medium mb-1">Kategori</label>
                <select name="category_id" id="category_id" class="w-full  rounded px-3 py-2">
                    <option value="">-- Pilih Kategori --</option>
                    <?php foreach($categories as $cat): ?>
					<option value="<?= $cat->id ?>"><?= htmlspecialchars($cat->name) ?></option>
                    <?php endforeach; ?>
				</select>
			</div>
            
            <div class="mb-4">
                <label for="name" class="block font-medium mb-1">Nama Produk</label>
                <input type="text" name="name" id="name" class="w-full  rounded px-3 py-2" required />
			</div>
			<div class="mb-4">
                <label for="originalPrice" class="block font-medium mb-1">Harga Asli / Harga coret</label>
                <input type="number" name="originalPrice" id="originalPrice" class="w-full  rounded px-3 py-2" min="0" />
			</div>
			
            <div class="mb-4">
                <label for="price" class="block font-medium mb-1">Harga Diskon</label>
                <input type="number" name="price" id="price" class="w-full  rounded px-3 py-2" required min="0" />
			</div>
			
			
            <div class="mb-4 flex items-center space-x-4">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="is_ready" id="is_ready" value="1" />
                    <span>Ready</span>
				</label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="is_active" id="is_active" value="1" checked />
                    <span>Aktif</span>
				</label>
			</div>
			
            <div class="mb-4">
                <label for="image" class="block font-medium mb-1">Gambar Produk</label>
                <input type="file" name="image" id="image" accept=".jpg,.jpeg,.png" />
                <div id="image-preview" class="mt-2"></div>
                <input type="hidden" name="old_image" id="old_image" />
			</div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" id="btn-cancel" class="px-4 py-2 bg-gray-400 rounded hover:bg-gray-500 text-white">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 rounded hover:bg-blue-700 text-white">Simpan</button>
			</div>
		</form>
	</div>
</div>

<script>
	document.addEventListener('DOMContentLoaded', () => {
		const table = $('#table-products').DataTable({
			processing: true,
			serverSide: true,
			ajax: {
				url: '<?= site_url("admin/products/ajax_list") ?>',
				type: 'POST'
			},
			columns: [
            { data: 0, orderable: false, searchable: false },
            { data: 1 },
            { data: 2 },
            { data: 3, className: 'text-right' },
            { data: 4, className: 'text-right' },
            { data: 5 },
            { data: 6, orderable: false, searchable: false, className: 'text-center' },
			],
			order: [[1, 'asc']],
			"language": {
                "emptyTable": "Tidak ada data yang tersedia pada tabel ini",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                "infoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Tampilkan _MENU_ entri",
                "loadingRecords": "Sedang memuat...",
                "processing": "Sedang memproses...",
                "search": "Pencarian:",
                "zeroRecords": "Tidak ditemukan data yang sesuai",
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
                // Tambah class Tailwind ke input pencarian
                $('div.dataTables_filter input')
                .addClass('border placeholder-gray-500 ml-2 px-3 py-2 rounded-lg border-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-600 dark:focus:border-blue-500 dark:placeholder-gray-400')
                .attr('placeholder', 'Cari data...');
                
                // Ubah posisi / bungkus search box jika mau
                $('div.dataTables_filter').addClass('flex justify-center mb-3');
			}
		});
		
		const modal = document.getElementById('product-modal');
		const form = document.getElementById('product-form');
		const btnAdd = document.getElementById('btn-add');
		const btnCancel = document.getElementById('btn-cancel');
		const modalTitle = document.getElementById('modal-title');
		const imagePreview = document.getElementById('image-preview');
		
		function openModal() {
			modal.classList.remove('hidden');
			modal.classList.add('flex');
		}
		
		function closeModal() {
			modal.classList.remove('flex');
			modal.classList.add('hidden');
			form.reset();
			imagePreview.innerHTML = '';
			document.getElementById('old_image').value = '';
			document.getElementById('product-id').value = '';
		}
		
		btnAdd.addEventListener('click', () => {
			modalTitle.textContent = 'Tambah Produk';
			openModal();
		});
		
		btnCancel.addEventListener('click', (e) => {
			e.preventDefault();
			closeModal();
		});
		
		// Preview image before upload
		document.getElementById('image').addEventListener('change', function(e) {
			const file = this.files[0];
			if (!file) {
				imagePreview.innerHTML = '';
				return;
			}
			const reader = new FileReader();
			reader.onload = function(evt) {
				imagePreview.innerHTML = `<img src="${evt.target.result}" class="w-24 h-24 object-contain rounded" />`;
			}
			reader.readAsDataURL(file);
		});
		
		// Edit product
		window.editProduct = function(id) {
			$.ajax({
				url: '<?= site_url("admin/products/ajax_edit") ?>/' + id,
				type: 'GET',
				dataType: 'json',
				success: function(data) {
					modalTitle.textContent = 'Edit Produk';
					openModal();
					
					$('#product-id').val(data.id);
					$('#category_id').val(data.category_id);
					$('#name').val(data.name);
					$('#price').val(data.price);
					$('#originalPrice').val(data.originalPrice);
					$('#is_ready').prop('checked', data.is_ready == 1);
					$('#is_active').prop('checked', data.is_active == 1);
					$('#old_image').val(data.image);
					
					if(data.image) {
						imagePreview.innerHTML = `<img src="<?= base_url('uploads/') ?>${data.image}" class="w-24 h-24 object-contain rounded" />`;
						} else {
						imagePreview.innerHTML = '';
					}
				}
			});
		};
		
		// Delete product
		window.deleteProduct = function(id) {
			if (confirm('Hapus produk ini?')) {
				$.ajax({
					url: '<?= site_url("admin/products/ajax_delete") ?>/' + id,
					type: 'POST',
					dataType: 'json',
					success: function(res) {
						if(res.status) {
							table.ajax.reload(null, false);
							} else {
							alert('Gagal menghapus produk');
						}
					}
				});
			}
		};
		
		// Submit form add/edit
		form.addEventListener('submit', function(e) {
			e.preventDefault();
			
			const formData = new FormData(form);
			
			$.ajax({
				url: '<?= site_url("admin/products/ajax_save") ?>',
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				dataType: 'json',
				success: function(res) {
					if (res.status) {
						closeModal();
						table.ajax.reload(null, false);
						} else {
						alert('Gagal menyimpan produk');
					}
				},
				error: function() {
					alert('Terjadi kesalahan saat menyimpan produk');
				}
			});
		});
	});
</script>
