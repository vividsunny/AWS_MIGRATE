<?php

/**
 *  Wallet Gateway
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'HR_Wallet_Gateway' ) ) {

    class HR_Wallet_Gateway extends WC_Payment_Gateway {

        /**
         * Constructor for the gateway.
         */
        public function __construct() {

            $this->id                 = 'HR_Wallet_Gateway' ;
            $this->has_fields         = false ;
            $this->method_title       = esc_html__( 'Wallet Gateway' , HRW_LOCALE ) ;
            $this->method_description = esc_html__( 'Wallet' , HRW_LOCALE ) ;
            $this->supports           = array(
                'refunds' ,
                    ) ;

            // Load the settings.
            $this->init_form_fields() ;
            $this->init_settings() ;

            $this->init_hooks() ;
            $this->populate_data() ;
        }

        /**
         * Populate Data
         */
        protected function populate_data() {
            $this->title                                    = $this->get_option( 'title' , 'Wallet Gateway' ) ;
            $this->description                              = $this->get_option( 'description' , 'Use Wallet Funds to Complete this order' ) ;
            $this->is_forced_automatic_subscription_payment = $this->get_option( 'hr_wallet_auto_manual_cb' ) == "yes" && $this->get_option( 'hr_wallet_auto_manual_selection' ) == "2" ;
        }

        /*
         * Add custom Wallet gateway
         */

        public static function add_custom_gateway( $wc_gateways ) {
            $wc_gateways[] = 'HR_Wallet_Gateway' ;

            return $wc_gateways ;
        }

        /**
         * Initialize Gateway Settings Form Fields.
         */
        public function init_form_fields() {

            $this->form_fields = apply_filters( 'hrw_gateway_form_fields' , array(
                'enabled'     => array(
                    'title'   => esc_html__( 'Enable/Disable' , HRW_LOCALE ) ,
                    'type'    => 'checkbox' ,
                    'label'   => esc_html__( 'Enable Wallet Gateway' , HRW_LOCALE ) ,
                    'default' => 'yes'
                ) ,
                'title'       => array(
                    'title'    => esc_html__( 'Title' , HRW_LOCALE ) ,
                    'type'     => 'text' ,
                    'std'      => esc_html__( 'Wallet Gateway' , HRW_LOCALE ) ,
                    'default'  => esc_html__( 'Wallet Gateway' , HRW_LOCALE ) ,
                    'desc_tip' => true ,
                ) ,
                'description' => array(
                    'title'    => esc_html__( 'Description' , HRW_LOCALE ) ,
                    'type'     => 'textarea' ,
                    'std'      => esc_html__( 'Use Wallet Gateway to complete this Purchase' , HRW_LOCALE ) ,
                    'default'  => esc_html__( 'Use Wallet Gateway to complete this Purchase' , HRW_LOCALE ) ,
                    'css'      => 'max-width:400px;' ,
                    'desc_tip' => true ,
                ) ,
                    ) ) ;
        }

        /**
         * Initialize the hook for gateway
         */
        public function init_hooks() {
            if ( is_user_logged_in() )
                add_filter( 'woocommerce_payment_gateways' , array( $this , 'add_custom_gateway' ) ) ;

            add_action( 'woocommerce_update_options_payment_gateways_' . $this->id , array( $this , 'process_admin_options' ) ) ;
        }

        /*
         * Process Payment
         */

        public function process_payment( $orderid ) {
            try {

                $order = wc_get_order( $orderid ) ;

                //Extra Usage Validation Hook
                do_action( 'hrw_do_gateway_usage_validation' , $order , $order->get_id() ) ;

                //Process Wallet Debit
                HRW_Order_Management::process_wallet_debit( $order ) ;

                //Change status to processing
                $order->update_status( 'processing' , esc_html__( 'Awaiting for Admin Confirmation' , HRW_LOCALE ) ) ;

                //empty cart
                WC()->cart->empty_cart() ;

                return array(
                    'result'   => 'success' ,
                    'redirect' => $this->get_return_url( $order )
                        ) ;
            } catch ( Exception $ex ) {
                wc_add_notice( $ex->getMessage() , 'error' ) ;
            }
        }

        /*
         * Process Refund
         */

        public function process_refund( $orderid , $amount = null , $reason = '' ) {
            $order       = wc_get_order( $orderid ) ;
            $log_message = get_option( 'hrw_localizations_order_refund_debit_amount_log' ) ;
            HRW_Order_Management::credit_amount_to_user( $order , $log_message , $amount ) ;

            return true ;
        }

    }

}