<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

$instance = YITH_Auctions()->bids;
$user_id  = get_current_user_id();
$date     = date( "Y-m-d H:i:s" );

$auctions_by_user = $instance->get_auctions_by_user( $user_id );

?>
<table class="shop_table shop_table_responsive my_account_orders yith_wcact_my_auctions_table">
    <thead>
        <tr>
            <th class="toptable order-number"><span class="nobr" ><?php echo esc_html__( 'Image', 'yith-auctions-for-woocommerce' ); ?></span></th>
            <th class="toptable order-status"><span class="nobr"><?php echo esc_html__( 'Product', 'yith-auctions-for-woocommerce' ); ?></span></th>
            <th class="toptable order-date"><span class="nobr"><?php echo esc_html__( 'Your bid', 'yith-auctions-for-woocommerce' ); ?></span></th>
            <th class="toptable order-total"><span class="nobr"><?php echo esc_html__( 'Current bid', 'yith-auctions-for-woocommerce' ); ?></span></th>
            <th class="toptable order-actions"><span class="nobr"><?php echo esc_html__( 'Status', 'yith-auctions-for-woocommerce' ); ?></span></th>

        </tr>
    </thead>
    <tbody>
    <?php
    foreach ( $auctions_by_user as $valor ) {
        $product      = wc_get_product( $valor->auction_id );
        if (!$product )
            continue;
        
        $product_name = get_the_title( $valor->auction_id );
        $product_url  = get_the_permalink( $valor->auction_id );
        $a            = $product->get_image( 'thumbnail' );
        $max_bid = $instance->get_max_bid($valor->auction_id);

        if($max_bid->user_id == $user_id) {
            $color = 'yith-wcact-max-bidder';
        }else{
            $color = 'yith-wcact-outbid-bidder';
        }
        ?>
            <tr class="yith-wcact-auction-endpoint" data-product="<?php echo $product->get_id() ?>" >
                <td class="order-number yith-wcact-auction-image" data-title="Image"><?php echo $a ?></td>
                <td class="order-status" data-title="Product"><a href="<?php echo $product_url; ?>"><?php echo $product_name ?></a></td>
                <td class="yith-wcact-my-bid-endpoint yith-wcact-my-auctions order-date <?php echo $color ?>" data-title="Your bid"><?php echo apply_filters('yith_wcact_auction_product_price',wc_price( $valor->max_bid),$valor->max_bid,$currency); ?></td>
                <td class="yith-wcact-current-bid-endpoint yith-wcact-my-auctions order-total" data-title="Current bid"><?php echo wc_price($product->get_price()) ?></td>
                <?php
                if ( $product->is_type('auction') && $product->is_closed() ) {
                     $max_bid = $instance->get_max_bid($valor->auction_id);

                    if ( $max_bid->user_id == $user_id && !$product->get_auction_paid_order() && ( !$product->has_reserve_price() || ($product->has_reserve_price() && $max_bid->bid >= $product->get_reserve_price())  ) ) {

                        $url  = add_query_arg( array( 'yith-wcact-pay-won-auction' => $product->get_id() ), apply_filters('yith_wcact_get_checkout_url',wc_get_checkout_url(),$product->get_id() ));
                        ?>
                        <td class="yith-wcact-auctions-status yith-wcact-my-auctions order-status" data-title="Status">
                            <span><?php echo apply_filters('yith_wcact_my_account_congratulation_message',esc_html__('You won this auction','yith-auctions-for-woocommerce'));?></span>

                            <?php if('yes' == get_option('yith_wcact_settings_tab_auction_show_add_to_cart_in_auction_product')){
                                $url  = add_query_arg( array( 'yith-wcact-pay-won-auction' => $product->get_id() ));
                            ?>
                                <a  href="<?php echo $url ?>" class="auction_add_to_cart_button button alt"
                                    id="yith-wcact-auction-won-auction">
                                    <?php echo sprintf(esc_html__('Add to cart', 'yith-auctions-for-woocommerce')); ?>
                                </a>
                            <?php

                            }else{

                                ?>
                                    <a  href="<?php echo $url ?>" class="auction_add_to_cart_button button alt"
                                        id="yith-wcact-auction-won-auction">
                                        <?php echo sprintf(esc_html__('Pay now', 'yith-auctions-for-woocommerce')); ?>
                                    </a>
                                <?php
                            }?>

                        </td>
                        <?php
                    }else {
                        ?>
                        <td class="yith-wcact-auctions-status yith-wcact-my-auctions order-status" data-title="Status"><?php echo esc_html__('Closed', 'yith-auctions-for-woocommerce'); ?>
                            <?php do_action('yith_wcact_auction_status_my_account_closed',$product,$valor); ?>
                        </td>

                    <?php
                    }
                } else {
                    ?>
                    <td class="yith-wcact-auctions-status yith-wcact-my-auctions order-status" data-title="Status"><?php echo esc_html__( 'Started', 'yith-auctions-for-woocommerce' ); ?>
                        <?php do_action('yith_wcact_auction_status_my_account_started',$product,$valor); ?>
                    </td>

                    <?php
                }
                ?>
            </tr>
        <?php
    }
    ?>
    </tbody>

</table>