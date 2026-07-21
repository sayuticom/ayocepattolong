<?php
	$app_name = !empty($settings->app_name) ? $settings->app_name : 'Ayo Cepat Tolong';
	$logo_path = (!empty($settings->app_logo) && is_file(FCPATH . $settings->app_logo)) ? $settings->app_logo : 'assets/img/act_logo.png';
	$icon_path = (!empty($settings->app_icon) && is_file(FCPATH . $settings->app_icon)) ? $settings->app_icon : 'assets/img/favicon.png';
	$meta_description = act_news_text_limit($news->caption, 155);
	$canonical_url = act_news_detail_url($news);
	$meta_image_url = act_news_image_url($news);
	$has_news_image = act_news_has_image_file($news);
	$published_iso = !empty($news->created_at) ? date('c', strtotime($news->created_at)) : '';
	$modified_iso = !empty($news->updated_at) ? date('c', strtotime($news->updated_at)) : $published_iso;
	$published_label = !empty($news->created_at) ? date('d M Y', strtotime($news->created_at)) : 'Warta Lapangan';
	$whatsapp_url = act_news_whatsapp_url($news, act_news_text_limit($news->caption, 140));
?>
<!DOCTYPE html>
<html lang="id">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title><?= html_escape($news->title); ?> | <?= html_escape($app_name); ?></title>
		<meta name="description" content="<?= html_escape($meta_description); ?>">
		<link rel="canonical" href="<?= html_escape($canonical_url); ?>">
		<link rel="icon" href="<?= base_url($icon_path) ?>">
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
		<link rel="stylesheet" href="<?= base_url('assets/style.css') ?>">

		<meta property="og:type" content="article">
		<meta property="og:site_name" content="<?= html_escape($app_name); ?>">
		<meta property="og:title" content="<?= html_escape($news->title); ?>">
		<meta property="og:description" content="<?= html_escape($meta_description); ?>">
		<meta property="og:url" content="<?= html_escape($canonical_url); ?>">
		<meta property="og:image" content="<?= html_escape($meta_image_url); ?>">
		<meta property="og:image:secure_url" content="<?= html_escape($meta_image_url); ?>">
		<meta property="og:image:alt" content="<?= html_escape($news->title); ?>">
		<?php
			$img_meta = act_news_image_meta($news);
			if ($img_meta['exists'] && $img_meta['width'] > 0):
		?>
		<meta property="og:image:width" content="<?= (int) $img_meta['width']; ?>">
		<meta property="og:image:height" content="<?= (int) $img_meta['height']; ?>">
		<meta property="og:image:type" content="<?= html_escape($img_meta['type']); ?>">
		<?php endif; ?>
		<?php if (!empty($published_iso)): ?>
		<meta property="article:published_time" content="<?= html_escape($published_iso); ?>">
		<meta property="article:modified_time" content="<?= html_escape($modified_iso); ?>">
		<?php endif; ?>

		<meta name="twitter:card" content="summary_large_image">
		<meta name="twitter:title" content="<?= html_escape($news->title); ?>">
		<meta name="twitter:description" content="<?= html_escape($meta_description); ?>">
		<meta name="twitter:image" content="<?= html_escape($meta_image_url); ?>">
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

		<main>
			<article class="act-news-detail">
				<div class="act-container act-detail-container">
					<a href="<?= base_url(); ?>#warta" class="act-back-link">Kembali ke Warta Kemanusiaan</a>
					<div class="act-detail-meta">
						<span class="act-badge act-badge-green">Kabar Lapangan</span>
						<time datetime="<?= html_escape($published_iso); ?>"><?= html_escape($published_label); ?></time>
					</div>
					<h1><?= html_escape($news->title); ?></h1>
					<div class="act-detail-actions">
						<a href="<?= html_escape($whatsapp_url); ?>" class="act-share-button" target="_blank" rel="noopener noreferrer" aria-label="Bagikan <?= html_escape($news->title); ?> melalui WhatsApp">
							<?= act_whatsapp_icon(); ?>
							<span>Bagikan via WhatsApp</span>
						</a>
					</div>
				</div>

			<div class="act-container">
				<div class="act-detail-hero">
					<figure class="act-detail-image <?= $has_news_image ? '' : 'act-detail-image-fallback'; ?>">
						<img src="<?= html_escape($meta_image_url); ?>" alt="<?= html_escape($news->title); ?>">
					</figure>
				</div>
			</div>

				<div class="act-container act-detail-content">
					<?= nl2br(html_escape($news->caption)); ?>
				</div>
			</article>

			<?php if (!empty($related_news)): ?>
			<section class="act-section act-news-section">
				<div class="act-container">
					<div class="act-section-heading">
						<p class="act-eyebrow">WARTA TERKAIT</p>
						<h2>Berita Terkait</h2>
					</div>
					<?php $accent_classes = ['act-news-card-accent-green', 'act-news-card-accent-orange', 'act-news-card-accent-red']; $i = 0; ?>
					<div class="act-news-grid">
						<?php foreach ($related_news as $item): ?>
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
				</div>
			</section>
			<?php endif; ?>
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
