<?php
/*
	Template name: Update PreOrder
*/
// we are creating this page for replace the woocommerce preorder plugin to yith preorder plugin
	
$product_id = 132772;
$wc_datetime = get_post_meta( $product_id, '_wc_pre_orders_availability_datetime', true );
$wc_availabel = get_post_meta( $product_id ,'available', true );
/*echo $wc_datetime;
echo "<br/>$wc_availabel";
die;*/
update_post_meta( $product_id, '_ywpo_preorder', 'yes');
update_post_meta( $product_id, '_ywpo_for_sale_date', $wc_datetime );
update_post_meta( $product_id, '_ywpo_price_adjustment', 'manual' );
update_post_meta( $product_id, '_ywpo_adjustment_type', 'fixed' );

/*_ywpo_preorder = yes
_ywpo_for_sale_date = strtotime date
_ywpo_preorder_label
_ywpo_preorder_availability_date_label
_ywpo_preorder_price
_ywpo_price_adjustment_amount
_ywpo_price_adjustment =  manual
_ywpo_adjustment_type = fixed*/


/*old meta
available
_wc_pre_orders_availability_datetime*/
?>