<div class="flex justify-between mb-4">
			<h2 class="text-2xl font-bold">Data Warta</h2>
			<button onclick="openModal()" class="btn-primary"><i class="fas fa-plus mr-1"></i>Tambah Warta</button>
		</div>

		<div class="bg-white rounded-2xl shadow-card overflow-hidden">
			<div class="px-6 py-4 border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between">
				<div class="mb-4 md:mb-0">
					<h2 class="text-lg font-semibold text-gray-800">Daftar Warta Kemanusiaan</h2>
					<p class="text-sm text-gray-600">Kelola berita dan informasi kemanusiaan</p>
				</div>
				<div class="flex space-x-3">
					<button onclick="refreshTable()"
					class="ripple px-4 py-2 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200 text-gray-700">
						<i class="fas fa-redo"></i>
					</button>
				</div>
			</div>
			<div class="p-4 md:p-6">
				<div class="admin-table-wrap">
					<table id="tableInformasi" class="table-news">
						<thead>
							<tr>
								<th class="th-img">Gambar</th>
								<th class="th-title">Judul</th>
								<th class="th-caption">Ringkasan</th>
								<th class="th-order">Urutan</th>
								<th class="th-status">Status</th>
								<th class="th-actions">Aksi</th>
							</tr>
						</thead>
					</table>
				</div>
				<div id="mobileNewsList" class="mobile-news-list"></div>
			</div>
	</div>
</div>

<div id="modal" class="admin-warta-modal-overlay hidden">
	<div class="admin-warta-modal">
		<h2 class="admin-warta-modal-title" id="modalTitle">Tambah Warta</h2>

		<form id="formData" class="admin-warta-form" enctype="multipart/form-data">
			<input type="hidden" id="id" name="id">

			<div class="admin-warta-field">
				<label class="admin-warta-label" for="title">Judul Berita <span class="text-red-500">*</span></label>
				<input type="text" id="title" name="title" class="admin-warta-input" required>
			</div>

			<div class="admin-warta-field">
				<label class="admin-warta-label" for="caption">Isi Berita</label>
				<textarea id="caption" name="caption" class="admin-warta-textarea" rows="6"></textarea>
			</div>

			<div class="admin-warta-row">
				<div class="admin-warta-field">
					<label class="admin-warta-label" for="urutan">Urutan</label>
					<input type="number" id="urutan" name="urutan" class="admin-warta-input" value="0">
				</div>
				<div class="admin-warta-field">
					<label class="admin-warta-label" for="status">Status</label>
					<select id="status" name="status" class="admin-warta-input">
						<option value="draft">Draft</option>
						<option value="publish" selected>Publish</option>
						<option value="arsip">Arsip</option>
					</select>
				</div>
			</div>

			<div id="slugWrapper" class="admin-warta-field hidden">
				<label class="admin-warta-label" for="slug">Slug</label>
				<input type="text" id="slug" name="slug" class="admin-warta-input">
				<p class="admin-warta-hint">Biarkan kosong untuk generate otomatis.</p>
			</div>

			<div class="admin-warta-field">
				<label class="admin-warta-label">Gambar</label>
				<p class="admin-warta-hint">Format JPG, PNG, atau WebP. Disarankan 1200 × 630 piksel. Maksimal 3 MB.</p>

				<div id="currentImageWrapper" class="admin-warta-image-section hidden">
					<img id="currentImage" src="" alt="Preview" class="admin-warta-preview">
					<label class="admin-warta-checkbox-label">
						<input type="checkbox" id="remove_image" name="remove_image" value="1">
						<span>Hapus gambar ini</span>
					</label>
				</div>

				<div id="noImagePlaceholder" class="admin-warta-placeholder">
					<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
					<span>Belum ada gambar</span>
				</div>

				<input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp" class="admin-warta-file-input">
			</div>

			<div class="admin-warta-modal-footer">
				<button type="button" onclick="closeModal()" class="admin-warta-btn admin-warta-btn-cancel">Batal</button>
				<button type="submit" class="admin-warta-btn admin-warta-btn-simpan">Simpan</button>
			</div>
		</form>
	</div>
</div>

<style>
.table-wrap { overflow-x: auto; }
.table-news { width:100%; table-layout:fixed; border-collapse:collapse; }
.table-news thead th { padding:10px 8px; font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:0.04em; color:#64748b; border-bottom:2px solid #e2e8f0; text-align:left; white-space:nowrap; }
.table-news tbody td { padding:10px 8px; border-bottom:1px solid #f1f5f9; vertical-align:middle; }
.table-news tbody tr:hover { background:#f8fafc; }
.th-img { width:90px; }
.th-title { width:26%; }
.th-caption { width:35%; }
.th-order { width:70px; }
.th-status { width:90px; }
.th-actions { width:110px; }

.news-thumb { width:80px; height:45px; border-radius:6px; overflow:hidden; background:#e2e8f0; }
.news-thumb img { width:100%; height:100%; object-fit:cover; display:block; }
.news-thumb-empty { display:flex; flex-direction:column; align-items:center; justify-content:center; gap:2px; background:#f1f5f9; color:#94a3b8; font-size:9px; }
.news-thumb-empty svg { width:16px; height:16px; }
.news-title { display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; line-height:1.35; font-weight:600; color:#0f172a; font-size:14px; }
.news-excerpt { display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden; line-height:1.45; color:#475569; font-size:13px; }

.badge { display:inline-block; padding:3px 10px; border-radius:999px; font-size:11px; font-weight:700; letter-spacing:0.02em; }
.badge-green { background:#dcfce7; color:#166534; }
.badge-amber { background:#fef3c7; color:#92400e; }
.badge-red { background:#fee2e2; color:#991b1b; }

.btn-actions { display:flex; gap:6px; flex-wrap:wrap; }
.btn-edit { display:inline-flex; align-items:center; gap:4px; padding:5px 10px; border:1.5px solid #f97316; color:#f97316; background:transparent; border-radius:6px; font-size:12px; font-weight:700; cursor:pointer; transition:all 0.15s; }
.btn-edit:hover { background:#fff7ed; }
.btn-delete { display:inline-flex; align-items:center; gap:4px; padding:5px 10px; border:1.5px solid #fca5a5; color:#dc2626; background:transparent; border-radius:6px; font-size:12px; font-weight:700; cursor:pointer; transition:all 0.15s; }
.btn-delete:hover { background:#fef2f2; }

.btn-primary { display:inline-flex; align-items:center; gap:4px; padding:8px 16px; border:0; border-radius:8px; background:#f97316; color:#fff; font-size:14px; font-weight:700; cursor:pointer; transition:all 0.15s; }
.btn-primary:hover { background:#ea580c; }
.btn-cancel { display:inline-flex; align-items:center; gap:4px; padding:8px 16px; border:1.5px solid #d1d5db; border-radius:8px; background:#fff; color:#374151; font-size:14px; font-weight:600; cursor:pointer; transition:all 0.15s; }
.btn-cancel:hover { background:#f9fafb; }

.admin-warta-modal-overlay { position:fixed; inset:0; background:rgba(0,0,0,0.5); display:none; justify-content:center; align-items:center; z-index:50; }
.admin-warta-modal-overlay.flex { display:flex; }

.admin-warta-modal { background:#fff; border-radius:12px; width:min(760px,calc(100vw - 48px)); max-height:calc(100vh - 48px); overflow-y:auto; padding:24px 28px; box-shadow:0 20px 60px rgba(0,0,0,0.2); display:flex; flex-direction:column; }

.admin-warta-modal-title { font-size:20px; font-weight:700; color:#0f172a; margin:0 0 20px 0; }

.admin-warta-form { display:flex; flex-direction:column; }

.admin-warta-field { margin-bottom:16px; }

.admin-warta-label { display:block; font-size:13px; font-weight:700; color:#374151; margin-bottom:6px; }

.admin-warta-input { width:100%; padding:10px 12px; border:1px solid #d1d5db; border-radius:8px; font-size:14px; color:#0f172a; background:#fff; transition:border-color 0.15s; box-sizing:border-box; min-height:42px; }
.admin-warta-input:focus { outline:none; border-color:#f97316; box-shadow:0 0 0 3px rgba(249,115,22,0.12); }

.admin-warta-textarea { width:100%; padding:10px 12px; border:1px solid #d1d5db; border-radius:8px; font-size:14px; color:#0f172a; background:#fff; resize:vertical; min-height:180px; font-family:inherit; box-sizing:border-box; transition:border-color 0.15s; }
.admin-warta-textarea:focus { outline:none; border-color:#f97316; box-shadow:0 0 0 3px rgba(249,115,22,0.12); }

.admin-warta-row { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
.admin-warta-row .admin-warta-field { margin-bottom:0; }

.admin-warta-hint { font-size:12px; color:#94a3b8; margin:4px 0 0 0; line-height:1.4; }

.admin-warta-image-section { margin-top:8px; }
.admin-warta-preview { width:100%; max-width:280px; height:auto; aspect-ratio:16/9; object-fit:cover; border-radius:8px; border:1px solid #e2e8f0; display:block; }
.admin-warta-checkbox-label { display:inline-flex; align-items:center; gap:8px; margin-top:8px; cursor:pointer; font-size:14px; color:#dc2626; font-weight:600; }
.admin-warta-checkbox-label input[type="checkbox"] { width:16px; height:16px; accent-color:#dc2626; margin:0; }

.admin-warta-placeholder { display:flex; flex-direction:column; align-items:center; justify-content:center; gap:4px; padding:20px; border:1.5px dashed #d1d5db; border-radius:8px; background:#fafafa; color:#94a3b8; font-size:13px; margin-top:8px; }

.admin-warta-file-input { display:block; width:100%; padding:8px 0; font-size:13px; color:#475569; margin-top:8px; cursor:pointer; }
.admin-warta-file-input::file-selector-button { padding:6px 14px; border:1px solid #d1d5db; border-radius:6px; background:#fff; color:#374151; font-size:13px; font-weight:600; cursor:pointer; transition:all 0.15s; margin-right:10px; }
.admin-warta-file-input::file-selector-button:hover { background:#f1f5f9; }

.admin-warta-modal-footer { display:flex; justify-content:flex-end; align-items:center; gap:10px; padding-top:20px; margin-top:4px; border-top:1px solid #e2e8f0; }

.admin-warta-btn { display:inline-flex; align-items:center; justify-content:center; gap:6px; padding:10px 20px; border-radius:8px; font-size:14px; font-weight:700; cursor:pointer; transition:all 0.15s; min-height:42px; border:0; }

.admin-warta-btn-simpan { background:#f97316; color:#fff; }
.admin-warta-btn-simpan:hover { background:#ea580c; }

.admin-warta-btn-cancel { background:#fff; color:#374151; border:1.5px solid #d1d5db; }
.admin-warta-btn-cancel:hover { background:#f9fafb; }

@media (max-width:640px) {
	.admin-warta-modal { width:calc(100vw - 24px); max-height:calc(100vh - 24px); padding:20px 16px; }
	.admin-warta-row { grid-template-columns:1fr; gap:0; }
	.admin-warta-modal-footer { flex-direction:column; }
	.admin-warta-btn { width:100%; }
}

.dataTables_wrapper .dataTables_filter input { border:1px solid #d1d5db; border-radius:8px; padding:6px 12px; font-size:13px; margin-left:6px; }
.dataTables_wrapper .dataTables_filter input:focus { outline:none; border-color:#f97316; box-shadow:0 0 0 3px rgba(249,115,22,0.12); }
.dataTables_wrapper .dataTables_length select { border:1px solid #d1d5db; border-radius:6px; padding:4px 8px; font-size:13px; }
.dataTables_info { font-size:13px; color:#64748b; }
.dataTables_paginate { font-size:13px; }

@media (max-width:768px) {
	.table-wrap { display:none; }
	#tableInformasi_wrapper { display:none; }
	.mobile-news-list { display:grid !important; gap:12px; }
	.mobile-news-card { display:flex; gap:12px; padding:12px; border:1px solid #e2e8f0; border-radius:10px; background:#fff; align-items:center; }
	.mobile-news-card .m-thumb { width:64px; height:64px; border-radius:8px; overflow:hidden; flex-shrink:0; background:#f1f5f9; }
	.mobile-news-card .m-thumb img { width:100%; height:100%; object-fit:cover; }
	.mobile-news-card .m-thumb-empty { display:flex; align-items:center; justify-content:center; color:#94a3b8; }
	.mobile-news-card .m-thumb-empty svg { width:20px; height:20px; }
	.mobile-news-card .m-body { flex:1; min-width:0; }
	.mobile-news-card .m-title { font-weight:600; font-size:14px; color:#0f172a; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
	.mobile-news-card .m-meta { display:flex; align-items:center; gap:8px; margin-top:4px; }
	.mobile-news-card .m-actions { display:flex; gap:6px; flex-shrink:0; }
}
@media (min-width:769px) {
	.mobile-news-list { display:none !important; }
}
</style>

<script>
function renderImage(data) {
	if (data && data.indexOf('news-thumb') !== -1) return data;
	return '<div class="news-thumb news-thumb-empty"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg><span>Belum ada</span></div>';
}
function renderActions(id) {
	return '<div class="btn-actions"><button onclick="editData('+id+')" class="btn-edit"><i class="fas fa-pen"></i>Edit</button><button onclick="deleteData('+id+')" class="btn-delete"><i class="fas fa-trash-alt"></i>Hapus</button></div>';
}
function renderBadge(data) {
	return data || '<span class="badge badge-amber">Draft</span>';
}

let table = $('#tableInformasi').DataTable({
	processing: true,
	serverSide: true,
	ajax: {
		url: "<?= site_url('admin/informasi/ajax_list') ?>",
		type: "POST"
	},
	columns: [
		{ data: "image", render: renderImage },
		{ data: "title" },
		{ data: "caption" },
		{ data: "urutan", className: "dt-center" },
		{ data: "status", render: renderBadge },
		{ data: "id", render: renderActions, orderable: false, searchable: false }
	],
	order: [[3, "asc"]],
	language: {
		emptyTable: "Belum ada warta yang tersedia.",
		info: "Menampilkan _START_–_END_ dari _TOTAL_ warta",
		infoEmpty: "Menampilkan 0–0 dari 0 warta",
		infoFiltered: "(tersaring dari _MAX_ total)",
		lengthMenu: "Tampilkan _MENU_",
		loadingRecords: "Memuat...",
		processing: "Memproses...",
		search: "Cari:",
		zeroRecords: "Tidak ditemukan warta yang sesuai",
		paginate: {
			first: '«',
			last: '»',
			next: '›',
			previous: '‹'
		}
	},
	drawCallback: function() {
		renderMobileCards();
	},
	initComplete: function() {
		$('div.dataTables_filter input').attr('placeholder', 'Cari warta...');
	}
});

function renderMobileCards() {
	var data = table.rows({filter: 'applied'}).data();
	var html = '';
	$.each(data, function(i, row) {
		var src = '';
		var match = row.image && row.image.match(/src="([^"]+)"/);
		if (match) src = match[1];
		var thumbHtml = src
			? '<img src="'+src+'" alt="">'
			: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>';
		var titleText = row.title ? row.title.replace(/<[^>]+>/g,'') : '';
		var badgeText = row.status || '<span class="badge badge-amber">Draft</span>';
		html += '<div class="mobile-news-card">'+
			'<div class="m-thumb'+(src?'':' m-thumb-empty')+'">'+thumbHtml+'</div>'+
			'<div class="m-body">'+
				'<div class="m-title">'+titleText+'</div>'+
				'<div class="m-meta">'+badgeText+'<span class="text-xs text-gray-400 ml-2">Urutan '+row.urutan+'</span></div>'+
			'</div>'+
			'<div class="m-actions">'+
				'<button onclick="editData('+row.id+')" class="btn-edit" title="Edit"><i class="fas fa-pen"></i></button>'+
				'<button onclick="deleteData('+row.id+')" class="btn-delete" title="Hapus"><i class="fas fa-trash-alt"></i></button>'+
			'</div>'+
		'</div>';
	});
	if (!html) html = '<div class="text-center py-8 text-gray-400 text-sm">Belum ada warta yang tersedia.</div>';
	$('#mobileNewsList').html(html);
}

function refreshTable() { table.ajax.reload(); }

function openModal() {
	$('#formData')[0].reset();
	$('#modalTitle').text('Tambah Warta');
	$('#id').val('');
	$('#currentImageWrapper').addClass('hidden');
	$('#noImagePlaceholder').removeClass('hidden');
	$('#slugWrapper').addClass('hidden');
	$('#remove_image').prop('checked', false);
	$('#modal').removeClass('hidden').addClass('flex');
}

function closeModal() { $('#modal').addClass('hidden'); }

$("#formData").submit(function (e) {
	e.preventDefault();
	var formData = new FormData(this);
	$.ajax({
		url: "<?= site_url('admin/informasi/save') ?>",
		type: "POST",
		data: formData,
		processData: false,
		contentType: false,
		success: function(res) {
			try {
				var r = typeof res === 'object' ? res : JSON.parse(res);
				if (r.status) {
					Swal.fire({ icon:"success", title:"Berhasil!", text:r.message||"Data berhasil disimpan.", timer:1500, showConfirmButton:false });
					closeModal();
					table.ajax.reload();
				} else {
					Swal.fire({ icon:"error", title:"Gagal!", text:r.message||"Terjadi kesalahan." });
				}
			} catch(e) {
				Swal.fire({ icon:"error", title:"Gagal!", text:"Terjadi kesalahan." });
			}
		},
		error: function() {
			Swal.fire({ icon:"error", title:"Gagal!", text:"Kesalahan jaringan." });
		}
	});
});

function editData(id) {
	$.get("<?= site_url('admin/informasi/get/') ?>" + id, function (data) {
		var d = typeof data === 'object' ? data : JSON.parse(data);
		if (!d) return;
		$('#modalTitle').text('Edit Warta');
		$('#id').val(d.id);
		$('#title').val(d.title);
		$('#caption').val(d.caption);
		$('#urutan').val(d.urutan);
		$('#status').val(d.status || 'draft');
		$('#remove_image').prop('checked', false);
		$('#slug').val(d.slug || '');
		$('#slugWrapper').removeClass('hidden');
		if (d.has_image) {
			$('#currentImage').attr('src', d.image_url);
			$('#currentImageWrapper').removeClass('hidden');
			$('#noImagePlaceholder').addClass('hidden');
		} else {
			$('#currentImageWrapper').addClass('hidden');
			$('#noImagePlaceholder').removeClass('hidden');
		}
		$('#modal').removeClass('hidden').addClass('flex');
	});
}

function deleteData(id) {
	Swal.fire({
		title: "Hapus warta ini?",
		text: "Data yang dihapus tidak dapat dikembalikan.",
		icon: "warning",
		showCancelButton: true,
		confirmButtonColor: "#dc2626",
		cancelButtonColor: "#6b7280",
		confirmButtonText: "Ya, hapus",
		cancelButtonText: "Batal"
	}).then((result) => {
		if (result.isConfirmed) {
			$.get("<?= site_url('admin/informasi/delete/') ?>" + id, function (res) {
				try {
					var r = typeof res === 'object' ? res : JSON.parse(res);
					Swal.fire({ icon:"success", title:"Berhasil!", text:"Warta berhasil dihapus.", timer:1500, showConfirmButton:false });
					table.ajax.reload();
				} catch(e) {
					Swal.fire({ icon:"error", title:"Gagal!", text:"Terjadi kesalahan." });
				}
			});
		}
	});
}
</script>
