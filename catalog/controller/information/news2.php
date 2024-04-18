<?php
class ControllerInformationNews2 extends Controller {

	public function index() {
		$this->load->language('information/news2');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home'),
			'separator' => false
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('information/news2'),
			'separator' => $this->language->get('text_separator')
		);

		$this->load->model('catalog/news2');

		$data['news'] = array();
		
		$news = $this->model_catalog_news2->getNews();

		if ($news) {
			$this->document->setTitle($this->language->get('heading_title'));

			$this->load->model('tool/image');

			foreach ($news as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], null, null);
				} else {
					$image = $this->model_tool_image->resize('no_image.png', 767, 767);
				}

				if ($result['image_adaptive']) {
					$imageAdaptive = $this->model_tool_image->resize($result['image_adaptive'], null, null);
				} else {
					$imageAdaptive = $this->model_tool_image->resize('no_image.png', 767, 767);
				}

				if ($result['image_inner']) {
					$imageInner = $this->model_tool_image->resize($result['image_inner'], null, null);
				} else {
					$imageInner = $this->model_tool_image->resize('no_image.png', 767, 767);
				}

				if ($result['image_inner_adaptive']) {
					$imageInnerAdaptive = $this->model_tool_image->resize($result['image_inner_adaptive'], null, null);
				} else {
					$imageInnerAdaptive = $this->model_tool_image->resize('no_image.png', 767, 767);
				}
				
				$data['news'][] = array(
					'thumb' => $image,
					'thumb_adaptive' => $imageAdaptive, 
					'thumb_inner' => $imageInner,
					'thumb_inner_adaptive' => $imageInnerAdaptive,
					'name' => $result['title'],
					'intro' => html_entity_decode($result['intro'], ENT_QUOTES, 'UTF-8'),
					'href' => $this->url->link('information/news2/info', 'news2_id=' . $result['news2_id']),
					'date' => date($this->language->get('date_format_short'), strtotime($result['date_start']))
				);
			}

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('information/news_list2', $data));
		} else {
			$this->document->setTitle($this->language->get('text_error'));

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['header'] = $this->load->controller('common/header');
			$data['footer'] = $this->load->controller('common/footer');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');

			$this->response->setOutput($this->load->view('information/news_list2', $data));
		}
	}

	public function info() {
		$this->load->language('information/news2');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home'),
			'separator' => false
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('information/news2'),
			'separator' => $this->language->get('text_separator')
		);

		if (isset($this->request->get['news2_id'])) {
			$news2_id = (int) $this->request->get['news2_id'];
		} else {
			$news2_id = 0;
		}

		$this->load->model('catalog/news2');

		$data['news'] = array();

		$this->load->model('tool/image');
		$news = $this->model_catalog_news2->getNewsItem($news2_id);
		

		if ($news) {
			$this->document->setTitle($news['meta_title']);
			$this->document->setDescription($news['meta_description']);
			$this->document->setKeywords($news['meta_keyword']);

			$data['breadcrumbs'][] = array(
				'text' => $news['title'],
				'href' => $this->url->link('information/news2/info', 'news2_id=' . $news2_id),
				'separator' => $this->language->get('text_separator')
			);
			
			$data['heading_title'] = $news['title'];

			$data['date'] = date($this->language->get('date_format_short'), strtotime($news['date_start']));
			$data['description'] = html_entity_decode($news['description'], ENT_QUOTES, 'UTF-8');


			$data['image'] = $news['image'];
			$data['image_adaptive'] = $news['image_adaptive'];
			$data['image_inner'] = $news['image_inner'];
			$data['image_inner_adaptive'] = $news['image_inner_adaptive'];



			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('information/news_info2', $data));
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_info_error'),
				'href' => $this->url->link('information/news2/info', 'news2_id=' . $news2_id),
				'separator' => $this->language->get('text_separator')
			);

			$this->document->setTitle($this->language->get('text_info_error'));

			$data['heading_title'] = $this->language->get('text_info_error');

			$data['text_error'] = $this->language->get('text_info_error');

			$data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('error/not_found', $data));
		}
			}

}
