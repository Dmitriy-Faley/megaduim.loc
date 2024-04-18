<?php
class ControllerExtensionModuleMtvideobanner extends Controller
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
							$icon = '';
							if (isset($i2['icon'])) {
								$icon = $this->model_tool_image->resize($i2['icon'], 640, 308, 'pole');
							} else {							
								$icon = false;
							}
							$data['items'][] = array (
								'title'			=>	$i2['title'],
								'time'		=> 	$i2['time'],
								'link'			=> 	$i2['link'],
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
				$this->document->addStyle('/catalog/view/theme/default/stylesheet/mt-video-banner.css');
		 		return $this->load->view('extension/module/mt_video_banner', $data);
			}
		}
	}
}