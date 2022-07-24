defined( 'ABSPATH' ) or die( 'Are you ok?' );

// register_activation_hook( **FILE**, array( $this, 'updateReminder_activate' ) );
// register_deactivation_hook( **FILE**, array( $this, 'updateReminder_deactivation' ) );

if ( is_admin() && ! defined( 'DOING_AJAX' ) ) return;

// HERE Define your targeted shipping method ID
$payment_method = 'bacs';

// The percent to apply
$percent = 15; // 15%

$cart_total = $cart_object->subtotal_ex_tax;
$chosen_payment_method = WC()->session->get('chosen_payment_method');

if( $payment_method == $chosen_payment_method ){
    $label_text = __( "Shipping discount 15%" );
    // Calculation
    $discount = number_format(($cart_total / 100) \* $percent, 2);
    // Add the discount
    $cart_object->add_fee( $label_text, -$discount, false );
}

add_action( 'woocommerce_review_order_before_payment', 'refresh_payment_methods' );
function refresh_payment_methods(){
// jQuery code
?>

<script type="text/javascript">
    (function($){
        $( 'form.checkout' ).on( 'change', 'input[name^="payment_method"]', function() {
            $('body').trigger('update_checkout');
        });
    })(jQuery);
</script>
<?php
}
//     ?>

// function show_admin_Page(){
// $wcmctrl = new WooCommerce_Controller();
//     $helper = new A_Sites_Helper_Functions();
//     $helper->print_array_pretty($wcmctrl->get_enabled_payment_gateways());
// $active_payment_methods = $wcmctrl->get_enabled_payment_gateways();

// <h2>Woocommerce Payment Fee</h2>
// <p>Füge für deine Aktiven Zahlungsmethoden gebürhen hinzu</p>
// <?php

// foreach ($active_payment_methods[1] as $index => $payment_method) {
//             echo "<label>Payment Fee for " . $payment_method . "
//                 <br>
//                 <input id='ats_fee_plugin_optins' name='ats_fee_plugin_optins' type='number' value='" . esc_attr($options[$active_payment_methods[0][$index]]) . "'></input>
// </label>
// <br/>
// <br/>";

// }
// }
