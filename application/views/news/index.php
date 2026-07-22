<?php
	$app_name = !empty($settings->app_name) ? $settings->app_name : 'Ayo Cepat Tolong';
	$logo_file = !empty($settings->app_logo) ? basename($settings->app_logo) : '';
	$icon_file = !empty($settings->app_icon) ? basename($settings->app_icon) : '';
	$logo_path = ($logo_file && is_file(FCPATH . 'uploads' . DIRECTORY_SEPARATOR . $logo_file)) ? 'uploads/' . $logo_file : 'uploads/act_logo.png';
	$icon_path = ($icon_file && is_file(FCPATH . 'uploads' . DIRECTORY_SEPARATOR . $icon_file)) ? 'uploads/' . $icon_file : 'uploads/icon.png';
?>
<!DOCTYPE html>
<html lang="id">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Warta Kemanusiaan | <?= html_escape($app_name); ?></title>
		<meta name="description" content="Informasi terbaru mengenai situasi darurat, kegiatan relawan, dan penyaluran bantuan.">
		<link rel="icon" href="<?= base_url($icon_path) ?>">
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
		<link rel="stylesheet" href="<?= base_url('assets/style.css') ?>">
	</head>
	<body class="act-page">
		<header class="act-header">
			<div class="act-container act-nav">
				<a href="<?= base_url(); ?>#home" class="act-brand" aria-label="Beranda Ayo Cepat Tolong">
					<img src="<?= base_url($logo_path) ?>" alt="<?= html_escape($app_name) ?>">
				</a>
				<nav class="act-menu" aria-label="Navigasi utama">
					<a href="<?= base_url(); ?>#home">Beranda</a>
					<a href="<?= site_url('news'); ?>">Warta</a>
					<a href="<?= base_url(); ?>#donasi">Donasi</a>
					<a href="<?= base_url(); ?>#relawan">Relawan</a>
					<a href="<?= base_url(); ?>#tentang">Tentang Kami</a>
				</nav>
				<div class="act-nav-actions">
					<a href="<?= base_url(); ?>#donasi" class="act-btn act-btn-primary act-btn-compact">Donasi Sekarang</a>
				</div>
			</div>
		</header>

		<main class="act-news-list-page">
			<section class="act-section act-news-section">
				<div class="act-container">
					<div class="act-section-heading">
						<p class="act-eyebrow">WARTA</p>
						<h1>Warta Kemanusiaan</h1>
						<p>Informasi terbaru mengenai situasi darurat, kegiatan relawan, dan penyaluran bantuan.</p>
					</div>

					<?php $accent_classes = ['act-news-card-accent-green', 'act-news-card-accent-orange', 'act-news-card-accent-red']; $i = 0; ?>
					<?php if (!empty($news_items)): ?>
					<div class="act-news-grid">
						<?php foreach ($news_items as $item): ?>
						<?php
							$item_image = act_news_image_url($item);
							$item_has_image = act_news_has_image_file($item);
							$item_excerpt = act_news_text_limit($item->caption, 160);
							$item_detail_url = act_news_detail_url($item);
							$item_whatsapp_url = act_news_whatsapp_url($item, $item_excerpt);
							$card_accent = $accent_classes[$i % 3];
							$badge_class = $i % 3 === 0 ? 'act-badge-green' : ($i % 3 === 2 ? 'act-badge-red' : '');
							$i++;
						?>
						<article class="act-news-card act-news-card-accent <?= $card_accent ?>">
							<div class="act-news-image <?= $item_has_image ? '' : 'act-news-placeholder' ?>">
								<img src="<?= html_escape($item_image); ?>" alt="<?= html_escape($item->title); ?>">
								<?php if (!$item_has_image): ?>
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
									<time><?= !empty($item->created_at) ? date('d M Y', strtotime($item->created_at)) : 'Warta Lapangan'; ?></time>
								</div>
								<h3><?= html_escape($item->title); ?></h3>
								<p><?= html_escape($item_excerpt); ?></p>
								<div class="act-card-actions">
									<a href="<?= html_escape($item_detail_url); ?>" class="act-link">Baca Selengkapnya</a>
									<a href="<?= html_escape($item_whatsapp_url); ?>" class="act-share-link" target="_blank" rel="noopener noreferrer" aria-label="Bagikan <?= html_escape($item->title); ?> melalui WhatsApp">
										<?= act_whatsapp_icon(); ?>
										<span>Bagikan</span>
									</a>
								</div>
							</div>
						</article>
						<?php endforeach; ?>
					</div>
					<?php else: ?>
					<div class="act-empty-state">
						<h2>Belum ada warta yang ditampilkan.</h2>
						<p>Informasi kegiatan terbaru akan muncul setelah data tersedia dari admin.</p>
					</div>
					<?php endif; ?>
				</div>
			</section>
		</main>

		<footer class="act-footer">
			<div class="act-container act-footer-grid">
				<div class="act-footer-brand">
					<img src="<?= base_url($logo_path) ?>" alt="<?= html_escape($app_name) ?>">
					<p>Gerakan kemanusiaan antar komunitas yang diinisiasi oleh LAMTREN.</p>
				</div>
				<div class="act-footer-links">
					<span class="act-footer-accent"></span>
					<h3>Menu Cepat</h3>
					<a href="<?= base_url(); ?>#home">Beranda</a>
					<a href="<?= site_url('news'); ?>">Warta</a>
					<a href="<?= base_url(); ?>#donasi">Donasi</a>
					<a href="<?= base_url(); ?>#relawan">Relawan</a>
					<a href="<?= base_url(); ?>#tentang">Tentang Kami</a>
				</div>
				<div class="act-footer-contact">
					<h3>Kontak</h3>
					<p>ayocepattolong.info</p>
				</div>
			</div>
			<div class="act-container act-footer-bottom">
				<p>&copy; <?= date('Y') ?> <?= html_escape($app_name) ?>. Semua hak dilindungi.</p>
			</div>
		</footer>
	</body>
</html>
