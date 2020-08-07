<?php

/*
 * Premium Compatibility Functions
 */

if ( ! function_exists( 'hrw_get_wallet_expires_day' ) ) {

    function hrw_get_wallet_expires_day() {
        $expired_day_count = ( int ) apply_filters( 'hrw_wallet_expired_day' , 365 ) ;

        $date_object = HRW_Date_Time::get_date_time_object( 'now' , false ) ;
        $date_object->modify( '+ ' . $expired_day_count . ' days' ) ;

        return $date_object->format( 'Y-m-d H:i:s' ) ;
    }

}

if ( ! function_exists( 'hrw_is_premium' ) ) {

    function hrw_is_premium() {
        if ( is_dir( HRW_PLUGIN_PATH . '/premium' ) )
            return true ;

        return false ;
    }

}

if ( ! function_exists( 'hrw_get_price_decimals' ) ) {

    function hrw_get_price_decimals() {
        return apply_filters( 'hrw_get_price_decimals' , wc_get_price_decimals() ) ;
    }

}