<?php
class ControllerCommonHome extends Controller {
	public function index() {
		$this->document->setTitle($this->config->get('config_meta_title'));
		$this->document->setDescription($this->config->get('config_meta_description'));
		$this->document->setKeywords($this->config->get('config_meta_keyword'));

		// Загружаем модель каталога производителей
		$this->load->model('catalog/manufacturer');

		// Загружаем модель Баннеров
		$this->load->model('catalog/generalbanner');

		$data['generalBanner'] = array();

		// Получаем список производителей 
        $data['manufacturers'] = array();

		$this->load->model('tool/image');

        $results = $this->model_catalog_manufacturer->getManufacturers();

		$this->load->model('catalog/product');
		
		$manufacturerCustom = $this->model_catalog_product->getProducts();
		
		$collections = $this->model_catalog_generalbanner->getNews();

		foreach ($collections as $collection) {
			if ($collection['image']) {
				$image = $this->model_tool_image->resize($collection['image'], 767, 767);
			} else {
				$image = $this->model_tool_image->resize('no_image.png', 767, 767);
			}
            $data['generalBanner'][] = array(
				'image'  => $collection['image'],
				'image_adaptive'  => $collection['image_adaptive'],
                'title' => $collection['title'],
                'description' => html_entity_decode($collection['description'], ENT_QUOTES, 'UTF-8'),
				'href' 		=> $collection['meta_keyword'],
            );
        }

        foreach ($results as $result) {
			if ($result['image']) {
				$image = $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_location_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_location_height'));
			} else {
				$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
			}
			
			$countProductBrand = 0;
			foreach ($manufacturerCustom as $manufacturerCustomProd){
				if($manufacturerCustomProd['manufacturer_id'] == $result['manufacturer_id']) {
					$countProductBrand += 1;
				} else {
					$countProductBrand += 0;
				}
			};
            $data['manufacturers'][] = array(
				'thumb'  => $image,
                'name' => $result['name'],
				'count' => $countProductBrand,
                'description' => html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'),
				'href' 		=> $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $result['manufacturer_id'])
            );
        }

		if (isset($this->request->get['route'])) {
			$this->document->addLink($this->config->get('config_url'), 'canonical');
		}


	/*	$data['prods'] = array();
		$prodsLeftBanner = $this->model_catalog_product->getProducts();

		foreach ($prodsLeftBanner as $prods) {

			$data['attribute_groups'] = $this->model_catalog_product->getProductAttributes($prods['product_id']);

			$data['prods'][] = array(
                'name' => $prods['name'],
				'attrblock'   => $data['attribute_groups'],
				'href' 		=> $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $prods['product_id'])
            );
		}*/
	/*	$qqq = $this->model_catalog_attribute->getAttributes();
		print_r($qqq);*/
		
		$this->load->model('catalog/product');
		$this->load->model('catalog/category');
		$categoriesHome = $this->model_catalog_category->getCategories(0);
		
		foreach ($categoriesHome as $key=>$categoryHome) {
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
					$data['categoriesHome'][] = array(
						'categoryId' => $categoryHome['category_id'],
						'name'     => $categoryHome['name'],
						'children' => $children_data,
						'column'   => $categoryHome['column'] ? $categoryHome['column'] : 1,
						'href'     => $this->url->link('product/category', 'path=' . $categoryHome['category_id']),
					);
					
				}
			}
		}
		

		/********************************************************** */
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

		//print_r($data['categoriesHome']);

		foreach ($categories_info as $category_info) {
			$category_id = $category_info['category_id'];

			$filter_data = array(
                'filter_category_id' => $category_id,
                'sort'               => 'p.sort_order',
                'order'              => 'ASC',
                'start'              => 0,
                'limit'              => 10 // Укажите необходимое количество товаров для вывода
            );

			$resultsHome = $this->model_catalog_product->getProducts($filter_data);

			foreach ($resultsHome as $result) {
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
				'price'       => $price,
				'special'     => $special,
				'tax'         => $tax,
				'stock'       => $result['stock_status'],
				'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
				'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'] . $url),
				'hrefPiece'        => $this->url->link('product/product', 'product_id=')
				);
			}

			$data['categories'][] = array(
                'heading_title' => $category_info['heading_title'],
                'products'      => $data['products']
            );
		}

		/***************************************************************************** */


		$this->load->model('catalog/news3');

		$data['news'] = array();

		$news = $this->model_catalog_news3->getNews();

		if ($news) {

			$this->load->model('tool/image');
			foreach ($news as $key=>$resultNews) {
				if( $key <= 2 ) {
					if ($resultNews['image']) {
						$image = $this->model_tool_image->resize($resultNews['image'], 333, 160);
					} else {
						$image = $this->model_tool_image->resize('no_image.png', 333, 160);
					}

					if ($resultNews['image_adaptive']) {
						$imageAdaptive = $this->model_tool_image->resize($resultNews['image_adaptive'], 767, 767);
					} else {
						$imageAdaptive = $this->model_tool_image->resize('no_image.png', 767, 767);
					}

					if ($resultNews['image_inner']) {
						$imageInner = $this->model_tool_image->resize($resultNews['image_inner'], 767, 767);
					} else {
						$imageInner = $this->model_tool_image->resize('no_image.png', 767, 767);
					}

					if ($resultNews['image_inner_adaptive']) {
						$imageInnerAdaptive = $this->model_tool_image->resize($resultNews['image_inner_adaptive'], 767, 767);
					} else {
						$imageInnerAdaptive = $this->model_tool_image->resize('no_image.png', 767, 767);
					}
					
					$data['news'][] = array(
						'thumb' => $image,
						'thumb_adaptive' => $imageAdaptive, 
						'thumb_inner' => $imageInner,
						'thumb_inner_adaptive' => $imageInnerAdaptive,
						'name' => $resultNews['title'],
						'intro' => trim(strip_tags(html_entity_decode($resultNews['intro'], ENT_QUOTES, 'UTF-8'))),
						'href' => $this->url->link('information/news/info', 'news_id=' . $resultNews['news_id']),
						'date' => date($this->language->get('date_format_short'), strtotime($resultNews['date_start']))
					);
				}
			}
		}

		//Акции
		$this->load->model('catalog/news2');

		$data['newsStock'] = array();

		$newsStock = $this->model_catalog_news2->getNews();

		if ($newsStock) {

			$this->load->model('tool/image');
			foreach ($newsStock as $key=>$resultStock) {
				if( $key <= 2 ) {
					if ($resultStock['image']) {
						$image = $this->model_tool_image->resize($resultStock['image'], null, null);
					} else {
						$image = $this->model_tool_image->resize('no_image.png', 333, 160);
					}

					if ($resultStock['image_adaptive']) {
						$imageAdaptive = $this->model_tool_image->resize($resultStock['image_adaptive'], null, null);
					} else {
						$imageAdaptive = $this->model_tool_image->resize('no_image.png', 767, 767);
					}

					if ($resultStock['image_inner']) {
						$imageInner = $this->model_tool_image->resize($resultStock['image_inner'], null, null);
					} else {
						$imageInner = $this->model_tool_image->resize('no_image.png', 767, 767);
					}

					if ($resultStock['image_inner_adaptive']) {
						$imageInnerAdaptive = $this->model_tool_image->resize($resultStock['image_inner_adaptive'], null, null);
					} else {
						$imageInnerAdaptive = $this->model_tool_image->resize('no_image.png', 767, 767);
					}
					
					$data['newsStock'][] = array(
						'thumb' => $image,
						'thumb_adaptive' => $imageAdaptive, 
						'thumb_inner' => $imageInner,
						'thumb_inner_adaptive' => $imageInnerAdaptive,
						'name' => $resultStock['title'],
						'intro' => html_entity_decode($resultStock['intro'], ENT_QUOTES, 'UTF-8'),
						'href' => $this->url->link('information/news2/info', 'news2_id=' . $resultStock['news2_id']),
						'date' => date($this->language->get('date_format_short'), strtotime($resultStock['date_start']))
					);
				}
			}
		}

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('common/home', $data));
	}
}
