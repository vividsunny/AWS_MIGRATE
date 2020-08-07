<?php
/**
 * Whole Refund button
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly
}
$whole_refund_label = apply_filters( 'hrrrefund_whole_refund_label' , get_option( 'hrr_refund_full_order_button_label' , 'Whole Refund' ) ) ;
?>
<p class="whole-refund">
	<a href="<?php echo esc_url( wc_get_endpoint_url( 'hrr-refund-request-form' , $order->get_id() , wc_get_page_permalink( 'myaccount' ) ) ) ; ?>" class="button"><?php echo esc_html( $whole_refund_label ) ; ?></a>
</p>
