<?php
class ControllerExtensionModuleMtbalv extends Controller
{
	public function index($setting)
	{
		if (isset($setting['name'][$this->config->get('config_language_id')])) {
			if (isset($setting['status']) && $setting['status'] && isset($setting['module_description'][$this->config->get('config_language_id')])) {
				$this->load->model('tool/image');
		 		$data = array();
				$data['items'] = array();
				foreach($setting['module_description'][$this->config->get('config_language_id')] as $i){
					if(is_array($i)){
						foreach($i as $i2){
							$width = isset($i2['width']) ? (int) $i2['width'] : 0;
							$width = $width <= 0 ? 1 : $width;
							$height = isset($i2['height']) ? (int) $i2['height'] : 0;
							$height = $height <= 0 ? 1 : $height;
							$icon = '';
							if (isset($i2['icon'])) {
								if ($width == 2 && $height == 1){
									$icon = $this->model_tool_image->resize($i2['icon'], 504, 252, 'pole');
								}
								else if ($width == 2 && $height == 2){
									$icon = $this->model_tool_image->resize($i2['icon'], 504, 504, 'pole');
								}
								else if ($width == 1 && $height == 2){
									$icon = $this->model_tool_image->resize($i2['icon'], 252, 504, 'pole');
								}
								else{
									$icon = $this->model_tool_image->resize($i2['icon'], 252, 252, 'pole');
								}
							} else {							
								$icon = false;
							}
							//if ($i['status']) {
								$data['items'][] = array (
									'title'			=>	$i2['title'],
									'special'		=> 	$i2['special'],
									'link'			=> 	$i2['link'],
									'css'			=> 	(isset($i2['css']) ? $i2['css'] : ''),
									'icon'			=>	$icon,
									'sort_order'	=>	(isset($i2['sort_order']) ? ($i2['sort_order'] == '' ? 0 : $i2['sort_order']) : 0),
									'wide'			=> 	$height > 1,
									'long'			=>	$width > 1
								);
							//}
						}
					}
					
				}
				usort($data['items'], function($a, $b) 
				{
					if (isset($a['sort_order']) && isset($b['sort_order'])){
						return $a['sort_order'] - $b['sort_order'];
					}
					else{
						return 0;
					}
				});
				$this->document->addStyle('/catalog/view/theme/default/stylesheet/mtbalv.css');
		 		return $this->load->view('extension/module/mtbalv', $data);
			}
		}
	}
}