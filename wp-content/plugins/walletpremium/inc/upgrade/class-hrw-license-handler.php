<?php

/*
 * License Handler
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'HRW_License_Handler' ) ) {

    /**
     * HRW_License_Handler Class
     * */
    class HRW_License_Handler {
        /*
         * Plugin Version Number
         */

        protected $version ;
        /*
         * Plugin Directory Slug
         */
        protected $dir_slug ;
        /*
         * Secret Key
         */
        protected $secret_key = '8b1a9953c4611296a827abf8c47804d7' ;

        /*
         * Item Key Name
         */
        protected $item_key_name = 'wallet-premium' ;

        /*
         * Site Url 
         */
        protected $update_path = 'https://hoicker.com/' ;

        /**
         * Option name
         */
        private $license_option = 'hrwp_products_license_activation' ;

        /**
         * Key option name
         */
        private $license_key_option = 'hrwp_products_license_activation_key' ;

        /**
         * HR_Plugin_Update_Checker Class Initialization
         * */
        public function __construct( $plugin_version , $plugin_slug ) {
            $this->version  = $plugin_version ;
            $this->dir_slug = $plugin_slug ;

            add_action( 'wp_ajax_hrw_license_handler' , array( $this , 'license_key_handler' ) ) ;
        }

        /**
         * Display License Verification Panel
         */
        public function show_panel() {
            include_once 'views/license-verification.php' ;
        }

        /**
         * Process to activate/deactive license key
         * */
        public function license_key_handler() {

            check_ajax_referer( 'hrw-upgrade-nonce' , 'hrw_security' ) ;

            try {
                if ( ! isset( $_POST ) )
                    throw new exception( esc_html__( 'Invalid Request' ) ) ;

                $license_key        = hrw_sanitize_text_field( $_POST[ 'license_key' ] ) ;
                $activation_handler = hrw_sanitize_text_field( $_POST[ 'handler' ] ) ;
                if ( $activation_handler == 'deactivate' ) {
                    $this->deactivate( $license_key ) ;
                } elseif ( $activation_handler == 'activate' ) {
                    $this->activate( $license_key ) ;
                }
            } catch ( Exception $ex ) {
                wp_send_json_error( array( 'error' => $ex->getMessage() ) ) ;
            }
        }

        /**
         * Verify data from API Endpoint
         * */
        protected function verify_activate_data( $action , $license_key ) {
            $necessary_data = array(
                'action'         => $action ,
                'license_key'    => $license_key ,
                'current_site'   => site_url() ,
                'plugin_version' => $this->version ,
                'slug'           => $this->dir_slug ,
                'secret_key'     => $this->secret_key ,
                'item_key_name'  => $this->item_key_name ,
                'free'           => ! (hrw_is_premium()) ,
                'wc_version'     => WC_VERSION ,
                'wp_version'     => get_bloginfo( 'version' ) ,
                    ) ;

            $request = wp_remote_post( $this->query_arg_url() , array( 'body' => $necessary_data ) ) ;

            return $request ;
        }

        /**
         * Activate license key for this site
         * */
        protected function activate( $license_key ) {
            try {
                $response_data = array() ;

                //verify the key
                $activated_response = $this->verify_activate_data( 'activate_licensekey' , $license_key ) ;
                if ( is_wp_error( $activated_response ) || wp_remote_retrieve_response_code( $activated_response ) !== 200 )
                    throw new Exception( $activated_response->get_error_message() ) ;

                $response = json_decode( wp_remote_retrieve_body( $activated_response ) ) ;
                if ( ! is_object( $response ) || ! $response->success )
                    throw new Exception( $this->error_messages( $response->errorcode ) ) ;


                update_option( $this->license_option , $response ) ;
                update_option( $this->license_key_option , $response->license_key ) ;
                $response_data[ 'success_msg' ] = esc_html__( 'Activated Successfully' , HRW_LOCALE ) ;

                wp_send_json_success( $response_data ) ;
            } catch ( Exception $ex ) {
                wp_send_json_error( array( 'error_msg' => $ex->getMessage() ) ) ;
            }
        }

        /**
         * Activate license key for this site
         * */
        protected function deactivate( $license_key ) {

            try {
                $saved_license_key = $this->license_key() ;
                if ( $license_key != $saved_license_key )
                    throw new Exception( esc_html__( 'Please provide Activated License Key' , HRW_LOCALE ) ) ;

                $response_data = array() ;

                //verify the key
                $deactivated_response = $this->verify_activate_data( 'deactivate_licensekey' , $license_key ) ;
                if ( is_wp_error( $deactivated_response ) || wp_remote_retrieve_response_code( $deactivated_response ) !== 200 )
                    throw new Exception( $deactivated_response->get_error_message() ) ;

                $response = json_decode( wp_remote_retrieve_body( $deactivated_response ) ) ;
                if ( ! is_object( $response ) || ! $response->success )
                    throw new Exception( $this->error_messages( $response->errorcode ) ) ;

                delete_option( $this->license_option ) ;
                delete_option( $this->license_key_option ) ;
                $response_data[ 'success_msg' ] = esc_html__( 'Deactivated Successfully' , HRW_LOCALE ) ;

                wp_send_json_success( $response_data ) ;
            } catch ( Exception $ex ) {
                wp_send_json_error( array( 'error_msg' => $ex->getMessage() ) ) ;
            }
        }

        /**
         * License Key
         * */
        public function license_key() {

            return get_option( $this->license_key_option ) ;
        }

        /**
         * API Endpoint Url
         * */
        protected function query_arg_url() {
            $api         = 'wc-api' ;
            $terminology = 'hr_autoupdater' ;
            $url         = esc_url( add_query_arg( array( $api => $terminology ) , $this->update_path ) ) ;
            return $url ;
        }

        /**
         * Error Codes
         * */
        protected function error_messages( $error_code ) {

            $error_messages_array = array(
                '5001' => esc_html__( 'Invalid license key' , HRW_LOCALE ) ,
                '5002' => esc_html__( 'Already verified license key' , HRW_LOCALE ) ,
                '5003' => esc_html__( 'Support expired' , HRW_LOCALE ) ,
                '5004' => esc_html__( 'License key not verified' , HRW_LOCALE ) ,
                '5005' => esc_html__( 'Invalid credentials' , HRW_LOCALE ) ,
                '5006' => esc_html__( 'license count exist' , HRW_LOCALE ) ,
                '5007' => esc_html__( 'Incorrect product' , HRW_LOCALE )
                    ) ;

            $error_message = isset( $error_messages_array[ $error_code ] ) ? $error_messages_array[ $error_code ] : '' ;

            return $error_message ;
        }

    }

}

