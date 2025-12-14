<div class="container mx-auto">
	
	<div class="container mx-auto">
		<div class="flex justify-between mb-4">
			<h2 class="text-2xl font-bold">Data Informasi</h2>
			<button onclick="openModal()" class="bg-blue-600 text-white px-4 py-2 rounded">+ Tambah</button>
		</div>
		
		<div class="bg-white rounded-2xl shadow-card overflow-hidden">
			<!-- Table Header -->
			<div class="px-6 py-4 border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between">
				<div class="mb-4 md:mb-0">
					<h2 class="text-lg font-semibold text-gray-800">Daftar Informasi</h2>
					<p class="text-sm text-gray-600">Kelola semua Informasi yang ditampilkan</p>
				</div>
				
				<!-- Filters -->
				<div class="flex space-x-3">
					
					<button onclick="refreshTable()" 
					class="ripple px-4 py-2 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200 text-gray-700">
						<i class="fas fa-redo"></i>
					</button>
				</div>
			</div>
			<div class="p-4 md:p-6">
				<div class="overflow-x-auto rounded-lg border border-gray-100">
					<table id="tableInformasi" class="w-full text-left">
						<thead>
							<tr class="border-b">
								<th class="p-2">ID</th>
								<th class="p-2">Judul</th>
								<th class="p-2">Caption</th>
								<th class="p-2">Urutan</th>
								<th class="p-2">Aksi</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>
	
</div>
<div id="modal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center">
	<div class="bg-white p-6 rounded w-96 shadow-lg">
		<h2 class="text-xl font-bold mb-4" id="modalTitle">Tambah Informasi</h2>
		
		<form id="formData">
			<input type="hidden" id="id" name="id">
			
			<label class="block mb-2">Judul</label>
			<input type="text" id="title" name="title" class="w-full border p-2 rounded mb-4">
			
			<label class="block mb-2">Caption</label>
			<textarea id="caption" name="caption" class="w-full border p-2 rounded mb-4"></textarea>
			
			<label class="block mb-2">Urutan</label>
			<input type="number" id="urutan" name="urutan" class="w-full border p-2 rounded mb-4">
			
			<div class="flex justify-end space-x-2">
				<button type="button" onclick="closeModal()" class="bg-gray-600 text-white px-4 py-2 rounded">Batal</button>
				<button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
			</div>
		</form>
	</div>
</div>
<script>
	let table = $('#tableInformasi').DataTable({
		processing: true,
		serverSide: true,
		ajax: {
			url: "<?= site_url('admin/informasi/ajax_list') ?>",
			type: "POST"
		},
		columns: [
		{ data: "id" },
		{ data: "title" },
		{ data: "caption" },
		{ data: "urutan" },
		{
			data: "id",
			render: function(id) {
				return `
				<button onclick="editData(${id})" class="bg-yellow-500 text-white px-3 py-1 rounded">Edit</button>
				<button onclick="deleteData(${id})" class="bg-red-600 text-white px-3 py-1 rounded">Hapus</button>
				`;
			}
		}
		],
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
	// Refresh table
	function refreshTable() {
		table.ajax.reload();                         // false = jangan reset pagination
	}
	function openModal() {
		$('#formData')[0].reset();
		$('#modalTitle').text('Tambah Informasi');
		$('#id').val('');
		$('#modal').removeClass('hidden').addClass('flex');
	}
	
	function closeModal() {
		$('#modal').addClass('hidden');
	}
	
	$("#formData").submit(function (e) {
		e.preventDefault();
		
		$.post("<?= site_url('admin/informasi/save') ?>", $(this).serialize(), function () {
			Swal.fire({
				icon: "success",
				title: "Berhasil!",
				text: "Data berhasil disimpan.",
				timer: 1500,
				showConfirmButton: false
			});
			$('#modal').addClass('hidden');
			table.ajax.reload();
		});
	});
	
	function editData(id) {
		$.get("<?= site_url('admin/informasi/get/') ?>" + id, function (data) {
			let d = JSON.parse(data);
			
			$('#modalTitle').text('Edit Informasi');
			$('#id').val(d.id);
			$('#title').val(d.title);
			$('#caption').val(d.caption);
			$('#urutan').val(d.urutan);
			
			$('#modal').removeClass('hidden').addClass('flex');
		});
	}
	
	function deleteData(id) {
		Swal.fire({
			title: "Hapus data ini?",
			text: "Data tidak dapat dikembalikan!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#d33",
			cancelButtonColor: "#3085d6",
			confirmButtonText: "Ya, hapus!",
			cancelButtonText: "Batal"
			}).then((result) => {
			if (result.isConfirmed) {
				$.get("<?= site_url('admin/informasi/delete/') ?>" + id, function () {
					
					Swal.fire({
						icon: "success",
						title: "Berhasil!",
						text: "Data berhasil dihapus.",
						timer: 1500,
						showConfirmButton: false
					});
					
					table.ajax.reload();
				});
			}
		});
	}
</script>
