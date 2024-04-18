<?php
class ControllerMTProductProduct extends Controller {
	public function index() {
      
      //$this->document->addStyle('catalog/view/theme/mt/stylesheet/choices.min.css');
      $this->document->addStyle('catalog/view/theme/mt/stylesheet/product.css');
	  //$this->document->addStyle('catalog/view/theme/mt/stylesheet/swiper-bundle.min.css');
      //$this->document->addScript('catalog/view/theme/mt/js/choices.min.js');
//	  $this->document->addStyle('catalog/view/theme/mt/js/select.js');
	  

      $this->document->addStyle('catalog/view/theme/mt/stylesheet/product-slider.css');
      $this->document->addStyle('catalog/view/theme/mt/stylesheet/similar-slider.css');
      $this->document->addStyle('catalog/view/theme/mt/stylesheet/jquery.datetimepicker.min.css');
      $this->document->addStyle('catalog/view/theme/mt/stylesheet/gallery/lightgallery.min.css');

      $this->document->addScript('catalog/view/theme/mt/js/jquery.datetimepicker.full.min.js');
      //$this->document->addScript('catalog/view/theme/mt/js/swiper-bundle.min.js');
      //$this->document->addScript('catalog/view/theme/mt/js/device.js');
      $this->document->addScript('catalog/view/theme/mt/js/gallery/lightgallery.min.js');
      $this->document->addScript('catalog/view/theme/mt/js/gallery/lg-zoom.min.js','footer');

	  $incart = $this->cart->getProducts();

	if (!isset($this->request->get['path']) && !isset($this->request->get['manufacturer_id']) && !isset($this->request->get['search']) && !isset($this->request->get['tag'])) {        
	
		$category_ids = $this->model_catalog_product->getCategories($this->request->get['product_id']);
				
		$sort = array();
		foreach ($category_ids as $category_idss) {
			$sort[$category_idss['num_id']] = (int)$category_idss['category_id'];
		}
		ksort($sort);        
				
		$b=array_pop($sort);
		while ($b != '0') {
			$cat_arr[] = $b;
			$a = $this->model_catalog_category->getCategory($b);
			$b = $a['parent_id'];
		}      
		$this->request->get['path'] = implode("_", array_reverse($cat_arr));
	}


      $data = [];

      $this->load->model('setting/setting');
		$data += $this->model_setting_setting->getSetting('theme_mt');
		foreach ($data as $index => $d) {
			if (is_string($d))
				$data[$index] = html_entity_decode($d, ENT_QUOTES, 'UTF-8');
		}

      $this->load->model('account/wishlist');
      $wishlist = $this->model_account_wishlist->getWishlist();

      $product_info = $this->model_catalog_product->getProduct($this->request->get['product_id']);

      $data['quantity'] = $product_info['quantity'];
      $data['sku'] = $product_info['sku'];
      $data['review_count'] = $product_info['reviews'];
      $data['in_wishlist'] = (in_array($this->request->get['product_id'], array_column($wishlist, 'product_id')) ? 1 : 0);
		
      $data['in_compare'] = 0;
      if (isset($this->session->data['compare'])) {
         if (in_array((int)$this->request->get['product_id'], $this->session->data['compare'])){
            $data['in_compare'] = 1;
         }
      }
      
      $data['imagesMain'] = array();

      $results = $this->model_catalog_product->getProductImages($this->request->get['product_id']);

      $data['imagesMain'][] = array(
         'popup' => $this->model_tool_image->resize($product_info['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_height'), (isset($data['theme_mt_image_popup_checked_pole']) ? 'pole' : '')),
         'thumb' => $this->model_tool_image->resize($product_info['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_thumb_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_thumb_height'), (isset($data['theme_mt_image_thumb_checked_pole']) ? 'pole' : '')),
         'additional' => $this->model_tool_image->resize($product_info['image'], null, null)
      );

      foreach ($results as $result) {
         $data['imagesMain'][] = array(
            'popup' => $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_height'), (isset($data['theme_mt_image_popup_checked_pole']) ? 'pole' : '')),
            'thumb' => $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_thumb_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_thumb_height'), (isset($data['theme_mt_image_thumb_checked_pole']) ? 'pole' : '')),
            'additional' => $this->model_tool_image->resize($result['image'], null, null)
         );
      }
		
		$this->document->setMtogimage($this->model_tool_image->resize($product_info['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_height'), (isset($data['theme_mt_image_popup_checked_pole']) ? 'pole' : '')));
		$this->document->setMtogurl($this->url->link('product/product', 'product_id=' . $product_info['product_id']));

      if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
         $data['price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'], '', false);
      } else {
         $data['price'] = false;
      }

      if (!is_null($product_info['special']) && (float)$product_info['special'] >= 0) {
         $data['special'] = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'], '', false);
         $tax_price = (float)$product_info['special'];
      } else {
         $data['special'] = false;
         $tax_price = (float)$product_info['price'];
      }
      
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

	  	//Вывод похожих товаров
		$parts = explode('_', (string)$this->request->get['path']);

		$category_id = (int)array_pop($parts);
		
		$filter_data = array(
		'filter_category_id' => $category_id
		);

		$data['products_variant'] = array();

		$results = $this->model_catalog_product->getProducts($filter_data);
		if ((float)$product_info['special']) {
			$data['special'] = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
		} else {
			$data['special'] = false;
		}
		
		foreach ($results as $result) {
			/* if ($result['image']) {
				$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
			} else {
				$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
			}    */ 
			
	 
			if ($result['image']) {
				$image = $this->model_tool_image->resize($result['image'], null, null);
			} else {
				$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_related_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_related_height'), (isset($data['theme_mt_image_related_checked_pole']) ? 'pole' : ''));
			}

			$additional = $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_additional_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_additional_height'), (isset($data['theme_mt_image_additional_checked_pole']) ? 'pole' : ''));

			
			/* $data['images'] = array();

			$results = $this->model_catalog_product->getProductImages($this->request->get['product_id']);

			$data['images'][] = array(
				'popup' => $this->model_tool_image->resize($product_info['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_height'), (isset($data['theme_mt_image_popup_checked_pole']) ? 'pole' : '')),
				'thumb' => $this->model_tool_image->resize($product_info['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_thumb_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_thumb_height'), (isset($data['theme_mt_image_thumb_checked_pole']) ? 'pole' : '')),
				'additional' => $this->model_tool_image->resize($product_info['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_additional_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_additional_height'), (isset($data['theme_mt_image_additional_checked_pole']) ? 'pole' : ''))
			); */



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

			$stock = $this->language->get('text_stock') . ' ';
			if ($result['quantity'] <= 0) {
				$stock .= $result['stock_status'];
			} elseif ($this->config->get('config_stock_display')) {
				$stock .= $result['quantity'];
			} else {
				$stock .= $this->language->get('text_instock');
			}
			$stock_qty = $result['quantity']; 

			$data['imageVari'] = array();

			$resultsImageVari = $this->model_catalog_product->getProductImages($result['product_id']);

			foreach ($resultsImageVari as $resultImageVari) {
				$data['imageVari'][] = array(
					'thumb' => $this->model_tool_image->resize($resultImageVari['image'], null, null)
					
				);
			}
			$data['options'] = array();
					foreach ($this->model_catalog_product->getProductOptions($result['product_id']) as $option) {
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
 

			$data['attribute_groups'] = $this->model_catalog_product->getProductAttributes($result['product_id']);

			$data['products_variant'][] = array(
				'product_id'	=> $result['product_id'],
				'thumb'     => $image,
				'in_wishlist' => $data['in_wishlist'],
				'in_cart'     => (in_array($result['product_id'], array_column($incart, 'product_id')) ? 1 : 0),
				'imgDop'      => $data['imageVari'], 
				'option'      => $data['options'],
				'attrblock'   => $data['attribute_groups'],
				'stock'     => $result['stock_status'],
				'name'      => $result['name'],
				'price'     => $price,
				'special'   => $special,
				'model'     => $result['model'],
				'minimum'    => $result['minimum'] > 0 ? $result['minimum'] : 1,
				'stock'     => $result['stock_status'],
				'stock_qty' => $stock_qty,
				'href'      => $this->url->link('product/product', 'path=' . $this->request->get['path'] . '&product_id=' . $result['product_id'] . $url),
				'hrefPiece'        => $this->url->link('product/product', 'product_id=')
			);
		}
		//Конец вывода дочерних товаров


		//Вывод товаров с определенной категории (Аксессуары)
		$parts = explode('_', (string)$this->request->get['path']);

		$category_id = (int)array_pop($parts);

		
		$data['products_accessory'] = array();

		$results = $this->model_catalog_product->getProducts();
		$data['checkAccesoryCount'] = 0;
		foreach ($results as $result) {
			if ($result['image']) {
				$image = $this->model_tool_image->resize($result['image'], null, null);
			} else {
				$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
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

			$stock = $this->language->get('text_stock') . ' ';
			if ($result['quantity'] <= 0) {
				$stock .= $result['stock_status'];
			} elseif ($this->config->get('config_stock_display')) {
				$stock .= $result['quantity'];
			} else {
				$stock .= $this->language->get('text_instock');
			}

			$data['images'] = array();

			$resultsImage = $this->model_catalog_product->getProductImages($result['product_id']);

			foreach ($resultsImage as $resultImage) {
				$data['images'][] = array(
					'thumb' => $this->model_tool_image->resize($resultImage['image'], null, null)
					
				);
			}

			$data['options'] = array();
					foreach ($this->model_catalog_product->getProductOptions($result['product_id']) as $option) {
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

			$data[' '] = $this->model_catalog_product->getProductAttributes($result['product_id']);


			foreach ($data['attribute_groups'] as $arrtEach) {
				if($arrtEach['attribute_group_id'] == 33) {
					$data['checkAccesoryCount'] += 1;
				}
			}
			$stock_qty = $result['quantity']; 

			$data['attribute_groups'] = $this->model_catalog_product->getProductAttributes($result['product_id']);

			$data['products_accessory'][] = array(
				'product_id'	=> $result['product_id'],
				'thumb'     => $image,
				'in_wishlist' => $data['in_wishlist'],
				'in_cart'     => (in_array($result['product_id'], array_column($incart, 'product_id')) ? 1 : 0),
				'imgDops'      => $data['images'], 
				'option'      => $data['options'],
				'attrblock'   => $data['attribute_groups'],
				'stock'     => $result['stock_status'],
				'name'      => $result['name'],
				'price'     => $price,
				'special'   => $special,
				'model'     => $result['model'],
				'minimum'    => $result['minimum'] > 0 ? $result['minimum'] : 1,
				'stock'     => $result['stock_status'],
				'stock_qty' => $stock_qty,
				'href'      => $this->url->link('product/product', 'path=' . $this->request->get['path'] . '&product_id=' . $result['product_id'] . $url),
				'hrefPiece'        => $this->url->link('product/product', 'product_id=')
			);
		}
		//Конец вывода товаров с определенной категории (Аксессуары)


      $data['products'] = array();
      $results = $this->model_catalog_product->getProductRelated($this->request->get['product_id']);
	  
      foreach ($results as $result) {
         $attributes = [];
         if (isset($data['theme_mt_checkout_category_product_attributes_checked']) && $data['theme_mt_checkout_category_product_attributes_checked']) {
            foreach ($this->model_catalog_product->getProductAttributes($result['product_id']) as $attribute_group) {
               foreach ($attribute_group['attribute'] as $attribute) {
                  $attributes[] = $attribute;
                  if (count($attributes) >= $data['theme_mt_checkout_category_product_attributes_length']) break;
               }
               if (count($attributes) >= $data['theme_mt_checkout_category_product_attributes_length']) break;
            }
         }

         if ($result['image']) {
            $image = $this->model_tool_image->resize($resultImage['image'], null, null);
		} else {
            $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_related_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_related_height'), (isset($data['theme_mt_image_related_checked_pole']) ? 'pole' : ''));
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
            $rating = (int)$result['rating'];
         } else {
            $rating = false;
         }

         $data['products'][] = array(
            'product_id'  => $result['product_id'],
            'thumb'       => $image,
            'name'        => $result['name'],
            'in_wishlist' => (in_array($result['product_id'], array_column($wishlist, 'product_id')) ? 1 : 0),
            'attributes'  => $attributes,
            'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
            'price'       => $price,
            'special'     => $special,
            'tax'         => $tax,
            'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
            'rating'      => $rating,
            'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
         );	
		 
      }

      $this->load->model('catalog/manufacturer');

		if (isset($product_info['manufacturer_id'])) {
			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($product_info['manufacturer_id']);
         if ($manufacturer_info)
            $data['manufacturer_image'] = $this->model_tool_image->resize($manufacturer_info['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_location_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_location_height'), (isset($data['theme_mt_image_location_checked_pole']) ? 'pole' : ''));
		}
		if (isset($product_info['manufacturer_id'])) {
			$data['manufacturer_description'] = '';
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer_description WHERE language_id = " . $this->config->get('config_language_id') . " AND manufacturer_id = " . (int)$product_info['manufacturer_id']);

			foreach ($query->rows as $result) {
				$data['manufacturer_description'] = html_entity_decode($result['description']);
			}
		} else {
			$data['manufacturer_description'] = '';
		}
		$data['manufacturers'] = $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $product_info['manufacturer_id']);
		if (!$product_info['manufacturer_id']) $data['manufacturers'] = '';

      $data['symbol_left'] = $currencies[$this->session->data['currency']]['symbol_left'];
      $data['symbol_right'] = $currencies[$this->session->data['currency']]['symbol_right'];
	  
      $data['main_contact'] = null;
		if (isset($data['theme_mt_contacts']) && is_array($data['theme_mt_contacts'])) {
			usort($data['theme_mt_contacts'], function($a, $b) {
				if (!$a['sort_order']) $a['sort_order'] = 0;
				if (!$b['sort_order']) $b['sort_order'] = 0;
				return $a['sort_order'] - $b['sort_order'];
			});
			foreach ($data['theme_mt_contacts'] as $index => $contact) {
				if (!$contact['status']) {
					unset($data['theme_mt_contacts'][$index]);
					continue;
				}

				$data['theme_mt_contacts'][$index]['text_before_mobile'] = html_entity_decode($contact['text_before_mobile']);
				$data['theme_mt_contacts'][$index]['text_after_mobile'] = html_entity_decode($contact['text_after_mobile']);
				if (isset($contact['phones'])) {
					foreach ($contact['phones'] as $phone_index => $p) {
						$contact['phones'][$phone_index]['text'] = html_entity_decode($p['text']);
					}
					$phones = $contact['phones'];
					usort($phones, function($a, $b) {
						if (!$a['sort_order']) $a['sort_order'] = 0;
						if (!$b['sort_order']) $b['sort_order'] = 0;
						return $a['sort_order'] - $b['sort_order'];
					});
					$data['theme_mt_contacts'][$index]['phones'] = $phones;
				}
				if (isset($contact['messengers'])) {
					$messengers = $contact['messengers'];
					usort($messengers, function($a, $b) {
						if (!$a['sort_order']) $a['sort_order'] = 0;
						if (!$b['sort_order']) $b['sort_order'] = 0;
						return $a['sort_order'] - $b['sort_order'];
					});
					$data['theme_mt_contacts'][$index]['messengers'] = $messengers;
				}
			}
			$data['main_contact'] = $data['theme_mt_contacts'];
		}
		
		if ($product_info['variation'] != '') {
			$data['variable_width'] = $this->config->get('theme_' . $this->config->get('config_theme') . '_image_additional_width');
			$data['variable_height'] = $this->config->get('theme_' . $this->config->get('config_theme') . '_image_additional_height');
			foreach (explode(PHP_EOL, $product_info['variation']) as $product_title) {
				if ($product_title == '') continue;
				$product_title = trim($product_title);

				$query = $this->db->query("SELECT DISTINCT product_id FROM " . DB_PREFIX . "product_description WHERE name LIKE '%$product_title%'");
				foreach ($query->rows as $row) {
					if (!isset($data['variable'][$row['product_id']])) {
						$result = $this->model_catalog_product->getProduct($row['product_id']);
						if ($result['image']) {
							$image = $this->model_tool_image->resize($result['image'], null, null);
						} else {
							$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_additional_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_additional_height'), (isset($data['theme_mt_image_additional_checked_pole']) ? 'pole' : ''));
						}
			
						$data['variable'][$result['product_id']] = array(
							'product_id'  => $result['product_id'],
							'quantity'    => $result['quantity'],
							'thumb'       => $image,
							'name'        => $result['name'],
							'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
						);
					}
				}
			}
		}

		$data['options'] = array();

		foreach ($this->model_catalog_product->getProductOptions($this->request->get['product_id']) as $option) {
			$product_option_value_data = array();

			foreach ($option['product_option_value'] as $option_value) {
				if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
					if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$option_value['price']) {
						$price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax') ? 'P' : false), $this->session->data['currency']);
					} else {
						$price = false;
					}

					$product_option_value_data[] = array(
						'product_option_value_id' => $option_value['product_option_value_id'],
						'option_value_id'         => $option_value['option_value_id'],
						'name'                    => $option_value['name'],
						'image'                   => $this->model_tool_image->resize($option_value['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_option_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_option_height'), (isset($data['theme_mt_image_option_checked_pole']) ? 'pole' : '')),
						'price'                   => $price,
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
		

		$this->load->model('localisation/currency');
		$currency_value = $this->model_localisation_currency->getCurrencyByCode($this->config->get('config_currency'));

		$sql = 'select * from '.DB_PREFIX.'product_discount where product_id = '.$product_info['product_id'].' and (date_start <= "'.date('Y-m-d').'" or date_start = "0000-00-00") and (date_end >= "'.date('Y-m-d').'" or date_end = "0000-00-00") order by priority asc, quantity desc limit 1';
		$res = $this->db->query($sql);
		$discount = null;
		if ($res->rows && isset($data['theme_mt_category_product_sticker_discount']) && $data['theme_mt_category_product_sticker_discount']) {
			$discount = $res->row;
			if ($discount && isset($discount['price']) && $discount['price'] > 0) {
				$discount = round((($discount['price'] - $product_info['price'])/$product_info['price'])*100)."% скидка от ".$discount['quantity']." шт.";
			}
		}
		$data['discount_check'] = $discount;
		$sql = 'select * from '.DB_PREFIX.'product_special where product_id = '.$product_info['product_id'].' and (date_start <= "'.date('Y-m-d').'" or date_start = "0000-00-00") and (date_end >= "'.date('Y-m-d').'" or date_end = "0000-00-00") order by priority asc limit 1';
		$res = $this->db->query($sql);
		$special_check = null;
		if ($res->rows && isset($data['theme_mt_category_product_sticker_special']) && $data['theme_mt_category_product_sticker_special']) {
			$special_check = $res->row;
			if ($special_check && isset($special_check['price']) && $special_check['price'] > 0) {
				$special_check = round((($special_check['price'] - $product_info['price'])/$product_info['price'])*100);
			}
		}
		$data['special_check'] = $special_check;

		$this->load->model('catalog/information');
		$information_info = $this->model_catalog_information->getInformation($data['theme_mt_product_delivery_id']);
		if (!isset($information_info['description'])) $information_info['description'] = '';
		$information_info['description'] = html_entity_decode($information_info['description']);
		if (isset($information_info['title']))
			$data['delivery_information_title'] = $information_info['title'];
		$information_info['heading_title'] = '';
		$information_info['header'] = '';
		$data['delivery_information'] = $this->load->view('information/information', $information_info);

		$information_info = $this->model_catalog_information->getInformation($data['theme_mt_product_payment_id']);
		if (isset($information_info['description']))
			$information_info['description'] = html_entity_decode($information_info['description']);
		if (isset($information_info['title']))
			$data['payment_information_title'] = $information_info['title'];
		$information_info['heading_title'] = '';
		$information_info['header'] = '';
		$data['payment_information'] = $this->load->view('information/information', $information_info);
		
		$data['logged'] = $this->customer->isLogged();
		$data['content_before_price'] = $this->load->controller('mt/common/content_before_price');
		$data['content_after_price'] = $this->load->controller('mt/common/content_after_price');
		$data['content_after_buttons'] = $this->load->controller('mt/common/content_after_buttons');
		$data['content_after_text'] = $this->load->controller('mt/common/content_after_text');
		$data['content_before_desc'] = $this->load->controller('mt/common/content_before_desc');
		$data['content_after_desc'] = $this->load->controller('mt/common/content_after_desc');
		$data['content_before_attr'] = $this->load->controller('mt/common/content_before_attr');
		$data['content_after_attr'] = $this->load->controller('mt/common/content_after_attr');

		return $data;
   }

   public function review() {
		$this->load->language('product/product');

		$this->load->model('catalog/review');

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

      $data = [];

      $this->load->model('setting/setting');
		$data += $this->model_setting_setting->getSetting('theme_mt');
		foreach ($data as $index => $d) {
			if (is_string($d))
				$data[$index] = html_entity_decode($d, ENT_QUOTES, 'UTF-8');
		}

		$data['reviews'] = array();

		$review_total = $this->model_catalog_review->getTotalReviewsByProductId($this->request->get['id']);

		$results = $this->model_catalog_review->getReviewsByProductId($this->request->get['id'], ($page - 1) * 5, 5);

		foreach ($results as $result) {
			$data['reviews'][] = array(
				'author'     => $result['author'],
				'text'       => nl2br($result['text']),
				'rating'     => (int)$result['rating'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}


		$pagination = new MtPagination();
		$pagination->total = $review_total;
		$pagination->page = $page;
		$pagination->limit = 5;
		$pagination->url = $this->url->link('product/product/review', 'product_id=' . $this->request->get['id'] . '&page={page}');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($review_total) ? (($page - 1) * 5) + 1 : 0, ((($page - 1) * 5) > ($review_total - 5)) ? $review_total : ((($page - 1) * 5) + 5), $review_total, ceil($review_total / 5));

		$this->response->setOutput($this->load->view('product/review', $data));
	}

   public function oneClick() {
		$redirect = '';
		if (isset($this->request->post['shipping_method']) && $this->request->post['shipping_method'] != '' && $this->request->post['shipping_code'] != '') {
         if (!$this->request->post['shipping_cost']) $this->request->post['shipping_cost'] = 0;
			$this->session->data['shipping_method'] = array(
				'title' => $this->request->post['shipping_method'],
				'code' => $this->request->post['shipping_code'],
				'cost' => $this->request->post['shipping_cost'],
				'tax_class_id' => $this->request->post['shipping_tax_class_id'],
			);
		}
		if (isset($this->request->post['payment_method']) && $this->request->post['payment_method'] != '' && $this->request->post['payment_method'] != '') {
			$this->session->data['payment_method'] = array(
				'title' => $this->request->post['payment_method'],
				'code' => $this->request->post['payment_code'],
			);
		}
		// if ($this->cart->hasShipping()) {
		// 	// Validate if shipping address has been set.
		// 	// if (!isset($this->session->data['shipping_address'])) {
		// 	// 	$redirect = $this->url->link('checkout/checkout', '', true);
		// 	// }

		// 	// Validate if shipping method has been set.
		// 	if (!isset($this->session->data['shipping_method'])) {
		// 		$redirect = $this->url->link('checkout/checkout', '', true);
		// 	}
		// } else {
		// 	unset($this->session->data['shipping_address']);
		// 	unset($this->session->data['shipping_method']);
		// 	unset($this->session->data['shipping_methods']);
		// }
      
		// // Validate if payment address has been set.
		// if (!isset($this->session->data['payment_address'])) {
		// 	$redirect = $this->url->link('checkout/checkout', '', true);
		// }

		// // Validate if payment method has been set.
		// if (!isset($this->session->data['payment_method'])) {
		// 	$redirect = $this->url->link('checkout/checkout', '', true);
		// }

		// Validate cart has products and has stock.
		// if ((!$this->cart->hasProducts() )) {
		// 	$redirect = $this->url->link('checkout/cart');
		// }
		

		// Validate minimum quantity requirements.
		if ($this->request->post['route'] != 'product/product')
      	$this->cart->add($this->request->post['oneclick_product']);
		$products = $this->cart->getProducts();
		foreach ($products as $product_index => $product) {
			
			$product_total = 0;

			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}

			if ($product['minimum'] > $product_total) {
				$redirect = $this->url->link('checkout/cart');

				break;
			}
		}
		
		if (!$redirect) {
			$order_data = array();

			$totals = array();
			$taxes = $this->cart->getTaxes();
			$total = 0;

			// Because __call can not keep var references so we put them into an array.
			$total_data = array(
				'totals' => &$totals,
				'taxes'  => &$taxes,
				'total'  => &$total
			);

			$this->load->model('setting/extension');

			$sort_order = array();

			$results = $this->model_setting_extension->getExtensions('total');

			foreach ($results as $key => $value) {
				$sort_order[$key] = $this->config->get('total_' . $value['code'] . '_sort_order');
			}

			array_multisort($sort_order, SORT_ASC, $results);

			foreach ($results as $result) {
				if ($this->config->get('total_' . $result['code'] . '_status')) {
					$this->load->model('extension/total/' . $result['code']);

					// We have to put the totals in an array so that they pass by reference.
					$this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
				}
			}

			$sort_order = array();

			foreach ($totals as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}
         
			array_multisort($sort_order, SORT_ASC, $totals);

			$order_data['totals'] = $totals;
			
			$this->load->language('checkout/checkout');

			$order_data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
			$order_data['store_id'] = $this->config->get('config_store_id');
			$order_data['store_name'] = $this->config->get('config_name');

			if ($order_data['store_id']) {
				$order_data['store_url'] = $this->config->get('config_url');
			} else {
				if ($this->request->server['HTTPS']) {
					$order_data['store_url'] = HTTPS_SERVER;
				} else {
					$order_data['store_url'] = HTTP_SERVER;
				}
			}
			
			$this->load->model('account/customer');

			$order_data['customer_id'] = 0;
			$order_data['customer_group_id'] = 0;
			$order_data['firstname'] = $this->request->post['firstname'];
			$order_data['lastname'] = '';
			$order_data['email'] = $this->config->get('config_email');
			$order_data['telephone'] = $this->request->post['phone'];
			$order_data['custom_field'] = $this->request->post['text'];

			$order_data['payment_firstname'] = $this->request->post['firstname'];
			$order_data['payment_lastname'] = '';
			$order_data['payment_company'] = '';
			$order_data['payment_address_1'] = '';
			$order_data['payment_address_2'] = '';
			$order_data['payment_city'] = '';
			$order_data['payment_postcode'] = '';
			$order_data['payment_zone'] = '';
			$order_data['payment_zone_id'] = '';
			$order_data['payment_country'] = '';
			$order_data['payment_country_id'] = '';
			$order_data['payment_address_format'] = '';
			$order_data['payment_custom_field'] = array();

			if (isset($this->session->data['payment_method']['title'])) {
				$order_data['payment_method'] = $this->session->data['payment_method']['title'];
			} else {
				$order_data['payment_method'] = '';
			}

			if (isset($this->session->data['payment_method']['code'])) {
				$order_data['payment_code'] = $this->session->data['payment_method']['code'];
			} else {
				$order_data['payment_code'] = '';
			}

			if ($this->cart->hasShipping()) {
				$order_data['shipping_firstname'] = $this->request->post['firstname'];
				$order_data['shipping_lastname'] = '';
				$order_data['shipping_company'] = '';
				$order_data['shipping_address_1'] = '';
				$order_data['shipping_address_2'] = '';
				$order_data['shipping_city'] = '';
				$order_data['shipping_postcode'] = '';
				$order_data['shipping_zone'] = '';
				$order_data['shipping_zone_id'] = '';
				$order_data['shipping_country'] = '';
				$order_data['shipping_country_id'] = '';
				$order_data['shipping_address_format'] = '';
				$order_data['shipping_custom_field'] = array();

				if (isset($this->session->data['shipping_method']['title'])) {
					$order_data['shipping_method'] = $this->session->data['shipping_method']['title'];
				} else {
					$order_data['shipping_method'] = '';
				}

				if (isset($this->session->data['shipping_method']['code'])) {
					$order_data['shipping_code'] = $this->session->data['shipping_method']['code'];
				} else {
					$order_data['shipping_code'] = '';
				}
			} else {
				$order_data['shipping_firstname'] = '';
				$order_data['shipping_lastname'] = '';
				$order_data['shipping_company'] = '';
				$order_data['shipping_address_1'] = '';
				$order_data['shipping_address_2'] = '';
				$order_data['shipping_city'] = '';
				$order_data['shipping_postcode'] = '';
				$order_data['shipping_zone'] = '';
				$order_data['shipping_zone_id'] = '';
				$order_data['shipping_country'] = '';
				$order_data['shipping_country_id'] = '';
				$order_data['shipping_address_format'] = '';
				$order_data['shipping_custom_field'] = array();
				$order_data['shipping_method'] = '';
				$order_data['shipping_code'] = '';
			}

			$order_data['products'] = array();
         
			foreach ($this->cart->getProducts() as $product) {
				$option_data = array();

				foreach ($product['option'] as $option) {
					$option_data[] = array(
						'product_option_id'       => $option['product_option_id'],
						'product_option_value_id' => $option['product_option_value_id'],
						'option_id'               => $option['option_id'],
						'option_value_id'         => $option['option_value_id'],
						'name'                    => $option['name'],
						'value'                   => $option['value'],
						'type'                    => $option['type']
					);
				}

				$order_data['products'][] = array(
					'product_id' => $product['product_id'],
					'name'       => $product['name'],
					'model'      => $product['model'],
					'option'     => $option_data,
					'download'   => $product['download'],
					'quantity'   => $product['quantity'],
					'subtract'   => $product['subtract'],
					'price'      => $product['price'],
					'total'      => $product['total'],
					'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
					'reward'     => $product['reward']
				);
			}
			// Gift Voucher
			$order_data['vouchers'] = array();

			if (!empty($this->session->data['vouchers'])) {
				foreach ($this->session->data['vouchers'] as $voucher) {
					$order_data['vouchers'][] = array(
						'description'      => $voucher['description'],
						'code'             => token(10),
						'to_name'          => $voucher['to_name'],
						'to_email'         => $voucher['to_email'],
						'from_name'        => $voucher['from_name'],
						'from_email'       => $voucher['from_email'],
						'voucher_theme_id' => $voucher['voucher_theme_id'],
						'message'          => $voucher['message'],
						'amount'           => $voucher['amount']
					);
				}
			}

			$order_data['comment'] = $this->request->post['text'];
			$order_data['total'] = $total_data['total'];

			if (isset($this->request->cookie['tracking'])) {
				$order_data['tracking'] = $this->request->cookie['tracking'];

				$subtotal = $this->cart->getSubTotal();

				// Affiliate
				$affiliate_info = $this->model_account_customer->getAffiliateByTracking($this->request->cookie['tracking']);

				if ($affiliate_info) {
					$order_data['affiliate_id'] = $affiliate_info['customer_id'];
					$order_data['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
				} else {
					$order_data['affiliate_id'] = 0;
					$order_data['commission'] = 0;
				}

				// Marketing
				$this->load->model('checkout/marketing');

				$marketing_info = $this->model_checkout_marketing->getMarketingByCode($this->request->cookie['tracking']);

				if ($marketing_info) {
					$order_data['marketing_id'] = $marketing_info['marketing_id'];
				} else {
					$order_data['marketing_id'] = 0;
				}
			} else {
				$order_data['affiliate_id'] = 0;
				$order_data['commission'] = 0;
				$order_data['marketing_id'] = 0;
				$order_data['tracking'] = '';
			}

			$order_data['language_id'] = $this->config->get('config_language_id');
			$order_data['currency_id'] = $this->currency->getId($this->session->data['currency']);
			$order_data['currency_code'] = $this->session->data['currency'];
			$order_data['currency_value'] = $this->currency->getValue($this->session->data['currency']);
			$order_data['ip'] = $this->request->server['REMOTE_ADDR'];

			if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
				$order_data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];
			} elseif (!empty($this->request->server['HTTP_CLIENT_IP'])) {
				$order_data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];
			} else {
				$order_data['forwarded_ip'] = '';
			}

			if (isset($this->request->server['HTTP_USER_AGENT'])) {
				$order_data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];
			} else {
				$order_data['user_agent'] = '';
			}

			if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
				$order_data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];
			} else {
				$order_data['accept_language'] = '';
			}
			
			$this->load->model('checkout/order');
			$this->session->data['order_id'] = $this->model_checkout_order->addOrder($order_data);
			
			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], 1, 'Быстрый заказ', true);
			foreach ($this->cart->getProducts() as $product) {
				$this->cart->remove($product['cart_id']);
			}
			$redirect = $this->url->link('checkout/success');
		} else {
			$data['redirect'] = $redirect;
		}
      
		$this->response->redirect($redirect);
	}

	public function consult() {
		$mail = new Mail($this->config->get('config_mail_engine'));
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

		$mail->setTo($this->config->get('config_email'));
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender($this->config->get('config_name'));
		$mail->setSubject('Форма обратной связи с сайта');
		$mail->setHtml($this->load->view('mail/consult', $this->request->post));
		$emails = explode(',', $this->config->get('config_mail_alert_email'));

		foreach ($emails as $email) {
			$email = trim($email);
			if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$mail->setTo($email);
				$mail->send();
			}
		}
		$mail->send();
		
		$this->response->redirect($this->url->link('information/contact/success'));
	}
	
	public function callback() {
		$mail = new Mail($this->config->get('config_mail_engine'));
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

		$mail->setTo($this->config->get('config_email'));
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender($this->config->get('config_name'));
		$mail->setSubject('Обратный звонок с сайта');
		$mail->setHtml($this->load->view('mail/callback', $this->request->post));
		$emails = explode(',', $this->config->get('config_mail_alert_email'));

		foreach ($emails as $email) {
			$email = trim($email);
			if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$mail->setTo($email);
				$mail->send();
			}
		}
		$mail->send();
		
		$this->response->redirect($this->url->link('information/contact/success'));
	}
}