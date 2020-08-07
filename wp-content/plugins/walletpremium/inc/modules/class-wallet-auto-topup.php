<?php

/**
 * Wallet Auto Topup
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'HRW_Auto_Topup_Module' ) ) {

    /**
     * Class HRW_Auto_Topup_Module
     */
    class HRW_Auto_Topup_Module extends HRW_Modules {
        /*
         * Data
         */

        protected $data = array(
            'enabled'                     => 'no' ,
            'amount_type'                 => '' ,
            'predefined_amount'           => '' ,
            'min_amount'                  => '' ,
            'max_amount'                  => '' ,
            'threshold_amount_type'       => '' ,
            'predefined_threshold_amount' => '' ,
            'min_threshold_amount'        => '' ,
            'max_threshold_amount'        => '' ,
            'display_privacy_policy_link' => 'no' ,
            'privacy_policy_url'          => '' ,
            'privacy_policy_content'      => '' ,
                ) ;
        protected $auto_topup_product ;

        /**
         * Get payment gateways to load in to the WC checkout
         * @var array 
         */
        protected static $load_gateways = array() ;

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'auto_topup' ;
            $this->title = esc_html__( 'Wallet Auto Top-up' , HRW_LOCALE ) ;

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
                    'title' => esc_html__( 'General Settings' , HRW_LOCALE ) ,
                    'id'    => 'auto_topup_general_options' ,
                ) ,
                array(
                    'title'   => esc_html__( 'Top-up Amount Type' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key( 'amount_type' ) ,
                    'type'    => 'select' ,
                    'class'   => 'hrw_select_field_option' ,
                    'default' => '' ,
                    'options' => array(
                        ''             => esc_html__( 'Choose an option' , HRW_LOCALE ) ,
                        'pre-defined'  => esc_html__( 'Predefined' , HRW_LOCALE ) ,
                        'user-defined' => esc_html__( 'User-Defined' , HRW_LOCALE ) ,
                        'both'         => esc_html__( 'Both' , HRW_LOCALE ) ,
                    ) ,
                ) ,
                array(
                    'title'   => esc_html__( 'Predefined Amounts' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key( 'predefined_amount' ) ,
                    'desc'    => '' ,
                    'type'    => 'textarea' ,
                    'default' => '' ,
                ) ,
                array(
                    'title'   => esc_html__( 'Minimum Top-up Amount' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key( 'min_amount' ) ,
                    'desc'    => '' ,
                    'type'    => 'text' ,
                    'default' => '' ,
                    'desc'    => esc_html__( 'The minimum amount which the user can set for Auto Top-Up' , HRW_LOCALE )
                ) ,
                array(
                    'title'   => esc_html__( 'Maximum Top-up Amount' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key( 'max_amount' ) ,
                    'desc'    => '' ,
                    'type'    => 'text' ,
                    'default' => '' ,
                    'desc'    => esc_html__( 'The maximum amount which the user can set for Auto Top-Up' , HRW_LOCALE )
                ) ,
                array(
                    'title'   => esc_html__( 'Threshold Amount Type' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key( 'threshold_amount_type' ) ,
                    'type'    => 'select' ,
                    'class'   => 'hrw_select_field_option' ,
                    'default' => '' ,
                    'options' => array(
                        ''             => esc_html__( 'Choose an option' , HRW_LOCALE ) ,
                        'pre-defined'  => esc_html__( 'Predefined' , HRW_LOCALE ) ,
                        'user-defined' => esc_html__( 'User-Defined' , HRW_LOCALE ) ,
                        'both'         => esc_html__( 'Both' , HRW_LOCALE ) ,
                    ) ,
                ) ,
                array(
                    'title'   => esc_html__( 'Predefined Amounts' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key( 'predefined_threshold_amount' ) ,
                    'desc'    => '' ,
                    'type'    => 'textarea' ,
                    'default' => '' ,
                ) ,
                array(
                    'title'             => esc_html__( 'Minimum Threshold Amount' , HRW_LOCALE ) ,
                    'id'                => $this->get_field_key( 'min_threshold_amount' ) ,
                    'desc'              => '' ,
                    'type'              => 'number' ,
                    'default'           => '' ,
                    'custom_attributes' => array(
                        'min'  => '0' ,
                        'step' => '0.01' ,
                    ) ,
                    'desc'              => esc_html__( 'The minimum amount which the user can set as Wallet Threshold' , HRW_LOCALE )
                ) ,
                array(
                    'title'             => esc_html__( 'Maximum Threshold Amount' , HRW_LOCALE ) ,
                    'id'                => $this->get_field_key( 'max_threshold_amount' ) ,
                    'desc'              => '' ,
                    'type'              => 'number' ,
                    'default'           => '' ,
                    'custom_attributes' => array(
                        'min'  => '0' ,
                        'step' => '0.01' ,
                    ) ,
                    'desc'              => esc_html__( 'The maximum amount which the user can set as Wallet Threshold' , HRW_LOCALE )
                ) ,
                array(
                    'title'   => esc_html__( 'Display Terms and Conditions Link' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key( 'display_privacy_policy_link' ) ,
                    'type'    => 'checkbox' ,
                    'default' => 'no' ,
                    'desc'    => esc_html__( 'When enabled, a Terms and Conditions link will be displayed to the user.' , HRW_LOCALE )
                ) ,
                array(
                    'title'   => esc_html__( 'Terms and Conditions URL' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key( 'privacy_policy_url' ) ,
                    'desc'    => '' ,
                    'type'    => 'text' ,
                    'default' => '' ,
                ) ,
                array(
                    'title'   => esc_html__( 'Terms and Conditions Content' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key( 'privacy_policy_content' ) ,
                    'desc'    => '' ,
                    'type'    => 'textarea' ,
                    'default' => 'I agree to the <a href="[wallet_auto_topup_terms]">Terms and conditions</a>' ,
                ) ,
                array(
                    'type' => 'sectionend' ,
                    'id'   => 'auto_topup_general_options' ,
                ) ,
                    ) ;
        }

        /*
         * Admin Actions
         */

        public function admin_action() {
            add_filter( 'woocommerce_available_payment_gateways' , array( $this , 'prevent_auto_topup_gateways_from_restriction' ) ) ;
            add_action( 'wp_loaded' , array( $this , 'admin_actions' ) , 20 ) ;
        }

        /*
         * Actions
         */

        public function actions() {
            add_filter( 'hrw_add_custom_post_types' , array( $this , 'register_auto_topup_cpt' ) ) ;
            add_action( 'plugins_loaded' , array( $this , 'load_payment_gateways' ) , 20 ) ;
            add_filter( 'woocommerce_payment_gateways' , array( $this , 'add_payment_gateways' ) ) ;
            add_filter( 'hrw_topup_wallet_amount_validation' , array( $this , 'prevent_amount_topup_to_wallet' ) , 20 , 2 ) ;
            add_action( 'hrw_after_wallet_amount_debited' , array( $this , 'maybe_auto_topup' ) , 20 ) ;
            add_filter( 'hrw_validate_low_funds_notification' , array( $this , 'prevent_low_funds_notify' ) , 20 , 2 ) ;
        }

        /*
         * Frontend Actions
         */

        public function frontend_action() {
            //Add Wallet Auto Top-up dashboard menu
            add_filter( 'hrw_frontend_dashboard_menu' , array( $this , 'add_dashboard_menu' ) , 10 , 1 ) ;
            // Load Wallet Auto Top-up Shortcode
            add_filter( 'hrw_load_shortcodes' , array( $this , 'add_wallet_auto_topup_shortcode' ) ) ;
            //Display Wallet Auto Top-up form via Own Shortcode
            add_action( 'hrw_shortcode_auto_topup_content' , array( $this , 'render_wallet_auto_topup_form' ) ) ;
            //Display Wallet Auto Top-up Menu content
            add_action( 'hrw_frontend_dashboard_menu_content_wallet_auto_topup' , array( $this , 'render_wallet_auto_topup_form' ) ) ;

            add_action( 'wp_loaded' , array( $this , 'auto_topup_action' ) , 20 ) ;
            add_filter( 'woocommerce_add_to_cart_validation' , array( $this , 'validate_add_to_cart' ) , 99 , 4 ) ;
            add_filter( 'woocommerce_product_get_price' , array( $this , 'set_initial_payable_zero' ) , 99 , 2 ) ;
            add_filter( 'woocommerce_product_variation_get_price' , array( $this , 'set_initial_payable_zero' ) , 99 , 2 ) ;
            add_filter( 'woocommerce_cart_total' , array( $this , 'get_auto_topup_amount_html' ) , 99 , 2 ) ;
            add_filter( 'woocommerce_cart_needs_payment' , array( $this , 'needs_payment' ) , 99 ) ;
            add_filter( 'hrw_cart_contains_topup_related_product' , array( $this , 'prevent_any_from_auto_topup' ) , 20 ) ;
            add_filter( 'hrw_get_available_payment_gateways_for_topup' , array( $this , 'prevent_other_gateways_in_checkout' ) ) ;
            add_filter( 'hrw_get_available_payment_gateways_for_non_topup' , array( $this , 'prevent_other_gateways_in_checkout' ) ) ;
            add_action( 'woocommerce_order_status_completed' , array( $this , 'maybe_create_new_auto_topup' ) , -1 ) ;
            add_action( 'woocommerce_order_status_processing' , array( $this , 'maybe_create_new_auto_topup' ) , -1 ) ;
        }

        /*
         * Add Wallet Auto Top-up Dashboard Menu
         */

        public function add_dashboard_menu( $menus ) {

            $menus[ 'wallet_auto_topup' ] = array(
                'label' => get_option( 'hrw_dashboard_customization_auto_topup_label' , 'Wallet Auto Topup' ) ,
                'code'  => 'fa fa-arrow-up' ,
                    ) ;

            return $menus ;
        }

        /*
         * Add Wallet Auto Top-up Shortcode
         */

        public function add_wallet_auto_topup_shortcode( $shortcodes ) {
            $shortcodes[] = 'hrw_auto_topup' ;
            return $shortcodes ;
        }

        /*
         * Render Wallet Auto Top-up Form
         */

        public function render_wallet_auto_topup_form() {
            $hrw_auto_topup_id = $this->get_auto_topup_by_user( get_current_user_id() , HRW_Wallet_User::get_wallet_id() ) ;

            if ( $hrw_auto_topup_id ) {
                $auto_topup = hrw_get_wallet_auto_topup( $hrw_auto_topup_id ) ;

                hrw_get_template( 'dashboard/wallet-auto-topup-authorized-user.php' , true , array(
                    'auto_topup' => $auto_topup ,
                ) ) ;
            } else {
                $topup_predefined_amount     = array_map( 'floatval' , array_filter( explode( ',' , $this->predefined_amount ) ) ) ;
                $threshold_predefined_amount = array_map( 'floatval' , array_filter( explode( ',' , $this->predefined_threshold_amount ) ) ) ;

                sort( $topup_predefined_amount ) ;
                sort( $threshold_predefined_amount ) ;

                hrw_get_template( 'dashboard/wallet-auto-topup-new-user.php' , true , array(
                    'topup_amount_type'           => $this->amount_type ,
                    'threshold_amount_type'       => $this->threshold_amount_type ,
                    'topup_min_amount'            => floatval( $this->min_amount ) ,
                    'topup_max_amount'            => is_numeric( $this->max_amount ) ? floatval( $this->max_amount ) : '' ,
                    'threshold_min_amount'        => floatval( $this->min_threshold_amount ) ,
                    'threshold_max_amount'        => is_numeric( $this->max_threshold_amount ) ? floatval( $this->max_threshold_amount ) : '' ,
                    'topup_predefined_amount'     => $topup_predefined_amount ,
                    'threshold_predefined_amount' => $threshold_predefined_amount ,
                ) ) ;
            }
        }

        public function auto_topup_action() {
            $nonce_value = isset( $_POST[ 'hrw-auto-topup-nonce' ] ) ? hrw_sanitize_text_field( $_POST[ 'hrw-auto-topup-nonce' ] ) : null ;

            if ( ! isset( $_POST[ 'hrw-action' ] ) || empty( $_POST[ 'hrw-action' ] ) || ! wp_verify_nonce( $nonce_value , 'hrw-auto-topup' ) )
                return ;

            try {
                switch ( $_POST[ 'hrw-action' ] ) {
                    case 'auto-topup':
                        if ( ! isset( $_POST[ 'hrw_auto_topup' ] ) ) {
                            throw new Exception( esc_html__( 'Invalid Request' , HRW_LOCALE ) ) ;
                        }

                        $auto_topup = $_POST[ 'hrw_auto_topup' ] ;

                        if ( empty( $auto_topup[ 'agree' ] ) && 'yes' === $this->display_privacy_policy_link ) {
                            throw new Exception( esc_html__( 'User should agree to the terms and conditions.' , HRW_LOCALE ) ) ;
                        }

                        if ( empty( $auto_topup[ 'amount' ] ) ) {
                            throw new Exception( esc_html__( 'Auto Top-up amount is required.' , HRW_LOCALE ) ) ;
                        }

                        if ( empty( $auto_topup[ 'threshold_amount' ] ) ) {
                            throw new Exception( esc_html__( 'Threshold amount is required.' , HRW_LOCALE ) ) ;
                        }

                        if ( floatval( $auto_topup[ 'amount' ] ) < floatval( $auto_topup[ 'threshold_amount' ] ) ) {
                            throw new Exception( esc_html__( 'Auto topup amount should be greater than the minimum amount' , HRW_LOCALE ) ) ;
                        }

                        if ( ! WC()->cart->is_empty() ) {
                            WC()->cart->empty_cart() ;
                            wc_add_notice( esc_html__( 'You cannot authorize for Wallet Auto Topup when other products are in Cart. And so your previous cart is removed.' ) , 'error' ) ;
                        }

                        //topup product in cart
                        $was_added_to_cart = WC()->cart->add_to_cart( HRW_Topup_Handler::$topup_product , 1 , 0 , array() , array(
                            'hrw_wallet' => array(
                                'product_id'       => absint( HRW_Topup_Handler::$topup_product ) ,
                                'price'            => floatval( $auto_topup[ 'amount' ] ) ,
                                'topup_amount'     => floatval( $auto_topup[ 'amount' ] ) ,
                                'threshold_amount' => floatval( $auto_topup[ 'threshold_amount' ] ) ,
                                'topup_mode'       => 'auto' ,
                            )
                                ) ) ;

                        if ( false === $was_added_to_cart ) {
                            throw new Exception( esc_html__( 'Something went wrong while authorizing future payments.' , HRW_LOCALE ) ) ;
                        }

                        wc_add_notice( esc_html__( 'Complete your authorization for future payments to automatically Top-up.' , HRW_LOCALE ) , 'success' ) ;

                        //redirect to checkout page
                        wp_safe_redirect( wc_get_checkout_url() ) ;
                        exit ;
                        break ;
                    case 'cancel-auto-topup':
                        if ( empty( $_REQUEST[ 'hrw_cancel_auto_topup_id' ] ) ) {
                            throw new Exception( esc_html__( 'We were unable to process your cancel auto Top-up request, please try again.' , HRW_LOCALE ) ) ;
                        }

                        $payment_gateways = WC()->payment_gateways->payment_gateways() ;
                        $auto_topup       = hrw_get_wallet_auto_topup( absint( $_REQUEST[ 'hrw_cancel_auto_topup_id' ] ) ) ;
                        $auto_topup->update_status( 'hrw_cancelled' ) ;

                        foreach ( $payment_gateways as $gateway ) {
                            if ( $gateway->supports( 'hrw_auto_topup' ) && is_callable( array( $gateway , 'clear_authorized_metas' ) ) ) {
                                $gateway->clear_authorized_metas( $auto_topup->get_id() ) ;
                            }
                        }

                        wc_add_notice( esc_html__( 'You have successfully cancelled your auto Top-up authorization.' , HRW_LOCALE ) , 'success' ) ;

                        do_action( 'hrw_auto_topup_is_cancelled' , array(
                            'auto_topup' => $auto_topup ,
                            'wallet'     => $auto_topup->get_wallet() ,
                        ) ) ;
                        break ;
                }
            } catch ( Exception $e ) {
                wc_add_notice( $e->getMessage() , 'error' ) ;
            }
        }

        public function admin_actions() {
            if ( empty( $_REQUEST ) || empty( $_REQUEST[ 'action' ] ) || empty( $_REQUEST[ 'id' ] ) ) {
                return ;
            }

            if ( 'cancelled' !== $_REQUEST[ 'action' ] ) {
                return ;
            }

            $auto_topup = hrw_get_wallet_auto_topup( $_REQUEST[ 'id' ] ) ;

            if ( ! $auto_topup->exists() ) {
                return ;
            }

            $auto_topup->update_status( 'hrw_cancelled' ) ;

            do_action( 'hrw_auto_topup_is_cancelled' , array(
                'auto_topup' => $auto_topup ,
                'wallet'     => $auto_topup->get_wallet() ,
            ) ) ;
        }

        /*
         * Extra Fields
         */

        public function extra_fields() {
            if ( ! class_exists( 'HRW_Auto_Topup_Table' ) )
                require_once( HRW_PLUGIN_PATH . '/premium/inc/admin/wp-list-table/class-hrw-wallet-auto-topup-table.php' ) ;

            echo '<div class="' . $this->plugin_slug . '_table_wrap">' ;
            echo '<h2 class="wp-heading-inline">' . esc_html__( 'Wallet Auto Top-up' , HRW_LOCALE ) . '</h2>' ;

            $post_table = new HRW_Auto_Topup_Table() ;
            $post_table->prepare_items() ;
            $post_table->views() ;
            $post_table->display() ;
            echo '</div>' ;
        }

        /*
         * Frontend enqueue js files
         */

        public function frontend_external_js_files() {
            wp_enqueue_script( 'hrw-auto-topup' , HRW_PLUGIN_URL . '/premium/assets/js/frontend/auto-topup.js' , array( 'jquery' ) , HRW_VERSION ) ;
        }

        /*
         * Admin enqueue js files
         */

        public function admin_external_js_files() {
            wp_enqueue_script( 'hrw-auto-topup' , HRW_PLUGIN_URL . '/premium/assets/js/admin/auto-topup.js' , array( 'jquery' , 'blockUI' ) , HRW_VERSION ) ;
            wp_enqueue_script( 'hrw-stripe-settings' , HRW_PLUGIN_URL . '/premium/assets/js/admin/stripe-settings.js' , array( 'jquery' ) , HRW_VERSION ) ;
        }

        public function register_auto_topup_cpt( $post_types ) {
            $post_types[ 'hrw_auto_topup' ] = array( $this , 'post_type_args' ) ;
            return $post_types ;
        }

        public function load_payment_gateways() {
            if ( ! class_exists( 'WC_Payment_Gateway' ) )
                return ;

            self::$load_gateways[] = include_once(HRW_PLUGIN_PATH . '/premium/inc/gateways/stripe/class-hrw-stripe-gateway.php') ;
        }

        public function add_payment_gateways( $gateways ) {
            if ( empty( self::$load_gateways ) ) {
                return $gateways ;
            }

            foreach ( self::$load_gateways as $gateway ) {
                $gateways[] = $gateway ;
            }
            return $gateways ;
        }

        public function post_type_args() {

            return apply_filters( 'hrw_wallet_auto_topup_post_type_args' , array(
                'labels'      => array(
                    'name'               => esc_html__( 'Wallet Auto Top-ups' , HRW_LOCALE ) ,
                    'singular_name'      => esc_html__( 'Wallet Auto Top-up' , HRW_LOCALE ) ,
                    'all_items'          => esc_html__( 'Wallet Auto Top-ups' , HRW_LOCALE ) ,
                    'menu_name'          => esc_html_x( 'Wallet Auto Top-ups' , 'Admin menu name' , HRW_LOCALE ) ,
                    'add_new'            => esc_html__( 'Add Wallet Auto Top-up' , HRW_LOCALE ) ,
                    'add_new_item'       => esc_html__( 'Add New Wallet Auto Top-up' , HRW_LOCALE ) ,
                    'edit'               => esc_html__( 'Edit' , HRW_LOCALE ) ,
                    'edit_item'          => esc_html__( 'Edit Wallet Auto Top-up' , HRW_LOCALE ) ,
                    'new_item'           => esc_html__( 'New Wallet Auto Top-up' , HRW_LOCALE ) ,
                    'view'               => esc_html__( 'View Wallet Auto Top-up' , HRW_LOCALE ) ,
                    'view_item'          => esc_html__( 'View Wallet Auto Top-up' , HRW_LOCALE ) ,
                    'view_items'         => esc_html__( 'View Wallet Auto Top-up' , HRW_LOCALE ) ,
                    'search_items'       => esc_html__( 'Search Wallet Auto Top-ups' , HRW_LOCALE ) ,
                    'not_found'          => esc_html__( 'No Data found' , HRW_LOCALE ) ,
                    'not_found_in_trash' => esc_html__( 'No Data found in trash' , HRW_LOCALE ) ,
                ) ,
                'description' => esc_html__( 'Here you can able to see list of Wallet Auto Top-ups' , HRW_LOCALE ) ,
                    ) ) ;
        }

        public function get_auto_topup_by_user( $user_id , $wallet_id , $status = 'hrw_active' ) {
            global $wpdb ;

            $status = is_array( $status ) ? implode( ',' , $status ) : $status ;
            return $wpdb->get_var(
                            $wpdb->prepare(
                                    "SELECT DISTINCT p.ID from {$wpdb->posts} p 
                                        WHERE p.post_type='hrw_auto_topup' AND p.post_parent='{$wallet_id}' AND p.post_status IN (%s) AND p.post_author='%s' LIMIT 1"
                                    , $status , $user_id
                    ) ) ;
        }

        public function cart_contains_auto_topup_product() {
            if ( $this->auto_topup_product )
                return $this->auto_topup_product ;

            $this->auto_topup_product = hrw_auto_topup_product_in_cart() ;

            return $this->auto_topup_product ;
        }

        public function validate_add_to_cart( $bool , $product_id , $quantity , $variation_id = '' ) {
            $product_id = $variation_id ? $variation_id : $product_id ;

            if ( HRW_Topup_Handler::$topup_product != $product_id ) {
                if ( $this->cart_contains_auto_topup_product() ) {
                    wc_add_notice( esc_html__( 'You cannot add the product to cart since the Wallet Auto Topup product is already in Cart' ) , 'error' ) ;
                    return false ;
                }
            }
            return $bool ;
        }

        public function set_initial_payable_zero( $price , $product ) {
            if ( $this->cart_contains_auto_topup_product() ) {
                if ( $product->get_id() === $this->auto_topup_product[ 'product_id' ] ) {
                    return 0 ;
                }
            }
            return $price ;
        }

        public function get_auto_topup_amount_html( $total ) {
            if ( $this->cart_contains_auto_topup_product() ) {
                $total = sprintf( esc_html__( '%s will be charged as auto Top-up amount.' , HRW_LOCALE ) , '<code>' . wc_price( $this->auto_topup_product[ 'price' ] ) . '</code>' ) ;
            }
            return $total ;
        }

        public function needs_payment( $need ) {
            if ( $this->cart_contains_auto_topup_product() ) {
                $need = true ;
            }
            return $need ;
        }

        public function prevent_any_from_auto_topup( $bool ) {
            if ( ! $bool ) {
                if ( $this->cart_contains_auto_topup_product() ) {
                    return true ;
                }
            }
            return $bool ;
        }

        public function prevent_auto_topup_gateways_from_restriction( $available_gateways ) {
            if ( empty( $available_gateways ) || ! in_array( get_current_screen()->id , hrw_page_screen_ids() ) ) {
                return $available_gateways ;
            }

            foreach ( $available_gateways as $gateway_id => $gateway ) {
                if ( $gateway->supports( 'hrw_auto_topup' ) ) {
                    unset( $available_gateways[ $gateway_id ] ) ;
                }
            }
            return $available_gateways ;
        }

        public function prevent_other_gateways_in_checkout( $available_gateways ) {
            if ( empty( $available_gateways ) ) {
                return $available_gateways ;
            }

            if ( $this->cart_contains_auto_topup_product() ) {
                foreach ( $available_gateways as $gateway_id => $gateway ) {
                    if ( ! $gateway->supports( 'hrw_auto_topup' ) ) {
                        unset( $available_gateways[ $gateway_id ] ) ;
                    }
                }
            } else {
                foreach ( $available_gateways as $gateway_id => $gateway ) {
                    if ( $gateway->supports( 'hrw_auto_topup' ) ) {
                        unset( $available_gateways[ $gateway_id ] ) ;
                    }
                }
            }
            return $available_gateways ;
        }

        public function maybe_create_new_auto_topup( $order_id ) {
            if ( ! is_user_logged_in() ) {
                return ;
            }

            $wallet_props = get_post_meta( $order_id , 'hr_wallet' , true ) ;

            if (
                    ! is_array( $wallet_props ) ||
                    'yes' === get_post_meta( $order_id , 'hr_is_auto_topup' , true ) ||
                    'auto' !== get_post_meta( $order_id , 'hr_wallet_topup_mode' , true )
            ) {
                return ;
            }

            if ( ! HRW_Wallet_User::wallet_exists() ) {
                $wallet_id = HRW_Credit_Debit_Handler::credit_amount_to_wallet( array(
                            'event' => esc_html__( 'User has authorized for Wallet Auto Top-up' , HRW_LOCALE ) ,
                        ) ) ;
            } else {
                $wallet_id = HRW_Wallet_User::get_wallet_id() ;
            }

            if ( ! $wallet_id || ! is_numeric( $wallet_id ) ) {
                return ;
            }

            $auto_topup_id = $this->get_auto_topup_by_user( get_current_user_id() , $wallet_id , 'hrw_cancelled' ) ;
            $order         = wc_get_order( $order_id ) ;

            if ( ! $auto_topup_id ) {
                foreach ( $order->get_items() as $item ) {
                    if ( ! $item ) {
                        return ;
                    }

                    $item_product = wc_get_product( $item->get_variation_id() ? $item->get_variation_id() : $item->get_product_id() ) ;

                    if ( ! $item_product || $item_product->get_id() != get_post_meta( $order->get_id() , 'hr_wallet_topup_product' , true ) ) {
                        return ;
                    }
                }

                $auto_topup_id = wp_insert_post( array(
                    'post_type'   => 'hrw_auto_topup' ,
                    'post_status' => 'hrw_active' ,
                    'post_parent' => $wallet_id ,
                    'post_author' => get_current_user_id() ,
                    'post_title'  => 'Wallet Auto Topup' ,
                        ) , true ) ;

                if ( is_wp_error( $auto_topup_id ) ) {
                    return ;
                }
            } else {
                $auto_topup = hrw_get_wallet_auto_topup( $auto_topup_id ) ;
                $auto_topup->update_status( 'hrw_active' ) ;
            }

            if ( ! $auto_topup_id ) {
                return ;
            }

            foreach ( $wallet_props as $prop => $val ) {
                update_post_meta( $auto_topup_id , "hrw_{$prop}" , $val ) ;
            }

            update_post_meta( $auto_topup_id , 'hrw_currency' , $order->get_currency() ) ;
            update_post_meta( $auto_topup_id , 'hrw_last_order' , $order->get_id() ) ;
            update_post_meta( $auto_topup_id , 'hrw_payment_method' , $order->get_payment_method() ) ;
            update_post_meta( $order->get_id() , 'hrw_auto_topup_id' , $auto_topup_id ) ;
            update_post_meta( $order->get_id() , 'hr_is_auto_topup' , 'yes' ) ;

            do_action( 'hrw_auto_topup_authorization_successful' , array(
                'auto_topup_id' => $auto_topup_id ,
                'wallet_id'     => $wallet_id ,
                'order'         => $order
            ) ) ;
        }

        public function prevent_amount_topup_to_wallet( $bool , $order_id ) {
            if ( 'auto' === get_post_meta( $order_id , 'hr_wallet_topup_mode' , true ) ) {
                return false ;
            }
            return $bool ;
        }

        public function prevent_low_funds_notify( $bool , $wallet ) {
            $auto_topup_id = $this->get_auto_topup_by_user( absint( $wallet->get_user_id() ) , $wallet->get_id() ) ;

            if ( $auto_topup_id ) {
                return false ;
            }
            return $bool ;
        }

        public function maybe_auto_topup( $wallet_id ) {

            $wallet        = hrw_get_wallet( $wallet_id ) ;
            $auto_topup_id = $this->get_auto_topup_by_user( absint( $wallet->get_user_id() ) , $wallet_id ) ;

            if ( ! $auto_topup_id ) {
                return ;
            }

            $auto_topup = hrw_get_wallet_auto_topup( $auto_topup_id ) ;

            if ( floatval( $wallet->get_available_balance() ) >= floatval( $auto_topup->get_threshold_amount() ) ) {
                return ;
            }

            try {
                if ( floatval( $auto_topup->get_topup_amount() ) <= 0 ) {
                    throw new Exception( esc_html__( 'Top-up amount should be greater than zero.' , HRW_LOCALE ) ) ;
                }

                $result = apply_filters( "hrw_charge_user_via_{$auto_topup->get_payment_method()}" , false , $auto_topup ) ;

                if ( is_wp_error( $result ) ) {
                    throw new Exception( $result->get_error_message() ) ;
                }

                if ( ! $result ) {
                    throw new Exception( esc_html__( "Unable to Auto Top-up the wallet" , HRW_LOCALE ) ) ;
                }

                HRW_Credit_Debit_Handler::credit_amount_to_wallet( array(
                    'user_id'  => absint( $auto_topup->get_user_id() ) ,
                    'amount'   => floatval( $auto_topup->get_topup_amount() ) ,
                    'event'    => 'Wallet Auto Top-up Successful' ,
                    'currency' => $auto_topup->get_currency()
                ) ) ;

                do_action( 'hrw_auto_topup_successful' , array(
                    'auto_topup' => $auto_topup ,
                    'wallet'     => $wallet ,
                ) ) ;
            } catch ( Exception $e ) {
                $auto_topup->update_status( 'hrw_cancelled' ) ;

                do_action( 'hrw_auto_topup_is_failed' , array(
                    'auto_topup'     => $auto_topup ,
                    'wallet'         => $wallet ,
                    'failure_reason' => $e->getMessage() ,
                ) ) ;
            }
        }

    }

}
