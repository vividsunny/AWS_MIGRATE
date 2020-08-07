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

if ( !class_exists( 'YITH_WCACT_Auction_Ajax' ) ) {
    /**
     * YITH_WCACT_Auction_Ajax
     *
     * @since 1.0.0
     */
    class YITH_WCACT_Auction_Ajax
    {

        /**
         * Single instance of the class
         *
         * @var \YITH_WCACT_Auction_Ajax
         * @since 1.0.0
         */
        protected static $instance;


        /**
         * Returns single instance of the class
         *
         * @return \YITH_WCACT_Auction_Ajax
         * @since 1.0.0
         */
        public static function get_instance() {
            $self = __CLASS__ . ( class_exists( __CLASS__ . '_Premium' ) ? '_Premium' : '' );

            if ( is_null( $self::$instance ) ) {
                $self::$instance = new $self;
            }

            return $self::$instance;
        }

        /**
         * Constructor
         *
         * @since  1.0.0
         * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
         */
        public function __construct()
        {
            add_action('wp_ajax_yith_wcact_add_bid', array($this, 'yith_wcact_add_bid'));
            add_action('wp_ajax_nopriv_yith_wcact_add_bid', array($this, 'redirect_to_my_account'));
        }


        /**
         * Redirect to user (My account)
         *
         * @since  1.0.0
         * @author Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
         */
        public function redirect_to_my_account()
        {
            if (!is_user_logged_in()) {
                
                $account = apply_filters('yith_wcact_redirect_url',wc_get_page_permalink( 'myaccount'));

                if (isset($_POST['bid']) && isset($_POST['product'])) {
                    $get_product_permalink = apply_filters( 'yith_wcact_get_product_permalink_redirect_to_my_account',urlencode(get_permalink($_POST['product'])), $_POST['product'] );
                    $url_to_redirect = add_query_arg('redirect_after_login',$get_product_permalink,$account);
                    $array = array(
                        'product_id' => $_POST['product'],
                        'bid'       => $_POST['bid'],
                        'url'       => $url_to_redirect,
                    );

                }
                wp_send_json($array);
            }
            die();
        }
    }
}