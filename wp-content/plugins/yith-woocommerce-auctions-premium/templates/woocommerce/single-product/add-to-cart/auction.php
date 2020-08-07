<?php
/**
 * Auction product add to cart
 *
 * @author 		Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $product;

$product_wpml = $product; //Fix redirect url problem with WPML active
$product = apply_filters('yith_wcact_get_auction_product',$product);
?>

<?php
// Availability
$availability      = $product->get_availability();
$availability_html = empty( $availability['availability'] ) ? '' : '<p class="stock ' . esc_attr( $availability['class'] ) . '">' . esc_html( $availability['availability'] ) . '</p>';

if( ! version_compare( WC()->version, '2.4.0', '>='  )) {

    echo apply_filters('woocommerce_stock_html', $availability_html, $availability['availability'], $product);

}else {

    echo apply_filters('woocommerce_get_stock_html',$availability_html,$product);
}
?>

<?php do_action('yith_wcact_before_add_to_cart_form',$product) ?>

<?php if ( $product->is_in_stock() ) : ?>

    <?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>
    <?php
        if(apply_filters('yith_wcact_before_add_to_cart',true,$product)) {

            if ( $product->is_start() && !$product->is_closed() ) {

                $auction_finish = ( $datetime = $product->get_end_date() ) ? $datetime : NULL;
                $date = strtotime('now');

                $user = wp_get_current_user();
                if( $user instanceof WP_User  && $user->ID > 0 ) {

                    $is_banned = get_user_meta($user->ID, '_yith_wcact_user_ban', true);
                    $ban_message = get_user_meta($user->ID, '_yith_wcact_ban_message', true);
                }

                do_action('yith_wcact_before_form_auction_product',$product);
                ?>
                <form class="cart" method="post" enctype='multipart/form-data'>

                   <div class="yith-wcact-main-auction-product">

                        <?php
                        $bid_increment = 1;
                        $total = $auction_finish - $date;

                        do_action('yith_wcact_in_to_form_add_to_cart',$product);

                        ?>

                        <div id="time" class="timetito" data-finish-time="<?php echo $auction_finish ?>" data-remaining-time=" <?php echo $total ?>" data-bid-increment="<?php echo $bid_increment ?>" data-currency="<?php echo get_woocommerce_currency(); ?>" data-product="<?php echo $product_wpml->get_id()?>"data-current="<?php echo $product->get_price()?>"data-finish="<?php echo $auction_finish?>">
                            <label for="yith_time_left" class="ywcact-time-left"><?php esc_html_e('Time left:', 'yith-auctions-for-woocommerce') ?></label>
                            <div id="yith-wcact-auction-timeleft">

                                <?php do_action('yith_wcact_auction_before_set_bid',$product) ?>

                            </div>

                            <?php do_action('woocommerce_before_add_to_cart_button'); ?>

                            <?php if ( !isset( $is_banned ) || !$is_banned ) { ?>

                                <div name="form_bid" id="yith-wcact-form-bid">

                                    <label
                                        for="_yith_your_bid"><?php esc_html_e('Your bid:', 'yith-auctions-for-woocommerce') ?></label>
                                    <br/>

                                    <?php

                                        $show_auction_buttons = get_option('yith_wcact_settings_tab_auction_show_button_plus_minus');

                                    if ('yes' == $show_auction_buttons ) {

                                    ?>
                                        <div class="yith-wcact-bid-section" >

                                        <input type="button" class="bid button_bid_subtr" value="-">

                                    <?php
                                    }
                                    ?>

	                                <?php
	                                woocommerce_quantity_input(
		                                array(
			                                'input_id'    => '_actual_bid',
			                                'classes'     => array( 'input-text', 'qty', 'text' ),
			                                'min_value'   => apply_filters( 'woocommerce_quantity_input_min', 0, $product ),
			                                'max_value'   => apply_filters( 'woocommerce_quantity_input_max', '', $product ),
			                                'input_value' => apply_filters('yith_wcact_actual_bid_value','',$product),
		                                )
	                                );
	                                ?>
                                    
                                    <?php

                                    if ('yes' == $show_auction_buttons ) {
                                        ?>
                                        <input type="button" class="bid button_bid_add" value="+">

                                        </div>
                                        <?php
                                    }

                                        do_action('yith_wcact_after_form_bid',$product);
                                    ?>

                                    <?php do_action('yith_wcact_before_add_button_bid',$product) ?>

                                    <div id="yith-wcact-aution-buttons">

                                        <button type="button" class="auction_bid button alt"><?php _e('Bid', 'yith-auctions-for-woocommerce'); ?></button>

                                    </div>

                                    <?php do_action('yith_wcact_after_add_button_bid',$product_wpml) ?>

                                </div>

                            <?php } else {

                                ?>
                                <div class="yith-wcact-ban-message-section">
                                    <p class="yith-wcact-ban-message"> <?php echo $ban_message ?> </p>
                                </div>

                                <?php
                            } ?>
                        </div>

                        <?php do_action('woocommerce_after_add_to_cart_button'); ?>

                        </div>

                    </form>

                    <?php do_action( 'yith_wcact_after_add_to_cart_form',$product); ?>

        <?php
            } elseif (!$product->is_closed() || !$product->is_start()) {

                    $for_auction = ($datetime = $product->get_start_date()) ? $datetime : NULL;
                    $auction_start = $for_auction;
                    $date = strtotime('now');
                    $total = $auction_start - $date;
                    $product_id = $product->get_id();
                    ?>
                    <h3><?php echo apply_filters('yith_wcact_auction_not_available_message',esc_html__('The auction is not available', 'yith-auctions-for-woocommerce'),$product) ?></h3>
                    <div id="time">
                        <label
                            for="yith_time_left" class="ywcact-time-left" ><?php esc_html_e('Time left to start auction:', 'yith-auctions-for-woocommerce') ?></label>
                        <div class="timer" id="timer_auction" data-remaining-time=" <?php echo $total ?>">
                            <span id="days"
                                  class="days_product_<?php echo $product->get_id() ?>"></span><?php esc_html_e('days', 'yith-auctions-for-woocommerce'); ?>
                            <span id="hours"
                                  class="hours_product_<?php echo $product->get_id() ?>"></span><?php esc_html_e('hours', 'yith-auctions-for-woocommerce'); ?>
                            <span id="minutes"
                                  class="minutes_product_<?php echo $product->get_id() ?>"></span><?php esc_html_e('minutes', 'yith-auctions-for-woocommerce'); ?>
                            <span id="seconds"
                                  class="seconds_product_<?php echo $product->get_id() ?>"></span><?php esc_html_e('seconds', 'yith-auctions-for-woocommerce'); ?>

                        </div>
                    </div>
                <?php
                //The auction end
            } else {
                
                do_action('yith_wcact_auction_end',$product);

            }
        }
    ?>
    <?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

<?php endif;

