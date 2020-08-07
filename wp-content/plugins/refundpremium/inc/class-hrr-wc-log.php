<?php

/**
 * WooCommerce Log.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'HRR_WooCommerce_Log' ) ) {

	/**
	 * Class.
	 */
	class HRR_WooCommerce_Log {

		public static $log = false ;

		/**
		 * Save error log on WooCommerce Log.
		 */
		public static function log( $message, $source = 'hrr-refund-upgrade', $level = 'info', $context = array() ) {
			if ( empty( self::$log ) ) {
				self::$log = new WC_Logger() ;
			}

			if ( empty( $context ) ) {
				$context = array( 'source' => $source , '_legacy' => true ) ;
			}

			$implements = class_implements( 'WC_Logger' ) ;

			if ( is_array( $implements ) && in_array( 'WC_Logger_Interface' , $implements ) ) {
				self::$log->log( $level , $message , $context ) ;
			} else {
				self::$log->add( $source , $message ) ;
			}
		}

	}

}
