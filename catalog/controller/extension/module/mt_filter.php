<?php
class ControllerExtensionModuleMtfilter extends Controller {
	public function index($setting) {
		$this->document->addStyle('catalog/view/theme/default/stylesheet/mt_filter/choices.min.css');
		$this->document->addStyle('catalog/view/theme/default/stylesheet/mt_filter/ion.rangeSlider.min.css');
		$this->document->addStyle('catalog/view/theme/default/stylesheet/mt_filter/mt-filter.css');
		$this->document->addScript('catalog/view/javascript/mt_filter/choices.min.js');
		$this->document->addScript('catalog/view/javascript/mt_filter/ion.rangeSlider.min.js');
		if (isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);
		} else {
			$parts = array();
		}

		$category_id = end($parts);

		$this->load->model('catalog/category');

		$category_info = $this->model_catalog_category->getCategory($category_id);

		if ($category_info) {
			$this->load->language('extension/module/filter');

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

			$data['action'] = str_replace('&amp;', '&', $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url));

			if (isset($this->request->get['filter'])) {
				$data['filter_category'] = explode(',', $this->request->get['filter']);
			} else {
				$data['filter_category'] = array();
			}

			$this->load->model('catalog/product');

			$filter_data = array(
				'filter_category_id' => $category_id,
				'sort'              => 'p.price',
			);

			$results = $this->model_catalog_product->getProducts($filter_data);
			$results_all = $this->model_catalog_product->getProducts($filter_data, true);

			$max_price_product = 0;
			$min_price_product = 0;
			$min_price = 0;
			$max_price = 0;

			$min_product = array_values($results);
			if (isset($min_product[0])) {
				$min_product = $min_product[0];
				if ($min_product['special']) $min_price = $min_product['special'];
				else $min_price = $min_product['price'];
			}
			$max_product = end($results);
			if ($max_product) {
				if ($max_product['special']) $max_price = $max_product['special'];
				else $max_price = $max_product['price'];
			}
			
			$min_product_max = array_values($results_all);
			if (isset($min_product_max[0])) {
				$min_product_max = $min_product_max[0];
				if (isset($min_product_max['special']) && $min_product_max['special']) $min_price_product = $min_product['special'];
				else $min_price_product = $min_product_max['price'];
			}
			$max_product_max = end($results_all);
			if ($max_product_max) {
				if (isset($max_product_max['special']) && $max_product_max['special']) $max_price_product = $max_product_max['special'];
				else $max_price_product = $max_product_max['price'];
			}

			$data['max_price_product'] = $max_price_product;
			$data['min_price_product'] = $min_price_product;
			if (isset($_GET['max_price']) && $_GET['max_price'] != '') $data['max_price'] = $_GET['max_price'];
			else $data['max_price'] = $max_price;
			if (isset($_GET['min_price']) && $_GET['min_price'] != '') $data['min_price'] = $_GET['min_price'];
			else $data['min_price'] = $min_price;

			$products = [];
			$manufacturers = [];
			$options = [];
			$opt_val_array = [];
			$attributes = [];
			$attributes_title = [];
			$options_array = [];
			$options_values_array = [];

			$load_options = false;
			if ($this->config->get('module_mt_filter_option_id')) {
				$load_options = $this->config->get('module_mt_filter_option_id');
				$load_options = explode(',',$load_options);
			}

			foreach ($results_all as $product_index => $product) {
				$opts = $this->model_catalog_product->getProductOptions($product['product_id']);
				foreach ($opts as $opt) {
					if ($load_options && is_array($load_options) && !in_array($opt['option_id'], $load_options)) {
						continue;
					}
					$opt['checked'] = false;
					if (isset($_GET['option']) && isset($_GET['option'][$opt['option_id']])) {
						foreach ($opt['product_option_value'] as $index_opt_val => $opt_val) {
							if ($opt_val['option_value_id'] == $_GET['option'][$opt['option_id']] ||
								(isset($_GET['option'][$opt['option_id']][0]) && in_array($opt_val['option_value_id'], explode(',', $_GET['option'][$opt['option_id']][0])))) {
								$opt['product_option_value'][$index_opt_val]['checked'] = true;
							}
							else {
								$opt['product_option_value'][$index_opt_val]['checked'] = false;
							}
						}
					}
					$options[] = $opt;
				}
				foreach ($options as $option) {
					foreach ($option['product_option_value'] as $product_option_value) {
						if (!isset($options_values_array[$product_option_value['option_value_id']]))
							$options_values_array[$product_option_value['option_value_id']] = [
								'product_option_value' => $product_option_value,
								'option_id' => $option['option_id']
							];
					}
					$options_array[$option['option_id']] = [
						'option_id' => $option['option_id'],
						'name' => $option['name'],
						'type' => $option['type'],
					];
				}
				
				foreach ($options_values_array as $options_values_array_index => $options_values_array_value) {
					$options_array[$options_values_array_value['option_id']]['product_option_value'][] = $options_values_array_value['product_option_value'];
					usort($options_array[$options_values_array_value['option_id']]['product_option_value'], function($a, $b){
						return ($a['sort_order'] - $b['sort_order']);
					});
				}

				if (!$load_options) {
					$attrs = $this->model_catalog_product->getProductAttributes($product['product_id']);
					$load_attrs = false;
					if ($this->config->get('module_mt_filter_option_id')) {
						$load_attrs = $this->config->get('module_mt_filter_option_id');
						$load_attrs = explode(',',$load_attrs);
					}
					foreach ($attrs as $attr) {
						if ($attr && is_array($attr) && !in_array($attr['attribute_group_id'], $load_attrs)) {
							continue;
						}
						foreach ($attr['attribute'] as $attr_value) {
							$attributes_title[$attr_value['attribute_id']] = $attr_value['name'];
							$attributes[$attr['attribute_group_id']][$attr_value['attribute_id']][$attr_value['text']] = [
								'value' => $attr_value,
								'checked' => (isset($_GET['attribute']) && isset($_GET['attribute'][$attr_value['attribute_id']]) && isset($_GET['attribute'][$attr_value['attribute_id']][0]) && in_array($attr_value['text'], explode(',', $_GET['attribute'][$attr_value['attribute_id']][0])) ? true : false)
							];
						}
					}

					if (!in_array($product['manufacturer'], $manufacturers)) {
						if ($product['manufacturer'] != '')
							$manufacturers[] = [
								'name' => $product['manufacturer'],
								'checked' => (isset($_GET['manufacturer']) && isset($_GET['manufacturer'][0]) && in_array($product['manufacturer'], explode(',', $_GET['manufacturer'][0])) ? true : false)
							];
					}
					$products[$product['product_id']] = [
						'options' => $options_array,
						'attributes' => $attributes
					];
				}
			}
			
			$data['options'] = $options_array;
			$data['attributes'] = $attributes;
			$data['manufacturers'] = $manufacturers;
			$data['show_clear'] = (isset($_GET['option']) || isset($_GET['attribute']) || isset($_GET['manufacturers']) ? true : false);

			return $this->load->view('extension/module/mt_filter', $data);
		}
	}
}