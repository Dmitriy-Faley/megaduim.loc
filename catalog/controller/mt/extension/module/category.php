<?php
class ControllerMTExtensionModuleCategory extends Controller {
	public function index() {
		$this->load->language('extension/module/category');

		if (isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);
		} else {
			$parts = array();
		}

		if (isset($parts[0])) {
			$data['category_id'] = $parts[0];
		} else {
			$data['category_id'] = 0;
		}

		if (end($parts)) {
			$data['child_id'] = end($parts);
		} else {
			$data['child_id'] = 0;
		}

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$data['categories'] = array();

      $category = $this->model_catalog_category->getCategory(($data['child_id'] == 0 ? $data['category_id'] : $data['child_id']));
      $parent_category = null;
      if (isset($category['parent_id']) && $category['parent_id'] && $this->config->get('theme_mt_category_parent')) {
         $parent = $this->model_catalog_category->getCategory($category['parent_id']);
         if ($parent) {
            $data['parent_category'] = $parent;
            $data['parent_category_href'] = $this->url->link('product/category', 'path=' . (implode('_', $this->loadCategoryBreadcrumbs($category)) ? implode('_', $this->loadCategoryBreadcrumbs($category)) : ($data['child_id'] != 0 ? $data['category_id'] : $data['child_id'])));
         }
      }

		$this->load->model('catalog/category');

		$main_categories = $this->cache->get('category.main');
		$data['categories_main'] = $this->cache->get('category.main_html');
		if (!$main_categories || !$data['categories_main']) {
			$main_categories = $this->model_catalog_category->getCategories();
			$data['categories_main'] = $main_categories;
			foreach ($data['categories_main'] as $index => $category) {
				$data['categories_main'][$index]['child'] = $this->loadChildCategory($category['category_id']);
				$data['categories_main'][$index]['url'] = $this->url->link('product/category', 'path=' . ($category['parent_id'] != 0 ? $category['parent_id'] . '_' : '') . $category['category_id']);
			}
			$this->cache->set('category.main', $data['categories_main']);
		
			$html = '';
			foreach ($data['categories_main'] as $category) {
				if (empty($category['child'])) {
					$html .= $this->load->view('common/menu_item', $category);
					// $html_mobile .= $this->load->view('common/menu_item_mobile', $category);
				}
				else {
					$category['child'] = $this->loadHtmlChildCategory($category['child']);
					$html .= $this->load->view('common/menu_item_child', $category);
					// $html_mobile .= $this->load->view('common/menu_item_child_mobile', $category);
				}
			}
			$data['categories_main'] = $html;
			$this->cache->set('category.main', $main_categories);
			$this->cache->set('category.main_html', $html);
			// $data['categories_main_mobile'] = $html_mobile;
		}

		$data['categories'] = $this->cache->get('category.html');
		if (!$data['categories']) {
			$data['categories'] = $main_categories;
			foreach ($data['categories'] as $index => $category) {
				if ($category['top']) {
					$data['categories'][$index]['child'] = $this->loadChildCategory($category['category_id']);
					$data['categories'][$index]['url'] = $this->url->link('product/category', 'path=' . (implode('_', $this->loadCategoryBreadcrumbs($category)) ? implode('_', $this->loadCategoryBreadcrumbs($category)).'_' : '').$category['category_id']);
				}
				else unset($data['categories'][$index]);
			}
			$html = '';
			foreach ($data['categories'] as $category) {
				if (empty($category['child'])) {
					$html .= $this->load->view('common/menu_item', $category);
				}
				else {
					$category['child'] = $this->loadHtmlChildCategory($category['child']);
					$html .= $this->load->view('common/menu_item_child', $category);
				}
			}
			$data['categories'] = $html;
			$this->cache->set('category.html', $html);
		}
      
		return $data;
	}

   private function loadChildCategory($category_id) {
		$categories = $this->model_catalog_category->getCategories($category_id);
		foreach ($categories as $index => $category) {
			$categories[$index]['child'] = $this->loadChildCategory($category['category_id'], $categories[$index]);
			$categories[$index]['url'] = $this->url->link('product/category', 'path=' . (implode('_', $this->loadCategoryBreadcrumbs($category)) ? implode('_', $this->loadCategoryBreadcrumbs($category)).'_' : '').$category['category_id']);
		}
		return $categories;
	}

	private function loadHtmlChildCategory($categores) {
		$html = '';
		foreach ($categores as $category) {
			if (!$category['child']) {
				$html .= $this->load->view('common/menu_item', $category);
			}
			else {
				$category['child'] = $this->loadHtmlChildCategory($category['child']);
				$html .= $this->load->view('common/menu_item_child', $category);
			}
		}
		return $html;
	}

	private function loadCategoryBreadcrumbs($category) {
		$this->load->model('catalog/category');
		global $bread;
		if ($category['parent_id']) {
			$cat = $this->model_catalog_category->getCategory($category['parent_id']);
			if ($cat) {
				$bread[] = $cat['category_id'] ?? null;
				return $this->loadCategoryBreadcrumbs($cat);
			}
		}
		else {
			$t_bread = $bread;
			$bread = [];
			return array_reverse($t_bread);
		}
	}
}