<?php
class ControllerCatalogGeneralbanner extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/generalbanner');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/generalbanner');

		$this->getList();
	}

	public function add() {
		$this->load->language('catalog/generalbanner');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/generalbanner');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_generalbanner->addNews($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/generalbanner', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('catalog/generalbanner');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/generalbanner');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_generalbanner->editNews($this->request->get['gbanner_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/generalbanner', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/generalbanner');

		$this->document->setTitle($this->language->get('heading_title'));
 
		$this->load->model('catalog/generalbanner');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $gbanner_id) {
				$this->model_catalog_generalbanner->deleteNews($gbanner_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/generalbanner', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort']; 
		} else {
			$sort = 'id.title';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/generalbanner', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		$data['add'] = $this->url->link('catalog/generalbanner/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['delete'] = $this->url->link('catalog/generalbanner/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);

		$data['news'] = array();

		$filter_data = array(
			'sort' => $sort,
			'order' => $order, 
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$news_total = $this->model_catalog_generalbanner->getTotalNews();

		$results = $this->model_catalog_generalbanner->getNews($filter_data);

		foreach ($results as $result) {
			$data['news'][] = array(
				'gbanner_id' => $result['gbanner_id'],
				'title' => $result['title'],
				'sort_order' => $result['sort_order'],
				'edit' => $this->url->link('catalog/generalbanner/edit', 'user_token=' . $this->session->data['user_token'] . '&gbanner_id=' . $result['gbanner_id'] . $url, true)
			);
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array) $this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_title'] = $this->url->link('catalog/generalbanner', 'user_token=' . $this->session->data['user_token'] . '&sort=id.title' . $url, true);
		$data['sort_sort_order'] = $this->url->link('catalog/generalbanner', 'user_token=' . $this->session->data['user_token'] . '&sort=i.sort_order' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $news_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/generalbanner', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($news_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($news_total - $this->config->get('config_limit_admin'))) ? $news_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $news_total, ceil($news_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/generalbanner_list', $data));
	}

	protected function getForm() {
		$data['text_form'] = !isset($this->request->get['gbanner_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['title'])) {
			$data['error_title'] = $this->error['title'];
		} else {
			$data['error_title'] = array();
		}

		if (isset($this->error['description'])) {
			$data['error_description'] = $this->error['description'];
		} else {
			$data['error_description'] = array();
		}

		/*if (isset($this->error['meta_title'])) {
			$data['error_meta_title'] = $this->error['meta_title'];
		} else {
			$data['error_meta_title'] = array();
		}*/

		if (isset($this->error['keyword'])) {
			$data['error_keyword'] = $this->error['keyword'];
		} else {
			$data['error_keyword'] = '';
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/generalbanner', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		if (!isset($this->request->get['gbanner_id'])) {
			$data['action'] = $this->url->link('catalog/generalbanner/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('catalog/generalbanner/edit', 'user_token=' . $this->session->data['user_token'] . '&gbanner_id=' . $this->request->get['gbanner_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('catalog/generalbanner', 'user_token=' . $this->session->data['user_token'] . $url, true);

		if (isset($this->request->get['gbanner_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$news_info = $this->model_catalog_generalbanner->getNewsItem($this->request->get['gbanner_id']);
		}

		$data['user_token'] = $this->session->data['user_token'];

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['news_description'])) {
			$data['news_description'] = $this->request->post['news_description'];
		} elseif (isset($this->request->get['gbanner_id'])) {
			$data['news_description'] = $this->model_catalog_generalbanner->getNewsDescriptions($this->request->get['gbanner_id']);
		} else {
			$data['news_description'] = array();
		}

		$this->load->model('setting/store');

		$data['stores'] = array();

		$data['stores'][] = array(
			'store_id' => 0,
			'name' => $this->language->get('text_default')
		);

		$stores = $this->model_setting_store->getStores();

		foreach ($stores as $store) {
			$data['stores'][] = array(
				'store_id' => $store['store_id'],
				'name' => $store['name']
			);
		}

		if (isset($this->request->post['news_store'])) {
			$data['news_store'] = $this->request->post['news_store'];
		} elseif (isset($this->request->get['gbanner_id'])) {
			$data['news_store'] = $this->model_catalog_generalbanner->getNewsStores($this->request->get['gbanner_id']);
		} else {
			$data['news_store'] = array(
				0);
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($news_info)) {
			$data['status'] = $news_info['status'];
		} else {
			$data['status'] = true;
		}

		if (isset($this->request->post['date_start'])) {
			$data['date_start'] = $this->request->post['date_start'];
		} elseif (!empty($news_info)) {
			$data['date_start'] = ($news_info['date_start'] != '0000-00-00 00:00:00' ? $news_info['date_start'] : '');
		} else {
			$data['date_start'] = date('Y-m-d H:i', time());
		}


		if (isset($this->request->post['image'])) {
			$data['image'] = $this->request->post['image'];
		} elseif (!empty($news_info)) {
			$data['image'] = $news_info['image'];
		} else {
			$data['image'] = '';
		}

		if (isset($this->request->post['image_adaptive'])) {
			$data['image_adaptive'] = $this->request->post['image_adaptive'];
		} elseif (!empty($news_info)) {
			$data['image_adaptive'] = $news_info['image_adaptive'];
		} else {
			$data['image_adaptive'] = '';
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($news_info) && is_file(DIR_IMAGE . $news_info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($news_info['image'], 100, 100);
		} else { 
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		if (isset($this->request->post['image_adaptive']) && is_file(DIR_IMAGE . $this->request->post['image_adaptive'])) {
			$data['thumb_adaptive'] = $this->model_tool_image->resize($this->request->post['image_adaptive'], 100, 100);
		} elseif (!empty($news_info) && is_file(DIR_IMAGE . $news_info['image_adaptive'])) {
			$data['thumb_adaptive'] = $this->model_tool_image->resize($news_info['image_adaptive'], 100, 100);
		} else {
			$data['thumb_adaptive'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($news_info)) {
			$data['sort_order'] = $news_info['sort_order'];
		} else {
			$data['sort_order'] = '';
		}

		if (isset($this->request->post['gbanner_seo_url'])) {
			$data['gbanner_seo_url'] = $this->request->post['gbanner_seo_url'];
		} elseif (isset($this->request->get['gbanner_id'])) {
			$data['gbanner_seo_url'] = $this->model_catalog_generalbanner->getNewsSeoUrls($this->request->get['gbanner_id']);
		} else {
			$data['gbanner_seo_url'] = array();
		}

		if (isset($this->request->post['news_layout'])) {
			$data['news_layout'] = $this->request->post['news_layout'];
		} elseif (isset($this->request->get['gbanner_id'])) {
			$data['news_layout'] = $this->model_catalog_generalbanner->getNewsLayouts($this->request->get['gbanner_id']);
		} else {
			$data['news_layout'] = array();
		}

		$this->load->model('design/layout');

		$data['layouts'] = $this->model_design_layout->getLayouts();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/generalbanner_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/generalbanner')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['news_description'] as $language_id => $value) {
			if ((utf8_strlen($value['title']) < 1) || (utf8_strlen($value['title']) > 255)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
			}

			if (utf8_strlen($value['description']) < 3) {
				$this->error['description'][$language_id] = $this->language->get('error_description');
			}


			/*if ((utf8_strlen($value['meta_title']) < 1) || (utf8_strlen($value['meta_title']) > 255)) {
				$this->error['meta_title'][$language_id] = $this->language->get('error_meta_title');
			}*/
		}


		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/generalbanner')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

}
