<?php

/**
 * Abstract Modules Class
 */
if ( ! defined ( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists ( 'HRW_Modules' ) ) {

    /**
     * HRW_Modules Class
     */
    abstract class HRW_Modules {
        /*
         * ID
         */

        protected $id ;

        /*
         * Section
         */
        protected $section = 'general' ;

        /*
         * Show settings
         */
        protected $show_settings = true ;

        /*
         * Title
         */
        protected $title ;
        /*
         * Data
         */
        protected $data = array ( 'enabled' => 'no' ) ;

        /*
         * Plugin slug
         */
        protected $plugin_slug = 'hrw' ;

        /*
         * Options
         */
        protected $options = array () ;

        /*
         * Suffix
         */
        protected $suffix ;

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->prepare_options () ;
            $this->process_actions () ;
        }

        /*
         * Get id
         */

        public function get_id() {
            return $this->id ;
        }

        /*
         * Get section
         */

        public function get_section() {
            return $this->section ;
        }

        /*
         * Get title
         */

        public function get_title() {
            return $this->title ;
        }

        /*
         * Actions
         */

        public function process_actions() {
            if ( ! $this->is_enabled () )
                return ;

            $this->actions () ;

            $this->suffix = defined ( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' ;

            if ( is_admin () ) {
                $this->admin_action () ;

                //add action for external js files in backend
                add_action ( 'admin_enqueue_scripts' , array ( $this , 'admin_enqueue_scripts' ) ) ;
            }

            if ( ! is_admin () || defined ( 'DOING_AJAX' ) ) {
                $this->frontend_action () ;

                //add action for external js files in backend
                add_action ( 'wp_enqueue_scripts' , array ( $this , 'frontend_enqueue_scripts' ) ) ;
            }
        }

        /*
         * warning message
         */

        public function get_warning_message() {

            return '' ;
        }

        /*
         * Admin Actions
         */

        public function admin_action() {
            
        }

        /*
         * Actions
         */

        public function actions() {
            
        }

        /*
         * Frontend Actions
         */

        public function frontend_action() {
            
        }

        /*
         * Admin enqueue scripts
         */

        public function admin_enqueue_scripts() {
            $this->admin_external_js_files () ;
            $this->admin_external_css_files () ;
        }

        /*
         * Frontend enqueue scripts
         */

        public function frontend_enqueue_scripts() {

            $this->frontend_external_js_files () ;
            $this->frontend_external_css_files () ;
        }

        /*
         * Frontend enqueue js files
         */

        public function frontend_external_js_files() {
            
        }

        /*
         * Frontend enqueue css files
         */

        public function frontend_external_css_files() {
            
        }

        /*
         * Admin enqueue js files
         */

        public function admin_external_js_files() {
            
        }

        /*
         * Admin enqueue css files
         */

        public function admin_external_css_files() {
            
        }

        /*
         * Save
         */

        public function save() {
            
        }

        /*
         * After save
         */

        public function after_save() {
            
        }

        /*
         * Before save
         */

        public function before_save() {
            
        }

        /*
         * Get settings link
         */

        public function settings_link() {
            if ( ! $this->show_settings )
                return false ;

            return hrw_get_settings_page_url ( array ( 'tab' => 'modules' , 'section' => $this->get_section () , 'subsection' => $this->id ) ) ;
        }

        /*
         * is enabled
         */

        public function is_enabled() {

            return $this->is_plugin_enabled () && 'yes' === $this->enabled ;
        }

        /*
         * is plugin enabled
         */

        public function is_plugin_enabled() {

            return true ;
        }

        /*
         * Get settings options array
         */

        public function settings_options_array() {
            array () ;
        }

        /*
         * Get data
         */

        public function get_data() {
            return $this->data ;
        }

        /**
         * Output the settings buttons.
         */
        public function output_buttons() {
            global $current_section , $current_action ;

            if ( $current_section && ! $current_action ) {
                HRW_Settings::output_buttons () ;
            }
        }

        /*
         * Update Option
         */

        public function update_option( $key , $value ) {
            $field_key = $this->get_field_key ( $key ) ;

            return update_option ( $field_key , $value ) ;
        }

        /*
         * Prepare Options
         */

        public function prepare_options() {
            $default_data = $this->data ;

            foreach ( $default_data as $key => $value ) {

                $this->$key = $this->get_option ( $key , $value ) ;
            }
        }

        /*
         * Get Option
         */

        public function get_option( $key , $value = false ) {
            $field_key = $this->get_field_key ( $key ) ;

            return get_option ( $field_key , $value ) ;
        }

        /*
         * Get field key
         */

        public function get_field_key( $key ) {
            return sanitize_key ( $this->plugin_slug . '_' . $this->id . '_' . $key ) ;
        }

        /*
         * Extra Fields
         */

        public function extra_fields() {
            
        }

    }

}
