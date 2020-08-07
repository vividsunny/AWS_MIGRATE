<?php

/*
 * Updates
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'HRW_Updates' ) ) {

    /**
     * Class.
     */
    class HRW_Updates {

        /**
         * DB updates and callbacks that need to be run per version.
         *
         * @var array
         */
        private static $updates = array(
            'update_200' => '2.0.0' ,
                ) ;

        /**
         * Maybe run updates if the versions do not match.
         */
        public static function maybe_run() {

            // return if it will not run admin.
            if ( ! is_admin() || defined( 'DOING_AJAX' ) || defined( 'DOING_CRON' ) )
                return ;

            if ( version_compare( get_option( 'hrw_update_version' ) , HRW_VERSION , '<' ) ) {
                HRW_Install::install() ;
                self::maybe_update_version() ;
            }
        }

        /**
         * Update HRW DB version to current if unavailable.
         */
        public static function update_version( $version = null ) {
            update_option( 'hrw_update_version' ,  ! is_numeric( $version ) ? HRW_VERSION : $version  ) ;
        }

        /**
         * Check whether we need to show or run db updates during install.
         */
        private static function maybe_update_version() {
            $needs_db_update = version_compare( get_option( 'hrw_update_version' ) , max( array_values( self::$updates ) ) , '<' ) ;

            if ( ! $needs_db_update ) {
                self::update_version() ;
                return ;
            }

            //Update HRW database
            foreach ( self::$updates as $update => $updating_version ) {
                if ( is_callable( array( 'HRW_Updates' , $update ) ) ) {
                    call_user_func_array( array( 'HRW_Updates' , $update ) , array( $updating_version ) ) ;
                }
            }
        }

        public static function update_200( $updating_version ) {

            if ( false === get_option( 'hr_wallet_allow_users_to_fund' ) || get_option( 'hrw_upgrade_success' ) == 'yes' )
                return ;

            hrw()->background_process()->trigger() ;
        }

    }

}