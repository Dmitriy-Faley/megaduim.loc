<?php
class ControllerExtensionModuleMtFeatured extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/featured');

		$this->load->model('catalog/product');
		$this->load->model('catalog/category');

		$this->load->model('tool/image');


		$data = [];

		$data['title'] = $setting['name'];
		
		$data['heading_title'] = $this->language->get('heading_title');
		$data['heading_title'] = $setting['name'];



      	$this->load->model('setting/setting');
		$data += $this->model_setting_setting->getSetting('theme_mt');
		foreach ($data as $index => $d) {
			if (is_string($d))
				$data[$index] = html_entity_decode($d, ENT_QUOTES, 'UTF-8');
		}

		$this->load->model('account/wishlist');
      	$wishlist = $this->model_account_wishlist->getWishlist(); 

		$incart = $this->cart->getProducts();

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
			$setting['limit'] = 1;
		} 
		
		$data['width'] = $setting['width'];
		$data['height'] = $setting['height'];
		
		
		$data['checkAccesoryCount'] = 0;
		$data['checkFeaturedCount'] = 0;


		$data['categories'] = array();

		// Замените ID категорий и их названия на фактические значения из вашей базы данных
        $categories_info = array(
			array(
                'category_id'   => 7,
                'heading_title' => 'Солнцезащитные очки'
            ),
			array(
                'category_id'   => 1,
                'heading_title' => 'Оправы для очков'
            ),
            array(
                'category_id'   => 18,
                'heading_title' => 'Аксессуары'
            ),
        );

		/*
		foreach ($categories_info as $category_info) {
            $category_id = $category_info['category_id'];

            $filter_data = array(
                'filter_category_id' => $category_id,
                'sort'               => 'p.sort_order',
                'order'              => 'ASC',
                'start'              => 0,
                'limit'              => 10
            );

            $category_products = $this->model_catalog_product->getProducts($filter_data);

            $products = array();

            foreach ($category_products as $product) {
                // Добавляем информацию об атрибуте для каждого продукта
                $attribute_value = $this->model_catalog_product->getProductAttributes($product['product_id']);
                $attribute_value = isset($attribute_value[0]['attribute']) ? $attribute_value[0]['attribute'] : '';

                $products[] = array(
                    'product_id'  => $product['product_id'],
                    'name'        => $product['name'],
                    'price'       => $product['price'],
                    'special'     => $product['special'],
                    'thumb'       => $this->model_tool_image->resize($product['image'], 100, 100),
                    'href'        => $this->url->link('product/product', 'product_id=' . $product['product_id']),
                    'attribute'   => $attribute_value // Добавлен атрибут
                );
            }

            $data['categories'][] = array(
                'heading_title' => $category_info['heading_title'],
                'products'      => $products
            );
        }
		*/

	
		foreach ($categories_info as $category_info) {
			$category_id = $category_info['category_id'];

			$filter_data = array(
                'filter_category_id' => $category_id,
                'sort'               => 'p.sort_order',
                'order'              => 'ASC',
                'start'              => 0,
                'limit'              => 10 // Укажите необходимое количество товаров для вывода
            );

				$category_products  = $this->model_catalog_product->getProducts($filter_data);

				$products = array();

				foreach ($category_products  as $prod) {
					if ($prod['image']) {
						$image = $this->model_tool_image->resize($prod['image'], null, null);
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height'], (isset($data['theme_mt_image_product_checked_pole']) ? 'pole' : ''));
					}
					
					$attributes = [];
					if (isset($data['theme_mt_category_product_attributes_checked']) && $data['theme_mt_category_product_attributes_checked']) {
						foreach ($this->model_catalog_product->getProductAttributes($prod['product_id']) as $attribute_group) {
							foreach ($attribute_group['attribute'] as $attribute) {
								$attributes[] = $attribute;
								if (count($attributes) >= $data['theme_mt_category_product_attributes_length']) break;
							}
							if (count($attributes) >= $data['theme_mt_category_product_attributes_length']) break;
						}
					}

					if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
						$price = $this->currency->format($this->tax->calculate($prod['price'], $prod['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'], '', false);
					} else {
						$price = false;
					}

					if (!is_null($prod['special']) && (float)$prod['special'] >= 0) {
						$special = $this->currency->format($this->tax->calculate($prod['special'], $prod['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'], '', false);
						$tax_price = (float)$prod['special'];
					} else {
						$special = false;
						$tax_price = (float)$prod['price'];
					}

					if ($this->config->get('config_tax')) {
						$tax = $this->currency->format($tax_price, $this->session->data['currency']);
					} else {
						$tax = false;
					}

					if ($this->config->get('config_review_status')) {
						$rating = $prod['rating'];
					} else {
						$rating = false;
					}

					$time = strtotime($prod['date_added']);
					$one_week_ago = strtotime('-1 week');

					$sql = 'select * from '.DB_PREFIX.'product_discount where product_id = '.$prod['product_id'].' and (date_start <= "'.date('Y-m-d').'" or date_start = "0000-00-00") and (date_end >= "'.date('Y-m-d').'" or date_end = "0000-00-00") order by priority asc, quantity desc limit 1';
					$res = $this->db->query($sql);
					$discount = null;
					if ($res->rows && $data['theme_mt_category_product_sticker_discount']) {
						$discount = $res->row;
						if ($discount && isset($discount['price']) && $discount['price'] > 0) {
							$discount = round((($discount['price'] - $prod['price'])/$prod['price'])*100)."% скидка от ".$discount['quantity']." шт.";
						}
					}
					$data['discount_check'] = $discount;
					$sql = 'select * from '.DB_PREFIX.'product_special where product_id = '.$prod['product_id'].' and (date_start <= "'.date('Y-m-d').'" or date_start = "0000-00-00") and (date_end >= "'.date('Y-m-d').'" or date_end = "0000-00-00") order by priority asc limit 1';
					$res = $this->db->query($sql);
					$special_check = null;
					if ($res->rows && $data['theme_mt_category_product_sticker_special']) {
						$special_check = $res->row;
						if ($special_check && isset($special_check['price']) && $special_check['price'] > 0) {
							$special_check = round((($special_check['price'] - $prod['price'])/$prod['price'])*100);
						}
					}

					$data['options'] = array();

					foreach ($this->model_catalog_product->getProductOptions($prod['product_id']) as $option) {
						
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

					$data['images'] = array();

					$results = $this->model_catalog_product->getProductImages($prod['product_id']);

					foreach ($results as $result) {
						$data['images'][] = array(
							'thumb' => $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_additional_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_additional_height'))
						);
					}

					$data['attribute_groups'] = $this->model_catalog_product->getProductAttributes($prod['product_id']);

					foreach ($data['attribute_groups'] as $arrtEach) {
						if($arrtEach['attribute_group_id'] == 31) {
							$data['checkFeaturedCount'] += 1;
						}
					}
					foreach ($data['attribute_groups'] as $arrtEach) {
						if($arrtEach['attribute_group_id'] == 32) {
							$data['checkAccesoryCount'] += 1;
						}
					}
					foreach ($data['attribute_groups'] as $arrtEach) {
						if($arrtEach['attribute_group_id'] == 33) {
							$data['checkAccesoryCount'] += 1;
						}
					}

					foreach ($data['attribute_groups'] as $arrtEach) {
						if($arrtEach['attribute_group_id'] == 8) {
							$data['checkAccesoryCount'] += 1;
						}
					}


					$products[] = array(
						'product_id'  => $prod['product_id'],
						'new'         => ($time > $one_week_ago ? true : false),
						'special_check' => $special_check,
						'discount'    => $discount,
						'in_wishlist' => (in_array($prod['product_id'], array_column($wishlist, 'product_id')) ? 1 : 0),
						'in_cart'     => (in_array($prod['product_id'], array_column($incart, 'product_id')) ? 1 : 0),
						'attributes'  => $attributes,
						'attrblock'   => $data['attribute_groups'],
						'thumb'       => $image,
						'option'      => $data['options'], 
						'imgDop'      => $data['images'], 
						'name'        => $prod['name'],
						'quantity'    => $prod['quantity'],
						'model'       => $prod['model'],
						'description' => utf8_substr(trim(strip_tags(html_entity_decode($prod['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')),
						'price'       => $price,
						'special'     => $special,
						'tax'         => $tax,
						'stock'       => $prod['stock_status'],
						'minimum'     => $prod['minimum'] > 0 ? $prod['minimum'] : 1,
						'rating'      => $prod['rating'],
						'href'        => $this->url->link('product/product', 'product_id=' . $prod['product_id']),
						'hrefPiece'        => $this->url->link('product/product', 'product_id=')
					);

					//var_dump($data['attribute_groups']);
			}

			$data['categories'][] = array(
                'heading_title' => $category_info['heading_title'],
                'products'      => $products
            );
		}
	

		//Вывод товаров с определенной категории (Аксессуары)

		$parts = explode('_', (string)$this->request->get['path']);

		$category_id = (int)array_pop($parts);

		$filter_data = array(
			'filter_category_id' => 65,
		);
		$filter_dataMan = array(
			'filter_category_id' => 64,
		);

		$data['products_accessory'] = array();
		$results = $this->model_catalog_product->getProducts($filter_data);

		$this->load->model('account/wishlist');
		$wishlist = $this->model_account_wishlist->getWishlist();

		$incart = $this->cart->getProducts();

		$data['products_latest'] = array();

		$filter_data = array(
			'sort'  => 'p.date_added',
			'order' => 'DESC',
			'start' => 0,
			'limit' => $setting['limit']
		);

		$results = $this->model_catalog_product->getProducts($filter_data);

		if ($results) {
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], null, null);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height'], (isset($data['theme_mt_image_product_checked_pole']) ? 'pole' : ''));
				}
				$attributes = [];

				if (isset($data['theme_mt_category_product_attributes_checked']) && $data['theme_mt_category_product_attributes_checked']) {
					foreach ($this->model_catalog_product->getProductAttributes($result['product_id']) as $attribute_group) {
						foreach ($attribute_group['attribute'] as $attribute) {
							$attributes[] = $attribute;
							if (count($attributes) >= $data['theme_mt_category_product_attributes_length']) break;
						}
						if (count($attributes) >= $data['theme_mt_category_product_attributes_length']) break;
					}
				}

				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'], '', false);
				} else {
					$price = false;
				}

				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'], '', false);
				} else {
					$special = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = $result['rating'];
				} else {
					$rating = false;
				}

				$time = strtotime($result['date_added']);
				$one_week_ago = strtotime('-1 week');

				$sql = 'select * from '.DB_PREFIX.'product_discount where product_id = '.$result['product_id'].' and (date_start <= "'.date('Y-m-d').'" or date_start = "0000-00-00") and (date_end >= "'.date('Y-m-d').'" or date_end = "0000-00-00") order by priority asc, quantity desc limit 1';
				$res = $this->db->query($sql);
				$discount = null;
				if ($res->rows && $data['theme_mt_category_product_sticker_discount']) {
					$discount = $res->row;
					if ($discount && isset($discount['price']) && $discount['price'] > 0) {
						$discount = round((($discount['price'] - $result['price'])/$result['price'])*100)."% скидка от ".$discount['quantity']." шт.";
					}
				}
				$data['discount_check'] = $discount;
				$sql = 'select * from '.DB_PREFIX.'product_special where product_id = '.$result['product_id'].' and (date_start <= "'.date('Y-m-d').'" or date_start = "0000-00-00") and (date_end >= "'.date('Y-m-d').'" or date_end = "0000-00-00") order by priority asc limit 1';
				$res = $this->db->query($sql);
				$special_check = null;
				if ($res->rows && $data['theme_mt_category_product_sticker_special']) {
					$special_check = $res->row;
					if ($special_check && isset($special_check['price']) && $special_check['price'] > 0) {
						$special_check = round((($special_check['price'] - $result['price'])/$result['price'])*100);
					}
				}

				$data['products_latest'][] = array(
					'product_id'  => $result['product_id'],
					'new'         => ($time > $one_week_ago ? true : false),
					'special_check' => $special_check,
					'discount'    => $discount,
					'in_wishlist' => (in_array($result['product_id'], array_column($wishlist, 'product_id')) ? 1 : 0),
					'attributes'  => $attributes,
					'thumb'       => $image,
					'name'        => $result['name'],
					//'model'       => $result['model'],
					'quantity'    => $result['quantity'],
					'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')),
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'stock'       => $result['stock_status'],
					'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
					'rating'      => $result['rating'],
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
				);
			}
		}

		$data['products_special'] = array();

		$filter_data = array(
			'sort'  => 'pd.name',
			'order' => 'ASC',
			'start' => 0,
			'limit' => $setting['limit']
		);

		$results = $this->model_catalog_product->getProductSpecials($filter_data);

		if ($results) {
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], null, null);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height'], (isset($data['theme_mt_image_product_checked_pole']) ? 'pole' : ''));
				}
				
				$attributes = [];
				if (isset($data['theme_mt_category_product_attributes_checked']) && $data['theme_mt_category_product_attributes_checked']) {
					foreach ($this->model_catalog_product->getProductAttributes($result['product_id']) as $attribute_group) {
						foreach ($attribute_group['attribute'] as $attribute) {
							$attributes[] = $attribute;
							if (count($attributes) >= $data['theme_mt_category_product_attributes_length']) break;
						}
						if (count($attributes) >= $data['theme_mt_category_product_attributes_length']) break;
					}
				}

				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'], '', false);
				} else {
					$price = false;
				}

				if (!is_null($result['special']) && (float)$result['special'] >= 0) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'], '', false);
					$tax_price = (float)$result['special'];
				} else {
					$special = false;
					$tax_price = (float)$result['price'];
				}
	
				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format($tax_price, $this->session->data['currency']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = $result['rating'];
				} else {
					$rating = false;
				}

				$time = strtotime($result['date_added']);
				$one_week_ago = strtotime('-1 week');

				$sql = 'select * from '.DB_PREFIX.'product_discount where product_id = '.$result['product_id'].' and (date_start <= "'.date('Y-m-d').'" or date_start = "0000-00-00") and (date_end >= "'.date('Y-m-d').'" or date_end = "0000-00-00") order by priority asc, quantity desc limit 1';
				$res = $this->db->query($sql);
				$discount = null;
				if ($res->rows && $data['theme_mt_category_product_sticker_discount']) {
					$discount = $res->row;
					if ($discount && isset($discount['price']) && $discount['price'] > 0) {
						$discount = round((($discount['price'] - $result['price'])/$result['price'])*100)."% скидка от ".$discount['quantity']." шт.";
					}
				}
				$data['discount_check'] = $discount;
				$sql = 'select * from '.DB_PREFIX.'product_special where product_id = '.$result['product_id'].' and (date_start <= "'.date('Y-m-d').'" or date_start = "0000-00-00") and (date_end >= "'.date('Y-m-d').'" or date_end = "0000-00-00") order by priority asc limit 1';
				$res = $this->db->query($sql);
				$special_check = null;
				if ($res->rows && $data['theme_mt_category_product_sticker_special']) {
					$special_check = $res->row;
					if ($special_check && isset($special_check['price']) && $special_check['price'] > 0) {
						$special_check = round((($special_check['price'] - $result['price'])/$result['price'])*100);
					}
				}

				$data['products_special'][] = array(
					'product_id'  => $result['product_id'],
					'new'         => ($time > $one_week_ago ? true : false),
					'special_check' => $special_check,
					'discount'    => $discount,
					'in_wishlist' => (in_array($result['product_id'], array_column($wishlist, 'product_id')) ? 1 : 0),
					'attributes'  => $attributes,
					'thumb'       => $image,
					'name'        => $result['name'],
					//'model'       => $result['model'],
					'quantity'    => $result['quantity'],
					'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')),
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'stock'       => $result['stock_status'],
					'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
					'rating'      => $result['rating'],
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
				);
			}
		}

		$data['logged'] = $this->customer->isLogged();
		return $this->load->view('extension/module/mt_featured', $data);
	}
}