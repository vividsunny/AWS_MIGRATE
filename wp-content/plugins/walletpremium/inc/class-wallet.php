<?php

/*
 * Wallet Main Class
 */

if( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if( ! class_exists( 'HR_Wallet' ) ) {

    /**
     * Main HR_Wallet Class.
     * */
    final class HR_Wallet {

        /**
         * Version
         * */
        private $version = '2.5.9' ;

        /**
         * Modules
         * */
        protected $modules ;

        /**
         * Notifications
         * */
        protected $notifications ;

        /**
         * Gateways
         * */
        protected $gateways ;

        /**
         * Background Process
         * */
        protected $background_process ;

        /**
         * License
         * */
        protected $license ;

        /**
         * Update checker
         * */
        protected $update_checker ;

        /**
         * The single instance of the class.
         * */
        protected static $_instance = null ;

        /**
         * Load HR_Wallet Class in Single Instance
         */
        public static function instance() {
            if( is_null( self::$_instance ) ) {
                self::$_instance = new self() ;
            }

            return self::$_instance ;
        }

        /* Cloning has been forbidden */

        public function __clone() {
            _doing_it_wrong( __FUNCTION__ , 'You are not allowed to perform this action!!!' , '1.0' ) ;
        }

        /**
         * Unserialize the class data has been forbidden
         * */
        public function __wakeup() {
            _doing_it_wrong( __FUNCTION__ , 'You are not allowed to perform this action!!!' , '1.0' ) ;
        }

        /**
         * Constructor
         * */
        public function __construct() {

            /* Include once will help to avoid fatal error by load the files when you call init hook */
            include_once( ABSPATH . 'wp-admin/includes/plugin.php' ) ;

            $this->header_already_sent_problem() ;
            $this->define_constants() ;
            $this->translate_file() ;
            $this->include_files() ;
            $this->init_hooks() ;
        }

        /**
         * Function to prevent header error that says you have already sent the header.
         */
        private function header_already_sent_problem() {
            ob_start() ;
        }

        /**
         * Initialize the translate files.
         * */
        private function translate_file() {
            load_plugin_textdomain( HRW_LOCALE , false , dirname( plugin_basename( __FILE__ ) ) . '/languages' ) ;
        }

        /**
         * Prepare the constants value array.
         * */
        private function define_constants() {
            $protocol = 'http://' ;

            if( isset( $_SERVER[ 'HTTPS' ] ) && ($_SERVER[ 'HTTPS' ] == 'on' || $_SERVER[ 'HTTPS' ] == 1) || isset( $_SERVER[ 'HTTP_X_FORWARDED_PROTO' ] ) && $_SERVER[ 'HTTP_X_FORWARDED_PROTO' ] == 'https' ) {
                $protocol = 'https://' ;
            }

            $constant_array = array(
                'HRW_VERSION'        => $this->version ,
                'HRW_LOCALE'         => 'wallet' ,
                'HRW_FOLDER_NAME'    => 'wallet' ,
                'HRW_PROTOCOL'       => $protocol ,
                'HRW_ABSPATH'        => dirname( HRW_PLUGIN_FILE ) . '/' ,
                'HRW_ADMIN_URL'      => admin_url( 'admin.php' ) ,
                'HRW_ADMIN_AJAX_URL' => admin_url( 'admin-ajax.php' ) ,
                'HRW_PLUGIN_SLUG'    => plugin_basename( HRW_PLUGIN_FILE ) ,
                'HRW_PLUGIN_PATH'    => untrailingslashit( plugin_dir_path( HRW_PLUGIN_FILE ) ) ,
                'HRW_PLUGIN_URL'     => untrailingslashit( plugins_url( '/' , HRW_PLUGIN_FILE ) ) ,
                    ) ;

            $constant_array = apply_filters( 'hrw_define_constants' , $constant_array ) ;

            if( is_array( $constant_array ) && ! empty( $constant_array ) ) {
                foreach( $constant_array as $name => $value ) {
                    $this->define_constant( $name , $value ) ;
                }
            }
        }

        /**
         * Define the Constants value.
         * */
        private function define_constant( $name , $value ) {
            if( ! defined( $name ) ) {
                define( $name , $value ) ;
            }
        }

        /**
         * Include required files
         * */
        private function include_files() {


            //function
            include_once(HRW_ABSPATH . 'inc/hrw-common-functions.php') ;

            //Abstract
            include_once(HRW_ABSPATH . 'inc/abstracts/class-hrw-post.php') ;

            include_once(HRW_ABSPATH . 'inc/class-hrw-cron-handler.php') ;

            //class
            include_once(HRW_ABSPATH . 'inc/modules/class-hrw-module-instances.php') ;
            include_once(HRW_ABSPATH . 'inc/notifications/class-hrw-notification-instances.php') ;

            include_once(HRW_ABSPATH . 'inc/class-hrw-register-post-type.php') ;
            include_once(HRW_ABSPATH . 'inc/class-hrw-register-post-status.php') ;

            include_once(HRW_ABSPATH . 'inc/class-hrw-wc-log.php') ;

            //Update
            include_once(HRW_ABSPATH . 'inc/class-hrw-updates.php') ;

            include_once(HRW_ABSPATH . 'inc/class-hrw-install.php') ;
            include_once(HRW_ABSPATH . 'inc/class-hrw-datetime.php') ;
            include_once(HRW_ABSPATH . 'inc/class-hrw-query.php') ;
            include_once(HRW_ABSPATH . 'inc/class-hrw-order-management.php') ;
            include_once(HRW_ABSPATH . 'inc/class-hrw-credit-debit-handler.php') ;
            include_once(HRW_ABSPATH . 'inc/privacy/class-hrw-privacy.php') ;

            //Entity
            include_once(HRW_ABSPATH . 'inc/entity/class-hrw-wallet.php') ;
            include_once(HRW_ABSPATH . 'inc/entity/class-hrw-transaction-log.php') ;

            if( is_admin() )
                $this->include_admin_files() ;

            if( ! is_admin() || defined( 'DOING_AJAX' ) )
                $this->include_frontend_files() ;

            //Initiate premium version
            if( hrw_is_premium() ) {
                include_once(HRW_ABSPATH . 'premium/class-wallet-premium.php') ;
            } else {
                include_once(HRW_ABSPATH . 'inc/class-hrw-premium-info-handler.php') ;
            }
        }

        /**
         * Include admin files
         * */
        private function include_admin_files() {
            include_once(HRW_ABSPATH . 'inc/class-hrw-pages.php') ;
            include_once(HRW_ABSPATH . 'inc/admin/menu/class-hrw-wallet-post-type.php') ;
            include_once(HRW_ABSPATH . 'inc/admin/menu/class-hrw-transaction-log-post-type.php') ;
            include_once(HRW_ABSPATH . 'inc/admin/class-hrw-admin-assets.php') ;
            include_once(HRW_ABSPATH . 'inc/admin/class-hrw-admin-ajax.php') ;
            include_once(HRW_ABSPATH . 'inc/admin/menu/class-hrw-menu-management.php') ;
            include_once(HRW_ABSPATH . 'inc/admin/menu/class-hrw-user-handler.php') ;
        }

        /**
         * Include frontend files
         * */
        private function include_frontend_files() {
            include_once(HRW_ABSPATH . 'inc/frontend/class-hrw-dashboard.php') ;
            include_once(HRW_ABSPATH . 'inc/frontend/class-hrw-user-wallet.php') ;
            include_once(HRW_ABSPATH . 'inc/frontend/class-hrw-topup-handler.php') ;
            include_once(HRW_ABSPATH . 'inc/frontend/class-hrw-form-handler.php') ;
            include_once(HRW_ABSPATH . 'inc/frontend/class-hrw-shortcodes.php') ;
            include_once(HRW_ABSPATH . 'inc/frontend/class-hrw-wallet-usage.php') ;
            include_once(HRW_ABSPATH . 'inc/frontend/class-hrw-myaccount-handler.php') ;
            include_once(HRW_ABSPATH . 'inc/frontend/class-hrw-frontend-assets.php') ;
            include_once(HRW_ABSPATH . 'inc/frontend/class-hrw-frontend.php') ;
        }

        /**
         * Define the hooks 
         * */
        private function init_hooks() {

            add_action( 'plugins_loaded' , array( $this , 'plugins_loaded' ) ) ;

            //Register the plugin 
            register_activation_hook( HRW_PLUGIN_FILE , array( 'HRW_Install' , 'install' ) ) ;
        }

        /**
         * Plugins Loaded
         * */
        public function plugins_loaded() {
            do_action( 'hrw_before_plugin_loaded' ) ;

            $this->maybe_deactivate_free_plugin() ;

            if( ! is_admin() || defined( 'DOING_AJAX' ) )
                HRW_Wallet_User::init() ;

            //Payment Gateway
            include_once(HRW_ABSPATH . 'inc/gateways/class-hrw-wallet-gateway.php') ;
            //Background process
            include_once(HRW_ABSPATH . 'inc/background-updater/hrw-background-process.php') ;
            //Upgrade
            include_once(HRW_ABSPATH . 'inc/upgrade/class-hrw-license-handler.php') ;
            include_once(HRW_ABSPATH . 'inc/upgrade/class-hrw-plugin-update-checker.php') ;

            $this->license        = new HRW_License_Handler( HRW_VERSION , HRW_PLUGIN_SLUG ) ;
            $this->update_checker = new HRW_Plugin_Update_Checker( HRW_VERSION , HRW_PLUGIN_SLUG , $this->license->license_key() ) ;

            $this->gateways           = new HR_Wallet_Gateway() ;
            $this->background_process = new HRW_Background_Process() ;

            $this->modules       = HRW_Module_Instances::get_modules() ;
            $this->notifications = HRW_Notification_Instances::get_notifications() ;

            do_action( 'hrw_after_plugin_loaded' ) ;
        }

        /**
         * May be deactivate the free plugin
         * */
        public function maybe_deactivate_free_plugin() {
            //Return if free plugin is not activated
            if( ! is_plugin_active( 'wallet/wallet.php' ) )
                return ;

            //Deactivate the free plugin
            deactivate_plugins( plugin_basename( 'wallet/wallet.php' ) ) ;

            //Add notice
            add_action( 'admin_notices' , array( $this , 'deactivation_notice' ) ) ;
        }

        /**
         * Display Notice
         * */
        public function deactivation_notice() {
            echo '<div class="error">' ;
            echo '<p>' . sprintf( esc_html__( 'You cannot Activate Both %s And %s at the same time' , HRW_LOCALE ) , '<b>' . esc_html__( 'Wallet' , HRW_LOCALE ) . '</b>' , '<b>' . esc_html__( 'Wallet Premium' , HRW_LOCALE ) . '</b>' ) . '</p>' ;
            echo '</div>' ;
        }

        /**
         * templates
         * */
        public function templates() {
            return HRW_PLUGIN_PATH . '/templates/' ;
        }

        /**
         * License
         * */
        public function license() {
            return $this->license ;
        }

        /**
         * Update Checker
         * */
        public function update_checker() {
            return $this->update_checker ;
        }

        /**
         * Modules instances
         * */
        public function modules() {
            return $this->modules ;
        }

        /**
         * Notifications instances
         * */
        public function notifications() {
            return $this->notifications ;
        }

        /**
         * Gateways
         * */
        public function gateways() {
            return $this->gateways ;
        }

        /**
         * Background Process
         * */
        public function background_process() {
            return $this->background_process ;
        }

    }

}

