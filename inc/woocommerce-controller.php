<?php


// require_once( ABSPATH . 'wp-content/plugins/woocommerce/woocommerce.php' );

require_once dirname(__DIR__).'/inc/fee-controller.php';


class WooCommerce_Controller {

    public function get_enabled_payment_gateways() {
        return $this->_get_enabled_payment_gateways();
    }

    private function _get_enabled_payment_gateways() {


        $gateways = WC()->payment_gateways->get_available_payment_gateways();

        $enabled_gateways = array();
        foreach ($gateways as $gateway) {
            if ($gateway->enabled == 'yes') {
                $enabled_gateways[] = $gateway->id;
                $title[] = $gateway->title;
            }
        }

        $response[0] = $enabled_gateways;
        $response[1] = $title;

        return $response;
    }

    public function set_payment_fee($name = "Payment Fee") {
        return $this->_set_payment_fee($name);
    }

    private function _set_payment_fee($name) {
        $fee_controller = new Fee_Controller();
        $fee = $fee_controller->get_fee_by_payment_method($payment_method);
    }

}
