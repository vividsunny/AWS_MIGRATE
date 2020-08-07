<?php

/**
 *  Handles forms
 */
if ( ! defined ( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
if ( ! class_exists ( 'HRW_Form_Handler' ) ) {

    /**
     * Class
     */
    class HRW_Form_Handler {

        /**
         * Error messages.
         */
        private static $errors = array () ;

        /**
         * Info messages.
         */
        private static $infos = array () ;

        /**
         * Success messages.
         */
        private static $messages = array () ;

        /**
         * Plugin slug.
         */
        private static $plugin_slug = 'hrw' ;

        /**
         * Class Initialization.
         */
        public static function init() {
            add_action ( 'wp_loaded' , array ( __CLASS__ , 'process_topup_form' ) ) ;
            add_action ( 'wp_loaded' , array ( __CLASS__ , 'process_edit_profile' ) ) ;
        }

        /**
         * Add a message.
         */
        public static function add_message( $text ) {
            self::$messages[] = $text ;
        }

        /**
         * Add an error.
         */
        public static function add_error( $text ) {
            self::$errors[] = $text ;
        }

        /**
         * Add an info.
         */
        public static function add_info( $text ) {
            self::$infos[] = $text ;
        }

        /**
         * Output messages + errors.
         */
        public static function show_messages() {
            if ( count ( self::$errors ) > 0 ) {
                foreach ( self::$errors as $error ) {
                    self::show_error ( $error ) ;
                }
            } elseif ( count ( self::$messages ) > 0 ) {
                foreach ( self::$messages as $message ) {
                    self::show_message ( $message ) ;
                }
            }

            foreach ( self::$infos as $info ) {
                self::show_info ( $info ) ;
            }

            self::$infos    = array () ;
            self::$errors   = array () ;
            self::$messages = array () ;
        }

        /**
         * Output a message.
         */
        public static function show_message( $message ) {
            wc_print_notice ( $message , 'success' ) ;
        }

        /**
         * Output a error.
         */
        public static function show_error( $error ) {
            wc_print_notice ( $error , 'error' ) ;
        }

        /**
         * Output a info.
         */
        public static function show_info( $info ) {
            wc_print_notice ( $info , 'notice' ) ;
        }

        /**
         * Process Top-up Form
         */
        public static function process_topup_form() {
            //prevent if call by cart ajax
            if ( isset ( $_POST[ 'woocommerce-cart-nonce' ] ) ) {
                return ;
            }

            $nonce_value = isset ( $_POST[ 'hrw-topup-nonce' ] ) ? hrw_sanitize_text_field ( $_POST[ 'hrw-topup-nonce' ] ) : null ;
            if ( ! isset ( $_POST[ 'hrw-action' ] ) || empty ( $_POST[ 'hrw-action' ] ) || ! wp_verify_nonce ( $nonce_value , 'hrw-topup' ) )
                return ;

            try {
                if ( ! isset ( $_POST[ 'hrw_topup_amount' ] ) || ! ($topup_amount = hrw_sanitize_text_field ( $_POST[ 'hrw_topup_amount' ] )) ) {
                    throw new Exception ( esc_html__ ( "Please enter funds to Top-up" , HRW_LOCALE ) ) ;
                }

                $topup_amount = apply_filters ( 'hrw_validate_topup_amount' , $topup_amount ) ;
                //validation for Top-up value
                if ( ! $topup_amount ) {
                    throw new Exception ( esc_html__ ( "Please enter funds to Top-up" , HRW_LOCALE ) ) ;
                }

                //validation for numeric value
                if ( ! is_numeric ( $topup_amount ) ) {
                    throw new Exception ( esc_html__ ( "Please enter only numeric values" , HRW_LOCALE ) ) ;
                }

                do_action ( 'hrw_do_topup_validation' ) ;

                //validation for minimum Top-up
                $minimum_topup = get_option ( 'hrw_general_topup_minimum_amount' ) ;
                if ( ! empty ( $minimum_topup ) && ($minimum_topup) > $topup_amount ) {
                    $minimum_topup_msg = get_option ( 'hrw_messages_minimum_topup_amount_msg' ) ;

                    throw new Exception ( str_replace ( '{topup-min-amount}' , $minimum_topup , $minimum_topup_msg ) ) ;
                }

                //validation for maximum Top-up
                $maximum_topup = get_option ( 'hrw_general_topup_maximum_amount' ) ;
                if ( ! empty ( $maximum_topup ) && ($maximum_topup) < $topup_amount ) {
                    $maximum_topup_msg = get_option ( 'hrw_messages_maximum_topup_amount_msg' ) ;

                    throw new Exception ( str_replace ( '{topup-max-amount}' , $maximum_topup , $maximum_topup_msg ) ) ;
                }

                //validation for maximum wallet balance for user
                $thresholed_value = ( float ) get_option ( 'hrw_general_topup_maximum_wallet_balance' ) ;
                if ( ! empty ( $thresholed_value ) && (HRW_Wallet_User::get_available_balance () + $topup_amount) > $thresholed_value ) {
                    throw new Exception ( get_option ( 'hrw_messages_topup_maximum_wallet_balance_msg' ) ) ;
                }

                $cart_item_data = array (
                    'hrw_wallet' => array (
                        'price'      => $topup_amount ,
                        'topup_mode' => 'manual' ,
                        'product_id' => HRW_Topup_Handler::$topup_product
                    )
                        ) ;

                //remove previous cart
                WC ()->cart->empty_cart () ;

                //topup product in cart
                WC ()->cart->add_to_cart ( HRW_Topup_Handler::$topup_product , '1' , 0 , array () , $cart_item_data ) ;

                //redirect to checkout page
                wp_safe_redirect ( wc_get_checkout_url () ) ;
                exit () ;
            } catch ( Exception $ex ) {

                self::add_error ( $ex->getMessage () ) ;
            }
        }

        /**
         * Process Edit Profile
         */
        public static function process_edit_profile() {

            $nonce_value = isset ( $_POST[ 'hrw-dashboard-profile-nonce' ] ) ? hrw_sanitize_text_field ( $_POST[ 'hrw-dashboard-profile-nonce' ] ) : null ;
            if ( ! isset ( $_POST[ 'hrw-action' ] ) || empty ( $_POST[ 'hrw-action' ] ) || ! wp_verify_nonce ( $nonce_value , 'hrw-dashboard-profile' ) )
                return ;

            try {

                $phone_number = hrw_sanitize_text_field ( $_POST[ 'hrw_phone_number' ] ) ;

                update_user_meta ( HRW_Wallet_User::get_user_id () , 'hrw_phone_number' , $phone_number ) ;

                self::add_message ( esc_html__ ( 'Profile updated successfully' , HRW_LOCALE ) ) ;

                unset ( $_POST[ 'hrw-dashboard-profile-nonce' ] ) ;
            } catch ( Exception $ex ) {

                self::add_error ( $ex->getMessage () ) ;
            }
        }

    }

    HRW_Form_Handler::init () ;
}
