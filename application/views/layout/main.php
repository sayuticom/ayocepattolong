<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title><?= $settings->app_name ?? 'Admin Panel' ?> - <?= $title ?></title>
		
		<!-- Favicon -->
		<?php if (!empty($settings->app_icon)): ?>
		<link rel="icon" type="image/x-icon" href="<?= base_url($settings->app_icon) ?>">
		<link rel="shortcut icon" href="<?= base_url($settings->app_icon) ?>" type="image/x-icon">
		<?php else: ?>
		<link rel="icon" type="image/x-icon" href="<?= base_url('assets/img/icon.png') ?>">
		<?php endif; ?>
		
		<!-- Apple Touch Icon -->
		<?php if (!empty($settings->app_logo)): ?>
		<link rel="apple-touch-icon" href="<?= base_url($settings->app_logo) ?>">
		<?php endif; ?>
		
		<!-- Tailwind CSS -->
		<script src="https://cdn.tailwindcss.com"></script>
		
		<!-- Custom Tailwind Configuration -->
		<script>
			tailwind.config = {
				darkMode: 'class',
				theme: {
					extend: {
						colors: {
							primary: {
								50: '#eff6ff',
								100: '#dbeafe',
								200: '#bfdbfe',
								300: '#93c5fd',
								400: '#60a5fa',
								500: '#3b82f6',
								600: '#2563eb',
								700: '#1d4ed8',
								800: '#1e40af',
								900: '#1e3a8a',
							},
							secondary: {
								50: '#f8fafc',
								100: '#f1f5f9',
								200: '#e2e8f0',
								300: '#cbd5e1',
								400: '#94a3b8',
								500: '#64748b',
								600: '#475569',
								700: '#334155',
								800: '#1e293b',
								900: '#0f172a',
							},
							success: '#10b981',
							warning: '#f59e0b',
							danger: '#ef4444',
							info: '#3b82f6'
						},
						animation: {
							'fade-in': 'fadeIn 0.5s ease-in-out',
							'slide-in': 'slideIn 0.3s ease-out',
							'bounce-slow': 'bounce 2s infinite',
							'pulse-slow': 'pulse 3s infinite',
							'spin-slow': 'spin 3s linear infinite',
						},
						keyframes: {
							fadeIn: {
								'0%': { opacity: '0' },
								'100%': { opacity: '1' },
							},
							slideIn: {
								'0%': { transform: 'translateX(-100%)' },
								'100%': { transform: 'translateX(0)' },
							}
						},
						boxShadow: {
							'soft': '0 2px 15px -3px rgba(0, 0, 0, 0.07), 0 10px 20px -2px rgba(0, 0, 0, 0.04)',
							'medium': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
							'hard': '0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
						}
					}
				}
			}
		</script>
		
		<!-- Font Awesome -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
		
		<!-- Google Fonts -->
		<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
		
		<!-- AOS (Animate on Scroll) -->
		<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
		
		<!-- DataTables CSS -->
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.7.0/css/select.dataTables.min.css">
		
		<!-- SweetAlert2 CSS -->
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
		
		<!-- Select2 CSS -->
		<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
		
		<!-- Flatpickr CSS -->
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
		
		<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
		<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
		<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
 
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 
		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
		<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
		<!-- Custom Styles -->
		<style>
			* {
            font-family: 'Inter', sans-serif;
			}
			
			body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
			}
			
			.sidebar-gradient {
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
			}
			
			.card-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
			}
			
			.stats-card {
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
			}
			
			.stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
			}
			
			.stats-card-1 { border-left-color: #3b82f6; }
			.stats-card-2 { border-left-color: #10b981; }
			.stats-card-3 { border-left-color: #f59e0b; }
			.stats-card-4 { border-left-color: #ef4444; }
			
			.menu-item {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
			}
			
			.menu-item::before {
            content: '';
            position: absolute;
            left: -100%;
            top: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            transition: left 0.5s;
			}
			
			.menu-item:hover::before {
            left: 100%;
			}
			
			.menu-item.active {
            background: rgba(59, 130, 246, 0.1);
            border-left: 4px solid #3b82f6;
			}
			
			.notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 8px;
            height: 8px;
            background: #ef4444;
            border-radius: 50%;
            animation: pulse 2s infinite;
			}
			
			.avatar-ring {
            border: 3px solid transparent;
            background: linear-gradient(white, white) padding-box,
			linear-gradient(135deg, #667eea 0%, #764ba2 100%) border-box;
			}
			
			.glass-effect {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
			}
			
			.dark .glass-effect {
            background: rgba(30, 41, 59, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.1);
			}
			
			.chart-container {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.05) 0%, rgba(236, 72, 153, 0.05) 100%);
            border-radius: 12px;
			}
			
			.progress-bar {
            height: 8px;
            border-radius: 4px;
            overflow: hidden;
            background: #e2e8f0;
			}
			
			.progress-fill {
            height: 100%;
            border-radius: 4px;
            transition: width 1s ease-in-out;
			}
			
			.table-row-hover:hover {
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.05) 0%, rgba(59, 130, 246, 0.02) 100%);
            transform: scale(1.001);
			}
			
			.floating-button {
            box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.4);
            animation: float 3s ease-in-out infinite;
			}
			
			@keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
			}
			
			.loader {
            border-top-color: #3b82f6;
            animation: spin 1s linear infinite;
			}
			
			@keyframes spin {
            to { transform: rotate(360deg); }
			}
			
			/* Dark mode transition */
			.dark-mode-transition * {
            transition: background-color 0.3s ease, border-color 0.3s ease;
			}
			
			/* Scrollbar styling */
			::-webkit-scrollbar {
            width: 8px;
            height: 8px;
			}
			
			::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
			}
			
			::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
			}
			
			::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
			}
			
			.dark ::-webkit-scrollbar-track {
            background: #1e293b;
			}
			
			.dark ::-webkit-scrollbar-thumb {
            background: #475569;
			}
			
			.dark ::-webkit-scrollbar-thumb:hover {
            background: #64748b;
			}
			
			/* Mobile improvements */
			@media (max-width: 768px) {
            .mobile-card {
			margin: 0.5rem;
			border-radius: 12px;
            }
            
            .mobile-nav {
			background: rgba(255, 255, 255, 0.95);
			backdrop-filter: blur(10px);
            }
            
            .dark .mobile-nav {
			background: rgba(30, 41, 59, 0.95);
            }
			}
			
			/* Print styles */
			@media print {
            .no-print {
			display: none !important;
            }
			}
			
			/* Loading overlay */
			.loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            backdrop-filter: blur(5px);
			}
			
			.dark .loading-overlay {
            background: rgba(15, 23, 42, 0.9);
			}
			
			/* Tooltip styles */
			.custom-tooltip {
            position: relative;
			}
			
			.custom-tooltip:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: #1e293b;
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            white-space: nowrap;
            z-index: 1000;
            margin-bottom: 5px;
			}
			
			.custom-tooltip:hover::before {
            content: '';
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            border: 5px solid transparent;
            border-top-color: #1e293b;
            margin-bottom: -5px;
            z-index: 1000;
			}
			/* Tambahkan di bagian CSS */
			.mobile-sidebar {
			transform: translateX(-100%);
			transition: transform 0.3s ease;
			}
			
			.mobile-sidebar.active {
			transform: translateX(0);
			}
		</style>
	</head>
	<body class="dark-mode-transition dark:bg-gray-900">
		
		<!-- Loading Overlay -->
		<div id="loadingOverlay" class="loading-overlay hidden">
			<div class="text-center">
				<div class="loader ease-linear rounded-full border-8 border-gray-200 border-t-primary-600 h-16 w-16 mx-auto mb-4"></div>
				<p class="text-gray-600 dark:text-gray-300 text-lg font-semibold animate-pulse">Memuat...</p>
			</div>
		</div>
		
		<div class="flex flex-col lg:flex-row min-h-screen">
			
			<!-- Desktop Sidebar -->
			<aside class="hidden lg:flex lg:w-72 lg:flex-col lg:fixed lg:inset-y-0">
				<div class="sidebar-gradient flex-1 flex flex-col">
					<!-- Logo Area -->
					<div class="p-6 border-b border-gray-700/30">
						<div class="flex items-center space-x-3">
							<?php if (!empty($settings->app_logo)): ?>
							<img src="<?= base_url($settings->app_logo) ?>" alt="Logo" class="w-10 h-10 rounded-lg">
							<?php else: ?>
							<div class="w-10 h-10 bg-gradient-to-r from-primary-600 to-purple-600 rounded-lg flex items-center justify-center">
								<i class="fas fa-cube text-white text-lg"></i>
							</div>
							<?php endif; ?>
							<div>
								<h1 class="text-xl font-bold text-white"><?= $settings->app_name ?? 'Admin Panel' ?></h1>
								<p class="text-xs text-gray-400">v1.0.0</p>
							</div>
						</div>
					</div>
					
					<!-- User Profile -->
					<div class="p-6 border-b border-gray-700/30">
						<div class="flex items-center space-x-3">
							<div class="relative">
								<div class="w-12 h-12 avatar-ring rounded-full overflow-hidden">
									<?php if($this->session->userdata('profile_picture')): ?>
									<img src="<?= base_url($this->session->userdata('profile_picture')) ?>" 
									alt="Profile" class="w-full h-full object-cover">
									<?php else: ?>
									<div class="w-full h-full bg-gradient-to-br from-primary-400 to-purple-500 flex items-center justify-center">
										<span class="text-white text-lg font-semibold">
											<?= strtoupper(substr($this->session->userdata('username'), 0, 1)) ?>
										</span>
									</div>
									<?php endif; ?>
								</div>
								<div class="absolute bottom-0 right-0 w-3 h-3 bg-success rounded-full border-2 border-gray-900"></div>
							</div>
							<div class="flex-1 min-w-0">
								<p class="text-sm font-semibold text-white truncate"><?= $this->session->userdata('username') ?></p>
								<p class="text-xs text-gray-400 truncate"><?= ucfirst($this->session->userdata('role')) ?></p>
							</div>
							<button id="userMenuBtn" class="text-gray-400 hover:text-white transition-colors">
								<i class="fas fa-ellipsis-v"></i>
							</button>
						</div>
						
						<!-- User Menu Dropdown -->
						<div id="userMenuDropdown" class="hidden mt-3 bg-gray-800 rounded-lg p-2 space-y-1">
							<a href="<?= site_url('admin/profile') ?>" class="flex items-center space-x-2 p-2 text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
								<i class="fas fa-user w-5"></i>
								<span>Profile</span>
							</a>
							<a href="<?= site_url('admin/settings') ?>" class="flex items-center space-x-2 p-2 text-gray-300 hover:bg-gray-700 rounded-lg transition-colors">
								<i class="fas fa-cog w-5"></i>
								<span>Settings</span>
							</a>
							<div class="border-t border-gray-700"></div>
							<a href="<?= site_url('auth/logout') ?>" class="flex items-center space-x-2 p-2 text-red-400 hover:bg-red-400/10 rounded-lg transition-colors">
								<i class="fas fa-sign-out-alt w-5"></i>
								<span>Logout</span>
							</a>
						</div>
					</div>
					
					<!-- Search Bar -->
					<div class="p-4 border-b border-gray-700/30">
						<div class="relative">
							<input type="text" 
							id="globalSearch"
							placeholder="Cari menu..."
							class="w-full bg-gray-800 border border-gray-700 rounded-lg py-2 pl-10 pr-4 text-sm text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
							<i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
						</div>
					</div>
					
					<!-- Navigation Menu -->
					<nav class="flex-1 p-4 overflow-y-auto">
						<ul class="space-y-1">
							<?php
								$current_url = current_url();
								$menu_items = [
                                'dashboard' => [
								'url' => site_url('admin/dashboard'),
								'icon' => 'fas fa-tachometer-alt',
								'text' => 'Dashboard',
								'badge' => '',
								'color' => 'text-blue-400'
                                ],
                                'relawan' => [
								'url' => site_url('admin/relawan'),
								'icon' => 'fas fa-users',
								'text' => 'Relawan',
								'badge' => '',
								'badge_color' => 'bg-primary-500',
								'color' => 'text-green-400'
                                ],
                                'informasi' => [
								'url' => site_url('admin/informasi'),
								'icon' => 'fas fa-newspaper',
								'text' => 'Informasi',
								'badge' => '',
								'color' => 'text-purple-400'
                                ],
                                'slider' => [
								'url' => site_url('admin/slider'),
								'icon' => 'fas fa-images',
								'text' => 'Slider',
								'badge' => '',
								'color' => 'text-yellow-400'
                                ],
                                 
                                'settings' => [
								'url' => site_url('admin/settings'),
								'icon' => 'fas fa-cog',
								'text' => 'Settings',
								'badge' => '',
								'color' => 'text-gray-400'
                                ],
                                'users' => [
								'url' => site_url('admin/users'),
								'icon' => 'fas fa-user-cog',
								'text' => 'Pengguna',
								'badge' => '',
								'color' => 'text-cyan-400'
                                ]
								];
								
								foreach ($menu_items as $key => $menu):
                                $is_active = strpos($current_url, $key) !== false || 
								($key == 'dashboard' && $current_url == site_url('admin'));
                                $active_class = $is_active ? 'active bg-gray-800/50' : '';
							?>
							<li>
								<a href="<?= $menu['url'] ?>" 
								class="menu-item flex items-center justify-between p-3 text-gray-300 hover:text-white hover:bg-gray-800/30 rounded-lg transition-all duration-200 <?= $active_class ?>">
									<div class="flex items-center space-x-3">
										<i class="<?= $menu['icon'] ?> <?= $menu['color'] ?> w-5 text-center"></i>
										<span class="font-medium"><?= $menu['text'] ?></span>
									</div>
									<?php if(!empty($menu['badge'])): ?>
									<span class="<?= $menu['badge_color'] ?? 'bg-primary-500' ?> text-white text-xs px-2 py-1 rounded-full animate-pulse">
										<?= $menu['badge'] ?>
									</span>
									<?php endif; ?>
								</a>
							</li>
							<?php endforeach; ?>
						</ul>
						
						<!-- System Status --
						<div class="mt-8 p-4 bg-gray-800/50 rounded-lg">
							<h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">System Status</h3>
							<div class="space-y-2">
								<div>
									<div class="flex justify-between text-xs text-gray-300 mb-1">
										<span>CPU Usage</span>
										<span>42%</span>
									</div>
									<div class="progress-bar">
										<div class="progress-fill bg-primary-500" style="width: 42%"></div>
									</div>
								</div>
								<div>
									<div class="flex justify-between text-xs text-gray-300 mb-1">
										<span>Memory</span>
										<span>78%</span>
									</div>
									<div class="progress-bar">
										<div class="progress-fill bg-warning" style="width: 78%"></div>
									</div>
								</div>
								<div>
									<div class="flex justify-between text-xs text-gray-300 mb-1">
										<span>Disk Space</span>
										<span>65%</span>
									</div>
									<div class="progress-bar">
										<div class="progress-fill bg-success" style="width: 65%"></div>
									</div>
								</div>
							</div>
						</div-->
					</nav>
					
					<!-- Sidebar Footer -->
					<div class="p-4 border-t border-gray-700/30">
						<div class="flex items-center justify-between text-xs text-gray-400">
							<span>© <?= date('Y') ?> <?= $settings->app_name ?? 'Admin Panel' ?></span>
							<span class="flex items-center">
								<i class="fas fa-circle text-success text-xs mr-1"></i>
								Online
							</span>
						</div>
					</div>
				</div>
			</aside>
			
			<!-- Mobile Sidebar Overlay -->
			<div id="mobileMenuOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden"></div>
			
			<!-- Mobile Sidebar -->
			<!-- Mobile Sidebar -->
			<aside id="mobileSidebar" class="mobile-sidebar fixed left-0 top-0 h-full w-72 bg-gray-900 shadow-xl z-50 lg:hidden">
				<div class="h-full flex flex-col">
					<!-- Header -->
					<div class="p-6 border-b border-gray-700/30 flex justify-between items-center">
						<div class="flex items-center space-x-3">
							<?php if (!empty($settings->app_logo)): ?>
							<img src="<?= base_url($settings->app_logo) ?>" alt="Logo" class="w-10 h-10 rounded-lg">
							<?php else: ?>
							<div class="w-10 h-10 bg-gradient-to-r from-primary-600 to-purple-600 rounded-lg flex items-center justify-center">
								<i class="fas fa-cube text-white text-lg"></i>
							</div>
							<?php endif; ?>
							<h1 class="text-xl font-bold text-white"><?= $settings->app_name ?? 'Admin Panel' ?></h1>
						</div>
						<button id="closeMobileMenu" class="text-gray-300 hover:text-white">
							<i class="fas fa-times text-xl"></i>
						</button>
					</div>
					
					<!-- User Profile -->
					<div class="p-6 border-b border-gray-700/30">
						<div class="flex items-center space-x-3">
							<div class="relative">
								<div class="w-12 h-12 avatar-ring rounded-full overflow-hidden">
									<?php if($this->session->userdata('profile_picture')): ?>
									<img src="<?= base_url($this->session->userdata('profile_picture')) ?>" 
									alt="Profile" class="w-full h-full object-cover">
									<?php else: ?>
									<div class="w-full h-full bg-gradient-to-br from-primary-400 to-purple-500 flex items-center justify-center">
										<span class="text-white font-semibold">
											<?= strtoupper(substr($this->session->userdata('username'), 0, 1)) ?>
										</span>
									</div>
									<?php endif; ?>
								</div>
							</div>
							<div>
								<p class="text-sm font-semibold text-white"><?= $this->session->userdata('username') ?></p>
								<p class="text-xs text-gray-400"><?= ucfirst($this->session->userdata('role')) ?></p>
							</div>
						</div>
					</div>
					
					<!-- Menu Items -->
					<nav class="flex-1 p-4 overflow-y-auto">
						<ul class="space-y-1">
							<?php
								$current_url = current_url();
								foreach ($menu_items as $key => $menu):
								$is_active = strpos($current_url, $key) !== false || 
								($key == 'dashboard' && $current_url == site_url('admin'));
								$active_class = $is_active ? 'bg-gray-800/50' : '';
							?>
							<li>
								<a href="<?= $menu['url'] ?>" 
								class="flex items-center justify-between p-3 text-gray-300 hover:text-white hover:bg-gray-800/30 rounded-lg transition-all duration-200 <?= $active_class ?>">
									<div class="flex items-center space-x-3">
										<i class="<?= $menu['icon'] ?> <?= $menu['color'] ?> w-5 text-center"></i>
										<span class="font-medium"><?= $menu['text'] ?></span>
									</div>
									<?php if(!empty($menu['badge'])): ?>
									<span class="<?= $menu['badge_color'] ?? 'bg-primary-500' ?> text-white text-xs px-2 py-1 rounded-full">
										<?= $menu['badge'] ?>
									</span>
									<?php endif; ?>
								</a>
							</li>
							<?php endforeach; ?>
						</ul>
					</nav>
					
					<!-- Footer -->
					<div class="p-4 border-t border-gray-700/30">
						<a href="<?= site_url('auth/logout') ?>" 
						class="flex items-center space-x-2 p-3 text-red-400 hover:bg-red-400/10 rounded-lg transition-colors">
							<i class="fas fa-sign-out-alt"></i>
							<span>Logout</span>
						</a>
					</div>
				</div>
			</aside>
			
			<!-- Main Content -->
			<div class="lg:ml-72 flex-1 flex flex-col min-h-screen">
				
				<!-- Top Navigation -->
				<header class="glass-effect sticky top-0 z-30">
					<div class="flex items-center justify-between px-6 py-4">
						<!-- Left: Breadcrumb and Title -->
						<div class="flex items-center space-x-4">
							<!-- Mobile Menu Toggle -->
							<button id="mobileMenuToggle" class="lg:hidden text-gray-600 dark:text-gray-300 hover:text-primary-600">
								<i class="fas fa-bars text-xl"></i>
							</button>
							
							<!-- Breadcrumb -->
							<nav class="hidden lg:flex" aria-label="Breadcrumb">
								<ol class="flex items-center space-x-2">
									<li>
										<a href="<?= site_url('admin/dashboard') ?>" 
										class="text-sm text-gray-600 dark:text-gray-400 hover:text-primary-600">
											<i class="fas fa-home"></i>
										</a>
									</li>
									<?php 
										$uri_segments = $this->uri->segment_array();
										$segments_count = count($uri_segments);
										for($i = 2; $i <= $segments_count; $i++):
                                        $segment = $uri_segments[$i];
                                        $is_last = ($i == $segments_count);
									?>
									<li class="flex items-center">
										<i class="fas fa-chevron-right text-xs text-gray-400 mx-2"></i>
										<?php if($is_last): ?>
										<span class="text-sm font-semibold text-primary-600">
											<?= ucfirst(str_replace('_', ' ', $segment)) ?>
										</span>
										<?php else: ?>
										<a href="<?= site_url(implode('/', array_slice($uri_segments, 0, $i))) ?>" 
										class="text-sm text-gray-600 dark:text-gray-400 hover:text-primary-600">
											<?= ucfirst(str_replace('_', ' ', $segment)) ?>
										</a>
										<?php endif; ?>
									</li>
									<?php endfor; ?>
								</ol>
							</nav>
							
						</div>
						
						<!-- Right: User Actions -->
						<div class="flex items-center space-x-4">
							<!-- Search Toggle (Mobile) -->
							<button id="mobileSearchToggle" class="lg:hidden text-gray-600 dark:text-gray-300 hover:text-primary-600">
								<i class="fas fa-search"></i>
							</button>
							
							<!-- Notifications -->
							<div class="relative">
								<button id="notificationsBtn" 
								class="relative p-2 text-gray-600 dark:text-gray-300 hover:text-primary-600 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
									<i class="fas fa-bell text-lg"></i>
									<span class="notification-badge"></span>
								</button>
								
								<!-- Notifications Dropdown -->
								<div id="notificationsDropdown" 
								class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-hard hidden z-50">
									<div class="p-4 border-b border-gray-200 dark:border-gray-700">
										<div class="flex justify-between items-center">
											<h3 class="font-semibold text-gray-800 dark:text-white">Notifications</h3>
											<button class="text-xs text-primary-600 hover:text-primary-700">Mark all read</button>
										</div>
									</div>
									<div class="max-h-96 overflow-y-auto">
										<!-- Notification items -->
										<div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700 border-b border-gray-100 dark:border-gray-700">
											<div class="flex space-x-3">
												<div class="flex-shrink-0">
													<div class="w-8 h-8 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center">
														<i class="fas fa-user-plus text-primary-600 dark:text-primary-400"></i>
													</div>
												</div>
												<div class="flex-1">
													<p class="text-sm text-gray-800 dark:text-gray-200">
														<span class="font-semibold">John Doe</span> registered as new user
													</p>
													<p class="text-xs text-gray-500 mt-1">2 minutes ago</p>
												</div>
											</div>
										</div>
										<!-- Add more notifications here -->
									</div>
									<div class="p-3 border-t border-gray-200 dark:border-gray-700">
										<a href="<?= site_url('admin/notifications') ?>" 
										class="block text-center text-sm text-primary-600 hover:text-primary-700 font-medium">
											View all notifications
										</a>
									</div>
								</div>
							</div>
							
							<!-- Messages -->
							<div class="relative">
								<button id="messagesBtn" 
								class="relative p-2 text-gray-600 dark:text-gray-300 hover:text-primary-600 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
									<i class="fas fa-envelope text-lg"></i>
									<span class="absolute -top-1 -right-1 w-2 h-2 bg-danger rounded-full"></span>
								</button>
							</div>
							
							<!-- User Menu (Desktop) -->
							<div class="hidden lg:flex items-center space-x-3">
								<div class="text-right">
									<p class="text-sm font-medium text-gray-800 dark:text-white">
										<?= $this->session->userdata('username') ?>
									</p>
									<p class="text-xs text-gray-500 dark:text-gray-400">
										<?= ucfirst($this->session->userdata('role')) ?>
									</p>
								</div>
								<div class="relative">
									<button id="desktopUserMenuBtn" 
									class="w-10 h-10 rounded-full overflow-hidden border-2 border-transparent hover:border-primary-500 transition-colors">
										<?php if($this->session->userdata('profile_picture')): ?>
										<img src="<?= base_url($this->session->userdata('profile_picture')) ?>" 
										alt="Profile" class="w-full h-full object-cover">
										<?php else: ?>
										<div class="w-full h-full bg-gradient-to-br from-primary-400 to-purple-500 flex items-center justify-center">
											<span class="text-white font-semibold">
												<?= strtoupper(substr($this->session->userdata('username'), 0, 1)) ?>
											</span>
										</div>
										<?php endif; ?>
									</button>
								</div>
							</div>
						</div>
					</div>
					
					<!-- Mobile Search Bar -->
					<div id="mobileSearch" class="px-6 pb-4 lg:hidden hidden">
						<div class="relative">
							<input type="text" 
							placeholder="Search..."
							class="w-full bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg py-2 pl-10 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
							<i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
						</div>
					</div>
				</header>
				
				<!-- Main Content Area -->
				<main class="flex-1 px-6 py-4">
					<!-- Page Header -->
					<div class="mb-6">
						<?php if(isset($page_header) && $page_header): ?>
						<div class="glass-effect rounded-xl p-6 mb-6">
							<div class="flex justify-between items-start">
								<div>
									<h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-2"><?= $page_header['title'] ?? $title ?></h2>
									<p class="text-gray-600 dark:text-gray-400"><?= $page_header['description'] ?? '' ?></p>
								</div>
								<?php if(isset($page_header['actions'])): ?>
								<div class="flex space-x-3">
									<?php foreach($page_header['actions'] as $action): ?>
									<a href="<?= $action['url'] ?>" 
									class="px-4 py-2 bg-<?= $action['color'] ?? 'primary' ?>-600 text-white rounded-lg hover:bg-<?= $action['color'] ?? 'primary' ?>-700 transition-colors flex items-center space-x-2">
										<?php if(isset($action['icon'])): ?>
										<i class="<?= $action['icon'] ?>"></i>
										<?php endif; ?>
										<span><?= $action['text'] ?></span>
									</a>
									<?php endforeach; ?>
								</div>
								<?php endif; ?>
							</div>
						</div>
						<?php endif; ?>
					</div>
					
					<!-- Dynamic Content -->
					<div class="mb-8">
						<?= $content ?>
					</div>
					
				</main>
				
				<!-- Footer -->
				<footer class="mt-auto border-t border-gray-200 dark:border-gray-800 py-4 px-6">
					<div class="flex flex-col md:flex-row justify-between items-center">
						<div class="text-sm text-gray-600 dark:text-gray-400 mb-4 md:mb-0">
							<p>© <?= date('Y') ?> <?= $settings->app_name ?? 'Admin Panel' ?>. All rights reserved.</p>
						</div>
						<div class="flex items-center space-x-4">
							<a href="#" class="text-sm text-gray-600 dark:text-gray-400 hover:text-primary-600">Privacy Policy</a>
							<a href="#" class="text-sm text-gray-600 dark:text-gray-400 hover:text-primary-600">Terms of Service</a>
							<a href="#" class="text-sm text-gray-600 dark:text-gray-400 hover:text-primary-600">Help Center</a>
						</div>
					</div>
				</footer>
			</div>
		</div>
		
		<!-- Custom JavaScript -->
		<script>
			// Base URL
			var base_url = "<?= site_url(); ?>";
 
			
			$(document).ready(function() {
				// Dark mode handling
				const darkModeToggle = $('#darkModeToggle');
				const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)');
				
				// Check for saved theme preference or use device preference
				const currentTheme = localStorage.getItem('theme') || 
				(prefersDarkScheme.matches ? 'dark' : 'light');
				
				if (currentTheme === 'dark') {
					$('body').addClass('dark');
					darkModeToggle.find('i').removeClass('fa-moon').addClass('fa-sun');
				}
				
				// Toggle dark mode
				darkModeToggle.on('click', function() {
					$('body').toggleClass('dark');
					const isDark = $('body').hasClass('dark');
					localStorage.setItem('theme', isDark ? 'dark' : 'light');
					darkModeToggle.find('i').toggleClass('fa-moon fa-sun');
				});
				// Mobile menu toggle
				$('#mobileMenuToggle').on('click', function() {
					$('#mobileSidebar').addClass('active');
					$('#mobileMenuOverlay').removeClass('hidden');
					$('body').addClass('overflow-hidden');
				});
				
				$('#closeMobileMenu').on('click', function() {
					$('#mobileSidebar').removeClass('active');
					$('#mobileMenuOverlay').addClass('hidden');
					$('body').removeClass('overflow-hidden');
				});
				
				$('#mobileMenuOverlay').on('click', function() {
					$('#mobileSidebar').removeClass('active');
					$(this).addClass('hidden');
					$('body').removeClass('overflow-hidden');
				});
				
				// Mobile search toggle
				$('#mobileSearchToggle').on('click', function() {
					$('#mobileSearch').slideToggle();
				});
				
				// User menu dropdowns
				$('#userMenuBtn, #desktopUserMenuBtn').on('click', function(e) {
					e.stopPropagation();
					$('#userMenuDropdown').toggleClass('hidden');
				});
				
				// Notifications dropdown
				$('#notificationsBtn').on('click', function(e) {
					e.stopPropagation();
					$('#notificationsDropdown').toggleClass('hidden');
				});
				
				// Close dropdowns when clicking outside
				$(document).on('click', function(e) {
					if (!$(e.target).closest('#userMenuBtn, #userMenuDropdown, #desktopUserMenuBtn').length) {
						$('#userMenuDropdown').addClass('hidden');
					}
					if (!$(e.target).closest('#notificationsBtn, #notificationsDropdown').length) {
						$('#notificationsDropdown').addClass('hidden');
					}
					if (!$(e.target).closest('#quickAddBtn').length) {
						$('.quick-action-btn').parent().addClass('hidden');
					}
				});
				
				// Quick actions menu
				$('#quickAddBtn').on('click', function(e) {
					e.stopPropagation();
					$('.quick-action-btn').parent().toggleClass('hidden');
				});
				
				// Scroll to top button
				$(window).on('scroll', function() {
					const scrollTop = $(window).scrollTop();
					const scrollToTopBtn = $('#scrollToTop');
					
					if (scrollTop > 300) {
						scrollToTopBtn.removeClass('opacity-0 translate-y-4').addClass('opacity-100');
						} else {
						scrollToTopBtn.removeClass('opacity-100').addClass('opacity-0 translate-y-4');
					}
				});
				
				$('#scrollToTop').on('click', function() {
					$('html, body').animate({ scrollTop: 0 }, 300);
				});
				
				// Global search
				$('#globalSearch').on('input', function() {
					const searchTerm = $(this).val().toLowerCase();
					if (searchTerm.length > 0) {
						$('.menu-item').each(function() {
							const text = $(this).text().toLowerCase();
							if (text.includes(searchTerm)) {
								$(this).show();
								$(this).addClass('bg-primary-500/10');
								} else {
								$(this).hide();
								$(this).removeClass('bg-primary-500/10');
							}
						});
						} else {
						$('.menu-item').show().removeClass('bg-primary-500/10');
					}
				});
				
				// Show/hide quick actions panel on scroll
				let lastScrollTop = 0;
				$(window).on('scroll', function() {
					const scrollTop = $(window).scrollTop();
					const quickActions = $('#quickActions');
					
					if (scrollTop > lastScrollTop) {
						// Scrolling down
						quickActions.removeClass('animate__fadeInUp').addClass('animate__fadeOutDown');
						} else {
						// Scrolling up
						quickActions.removeClass('animate__fadeOutDown').addClass('animate__fadeInUp');
					}
					lastScrollTop = scrollTop;
				});
				
				// Loading overlay for AJAX requests
				$(document).ajaxStart(function() {
					$('#loadingOverlay').removeClass('hidden');
					}).ajaxStop(function() {
					$('#loadingOverlay').addClass('hidden');
				});
				
				// Initialize Select2
				$('.select2').select2({
					width: '100%',
					theme: 'bootstrap'
				});
				
				// Initialize Flatpickr
				$('.datepicker').flatpickr({
					dateFormat: 'Y-m-d',
					allowInput: true
				});
				
				// Tooltips
				$('[data-tooltip]').hover(function() {
					const tooltip = $(this).attr('data-tooltip');
					$(this).append(`<div class="tooltip">${tooltip}</div>`);
					}, function() {
					$(this).find('.tooltip').remove();
				});
				
				// Copy to clipboard
				$('.copy-btn').on('click', function() {
					const text = $(this).attr('data-copy');
					navigator.clipboard.writeText(text).then(function() {
						Swal.fire({
							icon: 'success',
							title: 'Copied!',
							text: 'Text copied to clipboard',
							toast: true,
							position: 'top-end',
							showConfirmButton: false,
							timer: 2000
						});
					});
				});
				
				// // DataTables initialization with better options
				// if ($.fn.DataTable) {
					// $('.dataTable').DataTable({
						// responsive: true,
						// dom: '<"flex justify-between items-center mb-4"<"flex items-center"l><"flex items-center"f>>rt<"flex justify-between items-center mt-4"<"text-gray-600 text-sm"i><"flex"p>>',
						// language: {
							// search: "",
							// searchPlaceholder: "Search...",
							// lengthMenu: "_MENU_ per page",
							// info: "Showing _START_ to _END_ of _TOTAL_ entries",
							// paginate: {
								// previous: '<i class="fas fa-chevron-left"></i>',
								// next: '<i class="fas fa-chevron-right"></i>'
							// }
						// },
						// pageLength: 10,
						// lengthMenu: [5, 10, 25, 50, 100],
						// buttons: [
                        // {
                            // extend: 'copy',
                            // className: 'bg-gray-200 hover:bg-gray-300 text-gray-800 py-1 px-3 rounded text-sm'
						// },
                        // {
                            // extend: 'excel',
                            // className: 'bg-green-500 hover:bg-green-600 text-white py-1 px-3 rounded text-sm'
						// },
                        // {
                            // extend: 'pdf',
                            // className: 'bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded text-sm'
						// },
                        // {
                            // extend: 'print',
                            // className: 'bg-blue-500 hover:bg-blue-600 text-white py-1 px-3 rounded text-sm'
						// }
						// ],
						// initComplete: function() {
							// // Add custom styling to search input
							// $('.dataTables_filter input').addClass('border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500');
						// }
					// });
				// }
				
				// Auto-hide alerts
				$('.alert-auto-hide').delay(5000).fadeOut('slow');
				
				// Confirm delete
				$('.confirm-delete').on('click', function(e) {
					e.preventDefault();
					const href = $(this).attr('href');
					Swal.fire({
						title: 'Are you sure?',
						text: "You won't be able to revert this!",
						icon: 'warning',
						showCancelButton: true,
						confirmButtonColor: '#ef4444',
						cancelButtonColor: '#6b7280',
						confirmButtonText: 'Yes, delete it!',
						cancelButtonText: 'Cancel'
						}).then((result) => {
						if (result.isConfirmed) {
							window.location.href = href;
						}
					});
				});
				
				// Update active menu item
				function updateActiveMenu() {
					const currentPath = window.location.pathname;
					$('.menu-item').removeClass('active bg-gray-800/50');
					
					$('.menu-item').each(function() {
						const href = $(this).attr('href');
						if (currentPath.includes(href.replace(base_url, ''))) {
							$(this).addClass('active bg-gray-800/50');
						}
					});
				}
				
				updateActiveMenu();
				
				// System status update (simulated)
				setInterval(function() {
					const cpu = Math.floor(Math.random() * 100);
					const memory = Math.floor(Math.random() * 100);
					const disk = Math.floor(Math.random() * 100);
					
					$('.progress-fill').eq(0).css('width', cpu + '%').prev().find('span').text(cpu + '%');
					$('.progress-fill').eq(1).css('width', memory + '%').prev().find('span').text(memory + '%');
					$('.progress-fill').eq(2).css('width', disk + '%').prev().find('span').text(disk + '%');
				}, 5000);
				
				// Keyboard shortcuts
				$(document).on('keydown', function(e) {
					// Ctrl + K for search
					if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
						e.preventDefault();
						$('#globalSearch').focus();
					}
					
					// Escape to close modals
					if (e.key === 'Escape') {
						$('.modal').hide();
						$('.dropdown').hide();
					}
					
					// Ctrl + D for dark mode toggle
					if ((e.ctrlKey || e.metaKey) && e.key === 'd') {
						e.preventDefault();
						darkModeToggle.click();
					}
				});
				
				// Real-time clock
				function updateClock() {
					const now = new Date();
					const timeString = now.toLocaleTimeString('en-US', { 
						hour12: false,
						hour: '2-digit',
						minute: '2-digit'
					});
					const dateString = now.toLocaleDateString('en-US', {
						weekday: 'short',
						year: 'numeric',
						month: 'short',
						day: 'numeric'
					});
					
					$('#currentTime').text(timeString);
					$('#currentDate').text(dateString);
				}
				
				setInterval(updateClock, 1000);
				updateClock();
			});
			
			// Export functions
			window.exportData = function(format) {
				const table = $('.dataTable').DataTable();
				if (format === 'excel') {
					table.button('.buttons-excel').trigger();
					} else if (format === 'pdf') {
					table.button('.buttons-pdf').trigger();
					} else if (format === 'print') {
					table.button('.buttons-print').trigger();
				}
			};
			
			// Show loading
			window.showLoading = function() {
				$('#loadingOverlay').removeClass('hidden');
			};
			
			// Hide loading
			window.hideLoading = function() {
				$('#loadingOverlay').addClass('hidden');
			};
			
			// Show success message
			window.showSuccess = function(message, title = 'Success!') {
				Swal.fire({
					icon: 'success',
					title: title,
					text: message,
					toast: true,
					position: 'top-end',
					showConfirmButton: false,
					timer: 3000
				});
			};
			
			// Show error message
			window.showError = function(message, title = 'Error!') {
				Swal.fire({
					icon: 'error',
					title: title,
					text: message,
					toast: true,
					position: 'top-end',
					showConfirmButton: false,
					timer: 5000
				});
			};
			
			// Show warning message
			window.showWarning = function(message, title = 'Warning!') {
				Swal.fire({
					icon: 'warning',
					title: title,
					text: message,
					toast: true,
					position: 'top-end',
					showConfirmButton: false,
					timer: 4000
				});
			};
		</script>
	</body>
</html>