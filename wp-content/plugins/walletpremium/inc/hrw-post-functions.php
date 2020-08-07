<?php

/*
 * Post functions
 */

if ( ! defined ( 'ABSPATH' ) )
    exit ; // Exit if accessed directly.

if ( ! function_exists ( 'hrw_create_new_wallet' ) ) {

    function hrw_create_new_wallet( $meta_args , $post_args = array () ) {

        $object = new HRW_User_Wallet() ;
        $id     = $object->create ( $meta_args , $post_args ) ;

        return $id ;
    }

}

if ( ! function_exists ( 'hrw_get_wallet' ) ) {

    function hrw_get_wallet( $id ) {

        $object = new HRW_User_Wallet ( $id ) ;

        return $object ;
    }

}

if ( ! function_exists ( 'hrw_update_wallet' ) ) {

    function hrw_update_wallet( $id , $meta_args , $post_args = array () ) {

        $object = new HRW_User_Wallet ( $id ) ;
        $object->update ( $meta_args , $post_args ) ;

        do_action ( 'hrw_wallet_updated' , $id ) ;

        return $object ;
    }

}

if ( ! function_exists ( 'hrw_delete_wallet' ) ) {

    function hrw_delete_wallet( $id , $force = true ) {

        wp_delete_post ( $id , $force ) ;

        return true ;
    }

}

if ( ! function_exists ( 'hrw_create_new_transaction_log' ) ) {

    function hrw_create_new_transaction_log( $meta_args , $post_args = array () ) {

        $object = new HRW_Transaction_Log() ;
        $id     = $object->create ( $meta_args , $post_args ) ;

        return $id ;
    }

}

if ( ! function_exists ( 'hrw_get_transaction_log' ) ) {

    function hrw_get_transaction_log( $id ) {

        $object = new HRW_Transaction_Log ( $id ) ;

        return $object ;
    }

}

if ( ! function_exists ( 'hrw_update_transaction_log' ) ) {

    function hrw_update_transaction_log( $id , $meta_args , $post_args = array () ) {

        $object = new HRW_Transaction_Log ( $id ) ;
        $object->update ( $meta_args , $post_args ) ;

        return $object ;
    }

}

if ( ! function_exists ( 'hrw_delete_transaction_log' ) ) {

    function hrw_delete_transaction_log( $id , $force = true ) {

        wp_delete_post ( $id , $force ) ;

        return true ;
    }

}

if ( ! function_exists ( 'hrw_get_wallet_id_by_key' ) ) {

    function hrw_get_wallet_id_by_key( $meta_key , $meta_value ) {
        $args = array (
            'post_type'      => HRW_Register_Post_Types::WALLET_POSTTYPE ,
            'post_status'    => array ( 'hrw_active' , 'hrw_expired' , 'hr_locked' ) ,
            'posts_per_page' => 1 ,
            'fields'         => 'ids' ,
            'meta_key'       => $meta_key ,
            'meta_value'     => $meta_value
                ) ;

        $wallet_id = get_posts ( $args ) ;

        if ( ! hrw_check_is_array ( $wallet_id ) )
            return false ;

        return reset ( $wallet_id ) ;
    }

}

if ( ! function_exists ( 'hrw_get_wallet_id_by_user_id' ) ) {

    function hrw_get_wallet_id_by_user_id( $user_id , $status = false ) {

        if ( ! $status )
            $status = hrw_get_wallet_statuses () ;

        $args = array (
            'post_type'      => HRW_Register_Post_Types::WALLET_POSTTYPE ,
            'post_status'    => $status ,
            'post_parent'    => $user_id ,
            'posts_per_page' => 1 ,
            'fields'         => 'ids' ,
                ) ;

        $wallet_id = get_posts ( $args ) ;

        if ( ! hrw_check_is_array ( $wallet_id ) )
            return false ;

        return reset ( $wallet_id ) ;
    }

}

if ( ! function_exists ( 'hrw_get_transaction_logs_by_wallet_id' ) ) {

    function hrw_get_transaction_logs_by_wallet_id( $wallet_id , $status = false ) {

        if ( ! $status )
            $status = hrw_get_transaction_log_statuses () ;

        $args = array (
            'numberposts' => -1 ,
            'post_type'   => HRW_Register_Post_Types::TRANSACTION_LOG_POSTTYPE ,
            'post_parent' => $wallet_id ,
            'post_status' => $status ,
            'fields'      => 'ids' ,
                ) ;

        return get_posts ( $args ) ;
    }

}

if ( ! function_exists ( 'hrw_get_withdrawal_id' ) ) {

    function hrw_get_withdrawal_id( $wallet_id , $status = false ) {

        if ( ! $status )
            $status = hrw_get_withdrawal_log_statuses () ;

        $args = array (
            'numberposts' => -1 ,
            'post_type'   => HRWP_Register_Post_Types::WALLET_WITHDRAWAL_POSTTYPE ,
            'post_parent' => $wallet_id ,
            'post_status' => $status ,
            'fields'      => 'ids' ,
                ) ;

        return get_posts ( $args ) ;
    }

}

if ( ! function_exists ( 'hrw_get_wallet_statuses' ) ) {

    function hrw_get_wallet_statuses() {
        return apply_filters ( 'hrw_wallet_statuses' , array ( 'hrw_active' , 'hrw_expired' , 'hrw_blocked' ) ) ;
    }

}

if ( ! function_exists ( 'hrw_get_transaction_log_statuses' ) ) {

    function hrw_get_transaction_log_statuses() {
        return apply_filters ( 'hrw_transaction_log_statuses' , array ( 'hrw_debit' , 'hrw_credit' ) ) ;
    }

}

/*
 * Get all active wallet ids
 */

if ( ! function_exists ( 'hrw_get_active_wallet' ) ) {

    function hrw_get_active_wallet() {
        $args = array (
            'numberposts' => -1 ,
            'post_type'   => 'hrw_wallet' ,
            'post_status' => 'hrw_active' ,
            'fields'      => 'ids' ,
                ) ;

        $wallet_ids = get_posts ( $args ) ;

        if ( ! hrw_check_is_array ( $wallet_ids ) )
            return array () ;

        return $wallet_ids ;
    }

}


