<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Login Panel</title>
		<script src="https://cdn.tailwindcss.com"></script>
	</head>
	
	<body class="bg-gray-100">
		
		<div class="flex items-center justify-center h-screen">
			<div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
				
				<h2 class="text-2xl font-bold text-center mb-6">Login Admin Panel</h2>
				
				<?php if ($this->session->flashdata('error')): ?>
				<div class="bg-red-100 text-red-700 p-3 rounded mb-4">
					<?= $this->session->flashdata('error') ?>
				</div>
				<?php endif; ?>
				
				<form action="<?= site_url('auth/login') ?>" method="POST">
					
					<label class="block mb-2 text-sm font-medium">Username</label>
					<input type="text" name="username" required
					class="w-full border px-3 py-2 rounded mb-4 focus:ring focus:ring-blue-300">
					
					<label class="block mb-2 text-sm font-medium">Password</label>
					<input type="password" name="password" required
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
