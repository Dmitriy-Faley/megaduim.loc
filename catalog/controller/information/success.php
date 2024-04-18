<?php
class ControllerInformationSuccess extends Controller {
    public function index() {
        
        $this->load->language('information/success');
        
        $this->document->setTitle($this->language->get('Спасибо за заявку'));


        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');
        
        $this->response->setOutput($this->load->view('information/success', $data));
        
    }
}
