<?php

/**
 * Settings Page/Tab.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'HRR_Settings_Page' ) ) {

	/**
	 * HRR_Settings_Page.
	 */
	abstract class HRR_Settings_Page {

		/**
		 * Setting page id.
		 */
		protected $id = '' ;

		/**
		 * Setting page label.
		 */
		protected $label = '' ;

		/**
		 * Setting page code.
		 */
		protected $code = '' ;

		/**
		 * Plugin slug.
		 */
		protected $plugin_slug = 'hrr' ;

		/**
		 * Constructor.
		 */
		public function __construct() {
			add_filter( sanitize_key( $this->plugin_slug . '_settings_tabs_array' ) , array( $this , 'add_settings_page' ) , 20 ) ;
			add_action( sanitize_key( $this->plugin_slug . '_sections_' . $this->id ) , array( $this , 'output_sections' ) ) ;
			add_action( sanitize_key( $this->plugin_slug . '_settings_' . $this->id ) , array( $this , 'output' ) ) ;
			add_action( sanitize_key( $this->plugin_slug . '_settings_buttons_' . $this->id ) , array( $this , 'output_buttons' ) ) ;
			add_action( sanitize_key( $this->plugin_slug . '_settings_save_' . $this->id ) , array( $this , 'save' ) ) ;
			add_action( sanitize_key( $this->plugin_slug . '_settings_reset_' . $this->id ) , array( $this , 'reset' ) ) ;
			add_action( sanitize_key( $this->plugin_slug . '_after_setting_buttons_' . $this->id ) , array( $this , 'output_extra_fields' ) ) ;
		}

		/**
		 * Get settings page ID.
		 */
		public function get_id() {
			return $this->id ;
		}

		/**
		 * Get settings page label.
		 */
		public function get_label() {
			return $this->label ;
		}

		/**
		 * Get plugin slug.
		 */
		public function get_plugin_slug() {
			return $this->plugin_slug ;
		}

		/**
		 * Add this page to settings.
		 */
		public function add_settings_page( $pages ) {
			$pages[ $this->id ] = array(
				'label' => $this->label ,
				'code'  => $this->code
					) ;

			return $pages ;
		}

		/**
		 * Get settings array.
		 */
		public function get_settings( $current_section = '' ) {
			$settings = array() ;
			$function = $current_section . '_section_array' ;

			if ( method_exists( $this , $function ) ) {
				$settings = $this->$function() ;
			}

			return apply_filters( sanitize_key( $this->plugin_slug . '_get_settings_' . $this->id ) , $settings , $current_section ) ;
		}

		/**
		 * Get sections.
		 */
		public function get_sections() {
			return apply_filters( sanitize_key( $this->plugin_slug . '_get_sections_' . $this->id ) , array() ) ;
		}

		/**
		 * Output sections.
		 */
		public function output_sections() {
			global $current_section ;

			$sections = $this->get_sections() ;

			if ( empty( $sections ) || 1 === count( $sections ) ) {
				return ;
			}

			echo '<ul class="subsubsub ' . $this->plugin_slug . '-sections ' . $this->plugin_slug . '-subtab">' ;

			foreach ( $sections as $id => $section ) {
				echo '<li>'
				. '<a href="' . esc_url( hrr_get_settings_page_url( array( 'page' => 'hrr_settings' , 'tab' => $this->id , 'section' => sanitize_title( $id ) ) ) ) . '" '
				. 'class="' . ( $current_section == $id ? 'current' : '' ) . '"><i class="fa ' . esc_attr( $section[ 'code' ] ) . '"></i>' . esc_html( $section[ 'label' ] ) . '</a></li>' ;
			}

			echo '</ul><br class="clear" />' ;
		}

		/**
		 * Output the settings.
		 */
		public function output() {
			global $current_section , $current_sub_section ;

			$section = ( $current_sub_section ) ? $current_sub_section : $current_section ;

			$settings = $this->get_settings( $section ) ;

			WC_Admin_Settings::output_fields( $settings ) ;

			do_action( sanitize_key( $this->plugin_slug . '_' . $this->id . '_' . $section . '_display' ) ) ;
		}

		/**
		 * Output the settings buttons.
		 */
		public function output_buttons() {

			HRR_Settings::output_buttons() ;
		}

		/**
		 * Save settings.
		 */
		public function save() {
			global $current_section , $current_sub_section ;

			$section = ( $current_sub_section ) ? $current_sub_section : $current_section ;

			if ( ! isset( $_POST[ 'save' ] ) || empty( $_POST[ 'save' ] ) ) {// phpcs:ignore WordPress.Security.NonceVerification.NoNonceVerification
				return ;
			}
						
						check_admin_referer( 'hrr_save_settings' , '_hrr_nonce' ) ;

			$settings = $this->get_settings( $section ) ;

			WC_Admin_Settings::save_fields( $settings ) ;
			HRR_Settings::add_message( esc_html__( 'Your settings have been saved' , 'refund' ) ) ;

			do_action( sanitize_key( $this->plugin_slug . '_' . $this->id . '_settings_after_save' ) ) ;
		}

		/**
		 * Reset settings.
		 */
		public function reset() {
			global $current_section , $current_sub_section ;

			$section = ( $current_sub_section ) ? $current_sub_section : $current_section ;

			if ( ! isset( $_POST[ 'reset' ] ) || empty( $_POST[ 'reset' ] ) ) {// phpcs:ignore WordPress.Security.NonceVerification.NoNonceVerification
				return ;
			}
						
			check_admin_referer( 'hrr_reset_settings' , '_hrr_nonce' ) ;

			$settings = $this->get_settings( $section ) ;
			HRR_Settings::reset_fields( $settings ) ;
			HRR_Settings::add_message( esc_html__( 'Your settings have been reset' , 'refund' ) ) ;

			do_action( sanitize_key( $this->plugin_slug . '_' . $this->id . '_settings_after_save' ) ) ;
		}

		/**
		 * Output the extra fields.
		 */
		public function output_extra_fields() {
			
		}

		/**
		 * Get option key.
		 */
		public function get_option_key( $key ) {
			return sanitize_key( $this->plugin_slug . '_' . $this->id . '_' . $key ) ;
		}

	}

}
