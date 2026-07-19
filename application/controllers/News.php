<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class News extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->helper(['url', 'news']);
			$this->load->model('Informasi_model');
			$this->load->model('Settings_model');
		}

		public function index()
		{
			$data['settings'] = $this->Settings_model->get();
			$data['news_items'] = $this->Informasi_model->get_published();
			$this->load->view('news/index', $data);
		}

		public function detail($slug_or_id = null, $generated_slug = null)
		{
			$news = null;

			if ($slug_or_id !== null && ctype_digit((string) $slug_or_id)) {
				$news = $this->Informasi_model->get_published_by_id((int) $slug_or_id);
			} elseif ($slug_or_id !== null) {
				$news = $this->Informasi_model->get_published_by_slug(rawurldecode($slug_or_id));
			}

			if (empty($news)) {
				show_404();
				return;
			}

			$data['settings'] = $this->Settings_model->get();
			$data['news'] = $news;
			$data['related_news'] = $this->Informasi_model->get_related($news->id, 3);
			$this->load->view('news/detail', $data);
		}
	}
