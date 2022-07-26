<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

 require_once dirname(__DIR__).'/inc/pffw-woocommerce-controller.php';
 require_once dirname(__DIR__).'/inc/pffw-db-controller.php';

class PFFW_Fee_Controller {

    private $dbctrl;

    public function __construct() {
        $this->wcmctrl = new PFFW_WooCommerce_Controller();
        $this->dbctrl = new PFFW_DB_Controller();
    }
    
    public function save_fee() {
        $fees = $this->get_fee_relevant_cookies();
        if(is_array($fees)) {
            $this->_save_fee($fees);    
        }
    }

    public function get_fee_by_payment_method($payment_method) {
        
        return $this->_get_fee_by_payment_method($payment_method);
    }

    private function _get_fee_by_payment_method($payment_method) {
        
        return $this->dbctrl->get_fee_by_payment_method($payment_method);
    }

    private function _save_fee($fees) {
        foreach ($fees as $key => $fee) {
            foreach ($this->get_enabled_payment_gateways() as $index => $gateway) {
                if($key == $gateway) {
                    $this->dbctrl->insert_fee($gateway, (int)$fee, (int)-1);
                }
            }
        }
    }

    public function get_fee_relevant_cookies() {
        $cookies[] = $this->get_cookies();
        $res = [];
        $enabled_payment_gateways = $this->get_enabled_payment_gateways();

        foreach ($enabled_payment_gateways as $key => $payment_gateway) {
            if (wp_kses( $_COOKIE[$payment_gateway], null)) {
                $res[$payment_gateway] = wp_kses( $_COOKIE[$payment_gateway], null);
            }

        }
        
        return $res;
    }
    
    private function get_cookies() {
        return wp_kses($_COOKIE, null);
    }

    private function get_enabled_payment_gateways() {
        return $this->wcmctrl->get_enabled_payment_gateways()[0];
    }
}