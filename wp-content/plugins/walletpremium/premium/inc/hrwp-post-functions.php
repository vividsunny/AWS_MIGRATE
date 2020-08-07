<?php

/*
 * Post functions
 */

if ( ! defined ( 'ABSPATH' ) )
    exit ; // Exit if accessed directly.

if ( ! function_exists ( 'hrw_create_new_fund_transfer_log' ) ) {

    function hrw_create_new_fund_transfer_log( $meta_args , $post_args = array () ) {

        $object = new HRW_Fund_Transfer_Log() ;
        $id     = $object->create ( $meta_args , $post_args ) ;

        return $id ;
    }

}

if ( ! function_exists ( 'hrw_get_fund_transfer_log' ) ) {

    function hrw_get_fund_transfer_log( $id ) {

        $object = new HRW_Fund_Transfer_Log ( $id ) ;

        return $object ;
    }

}

if ( ! function_exists ( 'hrw_update_fund_transfer_log' ) ) {

    function hrw_update_fund_transfer_log( $id , $meta_args , $post_args = array () ) {

        $object = new HRW_Fund_Transfer_Log ( $id ) ;
        $object->update ( $meta_args , $post_args ) ;

        return $object ;
    }

}

if ( ! function_exists ( 'hrw_delete_fund_transfer_log' ) ) {

    function hrw_delete_fund_transfer_log( $id , $force = true ) {

        wp_delete_post ( $id , $force ) ;

        return true ;
    }

}

if ( ! function_exists ( 'hrw_create_new_fund_transfer' ) ) {

    function hrw_create_new_fund_transfer( $meta_args , $post_args = array () ) {

        $object = new HRW_Fund_Transfer() ;
        $id     = $object->create ( $meta_args , $post_args ) ;

        return $id ;
    }

}

if ( ! function_exists ( 'hrw_get_fund_transfer' ) ) {

    function hrw_get_fund_transfer( $id ) {

        $object = new HRW_Fund_Transfer ( $id ) ;

        return $object ;
    }

}

if ( ! function_exists ( 'hrw_update_fund_transfer' ) ) {

    function hrw_update_fund_transfer( $id , $meta_args , $post_args = array () ) {

        $object = new HRW_Fund_Transfer ( $id ) ;
        $object->update ( $meta_args , $post_args ) ;

        return $id ;
    }

}

if ( ! function_exists ( 'hrw_delete_fund_transfer' ) ) {

    function hrw_delete_fund_transfer( $id , $force = true ) {

        wp_delete_post ( $id , $force ) ;

        return true ;
    }

}

if ( ! function_exists ( 'hrw_create_new_wallet_withdrawal' ) ) {

    function hrw_create_new_wallet_withdrawal( $meta_args , $post_args = array () ) {

        $object = new HRW_Wallet_Withdrawal() ;

        $id = $object->create ( $meta_args , $post_args ) ;

        return $id ;
    }

}

if ( ! function_exists ( 'hrw_get_wallet_withdrawal' ) ) {

    function hrw_get_wallet_withdrawal( $id ) {

        $object = new HRW_Wallet_Withdrawal ( $id ) ;

        return $object ;
    }

}

if ( ! function_exists ( 'hrw_update_wallet_withdrawal' ) ) {

    function hrw_update_wallet_withdrawal( $id , $meta_args , $post_args = array () ) {

        $object = new HRW_Wallet_Withdrawal ( $id ) ;
        $object->update ( $meta_args , $post_args ) ;

        return $object ;
    }

}

if ( ! function_exists ( 'hrw_delete_wallet_withdrawal' ) ) {

    function hrw_delete_wallet_withdrawal( $id , $force = true ) {

        wp_delete_post ( $id , $force ) ;

        return true ;
    }

}

if ( ! function_exists ( 'hrw_create_new_cashback' ) ) {

    function hrw_create_new_cashback( $meta_args , $post_args = array () ) {

        $object = new HRW_CASHBACK() ;
        $id     = $object->create ( $meta_args , $post_args ) ;

        return $id ;
    }

}

if ( ! function_exists ( 'hrw_get_cashback' ) ) {

    function hrw_get_cashback( $id ) {

        $object = new HRW_CASHBACK ( $id ) ;

        return $object ;
    }

}

if ( ! function_exists ( 'hrw_update_cashback' ) ) {

    function hrw_update_cashback( $id , $meta_args , $post_args = array () ) {

        $object = new HRW_CASHBACK ( $id ) ;
        $object->update ( $meta_args , $post_args ) ;

        do_action ( 'hrw_cashback_updated' , $id ) ;

        return $object ;
    }

}

if ( ! function_exists ( 'hrw_delete_cashback' ) ) {

    function hrw_delete_cashback( $id , $force = true ) {

        wp_delete_post ( $id , $force ) ;

        return true ;
    }

}

if ( ! function_exists ( 'hrw_create_new_cashback_log' ) ) {

    function hrw_create_new_cashback_log( $meta_args , $post_args = array () ) {

        $object = new HRW_Cashback_Log() ;
        $id     = $object->create ( $meta_args , $post_args ) ;

        return $id ;
    }

}

if ( ! function_exists ( 'hrw_get_cashback_log' ) ) {

    function hrw_get_cashback_log( $id ) {

        $object = new HRW_Cashback_Log ( $id ) ;

        return $object ;
    }

}

if ( ! function_exists ( 'hrw_update_cashback_log' ) ) {

    function hrw_update_cashback_log( $id , $meta_args , $post_args = array () ) {

        $object = new HRW_Cashback_Log ( $id ) ;
        $object->update ( $meta_args , $post_args ) ;

        return $object ;
    }

}

if ( ! function_exists ( 'hrw_delete_cashback_log' ) ) {

    function hrw_delete_cashback_log( $id , $force = true ) {

        wp_delete_post ( $id , $force ) ;

        return true ;
    }

}

if ( ! function_exists ( 'hrw_create_new_discount' ) ) {

    function hrw_create_new_discount( $meta_args , $post_args = array () ) {

        $object = new HRW_DISCOUNT() ;
        $id     = $object->create ( $meta_args , $post_args ) ;

        return $id ;
    }

}

if ( ! function_exists ( 'hrw_get_discount' ) ) {

    function hrw_get_discount( $id ) {

        $object = new HRW_DISCOUNT ( $id ) ;

        return $object ;
    }

}

if ( ! function_exists ( 'hrw_update_discount' ) ) {

    function hrw_update_discount( $id , $meta_args , $post_args = array () ) {

        $object = new HRW_DISCOUNT ( $id ) ;
        $object->update ( $meta_args , $post_args ) ;

        do_action ( 'hrw_cashback_updated' , $id ) ;

        return $object ;
    }

}

if ( ! function_exists ( 'hrw_delete_discount' ) ) {

    function hrw_delete_discount( $id , $force = true ) {

        wp_delete_post ( $id , $force ) ;

        return true ;
    }

}




if ( ! function_exists ( 'hrw_create_new_gift' ) ) {

    function hrw_create_new_gift( $meta_args , $post_args = array () ) {

        $object = new HRW_Gift_Card() ;
        $id     = $object->create ( $meta_args , $post_args ) ;

        return $id ;
    }

}

if ( ! function_exists ( 'hrw_get_gift' ) ) {

    function hrw_get_gift( $id ) {

        $object = new HRW_Gift_Card ( $id ) ;

        return $object ;
    }

}

if ( ! function_exists ( 'hrw_update_gift' ) ) {

    function hrw_update_gift( $id , $meta_args , $post_args = array () ) {

        $object = new HRW_Gift_Card ( $id ) ;
        $object->update ( $meta_args , $post_args ) ;

        do_action ( 'hrw_gift_updated' , $id ) ;

        return $object ;
    }

}

if ( ! function_exists ( 'hrw_delete_gift' ) ) {

    function hrw_delete_gift( $id , $force = true ) {

        wp_delete_post ( $id , $force ) ;

        return true ;
    }

}

if ( ! function_exists ( 'hrw_get_fund_transfer_id_by_sender_id' ) ) {

    function hrw_get_fund_transfer_id_by_sender_id( $sender_id , $receiver_id ) {
        $args = array (
            'post_type'      => HRWP_Register_Post_Types::FUND_TRANSFER_POSTTYPE ,
            'post_status'    => hrw_get_fund_transfer_log_statuses () ,
            'author'         => $sender_id ,
            'post_parent'    => $receiver_id ,
            'posts_per_page' => 1 ,
            'fields'         => 'ids' ,
                ) ;

        $fund_transfer_id = get_posts ( $args ) ;

        if ( ! hrw_check_is_array ( $fund_transfer_id ) )
            return false ;

        return reset ( $fund_transfer_id ) ;
    }

}

if ( ! function_exists ( 'hrw_get_fund_transfer_logs' ) ) {

    function hrw_get_fund_transfer_logs( $transaction_id ) {
        $args = array (
            'post_type'      => HRWP_Register_Post_Types::FUND_TRANSFER_LOG_POSTTYPE ,
            'post_status'    => hrw_get_fund_transfer_log_statuses () ,
            'post_parent'    => $transaction_id ,
            'posts_per_page' => '-1' ,
            'fields'         => 'ids' ,
                ) ;

        $fund_transfer_logs = get_posts ( $args ) ;

        if ( ! hrw_check_is_array ( $fund_transfer_logs ) )
            return array () ;

        return $fund_transfer_logs ;
    }

}

if ( ! function_exists ( 'hrw_get_wallet_auto_topup' ) ) {

    function hrw_get_wallet_auto_topup( $id ) {

        $object = new HRW_Auto_Topup ( $id ) ;

        return $object ;
    }

}

if ( ! function_exists ( 'hrw_get_fund_transfer_log_statuses' ) ) {

    function hrw_get_fund_transfer_log_statuses() {
        return apply_filters ( 'hrw_fund_transfer_log_statuses' , array ( 'hrw_transfered' ,
            'hrw_received' ,
            'hrw_requested' ,
            'hrw_new_requested' ,
            'hrw_declined' ,
            'hrw_request_declined' ,
            'hrw_cancelled' ,
            'hrw_request_cancel'
                )
                ) ;
    }

}

if ( ! function_exists ( 'hrw_get_withdrawal_log_statuses' ) ) {

    function hrw_get_withdrawal_log_statuses() {
        return apply_filters ( 'hrw_withdrawal_log_statuses' , array (
            'hrw_paid' ,
            'hrw_unpaid' ,
            'hrw_in_progress' ,
            'hrw_cancelled' ,
                )
                ) ;
    }

}

if ( ! function_exists ( 'hrw_get_gift_card_statuses' ) ) {

    function hrw_get_gift_card_statuses() {

        return array (
            ''             => esc_html__ ( 'All' , HRW_LOCALE ) ,
            'hrw_created'  => esc_html__ ( 'Not Yet Redeemed' , HRW_LOCALE ) ,
            'hrw_redeemed' => esc_html__ ( 'Redeemed' , HRW_LOCALE ) ,
            'hrw_expired'  => esc_html__ ( 'Expired' , HRW_LOCALE ) ,
                ) ;
    }

}

if ( ! function_exists ( 'hrw_format_fund_transfer_log_status' ) ) {

    function hrw_format_fund_transfer_log_status( $transaction_log_object ) {

        switch ( $transaction_log_object->get_status () ) {
            case 'hrw_transfered':
                $content = sprintf ( esc_html__ ( 'Transferred on %s' , HRW_LOCALE ) , $transaction_log_object->get_formatted_date () ) ;
                break ;

            case 'hrw_received':
                $content = sprintf ( esc_html__ ( 'Received on %s' , HRW_LOCALE ) , $transaction_log_object->get_formatted_date () ) ;
                break ;

            case 'hrw_requested':
                $content = sprintf ( esc_html__ ( 'Request Sent on %s' , HRW_LOCALE ) , $transaction_log_object->get_formatted_date () ) ;
                break ;

            case 'hrw_new_requested':
                $content = sprintf ( esc_html__ ( 'New Request on %s' , HRW_LOCALE ) , $transaction_log_object->get_formatted_date () ) ;
                break ;

            case 'hrw_request_declined':
            case 'hrw_declined':
                $content = sprintf ( esc_html__ ( 'Request for %s was declined on %s' , HRW_LOCALE ) , hrw_price ( $transaction_log_object->get_amount () ) , $transaction_log_object->get_formatted_date () ) ;
                break ;

            case 'hrw_request_cancel':
            default:
                $content = sprintf ( esc_html__ ( 'Request for %s was cancelled on %s' , HRW_LOCALE ) , hrw_price ( $transaction_log_object->get_amount () ) , $transaction_log_object->get_formatted_date () ) ;
                break ;
        }

        return $content ;
    }

}

if ( ! function_exists ( 'hrw_get_user_fund_transfered_count' ) ) {

    function hrw_get_user_fund_transfered_count( $user_id ) {
        $args = array (
            'post_type'      => HRWP_Register_Post_Types::FUND_TRANSFER_LOG_POSTTYPE ,
            'post_status'    => array ( 'hrw_transfered' ) ,
            'author'         => $user_id ,
            'posts_per_page' => -1 ,
            'fields'         => 'ids' ,
            'date_query'     => array (
                'column' => 'post_date_gmt' ,
                'day'    => date ( 'd' ) ,
                'year'   => date ( 'Y' ) ,
                'month'  => date ( 'm' )
            )
                ) ;

        $ids = get_posts ( $args ) ;

        if ( ! hrw_check_is_array ( $ids ) )
            return 0 ;

        return count ( $ids ) ;
    }

}

if ( ! function_exists ( 'hrw_get_user_fund_requested_count' ) ) {

    function hrw_get_user_fund_requested_count( $user_id ) {
        $args = array (
            'post_type'      => HRWP_Register_Post_Types::FUND_TRANSFER_LOG_POSTTYPE ,
            'post_status'    => array ( 'hrw_requested' ) ,
            'author'         => $user_id ,
            'posts_per_page' => -1 ,
            'fields'         => 'ids' ,
            'date_query'     => array (
                'column' => 'post_date_gmt' ,
                'day'    => date ( 'd' ) ,
                'year'   => date ( 'Y' ) ,
                'month'  => date ( 'm' )
            )
                ) ;

        $ids = get_posts ( $args ) ;

        if ( ! hrw_check_is_array ( $ids ) )
            return 0 ;

        return count ( $ids ) ;
    }

}

if ( ! function_exists ( 'hrw_get_per_user_fund_transfered_count' ) ) {

    function hrw_get_per_user_fund_transfered_count( $user_id , $receiver_id ) {
        $args = array (
            'post_type'      => HRWP_Register_Post_Types::FUND_TRANSFER_LOG_POSTTYPE ,
            'post_status'    => array ( 'hrw_transfered' ) ,
            'author'         => $user_id ,
            'posts_per_page' => -1 ,
            'fields'         => 'ids' ,
            'date_query'     => array (
                'column' => 'post_date_gmt' ,
                'day'    => date ( 'd' ) ,
                'year'   => date ( 'Y' ) ,
                'month'  => date ( 'm' )
            ) ,
            'meta_key'       => 'hrw_receiver_id' ,
            'meta_value'     => $receiver_id
                ) ;

        $ids = get_posts ( $args ) ;

        if ( ! hrw_check_is_array ( $ids ) )
            return 0 ;

        return count ( $ids ) ;
    }

}


if ( ! function_exists ( 'hrw_get_per_user_fund_requested_count' ) ) {

    function hrw_get_per_user_fund_requested_count( $user_id , $receiver_id ) {
        $args = array (
            'post_type'      => HRWP_Register_Post_Types::FUND_TRANSFER_LOG_POSTTYPE ,
            'post_status'    => array ( 'hrw_requested' ) ,
            'author'         => $user_id ,
            'posts_per_page' => -1 ,
            'fields'         => 'ids' ,
            'date_query'     => array (
                'column' => 'post_date_gmt' ,
                'day'    => date ( 'd' ) ,
                'year'   => date ( 'Y' ) ,
                'month'  => date ( 'm' )
            ) ,
            'meta_key'       => 'hrw_receiver_id' ,
            'meta_value'     => $receiver_id
                ) ;

        $ids = get_posts ( $args ) ;

        if ( ! hrw_check_is_array ( $ids ) )
            return 0 ;

        return count ( $ids ) ;
    }

}

if ( ! function_exists ( 'hrw_get_user_withdrawal_requested_count' ) ) {

    function hrw_get_user_withdrawal_requested_count( $user_id ) {
        $args = array (
            'post_type'      => HRWP_Register_Post_Types::WALLET_WITHDRAWAL_POSTTYPE ,
            'post_status'    => array ( 'hrw_withdrawal' ) ,
            'author'         => $user_id ,
            'posts_per_page' => -1 ,
            'fields'         => 'ids' ,
            'date_query'     => array (
                'column' => 'post_date_gmt' ,
                'day'    => date ( 'd' ) ,
                'year'   => date ( 'Y' ) ,
                'month'  => date ( 'm' )
            )
                ) ;

        $ids = get_posts ( $args ) ;

        if ( ! hrw_check_is_array ( $ids ) )
            return 0 ;

        return count ( $ids ) ;
    }

}

if ( ! function_exists ( 'hrw_get_total_cashback_credited' ) ) {

    function hrw_get_total_cashback_credited( $user_id ) {
        global $wpdb ;
        $credit_query = new HRW_Query ( $wpdb->posts , 'p' ) ;
        $debit_query  = new HRW_Query ( $wpdb->posts , 'p' ) ;

        $credit_amount = $credit_query->leftJoin ( $wpdb->postmeta , 'pm' , '`p`.ID = `pm`.post_id' )
                ->where ( '`p`.post_type' , 'hrw_cashback_log' )
                ->where ( '`p`.post_status' , 'publish' )
                ->where ( '`p`.post_author' , $user_id )
                ->where ( '`pm`.meta_key' , 'hrw_amount_credited' )
                ->orderBy ( '`pm`.meta_value' )
                ->fetchCol ( "SUM(`pm`.meta_value)" ) ;

        $debit_amount = $debit_query->leftJoin ( $wpdb->postmeta , 'pm' , '`p`.ID = `pm`.post_id' )
                ->where ( '`p`.post_type' , 'hrw_cashback_log' )
                ->where ( '`p`.post_status' , 'publish' )
                ->where ( '`p`.post_author' , $user_id )
                ->where ( '`pm`.meta_key' , 'hrw_amount_debited' )
                ->orderBy ( '`pm`.meta_value' )
                ->fetchCol ( "SUM(`pm`.meta_value)" ) ;

        $total = array_sum ( $credit_amount ) - array_sum ( $debit_amount ) ;

        return $total ;
    }

}

if ( ! function_exists ( 'hrw_get_gift_ids_by' ) ) {

    function hrw_get_gift_ids_by( $flag = 'all' , $value , $extra_args = array () ) {

        $args = array (
            'post_type'      => HRWP_Register_Post_Types::GIFT_CARD_POSTTYPE ,
            'post_status'    => array ( 'hrw_created' , 'hrw_redeemed' , 'hrw_expired' ) ,
            'posts_per_page' => -1 ,
            'fields'         => 'ids' ,
                ) ;

        if ( $flag == 'sent' ) {
            $args[ 'author__in' ] = $value ;
        } else if ( $flag == 'received' ) {
            $user                 = get_user_by ( 'id' , $value ) ;
            $args[ 'meta_query' ] = array (
                'relation' => 'AND' ,
                array (
                    'key'     => 'hrw_receiver_id' ,  
                    'value'   => $user->user_email ,
                    'compare' => '='
                ) ) ;
        } else if ( $flag == 'code' ) {
            $args[ 'meta_query' ] = array (
                'relation' => 'AND' ,
                array (
                    'key'     => 'hrw_gift_code' ,
                    'value'   => $value ,
                    'compare' => '='
                ) ) ;
        }

        if ( ! empty ( $extra_args ) ) {
            $args = array_merge ( $args , $extra_args ) ;
        }

        $gift_ids = get_posts ( $args ) ;

        return $gift_ids ;
    }

}