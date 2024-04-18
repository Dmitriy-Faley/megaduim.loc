<?php
class ControllerExtensionModuleMtstocks extends Controller
{
	public function index($setting)
	{
		if (isset($setting['name'][$this->config->get('config_language_id')])) {
			if (isset($setting['status']) && $setting['status'] && isset($setting['module_description'][$this->config->get('config_language_id')])) {
				$this->document->addStyle('catalog/view/theme/mt/stylesheet/mt-stocks.css');
				$this->load->model('tool/image');
		 		$data = array();
				$data['items'] = array();
				$data['name'] = $setting['name'];
				foreach($setting['module_description'][$this->config->get('config_language_id')] as $i){
					if(is_array($i)){
						foreach($i as $i2){
							$icon = '';
							if (isset($i2['icon'])) {
								$icon = $this->model_tool_image->resize($i2['icon'], 320, 215, 'pole');
							} else {							
								$icon = false;
							}
							//if ($i['status']) {
								$data['items'][] = array (
									'title'			=>	$i2['title'],
									'description'		=> 	$i2['description'],
									'special'			=> 	$i2['special'],
									'link'			=> 	$i2['link'],
									'icon'			=>	$icon,
									'sort_order'	=>	(isset($i2['sort_order']) ? ($i2['sort_order'] == '' ? 0 : $i2['sort_order']) : 0),
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
				$this->document->addStyle('/catalog/view/theme/mt/stylesheet/mt-stocks.css');
		 		return $this->load->view('extension/module/mtstocks', $data);
			}
		}
	}
}