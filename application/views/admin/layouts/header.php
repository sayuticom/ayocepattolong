<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?= isset($title)?$title:'Admin' ?></title>
		<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
	</head>
	<body class="bg-gray-100">
		<!-- topbar -->
		<div class="bg-white shadow">
			<div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
				<div class="flex items-center space-x-4">
					<button id="btn-menu" class="md:hidden px-2">☰</button>
					<div class="text-lg font-semibold"><?= $settings->app_name ?? 'Admin Panel' ?></div>
				</div>
				<div class="flex items-center space-x-4">
					<span class="text-sm"><?= $current_user['fullname'] ?? $current_user['username'] ?></span>
					<a href="<?= site_url('logout') ?>" class="text-sm text-red-600">Logout</a>
				</div>
			</div>
		</div>
		<div class="flex">
				