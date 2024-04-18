<?php
class ModelExtensionPaymentSber extends Model {
    public function getMethod($address, $total) {
        $this->load->language('extension/payment/sber');

        $method_data = array(
            'code'     => 'sber',
            'title'    => $this->language->get('entry_sber_text_title'),
            'terms'      => '',
            'sort_order' => $this->config->get('payment_sber_sort_order')
        );

        return $method_data;
    }

    public function storeGatewayOrder($data) {
        $this->db->query("INSERT INTO `" . DB_PREFIX . "sber_order` SET `order_id` = '" . (int)$data['order_id'] . "', `gateway_order_reference` = '" . $this->db->escape($data['gateway_order_reference']) . "', `currency` = '" . $this->db->escape($data['currency']) . "', `order_amount` = '" . (float)$data['order_amount'] . "', `order_amount_deposited` = '" . (float)$data['order_amount_deposited'] . "', `status_deposited` = '" . (int)$data['status_deposited'] . "', `date_added` = NOW(), `date_updated` = NOW()");
    }
}
