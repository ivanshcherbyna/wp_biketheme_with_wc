<?php
/*
Plugin Name: my_plugin_liqPay_Privat_integration
Plugin URI: not has site
Description: Плагин позволяет расчитываться картами за товары посредством Приват24
Version: 2
Author: Иван Щербина
Author URI: not has site
License: GPL2
*/
?>
<?php
/*  Copyright 2018  IVAN IVANOV  (email : vanjok137@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 2 Soborny Av, Zaporozhye, ZP 69000  UA
*/
?>
<?php


// check of woocommerce active plugin
do_action('check_wc_plugin_activate');

add_action( 'plugins_loaded', 'init_liqpay_gateway_class' );

function init_liqpay_gateway_class() {


    if (!class_exists('WC_Payment_Gateway')) return;
    class WC_Gateway_Liqpay extends WC_Payment_Gateway
    {
        private $_checkout_url = 'https://www.liqpay.ua/api/checkout';//CLIENT-SERVER WAY
        //private $_checkout_url = 'https://www.liqpay.ua/api/request'; //SERVER-SERVER WAY

        protected $_supportedCurrencies = array('EUR','UAH','USD','RUB','RUR');

        public function __construct() {
            global $woocommerce;
            $this->id = 'liqpay';
            $this->has_fields = false;
            $this->method_title = __('liqPay', 'woocommerce');
            $this->method_description = __('Платежная система LiqPay', 'woocommerce');

            //$this->init_liqpay_api();
            $this->init_form_fields();
            $this->init_settings();

            $this->public_key = $this->get_option('public_key');
            $this->private_key = $this->get_option('private_key');
            $this->sandbox = $this->get_option('sandbox');


                $this->lang = $this->get_option('lang');
                $this->title = $this->get_option('title');
                $this->description = $this->get_option('description');
                $this->pay_message = $this->get_option('pay_message');
            
            $this->icon = $this->get_option('icon');
            $this->status = $this->get_option('status');
            $this->redirect_page = $this->get_option('redirect_page');
            $this->button = $this->get_option('button');

            // Actions
            //add woocommerce receipt_page (via generate_form)
            add_action('woocommerce_receipt_liqpay', array($this, 'receipt_page'));
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
            // Payment listener/API hook
            add_action('woocommerce_api_wc_gateway_liqpay', array($this, 'check_ipn_response'));

            //Check for
            if (!$this->is_valid_for_use()) {
                $this->enabled = false;
            }
        }
        //Generate in admin panel info
        public function admin_options() { ?>

            <h3><?php _e('Payment Privat24/LiqPay', 'woocommerce'); ?></h3>

            <?php if ($this->is_valid_for_use()) { ?>
                <table class="form-table"><?php $this->generate_settings_html(); ?></table>
            <?php } else { ?>

                <div class="inline error">
                    <p>
                        <strong><?php _e('Payment gateway is disabled', 'woocommerce'); ?></strong>: <?php _e('Liqpay dont support currency of your shop.', 'woocommerce'); ?>
                    </p>
                </div>

            <?php } ?>

        <?php }
        //Anonce for init form fields
        public function init_form_fields() {
            $this->form_fields = array(
                'enabled'     => array(
                    'title'   => __('Enable/Disable', 'woocommerce'),
                    'type'    => 'checkbox',
                    'label'   => __('Enable', 'woocommerce'),
                    'default' => 'yes',
                ),
                'title'       => array(
                    'title'       => __('Title of service by site', 'woocommerce'),
                    'type'        => 'textarea',
                    'description' => __('Title of service by front-end on site chekout page', 'woocommerce'),
                    'default'     => __('Payment via Visa or MasterCard (LiqPay)'),
                    'desc_tip'    => true,
                ),
                'description' => array(
                    'title'       => __('Description on front page', 'woocommerce'),
                    'type'        => 'textarea',
                    'description' => __('Description on front page when chekout goods', 'woocommerce'),
                    'default'     => __('Pay with LiqPay payment system', 'woocommerce'),
                    'desc_tip'    => true,
                ),
                'pay_message' => array(
                    'title'       => __('Message before pay', 'woocommerce'),
                    'type'        => 'textarea',
                    'description' => __('Message before pay', 'woocommerce'),
                    'default'     => __('Thank you for your order, click the button'),
                    'desc_tip'    => true,
                ),
                'public_key'  => array(
                    'title'       => __('Public key', 'woocommerce'),
                    'type'        => 'text',
                    'description' => __('Public key LiqPay. Обязательный параметр', 'woocommerce'),
                    'desc_tip'    => true,
                ),
                'private_key' => array(
                    'title'       => __('Private key', 'woocommerce'),
                    'type'        => 'text',
                    'description' => __('Private key LiqPay. Обязательный параметр', 'woocommerce'),
                    'desc_tip'    => true,
                ),
                'lang' => array(
                    'title'       => __('Language', 'woocommerce'),
                    'type'        => 'select',
                    'default'     => 'ru',
                    'options'     => array('ru'=> __('ru', 'woocommerce'), 'en'=> __('en', 'woocommerce')),
                    'description' => __('Language of interface ', 'woocommerce'),
                    'desc_tip'    => true,
                ),

                'icon'     => array(
                    'title'       => __('Logo of service', 'woocommerce'),
                    'type'        => 'text',
                    'default'     => 'https://www.privat24.ua/img/logo.png',
                    'description' => __('FULL URL for logo, by front-end of site', 'woocommerce'),
                    'desc_tip'    => true,
                ),
                'button'     => array(
                'title'       => __('Button', 'woocommerce'),
                'type'        => 'text',
                'default'     => '',
                'description' => __('FULL URL for button, by front-end of site for redirect to LiqPay', 'woocommerce'),
                'desc_tip'    => true,
            ),
                'status'     => array(
                'title'       => __('Status of order', 'woocommerce'),
                'type'        => 'text',
                'default'     => 'processing',
                'description' => __('Status of order after success pay', 'woocommerce'),
                'desc_tip'    => true,
            ),
                'sandbox'     => array(
                'title'       => __('Testing mode', 'woocommerce'),
                'label'       => __('Enabled', 'woocommerce'),
                'type'        => 'checkbox',
                'description' => __('This mode, can help to testing payment system without real money', 'woocommerce'),
                'desc_tip'    => true,
            ),
                'redirect_page'     => array(
                'title'       => __('URL Thanks Page', 'woocommerce'),
                'type'        => 'text',
                'default'     => '',
                'description' => __('URL site to redirect after success pay in LiqPay', 'woocommerce'),
                'desc_tip'    => true,
            )

            );
        }
        function is_valid_for_use() {
            if (!in_array(get_option('woocommerce_currency'), array('RUB', 'UAH', 'USD', 'EUR'))) {
                return false;
            }
            return true;
        }
        function process_payment($order_id) {
            $order = new WC_Order($order_id);

            return array(
                'result'   => 'success',
                'redirect' => add_query_arg('order-pay', $order_id, add_query_arg('key', $order->order_key, get_permalink(wc_get_page_id('pay')))),
            );
            
        }
        //CALL FORM WITH PARAMETRES FOR RECEIPT/КВИТАНЦИИ"
        public function receipt_page($order) {
            echo '<p>' . __(esc_attr($this->pay_message), 'woocommerce') . '</p><br/>';
            echo $this->generate_form($order);

        }
        //GENERATE OF FORM WITH PARAMETRES FOR RECIEPT/КВИТАНЦИИ"
        public function generate_form($order_id) {
            global $woocommerce;
            $order = new WC_Order($order_id);
            //$result_url = add_query_arg('wc-api', 'wc_gateway_liqpay', home_url('/'));

            //HERE IS ADDING  CALLBACK FUNC (IN FUNC CUSTOM WC ADD-ACTION) WC.DOC=PAYMENT-GATEWAY-API
            $result_url = add_query_arg('wc-api', 'wc_gateway_liqpay', 'https://thwpjphpfk.localtunnel.me');

            $currency= get_woocommerce_currency();
            if ($this->sandbox == 'yes') {
                $sandbox = 1;
            } else {
                $sandbox = 0;
            }

            if (trim($this->redirect_page) == '') {
                $redirect_page_url = $order->get_checkout_order_received_url();
            } else {
                $redirect_page_url = trim($this->redirect_page);
            }

           

            $html = $this->cnb_form(array(
                'version'     => '3',
                'amount'      => esc_attr($this->get_order_total()),
                'currency'    => esc_attr($currency),
                'description' => _("Оплата за заказ - ") . $order_id,
                'order_id'    => esc_attr($order_id),
                'result_url'  => $redirect_page_url,    //OTHER URL FOR REDIRECT TO PAGE
                'server_url'  => esc_attr($result_url), // URL OR SEND REQUEST
                'language'    => $this->lang,
                'sandbox'     => $sandbox
            ));
            return $html;
        }
        //ADD LISTENER FOR HOOK WHITH CHECK RESULT
        function check_ipn_response() {
            global $woocommerce;

            $success = isset($_POST['data']) && isset($_POST['signature']);

            if ($success) {

                $data = $_POST['data'];
                $parsed_data = json_decode(base64_decode($data));

                $received_signature = $_POST['signature'];
                $received_public_key = $parsed_data->public_key;
                $order_id = $parsed_data->order_id;
                $status = $parsed_data->status;
                $sender_phone = $parsed_data->sender_phone;    //Don't use in this time
                $amount = $parsed_data->amount;                //Don't use in this time
                $currency = $parsed_data->currency;            //Don't use in this time
                $transaction_id = $parsed_data->transaction_id;//Don't use in this time
                $generated_signature = base64_encode(sha1($this->private_key . $data . $this->private_key, 1));
                if ($received_signature != $generated_signature || $this->public_key != $received_public_key) wp_die('IPN Request Failure');
                $order = new WC_Order($order_id);
                //Check of status from response data
                if ($status == 'success' || ($status == 'sandbox' && $this->sandbox == 'yes')) {
                    //Mark order of status and empty cart
                    $order->update_status($this->status, __('Order has payed via LIQPAY (payment recieved)', 'woocommerce'));
                    $order->add_order_note(__('Client has payed for his goods', 'woocommerce'));
                    wc_reduce_stock_levels($order_id);//WC METOD REDUCED ORDER
                    $woocommerce->cart->empty_cart();
                    
                } else {
                    $order->update_status('failed', __('Payment not recieved (not get)', 'woocommerce'));
                    wp_redirect($order->get_cancel_order_url());
                    exit;
                }
            } else {
                wp_die('IPN Request Failure');
            }
        }
        //Method for use generate form BY
        public function cnb_form($params) {
            if (!isset($params['language'])) $language = 'ru';
            else $language = $params['language'];
            $params    = $this->cnb_params($params);
            $data      = base64_encode( json_encode($params) );
            $signature = $this->cnb_signature($params);
            if (trim($this->button) == '') {
                $button = '<input type="image" style="width: 160px" src="//static.liqpay.ua/buttons/p1%s.radius.png" name="btn_text" />';
            } else {
                //FOR CUSTUMIZING IMAGE BUTTON (use in admin panel url image)
                $button = '<input type="image" style="width: 160px" src="'.$this->button.'" name="btn_text" />';
            }

            return sprintf('
            <form method="POST" action="%s" accept-charset="utf-8">
                %s
                %s'. $button . '
            </form>
            ',
                $this->_checkout_url,
                sprintf('<input type="hidden" name="%s" value="%s" />', 'data', $data),
                sprintf('<input type="hidden" name="%s" value="%s" />', 'signature', $signature),
                $language
            );
        }
        //CHECK FOR INSERT PARAMS
        private function cnb_params($params) {
            $params['public_key'] = $this->public_key;
            if (!isset($params['version'])) {
                throw new InvalidArgumentException('version is null');
            }
            if (!isset($params['amount'])) {
                throw new InvalidArgumentException('amount is null');
            }
            if (!isset($params['currency'])) {
                throw new InvalidArgumentException('currency is null');
            }
            if (!in_array($params['currency'], $this->_supportedCurrencies)) {
                throw new InvalidArgumentException('currency is not supported');
            }
            if ($params['currency'] == 'RUR') {
                $params['currency'] = 'RUB';
            }
            if (!isset($params['description'])) {
                throw new InvalidArgumentException('description is null');
            }
            return $params;
        }
        //To formation signature
        public function cnb_signature($params) {
            $params      = $this->cnb_params($params);
            $private_key = $this->private_key;
            $json      = base64_encode( json_encode($params) );
            $signature = $this->str_to_sign($private_key . $json . $private_key);
            return $signature;
        }
        //for using in signature method
        public function str_to_sign($str) {
            $signature = base64_encode(sha1($str,1));
            return $signature;
        }
                /*
               * Init LIQPAY LIBRARY
               */
        private function init_liqpay_api() {
            // Include lib
            require_once 'LiqPay.php';
        }

    }

    function simple_liqpay($methods) {
        $methods[] = 'WC_Gateway_Liqpay';
        return $methods;
    }
    add_filter('woocommerce_payment_gateways', 'simple_liqpay');
}
