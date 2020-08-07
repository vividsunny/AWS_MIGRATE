<?php
/**
 * Notes class
 *
 * @author  Yithemes
 * @package YITH WooCommerce Auctions
 * @version 1.0.0
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'YITH_WCACT_Cron' ) ) {
    /**
     * YITH_WCACT_Cron_emails
     *
     * @since 1.0.0
     */
    class YITH_WCACT_Cron{
        /**
         * Constructor
         *
         * @since  1.0.0
         * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
         */
        public function __construct() {
            add_action( 'yith_wcact_register_cron_email', array( $this, 'cron_emails' ) );
            add_action( 'yith_wcact_send_emails', array( $this, 'yith_wcact_send_emails_bidders' ), 10, 1 );
            add_action( 'yith_wcact_register_cron_email_auction', array( $this, 'cron_emails_auctions' ));
            add_action( 'yith_wcact_send_emails_auction', array( $this, 'yith_wcact_send_emails' ),10,1);
            add_action('yith_wcact_send_emails_auction_overtime',array($this,'yith_wcact_send_emails'),10,1);
            add_action('yith_wcact_cron_winner_email_notification',array($this,'cron_resend_winner_email'));


        }

        /**
         * Create single event
         * Create single event for send emails to user when the auction is about to end
         *
         * @since  1.0.0
         * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
         */
        public function cron_emails( $product_id ) {
            if ( 'yes' == get_option( 'yith_wcact_settings_cron_auction_send_emails','yes') ) {
                $product = wc_get_product( $product_id );
                $time_end_auction = $product->get_end_date('edit');
                $number           = get_option( 'yith_wcact_settings_cron_auction_number_days' );
                $unit             = get_option( 'yith_wcact_settings_cron_auction_type_numbers' );
                $time_send_email  = strtotime( ( sprintf( "-%d %s", $number, $unit ) ), (int)$time_end_auction );

                wp_schedule_single_event( $time_send_email, 'yith_wcact_send_emails', array( $product_id ) );

            }
        }

        /**
         * Sends email
         * Create single event for send emails to user when the auction is about to end
         *
         * @since  1.0.0
         * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
         */
        public function yith_wcact_send_emails_bidders( $product_id ) {

            $product = wc_get_product($product_id);

            if( $product && 'auction' == $product->get_type()  &&  !$product->is_closed()  && !$product->get_is_closed_by_buy_now() ) {

                $query = YITH_Auctions()->bids;
                $users = $query->get_users($product_id);
                foreach ($users as $id => $user_id) {
                    WC()->mailer();
                    do_action('yith_wcact_end_auction', (int)$user_id->user_id, $product_id);
                }

                if ('yes' == get_option('yith_wcact_settings_tab_auction_allow_subscribe', 'no')) {
                    $product = wc_get_product($product_id);
                    $users = $product->get_watchlist();
                    if ($users) {
                        foreach ($users as $user) {
                            WC()->mailer();
                            do_action('yith_wcact_end_auction', $user, $product_id);
                        }
                    }
                }

            }

        }
        /**
         * Create single event when auction ends
         * Create single event for send emails to user when the auction is about to end
         *
         * @since  1.0.9
         * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
         */
        public function cron_emails_auctions ($product_id) {
            $product = wc_get_product($product_id);
            $time = $product->get_end_date('edit');
            wp_schedule_single_event( $time, 'yith_wcact_send_emails_auction', array( $product_id ) );
        }

        /**
         * Sends email
         * Send emails when end auction and admin check this option = true
         *
         * @since  1.0.9
         * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
         */
        public function yith_wcact_send_emails($product_id)
        {
            $product = wc_get_product($product_id);

            if( $product && 'auction' == $product->get_type() ) {

                $instance = YITH_Auctions()->bids;
                $max_bid = $instance->get_max_bid($product_id);

                if (!$product->get_is_closed_by_buy_now() && ('publish' == $product->get_status() || 'private' == $product->get_status() )) {

                    if ($product->has_reserve_price() && $product->get_price() < $product->get_reserve_price() && $max_bid) { //Admin email

                        WC()->mailer();

                        if (defined('YITH_WPV_PREMIUM') && YITH_WPV_PREMIUM) {
                            $vendor = yith_get_vendor($product, 'product');
                            if ($vendor->is_valid() && !user_can($vendor->id, 'manage_options')) {

                                do_action('yith_wcact_vendor_not_reached_reserve_price', $product, $vendor);

                            } else {
                                do_action('yith_wcact_not_reached_reserve_price', $product);
                            }
                        } else {
                            do_action('yith_wcact_not_reached_reserve_price', $product);
                        }


                    } else {
                        if ($max_bid) { //Then we send the email to the winner with the button for paying the order.
                            $user = get_user_by('id', $max_bid->user_id);

                            WC()->mailer();

                            //Send email to winner customer
                            do_action('yith_wcact_auction_winner', $product, $user);
                            do_action('yith_wcact_email_winner_admin', $product, $user);

	                        if (defined('YITH_WPV_PREMIUM') && YITH_WPV_PREMIUM ) {
		                        $vendor = yith_get_vendor( $product, 'product' );
		                        if ( $vendor->is_valid() && ! user_can( $vendor->id, 'manage_options' ) ) {

			                        do_action( 'yith_wcact_email_winner_vendor', $product, $vendor,$user );

		                        }
	                        }

                            //Send email to users who did not win the auction after it is finished
                            if ('yes' == get_option('yith_wcact_settings_tab_auction_no_winner_email', 'no')) {

                                $users = $instance->get_users($product_id);

                                if ($users) {

                                    foreach ($users as $bidder) {

                                        if ($bidder->user_id != $max_bid->user_id) {

                                            $user = get_user_by('id', $bidder->user_id);
                                            WC()->mailer();
                                            do_action('yith_wcact_auction_no_winner', $product, $user);

                                        }
                                    }
                                }
                            }

                        } else {//The auction is finished without any bids
                            WC()->mailer();

                            if (defined('YITH_WPV_PREMIUM') && YITH_WPV_PREMIUM) {
                                $vendor = yith_get_vendor($product, 'product');
                                if ($vendor->is_valid()  && !user_can($vendor->id, 'manage_options')) {

                                    do_action('yith_wcact_vendor_finished_without_any_bids', $product, $vendor);

                                } else {

                                    $time = $product->get_automatic_reschedule_time();
                                    if ($time['time_quantity'] > 0) {
                                        $end_auction = $product->get_end_date('edit');
                                        $new_end_auction = strtotime((sprintf("+%d %s", $time['time_quantity'], $time['time_unit'])), $end_auction);
                                        $product->set_end_date($new_end_auction);
                                        $product->save();
                                        $this->cron_emails($product_id);
                                        $this->cron_emails_auctions($product_id);
                                    } else {
                                        do_action('yith_wcact_finished_without_any_bids', $product);
                                    }
                                }
                            } else {
                                $time = $product->get_automatic_reschedule_time();
                                if ($time['time_quantity'] > 0) {
                                    $end_auction = $product->get_end_date('edit');
                                    $new_end_auction = strtotime((sprintf("+%d %s", $time['time_quantity'], $time['time_unit'])), $end_auction);
                                    $product->set_end_date($new_end_auction);
                                    $product->save();
                                    $this->cron_emails($product_id);
                                    $this->cron_emails_auctions($product_id);
                                } else {
                                    do_action('yith_wcact_finished_without_any_bids', $product);
                                }
                            }
                        }
                    }
                }
            }
        }
        /**
         * Sends email
         * Send winner email cron job
         *
         * @since  1.2.2
         * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
         */
        public function cron_resend_winner_email() {

            $args = array(
                'post_type'   => 'product',
                'numberposts' => -1,
                'fields'      => 'ids',
                'meta_query'  => array(
                    'relation' => 'AND',
                    array(
                        'key'     => 'yith_wcact_winner_email_is_not_send',
                        'value'   => '1',
                        'compare' => '='
                    ),
                    array(
                        'key'     => '_yith_auction_to',
                        'value'   => strtotime( 'now' ),
                        'compare' => '<='
                    )
                ));
            // Get all Auction ids
            $auction_ids = get_posts( $args );



            if ( $auction_ids ) {

                foreach ( $auction_ids as $auction_id ) {

                    $product = wc_get_product($auction_id);
                    $instance = YITH_Auctions()->bids;
                    $max_bidder = $instance->get_max_bid($product->get_id());
                    if( $max_bidder ) {
                        $user = get_user_by('id', $max_bidder->user_id);
                        $product->set_send_winner_email( false );
                        yit_delete_prop($product,'yith_wcact_winner_email_is_not_send',false);

                        $product->save();

                        WC()->mailer();

                        do_action('yith_wcact_auction_winner', $product, $user);
                    }
                }
            }

        }
    }

}

return new YITH_WCACT_Cron();
