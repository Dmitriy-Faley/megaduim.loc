<?php
class ControllerExtensionModuleMtbigimage extends Controller
{
	public function index($setting)
	{
		if (isset($setting['name'][$this->config->get('config_language_id')])) {
			if (isset($setting['status']) && $setting['status'] && isset($setting['module_description'][$this->config->get('config_language_id')])) {
				$this->load->model('tool/image');
		 		$data = $setting;
				$data['icon'] = $this->model_tool_image->resize($setting['icon'], 630, 630);
				$data['items'] = array();
				foreach($setting['module_description'][$this->config->get('config_language_id')] as $i){
					if(is_array($i)){
						foreach($i as $i2){
							$icon = '';
							if (isset($i2['icon'])) {
								$icon = $this->model_tool_image->resize($i2['icon'], 60, 60, 'pole');
							} else {							
								$icon = false;
							}
							$data['items'][] = array (
								'title'			=>	$i2['title'],
								'text'			=> html_entity_decode($i2['text']),
								'icon'			=>	$icon,
								'sort_order'	=>	(isset($i2['sort_order']) ? ($i2['sort_order'] == '' ? 0 : $i2['sort_order']) : 0),
							);
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
				$this->document->addStyle('/catalog/view/theme/default/stylesheet/mt-big-image.css');
		 		return $this->load->view('extension/module/mt_big_image', $data);
			}
		}
	}
}