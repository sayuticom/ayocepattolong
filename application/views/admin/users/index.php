<div class="p-6 bg-white shadow rounded">
    <div class="flex justify-between mb-4">
        <h2 class="text-xl font-semibold">Manajemen User</h2>
        <button onclick="addUser()" class="px-4 py-2 bg-blue-600 text-white rounded">Tambah User</button>
	</div>
	
    <table id="userTable" class="display responsive min-w-full border border-gray-300 dark:border-gray-700">
        <thead>
            <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                <th class="px-3 py-2">No</th>
                <th class="px-3 py-2">Username</th>
                <th class="px-3 py-2">Fullname</th>
                <th class="px-3 py-2">Role</th>
                <th class="px-3 py-2">Status</th>
                <th class="px-3 py-2">Aksi</th>
			</tr>
		</thead>
		<tbody class="bg-white divide-y divide-gray-200">
		</tbody>
	</table>
</div>

<!-- Modal -->
<div id="userModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center">
    <div class="bg-white p-6 rounded shadow-lg w-96">
        <h2 class="text-lg font-semibold mb-4" id="modalTitle"></h2>
		
        <form id="formUser">
            <input type="hidden" name="id" id="id">
			
            <label>Username</label>
            <input type="text" name="username" id="username" class="w-full border p-2 rounded mb-2">
			
            <label>Password (optional)</label>
            <input type="password" name="password" id="password" class="w-full border p-2 rounded mb-2">
			
            <label>Fullname</label>
            <input type="text" name="fullname" id="fullname" class="w-full border p-2 rounded mb-2">
			
            <label>Role</label>
            <select name="role_id" id="role_id" class="w-full border p-2 rounded mb-2">
                <option value="1">Admin</option>
                <option value="2">Staff</option>
			</select>
			
            <label>Status</label>
            <select name="is_active" id="is_active" class="w-full border p-2 rounded mb-4">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
			</select>
			
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal()" class="px-3 py-2 bg-gray-400 text-white rounded">Batal</button>
                <button type="submit" class="px-3 py-2 bg-blue-600 text-white rounded">Simpan</button>
			</div>
		</form>
	</div>
</div>

<script>
	
	$(document).ready(function() {
		$('#userTable').DataTable({
			processing: true,
			serverSide: true,
			ajax: {
				url: base_url + "admin/users/ajax_list",
				type: "POST"
			},
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
	});
	
	function addUser() {
		$('#formUser')[0].reset();
		$('#modalTitle').text("Tambah User");
		$('#userModal').removeClass('hidden');
	}
	
	function editUser(id) {
		$.ajax({
			url: base_url + "admin/users/get/" + id,
			type: "GET",
			dataType: "JSON",
			success: function(response) {
				if (response.status === 'success') {
					const data = response.data;
					
					$('#id').val(data.id);
					$('#username').val(data.username);
					$('#fullname').val(data.fullname);
					$('#role_id').val(data.role_id);
					$('#is_active').val(data.is_active ? '1' : '0');
					
					$('#modalTitle').text("Edit User");
					$('#userModal').removeClass('hidden');
					} else {
					// Handle error
					Swal.fire({
						icon: 'error',
						title: 'Error',
						text: response.message || 'Gagal mengambil data user',
						toast: true,
						position: 'top-end',
						showConfirmButton: false,
						timer: 3000
					});
				}
			},
			error: function(xhr, status, error) {
				console.error("AJAX Error:", error);
				Swal.fire({
					icon: 'error',
					title: 'Error',
					text: 'Terjadi kesalahan saat mengambil data',
					toast: true,
					position: 'top-end',
					showConfirmButton: false,
					timer: 3000
				});
			}
		});
	}
	
	function closeModal() {
		$('#userModal').addClass('hidden');
	}
	
	$('#formUser').submit(function(e){
		e.preventDefault();
		$.post(base_url + "save", $(this).serialize(), function(res){
			$('#userModal').addClass('hidden');
			$('#userTable').DataTable().ajax.reload();
		});
	});
	
	function deleteUser(id) {
		Swal.fire({
			title: 'Hapus?',
			text: "Data akan dihapus permanen",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			cancelButtonColor: '#6b7280',
			confirmButtonText: 'Hapus',
			cancelButtonText: 'Batal'
			}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: base_url + "admin/users/delete/" + id,
					type: "GET",
					dataType: "JSON",
					success: function(response) {
						if (response.status === 'success') {
							Swal.fire('Terhapus!', response.message, 'success');
							$('#userTable').DataTable().ajax.reload();
							} else {
							Swal.fire('Gagal!', response.message, 'error');
						}
					}
				});
			}
		});
	}
</script>
