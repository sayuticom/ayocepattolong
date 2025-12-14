<div class="container mx-auto">
	
	<div class="flex justify-between mb-4">
		<h2 class="text-2xl font-bold">Data Relawan</h2>
		<button onclick="addRelawan()" class="bg-blue-600 text-white px-4 py-2 rounded">+ Tambah Relawan</button>
	</div>
	
	<div class="bg-white shadow p-4 rounded">
		<table id="tableRelawan" class="min-w-full">
			<thead>
				<tr>
					<th>No</th>
					<th>Nama</th>
					<th>Telepon</th>
					<th>Alamat</th>
					<th>Created</th>
					<th>Aksi</th>
				</tr>
			</thead>
		</table>
	</div>
	
</div>

<!-- MODAL -->
<div id="modal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center">
	<div class="bg-white p-6 w-96 rounded shadow">
		
		<h2 id="modalTitle" class="text-xl font-bold mb-4">Tambah Relawan</h2>
		
		<form id="formRelawan">
			<input type="hidden" name="id" id="id">
			
			<label class="block mb-1">Nama</label>
			<input type="text" name="nama" id="nama" class="border p-2 w-full mb-3 rounded">
			
			<label class="block mb-1">Telepon</label>
			<input type="text" name="telepon" id="telepon" class="border p-2 w-full mb-3 rounded">
			
			<label class="block mb-1">Alamat</label>
			<textarea name="alamat" id="alamat" class="border p-2 w-full mb-3 rounded"></textarea>
			
			<div class="flex justify-end space-x-2">
				<button type="button" onclick="closeModal()" class="bg-gray-600 text-white px-4 py-2 rounded">Batal</button>
				<button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
			</div>
		</form>
		
	</div>
</div>
<script>
	let table = $('#tableRelawan').DataTable({
		processing: true,
		serverSide: true,
		ajax: {
			url: "<?= site_url('admin/relawan/ajax_list') ?>",
			type: "POST"
		},
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
	
	function addRelawan() {
		$('#formRelawan')[0].reset();
		$('#id').val('');
		$('#modalTitle').text('Tambah Relawan');
		$('#modal').removeClass('hidden').addClass('flex');
	}
	
	function editRelawan(id) {
		$.get("<?= site_url('admin/relawan/get/') ?>" + id, function(res) {
			let r = JSON.parse(res);
			$('#id').val(r.id);
			$('#nama').val(r.nama);
			$('#telepon').val(r.telepon);
			$('#alamat').val(r.alamat);
			
			$('#modalTitle').text('Edit Relawan');
			$('#modal').removeClass('hidden').addClass('flex');
		});
	}
	
	function deleteRelawan(id) {
		Swal.fire({
			title: "Hapus relawan ini?",
			text: "Data yang dihapus tidak dapat dikembalikan!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#e74c3c",
			cancelButtonColor: "#6c757d",
			confirmButtonText: "Ya, hapus!",
			cancelButtonText: "Batal"
			}).then((result) => {
			if (result.isConfirmed) {
				$.get("<?= site_url('admin/relawan/delete/') ?>" + id, function() {
					
					Swal.fire({
						icon: "success",
						title: "Berhasil!",
						text: "Relawan telah dihapus.",
						timer: 1500,
						showConfirmButton: false
					});
					
					table.ajax.reload();
				});
			}
		});
	}
	
	$("#formRelawan").submit(function(e) {
		e.preventDefault();
		
		$.post("<?= site_url('admin/relawan/save') ?>", $(this).serialize(), function() {
			
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
	
	function closeModal() {
		$('#modal').addClass('hidden');
	}
</script>
