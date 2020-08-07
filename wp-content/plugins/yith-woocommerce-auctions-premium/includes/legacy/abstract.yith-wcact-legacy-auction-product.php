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
 * @class      YITH_WCACT_Legacy_Auction_Product
 * @package    Yithemes
 * @since      Version 1.3.4
 * @author     Your Inspiration Themes
 *
 */

if ( !class_exists( 'YITH_WCACT_Legacy_Auction_Product' ) ) {
    /**
     * Class YITH_WCACT_Legacy_Auction_Product
     * the Auction Product
     *
     * @author Carlos Rodríguez <carlos.rodriguez@yithemes.com>
     */
    abstract class YITH_WCACT_Legacy_Auction_Product extends WC_Product {


        /**
         * Get minimum manual bid increment
         *
         * @since  1.3.4
         */
        public function get_minimum_manual_bid_increment() {

            $this->yith_wcact_deprecated_function('WC_Product_Auction_Premium::get_minimum_manual_bid_increment()','WC_Product_Auction_Premium::get_minimum_increment_amount','1.3.4');

            $this->get_minimum_increment_amount();
        }

        /**
         * Get buy now price
         *
         * @since  1.3.4
         */
        public function get_buy_now_price() {

            $this->yith_wcact_deprecated_function('WC_Product_Auction_Premium::get_buy_now_price()','WC_Product_Auction_Premium::get_buy_now()','1.3.4');

            $this->get_buy_now();

        }

        /**
         *  Check if the auction is close for user click in buttom buy_now and place order.
         *
         */
        public function is_closed_for_buy_now()
        {

            $this->yith_wcact_deprecated_function('WC_Product_Auction_Premium::is_closed_for_buy_now()','WC_Product_Auction_Premium::get_is_closed_by_buy_now()','1.3.4');


           $this->get_is_closed_by_buy_now();
        }

        /**
         *  Check if the auction is paid
         *
         */
        public function is_paid(){

            $this->yith_wcact_deprecated_function('WC_Product_Auction_Premium::is_paid()','WC_Product_Auction_Premium::get_auction_paid_order()','1.3.4');

            $this->get_auction_paid_order();

        }


        /**
         * Show deprecated notice
         *
         * @param string $function
         * @param string $replacement
         * @param string $version
         * @since  1.3.4
         */
        function yith_wcact_deprecated_function( $function,$replacement,$version ) {

            if ( is_ajax() ) {
                do_action( 'deprecated_function_run', $function, $replacement, $version );
                $log_string = "The {$function} function is deprecated since version {$version}.";
                $log_string .= $replacement ? " Replace with {$replacement}." : '';
                error_log( $log_string );
            } else {
                _deprecated_function( $function, $version, $replacement );
            }

        }

    }
}
