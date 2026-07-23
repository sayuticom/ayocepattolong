<?php
	$this->load->helper('news');

	$settings = !empty($settings) ? $settings : false;
	$app_name = !empty($settings->app_name) ? $settings->app_name : 'Ayo Cepat Tolong';
	$site_desc = !empty($settings->site_desc) ? $settings->site_desc : 'Gerakan kemanusiaan antar komunitas yang diinisiasi oleh LAMTREN.';
	$wa_number = !empty($settings->wa_number) ? preg_replace('/\D+/', '', $settings->wa_number) : '';
	$logo_file = !empty($settings->app_logo) ? basename($settings->app_logo) : '';
	$icon_file = !empty($settings->app_icon) ? basename($settings->app_icon) : '';
	$logo_path = ($logo_file && is_file(FCPATH . 'uploads' . DIRECTORY_SEPARATOR . $logo_file)) ? 'uploads/' . $logo_file : 'uploads/act_logo.png';
	$icon_path = ($icon_file && is_file(FCPATH . 'uploads' . DIRECTORY_SEPARATOR . $icon_file)) ? 'uploads/' . $icon_file : 'uploads/icon.png';
	$hero_file = !empty($settings->hero_image) ? basename($settings->hero_image) : '';
	$settings_hero_image = ($hero_file && is_file(FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'hero' . DIRECTORY_SEPARATOR . $hero_file)) ? base_url('uploads/hero/' . $hero_file) : '';

	if (!function_exists('act_text_limit')) {
		function act_text_limit($text, $limit = 160)
		{
			$text = trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags((string) $text), ENT_QUOTES, 'UTF-8')));
			if ($text === '') {
				return '';
			}

			if (function_exists('mb_strlen') && function_exists('mb_substr')) {
				return mb_strlen($text, 'UTF-8') > $limit ? rtrim(mb_substr($text, 0, $limit, 'UTF-8')) . '...' : $text;
			}

			return strlen($text) > $limit ? rtrim(substr($text, 0, $limit)) . '...' : $text;
		}
	}

	if (!function_exists('act_image_url')) {
		function act_image_url($filename, $folder = 'uploads')
		{
			$filename = trim((string) $filename);
			if ($filename === '') {
				return '';
			}

			if (preg_match('#^https?://#i', $filename)) {
				return $filename;
			}

			$candidates = [
				trim($folder, '/') . '/' . ltrim($filename, '/'),
				'uploads/' . ltrim($filename, '/'),
			];

			foreach (array_unique($candidates) as $path) {
				if (file_exists(FCPATH . $path)) {
					return base_url($path);
				}
			}

			return '';
		}
	}

	$hero_slides = [];
	if (!empty($slider)) {
		foreach ($slider as $row) {
			$image_file = isset($row->image) ? basename((string) $row->image) : '';
			if ($image_file === '' || !is_file(FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'slider' . DIRECTORY_SEPARATOR . $image_file)) {
				continue;
			}

			$mobile_file = isset($row->mobile_image) ? basename((string) $row->mobile_image) : '';
			$mobile_url = ($mobile_file && is_file(FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'slider' . DIRECTORY_SEPARATOR . $mobile_file))
				? base_url('uploads/slider/' . $mobile_file)
				: '';

			$text_position = isset($row->text_position) && in_array($row->text_position, ['left', 'center', 'right'], true) ? $row->text_position : 'left';
			$overlay_opacity = isset($row->overlay_opacity) ? (int) $row->overlay_opacity : 40;
			$overlay_opacity = max(0, min(80, $overlay_opacity));

			$primary_text = isset($row->primary_button_text) ? trim((string) $row->primary_button_text) : '';
			$primary_url = isset($row->primary_button_url) ? trim((string) $row->primary_button_url) : '';
			$secondary_text = isset($row->secondary_button_text) ? trim((string) $row->secondary_button_text) : '';
			$secondary_url = isset($row->secondary_button_url) ? trim((string) $row->secondary_button_url) : '';
			$primary_external = $primary_url !== '' && preg_match('#^https?://#i', $primary_url);
			$secondary_external = $secondary_url !== '' && preg_match('#^https?://#i', $secondary_url);

			$hero_slides[] = [
				'image' => base_url('uploads/slider/' . $image_file),
				'mobile_image' => $mobile_url,
					'title' => !empty($row->title) ? $row->title : 'Kegiatan Ayo Cepat Tolong',
					'caption' => !empty($row->caption) ? $row->caption : '',
				'primary_button_text' => $primary_text,
				'primary_button_url' => $primary_url,
				'primary_external' => $primary_external,
				'secondary_button_text' => $secondary_text,
				'secondary_button_url' => $secondary_url,
				'secondary_external' => $secondary_external,
				'text_position' => $text_position,
				'overlay_opacity' => $overlay_opacity,
			];
		}
	}

	$has_hero_slider = !empty($hero_slides);
	$hero_image = $settings_hero_image ?: (file_exists(FCPATH . 'uploads/lautan-kayu-di-aceh-tamiang.webp') ? base_url('uploads/lautan-kayu-di-aceh-tamiang.webp') : base_url($logo_path));
	$style_path = FCPATH . 'assets/style.css';
	$style_version = is_file($style_path) ? filemtime($style_path) : false;
	$style_url = base_url('assets/style.css');
	if ($style_version !== false) {
		$style_url .= (strpos($style_url, '?') === false ? '?' : '&') . 'v=' . (int) $style_version;
	}
?>
<!DOCTYPE html>
<html lang="id">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title><?= html_escape($app_name) ?></title>
		<link rel="icon" href="<?= base_url($icon_path) ?>">
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
		<link rel="stylesheet" href="<?= html_escape($style_url) ?>">
	</head>
	<body class="act-page">
		<?php if ($this->session->flashdata('sukses')): ?>
		<div class="act-flash">
			<?= $this->session->flashdata('sukses'); ?>
		</div>
		<?php endif; ?>

		<header class="act-header">
			<div class="act-container act-nav">
				<a href="#home" class="act-brand" aria-label="Beranda Ayo Cepat Tolong">
					<img src="<?= base_url($logo_path) ?>" alt="<?= html_escape($app_name) ?>">
				</a>

				<nav class="act-menu" aria-label="Navigasi utama">
					<a href="#home">Beranda</a>
					<a href="#warta">Warta</a>
					<a href="#donasi">Donasi</a>
					<a href="#relawan">Relawan</a>
					<a href="#tentang">Tentang Kami</a>
				</nav>

				<div class="act-nav-actions">
					<a href="#donasi" class="act-btn act-btn-primary act-btn-compact">Donasi Sekarang</a>
					<button class="act-menu-toggle" type="button" aria-label="Buka menu" aria-expanded="false" aria-controls="actMobileMenu">
						<span></span>
						<span></span>
						<span></span>
					</button>
				</div>
			</div>

			<div id="actMobileMenu" class="act-mobile-menu">
				<div class="act-container">
					<a href="#home">Beranda</a>
					<a href="#warta">Warta</a>
					<a href="#donasi">Donasi</a>
					<a href="#relawan">Relawan</a>
					<a href="#tentang">Tentang Kami</a>
					<a href="#donasi" class="act-btn act-btn-primary">Donasi Sekarang</a>
				</div>
			</div>
		</header>

		<main>
			<?php if ($has_hero_slider): ?>
			<section id="home" class="act-hero act-hero-slider" aria-roledescription="carousel" aria-label="Hero Ayo Cepat Tolong">
				<div class="act-hero-slides" id="actHeroSlider">
					<?php foreach ($hero_slides as $index => $slide): ?>
					<?php
						$is_active_slide = $index === 0;
						$position_class = 'act-hero-slide-' . $slide['text_position'];
						$heading_tag = $is_active_slide ? 'h1' : 'h2';
					?>
					<article class="act-hero-slide <?= $position_class ?> <?= $is_active_slide ? 'is-active' : '' ?>"
						aria-hidden="<?= $is_active_slide ? 'false' : 'true' ?>"
						style="--hero-overlay-opacity: <?= $slide['overlay_opacity'] / 100; ?>;">
						<div class="act-hero-slide-media">
							<picture>
								<?php if (!empty($slide['mobile_image'])): ?>
								<source media="(max-width: 640px)" srcset="<?= html_escape($slide['mobile_image']) ?>">
								<?php endif; ?>
								<img src="<?= html_escape($slide['image']) ?>" alt="<?= html_escape($slide['title']) ?>">
							</picture>
						</div>
						<div class="act-hero-slide-overlay"></div>
						<div class="act-hero-slide-content">
							<div class="act-container act-hero-slide-inner">
								<p class="act-eyebrow">GERAKAN KEMANUSIAAN ANTAR KOMUNITAS</p>
								<<?= $heading_tag ?>><?= html_escape($slide['title']) ?></<?= $heading_tag ?>>
								<?php if (!empty($slide['caption'])): ?>
								<p class="act-hero-text"><?= html_escape($slide['caption']) ?></p>
								<?php endif; ?>
								<?php if ((!empty($slide['primary_button_text']) && !empty($slide['primary_button_url'])) || (!empty($slide['secondary_button_text']) && !empty($slide['secondary_button_url']))): ?>
								<div class="act-hero-actions">
									<?php if (!empty($slide['primary_button_text']) && !empty($slide['primary_button_url'])): ?>
									<a href="<?= html_escape($slide['primary_button_url']) ?>" class="act-btn act-btn-primary" <?= !empty($slide['primary_external']) ? 'target="_blank" rel="noopener noreferrer"' : '' ?> <?= $is_active_slide ? '' : 'tabindex="-1"' ?>><?= html_escape($slide['primary_button_text']) ?></a>
									<?php endif; ?>
									<?php if (!empty($slide['secondary_button_text']) && !empty($slide['secondary_button_url'])): ?>
									<a href="<?= html_escape($slide['secondary_button_url']) ?>" class="act-btn act-btn-outline" <?= !empty($slide['secondary_external']) ? 'target="_blank" rel="noopener noreferrer"' : '' ?> <?= $is_active_slide ? '' : 'tabindex="-1"' ?>><?= html_escape($slide['secondary_button_text']) ?></a>
									<?php endif; ?>
								</div>
								<?php endif; ?>
							</div>
						</div>
					</article>
					<?php endforeach; ?>
				</div>

				<?php if (count($hero_slides) > 1): ?>
				<button class="act-hero-nav act-hero-prev" type="button" aria-label="Slide hero sebelumnya">
					<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M15 18l-6-6 6-6"/></svg>
				</button>
				<button class="act-hero-nav act-hero-next" type="button" aria-label="Slide hero berikutnya">
					<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 6l6 6-6 6"/></svg>
				</button>
				<div class="act-hero-dots" role="tablist" aria-label="Pilih slide hero">
					<?php foreach ($hero_slides as $index => $slide): ?>
					<button type="button" class="<?= $index === 0 ? 'is-active' : '' ?>" aria-label="Tampilkan slide <?= $index + 1 ?>" aria-selected="<?= $index === 0 ? 'true' : 'false' ?>"></button>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>
			</section>
			<?php else: ?>
			<section id="home" class="act-hero">
				<div class="act-container act-hero-grid">
					<div class="act-hero-copy">
						<p class="act-eyebrow">GERAKAN KEMANUSIAAN ANTAR KOMUNITAS</p>
						<h1>Bergerak Cepat,<br>Menolong Lebih Dekat</h1>
						<p class="act-hero-text">Menghubungkan komunitas, relawan, dan masyarakat untuk merespons bencana serta kebutuhan kemanusiaan secara cepat dan tepat.</p>
						<div class="act-hero-actions">
							<a href="#donasi" class="act-btn act-btn-primary">Donasi Sekarang</a>
							<a href="#relawan" class="act-btn act-btn-outline">Daftar Relawan</a>
						</div>
						<div class="act-trust-list" aria-label="Nilai gerakan">
							<span class="act-trust-green">Gerak Cepat</span>
							<span>Transparan</span>
							<span class="act-trust-red">Kolaboratif</span>
						</div>
					</div>
					<div class="act-hero-media">
						<img src="<?= $hero_image ?>" alt="Aksi kemanusiaan Ayo Cepat Tolong">
					</div>
				</div>
			</section>
			<?php endif; ?>

			<section id="warta" class="act-section act-news-section">
				<div class="act-container">
					<div class="act-section-heading">
						<p class="act-eyebrow">WARTA</p>
						<h2>Warta Kemanusiaan</h2>
						<p>Informasi terbaru mengenai situasi darurat, kegiatan relawan, dan penyaluran bantuan.</p>
					</div>

					<?php if (!empty($info)): ?>
					<?php $accent_classes = ['act-news-card-accent-green', 'act-news-card-accent-orange', 'act-news-card-accent-red']; $acc_i = 0; ?>
					<div class="act-news-grid <?= count($info) === 1 ? 'act-news-grid-single' : '' ?>">
						<?php foreach($info AS $row): ?>
						<?php
							$news_image = act_news_image_url($row);
							$has_news_image = act_news_has_image_file($row);
							$excerpt = act_news_text_limit(isset($row->caption) ? $row->caption : '', 160);
							$detail_url = act_news_detail_url($row);
							$whatsapp_url = act_news_whatsapp_url($row, $excerpt);
							$date_label = !empty($row->created_at) ? date('d M Y', strtotime($row->created_at)) : 'Warta Lapangan';
							$card_accent = $accent_classes[$acc_i % 3]; $acc_i++;
							$badge_class = $acc_i % 3 === 0 ? 'act-badge-green' : ($acc_i % 3 === 2 ? 'act-badge-red' : '');
						?>
						<article class="act-news-card act-news-card-accent <?= $card_accent ?>">
							<div class="act-news-image <?= $has_news_image ? '' : 'act-news-placeholder' ?>">
								<img src="<?= $news_image ?>" alt="<?= html_escape($row->title) ?>">
								<?php if (!$has_news_image): ?>
								<div class="act-placeholder-mark" aria-hidden="true">
									<svg viewBox="0 0 64 64">
										<path d="M16 38c8-16 24-16 32 0"/>
										<path d="M20 24h24"/>
										<path d="M32 12v40"/>
										<path d="M14 48h36"/>
									</svg>
								</div>
								<?php endif; ?>
							</div>
							<div class="act-news-body">
								<div class="act-news-meta">
									<span class="act-badge <?= $badge_class ?>">Kabar Lapangan</span>
									<time><?= html_escape($date_label) ?></time>
								</div>
								<h3><?= html_escape($row->title) ?></h3>
								<p><?= html_escape($excerpt) ?></p>
								<div class="act-card-actions">
									<a href="<?= html_escape($detail_url) ?>" class="act-link">Baca Selengkapnya</a>
									<a href="<?= html_escape($whatsapp_url); ?>" class="act-share-link" target="_blank" rel="noopener noreferrer" aria-label="Bagikan <?= html_escape($row->title); ?> melalui WhatsApp">
										<?= act_whatsapp_icon(); ?>
										<span>Bagikan</span>
									</a>
								</div>
							</div>
						</article>
						<?php endforeach;?>
					</div>
					<?php else: ?>
					<div class="act-empty-state">
						<h3>Belum ada warta yang ditampilkan.</h3>
						<p>Informasi kegiatan terbaru akan muncul setelah data tersedia dari admin.</p>
					</div>
					<?php endif; ?>
				</div>
			</section>

			<section id="donasi" class="act-donation-cta">
				<div class="act-container act-donation-grid">
					<div>
						<p class="act-eyebrow">DONASI</p>
						<h2>Satu Bantuan, Satu Harapan</h2>
						<p>Bantuan Anda membantu kebutuhan darurat, pendidikan, pemulihan trauma, dan pendampingan masyarakat terdampak.</p>
					</div>
					<button onclick="document.getElementById('modalDonasi').classList.add('is-open')" class="act-btn act-btn-light" type="button">
						Salurkan Donasi
					</button>
				</div>
			</section>

			<section id="tentang" class="act-section act-steps-section">
				<div class="act-container">
					<div class="act-section-heading">
						<p class="act-eyebrow">CARA KAMI BERGERAK</p>
						<h2>Kerja kemanusiaan yang terverifikasi dan kolaboratif.</h2>
					</div>

					<div class="act-steps-grid">
						<article class="act-step-card">
							<div class="act-icon-box act-icon-green">
								<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M13 2L4 14h7l-1 8 10-13h-7l1-7z"/></svg>
							</div>
							<h3>Respons Cepat</h3>
							<p>Menerima informasi dan melakukan verifikasi kebutuhan lapangan.</p>
						</article>
						<article class="act-step-card">
							<div class="act-icon-box">
								<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M16 11c1.66 0 3-1.57 3-3.5S17.66 4 16 4s-3 1.57-3 3.5 1.34 3.5 3 3.5zM8 11c1.66 0 3-1.57 3-3.5S9.66 4 8 4 5 5.57 5 7.5 6.34 11 8 11zM2.5 20c.7-3.2 2.8-5 5.5-5s4.8 1.8 5.5 5M10.5 20c.7-3.2 2.8-5 5.5-5s4.8 1.8 5.5 5"/></svg>
							</div>
							<h3>Kolaborasi Komunitas</h3>
							<p>Menghubungkan relawan, lembaga, dan masyarakat yang ingin membantu.</p>
						</article>
						<article class="act-step-card">
							<div class="act-icon-box act-icon-red">
								<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 7L9 18l-5-5M4 19h16"/></svg>
							</div>
							<h3>Penyaluran Tepat</h3>
							<p>Menyalurkan bantuan berdasarkan kebutuhan yang telah diverifikasi.</p>
						</article>
					</div>
				</div>
			</section>

			<section id="relawan" class="act-section act-volunteer-section">
				<div class="act-container act-volunteer-grid">
					<div class="act-volunteer-copy">
						<p class="act-eyebrow">RELAWAN</p>
						<h2>Daftar menjadi relawan lapangan atau pendukung kegiatan.</h2>
						<p>Isi data singkat berikut agar tim dapat menghubungi Anda saat ada kebutuhan dukungan.</p>
					</div>
					<form id="registerForm" class="act-form">
						<div class="act-field">
							<label for="nama">Nama Lengkap</label>
							<input id="nama" name="nama" required type="text" autocomplete="name">
						</div>
						<div class="act-field">
							<label for="telp">Telepon</label>
							<input id="telp" name="telp" required type="text" autocomplete="tel">
						</div>
						<div class="act-field">
							<label for="alamat">Alamat</label>
							<textarea id="alamat" name="alamat" required rows="4"></textarea>
						</div>
						<button class="act-btn act-btn-primary act-btn-full" type="submit">Daftar Sekarang</button>
						<div id="result" class="act-form-result" aria-live="polite"></div>
					</form>
				</div>
			</section>
		</main>

		<div id="modalDonasi" class="act-modal" role="dialog" aria-modal="true" aria-labelledby="donasiTitle">
			<div class="act-modal-panel">
				<button onclick="document.getElementById('modalDonasi').classList.remove('is-open')" class="act-modal-close" type="button" aria-label="Tutup modal">
					<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"/></svg>
				</button>
				<h3 id="donasiTitle">Rekening Donasi</h3>
				<div class="act-bank-box">
					<span>BCA</span>
					<strong>245 258 380 2</strong>
					<p>a.n Yayasan Harapan Dhuafa Banten</p>
				</div>
				<button onclick="document.getElementById('modalDonasi').classList.remove('is-open')" class="act-btn act-btn-primary act-btn-full" type="button">Tutup</button>
			</div>
		</div>

		<footer class="act-footer">
			<div class="act-container act-footer-grid">
				<div class="act-footer-brand">
					<img src="<?= base_url($logo_path) ?>" alt="<?= html_escape($app_name) ?>">
					<p><?= html_escape($site_desc) ?></p>
				</div>
				<div class="act-footer-links">
					<span class="act-footer-accent"></span>
					<h3>Menu Cepat</h3>
					<a href="#home">Beranda</a>
					<a href="#warta">Warta</a>
					<a href="#donasi">Donasi</a>
					<a href="#relawan">Relawan</a>
					<a href="#tentang">Tentang Kami</a>
				</div>
				<div class="act-footer-contact">
					<h3>Kontak</h3>
					<?php if (!empty($wa_number)): ?>
					<a href="https://wa.me/<?= html_escape($wa_number) ?>" target="_blank" rel="noopener">WhatsApp <?= html_escape($settings->wa_number) ?></a>
					<?php else: ?>
					<p>WhatsApp belum tersedia.</p>
					<?php endif; ?>
					<p>ayocepattolong.info</p>
				</div>
			</div>
			<div class="act-container act-footer-bottom">
				<p>&copy; <?= date('Y') ?> <?= html_escape($app_name) ?>. Semua hak dilindungi.</p>
			</div>
		</footer>

		<script src="https://www.google.com/recaptcha/api.js?render=6Ldc4SYsAAAAAACOcAaMF4s5XiCEFtQJThOJ_X7_"></script>
		<script>
			(function(){
				const toggle = document.querySelector('.act-menu-toggle');
				const mobileMenu = document.getElementById('actMobileMenu');

				if (toggle && mobileMenu) {
					toggle.addEventListener('click', function() {
						const isOpen = mobileMenu.classList.toggle('is-open');
						toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
					});

					mobileMenu.querySelectorAll('a').forEach(function(link) {
						link.addEventListener('click', function() {
							mobileMenu.classList.remove('is-open');
							toggle.setAttribute('aria-expanded', 'false');
						});
					});
				}

				const heroSlider = document.getElementById('actHeroSlider');
				if (heroSlider && heroSlider.children.length > 1) {
					const slides = Array.prototype.slice.call(heroSlider.children);
					const slidesCount = slides.length;
					let currentIndex = 0;
					let timer = null;
					let isPaused = false;
					let touchStartX = 0;
					const heroSection = document.querySelector('.act-hero-slider');
					const prevBtn = document.querySelector('.act-hero-prev');
					const nextBtn = document.querySelector('.act-hero-next');
					const dots = Array.prototype.slice.call(document.querySelectorAll('.act-hero-dots button'));
					const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

					function setSlide(index) {
						currentIndex = (index + slidesCount) % slidesCount;
						slides.forEach(function(slide, slideIndex) {
							const isActive = slideIndex === currentIndex;
							slide.classList.toggle('is-active', isActive);
							slide.setAttribute('aria-hidden', isActive ? 'false' : 'true');
							slide.querySelectorAll('a, button, input, textarea, select').forEach(function(focusable) {
								focusable.tabIndex = isActive ? 0 : -1;
							});
						});

						dots.forEach(function(dot, dotIndex) {
							const isActive = dotIndex === currentIndex;
							dot.classList.toggle('is-active', isActive);
							dot.setAttribute('aria-selected', isActive ? 'true' : 'false');
						});
					}

					function nextSlide() {
						setSlide(currentIndex + 1);
					}

					function startAutoplay() {
						if (reducedMotion || isPaused || timer) {
							return;
						}
						timer = setInterval(nextSlide, 6000);
					}

					function stopAutoplay() {
						if (timer) {
							clearInterval(timer);
							timer = null;
						}
					}

					prevBtn.addEventListener('click', function() {
						stopAutoplay();
						setSlide(currentIndex - 1);
						startAutoplay();
					});

					nextBtn.addEventListener('click', function() {
						stopAutoplay();
						nextSlide();
						startAutoplay();
					});

					dots.forEach(function(dot, index) {
						dot.addEventListener('click', function() {
							stopAutoplay();
							setSlide(index);
							startAutoplay();
						});
					});

					heroSection.addEventListener('mouseenter', function() {
						isPaused = true;
						stopAutoplay();
					});

					heroSection.addEventListener('mouseleave', function() {
						isPaused = false;
						startAutoplay();
					});

					heroSection.addEventListener('focusin', function() {
						isPaused = true;
						stopAutoplay();
					});

					heroSection.addEventListener('focusout', function(event) {
						if (!heroSection.contains(event.relatedTarget)) {
							isPaused = false;
							startAutoplay();
						}
					});

					heroSection.addEventListener('touchstart', function(event) {
						touchStartX = event.touches[0].clientX;
					}, {passive: true});

					heroSection.addEventListener('touchend', function(event) {
						const diff = touchStartX - event.changedTouches[0].clientX;
						if (Math.abs(diff) > 40) {
							stopAutoplay();
							setSlide(currentIndex + (diff > 0 ? 1 : -1));
							startAutoplay();
						}
					}, {passive: true});

					document.addEventListener('visibilitychange', function() {
						if (document.hidden) {
							stopAutoplay();
						} else {
							startAutoplay();
						}
					});

					setSlide(0);
					startAutoplay();
				}

				const modal = document.getElementById('modalDonasi');
				if (modal) {
					modal.addEventListener('click', function(event) {
						if (event.target === modal) {
							modal.classList.remove('is-open');
						}
					});
				}
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

						fetch("<?= base_url('api/register') ?>", {
							method: "POST",
							headers: {
								"Content-Type": "application/json",
								"X-API-KEY": "RAddaadad_12345"
							},
							body: JSON.stringify(payload)
						})
						.then(res => res.json())
						.then(res => {
							if (res.status === "success") {
								document.getElementById("registerForm").reset();
								document.getElementById("result").innerHTML = '<span class="act-success">' + res.message + '</span>';
							} else {
								document.getElementById("result").innerHTML = '<span class="act-error">' + (res.error || res.message) + '</span>';
							}
						})
						.catch(err => {
							document.getElementById("result").innerHTML = '<span class="act-error">Error: ' + err + '</span>';
						});
					});
				});
			});
		</script>
	</body>
</html>
