<?php

class ControllerExtensionPaymentSber extends Controller
{
    private $error = array();

    /**
     * settings
     */
    public function index()
    {
        $this->library('Sber');
        $method_library = new Sber();

        $this->load->language('extension/payment/sber');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('payment_sber', $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');

            $test_mode = ($this->request->post['payment_sber_mode'] == 'test') ? true : false;
            if ($method_library->allowCallbacks) {
                $this->_updateGateSettings($test_mode);
            }

            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', 'SSL'));
        }

        // header of page
        $data['heading_title'] = $this->language->get('heading_title');

        // breadcrumbs
        $data['breadcrumbs'] = array();
        array_push($data['breadcrumbs'],
            array(  // main
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL')
            ),
            array(  // payment
                'text' => $this->language->get('text_payment'),
                'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL')
            ),
            array(  // Payment by
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('extension/payment/sber', 'user_token=' . $this->session->data['user_token'], 'SSL')
            )
        );

        // buttons
        $data['action'] = $this->url->link('extension/payment/sber', 'user_token=' . $this->session->data['user_token'], 'SSL');
        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL');
        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        // panel heading
        $data['text_settings'] = $this->language->get('text_settings');

        // module state
        $data['entry_status'] = $this->language->get('status');
        $data['status_enabled'] = $this->language->get('status_enabled');
        $data['status_disabled'] = $this->language->get('status_disabled');

        if (isset($this->request->post['payment_sber_status'])) {
            $data['payment_sber_status'] = $this->request->post['payment_sber_status'];
        } else {
            $data['payment_sber_status'] = $this->config->get('payment_sber_status');
        }
        // echo   $this->config->get('merchantPassword'); die;
        // $data['payment_sber_status'] = $data['payment_sber_status'] = $this->config->get('payment_sber_status');

        $data['entry_merchantLogin'] = $this->language->get('merchantLogin');
        $data['payment_sber_merchantLogin'] = $this->config->get('payment_sber_merchantLogin');
        $data['entry_merchantPassword'] = $this->language->get('merchantPassword');
        $data['payment_sber_merchantPassword'] = $this->config->get('payment_sber_merchantPassword');

        $data['entry_mode'] = $this->language->get('mode');
        $data['mode_test'] = $this->language->get('mode_test');
        $data['mode_prod'] = $this->language->get('mode_prod');
        $data['payment_sber_mode'] = $this->config->get('payment_sber_mode');

        $data['entry_enable_sber_cacert'] = $this->language->get('entry_enable_sber_cacert');
        $data['sber_cacert_enabled'] = $this->language->get('entry_enable_sber_cacert_enable');
        $data['sber_cacert_disabled'] = $this->language->get('entry_enable_sber_cacert_disable');
        $data['payment_sber_enable_sber_cacert'] = $this->config->get('payment_sber_enable_sber_cacert');

        $data['entry_stage'] = $this->language->get('stage');
        $data['stage_one'] = $this->language->get('stage_one');
        $data['stage_two'] = $this->language->get('stage_two');
        $data['payment_sber_stage'] = $this->config->get('payment_sber_stage');

        $data['entry_order_status_completed'] = $this->language->get('entry_order_status_completed');
        if (isset($this->request->post['payment_sber_order_status_completed_id'])) {
            $data['payment_sber_order_status_completed_id'] = $this->request->post['payment_sber_order_status_completed_id'];
        } else {
            $data['payment_sber_order_status_completed_id'] = $this->config->get('payment_sber_order_status_completed_id');
        }

        $data['entry_order_status_before'] = $this->language->get('entry_order_status_before');
        if (isset($this->request->post['payment_sber_order_status_before_id'])) {
            $data['payment_sber_order_status_before_id'] = $this->request->post['payment_sber_order_status_before_id'];
        } else {
            $data['payment_sber_order_status_before_id'] = $this->config->get('payment_sber_order_status_before_id');
        }

        $data['entry_order_status_reversed'] = $this->language->get('entry_order_status_reversed');
        if (isset($this->request->post['payment_sber_order_status_reversed_id'])) {
            $data['payment_sber_order_status_reversed_id'] = $this->request->post['payment_sber_order_status_reversed_id'];
        } else {
            $data['payment_sber_order_status_reversed_id'] = $this->config->get('payment_sber_order_status_reversed_id');
        }
        $data['entry_order_status_refunded'] = $this->language->get('entry_order_status_refunded');
        if (isset($this->request->post['payment_sber_order_status_refunded_id'])) {
            $data['payment_sber_order_status_refunded_id'] = $this->request->post['payment_sber_order_status_refunded_id'];
        } else {
            $data['payment_sber_order_status_refunded_id'] = $this->config->get('payment_sber_order_status_refunded_id');
        }


        $this->load->model('localisation/order_status');
        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        $data['entry_sortOrder'] = $this->language->get('entry_sortOrder');
        $data['payment_sber_sort_order'] = $this->config->get('payment_sber_sort_order');

        // logging
        $data['entry_logging'] = $this->language->get('logging');
        $data['logging_enabled'] = $this->language->get('logging_enabled');
        $data['logging_disabled'] = $this->language->get('logging_disabled');
        $data['payment_sber_logging'] = $this->config->get('payment_sber_logging');

        $data['entry_currency'] = $this->language->get('entry_currency');
        $data['currency_list'] = array_merge(
            array(
                array(
                    'numeric' => 0,
                    'alphabetic' => $this->language->get('entry_currency_default')
                )
            ),
            $this->getCurrencyList()
        );
        $data['payment_sber_currency'] = $this->config->get('payment_sber_currency');

        $data['entry_ofdStatus'] = $this->language->get('entry_ofdStatus');
        $data['payment_sber_ofd_status'] = $this->config->get('payment_sber_ofd_status');
        $data['entry_ofd_enabled'] = $this->language->get('entry_ofd_enabled');
        $data['entry_ofd_disabled'] = $this->language->get('entry_ofd_disabled');

        $data['entry_taxSystem'] = $this->language->get('entry_taxSystem');
        $data['taxSystem_list'] = $this->getTaxSystemList();
        $data['payment_sber_taxSystem'] = $this->config->get('payment_sber_taxSystem');

        $data['entry_taxType'] = $this->language->get('entry_taxType');
        $data['taxType_list'] = $this->getTaxTypeList();
        $data['payment_sber_taxType'] = $this->config->get('payment_sber_taxType');


        //FFD version
        $data['entry_FFDVersionFormat'] = $this->language->get('entry_FFDVersionFormat');
        $data['FFDVersionList'] = $this->getFFDVersionlist();
        $data['payment_sber_FFDVersion'] = $this->config->get('payment_sber_FFDVersion');

        //FFD method
        $data['entry_paymentMethod'] = $this->language->get('entry_paymentMethod');
        $data['ffd_paymentMethodTypeList'] = $this->getPaymentMethodTypeList();
        $data['payment_sber_paymentMethodType'] = $this->config->get('payment_sber_paymentMethodType');

        //FFD delivery method
        $data['entry_paymentMethodDelivery'] = $this->language->get('entry_paymentMethodDelivery');
        $data['payment_sber_paymentMethodTypeDelivery'] = $this->config->get('payment_sber_paymentMethodTypeDelivery');

        //FFD object
        $data['entry_paymentObject'] = $this->language->get('entry_paymentObject');
        $data['ffd_paymentObjectTypeList'] = $this->getPaymentObjectTypeList();
        $data['payment_sber_paymentObjectType'] = $this->config->get('payment_sber_paymentObjectType');

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $data['enable_fiscale_options'] = $method_library->enable_fiscale_options;
        $data['enable_sber_cacert'] = $method_library->enable_sber_cacert;
        $data['enable_refund_options'] = $method_library->enable_refund_options;

        //template rendering
        $this->response->setOutput($this->load->view('extension/payment/sber', $data));
    }

    /**
     * @return bool
     */
    private function validate()
    {
        if (!$this->user->hasPermission('modify', 'extension/payment/sber')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        return !$this->error;
    }

    public function _updateGateSettings($test_mode = true)
    {
        $this->library('Sber');
        $method = new Sber();

        $this->library('log');
        $file_name = "oc3x_sber_" . date("y-m") . ".log";
        $logger = new Log($file_name);

        $url = new Url(HTTP_CATALOG, $this->config->get('config_secure') ? HTTP_CATALOG : HTTPS_CATALOG);
        $callback_addresses_string = $url->link('extension/payment/sber/callback');

        $login = $this->config->get('payment_sber_merchantLogin');
        $password = htmlspecialchars_decode($this->config->get('payment_sber_merchantPassword'));

        // Endpoints
        if ($test_mode) {
            $action_adr = $method->getTestUrl();
            $gate_url = str_replace("payment/rest", "mportal-uat/mvc/public/merchant/update", $action_adr);
            // "mportal-uat" diff for some ONE bank
            //$gate_url = str_replace("rest", "mportal/mvc/public/merchant/update", $action_adr);

        } else {
            $action_adr = $method->getProdUrl();
            $gate_url = str_replace("payment/rest", "mportal/mvc/public/merchant/update", $action_adr);
        }

        $gate_url .= substr($login, 0, -4); // we guess username = login w/o "-api"

        $headers = array(
            'Content-Type:application/json',
            'Authorization: Basic ' . base64_encode($login . ":" . $password)
        );

        $data['callbacks_enabled'] = true;
        $data['callback_http_method'] = "GET";
        $data['callback_operations'] = "deposited,approved,declinedByTimeout";
        $data['callback_addresses'] = $callback_addresses_string;

        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_VERBOSE => true,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_URL => $gate_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
//            CURLOPT_ENCODING, "gzip",
            CURLOPT_ENCODING, '',
        ));
        $response = curl_exec($ch);

        $logger->write("\nREQUEST: " . json_encode($data) . "\nRESPONSE: " . json_encode($response) . "\n");
    }


    /**
     * @return array
     */
    private function getCurrencyList()
    {
        return [
            [
                'numeric' => 643,
                'alphabetic' => 'RUR'
            ],
            [
                'numeric' => 810,
                'alphabetic' => 'RUB'
            ],
            [
                'numeric' => 840,
                'alphabetic' => 'USD'
            ],
            [
                'numeric' => 933,
                'alphabetic' => 'BYN'
            ],
            [
                'numeric' => 978,
                'alphabetic' => 'EUR'
            ],
        ];
    }


    /**
     * @return array
     */
    private function getTaxTypeList()
    {

        return [
            [
                'numeric' => 0,
                'alphabetic' => $this->language->get('entry_no_vat')
            ],
            [
                'numeric' => 1,
                'alphabetic' => $this->language->get('entry_vat0')
            ],
            [
                'numeric' => 2,
                'alphabetic' => $this->language->get('entry_vat10')
            ],
            [
                'numeric' => 3,
                'alphabetic' => $this->language->get('entry_vat18')
            ],
            [
                'numeric' => 4,
                'alphabetic' => $this->language->get('entry_vat10_110')
            ],
            [
                'numeric' => 5,
                'alphabetic' => $this->language->get('entry_vat18_118')
            ],

            [
                'numeric' => 6,
                'alphabetic' => $this->language->get('entry_vat20')
            ],
            [
                'numeric' => 7,
                'alphabetic' => $this->language->get('entry_vat20_120')
            ],
        ];
    }


    /**
     * @return array
     */
    private function getTaxSystemList()
    {
        return [
            [
                'numeric' => 0,
                'alphabetic' => $this->language->get('entry_tax_system_1')
            ],
            [
                'numeric' => 1,
                'alphabetic' => $this->language->get('entry_tax_system_2')
            ],
            [
                'numeric' => 2,
                'alphabetic' => $this->language->get('entry_tax_system_3')
            ],
            [
                'numeric' => 3,
                'alphabetic' => $this->language->get('entry_tax_system_4')
            ],
            [
                'numeric' => 4,
                'alphabetic' => $this->language->get('entry_tax_system_5')
            ],
            [
                'numeric' => 5,
                'alphabetic' => $this->language->get('entry_tax_system_6')
            ],
        ];
    }

    /**
     * @return array
     */
    private function getFFDVersionlist()
    {
        return [
//            [
//                'value' => 'v1_0',
//                'title' => 'v1.0'
//            ],
            [
                'value' => 'v1_05',
                'title' => 'v1.05'
            ],
            [
                'value' => 'v1_2',
                'title' => 'v1.2'
            ],
        ];
    }

    /**
     * @return array
     */
    private function getPaymentMethodTypeList()
    {
        return [
            [
                'numeric' => 1,
                'alphabetic' => $this->language->get('entry_payment_method_1')
            ],
            [
                'numeric' => 2,
                'alphabetic' => $this->language->get('entry_payment_method_2')
            ],
            [
                'numeric' => 3,
                'alphabetic' => $this->language->get('entry_payment_method_3')
            ],
            [
                'numeric' => 4,
                'alphabetic' => $this->language->get('entry_payment_method_4')
            ],
            [
                'numeric' => 5,
                'alphabetic' => $this->language->get('entry_payment_method_5')
            ],
            [
                'numeric' => 6,
                'alphabetic' => $this->language->get('entry_payment_method_6')
            ],
            [
                'numeric' => 7,
                'alphabetic' => $this->language->get('entry_payment_method_7')
            ],

        ];
    }

    /**
     * @return array
     */
    private function getPaymentObjectTypeList()
    {
        return [
            [
                'numeric' => 1,
                'alphabetic' => $this->language->get('entry_payment_object_1')
            ],
            [
                'numeric' => 2,
                'alphabetic' => $this->language->get('entry_payment_object_2')
            ],
            [
                'numeric' => 3,
                'alphabetic' => $this->language->get('entry_payment_object_3')
            ],
            [
                'numeric' => 4,
                'alphabetic' => $this->language->get('entry_payment_object_4')
            ],
            [
                'numeric' => 5,
                'alphabetic' => $this->language->get('entry_payment_object_5')
            ],
//            [
//                'numeric' => 6,
//                'alphabetic' => $this->language->get('entry_payment_object_6')
//            ],
            [
                'numeric' => 7,
                'alphabetic' => $this->language->get('entry_payment_object_7')
            ],
//            [
//                'numeric' => 8,
//                'alphabetic' => $this->language->get('entry_payment_object_8')
//            ],
            [
                'numeric' => 9,
                'alphabetic' => $this->language->get('entry_payment_object_9')
            ],
            [
                'numeric' => 10,
                'alphabetic' => $this->language->get('entry_payment_object_10')
            ],
//            [
//                'numeric' => 11,
//                'alphabetic' => $this->language->get('entry_payment_object_11')
//            ],
            [
                'numeric' => 12,
                'alphabetic' => $this->language->get('entry_payment_object_12')
            ],
            [
                'numeric' => 13,
                'alphabetic' => $this->language->get('entry_payment_object_13')
            ],
        ];
    }

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

    public function install()
    {
        $this->load->model('extension/payment/sber');
        $this->model_extension_payment_sber->install();

        //idabi
        //@mail('me@idabi.dev', 'Sber Module installed (3.1.1)', HTTP_CATALOG . ' - ' . $this->config->get('config_name') . "\r\n" . 'version - ' . VERSION . "\r\n" . 'IP - ' . $this->request->server['REMOTE_ADDR'], 'MIME-Version: 1.0' . "\r\n" . 'Content-type: text/plain; charset=UTF-8' . "\r\n" . 'From: ' . $this->config->get('config_owner') . ' <' . $this->config->get('config_email') . '>' . "\r\n");
    }

    public function uninstall()
    {
        $this->load->model('extension/payment/sber');
        $this->model_extension_payment_sber->deleteTables();
    }

    public function order()
    {


        $this->language->load('extension/payment/sber');

        $this->load->model('extension/payment/sber');
        $this->load->model('sale/order');

        $data['gateway_order'] = $this->model_extension_payment_sber->getGatewayOrder($this->request->get['order_id']);
        $order_info = $this->model_sale_order->getOrder($this->request->get['order_id']);

        $this->load->library('Sber');

        if ($this->Sber->enable_refund_options === true &&
            !empty($order_info) && !empty($data['gateway_order'])) {
            if (!empty($data['gateway_order']['status_deposited'])) {
                $amount = $data['gateway_order']['order_amount_deposited'];
            } else {
                $amount = $data['gateway_order']['order_amount'];
            }

            if (!empty($data['gateway_order']['status_refunded'])) {
                $amount -= $data['gateway_order']['order_amount_refunded'];
            }

            $amount = (int)($amount / 100);

            // API login
            $data['catalog'] = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;
            $this->load->model('user/api');

            $api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

            if ($api_info && $this->user->hasPermission('modify', 'sale/order')) {
                $session = new Session($this->config->get('session_engine'), $this->registry);

                $session->start();

                if (version_compare(VERSION, '3.0.3.7', '<')) {
                    $this->model_user_api->deleteApiSessionBySessonId($session->getId());
                } else {
                    $this->model_user_api->deleteApiSessionBySessionId($session->getId());
                }

                $this->model_user_api->addApiSession($api_info['api_id'], $session->getId(), $this->request->server['REMOTE_ADDR']);

                $session->data['api_id'] = $api_info['api_id'];

                $data['api_token'] = $session->getId();
            } else {
                $data['api_token'] = '';
            }

            $data['store_id'] = $order_info['store_id'];
            $data['help_sber_amount'] = sprintf($this->language->get('help_sber_amount'), $amount, $amount, $data['gateway_order']['currency']);
            $data['gateway_amount'] = $amount;
            $data['user_token'] = $this->request->get['user_token'];
            $data['order_id'] = $this->request->get['order_id'];
            return $this->load->view('extension/payment/sber_order', $data);
        }
    }

    public function gatewayOrderAction()
    {
        $this->language->load('extension/payment/sber');

        $this->load->model('extension/payment/sber');
        $this->load->model('sale/order');
        $this->load->library('Sber');

        $gateway_order = $this->model_extension_payment_sber->getGatewayOrder($this->request->get['order_id']);
        $order_info = $this->model_sale_order->getOrder($this->request->get['order_id']);


        if ($gateway_order && $order_info && !empty($this->request->post['order_action'])) {

            //todo refactor tis line
            $gateway_order['order_amount'] = (int)round($gateway_order['order_amount']);

            $parameters = array();
            $parameters['orderId'] = trim($gateway_order['gateway_order_reference']);
            $parameters['userName'] = $this->config->get('payment_sber_merchantLogin');
            $parameters['password'] = $this->config->get('payment_sber_merchantPassword');

            $order_action = trim($this->request->post['order_action']);

            $test_mode = $this->config->get('payment_sber_mode') == 'test' ? true : false;

            if ($test_mode) {
                $action_adr = $this->Sber->getTestUrl();
            } else {
                $action_adr = $this->Sber->getProdUrl();
            }

            if ($order_action == 'payment_status') {
                $response = $this->Sber->_sendGatewayData($parameters, $action_adr . "getOrderStatusExtended.do");
                $response = json_decode($response, true);

                if (!empty($response['orderNumber'])
                    && isset($response['orderStatus'])
                    && (int)$order_info['order_id'] == (int)explode("_", $response['orderNumber'])[0]) {
                    $json['success'] = "Gateway transaction status is: " . $response['paymentAmountInfo']['paymentState'];
                } else {
                    $json['error'] = $response['errorMessage'];
                }

                // payment_deposit
            } elseif (strpos($order_action, 'payment_deposit') !== false && empty($gateway_order['status_deposited'])) {

                if ($order_action == 'payment_deposit_partial') {
                    $user_amount = (float)str_replace(',', '.', trim($this->request->post['user_amount'])) * 100;
                } else {
                    $user_amount = 0;
                }

                if ($gateway_order['order_amount'] < $user_amount) {
                    $json['error'] = sprintf($this->language->get('error_invalid_refund_amount'), number_format((int)$gateway_order['order_amount'] / 100 , 2));
                    $this->response->setOutput(json_encode($json));
                    return;
                }
                $parameters['amount'] = intval($user_amount);

                $response = $this->Sber->_sendGatewayData($parameters, $action_adr . "deposit.do");
                $response = json_decode($response, true);

                if (!empty($response) && isset($response['errorCode'])) {
                    if (empty($response['errorCode'])) {
                        $json['success'] = $this->language->get('text_success_deposit');
                        $json['redirect'] = 1;

                        $sql_data = array(
                            'order_amount_deposited' => ($order_action == 'payment_deposit_partial' ? $user_amount : $gateway_order['order_amount']),
                            'status_deposited' => 1,
                            'status_reversed' => 0,
                        );
                        $this->model_extension_payment_sber->updateGatewayOrder($order_info['order_id'], $sql_data);

                        $json['history'] = array(
                            'order_status_id' => $this->config->get('payment_sber_order_status_completed_id'),
                            'comment' => sprintf($this->language->get('text_success_deposit_amount'), number_format(($order_action == 'payment_deposit_partial' ? $user_amount : $gateway_order['order_amount']) / 100, 2)),
                            'notify' => 0,
                        );
                    } else {
                        $json['error'] = $response['errorMessage'];
                    }
                }

                //payment_refund
            } elseif (strpos($order_action, 'payment_refund') !== false) {

                if ($order_action == 'payment_refund_partial') {
                    $user_amount = (float)str_replace(',', '.', trim($this->request->post['user_amount'])) * 100;
                } else {
                    $user_amount = $gateway_order['order_amount_deposited'];
                    if (!empty($gateway_order['status_refunded'])) {
                        $user_amount -= $gateway_order['order_amount_refunded'];
                    }
                }

                if ($gateway_order['order_amount_deposited'] < $user_amount + $gateway_order['order_amount_refunded']) {
                    $json['error'] = sprintf($this->language->get('error_invalid_refund_amount'), number_format((int)($gateway_order['order_amount_deposited'] - $gateway_order['order_amount_refunded']) / 100 , 2));
                    $this->response->setOutput(json_encode($json));
                    return;
                }

                $parameters['amount'] = intval($user_amount);

                $response = $this->Sber->_sendGatewayData($parameters, $action_adr . "refund.do");
                $response = json_decode($response, true);

                if (!empty($response) && isset($response['errorCode'])) {
                    if (empty($response['errorCode'])) {
                        $json['success'] = $this->language->get('text_success_refund');
                        $json['redirect'] = 1;

                        $sql_data = array(
                            'order_amount_refunded' => $user_amount + $gateway_order['order_amount_refunded'],
                            'status_refunded' => 1,
                            'status_reversed' => 1,
                        );

                        if ($gateway_order['order_amount_deposited'] == $sql_data['order_amount_refunded']) {
                            $sql_data['status'] = 1;
                        }

                        $this->model_extension_payment_sber->updateGatewayOrder($order_info['order_id'], $sql_data);

                        $json['history'] = array(
                            'order_status_id' => $this->config->get('payment_sber_order_status_refunded_id'),
                            'comment' => sprintf($this->language->get('text_success_refund_amount'), number_format($user_amount / 100, 2) ),
                            'notify' => 0,
                        );
                    } else {
                        $json['error'] = $response['errorMessage'];
                    }
                }
                //payment_reverse
            } elseif ($order_action == 'payment_reverse') {

                $response = $this->Sber->_sendGatewayData($parameters, $action_adr . "reverse.do");
                $response = json_decode($response, true);

                if (!empty($response) && isset($response['errorCode'])) {
                    if (empty($response['errorCode'])) {
                        $json['success'] = $this->language->get('text_success_reverse');
                        $json['redirect'] = 1;

                        $sql_data = array(
                            'order_amount_deposited' => 0.00,
                            'status_deposited' => 0,
                            'status_reversed' => 0,
                        );

                        $response = $this->Sber->_sendGatewayData($parameters, $action_adr . "getOrderStatusExtended.do");
                        $response = json_decode($response, true);

                        if (!empty($response['orderNumber'])
                            && isset($response['orderStatus'])
                            && (int)$order_info['order_id'] == (int)explode("_", $response['orderNumber'])[0]
                            && (int)$response['orderStatus'] === 3) {
                            $sql_data['status'] = 1;
                            $sql_data['status_reversed'] = 1;
                        }

                        $this->model_extension_payment_sber->updateGatewayOrder($order_info['order_id'], $sql_data);
                        $json['history'] = array(
                            'order_status_id' => $this->config->get('payment_sber_order_status_reversed_id'),
                            'comment' => $this->language->get('text_success_reverse'),
                            'notify' => 0,
                        );
                    } else {
                        $json['error'] = $response['errorMessage'];
                    }
                }
            }
            $this->response->setOutput(json_encode($json));
            return;
        }

        if (!isset($json['success']) && !isset($json['error'])) {
            $json['error'] = $this->language->get('error_order_missing');
        }

        $this->response->setOutput(json_encode($json));

    }
}
