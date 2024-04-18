<?php
class ModelExtensionModuleMtCheckout extends Model
{
	// Запись настроек в базу данных
	public function SaveSettings()
	{
		$this->load->model('setting/setting');
		if (isset($this->request->get['store_id'])) {
			$store_id = $this->request->get['store_id'];
		} else {
			$store_id = 0;
		}
		//colors
		$filepath = $this->getCssFilePath();
		// if ($this->request->post['module_mt_checkout_global_colorbackground'] == '') {
		// 	$this->request->post['module_mt_checkout_global_colorbackground'] = 'inherit';
		// }
		// if ($this->request->post['module_mt_checkout_global_colorfont'] == '') {
		// 	$this->request->post['module_mt_checkout_global_colorfont'] = 'inherit';
		// }
		// if ($this->request->post['module_mt_checkout_global_colorhint'] == '') {
		// 	$this->request->post['module_mt_checkout_global_colorhint'] = 'inherit';
		// }
		// if ($this->request->post['module_mt_checkout_global_coloricon'] == '') {
		// 	$this->request->post['module_mt_checkout_global_coloricon'] = 'inherit';
		// }
		// if ($this->request->post['module_mt_checkout_global_colorsuccess'] == '') {
		// 	$this->request->post['module_mt_checkout_global_colorsuccess'] = 'inherit';
		// }
		// if ($this->request->post['module_mt_checkout_global_colorerror'] == '') {
		// 	$this->request->post['module_mt_checkout_global_colorerror'] = 'inherit';
		// }
		// if ($this->request->post['module_mt_checkout_global_colormain'] == '') {
		// 	$this->request->post['module_mt_checkout_global_colormain'] = 'inherit';
		// }
		$r = file_put_contents($filepath, "
		:root {
	
			 --color-light: ".$this->request->post['module_mt_checkout_global_colorbackground']."; /* Цвет фона - надо скопировать и создать в твиге поле */
			 --color-dark: ".$this->request->post['module_mt_checkout_global_colorfont']."; /* Цвет шрифта */ 
			 --color-silver-sand: #bec2c5; /* не нужно Кружочки */
			 --color-alto: #e0e0e0; /* не нужно */	
			 --color-primary: ".$this->request->post['module_mt_checkout_global_colormain']."; /* Основной */
			 --color-primary-hover: ".$this->request->post['module_mt_checkout_global_colorbutton']."; /* не нужно - наведение */
			 --color-secondary: ".$this->request->post['module_mt_checkout_global_colorhint']."; /* Подсказки */ 
			 --color-thindly: ".$this->request->post['module_mt_checkout_global_coloricon']."; /* Цвет иконок */
			 --color-scorpion: #606060; /* не нужно Служебный серый */ 
			 --color-cararra: #d0d0ce; /* не нужно - разделительные линии */
			 --color-green: ".$this->request->post['module_mt_checkout_global_colorsuccess']."; /* Успешное уведомление */
			 --color-red: ".$this->request->post['module_mt_checkout_global_colorerror']."; /* Ошибка уведомление */
		}");
		$this->model_setting_setting->editSetting('module_mt_checkout', $this->request->post, $store_id);
	}
	public function LoadStatus()
	{
		return $this->config->get('module_mt_checkout_status');
	}

	private function getCssFilePath() {
		$theme = $this->config->get('config_theme');
		
		$file =  DIR_CATALOG . 'view/theme/default/stylesheet/mtcheckout/colors.css';
		if (file_exists(DIR_CATALOG . 'view/theme/'.$theme.'/stylesheet/mtcheckout/colors.css')) {
			$file =  DIR_CATALOG . 'view/theme/'.$theme.'/stylesheet/mtcheckout/colors.css';
		}
		return $file;
	}
}