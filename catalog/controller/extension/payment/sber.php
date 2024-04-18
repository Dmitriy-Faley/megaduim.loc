<?php

class ControllerExtensionPaymentSber extends Controller
{
    /**
     * @param $registry
     */
    public function __construct($registry)
    {
        parent::__construct($registry);
        $this->load->language('extension/payment/sber');
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/sber.twig')) {
            $this->have_template = true;
        }
    }

    /**
     * @return mixed
     */
    public function index()
    {
        $data['action'] = $this->url->link('extension/payment/sber/payment', '', true);
        $data['entry_sber_button_confirm'] = $this->language->get('entry_sber_button_confirm');
        return $this->get_template('extension/payment/sber', $data);
    }

    /**
     * @param $template
     * @param $data
     * @return mixed
     */
    private function get_template($template, $data)
    {
        return $this->load->view($template, $data);
    }

    public function payment()
    {

        $this->initializeGatewayLibrary();
        $this->load->model('checkout/order');
        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        $order_number = (int)$order_info['order_id'];
        $amount = round($order_info['total'] * $order_info['currency_value'], 2) * 100;
//        print_r($order_info);die;

        $return_url = $this->url->link('extension/payment/sber/comeback');

        $jsonParams_array = array(
            'CMS' => 'Opencart 3.x',
            'Module-Version' => 'Sber ' . $this->method_library->version,
        );

        if (!empty($order_info['email'])) {
            $jsonParams_array['email'] = $order_info['email'];
        }
        if (!empty($order_info['telephone'])) {
            $jsonParams_array['phone'] = "+" . preg_match('/[7]\d{9}/', $order_info['telephone']) ? $order_info['telephone'] : '';
        }

        // here we will collect data for orderBundle
        $orderBundle = [];

        $orderBundle['customerDetails'] = array(
            'email' => $order_info['email'],
            'phone' => preg_match('/[7]\d{9}/', $order_info['telephone']) ? $order_info['telephone'] : ''
        );

        // ITEMS
        foreach ($this->cart->getProducts() as $product) {

            $product_taxSum = $this->tax->getTax($product['price'], $product['tax_class_id']);
            $product_amount = (round($product['price'] + $product_taxSum, 2)) * $product['quantity'];

            $tax_type = $this->config->get('payment_sber_taxType');

            // DEFINE TAX_TYPE
            if ($product['tax_class_id'] != 0) {
                $item_rate = $product_taxSum / $product['price'] * 100;
                switch ($item_rate) {
                    case 20:
                        $tax_type = 6;
                        break;
                    case 18:
                        $tax_type = 3;
                        break;
                    case 10:
                        $tax_type = 2;
                        break;
                    case 0:
                        $tax_type = 1;
                        break;
                    default:
                        $tax_type = $this->config->get('payment_sber_taxType');
                }
            }

            $product_data = array(
                'positionId' => $product['cart_id'],
                'name' => $product['name'],
                'quantity' => array(
                    'value' => $product['quantity'],
                    'measure' => $this->method_library->getDefaultMeasurement(),
                ),
                'itemAmount' => $product_amount * 100,
                'itemCode' => $product['product_id'] . "_" . $product['cart_id'], //fix by PLUG-1740, PLUG-2620
                'tax' => array(
                    // todo: some question taxType
                    'taxType' => $tax_type,
                    'taxSum' => $product_taxSum * 100
                ),
                'itemPrice' => round($product['price'] + $product_taxSum, 2) * 100,
            );

            // FFD 1.05 added
//            if ($this->method_library->getFFDVersion() == 'v105') {

            $attributes = array();
            $attributes[] = array(
                "name" => "paymentMethod",
                "value" => $this->method_library->getPaymentMethodType()
            );
            $attributes[] = array(
                "name" => "paymentObject",
                "value" => $this->method_library->getPaymentObjectType()
            );

            $product_data['itemAttributes']['attributes'] = $attributes;
//            }

            $orderBundle['cartItems']['items'][] = $product_data;

        }

        // DELIVERY
        if (isset($this->session->data['shipping_method']['cost']) && $this->session->data['shipping_method']['cost'] > 0) {

            $delivery['positionId'] = 'delivery';
            $delivery['name'] = $this->session->data['shipping_method']['title'];
            $delivery['itemAmount'] = $this->session->data['shipping_method']['cost'] * 100;
            $delivery['quantity']['value'] = 1;

            $delivery['quantity']['measure'] = $this->method_library->getDefaultMeasurement(); //todo?
            $delivery['itemCode'] = $this->session->data['shipping_method']['code'];
            $delivery['tax']['taxType'] = $this->config->get('payment_sber_taxType');
            $delivery['tax']['taxSum'] = 0;
            $delivery['itemPrice'] = $this->session->data['shipping_method']['cost'] * 100;

            // FFD 1.05 added
//            if ($this->method_library->getFFDVersion() == 'v105') {

            $attributes = array();
            $attributes[] = array(
                "name" => "paymentMethod",
                "value" => $this->method_library->getPaymentMethodType(true)
            );
            $attributes[] = array(
                "name" => "paymentObject",
                "value" => 4
            );

            $delivery['itemAttributes']['attributes'] = $attributes;
//            }

            $orderBundle['cartItems']['items'][] = $delivery;
        }

        // VOUCHERS
        if (isset($this->session->data['vouchers']) && count($this->session->data['vouchers']) > 0) {
            foreach ($this->session->data['vouchers'] as $key => $voucher) {

                $itemVoucher = array(
                    'positionId' => 'voucher_' . $key,
                    'name' => $voucher['description'],
                    'itemAmount' => $voucher['amount'] * 100,
                    'quantity' => array(
                        'value' => 1,
                        'measure' => $this->method_library->getDefaultMeasurement(),
                    ),
                    'itemCode' => 'voucher_' . $key,
                    'tax' => array(
                        'taxType' => $this->config->get('payment_sber_taxType'),
                        'taxSum' => 0,
                    ),
                    'itemPrice' => $voucher['amount'] * 100
                );

                $attributes = array();
                $attributes[] = array(
                    "name" => "paymentMethod",
                    "value" => $this->method_library->getPaymentMethodType(),
                );
                $attributes[] = array(
                    "name" => "paymentObject",
                    "value" => 1
                );

                $itemVoucher['itemAttributes']['attributes'] = $attributes;
                $orderBundle['cartItems']['items'][] = $itemVoucher;
            }
        }

        if ($this->method_library->enable_fiscale_options && $this->method_library->ofd_status) {
            // DISCOUNT CALCULATE
            $discount = $this->method_library->discountHelper->discoverDiscount($amount, $orderBundle['cartItems']['items']);
            if ($discount > 0) {
                $this->method_library->discountHelper->setOrderDiscount($discount);
                $recalculatedPositions = $this->method_library->discountHelper->normalizeItems($orderBundle['cartItems']['items']);
                $recalculatedAmount = $this->method_library->discountHelper->getResultAmount();
                $orderBundle['cartItems']['items'] = $recalculatedPositions;
            }
        }
        $args = array(
            'orderNumber' => $order_number . "_" . time(),
            'amount' => $amount,
//            'currency' => $this->method_library->currency, //$order_info['currency_code'],
//            'language' => $this->method_library->language,
            'returnUrl' => $return_url,
            'jsonParams' => json_encode($jsonParams_array),
        );

        if ($this->method_library->enable_fiscale_options && $this->method_library->ofd_status && !empty($orderBundle)) {
            $args['taxSystem'] = $this->method_library->taxSystem;
            $args['orderBundle']['orderCreationDate'] = date('c');
            $args['orderBundle'] = json_encode($orderBundle);
        }

        //$response = $this->method_library->_sendGatewayDataOld($this->method_library->stage == 'two' ? 'registerPreAuth.do' : 'register.do', $args);

        $args['userName'] = $this->method_library->login;
        $args['password'] = $this->method_library->password;

        if ($this->method_library->mode == 'test') {
            $action_address = $this->method_library->test_url;
        } else {
            $action_address = $this->method_library->prod_url;

            // for migrate to new payment gateway
            if (defined('RBSPAYMENT_PROD_URL_ALT') && defined('RBSPAYMENT_PROD_URL_ALT_PREFIX')) {
                if (substr($this->method_library->login, 0, strlen(RBSPAYMENT_PROD_URL_ALT_PREFIX)) == RBSPAYMENT_PROD_URL_ALT_PREFIX) {
                    $action_address = RBSPAYMENT_PROD_URL_ALT;
                }
            }
        }
        $method = $this->method_library->stage == 'two' ? 'registerPreAuth.do' : 'register.do';
        $request = http_build_query($args, '', '&');
        $response = $this->method_library->_sendGatewayData($request, $action_address . $method);
        if ($this->method_library->logging) {
            $this->method_library->logger($action_address, $method, $request, $response);
        }
        $response = json_decode($response, true);


        if (isset($response['orderId'])) {
            $comment = "Order created in payment gateway";
            $this->model_checkout_order->addOrderHistory($order_number, $this->config->get('payment_sber_order_status_before_id'), $comment, false);
        }

        if (isset($response['errorCode'])) {
            $this->document->setTitle($this->language->get('error_title'));

            $data['header'] = $this->load->controller('common/header');
            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['button_continue'] = $this->language->get('error_continue');

            $data['heading_title'] = $this->language->get('error_title') . ' #' . $response['errorCode'];
            $data['text_error'] = $response['errorMessage'];
            $data['continue'] = $this->url->link('checkout/cart');

            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');

            $this->response->setOutput($this->get_template('error/sber', $data));
        } else {
            $this->response->redirect($response['formUrl']);
        }
    }

    /**
     * Init Library
     */
    private function initializeGatewayLibrary()
    {
        $this->library('Sber');
        $this->method_library = new Sber();
        $this->method_library->login = $this->config->get('payment_sber_merchantLogin');
        $this->method_library->password = htmlspecialchars_decode($this->config->get('payment_sber_merchantPassword'));
        $this->method_library->stage = $this->config->get('payment_sber_stage');
        $this->method_library->mode = $this->config->get('payment_sber_mode');
        $this->method_library->logging = $this->config->get('payment_sber_logging');
        $this->method_library->currency = $this->config->get('payment_sber_currency');
        $this->method_library->taxSystem = $this->config->get('payment_sber_taxSystem');
        $this->method_library->taxType = $this->config->get('payment_sber_taxType');
        $this->method_library->ofd_status = $this->config->get('payment_sber_ofd_status');

        $this->method_library->FFDVersion = $this->config->get('payment_sber_FFDVersion');
        $this->method_library->paymentMethodType = $this->config->get('payment_sber_paymentMethodType');
        $this->method_library->paymentObjectType = $this->config->get('payment_sber_paymentObjectType');
        $this->method_library->paymentMethodTypeDelivery = $this->config->get('payment_sber_paymentMethodTypeDelivery');


        if (file_exists(DIR_SYSTEM . "library/sber_cacert.cer") && $this->config->get('payment_sber_enable_sber_cacert') == true) {
            $this->method_library->enable_sber_cacert = $this->config->get('payment_sber_enable_sber_cacert');
            $this->method_library->sber_cacert_path = DIR_SYSTEM . "library/sber_cacert.cer";
        } else {
            $this->method_library->enable_sber_cacert = (float)$this->config->get('payment_sber_enable_sber_cacert');
        }

        $c_locale = substr($this->language->get('code'), 0, 2);
        $this->method_library->language = ($c_locale == "ru" || $c_locale == "en") ? $c_locale : "ru";

//        var_dump($this->method_library);die;
    }

    /**
     * in oc 2.1 no Loader::library()
     * self realization
     * @param $library
     */
    private function library($library)
    {
        $file = DIR_SYSTEM . 'library/' . str_replace('../', '', (string)$library) . '.php';

        if (file_exists($file)) {
            include_once($file);
        } else {
            trigger_error('Error: Could not load library ' . $file . '!');
            exit();
        }
    }

    public function callback()
    {
        if (isset($this->request->get['mdOrder'])) {
            $order_id = $this->request->get['mdOrder'];
        } else {
            die('Illegal Access');
        }

        $this->initializeGatewayLibrary();
        $response = $this->method_library->_getGatewayOrderStatus($order_id);
        $response = json_decode($response, true);

        $ex = explode("_", $response['orderNumber']);
        $order_number = $ex[0];

        $this->load->model('checkout/order');
//        $order_number = $this->session->data['order_id'];
        $order_info = $this->model_checkout_order->getOrder($order_number);

        if ($order_info) {
            if (($response['errorCode'] == 0) && (($response['orderStatus'] == 1) || ($response['orderStatus'] == 2))) {

                $this->_storeGatewayOrderData($order_id, $order_info, $response);

                // set order status
                $comment = "Incoming callback";
                $this->model_checkout_order->addOrderHistory($order_number, $this->config->get('payment_sber_order_status_completed_id'), $comment, false);

                $this->response->redirect($this->url->link('checkout/success', '', true));
            } else {
//                $this->model_checkout_order->addOrderHistory($order_number, 1);
                $this->response->redirect($this->url->link('checkout/failure', '', true));
            }
        }
    }

    public function comeback()
    {
        if (isset($this->request->get['orderId'])) {
            $order_id = $this->request->get['orderId'];
        } else {
            die('Illegal Access');
        }
        $this->initializeGatewayLibrary();
        $response = $this->method_library->_getGatewayOrderStatus($order_id);
        $response = json_decode($response, true);

        $ex = explode("_", $response['orderNumber']);
        $order_number = $ex[0];

        $this->load->model('checkout/order');
        $order_info = $this->model_checkout_order->getOrder($order_number);

        if ($order_info) {
            if (($response['errorCode'] == 0) && (($response['orderStatus'] == 1) || ($response['orderStatus'] == 2))) {
                if ($this->method_library->allowCallbacks == false) {

                    $this->_storeGatewayOrderData($order_id, $order_info, $response);

                    // set order status
                    $comment = "Incoming ReturnUrl";
                    $this->model_checkout_order->addOrderHistory($order_number, $this->config->get('payment_sber_order_status_completed_id'), $comment, false);

                }
                $this->response->redirect($this->url->link('checkout/success', '', true));
            } else {
                $this->response->redirect($this->url->link('checkout/failure', '', true));
            }
        }
    }

    public function _storeGatewayOrderData($order_id, $order_info, $response) {
        $this->load->model('extension/payment/sber');

        $data = array(
            'order_id' => (int)$order_info['order_id'],
            'gateway_order_reference' => $order_id,
            'currency' => $response['currency'],
            'order_amount' => $response['amount'],
            'order_amount_deposited' => $response['amount'],
            'status_deposited' => $response['orderStatus'] === 1 ? 0 : 1, //todo
        );
        $this->model_extension_payment_sber->storeGatewayOrder($data);
    }


}
