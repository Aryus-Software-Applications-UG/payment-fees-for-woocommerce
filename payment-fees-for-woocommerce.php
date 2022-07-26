<?php
/**
 * @package Payment Fees for WooCommerce
 */
/*
Plugin Name: Payment Fees for WooCommerce
Description: Add a payment fee to WooCommerce.
Version: 1.0
Author: a-sites | Aryus Software Applications UG (haftungsbeschränkt)
Author URI: https://www.a-sites.de/
Author URI: https://a-sites.de
License: GPLv2 or later
Text Domain: payment-fees-for-woocommerce
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

require_once( plugin_dir_path( __FILE__ ) . 'inc/pffw-db-controller.php');
require_once( plugin_dir_path( __FILE__ ) . 'inc/pffw-woocommerce-controller.php');
require_once( plugin_dir_path( __FILE__ ) . 'inc/pffw-helper.php');




class Payment_Fees_For_Woocommerce {
	public $pluginbasename;

	function __construct() {

        $this->pluginbasename = plugin_basename( __FILE__ );

    }

	function as_setup_admin_menu() {
		add_menu_page('Payment Fees for WooCommerce', 'Payment Fees for WooCommerce', 'manage_options', 'payment-fees-for-woocommerce', array($this, 'as_admin_page_setup'));
	}

	public function as_admin_page_setup(){
		require_once( plugin_dir_path( __FILE__ ) . 'template/admin.php');	
	}

	public function as_activate() {
		$dbctrl = new PFFW_DB_Controller();
		$dbctrl->create_db_table_for_fees();
	}

	public function as_uninstall() {
		$dbctrl = new PFFW_DB_Controller();
		$dbctrl->delete_db_table_for_fees();
	}

	public function as_deactivate() {
		flush_rewrite_rules();
	}
	
}





if( class_exists('Payment_Fees_For_Woocommerce') ) {
	
	$payment_fees_for_woocommerce = new Payment_Fees_For_Woocommerce();
	add_action('admin_menu', array($payment_fees_for_woocommerce, 'as_setup_admin_menu'));

	add_action( 'woocommerce_cart_calculate_fees', 'rudr_paypal_fee', 25 );
	function rudr_paypal_fee( $cart ) {
		$fee_controller = new PFFW_Fee_Controller();
		
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			return;
		}
		// if "PayPal" is the selected payment method, we add the fee
		if( 'paypal' == WC()->session->get( 'chosen_payment_method' ) ) {
			$fee = $fee_controller->get_fee_by_payment_method("paypal");
			if($fee > 0) {
				WC()->cart->add_fee( 'PayPal fee', $fee );
			}
		}

		if( 'ppec_paypal' == WC()->session->get( 'chosen_payment_method' ) ) {
			$fee = $fee_controller->get_fee_by_payment_method("ppec_paypal");
			if($fee > 0) {
				WC()->cart->add_fee( 'PayPal fee', $fee );
			}
		}

		if( 'cod' == WC()->session->get( 'chosen_payment_method' ) ) {
			$fee = $fee_controller->get_fee_by_payment_method("cod");
			if($fee > 0) {
				WC()->cart->add_fee( 'Cash on Delivery fee', $fee );
			}
		}

		if( 'bacs' == WC()->session->get( 'chosen_payment_method' ) ) {
			$fee = $fee_controller->get_fee_by_payment_method("bacs");
			if($fee > 0) {
				WC()->cart->add_fee( 'Bank Transfer fee', $fee );
			}
		}

		if( 'stripe' == WC()->session->get( 'chosen_payment_method' ) ) {
			$fee = $fee_controller->get_fee_by_payment_method("stripe");
			if($fee > 0) {
				WC()->cart->add_fee( 'Stripe fee', $fee );
			}
		}

		if( 'stripe_eps' == WC()->session->get( 'chosen_payment_method' ) ) {
			$fee = $fee_controller->get_fee_by_payment_method("stripe_eps");
			if($fee > 0) {
				WC()->cart->add_fee( 'EPS fee', $fee );
			}
		}

		if( 'stripe_sofort' == WC()->session->get( 'chosen_payment_method' ) ) {
			$fee = $fee_controller->get_fee_by_payment_method("stripe_sofort");
			if($fee > 0) {
				WC()->cart->add_fee( 'Sofort fee', $fee );
			}
		}

	}

	add_action( 'woocommerce_checkout_init', 'rudr_checkout_refresh' );
	function rudr_checkout_refresh() {
		wc_enqueue_js( "jQuery( function( $ ){
			$( 'form.checkout' ).on( 'change', 'input[name^=\"payment_method\"]', function(){
				$( 'body' ).trigger( 'update_checkout' );
			});
		});");
	}

	
	
}





register_activation_hook( __FILE__, array($payment_fees_for_woocommerce, 'as_activate') );
register_deactivation_hook( __FILE__, array($payment_fees_for_woocommerce, 'as_deactivate') );
// register_uninstall_hook( __FILE__, array($payment_fees_for_woocommerce, 'as_uninstall') );