<?php
/**
 * Notes class
 *
 * @author  Yithemes
 * @package YITH WooCommerce Auctions
 * @version 1.0.0
 */

if ( !defined( 'YITH_WCACT_VERSION' ) ) {
    exit( 'Direct access forbidden.' );
}

if ( !class_exists( 'YITH_WCACT_Auction_Ajax_Premium' ) ) {
    /**
     * YITH_WCACT_Auction_Ajax_Premium
     *
     * @since 1.0.0
     */
    class YITH_WCACT_Auction_Ajax_Premium extends YITH_WCACT_Auction_Ajax
    {

        /**
         * Constructor
         *
         * @since  1.0.0
         * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
         */
        public function __construct()
        {
            add_action('wp_ajax_yith_wcact_reshedule_product', array($this, 'yith_wcact_reshedule_product'));
            add_action('wp_ajax_yith_wcact_update_my_account_auctions', array($this, 'yith_wcact_update_auction_list'));
            add_action('wp_ajax_yith_wcact_update_list_bids', array($this, 'update_list_bids'));
            add_action('wp_ajax_nopriv_yith_wcact_update_list_bids', array($this,'update_list_bids'));
            add_action('wp_ajax_yith_wcact_delete_customer_bid',array($this,'delete_customer_bid'));

            add_action('wp_ajax_yith_wcact_resend_winner_email',array($this,'resend_winner_email'));

            parent::__construct();
        }

        /**
         * Add a bid to the product
         *
         * @since  1.0.11
         * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
         */
        public function yith_wcact_add_bid()
        {
                //check_ajax_referer('add-bid', 'security');

                $userid = get_current_user_id();

                $user_can_make_bid = apply_filters('yith_wcact_user_can_make_bid', true, $userid);

                if (!$user_can_make_bid) {
                    die();
                }

                if ( $userid && isset($_POST['bid']) && isset($_POST['product']) && apply_filters( 'yith_wcact_check_if_add_bid',true,$userid,$_POST['product'],$_POST['bid'] ) ) {

                    $currency = isset($_POST['currency']) ? $_POST['currency'] : get_woocommerce_currency();

                    $bid = apply_filters('yith_wcact_auction_bid',$_POST['bid'],$currency);

                    // Convert bid number with only 2 decimals
                    $bid = number_format((float)$bid, 2, '.', '');

                    $product_id = apply_filters( 'yith_wcact_auction_product_id',$_POST['product'] );

                    $date = date("Y-m-d H:i:s");

                    $product = wc_get_product($product_id);

                    if ( $product && 'auction' == $product->get_type() ) {

                    $end_auction = $product->get_end_date();

                        if ( strtotime($date) < $end_auction ) {

                            $overtime = $product->get_overtime();

                            if ( $overtime ) {

                                $date_end = $end_auction;
                                $date_now = time();

                                $interval_seconds = $date_end - $date_now;
                                $interval_minutes = apply_filters('yith_wcact_interval_minutes', ceil($interval_seconds / MINUTE_IN_SECONDS), $interval_seconds, $product);
                            }

                            $set_overtime = false;

                            $bids = YITH_Auctions()->bids;

                            $current_price = $product->get_price();
                            $exist_auctions = $bids->get_max_bid($product_id);
                            $last_bid_user = $bids->get_last_bid_user($userid, $product_id);

                            if ( $exist_auctions ) { // Auction product has at least one bid

                                if ( $minimun_increment_amount = $product->get_minimum_increment_amount() ) {

                                    $max_bid_manual = apply_filters('yith_wcact_max_bid_manual', $minimun_increment_amount + $product->get_current_bid(), $product);

                                    if ( $bid >= $max_bid_manual && !$last_bid_user ) { // Customer doesn't have bids on auction product

                                        if ( $exist_auctions->bid < $bid && $exist_auctions->user_id != $userid ) {

                                            WC()->mailer();
                                            do_action('yith_wcact_better_bid', $exist_auctions->user_id, $product, $bid);

                                        } else {

                                            if (apply_filters('yith_wcact_show_message', true) && apply_filters('yith_wcact_show_outbid_message', false)) { //Notice if the auction has bidup enabled

                                                wc_add_notice(esc_html__('You were outbid. Someone has placed a maximum bid. Please try again', 'yith-auctions-for-woocommerce'), 'error');
                                            }
                                        }

                                        $bids->add_bid($userid, $product_id, $bid, $date);

                                        $args = compact('bid', 'date');

                                        WC()->mailer();

                                        do_action('yith_wcact_successfully_bid', $userid, $product, $args);

                                        $set_overtime = true;

                                        if ( apply_filters( 'yith_wcact_show_message', true ) ) {

                                            $message_successfully = apply_filters('yith_wcact_message_successfully', esc_html__('You have successfully bid', 'yith-auctions-for-woocommerce'), $bid, $product, $userid);

                                            $notice_type = apply_filters('yith_wcact_message_successfully_notice_type', 'success', $bid, $product, $userid);

                                            wc_add_notice($message_successfully, $notice_type);
                                        }

                                        yit_save_prop($product, 'yith_wcact_new_bid', true);


                                    } elseif ( $bid > $last_bid_user && $bid >= $max_bid_manual ) {

                                        if ( $exist_auctions->bid < $bid && $exist_auctions->user_id != $userid ) {

                                            WC()->mailer();
                                            do_action('yith_wcact_better_bid', $exist_auctions->user_id, $product, $bid);

                                        } else {

                                            if ( apply_filters('yith_wcact_show_message', true) && apply_filters('yith_wcact_show_outbid_message', false) ) {

                                                wc_add_notice(esc_html__('You were outbid. Someone has placed a maximum bid. Please try again', 'yith-auctions-for-woocommerce'), 'error');

                                            }
                                        }

                                        $bids->add_bid($userid, $product_id, $bid, $date);
                                        $args = compact('bid', 'date');
                                        WC()->mailer();
                                        do_action('yith_wcact_successfully_bid', $userid, $product, $args);


                                        $set_overtime = true;

                                        if ( apply_filters('yith_wcact_show_message', true) ) {

                                            $message_successfully = apply_filters('yith_wcact_message_successfully', esc_html__('You have successfully bid', 'yith-auctions-for-woocommerce'), $bid, $product, $userid);
                                            $notice_type = apply_filters('yith_wcact_message_successfully_notice_type', 'success', $bid, $product, $userid);
                                            wc_add_notice($message_successfully, $notice_type);
                                        }
                                        yit_save_prop($product, 'yith_wcact_new_bid', true);

                                    } else {

                                        if ( $last_bid_user > $max_bid_manual ) {

                                            $max_bid_manual = $last_bid_user + $minimun_increment_amount;
                                        }

                                        if ( apply_filters('yith_wcact_show_message', true) ) {

                                            wc_add_notice(sprintf(esc_html__('Enter %s or more to be able to bid', 'yith-auctions-for-woocommerce'),

                                                wc_price($max_bid_manual)), 'error');

                                        }

                                    }

                                } else {

                                    if ( $bid > $current_price && !$last_bid_user ) {  // Customer doesn't have bids on auction product

                                        if ( $exist_auctions->bid < $bid && $exist_auctions->user_id != $userid ) {

                                            WC()->mailer();
                                            do_action('yith_wcact_better_bid', $exist_auctions->user_id, $product, $bid);

                                        } else {

                                            if ( apply_filters('yith_wcact_show_message', true) && apply_filters('yith_wcact_show_outbid_message', false) ) {

                                                wc_add_notice(esc_html__('You were outbid. Someone has placed a maximum bid. Please try again', 'yith-auctions-for-woocommerce'), 'error');
                                            }
                                        }

                                        $bids->add_bid($userid, $product_id, $bid, $date);
                                        $args = compact('bid', 'date');
                                        WC()->mailer();
                                        do_action('yith_wcact_successfully_bid', $userid, $product, $args);

                                        $set_overtime = true;

                                        if ( apply_filters('yith_wcact_show_message', true) ) {
                                            $message_successfully = apply_filters('yith_wcact_message_successfully', esc_html__('You have successfully bid', 'yith-auctions-for-woocommerce'), $bid, $product, $userid);
                                            $notice_type = apply_filters('yith_wcact_message_successfully_notice_type', 'success', $bid, $product, $userid);
                                            wc_add_notice($message_successfully, $notice_type);
                                        }

                                        yit_save_prop($product, 'yith_wcact_new_bid', true);


                                    } elseif ( $bid > $last_bid_user && $bid > $current_price ) {

                                        if ( $exist_auctions->bid < $bid && $exist_auctions->user_id != $userid ) {

                                            WC()->mailer();
                                            do_action('yith_wcact_better_bid', $exist_auctions->user_id, $product, $bid);
                                        } else {

                                            if ( apply_filters('yith_wcact_show_message', true) && apply_filters('yith_wcact_show_outbid_message', false) ) {

                                                wc_add_notice(esc_html__('You were outbid. Someone has placed a maximum bid. Please try again', 'yith-auctions-for-woocommerce'), 'error');
                                            }
                                        }

                                        $bids->add_bid($userid, $product_id, $bid, $date);
                                        $args = compact('bid', 'date');
                                        WC()->mailer();
                                        do_action('yith_wcact_successfully_bid', $userid, $product, $args);

                                        $set_overtime = true;

                                        if ( apply_filters('yith_wcact_show_message', true) ) {

                                            $message_successfully = apply_filters('yith_wcact_message_successfully', esc_html__('You have successfully bid', 'yith-auctions-for-woocommerce'), $bid, $product, $userid);
                                            $notice_type = apply_filters('yith_wcact_message_successfully_notice_type', 'success', $bid, $product, $userid);
                                            wc_add_notice($message_successfully, $notice_type);
                                        }

                                        yit_save_prop($product, 'yith_wcact_new_bid', true);

                                    } else {

                                        if ( apply_filters('yith_wcact_show_message', true) ) {

                                            wc_add_notice(sprintf(esc_html__('Enter %s or more to be able to bid', 'yith-auctions-for-woocommerce'),
                                                apply_filters('yith_wcact_auction_product_price', wc_price($product->get_current_bid()), $product->get_current_bid(), $currency)), 'error');
                                        }
                                    }

                                }

                            } else { //No bids on auction product
                                //Filter check bid increment on first bid for auction product
                                if ( apply_filters('yith_wcact_check_bid_increment', false, $product) && $product->get_minimum_increment_amount() && $bid < $max_bid_manual = $product->get_minimum_increment_amount() + $product->get_current_bid() ) {

                                    if ( apply_filters('yith_wcact_show_message', true) ) {

                                        wc_add_notice(sprintf(esc_html__('Enter %s or more to be able to bid', 'yith-auctions-for-woocommerce'),
                                            apply_filters('yith_wcact_auction_bid_increment_price', wc_price($max_bid_manual), $product, $product->get_current_bid(), $currency)), 'error');
                                    }

                                } elseif ( $bid >= $current_price ) {

                                    $bids->add_bid($userid, $product_id, $bid, $date);
                                    $args = compact('bid', 'date');

                                    WC()->mailer();
                                    do_action('yith_wcact_successfully_bid', $userid, $product, $args);

                                    $set_overtime = true;

                                    if ( apply_filters('yith_wcact_show_message', true) ) {

                                        $message_successfully = apply_filters('yith_wcact_message_successfully', esc_html__('You have successfully bid', 'yith-auctions-for-woocommerce'), $bid, $product, $userid);
                                        $notice_type = apply_filters('yith_wcact_message_successfully_notice_type', 'success', $bid, $product, $userid);
                                        wc_add_notice($message_successfully, $notice_type);
                                    }

                                    yit_save_prop($product, 'yith_wcact_new_bid', true);
                                }
                            }
                            $user_bid = array(
                                'user_id' => $userid,
                                'product_id' => $product_id,
                                'bid' => $bid,
                                'date' => $date,
                                'url' => get_permalink($_POST['product']),
                            );

                            $actual_price = $product->get_current_bid();

                            $product->set_price($actual_price);

                            if ( $set_overtime && $overtime ) {

                                if ( $interval_minutes <= $product->check_for_overtime() ) {


                                    $new_date_finish = apply_filters('yith_wcact_new_date_finish', strtotime('+' . $overtime . 'minute', $date_end), $overtime, $date_end, $product);

                                    //Remove cronjob for winner email
                                    if (wp_next_scheduled('yith_wcact_send_emails_auction', array( $product_id ))) {
                                        wp_clear_scheduled_hook('yith_wcact_send_emails_auction', array( $product_id ));
                                    }

                                    //Add new cronjob with the new end auction (end_auction + overtime)
                                    if (wp_next_scheduled('yith_wcact_send_emails_auction_overtime', array( $product_id ))) {
                                        wp_clear_scheduled_hook('yith_wcact_send_emails_auction_overtime', array( $product_id ));
                                    }
                                    wp_schedule_single_event($new_date_finish, 'yith_wcact_send_emails_auction_overtime', array( $product_id ));

                                    $product->set_end_date($new_date_finish);

                                    $product->set_is_in_overtime( true );

                                }
                            }

                            $product->save();

                            wp_send_json($user_bid);
                        }

                    } else {

                        $url = array(
                            'url' => get_permalink($product_id),
                        );
                        wp_send_json($url);
                    }

                } else {

                    $url = array(
                        'url' => get_permalink($_POST['product']),
                    );
                    wp_send_json($url);
                }
            die();
        }

        /**
         * Reshedule auction product
         *
         * @since  1.0.14
         * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
         */
        public function yith_wcact_reshedule_product()
        {
            if ( isset( $_POST['id'] ) ) {

                $id = $_POST['id'];
                $product = wc_get_product($id['ID']);

                if( $product && 'auction' == $product->get_type() ) {

                    $product->set_stock_status('instock');

                    $bids = YITH_Auctions()->bids;
                    $bids->reshedule_auction($product->get_id());

                    $product->set_is_closed_by_buy_now(false);
                    $product->set_is_in_overtime( false );
                    $product->set_auction_paid_order( false );

                    $product->set_send_winner_email( false );
                    $product->set_send_admin_winner_email( false );


                    /*Product has a watchlist*/
                    /*TODO check this meta to pass to CRUD metas*/
                    if ($product->get_watchlist()) {
                        yit_delete_prop($product, 'yith_wcact_auction_watchlist', false);
                    }

                    yit_delete_prop($product, 'yith_wcact_send_admin_not_reached_reserve_price', false);
                    yit_delete_prop($product, 'yith_wcact_send_admin_without_any_bids', false);

                    //delete winner email user prop (since v2.0.1)
                    yit_delete_prop($product, 'yith_wcact_winner_email_is_send', false);
                    yit_delete_prop($product, 'yith_wcact_winner_email_send_custoner', false);
                    yit_delete_prop($product, 'yith_wcact_winner_email_is_not_send', false);
                    yit_delete_prop($product,'current_bid', false);

                    $product->save();

                    $array = array(
                        'product_id' => $id,
                        'url' => get_edit_post_link($id),
                    );

                    wp_send_json($array);
                }
            }
            die();
        }

        /**
         * Update auction list
         *
         * @since  1.0.14
         * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
         */
        public function yith_wcact_update_auction_list()
        {
            $instance = YITH_Auctions()->bids;
            $user_id = get_current_user_id();
            $auctions_by_user = $instance->get_auctions_by_user($user_id);
            $currency = isset($_POST['currency']) ? $_POST['currency'] : get_woocommerce_currency();
            $auction = array();
            foreach ($auctions_by_user as $valor) {
                $product = wc_get_product($valor->auction_id);

                if (!$product)
                    continue;

                $max_bid = $instance->get_max_bid($valor->auction_id);

                if($max_bid->user_id == $user_id) {
                    $color = 'yith-wcact-max-bidder';
                }else{
                    $color = 'yith-wcact-outbid-bidder';
                }

                $auction[] = array(
                    'product_id' => $product->get_id(),
                    'price' => wc_price($product->get_price(),array('currency' => $currency)),
                    'product_name' => get_the_title($valor->auction_id),
                    'product_url' => get_the_permalink($valor->auction_id),
                    'image' => $product->get_image('thumbnail'),
                    'my_bid' => apply_filters('yith_wcact_auction_product_price',wc_price($valor->max_bid),$valor->max_bid,$currency),
                    'status' => $this->yith_wcact_get_status($product, $valor, $user_id, $instance),
                    'color' => $color,
                );
            }
            wp_send_json($auction);
        }

        /**
         * Get status of an auctions
         *
         * @since  1.0.14
         * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
         */
        function yith_wcact_get_status($product, $valor, $user_id, $instance)
        {

            if ($product->is_type('auction') && $product->is_closed()) {
                $max_bid = $instance->get_max_bid($valor->auction_id);

                if ($max_bid->user_id == $user_id && !$product->get_auction_paid_order() && ( !$product->has_reserve_price() || ($product->has_reserve_price() && $max_bid->bid >= $product->get_reserve_price())) ) {
                    $url = add_query_arg(array('yith-wcact-pay-won-auction' => $product->get_id()), apply_filters('yith_wcact_get_checkout_url',wc_get_checkout_url(),$product->get_id()));
                    $status = $this->print_won_auctions($url);

                } else {
                    $status = $this->status_closed();
                }
            } else {
                $status = $this->status_started();
            }
            return $status;
        }

        /**
         * Print won auctions
         *
         * @since  1.0.14
         * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
         */
        function print_won_auctions($url)
        {

            $won = apply_filters('yith_wcact_you_won_this_auction_label',esc_html__('You won this auction', 'yith-auctions-for-woocommerce'));
            $pay_now = apply_filters('yith_wcact_pay_now_label',esc_html__('Pay now', 'yith-auctions-for-woocommerce'));

            return '<span>' . $won . '</span><a href="' . $url . '" class="auction_add_to_cart_button button alt" id="yith-wcact-auction-won-auction">' . $pay_now . '</a>';
        }

        /**
         * status closed
         *
         * @since  1.0.14
         * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
         */
        function status_closed()
        {
            $closed = esc_html__('Closed', 'yith-auctions-for-woocommerce');

            return '<span>' . apply_filters('yith_wcact_auction_my_account_status_closed', $closed). '</span>';
        }

        /**
         * status started
         *
         * @since  1.0.14
         * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
         */
        function status_started()
        {
            $started = esc_html__('Started', 'yith-auctions-for-woocommerce');
            return '<span>' . apply_filters('yith_wcact_auction_my_account_status_open', $started) . '</span>';
        }

        /**
         * update list bid tab
         *
         * @since  1.1.0
         * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
         */
        public function update_list_bids()
        {

            if( isset( $_POST['product'] ) ) {

                $product = wc_get_product($_POST['product']);

                $currency = isset($_POST['currency']) ? $_POST['currency'] : get_woocommerce_currency();
                $templates = array();
                $args = array(
                    'product' => $product,
                    'currency' => $currency,
                );
                ob_start();
                wc_get_template('list-bids.php', $args, '', YITH_WCACT_TEMPLATE_PATH . 'frontend/');
                $templates['list_bids'] = ob_get_clean();
                $templates['current_bid'] = wc_price($product->get_price(), array('currency' => $currency));
                ob_start();
                wc_get_template('max-bidder.php', $args, '', YITH_WCACT_TEMPLATE_PATH . 'frontend/');
                $templates['max_bid'] = ob_get_clean();
                ob_start();
                wc_get_template('reserve_price_and_overtime.php', $args, '', YITH_WCACT_TEMPLATE_PATH . 'frontend/');
                $templates['reserve_price_and_overtime'] = ob_get_clean();

                if ($product->is_in_overtime()) {
                    ob_start();
                    wc_get_template('auction-timeleft.php', $args, '', YITH_WCACT_TEMPLATE_PATH . 'frontend/');
                    $templates['timeleft'] = ob_get_clean();
                }

                wp_send_json($templates);
            }
        }

        /**
         * delete customer bid
         *
         * @since  1.1.0
         * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
         */
        public function delete_customer_bid()
        {
            $product_id = $_POST['product_id'];
            $user_id =  $_POST['user_id'];
            $datetime = $_POST['date'];
            $bid = $_POST['bid'];
            $instance = YITH_Auctions()->bids;
            $instance->delete_customer_bid($product_id,$user_id,$datetime);

            $product = wc_get_product($product_id);
            yit_delete_prop($product,'current_bid');

            $instance = YITH_Auctions()->bids;
            $max_bidder = $instance->get_max_bid($product->get_id());
            if( $max_bidder ) {
                $price = $product->get_price();
                yit_save_prop($product,'current_bid',$price);
            }

            $args = compact('bid', 'datetime');
            WC()->mailer();
            do_action('yith_wcact_auction_delete_customer_bid', $product_id, $user_id,$args);
            do_action('yith_wcact_auction_delete_customer_bid_admin',$product_id, $user_id,$args);

            die();
        }

        /**
         * Resend Winner Email
         *
         * @since  1.2.2
         * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
         */
        public function resend_winner_email() {

            if ( isset($_POST['id'] ) ) {

                $id = $_POST['id'];
                $product = wc_get_product($id['ID']);

                $instance = YITH_Auctions()->bids;
                $max_bidder = $instance->get_max_bid($product->get_id());

                $user = get_user_by('id', $max_bidder->user_id);

                $product->set_send_winner_email( false );

                $product->save();

                WC()->mailer();

                do_action('yith_wcact_auction_winner', $product, $user);

                $args = array(
                    'post_id' => $id['ID'],
                    'product' => $product,
                );

                ob_start();

                wc_get_template('admin-auction-status.php', $args, '', YITH_WCACT_TEMPLATE_PATH . 'admin/');
                $templates['resend_winner_email'] = ob_get_clean();

                wp_send_json($templates);
            }
            die();
        }

    }
}