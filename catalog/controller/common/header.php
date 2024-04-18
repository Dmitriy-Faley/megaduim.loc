<?php
class ControllerCommonHeader extends Controller {
	public function index() {
		// Analytics
		$this->load->model('setting/extension');

		// Загружаем модель каталога производителей
		$this->load->model('catalog/manufacturer');

		// Получаем список производителей
        $data['manufacturers'] = array();

		$this->load->model('tool/image');

        $results = $this->model_catalog_manufacturer->getManufacturers();

        foreach ($results as $result) {

			if ($result['image']) {
				$image = $this->model_tool_image->resize($result['image'], null, null);
			} else {
				$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
			}

            $data['manufacturers'][] = array(
				'thumb'  => $image,
                'name' => $result['name'],
                'description' => html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'),
				'href' 		=> $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $result['manufacturer_id'])
                // Другие данные производителя, которые вы хотите вывести
            );
        }
		

		$data['analytics'] = array();

		$analytics = $this->model_setting_extension->getExtensions('analytics');

		foreach ($analytics as $analytic) {
			if ($this->config->get('analytics_' . $analytic['code'] . '_status')) {
				$data['analytics'][] = $this->load->controller('extension/analytics/' . $analytic['code'], $this->config->get('analytics_' . $analytic['code'] . '_status'));
			}
		}

		if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

		if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
			$this->document->addLink($server . 'image/' . $this->config->get('config_icon'), 'icon');
		}

		$data['title'] = $this->document->getTitle();

		$data['base'] = $server;
		$data['description'] = $this->document->getDescription();
		$data['keywords'] = $this->document->getKeywords();
		$data['links'] = $this->document->getLinks();
		$data['styles'] = $this->document->getStyles();
		$data['scripts'] = $this->document->getScripts('header');
		$data['lang'] = $this->language->get('code');
		$data['direction'] = $this->language->get('direction');

		$data['name'] = $this->config->get('config_name');

		if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			$data['logo'] = $server . 'image/' . $this->config->get('config_logo');
		} else {
			$data['logo'] = '';
		}

		$this->load->language('common/header');
 
		// Wishlist
		if ($this->customer->isLogged()) {
			$this->load->model('account/wishlist');

			$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), $this->model_account_wishlist->getTotalWishlist());
		} else {
			$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
		}
		$incart = $this->cart->getProducts();
		$data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', true), $this->customer->getFirstName(), $this->url->link('account/logout', '', true));
		
		$data['home'] = $this->url->link('common/home');
		$data['wishlist'] = $this->url->link('account/wishlist', '', true);
		$data['logged'] = $this->customer->isLogged();
		$data['account'] = $this->url->link('account/account', '', true);
		$data['register'] = $this->url->link('account/register', '', true);
		$data['login'] = $this->url->link('account/login', '', true);
		$data['order'] = $this->url->link('account/order', '', true);
		$data['transaction'] = $this->url->link('account/transaction', '', true);
		$data['download'] = $this->url->link('account/download', '', true);
		$data['logout'] = $this->url->link('account/logout', '', true);
		$data['shopping_cart'] = $this->url->link('checkout/cart');
		$data['checkout'] = $this->url->link('checkout/checkout', '', true);
		$data['contact'] = $this->url->link('information/contact');
		$data['telephone'] = $this->config->get('config_telephone');

		$data['text_search_history'] = $this->language->get('text_search_history');
		$data['search_history'] = $this->url->link('account/search_history', '', true);

		
		$data['language'] = $this->load->controller('common/language');
		$data['currency'] = $this->load->controller('common/currency');
		$data['search'] = $this->load->controller('common/search');
		$data['cart'] = $this->load->controller('common/cart');
		$data['menu'] = $this->load->controller('common/menu');


		$this->load->model('catalog/product');
		$this->load->model('catalog/category');

		

		$categories = $this->model_catalog_category->getCategories(0);
		foreach ($categories as $key=>$category) {
			if( $key <=5) {
				if ($category['top']) {
					// Level 2
					$children_data = array();

					$children = $this->model_catalog_category->getCategories($category['category_id']);

					foreach ($children as $child) {
						$filter_data = array(
							'filter_category_id'  => $child['category_id'],
							'filter_sub_category' => true
						);

						$children_data[] = array(
							'name'  => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
							'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id']),
							'nameNonum'  => $child['name']
						);
					}

					// Level 1
					$data['categoriesq'][] = array(
						'name'     => $category['name'],
						'children' => $children_data,
						'column'   => $category['column'] ? $category['column'] : 1,
						'href'     => $this->url->link('product/category', 'path=' . $category['category_id']),
					);
				}
			}
		}
		//$this->load->model('catalog/product');

		$data['products'] = array();

		$results = $this->model_catalog_product->getProducts(array('start' => 0, 'limit' => 3)); // Получаем первые три товара

		foreach ($results as $result) {
			if ($result['image']) {
               $image = $this->model_tool_image->resize($result['image'], null, null);
            } else {
               $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
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
               $rating = (int)$result['rating'];
            } else {
               $rating = false;
            }

				$sql = 'select * from '.DB_PREFIX.'product_discount where product_id = '.$result['product_id'].' and (date_start <= "'.date('Y-m-d').'" or date_start = "0000-00-00") and (date_end >= "'.date('Y-m-d').'" or date_end = "0000-00-00") order by priority asc, quantity desc limit 1';
				$res = $this->db->query($sql);
				$discount = null;
				if ($res->rows) {
					$discount = $res->row;
					if ($discount && isset($discount['price']) && $discount['price'] > 0) {
						$discount = round((($discount['price'] - $result['price'])/$result['price'])*100)."% скидка от ".$discount['quantity']." шт.";
					}
				}
				$data['discount_check'] = $discount;
				$sql = 'select * from '.DB_PREFIX.'product_special where product_id = '.$result['product_id'].' and (date_start <= "'.date('Y-m-d').'" or date_start = "0000-00-00") and (date_end >= "'.date('Y-m-d').'" or date_end = "0000-00-00") order by priority asc limit 1';
				$res = $this->db->query($sql);
				$special_check = null;
				if ($res->rows) {
					$special_check = $res->row;
					if ($special_check && isset($special_check['price']) && $special_check['price'] > 0) {
						$special_check = round((($special_check['price'] - $result['price'])/$result['price'])*100);
					}
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
			
			$data['images'] = array();

			$dops = $this->model_catalog_product->getProductImages($result['product_id']);

			foreach ($dops as $dop) {
				$data['images'][] = array(
					'thumb' => $this->model_tool_image->resize($dop['image'], null, null)
				);
			}

			$data['attribute_groups'] = $this->model_catalog_product->getProductAttributes($result['product_id']);
            
            $data['products'][] = array( 
               'product_id'  => $result['product_id'],
				'special_check' => $special_check,
            	'discount'    => $discount,
               'in_wishlist' => (in_array($result['product_id'], array_column($wishlist, 'product_id')) ? 1 : 0),
			   'in_cart'     => (in_array($result['product_id'], array_column($incart, 'product_id')) ? 1 : 0),
			   'attributes'  => $attributes,
			   'attrblock'   => $data['attribute_groups'],
			   'option'      => $data['options'], 
               'thumb'       => $image,
			   'imgDop'      => $data['images'],
               'name'        => $result['name'],
               'quantity'    => $result['quantity'],
               'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')),
               'price'       => $price,
               'special'     => $special,
               'tax'         => $tax,
               'stock'       => $result['stock_status'],
               'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
               'rating'      => $result['rating'],
               'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'] . $url),
			   'hrefPiece'        => $this->url->link('product/product', 'product_id=')
            );

		}



		return $this->load->view('common/header', $data);
	}
}
