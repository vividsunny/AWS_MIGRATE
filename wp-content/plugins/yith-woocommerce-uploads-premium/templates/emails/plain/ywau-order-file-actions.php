<?php
/**
 * Admin new order email (plain text)
 *
 * @author		WooThemes
 * @package 	WooCommerce/Templates/Emails/Plain
 * @version 	2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

echo "= " . $email_heading . " =\n\n";

echo sprintf( esc_html__( 'You have received an order from %s.', 'yith-woocommerce-additional-uploads' ), $order->billing_first_name . ' ' . $order->billing_last_name ) . "\n\n";

echo "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

do_action( 'woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text );

echo strtoupper( sprintf( esc_html__( 'Order number: %s', 'yith-woocommerce-additional-uploads' ), $order->get_order_number() ) ) . "\n";
echo date_i18n( __( 'jS F Y', 'yith-woocommerce-additional-uploads' ), strtotime( yit_get_prop($order, 'order_date') ) ) . "\n";

do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text );

echo "\n" . version_compare(WC()->version, '3.0', '<') ? $order->email_order_items_table( false, true, '', '', '', true ) : wc_get_email_order_items($order, false);

echo "==========\n\n";

if ( $totals = $order->get_order_item_totals() ) {
	foreach ( $totals as $total ) {
		echo $total['label'] . " " . $total['value'] . "\n";
	}
}

echo "\n" . sprintf( esc_html__( 'View order: %s', 'yith-woocommerce-additional-uploads'), admin_url( 'post.php?post=' . yit_get_prop($order, 'id') . '&action=edit' ) ) . "\n";

echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

do_action( 'woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text );

do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text );

echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

echo apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) );