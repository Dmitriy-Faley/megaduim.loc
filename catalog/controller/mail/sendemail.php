<?php
class ControllerMailSendemail extends Controller {
	public function send_form() {
        $json = array();
        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
          if ((utf8_strlen($this->request->post['phone']) < 6) || (utf8_strlen($this->request->post['phone']) > 2500)) {
              $json['error'] = 'Введите Ваш телефон';
          }

          $text = '';
          if (!isset($json['error'])) {
            //send form
            if( !$this->request->post['name']) $this->request->post['name'] = 'Аноним';
            $html = '<h3>Письмо от '.$this->request->post['name'].'</h3>';
            if($this->request->post['name'])$html .= '<p>'.$this->request->post['form'].'</p> <br>' . '<p><b>Имя:</b> '.$this->request->post['name'].'</p>';
            $html .= '<p><b>Телефон:</b> '.$this->request->post['phone'].'</p>';
            $html .= '<p><b>Вопрос:</b> '.$this->request->post['question'].'</p>';
            $html .= '<p><b>Рецепт:</b> '. $this->request->post['files'].'</p>';
            $html .= '<p><b>Рецепт2:</b> '. $this->request->file['file'].'</p>';
           
            
            $mail = new Mail(); 
            $mail->protocol = $this->config->get('config_mail_protocol');
            $mail->parameter = $this->config->get('config_mail_parameter');
            $mail->hostname = $this->config->get('config_smtp_host');
            $mail->username = $this->config->get('config_smtp_username');
            $mail->password = $this->config->get('config_smtp_password');
            $mail->port = $this->config->get('config_smtp_port');
            $mail->timeout = $this->config->get('config_smtp_timeout');			
            $mail->setTo($this->config->get('config_email')); 
            $mail->setTo($this->config->get('config_mail_alert_email')); 
            $mail->setFrom($this->config->get('config_email'));
            $mail->setSender($this->request->post['name']);
            $mail->setSubject(html_entity_decode('Тема письма от '.$this->request->post['name'], ENT_QUOTES, 'UTF-8'));
            $mail->setHtml($html);
            $mail->setText(html_entity_decode($text, ENT_QUOTES, 'UTF-8'));
            $mail->send();
            $json['success'] = $html;
            //$json['success'] = '<p><b>Имя:</b> '.$this->request->post['name'].'</p> <p><b>Телефон:</b> '.$this->request->post['phone'].'</p>';
          }
        }
        
        $this->response->setOutput(json_encode($json));*/
    }	
}
