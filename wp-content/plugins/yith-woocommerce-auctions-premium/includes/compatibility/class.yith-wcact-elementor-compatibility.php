<?php
/**
 * This file belongs to the YIT Plugin Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */


if ( !defined( 'ABSPATH' ) || !defined( 'YITH_WCACT_VERSION' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Implements the YITH_WCACT_Elementor_Compatibility class.
 *
 * @class   YITH_WCACT_Elementor_Compatibility
 * @package YITH
 * @since   1.3.4
 * @author  YITH
 */
if ( !class_exists( 'YITH_WCACT_Elementor_Compatibility' ) ) {

    /**
     * Class YITH_WCACT_Elementor_Compatibility
     */
    class YITH_WCACT_Elementor_Compatibility{
        /**
         * Single instance of the class
         *
         * @var YITH_WCACT_Elementor_Compatibility
         */

        protected static $instance;

        /**
         * Returns single instance of the class
         *
         * @return YITH_WCACT_Elementor_Compatibility
         */
        public static function get_instance() {
            if ( is_null( self::$instance ) ) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        /**
         * YITH_WCACT_Elementor_Compatibility constructor.
         */
        public function __construct() {
            if ( did_action( 'elementor/loaded' ) ) {
                add_action( 'elementor/elements/categories_registered', array( $this, 'add_elementor_yith_widget_category' ) );
                add_action( 'elementor/widgets/widgets_registered', array( $this, 'elementor_init_widgets' )   );

            }
        }

        /**
         * @param $elements_manager
         */
        public function add_elementor_yith_widget_category( $elements_manager ) {
            $elements_manager->add_category(
                'yith',
                array(
                    'title' => 'YITH',
                    'icon' => 'fa fa-plug',
                )
            );

        }

        /**
         * @throws Exception
         */
        public function elementor_init_widgets(  ) {
            // Include Widget files

            require_once( YITH_WCACT_PATH . 'includes/compatibility/elementor/class.yith-wcact-auction-form-elementor-widget.php');
            require_once( YITH_WCACT_PATH . 'includes/compatibility/elementor/class.yith-wcact-list-bids-elementor-widget.php');
            require_once( YITH_WCACT_PATH . 'includes/compatibility/elementor/class.yith-wcact-current-auctions-elementor-widget.php');
            require_once( YITH_WCACT_PATH . 'includes/compatibility/elementor/class.yith-wcact-end-auctions-elementor-widget.php');
            require_once( YITH_WCACT_PATH . 'includes/compatibility/elementor/class.yith-wcact-not-started-auctions-elementor-widget.php');





            // Register widget
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \YITH_WCACT_Auction_Form_Elementor_Widget());
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \YITH_WCACT_List_Bids_Elementor_Widget());
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \YITH_WCACT_Current_Auctions_Elementor_Widget());
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \YITH_WCACT_End_Auctions_Elementor_Widget());
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \YITH_WCACT_Not_Started_Auctions_Elementor_Widget());


        }
    }

}

/**
 * Unique access to instance of YITH_Request_Quote_Elementor class
 *
 * @return YITH_Request_Quote_Elementor
 */
function YITH_WCACT_Elementor_Compatibility() {
    return YITH_WCACT_Elementor_Compatibility::get_instance();
}

YITH_WCACT_Elementor_Compatibility();
