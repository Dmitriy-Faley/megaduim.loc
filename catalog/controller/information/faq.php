<?php
class ControllerInformationFaq extends Controller {

	public function index() {
		$this->load->language('information/faq');

		$data['breadcrumbs'] = array(); 

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home'),
			'separator' => false
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('information/faq'),
			'separator' => $this->language->get('text_separator')
		);

		$this->load->model('catalog/faq');

		$data['faqs'] = array();

		$faqs = $this->model_catalog_faq->getFaqs();

		if ($faqs) {
			$this->document->setTitle($this->language->get('heading_title'));

			foreach ($faqs as $result) {
				$data['faqs'][] = array(
					'title' => $result['title'],
					'description' => html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')
				);
			}

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('information/faq_info', $data));
			//$this->response->setOutput($this->load->view('information/information', $data));
		} else {
			$this->document->setTitle($this->language->get('text_error'));

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['header'] = $this->load->controller('common/header');
			$data['footer'] = $this->load->controller('common/footer');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');

			$this->response->setOutput($this->load->view('information/faq_info', $data));
		}
	}

}
