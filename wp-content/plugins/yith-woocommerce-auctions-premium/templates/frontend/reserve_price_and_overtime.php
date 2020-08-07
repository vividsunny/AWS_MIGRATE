<?php

$instance = YITH_Auctions()->bids;
$max_bid = $instance->get_max_bid($product->get_id());
$userid = get_current_user_id();
$minimun_increment_amount = (int)$product->get_minimum_increment_amount();

$manual_bid_increment = apply_filters('yith_wcact_max_bid_manual',(int)$product->get_current_bid() + $minimun_increment_amount,$product);

if ( $minimun_increment_amount && $max_bid && $userid != $max_bid->user_id ) {
    echo '<div id="yith_wcact_manuel_bid_increment" class="yith_wcact_font_size">';
        echo '<p>';
                echo apply_filters('yith_wcact_manual_bid_increment_text',sprintf( esc_html__('Enter "%s" or more.', 'yith-auctions-for-woocommerce'),
                    wc_price($manual_bid_increment)), $product);
        echo '</p>';

    echo '</div>';
}

echo '<div id="yith_wcact_reserve_and_overtime">';

    echo  '<div id="yith_reserve_price" class="yith_wcact_font_size">';
        if ( $product->has_reserve_price() ) {
            if ($max_bid && $max_bid->bid >= $product->get_reserve_price() ){
                echo '<p class="yith_wcact_exceeded_reserve_price">' . apply_filters('yith_wcact_product_exceeded_reserve_price_message',esc_html__('The product has exceeded the reserve price. ', 'yith-auctions-for-woocommerce')) . '</p>';
            } else {
               echo '<p class="yith_wcact_has_reserve_price">' . apply_filters('yith_wcact_product_has_reserve_price_message',esc_html__('The product has a reserve price. ', 'yith-auctions-for-woocommerce')) . '</p>';
            }
        } else {
            echo '<p class="yith_wcact_does_not_have_reserve_price">' . apply_filters('yith_wcact_product_does_not_have_a_reserve_price_message',esc_html__('This product does not have a reserve price. ', 'yith-auctions-for-woocommerce')) . '</p>';
        }
        echo '</div>';

        echo  '<div id="yith-wcact-overtime">';
        if( $product->is_in_overtime() ) {
            ?>
            <span id="yith-wcact-is-overtime"> <?php esc_html_e('Currently in overtime','yith-auctions-for-woocommerce')?> </span>
            <?php

        }
    echo '</div>';

echo '</div>';