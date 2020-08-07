<?php
/*  Copyright 2013  Your Inspiration Themes  (email : plugins@yithemes.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
/**
 * Admin class
 *
 * @author  YITH
 * @package YITH Infinite Scrolling
 * @version 1.0.0
 */

if ( ! defined( 'YITH_INFS' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_INFS_Admin_Premium' ) ) {
	/**
	 * Admin class.
	 * The class manage all the admin behaviors.
	 *
	 * @since 1.0.0
	 */
	class YITH_INFS_Admin_Premium extends YITH_INFS_Admin {

		/**
		 * Returns single instance of the class
		 *
		 * @since 1.0.0
		 * @return \YITH_INFS_Admin_Premium
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * @access public
		 * @since  1.0.0
		 */
		public function __construct() {

			parent::__construct();

			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

			// register plugin to licence/update system
			add_action( 'wp_loaded', array( $this, 'register_plugin_for_activation' ), 99 );
			add_action( 'admin_init', array( $this, 'register_plugin_for_updates' ) );

			// add panel type
			add_action( 'yit_panel_options-section', array( $this, 'admin_options_section' ), 10, 2 );
			// panel type ajax action add
			add_action( 'wp_ajax_yith_infinite_scroll_section', array( $this, 'yith_infinite_scroll_section_ajax' ) );
			add_action( 'wp_ajax_nopriv_yith_infinite_scroll_section', array( $this, 'yith_infinite_scroll_section_ajax' ) );
			// panel type ajax action remove
			add_action( 'wp_ajax_yith_infinite_scroll_section_remove', array( $this, 'yith_infinite_scroll_section_remove_ajax' ) );
			add_action( 'wp_ajax_nopriv_yith_infinite_scroll_section_remove', array( $this, 'yith_infinite_scroll_section_remove_ajax' ) );
		}

		/**
		 * Get sections fields
		 *
		 * @since  1.0.0
		 * @author Francesco Licandro
		 * @return array
		 */
		protected function get_section_fields() {
			return array(
				'navSelector'     => array(
					'label' => __( 'Navigation Selector', 'yith-infinite-scrolling' ),
					'desc'  => __( 'The selector that contains the navigation of this section. Selectors can be class or ID names: the first ones must have a dot in front (.class-name), while the second must have a hash (#id-name).', 'yith-infinite-scrolling' ),
				),
				'nextSelector'    => array(
					'label' => __( 'Next Selector', 'yith-infinite-scrolling' ),
					'desc'  => __( 'The selector of the link that redirects to the next page of this section. Selectors can be class or ID names: the first ones must have a dot in front (.class-name), while the second must have a hash (#id-name).', 'yith-infinite-scrolling' ),
				),
				'itemSelector'    => array(
					'label' => __( 'Item Selector', 'yith-infinite-scrolling' ),
					'desc'  => __( 'The selector of the single item in the page. Selectors can be class or ID names: the first ones must have a dot in front (.class-name), while the second must have a hash (#id-name).', 'yith-infinite-scrolling' ),
				),
				'contentSelector' => array(
					'label' => __( 'Content Selector', 'yith-infinite-scrolling' ),
					'desc'  => __( 'The selector that contains your section content. Selectors can be class or ID names: the first ones must have a dot in front (.class-name), while the second must have a hash (#id-name).', 'yith-infinite-scrolling' ),
				),
				'eventType'       => array(
					'type'    => 'select',
					'options' => array(
						'scroll'     => __( 'Infinite Scrolling', 'yith-infinite-scrolling' ),
						'button'     => __( 'Load More Button', 'yith-infinite-scrolling' ),
						'pagination' => __( 'Ajax Pagination', 'yith-infinite-scrolling' ),
					),
					'label'   => __( 'Event Type', 'yith-infinite-scrolling' ),
					'desc'    => __( 'Select the type of pagination', 'yith-infinite-scrolling' ),
					'class'   => 'yith-infs-eventype-select',
				),
				'buttonLabel'     => array(
					'label' => __( 'Button Label', 'yith-infinite-scrolling' ),
					'desc'  => __( 'Set button label', 'yith-infinite-scrolling' ),
					'value' => __( 'Load More', 'yith-infinite-scrolling' ),
				),
				'buttonClass'     => array(
					'label' => __( 'Extra Class of the Button', 'yith-infinite-scrolling' ),
					'desc'  => __( 'Add a custom class to customize the button style. Use space for multiple classes.', 'yith-infinite-scrolling' ),
				),
				'presetLoader'    => array(
					'type'          => 'loader',
					'options'       => yinfs_get_preset_loader(),
					'label'         => __( 'Choose a Loader', 'yith-infinite-scrolling' ),
					'desc'          => __( 'Choose a preset loader to use.', 'yith-infinite-scrolling' ),
					'class'         => 'yith-infs-loader-select',
					'needs_preview' => true,
				),
				'customLoader'    => array(
					'type'  => 'upload',
					'label' => __( 'Custom Loader', 'yith-infinite-scrolling' ),
					'desc'  => __( 'Upload a custom loading image. This option overrides the previous one.', 'yith-infinite-scrolling' ),
				),
				'loadEffect'      => array(
					'type'    => 'select',
					'options' => array(
						'yith-infs-zoomIn'      => __( 'Zoom in', 'yith-infinite-scrolling' ),
						'yith-infs-bounceIn'    => __( 'Bounce in', 'yith-infinite-scrolling' ),
						'yith-infs-fadeIn'      => __( 'Fade in', 'yith-infinite-scrolling' ),
						'yith-infs-fadeInDown'  => __( 'Fade in from top to down', 'yith-infinite-scrolling' ),
						'yith-infs-fadeInLeft'  => __( 'Fade in from right to left', 'yith-infinite-scrolling' ),
						'yith-infs-fadeInRight' => __( 'Fade in from left to right', 'yith-infinite-scrolling' ),
						'yith-infs-fadeInUp'    => __( 'Fade in from down to top', 'yith-infinite-scrolling' ),
					),
					'label'   => __( 'Load Effect', 'yith-infinite-scrolling' ),
					'desc'    => __( 'Type of animation for the loading of new contents.', 'yith-infinite-scrolling' ),
				),
			);
		}

		/**
		 * Template for admin section
		 *
		 * @since  1.0.0
		 * @access public
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 */
		public function admin_options_section( $option, $db_value ) {
			$fields = $this->get_section_fields();
			$id     = $this->_panel->get_id_field( $option['id'] );
			$name   = $this->_panel->get_name_field( $option['id'] );

			include YITH_INFS_TEMPLATE_PATH . '/admin/options-section.php';
		}

		/**
		 * Add admin scripts
		 *
		 * @since  1.0.0
		 * @access public
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 */
		public function admin_scripts() {

			if ( isset( $_GET['page'] ) && $_GET['page'] == $this->_panel_page ) {

				if ( class_exists( 'WC' ) ) {
					$assets_path = str_replace( array( 'http:', 'https:' ), '', WC()->plugin_url() ) . '/assets/';
					wp_enqueue_script( 'select2' );
					wp_enqueue_style( 'select2', $assets_path . 'css/select2.css' );
				}

				wp_enqueue_script( 'yith-infs-admin', YITH_INFS_ASSETS_URL . '/js/admin.js', array( 'jquery' ), $this->version, true );
				wp_enqueue_script( 'jquery-blockui', YITH_INFS_ASSETS_URL . '/js/jquery.blockUI.min.js', array( 'jquery' ), false, true );

				// CodeMirror plugin
				wp_enqueue_script( 'codemirror', YIT_CORE_PLUGIN_URL . '/assets/js/codemirror/codemirror.js', array( 'jquery' ), false, true );
				wp_enqueue_script( 'codemirror-javascript', YIT_CORE_PLUGIN_URL . '/assets/js/codemirror/javascript.js', array( 'jquery' ), false, true );
				wp_enqueue_style( 'codemirror', YIT_CORE_PLUGIN_URL . '/assets/css/codemirror/codemirror.css' );

				wp_localize_script( 'yith-infs-admin', 'yith_infs_admin', array(
					'ajaxurl'      => admin_url( 'admin-ajax.php', 'relative' ),
					'block_loader' => apply_filters( 'yith_infs_block_loader_admin', YITH_INFS_ASSETS_URL . '/images/block-loader.gif' ),
					'error_msg'    => apply_filters( 'yith_infs_error_msg_admin', __( 'Please insert a name for the section', 'yith-infinite-scrolling' ) ),
					'del_msg'      => apply_filters( 'yith_infs_delete_msg_admin', __( 'Do you really want to delete this section?', 'yith-infinite-scrolling' ) ),
					'loader'       => YITH_INFS_ASSETS_URL . '/images/loader.gif',
				) );
			}
		}

		/**
		 * Register plugins for activation tab
		 *
		 * @since 2.0.0
		 * @return void
		 */
		public function register_plugin_for_activation() {
			if ( function_exists( 'YIT_Plugin_Licence' ) ) {
				YIT_Plugin_Licence()->register( YITH_INFS_INIT, YITH_INFS_SECRET_KEY, YITH_INFS_SLUG );
			}

		}

		/**
		 * Register plugins for update tab
		 *
		 * @since 2.0.0
		 * @return void
		 */
		public function register_plugin_for_updates() {
			if ( ! class_exists( 'YIT_Plugin_Licence' ) ) {
				require_once YITH_INFS_DIR . 'plugin-fw/lib/yit-upgrade.php';
			}

			YIT_Upgrade()->register( YITH_INFS_SLUG, YITH_INFS_INIT );
		}

		/**
		 * Add new infinite scroll options section
		 *
		 * @since  1.0.0
		 * @access public
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 */
		public function yith_infinite_scroll_section_ajax() {

			if ( ! isset( $_REQUEST['section'] ) ) {
				die();
			}

			$key    = strip_tags( $_REQUEST['section'] );
			$id     = $_REQUEST['id'];
			$name   = $_REQUEST['name'];
			$fields = $this->get_section_fields();

			include YITH_INFS_TEMPLATE_PATH . '/admin/options-section-stamp.php';

			die();
		}

		/**
		 * Remove infinite scroll options section
		 *
		 * @since  1.0.0
		 * @access public
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 */
		public function yith_infinite_scroll_section_remove_ajax() {

			if ( ! isset( $_REQUEST['section'] ) ) {
				die();
			}

			$options = get_option( self::$_plugin_options );
			$section = $_REQUEST['section'];

			unset( $options['yith-infs-section'][ $section ] );

			update_option( self::$_plugin_options, $options );

			die();
		}
	}
}
/**
 * Unique access to instance of YITH_WCQV_Admin_Premium class
 *
 * @since 1.0.0
 * @return \YITH_INFS_Admin_Premium
 */
function YITH_INFS_Admin_Premium() {
	return YITH_INFS_Admin_Premium::get_instance();
}