<?php

$bid_increment   = ( $bid = $product->get_bid_increment()) ?  $bid  : '1';

//code for show overtime and bidup//
$showbidup = $product->get_upbid_checkbox();

$bidup = "";

if ( 'yes' == $showbidup ) {
    $bidup = ( $bid ) ? esc_html__('Bid up: ','yith-auctions-for-woocommerce') . apply_filters('yith_wcact_auction_product_price',wc_price($bid),$bid,$currency) :  esc_html__('Bid up: No bid up','yith-auctions-for-woocommerce') ;
}

$showoverbid = $product->get_overtime_checkbox();
$over = "";
if ( 'yes' == $showoverbid ) {

    $over = ( $overtime = $product->get_overtime()) ?  sprintf(esc_html_x( 'Overtime: %s min','Overtime: 3 min', 'yith-auctions-for-woocommerce' ), $overtime) : esc_html__('Overtime: No overtime','yith-auctions-for-woocommerce')  ;
}
?>
<div class="yith-wcact-max-bidder" id="yith-wcact-max-bidder">
        <div class="yith-wcact-overbidmode yith-wcact-bidupmode">
            <span id="yith-wcact-showbidup"><?php echo $bidup ?></span> <span title="<?php echo esc_html__('Total used from pool of money for automatic bid up.','yith-auctions-for-woocommerce') ?>" <?php echo ('yes' == $showbidup) ? 'class="yith-auction-help-tip"': '' ?>></span> </br>
            <span id="yith-wcact-showovertime"><?php echo $over ?> </span> <span title="<?php echo esc_html__('Number of minutes added to the auction if a bid is made within the overtime range.','yith-auctions-for-woocommerce')?>" <?php echo ( 'yes' == $showoverbid) ? 'class="yith-auction-help-tip"': '' ?>></span>
        </div>
    <?php
    ////////////////////////////////////
    
    $instance = YITH_Auctions()->bids;
    $max_bid = $instance->get_max_bid($product->get_id());
    $userid = get_current_user_id();
    
    if ( $max_bid && $userid == $max_bid->user_id) {
        ?>
        <div id="winner_maximun_bid">
            <p id="max_winner"><?php esc_html_e(' You are currently the highest bidder for this auction','yith-auctions-for-woocommerce')?> <span title="<?php echo esc_html__('Refresh the page regularly to see if you are still the highest bidder','yith-auctions-for-woocommerce') ?>" class="yith-auction-help-tip"></span></p>
            <?php
            $show_tooltip = ( $bid = yit_get_prop( $product, '_yith_auction_bid_increment', true )) ? '<span title="'. esc_html__('If your bid is higher or equivalent to the reserve price, your bid will match the reserve price with the remaining saved and used automatically to outbid a competitors bid.','yith-auctions-for-woocommerce').'" class="yith-auction-help-tip"></span>': '';
            ?>
            <p id="current_max_bid"><?php echo sprintf( apply_filters('yith_wcact_current_max_bid',_x( 'Your maximum bid: %s','My maximum bid: $ 50.00', 'yith-auctions-for-woocommerce' ),$show_tooltip), apply_filters('yith_wcact_auction_product_price',wc_price($max_bid->bid),$max_bid->bid,$currency ) ) ?> <?php echo $show_tooltip ?></p>
        </div>
        <?php
    }
    ?>
</div>
    