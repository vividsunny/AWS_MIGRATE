<?php

/**
 * Initialize the plugin.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'HRR_Install' ) ) {

	/**
	 * HRR_Install Class.
	 */
	class HRR_Install {

		/**
		 * HRR_Install Class initialization.
		 */
		public static function init() {
			add_action( 'init' , array( 'HRR_Updates' , 'maybe_run' ) , 1 ) ;
			add_filter( 'plugin_action_links_' . HRR_PLUGIN_SLUG , array( __CLASS__ , 'settings_link' ) ) ;
		}

		/**
		 * Install.
		 */
		public static function install() {
			//Default values.
			self::set_default_values() ;
			self::update_version() ;
		}

		/**
		 * Update current version.
		 */
		private static function update_version() {
			update_option( 'hrr_version' , HRR_VERSION ) ;
		}

		/**
		 *  Settings link. 
		 */
		public static function settings_link( $links ) {
			$setting_page_link = '<a href="' . esc_url( hrr_get_settings_page_url() ) . '">' . esc_html__( 'Settings' , 'refund' ) . '</a>' ;

			array_unshift( $links , $setting_page_link ) ;

			return $links ;
		}

		/**
		 *  Set settings default values.
		 */
		public static function set_default_values() {
			if ( ! class_exists( 'HRR_Settings' ) ) {
				include_once(HRR_PLUGIN_PATH . '/inc/admin/menu/class-hrr-settings.php') ;
			}

			//Default for settings.
			$settings = HRR_Settings::get_settings_pages() ;

			foreach ( $settings as $setting ) {
				$sections = $setting->get_sections() ;
				if ( ! hrr_check_is_array( $sections ) ) {
					continue ;
				}

				foreach ( $sections as $section_key => $section ) {
					$settings_array = $setting->get_settings( $section_key ) ;
					foreach ( $settings_array as $value ) {
						if ( isset( $value[ 'default' ] ) && isset( $value[ 'id' ] ) ) {
														$option = get_option( $value[ 'id' ]);
							if ( false ==  $option ) {
								add_option( $value[ 'id' ] , $value[ 'default' ] ) ;
							}
						}
					}
				}
			}

		}

	}

	HRR_Install::init() ;
}
