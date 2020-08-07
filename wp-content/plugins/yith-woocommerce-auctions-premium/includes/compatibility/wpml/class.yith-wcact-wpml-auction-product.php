<?php
if ( !defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

/**
 * WPML Auction Product Class
 *
 * @class   YITH_WCACT_WPML_Auction_Product
 * @package Yithemes
 * @since   1.0.3
 * @author  Yithemes
 *
 */
class YITH_WCACT_WPML_Auction_Product {
    /**
     * Single instance of the class
     *
     * @var \YITH_WCACT_WPML_Auction_Product
     */
    protected static $instance;

    /**
     * @var YITH_WCACT_WPML_Compatibility
     */
    public $wpml_integration;

    /**
     * Returns single instance of the class
     *
     * @param YITH_WCACT_WPML_Compatibility $wpml_integration
     *
     * @return YITH_WCACT_WPML_Auction_Product
     */
    public static function get_instance( $wpml_integration ) {
        if ( is_null( static::$instance ) ) {
            static::$instance = new static( $wpml_integration );
        }

        return static::$instance;
    }

    /**
     * Constructor
     *
     * @access protected
     *
     * @param YITH_WCACT_WPML_Compatibility $wpml_integration
     */
    protected function __construct( $wpml_integration ) {
        global $woocommerce_wpml;
        $this->wpml_integration = $wpml_integration;
        //add_filter('woocommerce_product_get_price',array($this,'get_parent_price'),10,2);
        
        add_filter('yith_wcact_auction_product_id',array($this,'get_parent_id'));
        add_filter('yith_wcact_get_auction_product',array($this,'get_parent_product'));
        add_filter('yith_wcact_auction_product_price',array($this,'auction_product_price_in_customer_currency'),10,3);
        add_filter('yith_wcact_get_buy_now_button',array($this,'yith_wcact_get_buy_now_button'),10,2);


        //yith_wcact_price_in_customer_currency
        add_filter('yith_wcact_auction_bid',array($this,'auction_product_price_in_default_currency'),10,2);
        add_filter('yith_wcact_get_price_for_customers',array($this,'get_price_for_customers'),10,2);
        //yith_wcact_price_in_default_currency
        //Poner el currency fuera de la llamada ajax
    }

    /**
     * Get parent price
     *
     * @param $value
     * @param $key
     * @param $product
     *
     * @return mixed
     */
    public function get_parent_price($price,$product) {
        global $wpml_post_translations;
        $id = $product->get_id();
        if ($wpml_post_translations && $parent_id = $wpml_post_translations->get_original_element($id)) {
            $parent_product = wc_get_product($parent_id);
            remove_filter('woocommerce_product_get_price',array($this,'get_parent_price'),10,2);
            $price = $parent_product->get_price();
            add_filter('woocommerce_product_get_price',array($this,'get_parent_price'),10,2);
        }
        return apply_filters('yith_wcact_get_price_for_customers',$price,$product);
    }

    /**
     * Get price in the customer currency with symbol
     *
     * @param $price
     *
     * @return $price
     */
    public function auction_product_price_in_customer_currency($price_with_currency,$price,$currency) {
        global $woocommerce_wpml;
        $price = $this->get_price_for_customers($price);
        $price = wc_price($price,array('currency'=>$currency));
        return $price;
    }
    /**
     * Get price in the customer currency
     *
     * @param $price
     *
     * @return $price
     */
    public function get_price_for_customers($price,$product='') {
        if(isset($price) && $price) {
            global $woocommerce_wpml;
            if ( isset( $woocommerce_wpml ) )
                $price = ($woocommerce_wpml->multi_currency) ? $woocommerce_wpml->multi_currency->prices->raw_price_filter($price) : $price;

        }
        return $price;
    }

    public function auction_product_price_in_default_currency($price,$currency) {
        global $woocommerce_wpml;
        $price = ($woocommerce_wpml->multi_currency) ? $woocommerce_wpml->multi_currency->prices->unconvert_price_amount($price,$currency) : $price;
        return $price;
    }

    /**
     * Get parent product id
     *
     * @param $value
     * @param $key
     * @param $product
     *
     * @return mixed
     */
    public function get_parent_id($product_id) {
        global $wpml_post_translations;
        if ( $wpml_post_translations && $parent_id = $wpml_post_translations->get_original_element( $product_id ) ) {
            return $parent_id;
        }
        return $product_id;
    }

    public function get_parent_product( $product ) {
        global $wpml_post_translations;
        $id = $product->get_id();
        if ( $wpml_post_translations && $parent_id = $wpml_post_translations->get_original_element( $id ) ) {
            $parent_product = wc_get_product($parent_id);
            return $parent_product;
        }
        return $product;
    }

    public function yith_wcact_get_buy_now_button($buy_now,$product) {
        global $wpml_post_translations;
        $id = $product->get_id();
        if ( $wpml_post_translations && $parent_id = $wpml_post_translations->get_original_element( $id ) ) {
            $parent_product = wc_get_product($parent_id);
            return $parent_product->get_buy_now();
        }
        return $buy_now;
    }
}