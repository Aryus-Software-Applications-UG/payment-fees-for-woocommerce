<?php 

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

require_once dirname(__DIR__).'/../payment-fees-for-woocommerce/inc/helper.php';
require_once dirname(__DIR__).'/../payment-fees-for-woocommerce/inc/woocommerce-controller.php';
require_once dirname(__DIR__).'/../payment-fees-for-woocommerce/inc/fee-controller.php';
require_once dirname(__DIR__).'/../payment-fees-for-woocommerce/inc/db-controller.php';



$wcmctrl = new WooCommerce_Controller();
$helper = new A_Sites_Helper_Functions();
$feectrl = new Fee_Controller();

$feectrl->save_fee();


?>
<div class="wrap">
	<h2><?php echo get_admin_page_title(); ?></h2>
</div>

<div class="as-admin-content">
	<?php 
		$active_payment_methods = $wcmctrl->get_enabled_payment_gateways();

    	foreach ($active_payment_methods[1] as $index => $payment_method) {
			?> 
			<table class="form-table">
				<tr>
					
					<th scope="row"><label for="<?php echo $active_payment_methods[0][$index] ?>"><?php echo $payment_method ?></label></th>
					<td><input name="<?php echo $active_payment_methods[0][$index] ?>" type="number" id="<?php echo $active_payment_methods[0][$index] ?>" value="<?php echo $feectrl->get_fee_by_payment_method($active_payment_methods[0][$index]) ?>"  class="regular-text"></td>
				</tr>
			</table>

			<script>
				document.getElementById('<?php echo $active_payment_methods[0][$index] ?>').addEventListener("change", function () {
					document.cookie = "<?php echo $active_payment_methods[0][$index] ?> = " + this.value;
				});
			</script>
			
			<?php
	    }


	?>
	<input type="submit" name="submit" id="submit" class="button" value="Speichern" onclick="location.reload()">
	
</div>


<?php
