<div class="container mx-auto px-4 py-8">
	
	<h1 class="text-3xl font-semibold mb-8">Daftar Orders</h1>
	
	<!-- Tambahkan setelah filter tanggal -->
	<div class="mb-6 flex flex-wrap gap-3 items-end">
		<!-- Filter tanggal existing -->
		<div>
			<label for="start_date" class="block mb-1 font-semibold text-gray-700">Tanggal Mulai</label>
			<input type="date" id="start_date" class="border rounded p-2" />
		</div>
		<div>
			<label for="end_date" class="block mb-1 font-semibold text-gray-700">Tanggal Akhir</label>
			<input type="date" id="end_date" class="border rounded p-2" />
		</div>
		
		<!-- Filter Status baru -->
		<div>
			<label for="status_filter" class="block mb-1 font-semibold text-gray-700">Status</label>
			<select id="status_filter" class="border rounded p-2">
				<option value="">Semua Status</option>
				<option value="pending">Pending</option>
				<option value="paid">Lunas</option>
				<option value="cancelled">Batal</option>
			</select>
		</div>
		
		<button id="btnFilter" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Filter</button>
		<button id="btnPrint" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Print Laporan</button>
	</div>
	
	<!-- Summary -->
	<div id="summary" class="bg-white border border-gray-300 rounded-lg p-4 mb-6 shadow-sm flex justify-around text-center">
		<div>
			<div class="text-sm text-gray-500">Total Order</div>
			<div id="totalOrder" class="text-2xl font-semibold text-blue-600">-</div>
		</div>
		<div>
			<div class="text-sm text-gray-500">Total Admin Fee (Rp)</div>
			<div id="totalAdminFee" class="text-2xl font-semibold text-green-600">-</div>
		</div>
	</div>
	
	<!-- Table orders -->
	<div class="bg-white rounded-xl shadow-lg overflow-hidden">
		<div class="p-6">
			<table id="ordersTable" class="display responsive min-w-full border border-gray-300 dark:border-gray-700">
				<thead class="bg-gray-100">
					<tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
						<th class="p-3">#</th>
						<th class="p-3">Nama Pemesan</th>
						<th class="p-3">Lokasi</th>
						<th class="p-3 min-w-[250px]">Items</th>
						<th class="p-3">Total (Rp)</th>
						<th class="p-3">Admin Fee (Rp)</th>
						<th class="p-3">Status</th>
						<th class="p-3">Tanggal</th>
						<th class="p-3">Aksi</th>
					</tr>
				</thead>
				<tbody class="bg-white divide-y divide-gray-200">
				</tbody>
			</table>
		</div>
	</div>
	
</div>

<script>
	var table;
	$(document).ready(function(){
		table = $('#ordersTable').DataTable({
			processing: true,
			serverSide: true,
			ajax: {
				url: '<?= site_url("admin/orders/ajax_list") ?>',
				type: 'POST',
				data: function(d){
					d.start_date = $('#start_date').val();
					d.end_date = $('#end_date').val();
					d.status = $('#status_filter').val(); // Tambahkan ini
				}
			},
			columns: [
			{ data: 0 },
			{ data: 1 },
			{ data: 2 },
			{ data: 3 },
			{ data: 4, className: 'text-right' },
			{ data: 5, className: 'text-right' },
			{ 
				data: 6,
				render: function(data, type, row) {
					let statusClass = '';
					let statusText = '';
					
					switch(data) {
						case 'paid':
						statusClass = 'bg-green-100 text-green-800';
						statusText = 'Lunas';
						break;
						case 'cancelled':
						statusClass = 'bg-red-100 text-red-800';
						statusText = 'Batal';
						break;
						default:
						statusClass = 'bg-yellow-100 text-yellow-800';
						statusText = 'Pending';
					}
					
					return `<span class="px-2 py-1 rounded-full text-xs font-medium ${statusClass}">${statusText}</span>`;
				}
			},
			{ data: 7 },
			{
				data: null,
				orderable: false,
				render: function(data, type, row) {
					const orderId = row[0]; // ID order
					return `
					<div class="flex space-x-1">
                    <a href="<?= site_url('admin/orders/detail/') ?>${orderId}" 
					class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
					<i class="fas fa-eye"></i>
                    </a>
                    <a href="<?= site_url('admin/orders/print_detail/') ?>${orderId}" 
					target="_blank"
					class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded text-sm">
					<i class="fas fa-print"></i>
                    </a>
                    <button onclick="updateStatus(${orderId})" 
					class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm">
					<i class="fas fa-edit"></i>
                    </button>
					</div>
					`;
				}
			}
			],
			order: [[6, 'desc']],
			lengthMenu: [[10, 25, 50], [10, 25, 50]],
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
		
		// Load summary saat filter / reload tabel
		$('#btnFilter').on('click', function(){
			table.ajax.reload();
			loadSummary();
		});
		
		// Load summary pertama kali saat page ready
		loadSummary();
		
		$('#btnPrint').on('click', function(){
			const start = $('#start_date').val();
			const end = $('#end_date').val();
			let printUrl = '<?= site_url("admin/orders/print") ?>';
			if(start && end){
				printUrl += '?start_date=' + encodeURIComponent(start) + '&end_date=' + encodeURIComponent(end);
			}
			window.open(printUrl, '_blank');
		});
	});
	
	// Fungsi format Rupiah (contoh)
	function formatRupiah(num) {
		return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
	}
	
	// Fungsi untuk load summary
	function loadSummary() {
		const start_date = $('#start_date').val();
		const end_date = $('#end_date').val();
		
		$.ajax({
			url: '<?= site_url("admin/orders/summary") ?>',
			method: 'POST',
			data: { start_date: start_date, end_date: end_date },
			dataType: 'json',
			success: function(res){
				$('#totalOrder').text(res.total_order ?? '0');
				$('#totalAdminFee').text(formatRupiah(res.total_admin_fee ?? 0));
			},
			error: function(){
				$('#totalOrder').text('-');
				$('#totalAdminFee').text('-');
			}
		});
	}
	
	// Tambahkan fungsi updateStatus di JavaScript
	function updateStatus(orderId) {
		Swal.fire({
			title: 'Update Status Pembayaran',
			html: `
			<div class="text-left">
			<label class="block mb-2">Status:</label>
			<select id="statusSelect" class="w-full border rounded p-2 mb-3">
			<option value="pending">Pending</option>
			<option value="paid">Lunas (Paid)</option>
			<option value="cancelled">Batal (Cancelled)</option>
			</select>
			<label class="block mb-2">Metode Pembayaran (opsional):</label>
			<input type="text" id="paymentMethod" class="w-full border rounded p-2" 
			placeholder="Contoh: Tunai, Transfer, QRIS">
			</div>
			`,
			showCancelButton: true,
			confirmButtonText: 'Update',
			cancelButtonText: 'Batal',
			preConfirm: () => {
				return {
					status: document.getElementById('statusSelect').value,
					payment_method: document.getElementById('paymentMethod').value
				}
			}
			}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: '<?= site_url("admin/orders/update_status") ?>',
					method: 'POST',
					data: {
						id: orderId,
						status: result.value.status,
						payment_method: result.value.payment_method
					},
					dataType: 'json',
					success: function(res) {
						if (res.success) {
							Swal.fire('Berhasil!', res.message, 'success');
							table.ajax.reload();
							loadSummary();
							} else {
							Swal.fire('Error!', res.message, 'error');
						}
					},
					error: function() {
						Swal.fire('Error!', 'Terjadi kesalahan', 'error');
					}
				});
			}
		});
	}
</script>
