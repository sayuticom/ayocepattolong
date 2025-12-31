<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>ACT – Ayoo Tolong</title>
		<link rel="icon" href="<?= base_url('assets/img/favicon.png') ?>">
		<script src="https://cdn.tailwindcss.com"></script>
		
		<script>
			function toggleDarkMode() {
				document.documentElement.classList.toggle('dark');
			}
			
			tailwind.config = {
				darkMode: 'class',
				theme: {
					extend: {
						colors: {
							orange: '#ff7a00',
						}
					}
				}
			}
		</script>
		
		<style>
			.orange { color:#ff7a00; }
			.bg-orange { background:#ff7a00; }
		</style>
	</head>
	
	<body class="bg-gray-100 dark:bg-gray-900 dark:text-white">
		
		<!-- FLASH MESSAGE -->
		<?php if ($this->session->flashdata('sukses')): ?>
		<div class="p-4 bg-green-500 text-white text-center">
			<?= $this->session->flashdata('sukses'); ?>
		</div>
		<?php endif; ?>
		
		<!-- NAVBAR -->
		<header class="bg-white dark:bg-gray-800 shadow sticky top-0 z-50">
			<div class="max-w-6xl mx-auto flex items-center justify-between px-4 py-4">
				<div>
					<a href="#home">
						<img src="<?= base_url('assets/img/act_logo.png') ?>" alt="Logo ACT" class="h-10 w-auto">
					</a>
				</div>
				
				
				<div class="hidden md:flex space-x-6 font-medium">
					<a href="#home" class="hover:text-orange">Home</a>
					<a href="#donasi" class="hover:text-orange">Donasi</a>
					<a href="#relawan" class="hover:text-orange">Relawann</a>
				</div>
				
				<button onclick="toggleDarkMode()" 
				class="px-3 py-1 rounded bg-orange text-white text-sm">
					Dark Mode
				</button>
			</div>
		</header>
		
		<!-- HERO -->
		<section id="home" class="relative bg-orange text-white">
			<div class="max-w-6xl mx-auto px-4 py-16 md:py-24">
				<h1 class="text-4xl md:text-5xl font-bold leading-tight">
					Donasi untuk Bencana di Sumatera dan Aceh
				</h1>
				<p class="mt-4 text-lg max-w-2xl">
					Bantuan Anda sangat berarti untuk saudara kita yang terdampak bencana di Sumatera.
				</p>
				
				<div class="mt-8 flex space-x-4">
					<a href="#donasi" class="px-6 py-3 bg-white orange font-semibold rounded shadow">
						Donasi Sekarang
					</a>
					
					<a href="#relawan" class="px-6 py-3 border border-white rounded">
						Daftar Relawan
					</a>
				</div>
			</div>
		</section>
		<!-- HERO SECTION -->
		<section id="home" class="relative bg-gray text-white">
			<!-- IMAGE SLIDER -->
			<div class="relative w-full max-w-6xl mx-auto m-8 overflow-hidden rounded-lg shadow-lg">
				<div id="slider" class="flex transition-transform duration-500">
					<?php foreach($slider AS $row): ?>
					<img src="<?=base_url();?>uploads/slider/<?=$row->image?>" alt="<?=$row->title?>" class="w-full flex-shrink-0">
					<?php endforeach;?>
				</div>
				
				<!-- Prev / Next Buttons -->
				<button id="prevBtn" aria-label="Previous Slide" 
				class="absolute top-1/2 left-3 -translate-y-1/2 bg-black bg-opacity-50 text-white rounded-full p-2 hover:bg-opacity-75">
					&#10094;
				</button>
				
				<button id="nextBtn" aria-label="Next Slide" 
				class="absolute top-1/2 right-3 -translate-y-1/2 bg-black bg-opacity-50 text-white rounded-full p-2 hover:bg-opacity-75">
					&#10095;
				</button>
			</div>
		</section>
		
		<!-- INFORMASI BENCANA -->
		<section class="py-16 bg-gray-50 dark:bg-gray-800">
			<div class="max-w-6xl mx-auto px-4">
				<h2 class="text-3xl font-bold orange text-center mb-12">
					Kondisi Terkini Bencana Sumatera
				</h2>
				
				<div class="grid md:grid-cols-3 gap-8">
					<?php foreach($info AS $row): ?>
					<div class="p-6 bg-white dark:bg-gray-700 rounded-lg shadow">
						<h3 class="font-bold text-xl mb-2 orange"><?=$row->title?></h3>
						<p><?=$row->caption?></p>
					</div>
					<?php endforeach;?>
				</div>
			</div>
		</section>
		
		<!-- DONASI -->
		<section id="donasi" class="py-16">
			<div class="max-w-6xl mx-auto px-4">
				<h2 class="text-3xl font-bold orange text-center mb-12">Donasi Sekarang</h2>
				
				<div class="text-center">
					<button 
					onclick="document.getElementById('modalDonasi').classList.remove('hidden')"
					class="px-8 py-3 bg-orange text-white font-semibold rounded-lg shadow">
						Lihat Rekening Transfer
					</button>
				</div>
			</div>
		</section>
		
		<!-- MODAL DONASI -->
		<div id="modalDonasi" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
			<div class="bg-white dark:bg-gray-800 p-8 rounded-xl w-[28rem] max-w-lg shadow-lg">
				
				<h3 class="text-2xl font-bold mb-6 orange text-center">Rekening Donasi</h3>
				
				<ul class="space-y-3 text-2xl text-center">
					<li><strong>BCA:</strong> 245 258 380 2</li>
					<li class="text-xl">a.n Yayasan Harapan Dhuafa Banten</li>
				</ul>
				
				<button 
				onclick="document.getElementById('modalDonasi').classList.add('hidden')"
				class="mt-8 w-full py-3 bg-orange text-white font-semibold rounded-xl hover:bg-orange-600 transition">
					Tutup
				</button>
			</div>
		</div>
		
		
		<!-- RELAWAN -->
		<section id="relawan" class="py-16 bg-gray-100 dark:bg-gray-900">
			<div class="max-w-6xl mx-auto px-4">
				<h2 class="text-3xl font-bold orange text-center mb-12">Pendaftaran Relawan</h2>
				<form id="registerForm" class="max-w-xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow space-y-4">
					<div>
						<label class="font-semibold">Nama Lengkap</label>
						<input name="nama" required type="text" class="w-full p-3 border rounded dark:bg-gray-700">
					</div>
					
					<div>
						<label class="font-semibold">Telepon</label>
						<input name="telp" required type="text" class="w-full p-3 border rounded dark:bg-gray-700">
					</div>
					
					<div>
						<label class="font-semibold">Alamat</label>
						<textarea name="alamat" required class="w-full p-3 border rounded dark:bg-gray-700"></textarea>
					</div>
					
					<button class="w-full py-3 bg-orange text-white font-semibold rounded">
						Daftar Sekarang
					</button>
				</form>
				<div id="result" class="mt-4 text-center font-semibold"></div>
			</div>
		</section>
		
		<!-- FOOTER -->
		<footer class="py-6 bg-gray-800 text-center text-white text-sm">
			© 2025 ACT – Ayoo Cepat Tolong | ayocepattolong.info
		</footer>
		<!-- reCAPTCHA v3 -->
		<script src="https://www.google.com/recaptcha/api.js?render=6Ldc4SYsAAAAAACOcAaMF4s5XiCEFtQJThOJ_X7_"></script>
		
		<script>
			(function(){
				const slider = document.getElementById('slider');
				const slidesCount = slider.children.length;
				let currentIndex = 0;
				
				const prevBtn = document.getElementById('prevBtn');
				const nextBtn = document.getElementById('nextBtn');
				
				function updateSlider() {
					slider.style.transform = `translateX(-${currentIndex * 100}%)`;
				}
				
				prevBtn.addEventListener('click', () => {
					currentIndex = (currentIndex - 1 + slidesCount) % slidesCount;
					updateSlider();
				});
				
				nextBtn.addEventListener('click', () => {
					currentIndex = (currentIndex + 1) % slidesCount;
					updateSlider();
				});
				
				// Auto slide every 5 seconds
				setInterval(() => {
					currentIndex = (currentIndex + 1) % slidesCount;
					updateSlider();
				}, 5000);
			})();
			
			document.getElementById("registerForm").addEventListener("submit", function(e) {
				e.preventDefault();
				
				grecaptcha.ready(function () {
					grecaptcha.execute("6Ldc4SYsAAAAAACOcAaMF4s5XiCEFtQJThOJ_X7_", { action: "submit" })
					.then(function (token) {
						
						const payload = {
							nama: document.querySelector("[name='nama']").value,
							telp: document.querySelector("[name='telp']").value,
							alamat: document.querySelector("[name='alamat']").value,
							recaptcha_token: token
						};
						
						fetch("<?= base_url('api/register') ?>", {   // ← GANTI
							method: "POST",
							headers: {
								"Content-Type": "application/json",
								"X-API-KEY": "RAddaadad_12345"              // ← GANTI
							},
							body: JSON.stringify(payload)
						})
						.then(res => res.json())
						.then(res => {
							if (res.status === "success") {
								
								document.getElementById("registerForm").reset();
								
								document.getElementById("result").innerHTML =
								`<span class="text-green-600">${res.message}</span>`;
								} else {
								document.getElementById("result").innerHTML =
								`<span class="text-red-600">${res.error || res.message}</span>`;
							}
							
						})
						.catch(err => {
							document.getElementById("result").innerHTML =
							`<span class="text-red-600">Error: ${err}</span>`;
						});
					});
				});
			});
			
		</script>
		
	</body>
</html>
