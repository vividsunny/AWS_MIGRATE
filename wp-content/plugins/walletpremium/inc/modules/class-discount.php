<?php
/**
 * Discount
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'HRW_Discount_Module' ) ) {

    /**
     * Class HRW_Discount_Module
     */
    class HRW_Discount_Module extends HRW_Modules {
        /*
         * Data
         */

        protected $data = array(
            'enabled'              => 'no' ,
            'hide_discount_notice' => '1' ,
            'discount_notice'      => 'Get [discount_value] Discount by completing this order through [gateway_title]' ,
            'rule_priority'        => 1 ,
                ) ;

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'discount' ;
            $this->title = esc_html__( 'Discount' , HRW_LOCALE ) ;

            parent::__construct() ;
        }

        /*
         * is plugin enabled
         */

        public function is_plugin_enabled() {

            return hrw_is_premium() ;
        }

        /*
         * warning message
         */

        public function get_warning_message() {

            $message = sprintf( esc_html__( 'This feature is available in %s' , HRW_LOCALE ) , '<a href="https://hoicker.com/product/wallet" target="_blank">' . esc_html__( "Wallet Premium Version" , HRW_LOCALE ) . '</a>' ) ;

            return '<i class="fa fa-info-circle"></i> ' . $message ;
        }

        /*
         * Get settings options array
         */

        public function settings_options_array() {
            return array(
                array(
                    'type'  => 'title' ,
                    'title' => esc_html__( 'Discount Settings' , HRW_LOCALE ) ,
                    'id'    => 'discount_settings' ,
                ) ,
                array(
                    'title'   => esc_html__( 'Rule Priority' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key( 'rule_priority' ) ,
                    'type'    => 'select' ,
                    'desc'    => esc_html( 'This option is applicable only when a discount value is matching with more than one rule configured' , HRW_LOCALE ) ,
                    'default' => '1' ,
                    'options' => array(
                        '1' => esc_html__( 'First Matched Rule' , HRW_LOCALE ) ,
                        '2' => esc_html__( 'Last Matched Rule' , HRW_LOCALE ) ,
                        '3' => esc_html__( 'Minimum Discount Value' , HRW_LOCALE ) ,
                        '4' => esc_html__( 'Maximum Discount Value' , HRW_LOCALE ) ,
                    ) ,
                ) ,
                array(
                    'type' => 'sectionend' ,
                    'id'   => 'discount_settings' ,
                ) ,
                array(
                    'type'  => 'title' ,
                    'title' => esc_html__( 'Message Settings' , HRW_LOCALE ) ,
                    'id'    => 'discount_msg_settings' ,
                ) ,
                array(
                    'title'   => esc_html__( 'Show/Hide Discount Notice' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key( 'hide_discount_notice' ) ,
                    'type'    => 'select' ,
                    'default' => '1' ,
                    'options' => array(
                        '1' => esc_html__( 'Show' , HRW_LOCALE ) ,
                        '2' => esc_html__( 'Hide' , HRW_LOCALE ) ,
                    ) ,
                ) ,
                array(
                    'title'   => esc_html__( 'Message to display as Notice in Cart and Checkout Page' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key( 'discount_notice' ) ,
                    'type'    => 'textarea' ,
                    'default' => 'Get [discount_value] Discount by completing this order through [gateway_title]' ,
                ) ,
                array(
                    'type' => 'sectionend' ,
                    'id'   => 'discount_msg_settings' ,
                ) ,
                    ) ;
        }

        /*
         * Admin action
         */

        public function admin_action() {
            add_action( $this->plugin_slug . '_section_discount_msg_settings_after_end' , array( $this , 'display_rules' ) ) ;
            add_action( 'wp_ajax_hrw_add_discount_rule' , array( $this , 'add_discount_rule' ) ) ;
            add_action( 'wp_ajax_hrw_add_discount_local_rule' , array( $this , 'add_discount_local_rule' ) ) ;
            add_action( 'wp_ajax_hrw_delete_discount_rule' , array( $this , 'delete_discount_rule' ) ) ;
            add_action( 'wp_ajax_hrw_add_fee' , array( $this , 'hrw_add_fee' ) ) ;
        }

        /*
         * Frontend action
         */

        public function frontend_action() {
            //Show Discount Notice Field in Cart
            add_action( 'woocommerce_before_cart_table' , array( $this , 'discount_notice' ) ) ;
            //Show Discount Notice Field in Checkout
            add_action( 'woocommerce_before_checkout_form' , array( $this , 'discount_notice' ) ) ;
            //Add Fee in Cart/Checkout
            add_action( 'woocommerce_cart_calculate_fees' , array( $this , 'calculate_fee' ) ) ;
        }

        /*
         * Admin enqueue css files
         */

        public function admin_external_css_files() {
            wp_enqueue_style( 'hrw-discount-module' , HRW_PLUGIN_URL . '/premium/assets/css/admin/discount.css' , array() , HRW_VERSION ) ;
        }

        /*
         * Admin enqueue JS files
         */

        public function admin_external_js_files() {
            wp_enqueue_script( 'hrw-discount' , HRW_PLUGIN_URL . '/premium/assets/js/admin/discount.js' , array( 'jquery' , 'blockUI' ) , HRW_VERSION ) ;
            wp_localize_script(
                    'hrw-discount' , 'hrw_discount_params' , array(
                'discount_nonce' => wp_create_nonce( 'hrw-discount-nonce' ) ,
                'ajaxurl'        => HRW_ADMIN_AJAX_URL
                    )
            ) ;
        }

        /*
         * Frontend enqueue JS files
         */

        public function frontend_external_js_files() {
            if ( is_checkout() ) {
                wp_enqueue_script( 'hrw-discount' , HRW_PLUGIN_URL . '/premium/assets/js/frontend/discount.js' , array( 'jquery' , 'blockUI' ) , HRW_VERSION ) ;
                wp_localize_script(
                        'hrw-discount' , 'hrw_discount_params' , array(
                    'discount_nonce' => wp_create_nonce( 'hrw-discount-nonce' ) ,
                    'ajaxurl'        => HRW_ADMIN_AJAX_URL
                        )
                ) ;
            }
        }

        /*
         * Display Rules
         */

        public function display_rules() {
            global $wpdb ;
            $post_query = new HRW_Query( $wpdb->posts , 'p' ) ;
            $discounts  = $post_query->select( 'DISTINCT `p`.`ID`' )
                    ->where( '`p`.post_type' , 'hrw_discount' )
                    ->where( '`p`.post_status' , 'publish' )
                    ->fetchArray() ;

            include HRW_PLUGIN_PATH . '/inc/admin/menu/views/discount/discount-rule-list.php' ;
        }

        /*
         * After save
         */

        public function after_save() {
            if ( ! isset( $_POST[ 'hrw_discount_rules' ] ) )
                return ;

            if ( ! hrw_check_is_array( $_POST[ 'hrw_discount_rules' ] ) )
                return ;

            foreach ( hrw_sanitize_text_field( $_POST[ 'hrw_discount_rules' ] ) as $post_id => $meta_values ) {
                $update = true ;
                if ( isset( $meta_values[ 'purchase_history' ] ) && $meta_values[ 'purchase_history' ] == '1' ) {
                    if ( isset( $meta_values[ 'no_of_order' ] ) && empty( $meta_values[ 'no_of_order' ] ) ) {
                        $update = false ;
                        HRW_Settings::add_error( sprintf( esc_html__( '%s : Number of Order(s) field cannot be empty' , HRW_LOCALE ) , $meta_values[ 'rule_name' ] ) ) ;
                    }
                } elseif ( isset( $meta_values[ 'purchase_history' ] ) && $meta_values[ 'purchase_history' ] == '' ) {
                    if ( isset( $meta_values[ 'total_amount' ] ) && empty( $meta_values[ 'total_amount' ] ) ) {
                        $update = false ;
                        HRW_Settings::add_error( sprintf( esc_html__( '%s : Total Amount field cannot be empty' , HRW_LOCALE ) , $meta_values[ 'rule_name' ] ) ) ;
                    }
                }

                if ( isset( $meta_values[ 'from_date' ] ) && empty( $meta_values[ 'from_date' ] ) ) {
                    $update = false ;
                    HRW_Settings::add_error( sprintf( esc_html__( '%s : From Date field cannot be empty' , HRW_LOCALE ) , $meta_values[ 'rule_name' ] ) ) ;
                }

                if ( isset( $meta_values[ 'to_date' ] ) && empty( $meta_values[ 'to_date' ] ) ) {
                    $update = false ;
                    HRW_Settings::add_error( sprintf( esc_html__( '%s : To Date field cannot be empty' , HRW_LOCALE ) , $meta_values[ 'rule_name' ] ) ) ;
                }

                if ( ! isset( $meta_values[ 'valid_days' ] ) ) {
                    $update = false ;
                    HRW_Settings::add_error( sprintf( esc_html__( '%s : Discount Valid on following Days cannot be empty' , HRW_LOCALE ) , $meta_values[ 'rule_name' ] ) ) ;
                }

                if ( $update )
                    hrw_update_discount( $post_id , $meta_values ) ;
            }
        }

        /*
         * Add Rule for Cashback
         */

        public static function add_discount_rule() {
            check_ajax_referer( 'hrw-discount-nonce' , 'hrw_security' ) ;

            try {
                if ( ! isset( $_REQUEST[ 'rule_name' ] ) || empty( $_REQUEST[ 'rule_name' ] ) )
                    throw new exception( esc_html__( 'Rule name cannot be empty' , HRW_LOCALE ) ) ;

                ob_start() ;
                $metadata                = array() ;
                $metadata[ 'rule_name' ] = hrw_sanitize_text_field( $_REQUEST[ 'rule_name' ] ) ;

                $postid   = hrw_create_new_discount( $metadata ) ;
                $discount = hrw_get_discount( $postid ) ;
                include HRW_PLUGIN_PATH . '/inc/modules/views/discount-rules.php' ;
                $field    = ob_get_contents() ;
                ob_end_clean() ;

                wp_send_json_success( array( 'field' => $field ) ) ;
            } catch ( exception $ex ) {
                wp_send_json_error( array( 'error' => $ex->getMessage() ) ) ;
            }
        }

        /*
         * Add Local Rule
         */

        public static function add_discount_local_rule() {
            check_ajax_referer( 'hrw-discount-nonce' , 'hrw_security' ) ;

            try {
                if ( ! isset( $_REQUEST[ 'postid' ] ) )
                    throw new exception( esc_html__( 'Invalid Request' , HRW_LOCALE ) ) ;

                ob_start() ;
                $postid   = absint( $_REQUEST[ 'postid' ] ) ;
                $uniqueid = uniqid() ;

                include HRW_PLUGIN_PATH . '/inc/admin/menu/views/discount/local-rule-list.php' ;

                $field = ob_get_contents() ;
                ob_end_clean() ;

                wp_send_json_success( array( 'field' => $field ) ) ;
            } catch ( exception $ex ) {
                wp_send_json_error( array( 'error' => $ex->getMessage() ) ) ;
            }
        }

        /*
         * Delete Cashback Rule
         */

        public static function delete_discount_rule() {
            check_ajax_referer( 'hrw-discount-nonce' , 'hrw_security' ) ;

            try {
                if ( ! isset( $_REQUEST[ 'postid' ] ) )
                    throw new exception( esc_html__( 'Invalid Request' , HRW_LOCALE ) ) ;

                hrw_delete_discount( absint( $_REQUEST[ 'postid' ] ) ) ;

                wp_send_json_success() ;
            } catch ( exception $ex ) {
                wp_send_json_error( array( 'error' => $ex->getMessage() ) ) ;
            }
        }

        /*
         * Discount Notice in Cart/Checkout
         */

        public function discount_notice() {
            if ( ! is_user_logged_in() )
                return ;

            if ( $this->hide_discount_notice == 2 )
                return ;

            $matched_value = HRWP_Discount_Handler::get_matched_rules() ;
            if ( empty( $matched_value ) )
                return ;

            $wc_gateways      = new WC_Payment_Gateways() ;
            $payment_gateways = $wc_gateways->get_available_payment_gateways() ;
            if ( ! isset( $payment_gateways[ 'HR_Wallet_Gateway' ] ) )
                return ;

            $notice = str_replace( array( '[discount_value]' , '[gateway_title]' ) , array( get_woocommerce_currency_symbol() . $matched_value , $payment_gateways[ 'HR_Wallet_Gateway' ]->title ) , $this->discount_notice ) ;
            ?>
            <div class="hrw_discount_notice_wrapper woocommerce-info"><?php echo esc_html( $notice ) ; ?></div>
            <?php
        }

        /*
         * Add Fee
         */

        public function calculate_fee() {
            if ( ! WC()->session->get( 'hrwp_check_if_fee_exist' ) )
                return ;

            if ( WC()->session->get( 'hrwp_check_if_fee_exist' ) == 'no' || WC()->session->get( 'hrwp_check_if_fee_exist' ) == NULL )
                return ;

            $matched_value = HRWP_Discount_Handler::get_matched_rules() ;
            if ( empty( $matched_value ) )
                return ;

            WC()->cart->add_fee( 'Discount' , -$matched_value , true ) ;
        }

        /*
         * Add Fee in Checkout for Wallet Gateway
         */

        public function hrw_add_fee() {
            check_ajax_referer( 'hrw-discount-nonce' , 'hrw_security' ) ;

            try {
                if ( isset( $_REQUEST[ 'gatewayid' ] ) && hrw_sanitize_text_field( $_REQUEST[ 'gatewayid' ] ) == 'HR_Wallet_Gateway' ) {
                    WC()->session->set( 'hrwp_check_if_fee_exist' , 'yes' ) ;
                } else {
                    WC()->session->__unset( 'hrwp_check_if_fee_exist' ) ;
                    remove_action( 'woocommerce_cart_calculate_fees' , array( $this , 'calculate_fee' ) ) ;
                }

                wp_send_json_success() ;
            } catch ( exception $ex ) {
                wp_send_json_error( array( 'error' => $ex->getMessage() ) ) ;
            }
        }

    }

}
