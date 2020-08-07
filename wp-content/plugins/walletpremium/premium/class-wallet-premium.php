<?php

/*
 * Wallet Premium Main Class
 */

if ( ! defined ( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists ( 'HR_Wallet_Premium' ) ) {

    /**
     * Main HR_Wallet_Premium Class.
     * */
    final class HR_Wallet_Premium {

        /**
         * The single instance of the class.
         * */
        protected static $_instance = null ;

        /**
         * Load HR_Wallet_Premium Class in Single Instance
         */
        public static function instance() {
            if ( is_null ( self::$_instance ) ) {
                self::$_instance = new self() ;
            }

            return self::$_instance ;
        }

        /* Cloning has been forbidden */

        public function __clone() {
            _doing_it_wrong ( __FUNCTION__ , 'You are not allowed to perform this action!!!' , '1.0' ) ;
        }

        /**
         * Unserialize the class data has been forbidden
         * */
        public function __wakeup() {
            _doing_it_wrong ( __FUNCTION__ , 'You are not allowed to perform this action!!!' , '1.0' ) ;
        }

        /**
         * Constructor
         * */
        public function __construct() {

            $this->include_files () ;
        }

        /**
         * Include required files
         * */
        private function include_files() {

            include_once(HRW_ABSPATH . 'premium/inc/hrwp-common-functions.php') ;

            include_once(HRW_ABSPATH . 'premium/inc/class-hrwp-order-management.php') ;
            include_once(HRW_ABSPATH . 'premium/inc/class-hrwp-filters.php') ;

            include_once(HRW_ABSPATH . 'premium/inc/class-hrwp-register-post-type.php') ;
            include_once(HRW_ABSPATH . 'premium/inc/class-hrwp-register-post-status.php') ;

            include_once(HRW_ABSPATH . 'premium/inc/class-hrwp-file-uploader.php') ;
            include_once(HRW_ABSPATH . 'premium/inc/class-hrwp-sms-handler.php') ;
            include_once(HRW_ABSPATH . 'premium/inc/class-hrwp-cron-handler.php') ;
            include_once(HRW_ABSPATH . 'premium/inc/class-hrwp-withdrawal-handler.php') ;
            include_once(HRW_ABSPATH . 'premium/inc/hrwp-account-statement-pdf-handler.php') ;
            include_once(HRW_ABSPATH . 'premium/inc/hrwp-gift-card-pdf-handler.php') ;

            //Entity
            include_once(HRW_ABSPATH . 'premium/inc/entity/class-hrw-fund-transfer.php') ;
            include_once(HRW_ABSPATH . 'premium/inc/entity/class-hrw-fund-transfer-log.php') ;
            include_once(HRW_ABSPATH . 'premium/inc/entity/class-hrw-wallet-withdrawal.php') ;
            include_once(HRW_ABSPATH . 'premium/inc/entity/class-hrw-auto-topup.php') ;
            include_once(HRW_ABSPATH . 'premium/inc/entity/class-hrw-cashback.php') ;
            include_once(HRW_ABSPATH . 'premium/inc/entity/class-hrw-cashback-log.php') ;
            include_once(HRW_ABSPATH . 'premium/inc/entity/class-hrw-gift-card.php') ;
            include_once(HRW_ABSPATH . 'premium/inc/entity/class-hrw-discount.php') ;

            if ( is_admin () )
                $this->include_admin_files () ;

            if ( ! is_admin () || defined ( 'DOING_AJAX' ) )
                $this->include_frontend_files () ;
        }

        /**
         * Include admin files
         * */
        private function include_admin_files() {
            include_once(HRW_ABSPATH . 'premium/inc/admin/class-hrwp-export-csv.php') ;
            include_once(HRW_ABSPATH . 'premium/inc/admin/class-hrwp-admin-assets.php') ;
        }

        /**
         * Include frontend files
         * */
        private function include_frontend_files() {
            include_once(HRW_ABSPATH . 'premium/inc/frontend/class-hrwp-fund-transfer-handler.php') ;
            include_once(HRW_ABSPATH . 'premium/inc/frontend/class-hrwp-cashback-handler.php') ;
            include_once(HRW_ABSPATH . 'premium/inc/frontend/class-hrwp-discount-handler.php') ;
            include_once(HRW_ABSPATH . 'premium/inc/frontend/class-hrwp-topup-handler.php') ;
            include_once(HRW_ABSPATH . 'premium/inc/frontend/class-hrwp-wallet-usage.php') ;
            include_once(HRW_ABSPATH . 'premium/inc/frontend/class-hrwp-frontend-assets.php') ;
        }

        /**
         * templates
         * */
        public function templates() {
            return HRW_PLUGIN_PATH . '/premium/templates/' ;
        }

    }

}

//return Wallet Premium class object
if ( ! function_exists ( 'HRWP' ) ) {

    function HRWP() {
        return HR_Wallet_Premium::instance () ;
    }

}

//initialize the Premium. 
HRWP () ;

