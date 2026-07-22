<!DOCTYPE html>
<html lang="en">
	<?php
		$app_name = !empty($settings->app_name) ? $settings->app_name : 'Ayo Cepat Tolong';
		$logo_file = !empty($settings->app_logo) ? basename($settings->app_logo) : '';
		$logo_path = ($logo_file && is_file(FCPATH . 'uploads' . DIRECTORY_SEPARATOR . $logo_file))
			? 'uploads/' . $logo_file
			: 'uploads/act_logo.png';
	?>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Login Panel - <?= html_escape($app_name) ?></title>
		<script src="https://cdn.tailwindcss.com"></script>
	</head>
	
	<body class="bg-gray-100">
		
		<div class="flex items-center justify-center h-screen">
			<div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
				
				<div class="flex justify-center mb-5">
					<img src="<?= base_url($logo_path) ?>" alt="<?= html_escape($app_name) ?>" class="max-h-14 w-auto object-contain">
				</div>
				<h2 class="text-2xl font-bold text-center mb-6">Login Admin Panel</h2>
				
				<?php if ($this->session->flashdata('error')): ?>
				<div class="bg-red-100 text-red-700 p-3 rounded mb-4">
					<?= $this->session->flashdata('error') ?>
				</div>
				<?php endif; ?>

				<?php if (!empty($error)): ?>
				<div class="bg-red-100 text-red-700 p-3 rounded mb-4">
					<?= html_escape($error) ?>
				</div>
				<?php endif; ?>
				
				<form action="<?= site_url('auth/login') ?>" method="POST">
					
					<label class="block mb-2 text-sm font-medium">Username</label>
					<input type="text" name="username" autocomplete="username" required
					class="w-full border px-3 py-2 rounded mb-4 focus:ring focus:ring-blue-300">
					
					<label class="block mb-2 text-sm font-medium">Password</label>
					<input type="password" name="password" autocomplete="current-password" required
					class="w-full border px-3 py-2 rounded mb-4 focus:ring focus:ring-blue-300">
					
					<button type="submit"
					class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
						Login
					</button>
					
				</form>
			</div>
		</div>
		
	</body>
</html>
