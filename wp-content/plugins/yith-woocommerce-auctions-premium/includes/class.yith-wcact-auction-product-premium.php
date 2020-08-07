<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */
if ( ! defined( 'YITH_WCACT_VERSION' ) ) {
	exit( 'Direct access forbidden.' );
}

/**
 *
 *
 * @class      YITH_AUCTIONS
 * @package    Yithemes
 * @since      Version 1.0.0
 * @author     Your Inspiration Themes
 *
 */

if ( ! class_exists( 'WC_Product_Auction_Premium' ) ) {
	/**
	 * Class WC_Product_Auction_Premium
	 *
	 * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
	 */
	class WC_Product_Auction_Premium extends WC_Product_Auction
    {

        protected $auction_data_defaults = array(

            'start_price'                               => '',
            'bid_increment'                             => '',
            'minimum_increment_amount'                  => '',
            'buy_now'                                   => '',
            'reserve_price'                             => '',
            'check_time_for_overtime_option'            => '',
            'overtime_option'                           => '',
            'automatic_reschedule'                      => '',
            'automatic_reschedule_auction_unit'         => 'days',
            'upbid_checkbox'                            => 'no',
            'overtime_checkbox'                         => 'no',
            'start_date'                                => '',
            'end_date'                                  => '',
            'is_in_overtime'                            => false,
            'is_closed_by_buy_now'                      => false,
            'auction_paid_order'                        => false,
            'send_winner_email'                         => false,
            'send_admin_winner_email'                   => false,
            //'auction_status'                          => '',
            );


        /**
         * Constructor gets the post object and sets the ID for the loaded product.
         *
         * @param int|WC_Product|object $product Product ID, post object, or product object
         */

        protected $status = false;

        public function __construct($product = 0 )
        {
            $this->data = array_merge( $this->data, $this->auction_data_defaults );
            parent::__construct($product);

		}

        /**
         * Get internal type.
         *
         * @since 3.0.0
         * @return string
         */
        public function get_type() {
            return 'auction';
        }


        /*
       |--------------------------------------------------------------------------
       | Getters
       |--------------------------------------------------------------------------
       |
       | Methods for getting data from the product object.
       */

        /**
         * Get Auction start price
         *
         * @param string $context What the value is for. Valid values are view and edit.
         * @return float|boolean
         * @since 1.3.4
         */
        public function get_start_price( $context = 'view' ) {
            return $this->get_prop( 'start_price', $context );
        }

        /**
         * Get Bid increment
         *
         * @param string $context What the value is for. Valid values are view and edit.
         * @return float|boolean
         * @since 1.3.4
         */
        public function get_bid_increment( $context = 'view' ) {
            return $this->get_prop( 'bid_increment', $context );
        }

        /**
         * Get Minimum increment amount
         *
         * @param string $context What the value is for. Valid values are view and edit.
         * @return float|boolean
         * @since 1.3.4
         */
        public function get_minimum_increment_amount( $context = 'view' ) {
            return $this->get_prop( 'minimum_increment_amount', $context );
        }

        /**
         * Get Reserve price
         *
         * @param string $context What the value is for. Valid values are view and edit.
         * @return float|boolean
         * @since 1.3.4
         */
        public function get_reserve_price( $context = 'view' ) {
            return $this->get_prop( 'reserve_price', $context );
        }

        /**
         * Get Buy now price
         *
         * @param string $context What the value is for. Valid values are view and edit.
         * @return float|boolean
         * @since 1.3.4
         */
        public function get_buy_now( $context = 'view' ) {
            return $this->get_prop( 'buy_now', $context );
        }
        /**
         * Get Check time for overtime option
         *
         * @param string $context What the value is for. Valid values are view and edit.
         * @return float|boolean
         * @since 1.3.4
         */
        public function get_check_time_for_overtime_option( $context = 'view' ) {
            return $this->get_prop( 'check_time_for_overtime_option', $context );
        }
        /**
         * Get Overtime option
         *
         * @param string $context What the value is for. Valid values are view and edit.
         * @return float|boolean
         * @since 1.3.4
         */
        public function get_overtime_option( $context = 'view' ) {
            return $this->get_prop( 'overtime_option', $context );
        }
        /**
         * Get Automatic reschedule
         *
         * @param string $context What the value is for. Valid values are view and edit.
         * @return float|boolean
         * @since 1.3.4
         */
        public function get_automatic_reschedule( $context = 'view' ) {
            return $this->get_prop( 'automatic_reschedule', $context );
        }
        /**
         * Get Automatic reschedule auction unit
         *
         * @param string $context What the value is for. Valid values are view and edit.
         * @return float|boolean
         * @since 1.3.4
         */
        public function get_automatic_reschedule_auction_unit( $context = 'view' ) {
            return $this->get_prop( 'automatic_reschedule_auction_unit', $context );
        }
        /**
         * Get Upbid option on frontend
         *
         * @param string $context What the value is for. Valid values are view and edit.
         * @return float|boolean
         * @since 1.3.4
         */
        public function get_upbid_checkbox( $context = 'view' ) {
            return $this->get_prop( 'upbid_checkbox', $context );
        }
        /**
         * Get Overtime option on frontend
         *
         * @param string $context What the value is for. Valid values are view and edit.
         * @return float|boolean
         * @since 1.3.4
         */
        public function get_overtime_checkbox( $context = 'view' ) {
            return $this->get_prop( 'overtime_checkbox', $context );
        }
        /**
         * Get Start Date from Auction
         *
         * @param string $context What the value is for. Valid values are view and edit.
         * @return float|boolean
         * @since 1.3.4
         */
        public function get_start_date( $context = 'view' ) {
            return $this->get_prop( 'start_date', $context );
        }
        /**
         * Get End Date
         *
         * @param string $context What the value is for. Valid values are view and edit.
         * @return float|boolean
         * @since 1.3.4
         */
        public function get_end_date( $context = 'view' ) {
            return $this->get_prop( 'end_date', $context );
        }

        /**
         * Get Is in overtime
         *
         * @param string $context What the value is for. Valid values are view and edit.
         * @return float|boolean
         * @since 1.3.4
         */
        public function get_is_in_overtime( $context = 'view' ) {
            return $this->get_prop( 'is_in_overtime', $context );
        }

        /**
         * Get Is closed by buy now
         *
         * @param string $context What the value is for. Valid values are view and edit.
         * @return boolean
         * @since 1.3.4
         */
        public function get_is_closed_by_buy_now( $context = 'view' ) {
            return $this->get_prop( 'is_closed_by_buy_now', $context );
        }

        /**
         * Get Auction Paid order
         *
         * @param string $context What the value is for. Valid values are view and edit.
         * @return boolean
         * @since 1.3.4
         */
        public function get_auction_paid_order( $context = 'view' ) {
            return $this->get_prop( 'auction_paid_order', $context );
        }

        //---------------------- Get email properties ----------------------------------------
        /**
         * Get Send winner email
         *
         * @param string $context What the value is for. Valid values are view and edit.
         * @return boolean
         * @since 1.3.4
         */
        public function get_send_winner_email( $context = 'view' ) {
            return $this->get_prop( 'send_winner_email', $context );
        }

        /**
         * Get Send admin winner email
         *
         * @param string $context What the value is for. Valid values are view and edit.
         * @return boolean
         * @since 1.3.4
         */
        public function get_send_admin_winner_email( $context = 'view' ) {
            return $this->get_prop( 'send_admin_winner_email', $context );
        }


        /**
         * Get current bid auction product
         *
         * @return float
         * @since 1.0.0
         */
        public function get_current_bid()
        {
            $bids = YITH_Auctions()->bids;
            $start_price = $this->get_start_price();
            $current_bid = $start_price;
            $bid_increment = $this->get_bid_increment();
            $reserve_price = $this->get_reserve_price();
            $buy_now = $this->get_is_closed_by_buy_now();

            if (!$buy_now) {
                    if ($bid_increment > 0) {
                        /*-------WITH BID INCREMENT---------*/
                        $last_two_bids = $bids->get_last_two_bids($this->get_id());

                        if (count($last_two_bids) == 2) {

                            // I have two or more bids
                            $first_bid = $last_two_bids[0] && isset($last_two_bids[0]->bid) ? $last_two_bids[0]->bid : 0;
                            $second_bid = $last_two_bids[1] && isset($last_two_bids[1]->bid) ? $last_two_bids[1]->bid : 0;

                            if ($first_bid == $second_bid) {

                                $current_bid = max( $start_price, $first_bid);

                            } else {
                                $is_auto_bid = ($first_bid - $second_bid) > $bid_increment;

                                if ($first_bid >= $reserve_price && $second_bid < $reserve_price) {

                                    $current_bid = $reserve_price;

                                } elseif ($is_auto_bid) {

                                    $current_bid = max( $start_price, $second_bid + $bid_increment);

                                } else {

                                    $current_bid = max( $start_price , $first_bid);
                                }
                            }
                        } elseif (count($last_two_bids) == 1) {
                            // I have only one bid
                            $the_bid = $last_two_bids[0];

                            if ($the_bid && isset($the_bid->bid) && $the_bid->bid >= $start_price) {

                                if ($the_bid->bid >= $reserve_price && isset($reserve_price) && $reserve_price > 0) {

                                    $current_bid = $reserve_price;

                                } elseif ( 0 == $start_price) {

                                    $current_bid = $the_bid->bid;

                                } else {
                                    $current_bid = $start_price;

                                }
                            }
                        }
                    } else {
                        /*-------WITHOUT BID INCREMENT---------*/
                        $max_bid = $bids->get_max_bid($this->get_id());

                        if ($max_bid && isset($max_bid->bid) && $max_bid->bid >= $start_price) {

                            $current_bid = $max_bid->bid;
                        }
                    }
            } else {

                $current_bid = $this->get_buy_now();

            }
            $the_current_bid = apply_filters('yith_wcact_get_current_bid', $current_bid, $this);
            yit_set_prop($this, 'current_bid', $the_current_bid);

            return $the_current_bid;
        }

        /**
         * Get current status of auction
         *
         * @return string
         * @since 1.0.0
         */
        public function get_auction_status()
        {

            $instance = YITH_Auctions()->bids;
            $max_bid = $instance->get_max_bid($this->get_id());

            if ($max_bid) {

                $max_bid = $max_bid->bid;

            } else {

                $max_bid = 0;
            }

            if ($this->is_start() && !$this->is_closed()) {

                if ($this->has_reserve_price() && $max_bid < $this->get_reserve_price() && !$this->get_is_closed_by_buy_now()) {

                    return 'started-reached-reserve';

                } elseif ($this->get_is_closed_by_buy_now()) {

                    return 'finnish-buy-now';

                } else {

                    return 'started';
                }

            } elseif ($this->is_closed()) {

                if ($this->has_reserve_price() && $max_bid < $this->get_reserve_price()) {

                    return 'finished-reached-reserve';

                } else {

                    return 'finished';
                }

            } else {
                return 'non-started';
            }
        }

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        /**
         * Product has a reserve price
         *
         */

        public function has_reserve_price()
        {
            $reserve_price = $this->get_reserve_price();

            if (isset($reserve_price) && $reserve_price) {

                return TRUE;

            } else {

                return FALSE;
            }
        }


        /**
         *  return if the auction product is visible
         *
         */
        public function is_visible()
        {
            if ( ( is_shop() || is_archive() ) && 'no' == get_option('yith_wcact_show_auctions_shop_page')) {
                return apply_filters('yith_wcact_is_product_visible',false,$this);
            }
            if (('yes' == get_option('yith_wcact_hide_auctions_out_of_stock') && $this->get_is_closed_by_buy_now()) && is_shop() ) {
                return apply_filters('yith_wcact_is_product_visible',false,$this);
            }
            if ( apply_filters('yith_wcact_specific_hide_auction_closed',('yes' == get_option('yith_wcact_hide_auctions_closed')) && $this->is_closed() && is_shop() )) {
                return apply_filters('yith_wcact_is_product_visible',false,$this);
            }
            if (('yes' == get_option('yith_wcact_hide_auctions_not_started') && !$this->is_start()) && is_shop() ) {
                return apply_filters('yith_wcact_is_product_visible',false,$this);
            }


            return apply_filters('yith_wcact_is_product_visible',parent::is_visible(),$this);
        }

        /**
         *  return global or local check to add overtime
         *
         */
        public function check_for_overtime()
        {
            $check_for_overtime = $this->get_check_time_for_overtime_option();

            if (isset($check_for_overtime) && $check_for_overtime) {
                return $check_for_overtime;
            } else {
                return get_option('yith_wcact_settings_overtime_option', 0);
            }
        }

        /**
         *  return global or local overtime
         *
         */
        public function get_overtime()
        {
            $overtime = $this->get_overtime_option();
            if (isset($overtime) && $overtime) {
                return $overtime;
            } else {
                return get_option('yith_wcact_settings_overtime', 0);
            }
        }

        /**
         *  return true if is in overtime
         *
         */
        public function is_in_overtime()
        {
            $is_in_overtime = $this->get_is_in_overtime();

            if (isset($is_in_overtime) && $is_in_overtime) {

                return TRUE;

            } else {

                return FALSE;
            }
        }

        /**
         *  return automatic reschedule time
         *
         */
        public function get_automatic_reschedule_time()
        {
            $time_quantity = $this->get_automatic_reschedule();

            if (isset($time_quantity) && $time_quantity >= 0) {
                $time = array(
                    'time_quantity' => $time_quantity,
                    'time_unit' => $this->get_automatic_reschedule_auction_unit(),
                );

            } else {
                $time = array(
                    'time_quantity' => get_option('yith_wcact_settings_automatic_reschedule_auctions_number', 0),
                    'time_unit' => get_option('yith_wcact_settings_automatic_reschedule_auctions_unit', 'minutes')
                );
            }
            return $time;
        }

        /**
         *  return list of watchlist user
         *
         */
        public function get_watchlist()
        {
            $watchlist = yit_get_prop($this, 'yith_wcact_auction_watchlist', true);

            if ( isset( $watchlist ) && $watchlist ) {

                return $watchlist;
            }else {
                return false;
            }
        }

        /**
         *  insert email in watchlist
         *
         */
        public function set_watchlist( $user_email ) {
            $watchlist = yit_get_prop($this, 'yith_wcact_auction_watchlist', true);
            if(!is_array($watchlist)) {
                $watchlist = array();
            }
            $watchlist[] = $user_email;
            yit_save_prop($this,'yith_wcact_auction_watchlist',$watchlist,true);
        }

        /**
         *  return is in watchlist
         *
         */
        public function is_in_watchlist( $user_email ) {
            $watchlist = yit_get_prop($this, 'yith_wcact_auction_watchlist', true);
            if(is_array($watchlist) && in_array($user_email,$watchlist)) {
                return true;
            } else {
                return false;
            }
        }


        /*
       |--------------------------------------------------------------------------
       | Setters
       |--------------------------------------------------------------------------
       |
       | Functions for setting product data. These should not update anything in the
       | database itself and should only change what is stored in the class
       | object.
       */

        /**
         * Set Buy now price.
         *
         * @param string $buy_now Product buy now price
         * @since 1.3.4
         */
        public function set_buy_now( $buy_now ) {

            $this->set_prop( 'buy_now', $buy_now );
        }

        /**
         * Set start price.
         *
         * @param string $start_price Product start price
         * @since 1.3.4
         */
        public function set_start_price( $start_price ) {
            $this->set_prop( 'start_price', $start_price  );
        }

        /**
         * Set Buy now price.
         *
         * @param string $bid_increment Product bid increment
         * @since 1.3.4
         */
        public function set_bid_increment( $bid_increment ) {
            $this->set_prop( 'bid_increment', $bid_increment );
        }
        /**
         * Set Buy now price.
         *
         * @param string $minimum_increment_amount
         * @since 1.3.4
         */
        public function set_minimum_increment_amount( $minimum_increment_amount ) {
            $this->set_prop( 'minimum_increment_amount', $minimum_increment_amount  );
        }
        /**
         * Set Buy now price.
         *
         * @param string $reserve_price
         * @since 1.3.4
         */
        public function set_reserve_price( $reserve_price ) {

            $this->set_prop( 'reserve_price', $reserve_price );
        }
        /**
         * Set Buy now price.
         *
         * @param string $check_time_for_overtime_option
         * @since 1.3.4
         */
        public function set_check_time_for_overtime_option( $check_time_for_overtime_option ) {
            $this->set_prop( 'check_time_for_overtime_option', $check_time_for_overtime_option  );
        }
        /**
         * Set Buy now price.
         *
         * @param string $overtime_option
         * @since 1.3.4
         */
        public function set_overtime_option( $overtime_option ) {
            $this->set_prop( 'overtime_option', $overtime_option );
        }
        /**
         * Set Buy now price.
         *
         * @param string $automatic_reschedule
         * @since 1.3.4
         */
        public function set_automatic_reschedule( $automatic_reschedule ) {
            $this->set_prop( 'automatic_reschedule', $automatic_reschedule );
        }
        /**
         * Set Buy now price.
         *
         * @param string $automatic_reschedule_auction_unit
         * @since 1.3.4
         */
        public function set_automatic_reschedule_auction_unit( $automatic_reschedule_auction_unit ) {
            $this->set_prop( 'automatic_reschedule_auction_unit',  $automatic_reschedule_auction_unit  );
        }
        /**
         * Set Buy now price.
         *
         * @param string $upbid_checkbox
         * @since 1.3.4
         */
        public function set_upbid_checkbox( $upbid_checkbox ) {
            $this->set_prop( 'upbid_checkbox', $upbid_checkbox   );
        }
        /**
         * Set Buy now price.
         *
         * @param string $overtime_checkbox
         * @since 1.3.4
         */
        public function set_overtime_checkbox( $overtime_checkbox ) {
            $this->set_prop( 'overtime_checkbox', $overtime_checkbox  );
        }
        /**
         * Set Buy now price.
         *
         * @param string $start_date
         * @since 1.3.4
         */
        public function set_start_date( $start_date ) {
            $this->set_prop( 'start_date', wc_format_decimal( wc_clean( $start_date ) ) );
        }
        /**
         * Set Buy now price.
         *
         * @param string $end_date
         * @since 1.3.4
         */
        public function set_end_date( $end_date ) {
            $this->set_prop( 'end_date', wc_format_decimal( wc_clean( $end_date ) ) );
        }

        /**
         * Set is in overtime
         *
         * @param bool $
         * @since 1.3.4
         */
        public function set_is_in_overtime( $is_in_overtime ) {
            $this->set_prop( 'is_in_overtime', wc_format_decimal( wc_clean( $is_in_overtime ) ) );
        }


        /**
         * Set is closed by buy now
         *
         * @param bool $is_closed_by_buy_now
         * @since 1.3.4
         */
        public function set_is_closed_by_buy_now( $is_closed_by_buy_now ) {
            $this->set_prop( 'is_closed_by_buy_now', $is_closed_by_buy_now );
        }

        /**
         * Set auction paid order
         *
         * @param bool $is_closed_by_buy_now
         * @since 1.3.4
         */
        public function set_auction_paid_order( $auction_paid_order ) {
            $this->set_prop( 'auction_paid_order', $auction_paid_order );
        }

        //----------------------- Set email properties -------------------------------------------
        /**
         * Set send winner email
         *
         * @param bool $send_winner_email
         * @since 1.3.4
         */
        public function set_send_winner_email( $send_winner_email ) {
            $this->set_prop( 'send_winner_email', wc_string_to_bool( $send_winner_email ) );
        }

        /**
         * Set send admin winner email
         *
         * @param bool $send_admin_winner_email
         * @since 1.3.4
         */
        public function set_send_admin_winner_email( $send_admin_winner_email) {
            $this->set_prop( 'send_admin_winner_email', wc_string_to_bool( $send_admin_winner_email ) );
        }

        //-----------------------------------------------------------------------------------------

    }

}




