<?php
class ControllerInformationDoc extends Controller {

	public function index() {
		$this->load->language('information/doc');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home'),
			'separator' => false
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('information/doc'),
			'separator' => $this->language->get('text_separator')
		);

		$this->load->model('catalog/doc'); 

		$data['docs'] = array();

		$docs = $this->model_catalog_doc->getDocsCustom();

		if ($docs) {
			$this->document->setTitle($this->language->get('heading_title'));

			foreach ($docs as $result) {
				$data['docs'][] = array(
					'title' => $result['title'],
					'file' => $result['filename'],
				);
			}
			
			// Downloads
			$download_data = array();

			//$download_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_download p2d LEFT JOIN " . DB_PREFIX . "download d ON (p2d.download_id = d.download_id) LEFT JOIN " . DB_PREFIX . "download_description dd ON (d.download_id = dd.download_id) WHERE p2d.product_id = '" . (int)$cart['product_id'] . "' AND dd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

			foreach ($download_query->rows as $download) {
				$download_data[] = array(
					'download_id' => $download['download_id'],
					'name'        => $download['name'],
					'filename'    => $download['filename'],
					'mask'        => $download['mask']
				);
			}
			

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('information/doc_info', $data));
		} else {
			$this->document->setTitle($this->language->get('text_error'));

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['header'] = $this->load->controller('common/header');
			$data['footer'] = $this->load->controller('common/footer');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');

			$this->response->setOutput($this->load->view('information/doc_info', $data));
		}
	}
}
