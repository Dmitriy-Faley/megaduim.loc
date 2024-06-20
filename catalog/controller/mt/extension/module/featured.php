<?php
class ControllerMTExtensionModuleFeatured extends Controller {
	public function index($setting) {
      $this->load->language('extension/module/featured');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		$data['logged'] = $this->customer->isLogged();

		$data = [];
		
      $this->load->model('setting/setting');
		$data += $this->model_setting_setting->getSetting('theme_mt');
		foreach ($data as $index => $d) {
			if (is_string($d))
				$data[$index] = html_entity_decode($d, ENT_QUOTES, 'UTF-8');
		}

		$this->load->model('account/wishlist');
      $wishlist = $this->model_account_wishlist->getWishlist();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "currency");
      $currencies = [];
      foreach ($query->rows as $currency) {
         $currencies[$currency['code']] = array(
            'currency_id'   => $currency['currency_id'],
            'title'         => $currency['title'],
            'symbol_left'   => $currency['symbol_left'],
            'symbol_right'  => $currency['symbol_right'],
            'decimal_place' => $currency['decimal_place'],
            'value'         => $currency['value']
         );
      }

      $data['symbol_left'] = $currencies[$this->session->data['currency']]['symbol_left'];
      $data['symbol_right'] = $currencies[$this->session->data['currency']]['symbol_right'];

		$data['products'] = array();

		if (!$setting['limit']) {
			$setting['limit'] = 4;
		}

		$data['width'] = $setting['width'];
		$data['height'] = $setting['height'];
		
		if (!empty($setting['product'])) {
			$products = array_slice($setting['product'], 0, (int)$setting['limit']);

			foreach ($products as $product_id) {
				$product_info = $this->model_catalog_product->getProduct($product_id);

				if ($product_info) {
					if ($product_info['image']) {
						$image = $this->model_tool_image->resize($product_info['image'], $setting['width'], $setting['height'], (isset($data['theme_mt_image_product_checked_pole']) ? 'pole' : ''));
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height'], (isset($data['theme_mt_image_product_checked_pole']) ? 'pole' : ''));
					}

					$attributes = [];
					if (isset($data['theme_mt_category_product_attributes_checked']) && $data['theme_mt_category_product_attributes_checked']) {
						foreach ($this->model_catalog_product->getProductAttributes($product_info['product_id']) as $attribute_group) {
							foreach ($attribute_group['attribute'] as $attribute) {
								$attributes[] = $attribute;
								if (count($attributes) >= $data['theme_mt_category_product_attributes_length']) break;
							}
							if (count($attributes) >= $data['theme_mt_category_product_attributes_length']) break;
						}
					}

					if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
						$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'], '', false);
					} else {
						$price = false;
					}

					if (!is_null($product_info['special']) && (float)$product_info['special'] >= 0) {
						$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'], '', false);
						$tax_price = (float)$product_info['special'];
					} else {
						$special = false;
						$tax_price = (float)$product_info['price'];
					}
		
					if ($this->config->get('config_tax')) {
						$tax = $this->currency->format($tax_price, $this->session->data['currency']);
					} else {
						$tax = false;
					}

					if ($this->config->get('config_review_status')) {
						$rating = $product_info['rating'];
					} else {
						$rating = false;
					}

					$data['images'] = array();

					$dops = $this->model_catalog_product->getProductImages($product_info['product_id']);

					foreach ($dops as $dop) {
						$data['images'][] = array(
							'thumb' => $this->model_tool_image->resize($dop['image'], null, null)
						);
					}

					$data['products'][] = array(
						'product_id'  => $product_info['product_id'],
						'in_wishlist' => (in_array($product_info['product_id'], array_column($wishlist, 'product_id')) ? 1 : 0),
						'attributes'  => $attributes,
						'thumb'       => $image,
						'imgDop'      => $data['images'],
						'name'        => $product_info['name'],
						'model'        =>$product_info['model'],
						'sku'    	  => $product_info['sku'],
						'quantity'    => $product_info['quantity'],
						'description' => utf8_substr(trim(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')),
						'price'       => $price,
						'special'     => $special,
						'tax'         => $tax,
						'stock'       => $product_info['stock_status'],
						'minimum'     => $product_info['minimum'] > 0 ? $product_info['minimum'] : 1,
						'rating'      => $product_info['rating'],
						'href'        => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
					);
				}
			}
		}
      
		return $data;
	}
}