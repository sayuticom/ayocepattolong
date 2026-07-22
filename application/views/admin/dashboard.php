<!-- Dashboard Content -->
<style>
    .admin-dashboard-page {
        --dashboard-surface: #1E293B;
        --dashboard-surface-soft: #334155;
        --dashboard-title: #F8FAFC;
        --dashboard-text: #CBD5E1;
        --dashboard-muted: #94A3B8;
        --dashboard-link: #FDBA74;
    }

    .admin-dashboard-heading {
        color: #0F172A;
    }

    .admin-dashboard-subtitle {
        color: #475569;
    }

    .admin-public-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        min-height: 42px;
        padding: 0.625rem 1rem;
        border: 1px solid rgba(249, 115, 22, 0.55);
        border-radius: 0.75rem;
        background: #F97316;
        color: #FFFFFF;
        font-size: 0.875rem;
        font-weight: 700;
        box-shadow: 0 10px 20px -12px rgba(249, 115, 22, 0.65);
        transition: background-color 0.2s ease, border-color 0.2s ease, transform 0.2s ease;
    }

    .admin-public-link:hover {
        background: #EA580C;
        border-color: #FB923C;
        color: #FFFFFF;
        transform: translateY(-1px);
    }

    .dashboard-panel,
    .dashboard-stat-card {
        background: var(--dashboard-surface) !important;
        color: var(--dashboard-text);
        border-color: rgba(148, 163, 184, 0.22);
    }

    .dashboard-panel-title,
    .dashboard-stat-card .dashboard-stat-value {
        color: var(--dashboard-title) !important;
    }

    .dashboard-panel-text,
    .dashboard-stat-card .dashboard-stat-label,
    .dashboard-table th,
    .dashboard-table td {
        color: var(--dashboard-text) !important;
    }

    .dashboard-panel-muted,
    .dashboard-stat-card .dashboard-stat-muted,
    .dashboard-table .dashboard-table-muted {
        color: var(--dashboard-muted) !important;
    }

    .dashboard-link {
        color: var(--dashboard-link) !important;
    }

    .dashboard-link:hover {
        color: #FED7AA !important;
    }

    .dashboard-icon-tile {
        background: rgba(255, 255, 255, 0.08) !important;
    }

    .dashboard-activity-item,
    .dashboard-quick-card,
    .dashboard-info-item {
        background: var(--dashboard-surface-soft) !important;
    }

    .dashboard-table tr {
        border-color: rgba(148, 163, 184, 0.22) !important;
    }

    .dashboard-table tr:hover {
        background: rgba(51, 65, 85, 0.75) !important;
    }

    @media (max-width: 640px) {
        .admin-dashboard-header {
            gap: 1rem;
        }

        .admin-public-link {
            width: 100%;
        }
    }
</style>

<div class="admin-dashboard-page container mx-auto px-4 py-6">
    <!-- Page Header -->
    <div class="admin-dashboard-header mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="admin-dashboard-heading text-3xl font-bold"><?= $title ?></h1>
            <p class="admin-dashboard-subtitle mt-2">Ringkasan data dan aktivitas terbaru</p>
        </div>
        <a href="<?= base_url('/'); ?>" target="_blank" rel="noopener noreferrer" class="admin-public-link mt-4 sm:mt-0">
            <i class="fas fa-globe-asia"></i>
            <span class="hidden sm:inline">Lihat Website Publik</span>
            <span class="sm:hidden">Website</span>
            <i class="fas fa-arrow-up-right-from-square text-xs"></i>
        </a>
	</div>
	
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <?php foreach ($summary as $key => $stat): ?>
        <div class="dashboard-stat-card rounded-xl shadow-soft p-6 transition-all duration-300 hover:shadow-hard hover:-translate-y-1 border-l-4 border-<?= $stat['color'] ?>-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="dashboard-stat-label text-sm font-medium uppercase tracking-wider">
                        <?= ucfirst($key) ?>
					</p>
                    <p class="dashboard-stat-value text-3xl font-bold mt-2">
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
                <div class="dashboard-icon-tile p-3 rounded-lg">
                    <i class="<?= $stat['icon'] ?> text-<?= $stat['color'] ?>-400 text-2xl"></i>
				</div>
			</div>
            <a href="<?= $stat['link'] ?>" 
			class="dashboard-link mt-4 inline-flex items-center text-sm font-medium">
                Lihat detail
                <i class="fas fa-arrow-right ml-1"></i>
			</a>
		</div>
        <?php endforeach; ?>
	</div>
	
    <!-- Charts and Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Chart Section -->
        <div class="dashboard-panel rounded-xl shadow-soft p-6">
            <h3 class="dashboard-panel-title text-lg font-semibold mb-4">
                <i class="fas fa-chart-line mr-2"></i>Statistik Pertumbuhan
			</h3>
            <div class="space-y-6">
                <!-- Relawan Chart -->
                <div>
                    <h4 class="dashboard-panel-text text-sm font-medium mb-2">Relawan per Bulan</h4>
                    <div class="h-48">
                        <canvas id="relawanChart"></canvas>
					</div>
				</div>
                <!-- Informasi Chart -->
                <div>
                    <h4 class="dashboard-panel-text text-sm font-medium mb-2">Informasi per Bulan</h4>
                    <div class="h-48">
                        <canvas id="informasiChart"></canvas>
					</div>
				</div>
			</div>
		</div>
		
        <!-- Recent Activity -->
        <div class="dashboard-panel rounded-xl shadow-soft p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="dashboard-panel-title text-lg font-semibold">
                    <i class="fas fa-history mr-2"></i>Aktivitas Terbaru
				</h3>
                <a href="#" class="dashboard-link text-sm">Lihat semua</a>
			</div>
            
            <div class="space-y-4">
                <?php if(empty($recent_activities)): ?>
                <div class="text-center py-8">
                    <i class="fas fa-inbox text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
                    <p class="dashboard-panel-muted">Belum ada aktivitas</p>
				</div>
                <?php else: ?>
                <?php foreach($recent_activities as $activity): ?>
                <div class="dashboard-activity-item flex items-start space-x-3 p-3 rounded-lg transition-colors">
                    <div class="dashboard-icon-tile flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center">
                        <i class="<?= $activity['icon'] ?> text-<?= $activity['color'] ?>-400"></i>
					</div>
                    <div class="flex-1">
                        <p class="dashboard-panel-title text-sm font-medium">
                            <?= $activity['title'] ?>
						</p>
                        <p class="dashboard-panel-text text-sm mt-1">
                            <?= $activity['description'] ?>
						</p>
                        <p class="dashboard-panel-muted text-xs mt-2">
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
    <div class="dashboard-panel mt-8 rounded-xl shadow-soft p-6">
        <h3 class="dashboard-panel-title text-lg font-semibold mb-4">
            <i class="fas fa-tachometer-alt mr-2"></i>Statistik Cepat
		</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="dashboard-quick-card text-center p-4 rounded-lg">
                <div class="text-3xl font-bold text-white mb-2">
                    <?= number_format($summary['relawan']['total']) ?>
				</div>
                <p class="dashboard-panel-text text-sm">Total Relawan</p>
			</div>
            <div class="dashboard-quick-card text-center p-4 rounded-lg">
                <div class="text-3xl font-bold text-green-300 mb-2">
                    <?= number_format($summary['slider']['active']) ?>
				</div>
                <p class="dashboard-panel-text text-sm">Slider Aktif</p>
			</div>
            <div class="dashboard-quick-card text-center p-4 rounded-lg">
                <div class="text-3xl font-bold text-orange-300 mb-2">
                    <?= number_format($summary['users']['active']) ?>
				</div>
                <p class="dashboard-panel-text text-sm">User Aktif</p>
			</div>
		</div>
	</div>
	
    <!-- System Overview -->
    <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Relawan -->
        <div class="dashboard-panel rounded-xl shadow-soft p-6">
            <h3 class="dashboard-panel-title text-lg font-semibold mb-4">
                <i class="fas fa-users mr-2"></i>Relawan Terbaru
			</h3>
            <div class="overflow-x-auto">
                <table class="dashboard-table w-full text-sm">
                    <thead>
                        <tr class="border-b dark:border-gray-700">
                            <th class="py-2 text-left">Nama</th>
                            <th class="py-2 text-left">Telepon</th>
                            <th class="py-2 text-left">Tanggal</th>
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
                            <td colspan="3" class="dashboard-table-muted py-4 text-center">Belum ada relawan</td>
						</tr>
                        <?php else: ?>
                        <?php foreach($relawan as $r): ?>
                        <tr class="border-b">
                            <td class="py-3"><?= htmlspecialchars($r->nama) ?></td>
                            <td class="py-3"><?= htmlspecialchars($r->telepon) ?></td>
                            <td class="dashboard-table-muted py-3"><?= date('d/m/Y', strtotime($r->created_at)) ?></td>
						</tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
					</tbody>
				</table>
			</div>
            <a href="<?= site_url('admin/relawan') ?>" 
			class="dashboard-link mt-4 inline-flex items-center text-sm font-medium">
                Lihat semua relawan
                <i class="fas fa-arrow-right ml-1"></i>
			</a>
		</div>
		
        <!-- Recent Informasi -->
        <div class="dashboard-panel rounded-xl shadow-soft p-6">
            <h3 class="dashboard-panel-title text-lg font-semibold mb-4">
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
                    <p class="dashboard-panel-muted">Belum ada informasi</p>
				</div>
                <?php else: ?>
                <?php foreach($informasi as $info): ?>
                <div class="dashboard-info-item p-3 border-l-4 border-success dark:border-green-500 rounded">
                    <h4 class="dashboard-panel-title font-medium"><?= htmlspecialchars($info->title) ?></h4>
                    <p class="dashboard-panel-text text-sm mt-1 truncate"><?= htmlspecialchars($info->caption) ?></p>
                    <p class="dashboard-panel-muted text-xs mt-2">
                        <i class="far fa-clock mr-1"></i>
                        <?= date('d/m/Y H:i', strtotime($info->create_at)) ?>
					</p>
				</div>
                <?php endforeach; ?>
                <?php endif; ?>
			</div>
            <a href="<?= site_url('admin/informasi') ?>" 
			class="dashboard-link mt-4 inline-flex items-center text-sm font-medium">
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
		const dashboardChartScales = function() {
			return {
				x: {
					ticks: {
						color: '#CBD5E1'
					},
					grid: {
						color: 'rgba(148, 163, 184, 0.15)'
					}
				},
				y: {
					beginAtZero: true,
					ticks: {
						color: '#CBD5E1',
						precision: 0
					},
					grid: {
						color: 'rgba(148, 163, 184, 0.15)'
					}
				}
			};
		};

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
				scales: dashboardChartScales()
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
				scales: dashboardChartScales()
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
