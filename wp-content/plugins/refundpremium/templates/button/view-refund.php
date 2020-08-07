<?php
/**
 * View Refund button
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly
}

$view_refund_label = apply_filters( 'hrrrefund_partial_refund_label' , esc_html__( 'View' , 'refund' ) ) ;
?>
<p class="view-refund">
	<a href="<?php echo esc_url( wc_get_endpoint_url( 'hrr-refund-request-view' , $request_id , wc_get_page_permalink( 'myaccount' ) ) ) ; ?>" class="button"><?php echo esc_html( $view_refund_label ) ; ?></a>
</p>
