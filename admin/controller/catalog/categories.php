<?php
class ControllerCatalogCategories extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('catalog/categories');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/category');

        $this->getList();
    }

    // Остальные методы контроллера (getList, add, edit, delete) будут идти дальше...
}
