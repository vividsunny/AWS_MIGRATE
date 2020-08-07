<?php
!defined( 'YITH_WCBK' ) && exit; // Exit if accessed directly

if ( !class_exists( 'YITH_WCBK' ) ) {
    /**
     * Class YITH_WCBK
     * Main Class
     *
     * @author Leanza Francesco <leanzafrancesco@gmail.com>
     */
    class YITH_WCBK {

        /** @var YITH_WCBK */
        private static $_instance;

        /** @var string Plugin version */
        public $version = YITH_WCBK_VERSION;

        /** @var YITH_WCBK_Admin */
        public $admin;

        /** @var YITH_WCBK_Frontend */
        public $frontend;

        /** @var YITH_WCBK_Orders */
        public $orders;

        /** @var YITH_WCBK_Person_Type_Helper */
        public $person_type_helper;

        /** @var YITH_WCBK_Extra_Cost_Helper */
        public $extra_cost_helper;

        /** @var YITH_WCBK_Service_Helper */
        public $service_helper;

        /** @var YITH_WCBK_Booking_Helper */
        public $booking_helper;

        /** @var YITH_WCBK_Search_Form_Helper */
        public $search_form_helper;

        /** @var YITH_WCBK_Notes */
        public $notes;

        /** @var YITH_WCBK_Notifier */
        public $notifier;

        /** @var YITH_WCBK_Settings */
        public $settings;

        /** @var YITH_WCBK_Maps */
        public $maps;

        /** @var YITH_WCBK_Exporter */
        public $exporter;

        /** @var YITH_WCBK_Endpoints */
        public $endpoints;

        /** @var YITH_WCBK_Integrations */
        public $integrations;

        /** @var YITH_WCBK_Language */
        public $language;

        /** @var YITH_WCBK_WP_Compatibility */
        public $wp;

        /** @var YITH_WCBK_Google_Calendar_Sync */
        public $google_calendar_sync;

        /** @var YITH_WCBK_Booking_Externals */
        public $externals;

        /** @var YITH_WCBK_Background_Processes */
        public $background_processes;

        /** @var YITH_WCBK_Theme */
        public $theme;

        /**
         * Singleton implementation
         *
         * @return YITH_WCBK
         */
        public static function get_instance() {
            return !is_null( self::$_instance ) ? self::$_instance : self::$_instance = new self();
        }

        /**
         * YITH_WCBK constructor.
         */
        private function __construct() {
            // Load Plugin Framework
            add_action( 'plugins_loaded', array( $this, 'plugin_fw_loader' ), 15 );
            add_action( 'plugins_loaded', array( $this, 'load_privacy' ), 20 );

            $this->background_processes = YITH_WCBK_Background_Processes::get_instance();

            YITH_WCBK_Post_Types::init();
            YITH_WCBK_Shortcodes::init();
            YITH_WCBK_Common_Assets::get_instance();

            // Class admin
            if ( is_admin() && !defined( 'DOING_AJAX' ) || ( defined( 'DOING_AJAX' ) && DOING_AJAX && ( !isset( $_REQUEST[ 'context' ] ) || ( isset( $_REQUEST[ 'context' ] ) && $_REQUEST[ 'context' ] !== 'frontend' ) ) ) ) {
                $this->admin = YITH_WCBK_Admin();
            } else {
                $this->frontend = YITH_WCBK_Frontend();
            }

            YITH_WCBK_AJAX::get_instance();

            $this->person_type_helper = YITH_WCBK_Person_Type_Helper::get_instance();
            $this->extra_cost_helper  = YITH_WCBK_Extra_Cost_Helper::get_instance();
            $this->service_helper     = YITH_WCBK_Service_Helper::get_instance();
            $this->booking_helper     = YITH_WCBK_Booking_Helper::get_instance();
            $this->search_form_helper = YITH_WCBK_Search_Form_Helper::get_instance();

            $this->orders               = YITH_WCBK_Orders::get_instance();
            $this->notes                = YITH_WCBK_Notes::get_instance();
            $this->notifier             = YITH_WCBK_Notifier::get_instance();
            $this->settings             = YITH_WCBK_Settings::get_instance();
            $this->maps                 = YITH_WCBK_Maps::get_instance();
            $this->exporter             = YITH_WCBK_Exporter::get_instance();
            $this->endpoints            = YITH_WCBK_Endpoints::get_instance();
            $this->language             = YITH_WCBK_Language::get_instance();
            $this->integrations         = YITH_WCBK_Integrations::get_instance();
            $this->wp                   = YITH_WCBK_WP_Compatibility::get_instance();
            $this->google_calendar_sync = YITH_WCBK_Google_Calendar_Sync::get_instance();
            $this->externals            = YITH_WCBK_Booking_Externals::get_instance();

            $this->theme = YITH_WCBK_Theme::get_instance();

            add_action( 'widgets_init', array( $this, 'register_widgets' ) );
            add_filter( 'user_has_cap', array( $this, 'user_has_capability' ), 10, 3 );

            YITH_WCBK_Cron::get_instance();
        }

        /**
         * Checks if a user has a certain capability.
         *
         * @param array $allcaps All capabilities.
         * @param array $caps    Capabilities.
         * @param array $args    Arguments.
         * @since 2.1.4
         * @return array The filtered array of all capabilities.
         */
        public function user_has_capability( $allcaps, $caps, $args ) {
            if ( isset( $caps[ 0 ] ) ) {
                switch ( $caps[ 0 ] ) {
                    case 'view_booking':
                        $user_id = isset( $args[ 1 ] ) ? intval( $args[ 1 ] ) : false;
                        $booking = isset( $args[ 2 ] ) ? yith_get_booking( $args[ 2 ] ) : false;

                        $can = $user_id && $booking && $user_id === absint($booking->user_id);
                        $can = apply_filters( 'yith_wcbk_user_can_view_booking', $can, $user_id, $booking );

                        if ( $can ) {
                            $allcaps[ 'view_booking' ] = true;
                        }
                        break;
                }
            }
            return $allcaps;
        }

        public function load_privacy() {
            require_once( 'class.yith-wcbk-privacy.php' );
        }


        /**
         * Load Plugin Framework
         *
         * @access public
         */
        public function plugin_fw_loader() {
            if ( !defined( 'YIT_CORE_PLUGIN' ) ) {
                global $plugin_fw_data;
                if ( !empty( $plugin_fw_data ) ) {
                    $plugin_fw_file = array_shift( $plugin_fw_data );
                    require_once( $plugin_fw_file );
                }
            }
        }


        /**
         * register Widgets
         *
         * @access public
         */
        public function register_widgets() {
            register_widget( 'YITH_WCBK_Search_Form_Widget' );

            if ( 'widget' === get_option( 'yith-wcbk-booking-form-position', 'default' ) ) {
                register_widget( 'YITH_WCBK_Product_Form_Widget' );
            }
        }

    }
}

/**
 * Unique access to instance of YITH_WCBK class
 *
 * @return YITH_WCBK
 * @since 1.0.0
 */
function YITH_WCBK() {
    return YITH_WCBK::get_instance();
}

