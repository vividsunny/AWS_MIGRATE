<?php

/*
 * Common functions
 */

if ( ! defined ( 'ABSPATH' ) )
    exit ; // Exit if accessed directly.

include_once('hrwp-post-functions.php') ;

if ( ! function_exists ( 'hrw_product_category_restriction' ) ) {

    function hrw_product_category_restriction() {
        //return if selected as all products
        if ( ($type = get_option ( 'hrw_advanced_wallet_usage_product_restriction_type' , '1' )) == '1' )
            return true ;

        $cart_contents = WC ()->cart->get_cart () ;

        if ( ! hrw_check_is_array ( $cart_contents ) )
            return true ;

        $selected_products   = get_option ( 'hrw_advanced_wallet_usage_product_restriction' , array () ) ;
        $selected_categories = get_option ( 'hrw_advanced_wallet_usage_category_restriction' , array () ) ;

        foreach ( $cart_contents as $cart_content ) {

            if ( $type == '2' ) {
                if ( in_array ( $cart_content[ 'variation_id' ] , $selected_products ) || in_array ( $cart_content[ 'product_id' ] , $selected_products ) )
                    return true ;
            } elseif ( $type == '3' ) {
                $product_categories = get_the_terms ( $cart_content[ 'product_id' ] , 'product_cat' ) ;
                if ( hrw_check_is_array ( $product_categories ) )
                    return true ;
            } else {
                $product_categories = get_the_terms ( $cart_content[ 'product_id' ] , 'product_cat' ) ;
                if ( hrw_check_is_array ( $product_categories ) ) {
                    foreach ( $product_categories as $product_category ) {
                        if ( in_array ( $product_category->term_id , $selected_categories ) )
                            return true ;
                    }
                }
            }
        }

        return false ;
    }

}

if ( ! function_exists ( 'hrw_wallet_usage_user_roles_restriction' ) ) {

    function hrw_wallet_usage_user_roles_restriction( $user_id = false ) {

        if ( ! $user_id )
            $user_id = get_current_user_id () ;

        $type = get_option ( 'hrw_advanced_wallet_usage_user_restriction_type' , '1' ) ;

        switch ( $type ) {

            case '1' :
                return true ;

            case '2':
                $selected_users = get_option ( 'hrw_advanced_wallet_usage_user_restriction' , array () ) ;

                if ( in_array ( $user_id , $selected_users ) )
                    return true ;
                break ;
            case '3':
                $selected_user_roles = get_option ( 'hrw_advanced_wallet_usage_user_role_restriction' , array () ) ;
                $user_data           = get_userdata ( $user_id ) ;
                $user_roles          = $user_data->roles ;

                if ( hrw_check_is_array ( $user_roles ) ) {
                    foreach ( $user_roles as $user_role ) {
                        if ( in_array ( $user_role , $selected_user_roles ) )
                            return true ;
                    }
                }
                break ;
        }

        return false ;
    }

}

if ( ! function_exists ( 'hrw_topup_user_roles_restriction' ) ) {

    function hrw_topup_user_roles_restriction( $user_id = false ) {

        if ( ! $user_id )
            $user_id = get_current_user_id () ;

        $type = get_option ( 'hrw_advanced_topup_user_restriction_type' , '1' ) ;

        switch ( $type ) {
            case '1':
                return true ;

            case '2':
                $selected_users = get_option ( 'hrw_advanced_topup_user_restriction' , array () ) ;
                if ( in_array ( $user_id , $selected_users ) )
                    return true ;

                break ;
            case '3':
                $selected_user_roles = get_option ( 'hrw_advanced_topup_user_role_restriction' , array () ) ;
                $user_data           = get_userdata ( $user_id ) ;
                $user_roles          = $user_data->roles ;

                if ( hrw_check_is_array ( $user_roles ) ) {
                    foreach ( $user_roles as $user_role ) {
                        if ( in_array ( $user_role , $selected_user_roles ) )
                            return true ;
                    }
                }
                break ;
        }

        return false ;
    }

}

if ( ! function_exists ( 'hrw_auto_topup_product_in_cart' ) ) {

    function hrw_auto_topup_product_in_cart() {

        foreach ( WC ()->cart->get_cart () as $key => $value ) {
            if ( ! isset ( $value[ 'hrw_wallet' ] ) )
                continue ;

            if ( 'auto' !== $value[ 'hrw_wallet' ][ 'topup_mode' ] ) {
                continue ;
            }

            if ( HRW_Topup_Handler::$topup_product != $value[ 'hrw_wallet' ][ 'product_id' ] )
                continue ;

            return $value[ 'hrw_wallet' ] ;
        }

        return false ;
    }

}

if ( ! function_exists ( 'hrw_display_payment_method' ) ) {

    function hrw_display_payment_method( $payment_method , $html = true ) {

        switch ( $payment_method ) {
            case 'bank_transfer':
                $payment_method = esc_html__ ( 'Bank Transfer' , HRW_LOCALE ) ;
                break ;
            case 'paypal':
                $payment_method = esc_html__ ( 'Paypal' , HRW_LOCALE ) ;
                break ;
        }
        return $html ? '<span class="hrw_payment_method">' . $payment_method . '</span>' : $payment_method ;
    }

}

if ( ! function_exists ( 'hrw_generate_random_codes' ) ) {

    function hrw_generate_random_codes( $args = array () ) {
        $args = wp_parse_args ( $args , array (
            'number_type'         => 'random' ,
            'character_type'      => '3' ,
            'length'              => 10 ,
            'prefix'              => '' ,
            'suffix'              => '' ,
            'series_alphanumeric' => '' ,
            'sequence_number'     => '' ,
                ) ) ;

        if ( $args[ 'number_type' ] == 'series' ) {
            $random_code = sanitize_title ( $args[ 'series_alphanumeric' ] ) ;
        } else {
            if ( $args[ 'character_type' ] == '2' ) {
                $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890' ;
            } elseif ( $args[ 'character_type' ] == '3' ) {
                $exploded_val = explode ( ',' , $args[ 'exclude_alphbates' ] ) ;
                $characters   = str_replace ( $exploded_val , '' , 'abcdefghijklmnopqrstuvwxyz1234567890' ) ;
            } else {
                $characters = '1234567890' ;
            }

            $random_codes     = array () ;
            $character_length = strlen ( $characters ) - 1 ; //put the length -1 in cache

            for ( $i = 0 ; $i < $args[ 'length' ] ; $i ++ ) {
                $n              = rand ( 0 , $character_length ) ;
                $random_codes[] = $characters[ $n ] ;
            }

            $random_code = implode ( $random_codes ) ;
        }

        $generated_random_code = $args[ 'prefix' ] . $random_code . $args[ 'suffix' ] ;

        if ( $args[ 'sequence_number' ] )
            $generated_random_code = $generated_random_code . '_' . $args[ 'sequence_number' ] ;

        return $generated_random_code ;
    }

}


if ( ! function_exists ( 'hrw_payment_method_preference_status' ) ) {

    function hrw_payment_method_preference_status( $key = '' ) {

        $options_status = array (
            'bank_transfer' => 'enable' ,
            'paypal'        => 'enable' ,
                ) ;

        $status = apply_filters ( 'hrw_custom_payment_preference_status' , $options_status ) ;

        if ( $key == '' )
            return $status ;

        return isset ( $status[ $key ] ) ? $status[ $key ] : '' ;
    }

}

if ( ! function_exists ( 'hrw_payment_method_preference' ) ) {

    function hrw_payment_method_preference( $key = '' ) {

        $options = array (
            'bank_transfer' => esc_html__ ( 'Bank Transfer' , HRW_LOCALE ) ,
            'paypal'        => esc_html__ ( 'Paypal' , HRW_LOCALE ) ,
                ) ;

        $available_payments = apply_filters ( 'hrw_custom_payment_preference_option' , $options ) ;

        if ( $key == '' ) {
            return $available_payments ;
        }

        return isset ( $available_payments[ $key ] ) ? $available_payments[ $key ] : '' ;
    }

}

if ( ! function_exists ( 'hrw_get_week_days' ) ) {

    function hrw_get_week_days() {
        $days = array (
            '0' => esc_html ( 'Sunday' , HRW_LOCALE ) ,
            '1' => esc_html ( 'Monday' , HRW_LOCALE ) ,
            '2' => esc_html ( 'Tuesday' , HRW_LOCALE ) ,
            '3' => esc_html ( 'Wednesday' , HRW_LOCALE ) ,
            '4' => esc_html ( 'Thrusday' , HRW_LOCALE ) ,
            '5' => esc_html ( 'Friday' , HRW_LOCALE ) ,
            '6' => esc_html ( 'Saturday' , HRW_LOCALE ) ,
                ) ;

        return $days ;
    }

}

if ( ! function_exists ( 'hrw_get_wc_tags' ) ) {

    function hrw_get_wc_tags() {
        $tags    = array () ;
        $wc_tags = get_terms ( 'product_tag' ) ;

        if ( ! hrw_check_is_array ( $wc_tags ) )
            return $tags ;

        foreach ( $wc_tags as $tag ) {
            $tags[ $tag->term_id ] = $tag->name ;
        }

        return $tags ;
    }

}

if ( ! function_exists ( 'hrw_get_gift_data_by_key' ) ) {

    /**
     * Get Gift Data By Key
     */
    function hrw_get_gift_data_by_key( $key , $default = '' ) {

        if ( ! isset ( $_POST[ 'hrw_gift' ] ) ) {
            return $default ;
        }

        return isset ( $_POST[ 'hrw_gift' ][ $key ] ) ? $_POST[ 'hrw_gift' ][ $key ] : $default ;
    }

}

function hrw_get_user_mail_by( $flag , $user_id ) {
    $user = get_user_by ( $flag , $user_id ) ;

    return $user->user_email ? $user->user_email : '' ;
}

if ( ! function_exists ( 'hrw_get_gift_gateways' ) ) {

    function hrw_get_gift_gateways() {
        $gift_gateways      = array () ;
        $wc_gateways        = new WC_Payment_Gateways() ;
        $available_gateways = $wc_gateways->get_available_payment_gateways () ;

        foreach ( $available_gateways as $id => $gateway ) {

            if ( $id == 'HR_Wallet_Gateway' ) {
                continue ;
            }

            $gift_gateways[ $id ] = $gateway->get_title () ;
        }

        return $gift_gateways ;
    }

}
