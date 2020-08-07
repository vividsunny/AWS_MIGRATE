<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */
if ( !defined( 'YITH_WCACT_VERSION' ) ) {
    exit( 'Direct access forbidden.' );
}

$auction_product = wc_get_product($post);
$post_id = '';

if($auction_product instanceof WC_Product) {
    $post_id = $auction_product->get_id();

} else {
    if ( ! empty( $_GET['product_id'] ) ) {
        $auction_product = wc_get_product( $_GET['product_id'] );
        $post_id = ( isset($auction_product) && $auction_product instanceof WC_Product ) ? $auction_product->get_id() : '';
    }
}


do_action('yith_before_auction_tab',$post_id);

if( $auction_product && 'auction' == $auction_product->get_type() ) {


    yit_delete_prop($auction_product,'yith_wcact_new_bid');


    $from_auction = ($datetime = $auction_product->get_start_date()) ? absint($datetime) : '';
    $from_auction = $from_auction ? get_date_from_gmt(date('Y-m-d H:i:s', $from_auction)) : '';
    $to_auction = ($datetime = $auction_product->get_end_date()) ? absint($datetime) : '';
    $to_auction = $to_auction ? get_date_from_gmt(date('Y-m-d H:i:s', $to_auction)) : '';

} else {

    $from_auction = '';
    $to_auction = '';
}

if ( apply_filters('yith_wcact_show_auction_dates',true, $auction_product ) ) {

    echo '<p class="form-field wc_auction_dates">
                        <label for="wc_auction_dates_from">' . esc_html__('Auction Dates', 'yith-auctions-for-woocommerce') . '</label>
                        <input type="text" name="_yith_auction_for" class="wc_auction_datepicker" id="_yith_auction_for" value="' . $from_auction . '" placeholder="' . esc_html__('From', 'yith-auctions-for-woocommerce') . '"
						title="YYYY-MM-DD hh:mm:ss" data-related-to="#_yith_auction_to">
                        <input type="text" name="_yith_auction_to" class="wc_auction_datepicker" id="_yith_auction_to" value="' . $to_auction . '" placeholder="' . esc_html__('To', 'yith-auctions-for-woocommerce') . '"
						title="YYYY-MM-DD hh:mm:ss">
		</p>';

}

do_action('yith_after_auction_tab',$post_id);