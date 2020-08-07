<?php

/**
 * Report
 */
if ( ! defined ( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists ( 'HRW_Wallet_Account_Statement_Module' ) ) {

    /**
     * Class HRW_Wallet_Account_Statement_Module
     */
    class HRW_Wallet_Account_Statement_Module extends HRW_Modules {
        /*
         * Data
         */

        protected $data = array (
            'enabled'                 => 'no' ,
            'enable_wallet_statement' => 'no' ,
            'statement_method'        => '1' ,
            'yearly_notification'     => 'no' ,
            'year_start_date'         => '' ,
            'file_name_method'        => '1' ,
            'default_format'          => 'Y-m-d @ H.i.s' ,
            'char_count'              => 10 ,
            'file_prefix'             => '' ,
            'file_suffix'             => '' ,
            'sequence_number'         => 1 ,
            'name_label_heading'      => 'Wallet Statement Name' ,
            'date_heading'            => 'Date' ,
            'logo_image_url'          => '' ,
            'logo_max_percent'        => '' ,
            'admin_section_heading'   => 'Admin Details' ,
            'admin_section'           => '' ,
                ) ;

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'wallet_account_statement' ;
            $this->title = esc_html__ ( 'Wallet Account Statement' , HRW_LOCALE ) ;

            parent::__construct () ;
        }

        /*
         * is plugin enabled
         */

        public function is_plugin_enabled() {

            return hrw_is_premium () ;
        }

        /*
         * warning message
         */

        public function get_warning_message() {

            $message = sprintf ( esc_html__ ( 'This feature is available in %s' , HRW_LOCALE ) , '<a href="https://hoicker.com/product/wallet" target="_blank">' . esc_html__ ( "Wallet Premium Version" , HRW_LOCALE ) . '</a>' ) ;

            return '<i class="fa fa-info-circle"></i> ' . $message ;
        }

        /*
         * Get settings options array
         */

        public function settings_options_array() {
            $section_fields   = array () ;
            //General section start
            $section_fields[] = array (
                'type'  => 'title' ,
                'title' => esc_html__ ( 'General Settings' , HRW_LOCALE ) ,
                'id'    => 'wallet_general_settings' ,
                    ) ;
            $section_fields[] = array (
                'title'   => esc_html__ ( 'Statement Frequency' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key ( 'statement_method' ) ,
                'type'    => 'select' ,
                'default' => '1' ,
                'options' => array (
                    '1' => esc_html__ ( 'Weekly' , HRW_LOCALE ) ,
                    '2' => esc_html__ ( 'Monthly' , HRW_LOCALE ) ,
                    '3' => esc_html__ ( 'Yearly' , HRW_LOCALE )
                ) ,
                'desc'    => esc_html__ ( 'This option controls how frequently the Wallet statements has to be mailed to the users' , HRW_LOCALE ) ,
                    ) ;
            $section_fields[] = array (
                'title'   => esc_html__ ( 'Send Yearly Notifications' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key ( 'yearly_notification' ) ,
                'type'    => 'checkbox' ,
                'default' => 'no' ,
                'desc'    => esc_html__ ( 'Enable this option if you want to send yearly wallet statements to your users apart from the weekly / monthly statements' , HRW_LOCALE ) ,
                    ) ;
            $section_fields[] = array (
                'title'       => esc_html__ ( 'Yearly Wallet Statement Notification Date' , HRW_LOCALE ) ,
                'id'          => $this->get_field_key ( 'year_start_date' ) ,
                'type'        => 'datepicker' ,
                'placeholder' => HRW_Date_Time::get_wp_date_format () ,
                'desc'        => esc_html__ ( 'Yearly Wallet Statements will be sent on the selected date' , HRW_LOCALE ) ,
                    ) ;
            $section_fields[] = array (
                'type' => 'sectionend' ,
                'id'   => 'wallet_general_settings' ,
                    ) ;
            //General section End
            //Wallet Statement section start
            $section_fields[] = array (
                'type'  => 'title' ,
                'title' => esc_html__ ( 'Wallet Statement Settings' , HRW_LOCALE ) ,
                'id'    => 'wallet_statement_settings' ,
                    ) ;
            $section_fields[] = array (
                'title'   => esc_html__ ( 'File Name Type' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key ( 'file_name_method' ) ,
                'type'    => 'select' ,
                'default' => '1' ,
                'options' => array (
                    '1' => esc_html__ ( 'Default' , HRW_LOCALE ) ,
                    '2' => esc_html__ ( 'Advanced' , HRW_LOCALE ) ,
                ) ,
                    ) ;
            $section_fields[] = array (
                'title'   => esc_html__ ( 'Format' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key ( 'default_format' ) ,
                'type'    => 'text' ,
                'default' => 'Y-m-d @ H.i.s' ,
                'desc'    => esc_html__ ( 'Y – Year , m – Month , d – Day , h – Hours , i – Minutes , s – Seconds' , HRW_LOCALE ) ,
                    ) ;
            $section_fields[] = array (
                'title'             => esc_html__ ( 'Statement Name Character Count' , HRW_LOCALE ) ,
                'desc'              => esc_html__ ( 'character count excluding prefix and suffix' , HRW_LOCALE ) ,
                'id'                => $this->get_field_key ( 'char_count' ) ,
                'class'             => $this->get_field_key ( 'advanced_fields' ) ,
                'type'              => 'number' ,
                'default'           => 10 ,
                'custom_attributes' => array ( 'min' => '1' ) ,
                'desc'              => esc_html__ ( 'Character count excluding prefix and sufix' , HRW_LOCALE )
                    ) ;
            $section_fields[] = array (
                'title'   => esc_html__ ( 'Statement File Name Prefix' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key ( 'file_prefix' ) ,
                'class'   => $this->get_field_key ( 'advanced_fields' ) ,
                'type'    => 'text' ,
                'default' => '' ,
                    ) ;
            $section_fields[] = array (
                'title'   => esc_html__ ( 'Statement File Name Suffix' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key ( 'file_suffix' ) ,
                'class'   => $this->get_field_key ( 'advanced_fields' ) ,
                'type'    => 'text' ,
                'default' => '' ,
                    ) ;
            $section_fields[] = array (
                'title'             => esc_html__ ( 'Wallet Statement Sequence Starting Number' , HRW_LOCALE ) ,
                'desc'              => esc_html__ ( 'If prefix is given, then this number will come after prefix' , HRW_LOCALE ) ,
                'id'                => $this->get_field_key ( 'sequence_number' ) ,
                'class'             => $this->get_field_key ( 'advanced_fields' ) ,
                'type'              => 'number' ,
                'default'           => 1 ,
                'custom_attributes' => array ( 'min' => '0' ) ,
                'desc'              => esc_html__ ( 'If prefix is given, then this number will come after prefix' , HRW_LOCALE )
                    ) ;
            $section_fields[] = array (
                'title'   => esc_html__ ( 'Wallet Statement Number Label' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key ( 'name_label_heading' ) ,
                'type'    => 'text' ,
                'default' => 'Wallet Statement Number' ,
                    ) ;
            $section_fields[] = array (
                'title'   => esc_html__ ( 'Statement Date Label' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key ( 'date_heading' ) ,
                'type'    => 'text' ,
                'default' => 'Date' ,
                    ) ;
            $section_fields[] = array (
                'type' => 'sectionend' ,
                'id'   => 'wallet_statement_settings' ,
                    ) ;
            //Wallet Statement section end
            //Wallet Admin section start
            $section_fields[] = array (
                'type'  => 'title' ,
                'title' => esc_html__ ( 'Admin Section Customization' , HRW_LOCALE ) ,
                'id'    => 'wallet_admin_section_customization' ,
                    ) ;
            $section_fields[] = array (
                'title' => esc_html__ ( 'Logo' , HRW_LOCALE ) ,
                'id'    => $this->get_field_key ( 'logo_image_url' ) ,
                'type'  => 'file_upload' ,
                    ) ;
            $section_fields[] = array (
                'title'             => esc_html__ ( 'Logo Maximum Width(in %)' , HRW_LOCALE ) ,
                'id'                => $this->get_field_key ( 'logo_max_percent' ) ,
                'type'              => 'number' ,
                'custom_attributes' => array ( 'min' => '0' , 'max' => '100' )
                    ) ;
            $section_fields[] = array (
                'title'   => esc_html__ ( 'Admin Details Section Heading' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key ( 'admin_section_heading' ) ,
                'type'    => 'text' ,
                'default' => 'Admin Details' ,
                    ) ;
            $section_fields[] = array (
                'title'   => esc_html__ ( 'Admin Details' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key ( 'admin_section' ) ,
                'type'    => 'textarea' ,
                'default' => '' ,
                    ) ;
            $section_fields[] = array (
                'type' => 'sectionend' ,
                'id'   => 'wallet_admin_section_customization' ,
                    ) ;
            //Wallet Admin section end
            return $section_fields ;
        }

        /*
         * Action
         */

        public function actions() {
            //cron initialization
            add_action ( 'init' , array ( $this , 'maybe_initialize_cron' ) ) ;
            // month or week cron trigger
            add_action ( 'hrw_wallet_account_statement_weekly_monthly' , array ( $this , 'send_week_month_email' ) ) ;
            // year cron trigger
            add_action ( 'hrw_wallet_account_statement_yearly' , array ( $this , 'send_year_email' ) ) ;
            // cron schedule 
            add_filter ( 'cron_schedules' , array ( $this , 'schedule_cron' ) ) ;
            // frontend dashboard menu header
            add_filter ( 'hrw_frontend_dashboard_menu' , array ( $this , 'add_dashboard_menu' ) , 10 , 1 ) ;
            // frontend dashboard menu content
            add_action ( 'hrw_frontend_dashboard_menu_content_account_statement' , array ( $this , 'render_account_statement' ) ) ;
        }

        /*
         * Admin Action
         */

        public function admin_action() {
            // customized date statements.
            add_action ( 'wp_ajax_hrw_wallet_statement_email' , array ( $this , 'wallet_statement_email' ) ) ;
        }

        /**
         * Enqueue external CSS files
         */
        public function frontend_external_css_files() {
            wp_enqueue_style ( 'jquery-ui' , HRW_PLUGIN_URL . '/assets/css/jquery-ui' . $this->suffix . '.css' , array () , HRW_VERSION ) ;
        }

        /**
         * Enqueue external JS files
         */
        public function frontend_external_js_files() {
            //Register Js File
            wp_enqueue_script ( 'hrw_account_statement' , HRW_PLUGIN_URL . '/premium/assets/js/frontend/account-statement.js' , array ( 'jquery' ) , HRW_VERSION ) ;

            //localize script
            wp_localize_script ( 'hrw_account_statement' , 'hrw_account_statement_obj' , array (
                'admin_url'              => HRW_ADMIN_AJAX_URL ,
                'wallet_statement_nonce' => wp_create_nonce ( 'hrw-wallet-statement' ) ,
                'empty_from_to_interval' => esc_html__ ( 'please enter intervals' ) ,
            ) ) ;
        }

        /**
         * Enqueue external JS files
         */
        public function admin_external_js_files() {
            wp_enqueue_media () ;
            //Enqueue Js File
            wp_enqueue_script ( 'hrw_account_statement' , HRW_PLUGIN_URL . '/premium/assets/js/admin/account-statement.js' , array ( 'jquery' ) , HRW_VERSION ) ;
        }

        /**
         * Save settings.
         */
        public function save() {
            $statement_method = hrw_sanitize_text_field ( $_POST[ $this->get_field_key ( 'statement_method' ) ] ) ;
            $year_start_date  = hrw_sanitize_text_field ( $_POST[ $this->get_field_key ( 'year_start_date' ) ] ) ;

            if ( $statement_method != $this->get_option ( 'statement_method' ) ) {
                $this->maybe_initialize_cron ( 'week_month' ) ;
                $this->maybe_initialize_cron ( 'year' ) ;
            }

            if ( ! isset ( $year_start_date ) || $year_start_date != $this->get_option ( 'year_start_date' ) ) {
                $this->maybe_initialize_cron ( 'year' ) ;
            }
        }

        /*
         * Schedule Interval
         */

        public function schedule_interval( $year = false ) {
            $interval = array () ;

            if ( $this->statement_method == '1' ) {
                $interval[ 'interval' ] = (date ( 'w' , time () ) != '6') ? (strtotime ( 'next Sunday' , time () )) - time () : (strtotime ( 'tomorrow' , time () )) - time () ;
                $interval[ 'label' ]    = esc_html__ ( 'Weekly' , HRW_LOCALE ) ;
            }

            if ( $this->statement_method == '2' ) {
                $interval[ 'interval' ] = (strtotime ( date ( 'Y-m-t' ) ) + 86399) - time () ;
                $interval[ 'label' ]    = esc_html__ ( 'Monthly' , HRW_LOCALE ) ;
            }

            if ( $this->statement_method == '3' || $year ) {
                $interval[ 'interval' ] = (strtotime ( $this->year_start_date . '+1 years' ) - 1 ) - time () ;
                $interval[ 'label' ]    = esc_html__ ( 'Yearly' , HRW_LOCALE ) ;
            }

            return $interval ;
        }

        /*
         * Cron Schedule
         */

        public function schedule_cron( $schedules ) {
            if ( $this->statement_method == '1' || $this->statement_method == '2' ) {
                $interval                                                            = $this->schedule_interval () ;
                $schedules[ 'hrw_wallet_account_statement_weekly_monthly_interval' ] = array (
                    'interval' => $interval[ 'interval' ] ,
                    'display'  => $interval[ 'label' ] ,
                        ) ;
            }

            if ( $this->yearly_notification == 'yes' || $this->statement_method == '3' ) {
                $interval                                                    = $this->schedule_interval ( true ) ;
                $schedules[ 'hrw_wallet_account_statement_yearly_interval' ] = array (
                    'interval' => $interval[ 'interval' ] ,
                    'display'  => $interval[ 'label' ] ,
                        ) ;
            }

            return $schedules ;
        }

        /*
         * Initialize Cron
         */

        public function maybe_initialize_cron( $clear_event = false ) {
            if ( $clear_event == 'week_month' ) {
                wp_clear_scheduled_hook ( 'hrw_wallet_account_statement_weekly_monthly' ) ;
            }

            if ( $clear_event == 'year' ) {
                wp_clear_scheduled_hook ( 'hrw_wallet_account_statement_yearly' ) ;
            }

            if ( ($this->statement_method == '1' || $this->statement_method == '2') && ! wp_next_scheduled ( 'hrw_wallet_account_statement_weekly_monthly' ) ) {
                wp_schedule_event ( time () , 'hrw_wallet_account_statement_weekly_monthly_interval' , 'hrw_wallet_account_statement_weekly_monthly' ) ;
            }

            if ( ($this->yearly_notification == 'yes' || $this->statement_method == '3') && ! wp_next_scheduled ( 'hrw_wallet_account_statement_yearly' ) ) {
                wp_schedule_event ( time () , 'hrw_wallet_account_statement_yearly_interval' , 'hrw_wallet_account_statement_yearly' ) ;
            }
        }

        /*
         * Send Week or Month E-mail
         */

        public function send_week_month_email() {
            $args = array () ;
            if ( $this->statement_method == '2' ) {
                $args = array (
                    'from' => date ( "01 M Y" ) ,
                    'to'   => date ( "t M Y" ) ,
                    'name' => esc_html__ ( 'month' , HRW_LOCALE ) ,
                    'type' => 'month'
                        ) ;
            } else {
                if ( (date ( 'w' , time () ) == '6') || (date ( 'w' , time () ) == '0') ) {
                    if ( (date ( 'w' , time () ) == '6' ) ) {
                        $args[ 'from' ] = date ( 'd M Y' , strtotime ( 'last sunday' , time () ) ) ;
                        $args[ 'to' ]   = date ( 'd M Y' , strtotime ( 'midnight' , time () ) ) ;
                    } else {
                        $args[ 'from' ] = date ( 'd M Y' , strtotime ( 'midnight' , time () ) ) ;
                        $args[ 'to' ]   = date ( 'd M Y' , strtotime ( 'next Saturday' , time () ) ) ;
                    }
                } else {
                    $args[ 'from' ] = date ( 'd M Y' , strtotime ( 'last sunday' , time () ) ) ;
                    $args[ 'to' ]   = date ( 'd M Y' , strtotime ( 'next Saturday' , time () ) ) ;
                }

                $args[ 'name' ] = esc_html__ ( 'week' , HRW_LOCALE ) ;
                $args[ 'type' ] = 'week' ;
            }

            $this->send_email ( $args ) ;
        }

        /*
         * Send Year E-mail
         */

        public function send_year_email() {
            $args = array (
                'from' => date ( 'd M Y' , strtotime ( $this->year_start_date ) ) ,
                'to'   => date ( 'd M Y' , (strtotime ( $this->year_start_date . "+1 years" ) - 1 ) ) ,
                'name' => esc_html__ ( 'year' , HRW_LOCALE ) ,
                'type' => 'year'
                    ) ;

            $this->send_email ( $args ) ;
        }

        /*
         * Set Statment File Name
         */

        public function get_statement_file_name() {
            if ( $this->file_name_method == '1' ) {
                $statement_name = 'Wallet Statement-' . date ( $this->default_format , current_time ( 'timestamp' ) ) ;
            } else {

                $args           = array ( 'length'          => $this->char_count ,
                    'prefix'          => $this->file_prefix ,
                    'suffix'          => $this->file_suffix ,
                    'sequence_number' => $this->sequence_number ,
                        ) ;
                $statement_name = hrw_generate_random_codes ( $args ) ;
            }

            return $statement_name ;
        }

        /*
         * Send E-mail
         */

        public function send_email( $wallet_args = array () ) {
            $wallet_ids = array () ;
            $from       = $to         = '' ;

            if ( hrw_check_is_array ( $wallet_args ) ) {
                extract ( $wallet_args ) ;
            }

            $wallet_ids = hrw_check_is_array ( $wallet_ids ) ? $wallet_ids : hrw_get_active_wallet () ;

            foreach ( $wallet_ids as $wallet_id ) {
                // set pdf file name
                $statement_name = $this->get_statement_file_name () ;

                // construct pdf
                $pdf            = new HRWP_Wallet_Statement_PDF ( $wallet_id , $statement_name ) ;
                $statement_name = $pdf->generate_pdf ( $from , $to , $name ) ;

                // Update pdf sequence number
                $this->update_option ( 'sequence_number' , (( int ) $this->sequence_number) + 1 ) ;

                $args = array ( 'wallet_id' => $wallet_id ,
                    'from_date' => $from ,
                    'to_date'   => $to ,
                    'file_path' => $statement_name ,
                        ) ;

                // Mail purpose
                do_action ( 'hrw_customer_' . $type . '_wallet_account_statement_notification' , $args ) ;
            }
        }

        /*
         * Get wallet data based on the time interval
         */

        public function get_wallet_info_by_interval( $args ) {
            global $wpdb ;
            if ( $args[ 'key' ] == 'hrw_total' ) {
                $post_query = new HRW_Query ( $wpdb->posts , 'p' ) ;
                $post_query->select ( 'DISTINCT `pm`.meta_value' )
                        ->leftJoin ( $wpdb->postmeta , 'pm' , '`p`.ID = `pm`.post_id' )
                        ->where ( '`p`.post_parent' , $args[ 'wallet_id' ] )
                        ->where ( '`p`.post_type' , 'hrw_transactions_log' )
                        ->whereIn ( '`p`.post_status' , array ( "hrw_credit" , "hrw_debit" ) )
                        ->whereLt ( '`p`.post_date' , $args[ 'from_date' ] )
                        ->where ( '`pm`.meta_key' , $args[ 'key' ] )
                        ->orderBy ( '`p`.post_date' )
                        ->order ( 'desc' )
                        ->limit ( '1' ) ;

                return $post_query->fetchCol ( 'meta_value' ) ;
            } else {
                $post_query = new HRW_Query ( $wpdb->posts , 'p' ) ;
                $post_query->leftJoin ( $wpdb->postmeta , 'pm' , '`p`.ID = `pm`.post_id' )
                        ->where ( '`p`.post_parent' , $args[ 'wallet_id' ] )
                        ->where ( '`p`.post_type' , 'hrw_transactions_log' )
                        ->where ( '`p`.post_status' , $args[ 'key' ] )
                        ->where ( '`pm`.meta_key' , 'hrw_amount' )
                        ->whereGte ( '`p`.post_date' , $args[ 'from_date' ] )
                        ->whereLte ( '`p`.post_date' , $args[ 'to_date' ] )
                        ->orderBy ( '`pm`.meta_value' ) ;

                return $post_query->fetchCol ( "SUM(`pm`.meta_value)" ) ;
            }
        }

        /*
         * Get transaction ids based on the time interval
         */

        public function get_transaction_ids_by_interval( $args ) {
            global $wpdb ;
            $post_query = new HRW_Query ( $wpdb->posts , 'p' ) ;
            $post_query->select ( 'DISTINCT `p`.ID' )
                    ->leftJoin ( $wpdb->postmeta , 'pm' , '`p`.ID = `pm`.post_id' )
                    ->where ( '`p`.post_parent' , $args[ 'wallet_id' ] )
                    ->where ( '`p`.post_type' , $args[ 'key' ] )
                    ->whereIn ( '`p`.post_status' , array ( "hrw_credit" , "hrw_debit" ) )
                    ->whereGte ( '`p`.post_date' , $args[ 'from_date' ] )
                    ->whereLte ( '`p`.post_date' , $args[ 'to_date' ] )
                    ->orderBy ( '`p`.ID' ) ;

            return $post_query->fetchCol ( 'DISTINCT `p`.ID' ) ;
        }

        /**
         * Send wallet statement to email
         */
        public function wallet_statement_email() {
            check_ajax_referer ( 'hrw-wallet-statement' , 'hrw_security' ) ;

            try {

                if ( ! isset ( $_REQUEST ) || ! isset ( $_REQUEST[ 'interval' ] ) )
                    throw new exception ( esc_html__ ( 'Invalid Request' , HRW_LOCALE ) ) ;

                if ( ! HRW_Wallet_User::wallet_exists () )
                    throw new exception ( esc_html__ ( 'No wallet found' , HRW_LOCALE ) ) ;

                $wallet_id = array ( HRW_Wallet_User::get_wallet_id () ) ;
                $intervals = hrw_sanitize_text_field ( $_REQUEST[ 'interval' ] ) ;
                $type      = 'custom' ;

                switch ( $intervals ) {
                    case '1':
                        $from      = date ( '01 M Y' , strtotime ( 'last month' ) ) ;
                        $to        = date ( 't M Y' , strtotime ( 'last month' ) ) ;
                        $name      = esc_html__ ( 'Last 1 Month' , HRW_LOCALE ) ;
                        break ;
                    case '3':
                        $from      = date ( '01 M Y' , strtotime ( '-3 month' ) ) ;
                        $to        = date ( 't M Y' , strtotime ( 'last month' ) ) ;
                        $name      = esc_html__ ( 'Last 3 Months' , HRW_LOCALE ) ;
                        break ;
                    case '6':
                        $from      = date ( '01 M Y' , strtotime ( '-6 month' ) ) ;
                        $to        = date ( 't M Y' , strtotime ( 'last month' ) ) ;
                        $name      = esc_html__ ( 'Last 6 Months' , HRW_LOCALE ) ;
                        break ;
                    case '12':
                        $from      = date ( '01 M Y' , strtotime ( '-12 month' ) ) ;
                        $to        = date ( 't M Y' , strtotime ( 'last month' ) ) ;
                        $name      = esc_html__ ( 'Last 12 Months' , HRW_LOCALE ) ;
                        break ;
                    default:
                        $intervals = explode ( '+' , $intervals ) ;
                        $from      = date ( 'd M Y' , strtotime ( $intervals[ '0' ] ) ) ;
                        $to        = date ( 'd M Y' , strtotime ( $intervals[ '1' ] ) ) ;
                        $name      = '' ;
                        break ;
                }

                $args = array (
                    'wallet_ids' => $wallet_id ,
                    'from'       => $from ,
                    'to'         => $to ,
                    'name'       => $name ,
                    'type'       => $type
                        ) ;

                $this->send_email ( $args ) ;

                wp_send_json_success ( array ( 'msg' => esc_html__ ( 'Email sent successfully' , HRW_LOCALE ) ) ) ;
            } catch ( Exception $e ) {
                wp_send_json_error ( array ( 'error' => $e->getMessage () ) ) ;
            }
        }

        /*
         * Add Fund Transfer Dashboard Menu
         */

        public function add_dashboard_menu( $menus ) {

            $menus[ 'account_statement' ] = array (
                'label' => esc_html__ ( 'Account Statement' , HRW_LOCALE ) ,
                'code'  => 'fa fa-file-pdf-o' ,
                    ) ;

            return $menus ;
        }

        /*
         * Display Statment menu content
         */

        public function render_account_statement() {
            hrw_get_template ( 'dashboard/account-statement.php' , true ) ;
        }

    }

}
