<?php

class ControllerExtensionModuleMatrositeLooked extends Controller {
	public function index() {
		
		$this->load->model('setting/setting');
		$this->load->language('extension/module/matrosite/looked');
		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		$this->load->model('account/wishlist');
		$wishlist = $this->model_account_wishlist->getWishlist();

		$incart = $this->cart->getProducts();
		
		$setting = $this->model_setting_setting->getSetting('module_matrosite_looked');
		
		if ( !isset($setting['module_matrosite_looked_limit']) ) {
			$setting['module_matrosite_looked_limit'] = 4;
		}
		
		if ( (int)$setting['module_matrosite_looked_status'] == 1 ) {
			
			$data['heading_title'] = $this->language->get('matrosite_looked_title');
			
			if (isset($this->session->data['matrosite']['looked'])) {
				$products = $this->session->data['matrosite']['looked'];
			} else {
				$products = array();
			}
			
			if (isset($this->request->get['product_id'])) {
				
				$isset = false; // Флаг присутствия текущего товара в списке
			
				foreach($products as $key => $product_id){
					if ($product_id == $this->request->get['product_id']) {
						$isset = true;
						unset($products[$key]);
					}
				}
				
				if (!$isset) {
					$this->session->data['matrosite']['looked'][] = $this->request->get['product_id'];
				}
				
				// Удаляем излишки
				if (count($this->session->data['matrosite']['looked']) > (int)$setting['module_matrosite_looked_limit']) {
					$iteration = count($this->session->data['matrosite']['looked']) - (int)$setting['module_matrosite_looked_limit'];
					for ($i=0; $i<$iteration; $i++){
						array_shift($this->session->data['matrosite']['looked']);
					}
				}
			
			}
			
			$data['products'] = array();
			foreach(array_reverse($products) as $key => $product_id){
				$product_info = $this->model_catalog_product->getProduct($product_id);
				if ($product_info) {
					if ($product_info['image']) {
						$image = $this->model_tool_image->resize($product_info['image'], null, null);
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_related_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_related_height'));
					}

					if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
						$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
						$price = false;
					}

					if ((float)$product_info['special']) {
						$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
						$special = false;
					}

					if ($this->config->get('config_tax')) {
						$tax = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price'], $this->session->data['currency']);
					} else {
						$tax = false;
					}

					if ($this->config->get('config_review_status')) {
						$rating = $product_info['rating'];
					} else {
						$rating = false;
					}

					$data['images'] = array();

					$results = $this->model_catalog_product->getProductImages($product_info['product_id']);
		
					foreach ($results as $result) {
						$data['images'][] = array(
							'thumb' => $this->model_tool_image->resize($result['image'], null, null)
						);
					}
					$data['options'] = array();
					foreach ($this->model_catalog_product->getProductOptions($product_info['product_id']) as $option) {
						$product_option_value_data = array();
						foreach ($option['product_option_value'] as $option_value) {
							if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {

								$product_option_value_data[] = array(
									'product_option_value_id' => $option_value['product_option_value_id'],
									'option_value_id'         => $option_value['option_value_id'],
									'name'                    => $option_value['name'],
									'image'                   => $this->model_tool_image->resize($option_value['image'], 50, 50),
									'price'                   => $price,
									'quantity'                => $option_value['quantity'],
									'price_prefix'            => $option_value['price_prefix']
								);
							}
						}
						$data['options'][] = array(
							'product_option_id'    => $option['product_option_id'],
							'product_option_value' => $product_option_value_data,
							'option_id'            => $option['option_id'],
							'name'                 => $option['name'],
							'type'                 => $option['type'],
							'value'                => $option['value'],
							'required'             => $option['required']
						);
					}
					$data['in_wishlist'] = (in_array($result['product_id'], array_column($wishlist, 'product_id')) ? 1 : 0);
		

					$data['attribute_groups'] = $this->model_catalog_product->getProductAttributes($product_info['product_id']);


					$data['products'][] = array(
						'product_id'  => $product_info['product_id'],
						'thumb'       => $image,
						'in_wishlist' => (in_array($product_info['product_id'], array_column($wishlist, 'product_id')) ? 1 : 0),
						'in_cart'     => (in_array($product_info['product_id'], array_column($incart, 'product_id')) ? 1 : 0),
						'imgDop'      => $data['images'],
						'name'        => $product_info['name'],
						'model'      => $product_info['model'],
						'description' => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
						'price'       => $price,
						'special'     => $special,
						'tax'         => $tax,
						'option'      => $data['options'],
						'attrblock'   => $data['attribute_groups'],
						'stock'     => $product_info['stock_status'],
						'minimum'    => $result['minimum'] > 0 ? $result['minimum'] : 1,
						'rating'      => $rating,
						'href'        => $this->url->link('product/product', 'product_id=' . $product_info['product_id']),
						'hrefPiece'        => $this->url->link('product/product', 'product_id=')
						
					);
				}
			}
			
			$data['count'] = count($data['products']);
			
			if ($data['products']) {
				return $this->load->view('extension/module/matrosite_looked', $data);
			}
		
		}
		$data['logged'] = $this->customer->isLogged();
	}
}