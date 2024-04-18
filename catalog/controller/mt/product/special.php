<?php
class ControllerMTProductSpecial extends Controller {
	public function index() {
      $this->document->addStyle('catalog/view/theme/mt/stylesheet/category.css');

      $this->load->model('catalog/category');
      $this->load->model('tool/image');

      $data = [];

      $this->load->model('setting/setting');
		$data += $this->model_setting_setting->getSetting('theme_mt');
		foreach ($data as $index => $d) {
			if (is_string($d))
				$data[$index] = html_entity_decode($d, ENT_QUOTES, 'UTF-8');
		}

      if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.sort_order';
		}

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

		if (isset($this->request->get['search'])) {
			$this->document->setTitle($this->language->get('heading_title') .  ' - ' . $this->request->get['search']);
		} elseif (isset($this->request->get['tag'])) {
			$this->document->setTitle($this->language->get('heading_title') .  ' - ' . $this->language->get('heading_tag') . $this->request->get['tag']);
		} else {
			$this->document->setTitle($this->language->get('heading_title'));
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$url = '';

		if (isset($this->request->get['search'])) {
			$url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['tag'])) {
			$url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['description'])) {
			$url .= '&description=' . $this->request->get['description'];
		}

		if (isset($this->request->get['category_id'])) {
			$url .= '&category_id=' . $this->request->get['category_id'];
		}

		if (isset($this->request->get['sub_category'])) {
			$url .= '&sub_category=' . $this->request->get['sub_category'];
		}

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
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('product/search', $url)
		);

		if (isset($this->request->get['search'])) {
			$data['heading_title'] = $this->language->get('heading_title') .  ' - ' . $this->request->get['search'];
		} else {
			$data['heading_title'] = $this->language->get('heading_title');
		}

		$data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));

		$data['compare'] = $this->url->link('product/compare');

		// 3 Level Category Search
		$data['categories'] = array();

		$categories_1 = $this->model_catalog_category->getCategories(0);

		foreach ($categories_1 as $category_1) {
			$level_2_data = array();

			$categories_2 = $this->model_catalog_category->getCategories($category_1['category_id']);

			foreach ($categories_2 as $category_2) {
				$level_3_data = array();

				$categories_3 = $this->model_catalog_category->getCategories($category_2['category_id']);

				foreach ($categories_3 as $category_3) {
					$level_3_data[] = array(
						'category_id' => $category_3['category_id'],
						'name'        => $category_3['name'],
					);
				}

				$level_2_data[] = array(
					'category_id' => $category_2['category_id'],
					'name'        => $category_2['name'],
					'children'    => $level_3_data
				);
			}

			$data['categories'][] = array(
				'category_id' => $category_1['category_id'],
				'name'        => $category_1['name'],
				'children'    => $level_2_data
			);
		}
      
      $data['products'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $limit,
			'limit' => $limit
		);

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

		$product_total = $this->model_catalog_product->getTotalProductSpecials($filter_data);

		$results = $this->model_catalog_product->getProductSpecials($filter_data);

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
			
			$data['products'][] = array(
				'product_id'  => $result['product_id'],
				'special_check' => $special_check,
            	'discount'    => $discount,
				'in_wishlist' => (in_array($result['product_id'], array_column($wishlist, 'product_id')) ? 1 : 0),
				'attributes'  => $attributes,
				'thumb'       => $image,
				'name'        => $result['name'],
				'quantity'    => $result['quantity'],
				'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')),
				'price'       => $price,
				'special'     => $special,
				'tax'         => $tax,
				'stock'       => $result['stock_status'],
				'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
				'rating'      => $result['rating'],
				'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'] . $url)
			);
		}

		$url = '';

		if (isset($this->request->get['tag'])) {
			$url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['description'])) {
			$url .= '&description=' . $this->request->get['description'];
		}

		if (isset($this->request->get['category_id'])) {
			$url .= '&category_id=' . $this->request->get['category_id'];
		}

		if (isset($this->request->get['sub_category'])) {
			$url .= '&sub_category=' . $this->request->get['sub_category'];
		}

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
				'href'  => $this->url->link('product/search', 'sort=p.price&order='. $data['order'] . '' . $url)
			);
		}
		if (isset($data['theme_mt_category_sort_popular_checked']) && $data['theme_mt_category_sort_popular_checked']) {
			$data['sorts'][] = array(
				'code'  => 'rating',
				'text'  => $data['theme_mt_category_sort_popular_title'],
				'value' => 'rating-'.$data['order'],
				'href'  => $this->url->link('product/search', 'sort=rating&order='. $data['order'] . '' . $url)
			);
		}
		if (isset($data['theme_mt_category_sort_special_checked']) && $data['theme_mt_category_sort_special_checked']) {
			$data['sorts'][] = array(
				'code'  => 'p.special',
				'text'  => $data['theme_mt_category_sort_special_title'],
				'value' => 'p.special-'.$data['order'],
				'href'  => $this->url->link('product/search', 'sort=p.special&order='.$data['order'] . '' . $url)
			);
		}
		if (isset($data['theme_mt_category_sort_name_checked']) && $data['theme_mt_category_sort_name_checked']) {
			$data['sorts'][] = array(
				'code'  => 'pd.name',
				'text'  => $data['theme_mt_category_sort_name_title'],
				'value' => 'pd.name-'.$data['order'],
				'href'  => $this->url->link('product/search', 'sort=pd.name&order='. $data['order'] . '' . $url)
			);
		}
		if (isset($data['theme_mt_category_sort_date_checked']) && $data['theme_mt_category_sort_date_checked']) {
			$data['sorts'][] = array(
				'code'  => 'p.date_added',
				'text'  => $data['theme_mt_category_sort_date_title'],
				'value' => 'p.date_added-'.$data['order'],
				'href'  => $this->url->link('product/search', 'sort=p.date_added&order='. $data['order'] . '' . $url)
			);
		}
		
		$url = '';

		if (isset($this->request->get['tag'])) {
			$url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['description'])) {
			$url .= '&description=' . $this->request->get['description'];
		}

		if (isset($this->request->get['category_id'])) {
			$url .= '&category_id=' . $this->request->get['category_id'];
		}

		if (isset($this->request->get['sub_category'])) {
			$url .= '&sub_category=' . $this->request->get['sub_category'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$data['limits'] = array();

		$limits = array_unique(array($this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit'), 25, 50, 75, 100));

		sort($limits);

		foreach($limits as $value) {
			$data['limits'][] = array(
				'text'  => $value,
				'value' => $value,
				'href'  => $this->url->link('product/search', $url . '&limit=' . $value)
			);
		}

		$url = '';

		if (isset($this->request->get['tag'])) {
			$url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['description'])) {
			$url .= '&description=' . $this->request->get['description'];
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

		$this->load->library('mtpagination');
		$pagination = new MtPagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('product/search', $url . '&page={page}');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));
      
      $data['logged'] = $this->customer->isLogged();

      return $data;
   }
}