<!DOCTYPE html>
<html lang="id">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title><?= $title ?> - <?= date('d-m-Y') ?></title>
		<style>
			/* Reset untuk print */
			* {
            margin: 0;
            padding: 20px auto;
            box-sizing: border-box;
			}
			
			@page {
            size: A4;
            margin: 15mm;
			}
			
			@media print {
            @page {
			size: A4 portrait;
			margin: 15mm;
            }
            
            body {
			margin: 0;
			padding: 20px auto;
			-webkit-print-color-adjust: exact;
			print-color-adjust: exact;
            }
            
            .no-print {
			display: none !important;
            }
            
            .page-break {
			page-break-before: always;
            }
            
            .keep-together {
			page-break-inside: avoid;
            }
			}
			
			/* Base Styles */
			body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
            background: #fff;
			padding: 20px auto;
			}
			
			/* Container Utama */
			.container {
            max-width: 100%;
            margin: 0 auto;
			
			}
			
			/* Header Aplikasi */
			.app-header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #333;
			}
			
			.logo-section {
            margin-bottom: 10px;
			}
			
			.app-logo {
            max-height: 60px;
            max-width: 200px;
			}
			
			.app-name {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin: 5px 0;
			}
			
			.app-tagline {
            font-size: 11px;
            color: #666;
            margin-bottom: 5px;
			}
			
			/* Info Laporan */
			.report-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 4px;
            border: 1px solid #dee2e6;
			}
			
			.report-title {
            font-size: 16px;
            font-weight: bold;
            color: #333;
			}
			
			.report-details {
            text-align: right;
            font-size: 11px;
			}
			
			.report-details div {
            margin-bottom: 2px;
			}
			
			/* Informasi Kontak */
			.contact-info {
            text-align: center;
            font-size: 11px;
            color: #666;
            margin: 10px 0;
            padding: 5px 0;
            border-top: 1px solid #eee;
            border-bottom: 1px solid #eee;
			}
			
			/* Tabel Data */
			.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 11px;
			}
			
			.data-table th {
            background-color: #f2f2f2;
            border: 1px solid #ddd;
            padding: 8px 5px;
            text-align: left;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
			}
			
			.data-table td {
            border: 1px solid #ddd;
            padding: 6px 5px;
            vertical-align: top;
			}
			
			.data-table tr:nth-child(even) {
            background-color: #f9f9f9;
			}
			
			.data-table .text-right {
            text-align: right;
			}
			
			.data-table .text-center {
            text-align: center;
			}
			
			.data-table .text-bold {
            font-weight: bold;
			}
			
			/* Items List dalam Tabel */
			.items-list {
            list-style: none;
            padding: 0;
            margin: 0;
			}
			
			.items-list li {
            padding: 2px 0;
            border-bottom: 1px dotted #eee;
			}
			
			.items-list li:last-child {
            border-bottom: none;
			}
			
			.item-detail {
            display: flex;
            justify-content: space-between;
			}
			
			.item-name {
            flex: 1;
			}
			
			.item-qty {
            font-weight: bold;
            color: #333;
			}
			
			/* Footer Tabel */
			.table-footer {
            margin-top: 15px;
            padding-top: 10px;
            border-top: 2px solid #333;
			}
			
			.summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
			}
			
			.summary-label {
            font-weight: bold;
			}
			
			.summary-value {
            font-weight: bold;
            text-align: right;
			}
			
			/* Footer Laporan */
			.report-footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #666;
			}
			
			.footer-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
			}
			
			/* Kosong State */
			.empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #666;
            font-style: italic;
			}
			
			/* Utility Classes */
			.currency {
            font-family: 'Courier New', monospace;
            font-weight: bold;
			}
			
			.timestamp {
            font-size: 10px;
            color: #666;
			}
			
			/* Styling untuk No Invoice / ID */
			.order-id {
            font-family: monospace;
            font-size: 10px;
            color: #666;
			}
			
			/* Watermark untuk draft */
			.draft-watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            color: rgba(0,0,0,0.1);
            z-index: -1;
            font-weight: bold;
            pointer-events: none;
			}
		</style>
	</head>
	<body>
		<!-- Watermark (opsional) -->
		<?php if(isset($is_draft) && $is_draft): ?>
		<div class="draft-watermark">DRAFT</div>
		<?php endif; ?>
		
		<div class="container" style="padding:20px">
			<!-- Header Aplikasi -->
			<div class="app-header">
				<div class="logo-section">
					<?php if (!empty($settings->app_logo)): ?>
					<img src="<?= base_url($settings->app_logo) ?>" 
					alt="<?= htmlspecialchars($settings->app_name) ?>" 
					class="app-logo">
					<?php endif; ?>
					
					<h1 class="app-name"><?= htmlspecialchars($settings->app_name) ?></h1>
					
					<?php if (!empty($settings->alamat)): ?>
					<div class="app-tagline">
						<?= nl2br(htmlspecialchars($settings->alamat)) ?>
					</div>
					<?php endif; ?>
				</div>
			</div>
			
			<!-- Informasi Kontak (opsional) -->
			<?php if (!empty($settings->wa_number)): ?>
			<div class="contact-info">
				WhatsApp: <?= htmlspecialchars($settings->wa_number) ?>
				<?php if (!empty($settings->qris_content)): ?>
				| QRIS: Tersedia
				<?php endif; ?>
			</div>
			<?php endif; ?>
			
			<!-- Info Laporan -->
			<div class="report-info">
				<div class="report-title">
					<?= htmlspecialchars($title) ?>
				</div>
				<div class="report-details">
					<?php if($start_date && $end_date): ?>
					<div>Periode: <?= date('d/m/Y', strtotime($start_date)) ?> - <?= date('d/m/Y', strtotime($end_date)) ?></div>
					<?php endif; ?>
					<div>Dicetak: <?= date('d/m/Y H:i') ?></div>
					<div>Oleh: <?= $this->session->userdata('username') ?></div>
					<div class="timestamp">ID: LAP-<?= date('YmdHis') ?></div>
				</div>
			</div>
			
			<!-- Konten Utama -->
			<?php if(empty($orders)): ?>
			<div class="empty-state">
				Tidak ada data pesanan untuk ditampilkan.
			</div>
			<?php else: ?>
			<!-- Tabel Data -->
			<table class="data-table">
				<thead>
					<tr>
						<th width="30">No</th>
						<th>Nama Pemesan</th>
						<th width="120">Lokasi</th>
						<th width="150">Items</th>
						<th width="80" class="text-right">Total</th>
						<th width="80" class="text-right">Admin Fee</th>
						<th width="100">Tanggal</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						$grand_total = 0;
						$grand_fee = 0;
					?>
					<?php foreach($orders as $i => $order): 
						$items = json_decode($order->items, true);
						$grand_total += $order->total;
						$grand_fee += $order->fee;
					?>
					<tr>
						<td class="text-center"><?= $i + 1 ?></td>
						<td>
							<div class="text-bold"><?= htmlspecialchars($order->name) ?></div>
							<div class="order-id">#<?= $order->id ?></div>
						</td>
						<td><?= htmlspecialchars($order->location) ?></td>
						<td>
							<?php if ($items && is_array($items)): ?>
							<ul class="items-list">
								<?php foreach ($items as $item): ?>
								<li>
									<div class="item-detail">
										<span class="item-name"><?= htmlspecialchars($item['name']) ?></span>
										<span class="item-qty"><?= $item['quantity'] ?>x</span>
									</div>
								</li>
								<?php endforeach; ?>
							</ul>
							<?php else: ?>
							-
							<?php endif; ?>
						</td>
						<td class="text-right currency"><?= number_format($order->total, 0, ',', '.') ?></td>
						<td class="text-right currency"><?= number_format($order->fee, 0, ',', '.') ?></td>
						<td>
							<div><?= date('d/m/Y', strtotime($order->created_at)) ?></div>
							<div class="timestamp"><?= date('H:i', strtotime($order->created_at)) ?></div>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
				<tfoot>
					<tr style="background-color: #f2f2f2;">
						<td colspan="4" class="text-right text-bold">TOTAL:</td>
						<td class="text-right text-bold currency"><?= number_format($grand_total, 0, ',', '.') ?></td>
						<td class="text-right text-bold currency"><?= number_format($grand_fee, 0, ',', '.') ?></td>
						<td></td>
					</tr>
				</tfoot>
			</table>
			
			<!-- Ringkasan Footer -->
			<div class="table-footer">
				<div class="summary-row">
					<span class="summary-label">Jumlah Pesanan:</span>
					<span class="summary-value"><?= count($orders) ?> pesanan</span>
				</div>
				<div class="summary-row">
					<span class="summary-label">Total Pendapatan:</span>
					<span class="summary-value currency">Rp <?= number_format($grand_total, 0, ',', '.') ?></span>
				</div>
				<div class="summary-row">
					<span class="summary-label">Total Admin Fee (<?= $settings->admin_fee ?>%):</span>
					<span class="summary-value currency">Rp <?= number_format($grand_fee, 0, ',', '.') ?></span>
				</div>
				<?php if($settings->admin_fee > 0): ?>
				<div class="summary-row">
					<span class="summary-label">Pendapatan Bersih:</span>
					<span class="summary-value currency">Rp <?= number_format($grand_total - $grand_fee, 0, ',', '.') ?></span>
				</div>
				<?php endif; ?>
			</div>
			<?php endif; ?>
			
			<!-- Footer Laporan -->
			<div class="report-footer">
				<div class="footer-row">
					<div><?= $settings->app_name ?> &copy; <?= date('Y') ?></div>
					<div>Halaman 1 dari 1</div>
				</div>
				<div class="footer-row">
					<div>Dokumen ini dicetak dari sistem <?= $settings->app_name ?></div>
					<div><?= date('d F Y, H:i:s') ?></div>
				</div>
			</div>
		</div>
		
		<!-- Tombol Print (hanya tampil di browser) -->
		<div class="no-print" style="position: fixed; bottom: 20px; right: 20px;">
			<button onclick="window.print()" 
			style="background: #333; 
			color: white; 
			border: none; 
			padding: 10px 20px; 
			border-radius: 4px; 
			cursor: pointer;
			font-size: 14px;">
				🖨️ Cetak Laporan
			</button>
		</div>
		
		<script>
			// Auto print setelah halaman load (opsional)
			window.onload = function() {
				// Uncomment baris berikut untuk auto print
				// setTimeout(() => { window.print(); }, 1000);
			};
			
			// Format angka dengan pemisah ribuan
			function formatNumber(num) {
				return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
			}
		</script>
	</body>
</html>