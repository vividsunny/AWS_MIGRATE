<?php

/*
 * Plugin Update Checker
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'HRW_Plugin_Update_Checker' ) ) {

    /**
     * HRW_Plugin_Update_Checker Class
     * */
    class HRW_Plugin_Update_Checker {
        /*
         * Plugin Version Number
         */

        protected $version ;
        /*
         * Plugin Directory Slug
         */
        protected $dir_slug ;
        /*
         * License Key
         */
        protected $license_key ;
        /*
         * Secret Key
         */
        protected $secret_key    = '8b1a9953c4611296a827abf8c47804d7' ;
        /*
         * Item Key Name
         */
        protected $item_key_name = 'wallet-premium' ;
        /*
         * Site Url
         */
        protected $update_path   = 'https://hoicker.com/' ;

        /**
         * HRW_Plugin_Update_Checker Class Initialization
         * */
        public function __construct( $plugin_version , $plugin_slug , $license_key ) {
            $this->version     = ( string ) $plugin_version ;
            $this->dir_slug    = ( string ) $plugin_slug ;
            $this->license_key = ( string ) $license_key ;

            add_filter( 'plugins_api' , array( &$this , 'check_info' ) , 10 , 3 ) ;
            add_filter( "in_plugin_update_message-{$plugin_slug}" , array( $this , 'plugin_update_row' ) , 10 , 2 ) ;
            add_filter( 'pre_set_site_transient_update_plugins' , array( $this , 'initialize_update' ) ) ;
        }

        /**
         * Check Update new available for the plugin from API Endpoint
         * */
        public function check_info( $obj , $action , $arg ) {
            $explode_slug = explode( '/' , $this->dir_slug ) ;
            if ( ! is_array( $explode_slug ) || empty( $explode_slug ) )
                return $obj ;

            $slug_plugin       = $explode_slug[ 1 ] ;
            $replace_extension = str_replace( '.php' , '' , $slug_plugin ) ;
            $slug              = $replace_extension ;
            if ( ($action == 'query_plugins' || $action == 'plugin_information') && isset( $arg->slug ) && $arg->slug === $slug ) {
                $plugin_info_response = unserialize( $this->verify_data( 'info' ) ) ;
                if ( isset( $plugin_info_response[ 'success' ] ) && $plugin_info_response[ 'success' ] )
                    return $plugin_info_response[ 'package_info' ] ;
            }
            return $obj ;
        }

        /**
         * Initialize the new update transient
         * */
        public function initialize_update( $transient ) {

            $update_data = $this->verify_data( 'fetch_update_details' ) ;
            if ( ! $update_data )
                return $transient ;

            $update_data = unserialize( $update_data ) ;
            if ( ! isset( $update_data[ 'success' ] ) || ! $update_data[ 'success' ] )
                return $transient ;

            $obj          = new stdClass() ;
            $explode_slug = explode( '/' , $this->dir_slug ) ;
            if ( is_array( $explode_slug ) && ! empty( $explode_slug ) ) {
                $slug_plugin       = $explode_slug[ 1 ] ;
                $replace_extension = str_replace( '.php' , '' , $slug_plugin ) ;
                $obj->slug         = $replace_extension ;
            }

            $obj->new_version     = $update_data[ 'version' ] ;
            $obj->package         = $update_data[ 'package' ] ;
            $obj->warning_message = $update_data[ 'warning_message' ] ;

            if ( isset( $update_data[ 'version' ] ) && ($update_data[ 'version' ] > $this->version) ) {
                $transient->response[ $this->dir_slug ] = $obj ;
                unset( $transient->no_update[ $this->dir_slug ] ) ;
            } else {
                $transient->no_update[ $this->dir_slug ] = $obj ;
                unset( $transient->response[ $this->dir_slug ] ) ;
            }

            return $transient ;
        }

        /**
         * Display Message About When License Key Empty
         * */
        public function plugin_update_row( $plugin_data , $response ) {
            if ( ! isset( $response ) )
                return false ;

            if ( isset( $response->warning_message ) && $response->warning_message == '5006' ) {
                printf( esc_html__( 'There is a new version of %1$s available.<em>Automatic update is unavailable for this plugin.</em>' ) , $packages->new_version ) ;
            }
        }

        /**
         * Verify data from API Endpoint
         * */
        protected function verify_data( $action ) {
            $necessary_data = array(
                'action'         => $action ,
                'license_key'    => $this->license_key ,
                'current_site'   => site_url() ,
                'plugin_version' => $this->version ,
                'slug'           => $this->dir_slug ,
                'free'           => ! (hrw_is_premium()) ,
                'secret_key'     => $this->secret_key ,
                'item_key_name'  => $this->item_key_name ,
                'wc_version'     => WC_VERSION ,
                'wp_version'     => get_bloginfo( 'version' ) ,
                    ) ;

            $request = wp_remote_post( $this->query_arg_url() , array( 'body' => $necessary_data ) ) ;
            if ( ! is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) === 200 )
                return json_decode( wp_remote_retrieve_body( $request ) ) ;

            return false ;
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
                '5006' => esc_html__( 'license count exist' , HRW_LOCALE )
                    ) ;

            $error_message = isset( $error_messages_array[ $error_code ] ) ? $error_messages_array[ $error_code ] : '' ;

            return $error_message ;
        }

    }

}