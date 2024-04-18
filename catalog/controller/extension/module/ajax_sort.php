<?php
class ControllerExtensionModuleAjaxSort extends Controller {
    public function index() {
        if ($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['sort_order'])) {
            $sort_order = $this->request->post['sort_order'];

            // Загрузите модель каталога, чтобы получить данные товаров
            $this->load->model('catalog/product');
            $data['products'] = $this->model_catalog_product->getProducts($sort_order);

            // Загрузите вид, чтобы вернуть отформатированный список товаров
            $response = $this->load->view('product/product', $data);

            $this->response->setOutput($response);
        }
    }
}
