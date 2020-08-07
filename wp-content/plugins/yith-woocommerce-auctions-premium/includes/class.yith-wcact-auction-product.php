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

if ( ! class_exists( 'WC_Product_Auction' ) ) {
    /**
     * Class WC_Product_Auction
     *
     * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
     */
    class WC_Product_Auction extends YITH_WCACT_Legacy_Auction_Product {
        /**
         * Constructor gets the post object and sets the ID for the loaded product.
         *
         * @param int|WC_Product|object $product Product ID, post object, or product object
         */

        public function __construct( $product = 0 ) {

            yit_set_prop($this,'manage_stock','yes');

            $this->set_stock_quantity(1);

            parent::__construct( $product );
        }


        public function get_price( $context = 'view' ) {


            $price =  parent::get_price( 'edit' );

            global $wpml_post_translations;
            $id = $this->get_id();
            if ($wpml_post_translations && $parent_id = $wpml_post_translations->get_original_element($id)) {

                $parent_product = wc_get_product($parent_id);
                return apply_filters('yith_wcact_get_price_for_customers',apply_filters( 'woocommerce_product_get_price',$price ? $price : $parent_product->get_current_bid(),$this),$this);

            } else {
                return apply_filters('yith_wcact_get_price_for_customers',apply_filters( 'woocommerce_product_get_price',$price ? $price : $this->get_current_bid(),$this),$this);
            }
        }

        /**
         *  Check if the auction is start.
         *
         */
        public function is_start() {
            $start_time = $this->get_start_date();
            if ( isset($start_time) && $start_time ){

                $date_for = $start_time;
                $date_now = strtotime('now');

                if( $date_for <= $date_now){

                    return TRUE;

                } else{

                    return FALSE;
                }

            } else {

                return TRUE;
            }
        }

        /**
         *  Check if the auction is close.
         *
         */
        public function is_closed() {
            $end_time = $this->get_end_date();
            if ( isset($end_time) && $end_time ) {
                $date_to = $end_time;
                $date_now = strtotime('now');

                if ( $date_to <= $date_now){

                    return TRUE;
                } else {
                    return FALSE;
                }


            } else {
                return TRUE;
            }
        }


        /**
         *  return status of auction
         *
         */
        public function get_auction_status(){

            if ( $this->is_start() && !$this->is_closed() ) {
                return 'started';

            } elseif ( $this->is_closed() ) {
                return 'finished';

            } else {
                return 'non-started';
            }
        }

    }

}




