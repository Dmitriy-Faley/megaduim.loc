<?php
class ControllerExtensionModuleBanner extends Controller {
	public function index($setting) {
		static $module = 0;

		$this->load->model('design/banner');
		$this->load->model('tool/image');

		$this->document->addStyle('catalog/view/javascript/jquery/swiper/css/swiper.min.css');
		$this->document->addStyle('catalog/view/javascript/jquery/swiper/css/opencart.css');
		$this->document->addScript('catalog/view/javascript/jquery/swiper/js/swiper.jquery.js');

		$data['banners'] = array();

		$results = $this->model_design_banner->getBanner($setting['banner_id']);

		foreach ($results as $result) {
			//if (is_file(DIR_IMAGE . $result['image'])) {
				$data['banners'][] = array(
					'title' => $result['title'], 
					'link'  => $result['link'],
					'link_mob'  => $result['link_mob'],
					'btn_link' => $result['btn_link'],
					'image' => $this->model_tool_image->resize($result['image'], null, null)
				);
			//}
		}


		$data['module'] = $module++;

		return $this->load->view('extension/module/banner', $data);
	}
}