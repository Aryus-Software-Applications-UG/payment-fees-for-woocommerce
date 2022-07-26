<?php 

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

require_once dirname(__DIR__).'/../payment-fees-for-woocommerce/inc/pffw-helper.php';
require_once dirname(__DIR__).'/../payment-fees-for-woocommerce/inc/pffw-woocommerce-controller.php';
require_once dirname(__DIR__).'/../payment-fees-for-woocommerce/inc/pffw-fee-controller.php';
require_once dirname(__DIR__).'/../payment-fees-for-woocommerce/inc/pffw-db-controller.php';



$wcmctrl = new PFFW_WooCommerce_Controller();
$helper = new PFFW_Helper_Functions();
$feectrl = new PFFW_Fee_Controller();

$feectrl->save_fee();


?>
<div class="wrap">
	<h2><?php echo esc_html(get_admin_page_title()); ?></h2>
</div>

<div class="as-admin-content">
	<?php 
		$active_payment_methods = $wcmctrl->get_enabled_payment_gateways();

    	foreach ($active_payment_methods[1] as $index => $payment_method) {
			?> 
			<table class="form-table">
				<tr>
					<th scope="row"><label for="<?php echo esc_attr($active_payment_methods[0][$index]); ?>"><?php echo esc_html($payment_method); ?></label></th>
					<td><input name="<?php echo esc_attr($active_payment_methods[0][$index]); ?>" type="number" id="<?php echo esc_attr($active_payment_methods[0][$index]); ?>" value="<?php echo esc_attr($feectrl->get_fee_by_payment_method($active_payment_methods[0][$index])); ?>"  class="regular-text"></td>
				</tr>
			</table>

			<script>
				document.getElementById('<?php echo esc_html($active_payment_methods[0][$index]); ?>').addEventListener("change", function () {
					document.cookie = "<?php echo esc_html($active_payment_methods[0][$index]); ?> = " + this.value;
				});
			</script>
			
			<?php
	    }


	?>
	<input type="submit" name="submit" id="submit" class="button" value="Speichern" onclick="location.reload()">
	
</div>


<?php
