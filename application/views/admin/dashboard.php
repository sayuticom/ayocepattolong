<!-- Dashboard Content -->
<div class="container mx-auto px-4 py-6">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white"><?= $title ?></h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">Ringkasan data dan aktivitas terbaru</p>
	</div>
	
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <?php foreach ($summary as $key => $stat): ?>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-soft p-6 transition-all duration-300 hover:shadow-hard hover:-translate-y-1 border-l-4 border-<?= $stat['color'] ?>-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                        <?= ucfirst($key) ?>
					</p>
                    <p class="text-3xl font-bold text-gray-800 dark:text-white mt-2">
                        <?= number_format($stat['total']) ?>
					</p>
                    <?php if(isset($stat['today'])): ?>
                    <p class="text-sm text-<?= $stat['color'] ?>-600 dark:text-<?= $stat['color'] ?>-400 mt-1">
                        <i class="fas fa-calendar-day mr-1"></i>
                        <?= $stat['today'] ?> hari ini
					</p>
                    <?php elseif(isset($stat['active'])): ?>
                    <p class="text-sm text-<?= $stat['color'] ?>-600 dark:text-<?= $stat['color'] ?>-400 mt-1">
                        <i class="fas fa-check-circle mr-1"></i>
                        <?= $stat['active'] ?> aktif
					</p>
                    <?php endif; ?>
				</div>
                <div class="p-3 bg-<?= $stat['color'] ?>-100 dark:bg-<?= $stat['color'] ?>-900 rounded-lg">
                    <i class="<?= $stat['icon'] ?> text-<?= $stat['color'] ?>-600 dark:text-<?= $stat['color'] ?>-400 text-2xl"></i>
				</div>
			</div>
            <a href="<?= $stat['link'] ?>" 
			class="mt-4 inline-flex items-center text-sm font-medium text-<?= $stat['color'] ?>-600 hover:text-<?= $stat['color'] ?>-800">
                Lihat detail
                <i class="fas fa-arrow-right ml-1"></i>
			</a>
		</div>
        <?php endforeach; ?>
	</div>
	
    <!-- Charts and Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Chart Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-soft p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                <i class="fas fa-chart-line mr-2"></i>Statistik Pertumbuhan
			</h3>
            <div class="space-y-6">
                <!-- Relawan Chart -->
                <div>
                    <h4 class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Relawan per Bulan</h4>
                    <div class="h-48">
                        <canvas id="relawanChart"></canvas>
					</div>
				</div>
                <!-- Informasi Chart -->
                <div>
                    <h4 class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Informasi per Bulan</h4>
                    <div class="h-48">
                        <canvas id="informasiChart"></canvas>
					</div>
				</div>
			</div>
		</div>
		
        <!-- Recent Activity -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-soft p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                    <i class="fas fa-history mr-2"></i>Aktivitas Terbaru
				</h3>
                <a href="#" class="text-sm text-primary-600 hover:text-primary-800">Lihat semua</a>
			</div>
            
            <div class="space-y-4">
                <?php if(empty($recent_activities)): ?>
                <div class="text-center py-8">
                    <i class="fas fa-inbox text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
                    <p class="text-gray-500 dark:text-gray-400">Belum ada aktivitas</p>
				</div>
                <?php else: ?>
                <?php foreach($recent_activities as $activity): ?>
                <div class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <div class="flex-shrink-0 w-10 h-10 bg-<?= $activity['color'] ?>-100 dark:bg-<?= $activity['color'] ?>-900 rounded-full flex items-center justify-center">
                        <i class="<?= $activity['icon'] ?> text-<?= $activity['color'] ?>-600 dark:text-<?= $activity['color'] ?>-400"></i>
					</div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-800 dark:text-white">
                            <?= $activity['title'] ?>
						</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                            <?= $activity['description'] ?>
						</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            <i class="far fa-clock mr-1"></i>
                            <?= $activity['time'] ?>
						</p>
					</div>
				</div>
                <?php endforeach; ?>
                <?php endif; ?>
			</div>
		</div>
	</div>
	
    <!-- Quick Stats -->
    <div class="mt-8 bg-white dark:bg-gray-800 rounded-xl shadow-soft p-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
            <i class="fas fa-tachometer-alt mr-2"></i>Statistik Cepat
		</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <div class="text-3xl font-bold text-primary-600 dark:text-primary-400 mb-2">
                    <?= number_format($summary['relawan']['total']) ?>
				</div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Total Relawan</p>
			</div>
            <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <div class="text-3xl font-bold text-success dark:text-green-400 mb-2">
                    <?= number_format($summary['slider']['active']) ?>
				</div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Slider Aktif</p>
			</div>
            <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <div class="text-3xl font-bold text-warning dark:text-yellow-400 mb-2">
                    <?= number_format($summary['users']['active']) ?>
				</div>
                <p class="text-sm text-gray-600 dark:text-gray-400">User Aktif</p>
			</div>
		</div>
	</div>
	
    <!-- System Overview -->
    <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Relawan -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-soft p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                <i class="fas fa-users mr-2"></i>Relawan Terbaru
			</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b dark:border-gray-700">
                            <th class="py-2 text-left text-gray-600 dark:text-gray-400">Nama</th>
                            <th class="py-2 text-left text-gray-600 dark:text-gray-400">Telepon</th>
                            <th class="py-2 text-left text-gray-600 dark:text-gray-400">Tanggal</th>
						</tr>
					</thead>
                    <tbody>
                        <?php 
							$this->db->order_by('created_at', 'DESC');
							$this->db->limit(5);
							$relawan = $this->db->get('relawan')->result();
						?>
                        <?php if(empty($relawan)): ?>
                        <tr>
                            <td colspan="3" class="py-4 text-center text-gray-500">Belum ada relawan</td>
						</tr>
                        <?php else: ?>
                        <?php foreach($relawan as $r): ?>
                        <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="py-3"><?= htmlspecialchars($r->nama) ?></td>
                            <td class="py-3"><?= htmlspecialchars($r->telepon) ?></td>
                            <td class="py-3 text-gray-500"><?= date('d/m/Y', strtotime($r->created_at)) ?></td>
						</tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
					</tbody>
				</table>
			</div>
            <a href="<?= site_url('admin/relawan') ?>" 
			class="mt-4 inline-flex items-center text-sm font-medium text-primary-600 hover:text-primary-800">
                Lihat semua relawan
                <i class="fas fa-arrow-right ml-1"></i>
			</a>
		</div>
		
        <!-- Recent Informasi -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-soft p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                <i class="fas fa-newspaper mr-2"></i>Informasi Terbaru
			</h3>
            <div class="space-y-3">
                <?php 
					$this->db->order_by('create_at', 'DESC');
					$this->db->limit(5);
					$informasi = $this->db->get('informasi')->result();
				?>
                <?php if(empty($informasi)): ?>
                <div class="text-center py-4">
                    <p class="text-gray-500">Belum ada informasi</p>
				</div>
                <?php else: ?>
                <?php foreach($informasi as $info): ?>
                <div class="p-3 border-l-4 border-success dark:border-green-500 bg-gray-50 dark:bg-gray-700 rounded">
                    <h4 class="font-medium text-gray-800 dark:text-white"><?= htmlspecialchars($info->title) ?></h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 truncate"><?= htmlspecialchars($info->caption) ?></p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                        <i class="far fa-clock mr-1"></i>
                        <?= date('d/m/Y H:i', strtotime($info->create_at)) ?>
					</p>
				</div>
                <?php endforeach; ?>
                <?php endif; ?>
			</div>
            <a href="<?= site_url('admin/informasi') ?>" 
			class="mt-4 inline-flex items-center text-sm font-medium text-primary-600 hover:text-primary-800">
                Lihat semua informasi
                <i class="fas fa-arrow-right ml-1"></i>
			</a>
		</div>
	</div>
</div>
 <!-- JavaScript for Charts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
	$(document).ready(function() {
		// Relawan Chart
		const relawanCtx = document.getElementById('relawanChart').getContext('2d');
		const relawanChart = new Chart(relawanCtx, {
			type: 'line',
			data: {
				labels: <?= json_encode(array_column($chart_data['relawan_chart'], 'month')) ?>,
				datasets: [{
					label: 'Relawan',
					data: <?= json_encode(array_column($chart_data['relawan_chart'], 'total')) ?>,
					borderColor: '#3b82f6',
					backgroundColor: 'rgba(59, 130, 246, 0.1)',
					tension: 0.4,
					fill: true
				}]
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
				plugins: {
					legend: {
						display: false
					}
				},
				scales: {
					y: {
						beginAtZero: true,
						ticks: {
							precision: 0
						}
					}
				}
			}
		});
		
		// Informasi Chart
		const informasiCtx = document.getElementById('informasiChart').getContext('2d');
		const informasiChart = new Chart(informasiCtx, {
			type: 'bar',
			data: {
				labels: <?= json_encode(array_column($chart_data['informasi_chart'], 'month')) ?>,
				datasets: [{
					label: 'Informasi',
					data: <?= json_encode(array_column($chart_data['informasi_chart'], 'total')) ?>,
					backgroundColor: '#10b981',
					borderColor: '#10b981',
					borderWidth: 1
				}]
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
				plugins: {
					legend: {
						display: false
					}
				},
				scales: {
					y: {
						beginAtZero: true,
						ticks: {
							precision: 0
						}
					}
				}
			}
		});
		
		// Auto refresh every 30 seconds
		setInterval(function() {
			$.get('<?= site_url("admin/dashboard/refresh") ?>', function(data) {
				// Update summary cards
				$.each(data.summary, function(key, stat) {
					$('.' + key + '-total').text(stat.total);
				});
				
				// Update charts
				relawanChart.data.labels = data.chart_data.relawan_chart.months;
				relawanChart.data.datasets[0].data = data.chart_data.relawan_chart.totals;
				relawanChart.update();
				
				informasiChart.data.labels = data.chart_data.informasi_chart.months;
				informasiChart.data.datasets[0].data = data.chart_data.informasi_chart.totals;
				informasiChart.update();
			}, 'json');
		}, 30000);
	});
</script> <!-- Dashboard Content -->
 