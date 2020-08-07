<?php

/**
 * Modules Instances Class
 */
if ( ! defined ( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists ( 'HRW_Module_Instances' ) ) {

    /**
     * Class HRW_Module_Instances
     */
    class HRW_Module_Instances {
        /*
         * Modules
         */

        private static $modules = array () ;

        /*
         * Get Modules
         */

        public static function get_modules() {
            if ( ! self::$modules ) {
                self::load_modules () ;
            }
            return self::$modules ;
        }

        /*
         * Load all Modules
         */

        public static function load_modules() {

            if ( ! class_exists ( 'HRW_Modules' ) )
                include HRW_PLUGIN_PATH . '/inc/abstracts/class-hrw-modules.php' ;

            $default_module_classes = array (
                'fund-transfer'            => 'HRW_Fund_Transfer_Module' ,
                'cashback'                 => 'HRW_Cashback_Module' ,
                'discount'                 => 'HRW_Discount_Module' ,
                'gift-card'                => 'HRW_GiftCard' ,
                'wallet-auto-topup'        => 'HRW_Auto_Topup_Module' ,
                'wallet-withdrawal'        => 'HRW_Wallet_Withdrawal_Module' ,
                'security-settings'        => 'HRW_Security_Settings_Module' ,
                'wallet-account-statement' => 'HRW_Wallet_Account_Statement_Module' ,
                'sms'                      => 'HRW_SMS_Module'
                    ) ;

            foreach ( $default_module_classes as $file_name => $module_class ) {

                // include file
                include 'class-' . $file_name . '.php' ;

                //add module
                self::add_module ( new $module_class ) ;
            }
        }

        /**
         * Add a Module
         */
        public static function add_module( $module ) {

            self::$modules[ $module->get_id () ] = $module ;

            return new self() ;
        }

        /**
         * Get module by id
         */
        public static function get_module_by_id( $module_id ) {
            $modules = self::get_modules () ;

            return isset ( $modules[ $module_id ] ) ? $modules[ $module_id ] : false ;
        }

    }

}
