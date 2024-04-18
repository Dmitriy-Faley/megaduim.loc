<?php
class ControllerMTProductCategory extends Controller {
	public function index($data) {
      $this->load->language('product/product');
      
      $this->document->addStyle('catalog/view/theme/mt/stylesheet/category.css');

      $this->load->model('catalog/category');
      $this->load->model('tool/image');

      $parts = explode('_', (string)$this->request->get['path']);

      $category_id = (int)array_pop($parts);

      $category_info = $this->model_catalog_category->getCategory($category_id);

      $this->load->model('setting/setting');
		$data += $this->model_setting_setting->getSetting('theme_mt');
		foreach ($data as $index => $d) {
			if (is_string($d))
				$data[$index] = html_entity_decode($d, ENT_QUOTES, 'UTF-8');
		}

      if (isset($this->request->get['filter'])) {
			$filter = $this->request->get['filter'];
		} else {
			$filter = ''; 
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

		if (isset($this->request->get['limit'])) {
			$limit = (int)$this->request->get['limit'];
		} else {
			$limit = $this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit');
		}

      $parts = explode('_', (string)$this->request->get['path']);
      $category_id = (int)array_pop($parts);

      $url = '';

      if (isset($this->request->get['filter'])) {
         $url .= '&filter=' . $this->request->get['filter'];
      }

      if (isset($this->request->get['sort'])) {
         $url .= '&sort=' . $this->request->get['sort'];
      }

      if (isset($this->request->get['order'])) {
         $url .= '&order=' . $this->request->get['order'];
      }

      if (isset($this->request->get['limit'])) {
         $url .= '&limit=' . $this->request->get['limit'];
      }

      /*
      $data['top_categories'] = array();
      $results = $this->model_catalog_category->getCategories($category_id);
      foreach ($results as $result) {
         $filter_data = array(
            'filter_category_id'  => $result['category_id'],
            'filter_sub_category' => true
         );

         $data['top_categories'][] = array(
            'name' => $result['name'],
            'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '_' . $result['category_id'] . $url),
            'image' => $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_category_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_category_height'), (isset($data['theme_mt_image_category_checked_pole']) ? 'pole' : '')),
         );
      }
      */

      $categoriesCat = $this->model_catalog_category->getCategories($category_id);
		
		foreach ($categoriesCat as $key=>$categoryHome) {
			if( $key <=5) { 
				if ($categoryHome['top']) {
					// Level 2
					$children_data = array();
					$children = $this->model_catalog_category->getCategories($categoryHome['category_id']);

					foreach ($children as $child) {
						$twoCat = array();
						
						$childrenTwo = $this->model_catalog_category->getCategories($child['category_id']);

						$filter_data = array(
							'filter_category_id'  => $child['category_id'],
							'filter_sub_category' => true
						);

						foreach ($childrenTwo as $two) {
							$twoCat[] = array(
								'childrenId' => $two['category_id'],
								'name'  => $two['name'],
								'href'  => $this->url->link('product/category', 'path=' . $categoryHome['category_id'] . '_' . $child['category_id'] . '_' . $two['category_id']),
								'nameNonum'  => $two['name'],
							);
						}

						
						$children_data[] = array(
							'childrenId' => $child['category_id'],
							'name'  => $child['name'],
							'href'  => $this->url->link('product/category', 'path=' . $categoryHome['category_id'] . '_' . $child['category_id']),
							'nameNonum'  => $child['name'],
							'childrenTwo'  => $twoCat,
						);
					}

					// Level 1
					$data['categoriesCat'][] = array(
						'categoryId' => $categoryHome['category_id'],
						'name'     => $categoryHome['name'],
						'children' => $children_data,
						'column'   => $categoryHome['column'] ? $categoryHome['column'] : 1,
						'href'     => $this->url->link('product/category', 'path=' . $categoryHome['category_id']),
					);
					
				}
			}
		}

      $filter_data = array(
         'filter_category_id' => $category_id,
         'filter_sub_category' => true,
         'filter_filter'      => $filter, //Для сортировки по цене
         'sort'               => $sort, //Для сортировки по цене
         'order'              => $order,
         'start'              => ($page - 1) * $limit,
       //  'limit'              => $limit //Лимит задается в настройках темы
      );
      
      if (isset($_GET['filter_manufacturer_id'])) {
         $filter_data['filter_manufacturer_id'] = $_GET['filter_manufacturer_id'];
      }

      $product_total = $this->model_catalog_product->getTotalProducts($filter_data);

      $results = $this->model_catalog_product->getProducts($filter_data);

      $data['manufacturers'] = [];
      foreach ($results as $result) {
         if ($result['manufacturer'] != '' && !isset($data['manufacturers'][$result['manufacturer_id']]))
         $data['manufacturers'][$result['manufacturer_id']] = array(
            'name' => $result['manufacturer'],
            'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&filter_manufacturer_id='.$result['manufacturer_id']. '' . $url),
         );
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

      $data['symbol_left'] = $currencies[$this->session->data['currency']]['symbol_left'];
      $data['symbol_right'] = $currencies[$this->session->data['currency']]['symbol_right'];

      $this->load->model('account/wishlist');
      $wishlist = $this->model_account_wishlist->getWishlist();


      $products = [];
      foreach ($results as $result) {
         $product_index = null;
         $temp_product = null;
         foreach ($data['products'] as $index => $product) {
            if ($product['product_id'] == $result['product_id']) {
               $product_index = $index;
               $temp_product = $product;
            }
         }
         if (!$product_index && !$temp_product) {
            continue;
         }
         if ($result['image']) {
            //$image = $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'), (isset($data['theme_mt_image_product_checked_pole']) ? 'pole' : ''));
            $image = $this->model_tool_image->resize($result['image'], null, null);
         } else {
            $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'), (isset($data['theme_mt_image_product_checked_pole']) ? 'pole' : ''));
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

         if ($result['quantity'] <= 0) {
				$stock = $result['stock_status'];
			} elseif ($this->config->get('config_stock_display')) {
				$stock = $result['quantity'];
			} else {
				$stock = $this->language->get('text_instock');
			}

         $time = strtotime($result['date_added']);
         $one_week_ago = strtotime('-1 week');

         $sql = 'select * from '.DB_PREFIX.'product_discount where product_id = '.$result['product_id'].' and (date_start <= "'.date('Y-m-d').'" or date_start = "0000-00-00") and (date_end >= "'.date('Y-m-d').'" or date_end = "0000-00-00") order by priority asc, quantity desc limit 1';
         $res = $this->db->query($sql);
         $discount = null;
         if ($res->rows && isset($data['theme_mt_category_product_sticker_discount']) && $data['theme_mt_category_product_sticker_discount']) {
            $discount = $res->row;
            if ($discount && isset($discount['price']) && $discount['price'] > 0) {
               $discount = round((($discount['price'] - $result['price'])/$result['price'])*100)."% скидка от ".$discount['quantity']." шт.";
            }
         }
         $data['discount_check'] = $discount;
         $sql = 'select * from '.DB_PREFIX.'product_special where product_id = '.$result['product_id'].' and (date_start <= "'.date('Y-m-d').'" or date_start = "0000-00-00") and (date_end >= "'.date('Y-m-d').'" or date_end = "0000-00-00") order by priority asc limit 1';
         $res = $this->db->query($sql);
         $special_check = null;
         if ($res->rows && isset($data['theme_mt_category_product_sticker_special']) && $data['theme_mt_category_product_sticker_special']) {
            $special_check = $res->row;
            if ($special_check && isset($special_check['price']) && $special_check['price'] > 0) {
               $special_check = round((($special_check['price'] - $result['price'])/$result['price'])*100);
            }
         }

         $data['images'] = array();
         $dops = $this->model_catalog_product->getProductImages($result['product_id']);

         foreach ($dops as $dop) {
            $data['images'][] = array(

               //'thumb' => $this->model_tool_image->resize($dop['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_additional_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_additional_height'))
               'thumb' => $this->model_tool_image->resize($dop['image'], null, null)
            );
         }

         $data['attribute_groups'] = $this->model_catalog_product->getProductAttributes($results['product_id']);
         
         $temp_product['new']             = ($time > $one_week_ago ? true : false);
         $temp_product['special_check']   = $special_check;
         $temp_product['discount']        = $discount;
         $temp_product['in_wishlist']     = (in_array($result['product_id'], array_column($wishlist, 'product_id')) ? 1 : 0);
         //$temp_product['in_cart']         = (in_array($result['product_id'], array_column($incart, 'product_id')) ? 1 : 0);
         //$temp_product['attributes']      = $attributes;
         $temp_product['thumb']           = $image;
         $temp_product['imgDop']           = $data['images'];
         //$temp_product['name']            = $result['name'];
         $temp_product['quantity']        = $result['quantity'];
         $temp_product['description']     = utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length'));
         $temp_product['price']           = $price;
         $temp_product['special']         = $special;
         $temp_product['tax']             = $tax;
         //$temp_product['stock']           = $stock;
         $temp_product['minimum']         = $result['minimum'] > 0 ? $result['minimum'] : 1;
         $temp_product['rating']          = $result['rating'];
         $temp_product['href']            = $this->url->link('product/product', 'path=' . $this->request->get['path'] . '&product_id=' . $result['product_id'] . $url);
         $products[] = $temp_product;
      }
      
      $data['products'] = $products; 

      $url = '';

      if (isset($this->request->get['filter'])) {
         $url .= '&filter=' . $this->request->get['filter'];
      }

      if (isset($this->request->get['limit'])) {
         $url .= '&limit=' . $this->request->get['limit'];
      }

      if (isset($this->request->get['filter_manufacturer_id'])) {
         $url .= '&filter_manufacturer_id=' . $this->request->get['filter_manufacturer_id'];
      }
      
      $data['sorts'] = array();
      if ($data['order'] == 'ASC') $data['order'] = 'DESC';
      else $data['order'] = 'ASC';
      if (isset($data['theme_mt_category_sort_price_checked']) && $data['theme_mt_category_sort_price_checked']) {
         $data['sorts'][] = array(
            'code'  => 'p.price',
            'text'  => $data['theme_mt_category_sort_price_title'],
            'value' => 'p.price-'.$data['order'],
            'href'  => $_SERVER['REQUEST_URI'] . '&sort=p.price&order='. $data['order'] . '' . $url
            //'href'  => $this->url->link('product/category', 'path=' . $_SERVER['REQUEST_URI'] . '&sort=p.price&order='. $data['order'] . '' . $url)
         );
      }


      if (isset($data['theme_mt_category_sort_popular_checked']) && $data['theme_mt_category_sort_popular_checked']) {
         $data['sorts'][] = array(
            'code'  => 'rating',
            'text'  => $data['theme_mt_category_sort_popular_title'],
            'value' => 'rating-'.$data['order'],
            'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=rating&order='. $data['order'] . '' . $url)
         );
      }
      if (isset($data['theme_mt_category_sort_special_checked']) && $data['theme_mt_category_sort_special_checked']) {
         $data['sorts'][] = array(
            'code'  => 'p.special',
            'text'  => $data['theme_mt_category_sort_special_title'],
            'value' => 'p.special-'.$data['order'],
            'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.special&order='.$data['order'] . '' . $url)
         );
      }
      if (isset($data['theme_mt_category_sort_name_checked']) && $data['theme_mt_category_sort_name_checked']) {
         $data['sorts'][] = array(
            'code'  => 'pd.name',
            'text'  => $data['theme_mt_category_sort_name_title'],
            'value' => 'pd.name-'.$data['order'],
            'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order='. $data['order'] . '' . $url)
         );
      }
      if (isset($data['theme_mt_category_sort_date_checked']) && $data['theme_mt_category_sort_date_checked']) {
         $data['sorts'][] = array(
            'code'  => 'p.date_added',
            'text'  => $data['theme_mt_category_sort_date_title'],
            'value' => 'p.date_added-'.$data['order'],
            'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.date_added&order='. $data['order'] . '' . $url)
         );
      }

      $filter_url = [];
      if (isset($_GET['max_price'])) {
         $filter_url[] = "max_price=".$_GET['max_price'];
      }
      if (isset($_GET['min_price'])) {
         $filter_url[] = "min_price=".$_GET['min_price'];
      }

      if (isset($_GET['option'])) {
         foreach ($_GET['option'] as $filter_option_index => $filter_option) {
            if (is_array($filter_option)) {
               $filter_option = explode(',', $filter_option[0]);
               $fo_array = [];
               foreach ($filter_option as $fo) {
                  $fo_array[] = $fo;
               }
               $filter_url[] = "option[$filter_option_index][]=".implode(',', $fo_array);
            }
            else {
               $filter_url[] = "option[$filter_option_index]=$filter_option";
            }
         }
      }
      if (isset($_GET['attribute'])) {
         foreach ($_GET['attribute'] as $filter_attribute_index => $filter_attribute) {
            if (is_array($filter_attribute)) {
               $filter_attribute = explode(',', $filter_attribute[0]);
               $fo_array = [];
               foreach ($filter_attribute as $fo) {
                  $fo_array[] = $fo;
               }
               $filter_url[] = "option[$filter_attribute_index][]=".implode(',', $fo_array);
            }
            else {
               $filter_url[] = "option[$filter_attribute_index]=$filter_attribute";
            }
         }
      }
      $filter_url = implode('&',$filter_url);
      $data['logged'] = $this->customer->isLogged();

      /*$this->load->library('mtpagination');
      $pagination = new MtPagination();
      $pagination->total = $product_total;
      $pagination->page = $page;
      $pagination->limit = $limit;
      $pagination->url = $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&page={page}&'.$filter_url);

      $data['pagination'] = $pagination->render();

      $data['logged'] = $this->customer->isLogged();

      $description = explode('<a id="dalee" name="dalee"></a>', html_entity_decode($category_info['description']));
      if (count($description) <= 1) {
         $description = explode('<pre>далее<br></pre>', html_entity_decode($category_info['description']));
      }
      if (count($description) > 1) {
         $data['description'] = $description[0];
         $data['description_bottom'] = $description[1];
      }*/
      
      return $data;
   }
}