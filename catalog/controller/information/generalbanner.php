<?php
class ControllerCatalogGeneralbanner extends Controller {

	public function index() {
		$this->load->language('catalog/generalbanner');

		$this->load->model('catalog/generalbanner');
		$data['generalBanner'] = array();
 
		$gbanner = $this->model_catalog_generalbanner->getNews();


		if ($gbanner) {
			$this->document->setTitle($this->language->get('heading_title'));

			$this->load->model('tool/image');

			foreach ($gbanner as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], 767, 767);
				} else {
					$image = $this->model_tool_image->resize('no_image.png', 767, 767);
				}

				if ($result['image_adaptive']) {
					$imageAdaptive = $this->model_tool_image->resize($result['image_adaptive'], 767, 767);
				} else {
					$imageAdaptive = $this->model_tool_image->resize('no_image.png', 767, 767);
				}
				
				$data['generalBanner'][] = array(
					'thumb' => $image,
					'thumb_adaptive' => $imageAdaptive, 
					'name' => $result['title'],
					//'href' => $this->url->link('information/generalbanner/info', 'news2_id=' . $result['news2_id']),
					//'date' => date($this->language->get('date_format_short'), strtotime($result['date_start']))
				);
			}

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('common/home', $data));
		} else {
			$this->document->setTitle($this->language->get('text_error'));

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['header'] = $this->load->controller('common/header');
			$data['footer'] = $this->load->controller('common/footer');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');

			$this->response->setOutput($this->load->view('common/home', $data));
		}
	}
}
