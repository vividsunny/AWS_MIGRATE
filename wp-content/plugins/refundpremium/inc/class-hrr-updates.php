<?php

/*
 * Updates.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'HRR_Updates' ) ) {

	/**
	 * Class.
	 */
	class HRR_Updates {

		/**
		 * DB updates and callback that need to be run per version.
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

			//Return if it will not run admin.
			if ( ! is_admin() || defined( 'DOING_AJAX' ) || defined( 'DOING_CRON' ) ) {
				return ;
			}

			if ( version_compare( get_option( 'hrr_update_version' ) , HRR_VERSION , '<' ) ) {
				HRR_Install::install() ;
				self::maybe_update_version() ;
			}
		}

		/**
		 * Update HRR DB version to current if unavailable.
		 */
		public static function update_version( $version = null ) {
			update_option( 'hrr_update_version' , ! is_numeric( $version ) ? HRR_VERSION : $version  ) ;
		}

		/**
		 * Check whether we need to show or run db updates during install.
		 */
		private static function maybe_update_version() {
			$needs_db_update = version_compare( get_option( 'hrr_update_version' ) , max( array_values( self::$updates ) ) , '<' ) ;

			if ( ! $needs_db_update ) {
				self::update_version() ;
				return ;
			}

			//Update HRR database.
			foreach ( self::$updates as $update => $updating_version ) {
				if ( is_callable( array( 'HRR_Updates' , $update ) ) ) {
					call_user_func_array( array( 'HRR_Updates' , $update ) , array( $updating_version ) ) ;
				}
			}
		}

		public static function update_200( $updating_version ) {

						$update_option = get_option( 'hrr_upgrade_success' );
			if (  'yes' == $update_option ) {
				return ;
			}

			hrr()->background_process()->trigger() ;
		}

	}

}
