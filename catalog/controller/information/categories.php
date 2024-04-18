<?php
class ControllerInformationCategories extends Controller {
    public function index() {
        $this->load->model('catalog/category');

        $categories = $this->model_catalog_category->getCategories();

        


        $data['categories'] = array();

        $data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

        $data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

        $this->load->model('tool/image');

        foreach ($categories as $category) {

            if ($category['image']) {
                $image = $this->model_tool_image->resize($category['image'], 666, 840);
            } else {
                $image = $this->model_tool_image->resize('no_image.png', 666, 840);
            }

            $data['categories'][] = array(
                'name' => $category['name'],
                'thumb' => $image,
                'href' => $this->url->link('product/category', 'path=' . $category['category_id'])
            );
        }

        $this->response->setOutput($this->load->view('information/categories', $data));
    }
}
