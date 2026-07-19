<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	if (!function_exists('act_news_text_limit')) {
		function act_news_text_limit($text, $limit = 160)
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

	if (!function_exists('act_news_slug_from_title')) {
		function act_news_slug_from_title($title)
		{
			$CI =& get_instance();
			$CI->load->helper(['url', 'text']);
			$slug = url_title(convert_accented_characters((string) $title), 'dash', TRUE);
			return $slug !== '' ? $slug : 'warta-kemanusiaan';
		}
	}

	if (!function_exists('act_news_has_clean_slug')) {
		function act_news_has_clean_slug($slug)
		{
			return is_string($slug) && preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug);
		}
	}

	if (!function_exists('act_news_detail_url')) {
		function act_news_detail_url($news)
		{
			$slug = isset($news->slug) ? trim((string) $news->slug) : '';

			if (act_news_has_clean_slug($slug)) {
				return site_url('news/' . $slug);
			}

			$id = isset($news->id) ? (int) $news->id : 0;
			$source_slug = $slug !== '' ? $slug : (isset($news->title) ? $news->title : '');
			return site_url('news/' . $id . '/' . act_news_slug_from_title($source_slug));
		}
	}

	if (!function_exists('act_news_image_url')) {
		function act_news_image_url($news)
		{
			$default_image = is_file(FCPATH . 'uploads/lautan-kayu-di-aceh-tamiang.webp')
				? base_url('uploads/lautan-kayu-di-aceh-tamiang.webp')
				: base_url('assets/img/act_logo.png');
			$image = isset($news->image) ? trim((string) $news->image) : '';

			if ($image === '') {
				return $default_image;
			}

			if (preg_match('#^https?://#i', $image)) {
				return $image;
			}

			$filename = basename($image);
			$candidates = [
				'uploads/news/' . $filename,
				'uploads/informasi/' . $filename,
				'uploads/' . $filename,
			];

			foreach ($candidates as $relative_path) {
				if (is_file(FCPATH . $relative_path)) {
					return base_url($relative_path);
				}
			}

			return $default_image;
		}
	}

	if (!function_exists('act_news_has_image_file')) {
		function act_news_has_image_file($news)
		{
			$image = isset($news->image) ? trim((string) $news->image) : '';

			if ($image === '') {
				return FALSE;
			}

			if (preg_match('#^https?://#i', $image)) {
				return TRUE;
			}

			$filename = basename($image);
			foreach (['uploads/news/', 'uploads/informasi/', 'uploads/'] as $folder) {
				if (is_file(FCPATH . $folder . $filename)) {
					return TRUE;
				}
			}

			return FALSE;
		}
	}

	if (!function_exists('act_news_whatsapp_url')) {
		function act_news_whatsapp_url($news, $excerpt = null)
		{
			$detail_url = act_news_detail_url($news);
			$excerpt = $excerpt !== null ? $excerpt : act_news_text_limit(isset($news->caption) ? $news->caption : '', 140);
			$title = isset($news->title) ? $news->title : 'Warta Kemanusiaan';
			$share_text = $title . "\n\n"
				. $excerpt . "\n\n"
				. "Baca selengkapnya:\n"
				. $detail_url;

			return 'https://wa.me/?text=' . rawurlencode($share_text);
		}
	}

	if (!function_exists('act_news_image_meta')) {
		function act_news_image_meta($news)
		{
			$meta = ['url' => '', 'width' => 0, 'height' => 0, 'type' => '', 'exists' => false];

			if (!isset($news->image) || trim((string) $news->image) === '') {
				return $meta;
			}

			$filename = basename(trim((string) $news->image));
			$paths = [
				'uploads/news/' . $filename,
				'uploads/informasi/' . $filename,
				'uploads/' . $filename,
			];

			foreach ($paths as $relative) {
				$full = FCPATH . $relative;
				if (is_file($full)) {
					$info = @getimagesize($full);
					if ($info !== false) {
						$meta['url'] = base_url($relative);
						$meta['width'] = $info[0];
						$meta['height'] = $info[1];
						$meta['type'] = $info['mime'];
						$meta['exists'] = true;
					}
					break;
				}
			}

			return $meta;
		}
	}

	if (!function_exists('act_whatsapp_icon')) {
		function act_whatsapp_icon()
		{
			return '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12.04 3.5a8.45 8.45 0 0 0-7.18 12.92L4 20.5l4.18-.82A8.45 8.45 0 1 0 12.04 3.5z"/><path d="M9.4 8.25c-.18-.43-.36-.44-.53-.45h-.46c-.16 0-.42.06-.64.3-.22.24-.84.82-.84 2s.86 2.32.98 2.48c.12.16 1.66 2.66 4.12 3.62 2.04.8 2.46.64 2.9.6.44-.04 1.42-.58 1.62-1.14.2-.56.2-1.04.14-1.14-.06-.1-.22-.16-.46-.28-.24-.12-1.42-.7-1.64-.78-.22-.08-.38-.12-.54.12-.16.24-.62.78-.76.94-.14.16-.28.18-.52.06-.24-.12-1.02-.38-1.94-1.2-.72-.64-1.2-1.44-1.34-1.68-.14-.24-.02-.37.1-.49.11-.1.24-.28.36-.42.12-.14.16-.24.24-.4.08-.16.04-.3-.02-.42-.06-.12-.53-1.3-.76-1.74z"/></svg>';
		}
	}
