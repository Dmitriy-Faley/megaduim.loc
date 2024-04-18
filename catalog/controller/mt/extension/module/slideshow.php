<?php
class ControllerMTExtensionModuleSlideshow extends Controller {
	public function index($setting) {

		$this->document->addStyle('catalog/view/theme/mt/stylesheet/swiper-bundle.min.css');
		$this->document->addScript('catalog/view/theme/mt/js/swiper-bundle.min.js');
		
		$data = [];

		$data['banners'] = array();

		$results = $this->model_design_banner->getBanner($setting['banner_id']);

		foreach ($results as $result) {
			if (is_file(DIR_IMAGE . $result['image'])) {
				$data['banners'][] = array(
					'title' => $result['title'],
					'link'  => $result['link'],
					'image' => $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height'], 'pole')
				);
			}
		}
      
		return $data;
	}
}