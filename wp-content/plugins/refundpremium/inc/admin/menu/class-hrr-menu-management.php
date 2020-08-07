<?php

/**
 * Menu Management.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'HRR_Menu_Management' ) ) {

	include_once('class-hrr-settings.php') ;

	/**
	 * HRR_Menu_Management Class.
	 */
	class HRR_Menu_Management {

		/**
		 * Plugin slug.
		 */
		protected static $plugin_slug = 'hrr' ;

		/**
		 * Menu slug.
		 */
		protected static $menu_slug = 'hrr_request' ;

		/**
		 * Settings slug.
		 */
		protected static $settings_slug = 'hrr_settings' ;

		/**
		 * Class initialization.
		 */
		public static function init() {
			//Add Admin Menu Page.
			add_action( 'admin_menu' , array( __CLASS__ , 'add_menu_pages' ) ) ;
			//Add Custom Screen Ids.
			add_filter( 'woocommerce_screen_ids' , array( __CLASS__ , 'add_custom_wc_screen_ids' ) , 9 , 1 ) ;
		}

		/**
		 * Add Custom Screen IDs in WooCommerce.
		 */
		public static function add_custom_wc_screen_ids( $wc_screen_ids ) {
			$screen_ids = hrr_page_screen_ids() ;

			$newscreenids = get_current_screen() ;
			$screenid     = str_replace( 'edit-' , '' , $newscreenids->id ) ;

			//Return if current page is not refund page.
			if ( ! in_array( $screenid , $screen_ids ) ) {
				return $wc_screen_ids ;
			}

			$wc_screen_ids[] = $screenid ;

			return $wc_screen_ids ;
		}

		/**
		 * Add menu pages.
		 */
		public static function add_menu_pages() {
			$dash_icon_url = HRR_PLUGIN_URL . '/assets/images/refund-icon16.png' ;

			add_menu_page( esc_html__( 'Refund Premium' , 'refund' ) , esc_html__( 'Refund Premium' , 'refund' ) , 'manage_options' , self::$menu_slug , '' , $dash_icon_url ) ;

			//Settings Submenu.
			$settings_page = add_submenu_page( self::$menu_slug , esc_html__( 'Settings' , 'refund' ) , esc_html__( 'Settings' , 'refund' ) , 'manage_options' , self::$settings_slug , array( __CLASS__ , 'settings_page' ) ) ;

			add_action( sanitize_key( 'load-' . $settings_page ) , array( __CLASS__ , 'settings_page_init' ) ) ;
		}

		/**
		 * Settings page init.
		 */
		public static function settings_page_init() {
			global $current_tab , $current_section , $current_sub_section , $current_action ;

			//Include settings pages.
			$settings = HRR_Settings::get_settings_pages() ;

			$tabs = hrr_get_allowed_setting_tabs() ;

			//Get current tab/section.
			$current_tab = ( !isset($_GET[ 'tab' ]) || empty( $_GET[ 'tab' ] ) || ! array_key_exists( wc_clean(wp_unslash($_GET[ 'tab' ])) , $tabs ) ) ? key( $tabs ) : wc_clean(wp_unslash( $_GET[ 'tab' ] ) ) ;

			$section = isset( $settings[ $current_tab ] ) ? $settings[ $current_tab ]->get_sections() : array() ;

			$current_section     = empty( $_REQUEST[ 'section' ] ) ? key( $section ) : wc_clean( wp_unslash( $_REQUEST[ 'section' ] ) ) ;
			$current_section     = empty( $current_section ) ? $current_tab : $current_section ;
			$current_sub_section = empty( $_REQUEST[ 'subsection' ] ) ? '' : wc_clean( wp_unslash( $_REQUEST[ 'subsection' ] ) ) ;
			$current_action      = empty( $_REQUEST[ 'action' ] ) ? '' : wc_clean( wp_unslash( $_REQUEST[ 'action' ] ) ) ;

			do_action( sanitize_key( self::$plugin_slug . '_settings_save_' . $current_tab ) , $current_section ) ;
			do_action( sanitize_key( self::$plugin_slug . '_settings_reset_' . $current_tab ) , $current_section ) ;

			//Add Custom Field in Settings.
			add_action( 'woocommerce_admin_field_hrr_custom_fields' , array( __CLASS__ , 'custom_fields_output' ) ) ;
			//Save Custom Field in Settings.
			add_filter( 'woocommerce_admin_settings_sanitize_option_hrr_custom_fields' , array( __CLASS__ , 'save_custom_fields' ) , 10 , 3 ) ;
		}

		/**
		 * Settings page output.
		 */
		public static function settings_page() {
			HRR_Settings::output() ;
		}

		/**
		 * Output the custom field settings.
		 */
		public static function custom_fields_output( $options ) {

			HRR_Settings::output_fields( $options ) ;
		}

		/**
		 * Save Custom Field settings.
		 */
		public static function save_custom_fields( $value, $option, $raw_value ) {

			HRR_Settings::save_fields( $value , $option , $raw_value ) ;
		}

	}

	HRR_Menu_Management::init() ;
}
