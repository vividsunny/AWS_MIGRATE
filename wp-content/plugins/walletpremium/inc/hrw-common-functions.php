<?php

/*
 * Common functions
 */

if( ! defined( 'ABSPATH' ) )
    exit ; // Exit if accessed directly.

include_once('hrw-layout-functions.php') ;
include_once('hrw-post-functions.php') ;
include_once('hrw-formatting-functions.php') ;
include_once('hrw-premium-functions.php') ;
include_once('hrw-template-functions.php') ;

if( ! function_exists( 'hrw_check_is_array' ) ) {

    function hrw_check_is_array( $data ) {
        return ( is_array( $data ) && ! empty( $data ) ) ;
    }

}

if( ! function_exists( 'hrw_page_screen_ids' ) ) {

    function hrw_page_screen_ids() {
        return apply_filters( 'hrw_page_screen_ids' , array(
            HRW_Register_Post_Types::WALLET_POSTTYPE ,
            HRW_Register_Post_Types::TRANSACTION_LOG_POSTTYPE ,
            'wallet-premium_page_hrw_settings'
                ) ) ;
    }

}

if( ! function_exists( 'hrw_formatted_price' ) ) {

    function hrw_formatted_price( $price ) {

        $price = apply_filters( 'hrw_alter_price' , $price ) ;

        $formatted_price = sprintf( get_woocommerce_price_format() , get_woocommerce_currency_symbol() , $price ) ;

        return apply_filters( 'hrw_formatted_price' , $formatted_price , $price ) ;
    }

}

if( ! function_exists( 'hrw_price' ) ) {

    function hrw_price( $price , $args = array() ) {

        $args = apply_filters( 'hrw_price_args' , wp_parse_args( $args , array( 'decimals' => hrw_get_price_decimals() ) ) ) ;

        $price = apply_filters( 'hrw_alter_price' , ( float ) $price ) ;

        return apply_filters( 'hrw_price' , wc_price( $price , $args ) , $price , $args ) ;
    }

}

if( ! function_exists( 'hrw_get_allowed_setting_tabs' ) ) {

    function hrw_get_allowed_setting_tabs() {

        return apply_filters( 'hrw_settings_tabs_array' , array() ) ;
    }

}

if( ! function_exists( 'hrw_get_wc_order_statuses' ) ) {

    function hrw_get_wc_order_statuses() {

        $order_statuses_keys   = array_keys( wc_get_order_statuses() ) ;
        $order_statuses_keys   = str_replace( 'wc-' , '' , $order_statuses_keys ) ;
        $order_statuses_values = array_values( wc_get_order_statuses() ) ;
        $order_statuses        = array_combine( $order_statuses_keys , $order_statuses_values ) ;

        $not_in_statuses = array( 'failed' , 'refunded' , 'cancelled' ) ;

        $selected_statuses = array() ;
        foreach( $order_statuses as $key => $values ):
            if( ! in_array( $key , $not_in_statuses ) ):
                $selected_statuses[ $key ] = $values ;
            endif ;
        endforeach ;

        return $selected_statuses ;
    }

}

if( ! function_exists( 'hrw_get_paid_order_statuses' ) ) {

    function hrw_get_paid_order_statuses() {

        $statuses = array(
            'processing' => esc_html__( 'Processing' , HRW_LOCALE ) ,
            'completed'  => esc_html__( 'Completed' , HRW_LOCALE ) ,
                ) ;

        return $statuses ;
    }

}


if( ! function_exists( 'hrw_get_page_ids' ) ) {

    function hrw_get_page_ids() {
        $format_page_ids = array() ;
        $pages           = get_pages() ;

        if( ! hrw_check_is_array( $pages ) )
            return $format_page_ids ;

        foreach( $pages as $page ) {

            if( ! is_object( $page ) )
                continue ;

            $format_page_ids[ $page->ID ] = $page->post_title ;
        }

        return $format_page_ids ;
    }

}

if( ! function_exists( 'hrw_get_page_id' ) ) {

    function hrw_get_page_id( $page_name = 'register' , $permalink = false , $default = false ) {

        $page_id = get_option( 'hrw_' . $page_name . '_page_id' , $default ) ;
        if( $permalink )
            return get_permalink( $page_id ) ;

        return $page_id ;
    }

}

if( ! function_exists( 'hrw_get_wc_categories' ) ) {

    function hrw_get_wc_categories() {
        $categories    = array() ;
        $wc_categories = get_terms( 'product_cat' ) ;

        if( ! hrw_check_is_array( $wc_categories ) )
            return $categories ;

        foreach( $wc_categories as $category ) {
            $categories[ $category->term_id ] = $category->name ;
        }

        return $categories ;
    }

}

if( ! function_exists( 'hrw_get_user_roles' ) ) {

    function hrw_get_user_roles() {
        global $wp_roles ;
        $user_roles = array() ;

        if( ! isset( $wp_roles->roles ) || ! hrw_check_is_array( $wp_roles->roles ) )
            return $user_roles ;

        foreach( $wp_roles->roles as $slug => $role ) {
            $user_roles[ $slug ] = $role[ 'name' ] ;
        }

        return $user_roles ;
    }

}

if( ! function_exists( 'hrw_get_wc_available_gateways' ) ) {

    function hrw_get_wc_available_gateways() {
        $available_gateways = array() ;
        $wc_gateways        = WC()->payment_gateways->payment_gateways() ;

        if( ! hrw_check_is_array( $wc_gateways ) )
            return $available_gateways ;

        foreach( $wc_gateways as $gateway ) {
            if( $gateway->id == 'HR_Wallet_Gateway' )
                continue ;

            $available_gateways[ $gateway->id ] = $gateway->title ;
        }

        return $available_gateways ;
    }

}

if( ! function_exists( 'hrw_get_settings_page_url' ) ) {

    /**
     * Function to get event page URL
     * */
    function hrw_get_settings_page_url( $args = array() ) {

        $url = admin_url( 'admin.php?page=hrw_settings' ) ;

        if( hrw_check_is_array( $args ) )
            $url = add_query_arg( $args , $url ) ;

        return $url ;
    }

}

if( ! function_exists( 'hrw_get_wallet_page_url' ) ) {

    /**
     * Function to get event page URL
     * */
    function hrw_get_wallet_page_url( $args = array() ) {

        $url = admin_url( 'edit.php?post_type=' . HRW_Register_Post_Types::WALLET_POSTTYPE ) ;

        if( hrw_check_is_array( $args ) )
            $url = add_query_arg( $args , $url ) ;

        return $url ;
    }

}

if( ! function_exists( 'hrw_get_wallet_page_url' ) ) {

    /**
     * Function to get event page URL
     * */
    function hrw_get_transaction_log_page_url( $args = array() ) {

        $url = admin_url( 'edit.php?post_type=' . HRW_Register_Post_Types::TRANSACTION_LOG_POSTTYPE ) ;

        if( hrw_check_is_array( $args ) )
            $url = add_query_arg( $args , $url ) ;

        return $url ;
    }

}

if( ! function_exists( 'hrw_topup_product_in_cart' ) ) {

    function hrw_topup_product_in_cart() {

        foreach( WC()->cart->get_cart() as $key => $value ) {
            if( ! isset( $value[ 'hrw_wallet' ] ) )
                continue ;

            if( 'auto' === $value[ 'hrw_wallet' ][ 'topup_mode' ] ) {
                continue ;
            }

            if( HRW_Topup_Handler::$topup_product != $value[ 'hrw_wallet' ][ 'product_id' ] )
                continue ;

            return $value[ 'hrw_wallet' ] ;
        }

        return false ;
    }

}

if( ! function_exists( 'hrw_gift_product_in_cart' ) ) {

    function hrw_gift_product_in_cart() {

        foreach( WC()->cart->get_cart() as $key => $value ) {
            if( ! isset( $value[ 'hrw_gift_card' ] ) )
                continue ;

            if( HRW_GiftCard::$hrw_gift_product != $value[ 'hrw_gift_card' ][ 'product_id' ] )
                continue ;

            return $value[ 'hrw_gift_card' ] ;
        }

        return false ;
    }

}

if( ! function_exists( 'hrw_topup_related_product_in_cart' ) ) {

    function hrw_topup_related_product_in_cart() {
        return apply_filters( 'hrw_cart_contains_topup_related_product' , hrw_topup_product_in_cart() ) ;
    }

}

if( ! function_exists( 'hrw_create_new_wallet_product' ) ) {

    /*
     * Create New Event Product
     */

    function hrw_create_new_wallet_product( $producttitle ) {
        $args = array(
            'post_author' => get_current_user_id() ,
            'post_status' => "publish" ,
            'post_title'  => $producttitle ,
            'post_type'   => "product" ,
                ) ;

        $product_id = wp_insert_post( $args ) ;

        $terms = array( 'exclude-from-search' , 'exclude-from-catalog' ) ; // for hidden..
        wp_set_post_terms( $product_id , $terms , 'product_visibility' , false ) ;

        $meta_keys = array(
            '_stock_status'      => 'instock' ,
            'total_sales'        => '0' ,
            '_downloadable'      => 'no' ,
            '_virtual'           => 'yes' ,
            '_regular_price'     => '0' ,
            '_price'             => '0' ,
            '_sale_price'        => '' ,
            '_featured'          => '' ,
            '_sold_individually' => 'yes' ,
            '_manage_stock'      => 'no' ,
            '_backorders'        => 'no' ,
            '_stock'             => '' ,
                ) ;

        foreach( $meta_keys as $key => $value ) {
            update_post_meta( $product_id , sanitize_key( $key ) , $value ) ;
        }
        return $product_id ;
    }

}


if( ! function_exists( 'hrw_customize_array_position' ) ) {

    function hrw_customize_array_position( $array , $key , $new_value ) {
        $keys  = array_keys( $array ) ;
        $index = array_search( $key , $keys ) ;
        $pos   = false === $index ? count( $array ) : $index + 1 ;

        $new_value = is_array( $new_value ) ? $new_value : array( $new_value ) ;

        return array_merge( array_slice( $array , 0 , $pos ) , $new_value , array_slice( $array , $pos ) ) ;
    }

}

if( ! function_exists( 'hrw_get_cron_interval' ) ) {

    function hrw_get_cron_interval( $interval_time , $interval_type ) {
        $interval = ( float ) get_option( $interval_time , 12 ) ;
        $type     = get_option( $interval_type , 'hours' ) ;
        if( $type == '1' ) {
            $interval = $interval * 60 ;
        } else if( $type == '2' ) {
            $interval = $interval * 3600 ;
        } else if( $type == '3' ) {
            $interval = $interval * 86400 ;
        }

        return $interval ;
    }

}

if( ! function_exists( 'hrw_get_min_value_for_number_field' ) ) {

    function hrw_get_min_value_for_number_field() {

        $number_of_decimals = get_option( 'woocommerce_price_num_decimals' ) ;
        $min_number         = 1 ;
        if( $number_of_decimals && '2' == get_option( 'hrw_general_fund_number_type' ) ) {
            $num_value = 1 ;
            for( $i = 1 ; $i <= $number_of_decimals ; $i ++ ) {
                $num_value = $num_value * 10 ;
            }
            $min_number = 1 / $num_value ;
        }

        return $min_number ;
    }

}
