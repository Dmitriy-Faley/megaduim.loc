<?php
class ControllerMTProductManufacturer extends Controller {
	public function index() {
      $this->document->addStyle('catalog/view/theme/mt/stylesheet/category.css');
	  $this->document->addStyle('catalog/view/theme/mt/stylesheet/brands.css');
		
		$data = [];

      $this->load->model('setting/setting');
		$data += $this->model_setting_setting->getSetting('theme_mt');
		foreach ($data as $index => $d) {
			if (is_string($d))
				$data[$index] = html_entity_decode($d, ENT_QUOTES, 'UTF-8');
		}
		
      	$this->load->language('product/manufacturer');

		$this->load->model('catalog/manufacturer');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		$incart = $this->cart->getProducts();

		if (isset($this->request->get['manufacturer_id'])) {
			$manufacturer_id = (int)$this->request->get['manufacturer_id'];
		} else {
			$manufacturer_id = 0;
		}
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else if (isset($data['theme_mt_category_sort_default'])) {
			$sort = $data['theme_mt_category_sort_default'];
		}
      else $sort = 'p.price';
      $data['sort'] = $sort;

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
      $data['order'] = $order;

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($data['theme_mt_category_product_limit_checked']) && $data['theme_mt_category_product_limit_checked'])
         $limit = $data['theme_mt_category_product_limit'];
      else $limit = 12;

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_brand'),
			'href' => $this->url->link('product/manufacturer')
		);

		$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);

		if ($manufacturer_info) {
			$this->document->setTitle($manufacturer_info['name']);

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $manufacturer_info['name'],
				'href' => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . $url)
			);

			$data['heading_title'] = $manufacturer_info['name'];

			$data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));

			$data['compare'] = $this->url->link('product/compare');

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

			$this->load->model('account/wishlist');
			$wishlist = $this->model_account_wishlist->getWishlist();

			$data['products'] = array();

			$filter_data = array(
				'filter_manufacturer_id' => $manufacturer_id,
				'sort'                   => $sort,
				'order'                  => $order,
				'start'                  => ($page - 1) * $limit,
				'limit'                  => $limit
			);

			$product_total = $this->model_catalog_product->getTotalProducts($filter_data);

			$results = $this->model_catalog_product->getProducts($filter_data);

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

				$data['images'] = array();
				$dops = $this->model_catalog_product->getProductImages($result['product_id']);

				foreach ($dops as $dop) {
					$data['images'][] = array(
						'thumb' => $this->model_tool_image->resize($dop['image'], null, null)
					);
				}

				$data['options'] = array();
				foreach ($this->model_catalog_product->getProductOptions($result['product_id']) as $option) {
					$product_option_value_data = array();
					foreach ($option['product_option_value'] as $option_value) {
						if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
							if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$option_value['price']) {
								$price = $this->currency->format($this->tax->calculate($option_value['price'], $result['tax_class_id'], $this->config->get('config_tax') ? 'P' : false), $this->session->data['currency']);
							} else {
								$price = false;
							}
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
				$data['attribute_groups'] = $this->model_catalog_product->getProductAttributes($result['product_id']);

				$data['products'][] = array(
					'product_id'  => $result['product_id'],
					'in_wishlist' => (in_array($result['product_id'], array_column($wishlist, 'product_id')) ? 1 : 0),
					'in_cart'     => (in_array($result['product_id'], array_column($incart, 'product_id')) ? 1 : 0),
					'special_check' => $special_check,
					'discount'    => $discount,
					'thumb'       => $image,
					'imgDop'      => $data['images'],
					'attrblock'   => $data['attribute_groups'],
					'option'      => $data['options'],
					'name'        => $result['name'],
					'quantity'    => $result['quantity'],
					'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')),
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'stock'       => $result['stock_status'],
					'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
					'rating'      => $result['rating'],
					//'href'        => $this->url->link('product/product', 'manufacturer_id=' . $result['manufacturer_id'] . '&product_id=' . $result['product_id'] . $url)
					'href'        => $this->url->link('product/product' . '&product_id=' . $result['product_id'] . $url),
					'hrefPiece'   => $this->url->link('product/product', 'manufacturer_id=' . $result['manufacturer_id'] . '&product_id=')
				);
			}

			$url = '';

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['sorts'] = array();
         if ($data['order'] == 'ASC') $data['order'] = 'DESC';
         else $data['order'] = 'ASC';
         if (isset($data['theme_mt_category_sort_price_checked']) && $data['theme_mt_category_sort_price_checked']) {
            $data['sorts'][] = array(
               'code'  => 'p.price',
               'text'  => $data['theme_mt_category_sort_price_title'],
               'value' => 'p.price-'.$data['order'],
               'href'  => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . '&sort=p.price&order='. $data['order'] . '' . $url)
            );
         }
         if (isset($data['theme_mt_category_sort_popular_checked']) && $data['theme_mt_category_sort_popular_checked']) {
            $data['sorts'][] = array(
               'code'  => 'rating',
               'text'  => $data['theme_mt_category_sort_popular_title'],
               'value' => 'rating-'.$data['order'],
               'href'  => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . '&sort=rating&order='. $data['order'] . '' . $url)
            );
         }
         if (isset($data['theme_mt_category_sort_special_checked']) && $data['theme_mt_category_sort_special_checked']) {
            $data['sorts'][] = array(
               'code'  => 'p.special',
               'text'  => $data['theme_mt_category_sort_special_title'],
               'value' => 'p.special-'.$data['order'],
               'href'  => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . '&sort=p.special&order='.$data['order'] . '' . $url)
            );
         }
         if (isset($data['theme_mt_category_sort_name_checked']) && $data['theme_mt_category_sort_name_checked']) {
            $data['sorts'][] = array(
               'code'  => 'pd.name',
               'text'  => $data['theme_mt_category_sort_name_title'],
               'value' => 'pd.name-'.$data['order'],
               'href'  => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . '&sort=pd.name&order='. $data['order'] . '' . $url)
            );
         }
         if (isset($data['theme_mt_category_sort_date_checked']) && $data['theme_mt_category_sort_date_checked']) {
            $data['sorts'][] = array(
               'code'  => 'p.date_added',
               'text'  => $data['theme_mt_category_sort_date_title'],
               'value' => 'p.date_added-'.$data['order'],
               'href'  => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . '&sort=p.date_added&order='. $data['order'] . '' . $url)
            );
         }

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['pagCustom'] = array(
				'url' => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . $url . '&page=' . ($page + 1)),
				'last' => ceil($product_total / $limit),
				'page' => $page
			);

			$this->load->library('mtpagination');
			$pagination = new MtPagination();
			$pagination->total = $product_total;
			$pagination->page = $page;
			$pagination->num_links = 4;
			$pagination->limit = $limit;
			$pagination->url = $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . $url . '&page={page}');
			$data['pagination'] = $pagination->render();

			$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));

			// http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html
			if ($page == 1) {
				$this->document->addLink($this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id']), 'canonical');
			} else {
				$this->document->addLink($this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . '&page=' . $page), 'canonical');
			}
			
			if ($page > 1) {
				$this->document->addLink($this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . (($page - 2) ? '&page=' . ($page - 1) : '')), 'prev');
			}

			if ($limit && ceil($product_total / $limit) > $page) {
				$this->document->addLink($this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . '&page=' . ($page + 1)), 'next');
			}
		}

		if (isset($this->request->post['description'])) {
			$data['description'] = $this->request->post['description'];
		} elseif (isset($this->request->get['manufacturer_id'])) {
			$data['description'] = '';
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer_description WHERE language_id = " . $this->config->get('config_language_id') . " AND manufacturer_id = " . (int)$this->request->get['manufacturer_id']);

			foreach ($query->rows as $result) {
				$data['description'] = html_entity_decode($result['description']);
			}
		} else {
			$data['description'] = '';
		}

		if (isset($this->request->get['manufacturer_id'])) {
			$data['meta_title'] = [];
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer_description WHERE language_id = " . $this->config->get('config_language_id') . " AND manufacturer_id = " . (int)$this->request->get['manufacturer_id']);

			foreach ($query->rows as $result) {
				$this->document->setTitle($result['meta_title']);
				$data['meta_h1'] = $result['meta_h1'];
			}
		}
		
		if (isset($this->request->get['manufacturer_id'])) {
			$data['meta_description'] = [];
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer_description WHERE language_id = " . $this->config->get('config_language_id') . " AND manufacturer_id = " . (int)$this->request->get['manufacturer_id']);

			foreach ($query->rows as $result) {
				$this->document->setDescription($result['meta_description']);
			}
		}
		
		if (isset($this->request->get['manufacturer_id'])) {
			$data['meta_keyword'] = [];
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer_description WHERE language_id = " . $this->config->get('config_language_id') . " AND manufacturer_id = " . (int)$this->request->get['manufacturer_id']);

			foreach ($query->rows as $result) {
				$this->document->setKeywords($result['meta_keyword']);
			}
		}

      $data['logged'] = $this->customer->isLogged();

      return $data;
   }
}