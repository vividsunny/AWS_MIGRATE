<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit() ;
}

if ( ! class_exists( 'HRW_Background_Process' ) ) {

    if ( ! class_exists( 'WP_Async_Request' ) )
        include_once HRW_PLUGIN_PATH . '/inc/background-updater/wp-async-request.php' ;

    if ( ! class_exists( 'WP_Background_Process' ) )
        include_once HRW_PLUGIN_PATH . '/inc/background-updater/wp-background-process.php' ;

    /**
     * HRW_Background_Process Class.
     */
    class HRW_Background_Process {

        /**
         * Background Process
         * */
        protected $background_process = array() ;

        /**
         * Background Process count
         * */
        protected $count_identifier = 'hrw_background_process_count' ;

        /**
         * The single instance of the class.
         * */
        protected static $_instance = null ;

        /**
         * Load HRW_Background_Process Class in Single Instance
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self() ;
            }

            return self::$_instance ;
        }

        /**
         * Initialize background process.
         */
        public function __construct() {
            $this->init() ;
        }

        /**
         * Initialize background process.
         */
        protected function init() {

            $background_process = array(
                'wallet'           => 'HRW_Wallet_Background_Process' ,
                'transaction-logs' => 'HRW_Transaction_Logs_Background_Process' ,
                'settings'         => 'HRW_Settings_Background_Process' ,
                    ) ;

            if ( hrw_check_is_array( $background_process ) ) {
                foreach ( $background_process as $key => $classname ) {
                    if ( ! class_exists( $classname ) )
                        include_once ( HRW_PLUGIN_PATH . '/inc/background-updater/hrw-' . $key . '-background-process.php' ) ;

                    $this->background_process[ $key ] = new $classname() ;
                }
            }

            //Custom Dashboard menu
            add_action( 'admin_menu' , array( $this , 'custom_dashboard_page' ) ) ;
            //Remove dashboard navigation menu
            add_action( 'admin_head' , array( $this , 'remove_dashboard_navigation_menu' ) ) ;
            //upgrade progress count
            add_action( 'wp_ajax_hrw_background_process_count' , array( $this , 'background_process_count' ) ) ;
        }

        /**
         * Trigger Background Process
         * */
        public function trigger() {
            if ( $this->is_process_running() )
                return ;

            $this->delete_progress_count() ;

            $background_process = $this->get_background_process() ;
            $background_process[ 'wallet' ]->trigger() ;

            wp_safe_redirect( add_query_arg( array( 'page' => 'hrw_upgrade' ) , HRW_ADMIN_URL ) ) ;
        }

        /**
         * Is Process Running
         * */
        protected function is_process_running() {
            foreach ( $this->get_background_process() as $background_process ) {
                if ( $background_process->is_process_running() )
                    return true ;
            }

            return false ;
        }

        /**
         * Background Process
         * */
        public function get_background_process() {
            return $this->background_process ;
        }

        /**
         * Get Background Process By ID
         * */
        public function get_background_process_by_id( $id ) {
            return isset( $this->background_process[ $id ] ) ? $this->background_process[ $id ] : false ;
        }

        /**
         * Background process count 
         * */
        public function background_process_count() {
            check_ajax_referer( 'hrw-upgrade-nonce' , 'hrw_security' ) ;

            try {
                if ( ! isset( $_POST ) )
                    throw new exception( esc_html__( 'Invalid Request' , HRW_LOCALE ) ) ;

                $percentage = $this->get_progress_count() ;
                $response   = array(
                    'percentage' => $percentage ,
                    'completed'  => 'no'
                        ) ;

                if ( $percentage >= 100 ) {
                    $response[ 'completed' ]    = 'yes' ;
                    $response[ 'msg' ]          = sprintf( esc_html__( 'Upgrade to v%s completed successfully' , HRW_LOCALE ) , HRW_VERSION ) ;
                    $response[ 'redirect_url' ] = hrw_get_settings_page_url() ;
                }

                wp_send_json_success( $response ) ;
            } catch ( Exception $ex ) {
                wp_send_json_error( array( 'error' => $ex->getMessage() ) ) ;
            }
        }

        /**
         * Delete Progress count
         * */
        public function delete_progress_count() {
            delete_site_option( $this->count_identifier ) ;
        }

        /**
         * Get Progress count
         * */
        public function get_progress_count() {
            return ( int ) get_site_option( $this->count_identifier , 0 ) ;
        }

        /**
         * Update Progress count
         * */
        public function update_progress_count( $progress = 0 ) {
            update_site_option( $this->count_identifier , $progress ) ;
        }

        /*
         * Add Custom Dashborad Page
         */

        public function custom_dashboard_page() {
            add_dashboard_page(
                    esc_html__( 'Upgrade' , HRW_LOCALE ) , esc_html__( 'Upgrade' , HRW_LOCALE ) , 'read' , 'hrw_upgrade' , array( $this , 'upgrade_content' )
            ) ;
        }

        /*
         * Remove dashboard navigation menu
         */

        function remove_dashboard_navigation_menu() {
            remove_submenu_page( 'index.php' , 'hrw_upgrade' ) ;
        }

        /*
         * Display Upgrade content
         */

        public function upgrade_content() {
            $percent = $this->get_progress_count() ;
            ?>
            <div class="hrw_prograss_bar_wrapper">
                <h1><?php esc_html_e( 'Wallet' , HRW_LOCALE ) ; ?></h1>
                <div id="hrw_prograss_bar_label">
                    <h2><?php echo sprintf( esc_html__( 'Upgrade to v%s is under progress...' , HRW_LOCALE ) , HRW_VERSION ) ; ?></h2>
                </div>
                <div class="hrw_prograss_bar_outer">
                    <div class="hrw_prograss_bar_inner" style="width: <?php echo esc_attr( $percent ) ; ?>%">

                    </div>
                </div>
                <div id="hrw_prograss_bar_status">
                    <span id="hrw_prograss_bar_current_status"><?php echo esc_html( $percent ) ; ?></span>
                    <?php esc_html_e( '% Completed' , HRW_LOCALE ) ; ?>
                </div>
            </div>
            <?php
        }

    }

}