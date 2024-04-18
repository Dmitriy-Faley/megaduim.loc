<?php
class ControllerAccountWishList extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/wishlist', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('account/wishlist');

		$this->load->model('account/wishlist'); 
		$wishlist = $this->model_account_wishlist->getWishlist();

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		$incart = $this->cart->getProducts();

    
		if (isset($this->request->get['remove'])) {
			// Remove Wishlist
			$this->model_account_wishlist->deleteWishlist($this->request->get['remove']);

			$this->session->data['success'] = $this->language->get('text_remove');

			$this->response->redirect($this->url->link('account/wishlist'));
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/wishlist')
		);

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		$this->load->model('setting/setting');
		$data += $this->model_setting_setting->getSetting('theme_mt');

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

		
		$data['sorts'] = array();
		if ($data['order'] == 'ASC') $data['order'] = 'DESC';
		else $data['order'] = 'ASC';
		if (isset($data['theme_mt_category_sort_price_checked']) && $data['theme_mt_category_sort_price_checked']) {
		   $data['sorts'][] = array(
			  'code'  => 'p.price',
			  'text'  => $data['theme_mt_category_sort_price_title'],
			  'value' => 'p.price-'.$data['order'],
			  'href'  => $this->url->link('account/wishlist', '&sort=p.price&order='. $data['order'] . '' . $url)
		   );
		}
		$filter_url = [];
		if (isset($_GET['max_price'])) {
		   $filter_url[] = "max_price=".$_GET['max_price'];
		}
		if (isset($_GET['min_price'])) {
		   $filter_url[] = "min_price=".$_GET['min_price'];
		}
		$filter_url = implode('&',$filter_url);

		if (isset($data['theme_mt_category_product_limit_checked']) && $data['theme_mt_category_product_limit_checked'])
		$limit = $data['theme_mt_category_product_limit'];
	 	else $limit = 8;

		$data['products'] = array();

		$filter_data = array(
			'filter_filter'      => $filter, //Для сортировки по цене
			'sort'               => $sort, //Для сортировки по цене
			'order'              => $order,
			'start' => ($page - 1) * $limit,
			'limit' => $limit
		);
		
		

		$product_total = $this->model_account_wishlist->getTotalWishlist($this->request->get['id']);

		$results = $this->model_account_wishlist->getWishlists($filter_data);


		$resultsItem = $this->model_account_wishlist->getWishlist($filter_data);

		// Навигация по странице

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}
		
		$this->load->library('mtpagination');
		$pagination = new MtPagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->num_links = 4;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('account/wishlist', $url . '&page={page}');
		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));
		
		$data['pagCustom'] = array(
			'url' => $this->url->link('account/wishlist', $url . '&page='. ($page + 1)),
			'page' => $page,
			'last' => ceil($product_total / $limit),
		);

		$prodLimit = $page * $limit;
		$difference = $prodLimit - $limit;

		foreach ($resultsItem as $key => $resItem){
			if($difference <= $key && $prodLimit > $key) {
				$product_info = $this->model_catalog_product->getProduct($resItem['product_id']);
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], null, null);
				} else {
					$image = false;
				}
				
				if ($product_info) {
					if ($product_info['image']) {
						$image = $this->model_tool_image->resize($product_info['image'], null, null);
					} else {
						$image = false;
					}
	
					if ($product_info['quantity'] <= 0) {
						$stock = $product_info['stock_status'];
					} elseif ($this->config->get('config_stock_display')) {
						$stock = $product_info['quantity'];
					} else {
						$stock = $this->language->get('text_instock');
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
	
					$data['images'] = array();
	
					$results = $this->model_catalog_product->getProductImages($product_info['product_id']);
		
					foreach ($results as $result) {
						$data['images'][] = array(
							'thumb' => $this->model_tool_image->resize($result['image'], null, null)
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
					$data['attribute_groups'] = $this->model_catalog_product->getProductAttributes($product_info['product_id']);
	
					$data['products'][] = array( 
						'product_id' => $product_info['product_id'],
						'thumb'      => $image,
						'name'       => $product_info['name'],
						'model'      => $product_info['model'],
						'imgDop'      => $data['images'], 
						'attrblock'   => $data['attribute_groups'],
						'in_wishlist' => (in_array($product_info['product_id'], array_column($wishlist, 'product_id')) ? 1 : 0),
						'in_cart'     => (in_array($product_info['product_id'], array_column($incart, 'product_id')) ? 1 : 0),
						'stock'     => $product_info['stock_status'],
						'minimum'     => $product_info['minimum'] > 0 ? $product_info['minimum'] : 1,
						'price'      => $price,
						'option'      => $data['options'],
						'special'    => $special,
						'href'       => $this->url->link('product/product', 'product_id=' . $product_info['product_id']),
						'remove'     => $this->url->link('account/wishlist', 'remove=' . $product_info['product_id'])
					);
				} else {
					$this->model_account_wishlist->deleteWishlist($result['product_id']);
				}
			}
		}
		
		$data['continue'] = $this->url->link('account/account', '', true);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/wishlist', $data));
	}

	public function add() {
		$this->load->language('account/wishlist');

		$json = array();

		if (isset($this->request->post['product_id'])) {
			$product_id = $this->request->post['product_id'];
		} else {
			$product_id = 0;
		}

		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct($product_id);

		if ($product_info) {
			if ($this->customer->isLogged()) {
				// Edit customers cart
				$this->load->model('account/wishlist');

				$this->model_account_wishlist->addWishlist($this->request->post['product_id']);

				$json['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' . (int)$this->request->post['product_id']), $product_info['name'], $this->url->link('account/wishlist'));

				$json['total'] = sprintf($this->language->get('text_wishlist'), $this->model_account_wishlist->getTotalWishlist());
			} else {
				if (!isset($this->session->data['wishlist'])) {
					$this->session->data['wishlist'] = array();
				}

				$this->session->data['wishlist'][] = $this->request->post['product_id'];

				$this->session->data['wishlist'] = array_unique($this->session->data['wishlist']);

				$json['success'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', '', true), $this->url->link('account/register', '', true), $this->url->link('product/product', 'product_id=' . (int)$this->request->post['product_id']), $product_info['name'], $this->url->link('account/wishlist'));

				$json['total'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
