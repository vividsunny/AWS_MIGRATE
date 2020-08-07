<?php

/**
 * Gift Card
 */
if ( ! defined ( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists ( 'HRW_GiftCard' ) ) {

    /**
     * Class HRW_GiftCard
     */
    class HRW_GiftCard extends HRW_Modules {
        /*
         * Gift Product
         */

        public static $hrw_gift_product = '' ;


        /*
         * Data
         */
        protected $data = array (
            'enabled'                    => 'no' ,
            'amount_type'                => '1' ,
            'predefined_amount'          => '' ,
            'min_amount'                 => '' ,
            'max_amount'                 => '' ,
            'topup_field_type'           => '' ,
            'gift_product_type'          => '' ,
            'gift_product_id'            => array () ,
            'gift_product_name'          => '' ,
            'expiry_days'                => '365' ,
            'gift_gateways'              => array () ,
            'topup_order_status'         => array () ,
            'file_name_method'           => '1' ,
            'default_format'             => 'Y-m-d @ H.i.s' ,
            'char_count'                 => 10 ,
            'file_prefix'                => '' ,
            'file_suffix'                => '' ,
            'sequence_number'            => 1 ,
            'logo_image_url'             => '' ,
            'logo_max_percent'           => '' ,
            'admin_section_heading'      => '' ,
            'admin_deatils'              => '' ,
            'sender_section_heading'     => '' ,
            'min_amount'                 => '' ,
            'code_type'                  => '' ,
            'exclude_alphbates'          => '' ,
            'code_char_count'            => 10 ,
            'code_prefix'                => '' ,
            'code_suffix'                => '' ,
            'invalid_email_err'          => 'Please enter the valid email id' ,
            'empty_name_err'             => 'Please enter the name' ,
            'empty_amnt_err'             => 'Please enter the amount' ,
            'min_amnt_err'               => 'Please enter an amount more than[minimum amount]' ,
            'max_amnt_err'               => 'Please enter an amount less than[maximum amount]' ,
            'invalid_code_err'           => 'Please enter the valid code to redeem' ,
            'max_threshold_reaching_err' => 'You will be reaching the maximum threshold[maximum_amount] balance to hold on your wallet by using this code. Hence, you cannot redeem the code.' ,
            'max_threshold_reached_err'  => 'You have already reached the maximum threshold [maximum_amount] balance to hold on your wallet. Hence, you cannot redeem the code. ' ,
            'redeem_success_msg'         => 'You have successfully redeemed the code and $10[amount] has added to your wallet' ,
            'redeem_event_msg'           => 'Funds credited to your wallet for using Gift Card' ,
                ) ;

        /**
         * Class Constructor
         */
        public function __construct() {

            self::set_gift_product () ;
            $this->id    = 'gift_card' ;
            $this->title = esc_html__ ( 'Gift Card' , HRW_LOCALE ) ;

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

        public function save() {
            if ( isset ( $_POST[ 'hrw_uploaded_file_key' ] ) ) {
                update_option ( 'hrw_gift_card_logo_image_url' , $_POST[ 'hrw_uploaded_file_key' ] ) ;
            }
        }

        /*
         * Get settings options array
         */

        public function settings_options_array() {
            $wc_order_statuses  = hrw_get_paid_order_statuses () ;
            $gift_gateways      = hrw_get_gift_gateways () ;
            $gift_gateways_keys = hrw_check_is_array ( $gift_gateways ) ? array_keys ( $gift_gateways ) : array () ;

            return array (
                array (
                    'type'  => 'title' ,
                    'title' => esc_html__ ( 'General Settings' , HRW_LOCALE ) ,
                    'id'    => 'hrw_gift_general_settings' ,
                ) ,
                array (
                    'title'   => esc_html__ ( 'Gift Card Amount Type' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key ( 'amount_type' ) ,
                    'type'    => 'select' ,
                    'class'   => 'hrw_select_field_option' ,
                    'default' => '1' ,
                    'options' => array (
                        '1' => esc_html__ ( 'User-Defined' , HRW_LOCALE ) ,
                        '2' => esc_html__ ( 'Predefined' , HRW_LOCALE ) ,
                        '3' => esc_html__ ( 'Both' , HRW_LOCALE ) ,
                    ) ,
                ) ,
                array (
                    'title'   => esc_html__ ( 'Predefined Amounts' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key ( 'predefined_amount' ) ,
                    'class'   => 'hrw_gift_card_predefined_field gift_input_fields' ,
                    'desc'    => '' ,
                    'type'    => 'textarea' ,
                    'default' => '' ,
                ) ,
                array (
                    'title'             => esc_html__ ( 'Minimum Gift Card Amount' , HRW_LOCALE ) ,
                    'id'                => $this->get_field_key ( 'min_amount' ) ,
                    'class'             => 'hrw_gift_card_userdefined_field gift_input_fields' ,
                    'type'              => 'number' ,
                    'custom_attributes' => array ( 'min' => '0' ) ,
                    'default'           => '' ,
                ) ,
                array (
                    'title'             => esc_html__ ( 'Maximum Gift Card Amount' , HRW_LOCALE ) ,
                    'id'                => $this->get_field_key ( 'max_amount' ) ,
                    'class'             => 'hrw_gift_card_userdefined_field gift_input_fields' ,
                    'type'              => 'number' ,
                    'custom_attributes' => array ( 'min' => '0' ) ,
                    'default'           => '' ,
                ) ,
                array (
                    'title'   => esc_html__ ( 'Gift Card Product' , HRW_LOCALE ) ,
                    'type'    => 'select' ,
                    'id'      => $this->get_field_key ( 'gift_product_type' ) ,
                    'options' => array (
                        '1' => esc_html__ ( 'New Product' , HRW_LOCALE ) ,
                        '2' => esc_html__ ( 'Existing Product' , HRW_LOCALE ) ,
                    ) ,
                ) ,
                array (
                    'title'             => esc_html__ ( 'Select a Product' , HRW_LOCALE ) ,
                    'id'                => $this->get_field_key ( 'gift_product_id' ) ,
                    'class'             => 'hrw_product_selection hrw_gift_product_field' ,
                    'action'            => 'hrw_product_search' ,
                    'type'              => 'ajaxmultiselect' ,
                    'list_type'         => 'products' ,
                    'placeholder'       => esc_html__ ( 'Select a product' , HRW_LOCALE ) ,
                    'multiple'          => false ,
                    'allow_clear'       => false ,
                    'custom_attributes' => array ( 'required' => 'required' )
                ) ,
                array (
                    'title'   => esc_html__ ( 'Product Name' , HRW_LOCALE ) ,
                    'type'    => 'text' ,
                    'default' => 'Wallet' ,
                    'id'      => $this->get_field_key ( 'gift_product_name' ) ,
                    'class'   => 'hrw_gift_product_field' ,
                ) , array (
                    'type'    => 'button' ,
                    'default' => esc_html__ ( 'Create New product' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key ( 'create_product_btn' ) ,
                    'class'   => 'hrw_gift_product_field hrw_create_product_btn' ,
                ) , array (
                    'title'             => esc_html__ ( 'Gift Card Expiry in days' , HRW_LOCALE ) ,
                    'id'                => $this->get_field_key ( 'expiry_days' ) ,
                    'type'              => 'number' ,
                    'custom_attributes' => array ( 'min' => '0' ) ,
                    'default'           => '' ,
                ) ,
                array (
                    'title'   => esc_html__ ( 'Payment Gateway(s) to display at checkout for sending a Gift Card' , HRW_LOCALE ) ,
                    'type'    => 'multiselect' ,
                    'class'   => 'hrw_select2' ,
                    'default' => $gift_gateways_keys ,
                    'options' => $gift_gateways ,
                    'id'      => $this->get_field_key ( 'gift_gateways' ) ,
                ) ,
                array (
                    'title'   => esc_html__ ( 'Gift Card will be sent to users when the order status reaches' , HRW_LOCALE ) ,
                    'type'    => 'multiselect' ,
                    'class'   => 'hrw_select2' ,
                    'default' => array ( 'completed' ) ,
                    'options' => $wc_order_statuses ,
                    'id'      => $this->get_field_key ( 'topup_order_status' ) ,
                ) ,
                array (
                    'type' => 'sectionend' ,
                    'id'   => 'hrw_gift_general_settings' ,
                ) ,
                array (
                    'type'  => 'title' ,
                    'title' => esc_html__ ( 'Gift Card Settings' , HRW_LOCALE ) ,
                    'id'    => 'wallet_gift_code_settings' ,
                ) ,
                array (
                    'title'   => esc_html__ ( 'Gift Card Type' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key ( 'code_type' ) ,
                    'type'    => 'select' ,
                    'default' => '4' ,
                    'options' => array (
                        '4' => esc_html__ ( 'Numeric' , HRW_LOCALE ) ,
                        '3' => esc_html__ ( 'Alphanumeric' , HRW_LOCALE ) ,
                    ) ,
                ) ,
                array (
                    'title'   => esc_html__ ( 'Exclude Alphabates' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key ( 'exclude_alphbates' ) ,
                    'type'    => 'text' ,
                    'class'   => 'hrw_alphanumeric_field' ,
                    'default' => '' ,
                ) ,
                array (
                    'title'             => esc_html__ ( 'Character Count' , HRW_LOCALE ) ,
                    'desc'              => esc_html__ ( 'character count excluding prefix and suffix' , HRW_LOCALE ) ,
                    'id'                => $this->get_field_key ( 'code_char_count' ) ,
                    'type'              => 'number' ,
                    'default'           => 10 ,
                    'custom_attributes' => array ( 'min' => '0' ) ,
                    'desc'              => esc_html__ ( 'Character count excluding prefix and suffix' , HRW_LOCALE )
                ) ,
                array (
                    'title'   => esc_html__ ( 'Prefix' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key ( 'code_prefix' ) ,
                    'type'    => 'text' ,
                    'default' => '' ,
                ) ,
                array (
                    'title'   => esc_html__ ( 'Suffix' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key ( 'code_suffix' ) ,
                    'type'    => 'text' ,
                    'default' => '' ,
                ) ,
                array (
                    'type' => 'sectionend' ,
                    'id'   => 'wallet_gift_code_settings' ,
                ) ,
                array (
                    'type'  => 'title' ,
                    'title' => esc_html__ ( 'Gift Card File Name Settings' , HRW_LOCALE ) ,
                    'id'    => 'wallet_gift_statement_settings' ,
                ) ,
                array (
                    'title'   => esc_html__ ( 'File Name Type' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key ( 'file_name_method' ) ,
                    'type'    => 'select' ,
                    'default' => '1' ,
                    'options' => array (
                        '1' => esc_html__ ( 'Default' , HRW_LOCALE ) ,
                        '2' => esc_html__ ( 'Advanced' , HRW_LOCALE ) ,
                    ) ,
                ) ,
                array (
                    'title'   => esc_html__ ( 'Format' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key ( 'default_format' ) ,
                    'type'    => 'text' ,
                    'default' => 'Y-m-d @ H.i.s' ,
                    'desc'    => esc_html__ ( 'Y Year , m Month , d Day , h Hours , i Minutes , s Seconds' , HRW_LOCALE ) ,
                ) ,
                array (
                    'title'             => esc_html__ ( 'Character Count' , HRW_LOCALE ) ,
                    'desc'              => esc_html__ ( 'character count excluding prefix and suffix' , HRW_LOCALE ) ,
                    'id'                => $this->get_field_key ( 'char_count' ) ,
                    'class'             => $this->get_field_key ( 'advanced_fields' ) ,
                    'type'              => 'number' ,
                    'default'           => 10 ,
                    'custom_attributes' => array ( 'min' => '0' ) ,
                    'desc'              => esc_html__ ( 'Character count excluding prefix and sufix' , HRW_LOCALE )
                ) ,
                array (
                    'title'   => esc_html__ ( 'File Name Prefix' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key ( 'file_prefix' ) ,
                    'class'   => $this->get_field_key ( 'advanced_fields' ) ,
                    'type'    => 'text' ,
                    'default' => '' ,
                ) ,
                array (
                    'title'   => esc_html__ ( 'File Name Suffix' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key ( 'file_suffix' ) ,
                    'class'   => $this->get_field_key ( 'advanced_fields' ) ,
                    'type'    => 'text' ,
                    'default' => '' ,
                ) ,
                array (
                    'title'             => esc_html__ ( 'Sequence Starting Number' , HRW_LOCALE ) ,
                    'desc'              => esc_html__ ( 'If prefix is given, then this number will come after prefix' , HRW_LOCALE ) ,
                    'id'                => $this->get_field_key ( 'sequence_number' ) ,
                    'class'             => $this->get_field_key ( 'advanced_fields' ) ,
                    'type'              => 'number' ,
                    'default'           => 1 ,
                    'custom_attributes' => array ( 'min' => '0' ) ,
                    'desc'              => esc_html__ ( 'If prefix is given, then this number will come after prefix' , HRW_LOCALE )
                ) ,
                array (
                    'type' => 'sectionend' ,
                    'id'   => 'wallet_gift_statement_settings' ,
                ) ,
                array (
                    'type'  => 'title' ,
                    'title' => esc_html__ ( 'Gift Card PDF Settings' , HRW_LOCALE ) ,
                    'id'    => 'wallet_gift_pdf_settings' ,
                ) ,
                array (
                    'title'   => esc_html__ ( 'Logo' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key ( 'logo_image_url' ) ,
                    'type'    => 'file_upload_button' ,
                    'default' => esc_html__ ( 'Browse' , HRW_LOCALE ) ,
                ) ,
                array (
                    'title'             => esc_html__ ( 'Logo Maximum Width(in %)' , HRW_LOCALE ) ,
                    'id'                => $this->get_field_key ( 'logo_max_percent' ) ,
                    'type'              => 'number' ,
                    'custom_attributes' => array ( 'min' => '0' , 'max' => '100' )
                ) ,
                array (
                    'title'   => esc_html__ ( 'Admin Details Section Heading' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key ( 'admin_section_heading' ) ,
                    'type'    => 'text' ,
                    'default' => '' ,
                ) , array (
                    'title'   => esc_html__ ( 'Admin Details' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key ( 'admin_deatils' ) ,
                    'type'    => 'textarea' ,
                    'default' => '' ,
                ) ,
                array (
                    'title'   => esc_html__ ( 'Sender Details Section Heading' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key ( 'sender_section_heading' ) ,
                    'type'    => 'text' ,
                    'default' => '' ,
                ) ,
                array (
                    'type' => 'sectionend' ,
                    'id'   => 'wallet_gift_pdf_settings' ,
                ) ,
                array (
                    'type'  => 'title' ,
                    'title' => esc_html__ ( 'Messages' , HRW_LOCALE ) ,
                    'id'    => 'hrw_gift_msgs_settings' ,
                ) ,
                array (
                    'type'  => 'subtitle' ,
                    'title' => esc_html__ ( 'Gift Card Form Messages' , HRW_LOCALE ) ,
                    'id'    => 'hrw_gift_form_msgs_settings' ,
                ) ,
                array (
                    'title'   => esc_html__ ( 'Error Message to display when a user enters the invalid email id' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key ( 'invalid_email_err' ) ,
                    'type'    => 'text' ,
                    'default' => 'Please enter the valid email id' ,
                ) ,
                array (
                    'title'   => esc_html__ ( "Error Message to display when a user didn't enter the name" , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key ( 'empty_name_err' ) ,
                    'type'    => 'text' ,
                    'default' => 'Please enter the Name' ,
                ) ,
                array (
                    'title'   => esc_html__ ( "Error Message to display when the user didn't enter the amount" , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key ( 'empty_amnt_err' ) ,
                    'type'    => 'textarea' ,
                    'default' => 'Please enter the amount' ,
                ) ,
                array (
                    'title'   => esc_html__ ( 'Error Message to display when a user enters less than Minimum Amount configured' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key ( 'min_amnt_err' ) ,
                    'type'    => 'textarea' ,
                    'default' => 'Please enter an amount more than [minimum_amount]' ,
                ) ,
                array (
                    'title'   => esc_html__ ( 'Error Message to display when the user enters more than Maximum Amount configured' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key ( 'max_amnt_err' ) ,
                    'type'    => 'textarea' ,
                    'default' => 'Please enter an amount less than [maximum_amount]' ,
                ) ,
                array (
                    'type'  => 'subtitle' ,
                    'title' => esc_html__ ( 'Gift Card Redeem Messages' , HRW_LOCALE ) ,
                    'id'    => 'hrw_redeem_msgs_settings' ,
                ) ,
                array (
                    'title'   => esc_html__ ( 'Error Message to display when a user enters the invalid Gift Card' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key ( 'invalid_code_err' ) ,
                    'type'    => 'textarea' ,
                    'default' => 'Please enter the valid gift card' ,
                ) ,
                array (
                    'title'   => esc_html__ ( 'Error Message to display when a user tries to redeem the Gift Card during maximum wallet threshold reaching' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key ( 'max_threshold_reaching_err' ) ,
                    'type'    => 'textarea' ,
                    'default' => 'You will be reaching the maximum threshold[threshold_amount] balance to hold on your wallet by using this gift card. Hence, you cannot redeem.' ,
                ) ,
                array (
                    'title'   => esc_html__ ( 'Error Message to display when a user tries to redeem the Gift Card during maximum wallet threshold reached' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key ( 'max_threshold_reached_err' ) ,
                    'type'    => 'textarea' ,
                    'default' => 'You have already reached the maximum threshold [threshold_amount] balance to hold on your wallet. Hence, you cannot redeem this gift card.' ,
                ) ,
                array (
                    'title'   => esc_html__ ( 'Message to display when a user successfully redeem the Gift Card' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key ( 'redeem_success_msg' ) ,
                    'type'    => 'textarea' ,
                    'default' => 'You have successfully redeemed the gift card and [amount] has added to your wallet' ,
                ) ,
                array (
                    'type' => 'sectionend' ,
                    'id'   => 'hrw_gift_msgs_settings' ,
                ) ,
                array (
                    'type'  => 'title' ,
                    'title' => esc_html__ ( 'Localization' , HRW_LOCALE ) ,
                    'id'    => 'hrw_gift_localization_settings' ,
                ) ,
                array (
                    'title'   => esc_html__ ( 'Gift Card Redeemed Successfully' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key ( 'redeem_event_msg' ) ,
                    'type'    => 'textarea' ,
                    'default' => 'Funds credited to your wallet for using Gift Card' ,
                ) ,
                array (
                    'type' => 'sectionend' ,
                    'id'   => 'hrw_gift_localization_settings' ,
                ) ,
                    ) ;
        }

        /*
         * Actions
         */

        public function actions() {
            add_action ( 'hrw_update_wallet_block' , array ( 'HRW_Cron_Handler' , 'do_all_wallet_block' ) ) ;

            $gift_order_statuses = $this->topup_order_status ;

            if ( hrw_check_is_array ( $gift_order_statuses ) ) {
                foreach ( $gift_order_statuses as $gift_status )
                    add_action ( "woocommerce_order_status_{$gift_status}" , array ( $this , 'generate_gift_card' ) , 1 ) ;
            }

            add_action ( 'wp_ajax_hrw_create_gift_product' , array ( $this , 'create_gift_product' ) ) ;
        }

        /*
         * Frontend Actions
         */

        public function frontend_action() {
//Displaying Menu and Content in Dashboard
            add_filter ( 'hrw_frontend_dashboard_menu' , array ( $this , 'adding_menu' ) , 10 , 1 ) ;
//Add Gift card submenu
            add_filter ( 'hrw_frontend_dashboard_gift_card_submenus' , array ( $this , 'add_gift_card_submenu' ) , 10 , 1 ) ;
            add_action ( 'hrw_frontend_dashboard_menu_content_gift_generate_form' , array ( $this , 'gift_form_content' ) ) ;
            add_action ( 'hrw_after_gift_card_form' , array ( $this , 'display_sent_table' ) ) ;
            add_action ( 'hrw_frontend_dashboard_menu_content_gift_redeem_form' , array ( $this , 'gift_redeem_content' ) ) ;
            add_action ( 'hrw_after_gift_card_redeem_form' , array ( $this , 'display_received_table' ) ) ;

//Add to cart Validation
            add_filter ( 'woocommerce_add_to_cart_validation' , array ( __CLASS__ , 'validate_other_product_add_to_cart' ) , 10 , 5 ) ;
//set price for topup product
            add_action ( 'woocommerce_before_calculate_totals' , array ( __CLASS__ , 'set_price' ) , 1 , 1 ) ;
//set cart quantity as 1 in cart page
            add_filter ( 'woocommerce_cart_item_quantity' , array ( __CLASS__ , 'set_cart_item_quantity' ) , 10 , 2 ) ;
//update order meta
            add_action ( 'woocommerce_checkout_update_order_meta' , array ( __CLASS__ , 'update_order_meta' ) ) ;
//Gift Card Form Process
            add_action ( 'wp_loaded' , array ( $this , 'process_gift_card_form' ) ) ;
//Gift Card Redeem Process
            add_action ( 'wp_loaded' , array ( $this , 'process_gift_card_redeem' ) ) ;
//handle payment gateways for wallet usage
            add_filter ( 'woocommerce_available_payment_gateways' , array ( $this , 'handle_payment_gateways' ) , 10 , 1 ) ;
        }

        public function admin_external_js_files() {
            wp_enqueue_media () ;
            wp_enqueue_script ( 'hrw-gift-card' , HRW_PLUGIN_URL . '/premium/assets/js/admin/gift-card.js' , array ( 'jquery' , 'blockUI' ) , HRW_VERSION ) ;
            wp_localize_script (
                    'hrw-gift-card' , 'hrw_gift_card_args' , array (
                'gift_product_nonce' => wp_create_nonce ( 'hrw-gift-product-nonce' ) ,
                    )
            ) ;
        }

        public function frontend_external_js_files() {
            wp_enqueue_script ( 'hrw-gift-card-frontend' , HRW_PLUGIN_URL . '/premium/assets/js/frontend/gift-card.js' , array ( 'jquery' , 'blockUI' ) , HRW_VERSION ) ;
        }

        /**
         * Gift Product Creation
         */
        public static function create_gift_product() {

            check_ajax_referer ( 'hrw-gift-product-nonce' , 'hrw_security' ) ;

            try {
                if ( ! isset ( $_REQUEST ) || ! isset ( $_REQUEST[ 'gift_product_name' ] ) )
                    throw new exception ( esc_html__ ( 'Invalid Request' , HRW_LOCALE ) ) ;

                $product_id = hrw_create_new_wallet_product ( hrw_sanitize_text_field ( $_POST[ 'gift_product_name' ] ) ) ;

                update_option ( 'hrw_gift_card_gift_product_type' , '2' ) ;
                update_option ( 'hrw_gift_card_gift_product_id' , array ( $product_id ) ) ;
                update_post_meta ( $product_id , 'hrw_gift_product' , 'yes' ) ;

                wp_send_json_success ( array ( 'content' => 'success' ) ) ;
            } catch ( Exception $ex ) {
                wp_send_json_error ( array ( 'error' => $ex->getMessage () ) ) ;
            }
        }

        public function adding_menu( $menus ) {

            $menus[ 'gift_card' ] = array (
                'label' => esc_html__ ( 'Gift Card' , HRW_LOCALE ) ,
                'code'  => 'fa fa-gift'
                    ) ;
            return $menus ;
        }

        /*
         * Add Wallet Gift Card Dashboard Submenu
         */

        public function add_gift_card_submenu( $submenus ) {
            $submenus[ 'gift_generate_form' ] = get_option ( 'hrw_dashboard_customization_gift_generate_form_label' , 'Gift Card Form' ) ;
            $submenus[ 'gift_redeem_form' ]   = get_option ( 'hrw_dashboard_customization_gift_redeem_form_label' , 'Gift Card Redeem Form' ) ;
            return $submenus ;
        }

        /*
         * Displaying Gift Generating Form Content
         */

        public function gift_form_content() {

            $gift_field_type  = $this->amount_type ;
            $prefilled_amount = $this->predefined_amount ? explode ( ',' , $this->predefined_amount ) : '' ;
            $min_gift         = $this->min_amount ? $this->min_amount : 0 ;
            $max_gift         = $this->max_amount ;
            $ready_only       = ($gift_field_type == '3') ? ' readonly="readonly"' : '' ;

            $form_args = array (
                'gift_field_type'  => $gift_field_type ,
                'prefilled_amount' => $prefilled_amount ,
                'min_gift'         => $min_gift ,
                'max_gift'         => $max_gift ,
                'ready_only'       => $ready_only ,
                    ) ;

            hrw_get_template ( 'dashboard/gift-card-form.php' , true , $form_args ) ;
        }

        /*
         * Displaying Gift Redeeming Form Content
         */

        public function gift_redeem_content() {

            //validation for maximum wallet balance for user
            $thresholed_value = ( float ) get_option ( 'hrw_general_topup_maximum_wallet_balance' ) ;
            if ( ! empty ( $thresholed_value ) && (HRW_Wallet_User::get_available_balance () ) > $thresholed_value ) {
                $prepared_notice = str_replace ( '[threshold_amount]' , hrw_price ( $thresholed_value ) , $this->max_threshold_reached_err ) ;
                echo esc_html__ ( $prepared_notice ) ;
            }
            hrw_get_template ( 'dashboard/gift-card-redeem.php' , true ) ;
        }

        /*
         * Displaying Gift Listing Content
         */

        public function display_received_table() {

            $data_args = self::get_gift_table_data ( 'received' ) ;

            hrw_get_template ( 'dashboard/gift-card-listing-received.php' , true , $data_args ) ;
        }

        public static function get_gift_table_data( $type ) {
            $per_page     = 5 ;
            $current_page = self::get_current_page_number () ;

            /* Calculate Page Count */
            $overall_count                  = hrw_get_gift_ids_by ( $type , get_current_user_id () ) ;
            $page_count                     = ceil ( count ( $overall_count ) / $per_page ) ;
            $extra_args                     = array () ;
            $extra_args[ 'offset' ]         = ($current_page - 1) * $per_page ;
            $extra_args[ 'posts_per_page' ] = $per_page ;


            $data_args = array (
                'table_datas'   => hrw_get_gift_ids_by ( $type , get_current_user_id () , $extra_args ) ,
                'serial_number' => ( $current_page * $per_page ) - $per_page + 1 ,
                'pagination'    => array (
                    'page_count'      => $page_count ,
                    'current_page'    => $current_page ,
                    'next_page_count' => (($current_page + 1) > ($page_count - 1)) ? ($current_page) : ($current_page + 1) ,
                ) ) ;

            return $data_args ;
        }

        public function display_sent_table() {
            $data_args = self::get_gift_table_data ( 'sent' ) ;
            hrw_get_template ( 'dashboard/gift-card-listing-sent.php' , true , $data_args ) ;
        }

        /**
         * Get current page number
         */
        public static function get_current_page_number() {

            return isset ( $_REQUEST[ 'page_no' ] ) && absint ( $_REQUEST[ 'page_no' ] ) ? absint ( $_REQUEST[ 'page_no' ] ) : 1 ;
        }

        /*
         * Set Gift Product
         */

        public function set_gift_product() {
            $gift_product = array_filter ( get_option ( 'hrw_gift_card_gift_product_id' , array () ) ) ;

            self::$hrw_gift_product = reset ( $gift_product ) ;
        }

        /*
         * Set custom price for topup product 
         */

        public static function set_price( $cart_object ) {

            foreach ( $cart_object->cart_contents as $key => $value ) {
                if ( ! isset ( $value[ 'hrw_gift_card' ] ) )
                    continue ;

                if ( self::$hrw_gift_product != $value[ 'hrw_gift_card' ][ 'product_id' ] )
                    continue ;

                $value[ 'data' ]->set_price ( $value[ 'hrw_gift_card' ][ 'price' ] ) ;
            }
        }

        /*
         * Set cart quantity as 1 in cart page
         */

        public static function set_cart_item_quantity( $quantity , $cart_item_key ) {
            $cart_items = WC ()->cart->get_cart () ;

            if ( ! isset ( $cart_items[ $cart_item_key ][ 'hrw_gift_card' ] ) )
                return $quantity ;

            return 1 ;
        }

        /*
         * validate other product add to cart
         */

        public static function validate_other_product_add_to_cart( $passed ) {

            if ( ! hrw_gift_product_in_cart () )
                return $passed ;

            wc_add_notice ( esc_html__ ( 'Cannot add Other Product While Gift Product in Cart' , HRW_LOCALE ) , 'error' ) ;

            return false ;
        }

        /*
         * Update Order Meta
         */

        public static function update_order_meta( $order_id ) {

            foreach ( WC ()->cart->get_cart () as $key => $value ) {
                if ( ! isset ( $value[ 'hrw_gift_card' ] ) )
                    continue ;

                if ( self::$hrw_gift_product != $value[ 'hrw_gift_card' ][ 'product_id' ] )
                    continue ;

                update_post_meta ( $order_id , 'hr_wallet_gift_fund' , $value[ 'hrw_gift_card' ][ 'price' ] ) ;
                update_post_meta ( $order_id , 'hr_wallet_gift_receiver' , $value[ 'hrw_gift_card' ][ 'receiver' ] ) ;
                update_post_meta ( $order_id , 'hr_wallet_gift_receiver_name' , $value[ 'hrw_gift_card' ][ 'receiver_name' ] ) ;
                update_post_meta ( $order_id , 'hr_wallet_gift_product' , $value[ 'hrw_gift_card' ][ 'product_id' ] ) ;
                update_post_meta ( $order_id , 'hr_wallet_gift' , $value[ 'hrw_gift_card' ] ) ;
            }
        }

        /*
         * Debit wallet amount From Sender Wallet
         */

        public function generate_gift_card( $order_id ) {

//return if Gift Debit process completed for this order.
            $already_generated = get_post_meta ( $order_id , 'hrw_gift_generated' , true ) ;

            if ( $already_generated == 'yes' )
                return ;
//return if order is not placed by Gift Process

            $gift_data = get_post_meta ( $order_id , 'hr_wallet_gift' , true ) ;

            if ( empty ( $gift_data ) )
                return ;

            $order       = wc_get_order ( $order_id ) ;
            $gift_amount = $gift_data[ 'price' ] ;
            $expiry_date = $this->expiry_days ? date ( 'Y-m-d H:i:s' , strtotime ( current_time ( 'mysql' , true ) . ' +' . $this->expiry_days . 'days' ) ) : '' ;

//Preparing Gift Post Enrty
            $meta_args = array (
                'hrw_gift_code'     => $this->get_gift_code () ,
                'hrw_amount'        => $gift_amount ,
                'hrw_sender_id'     => $order->get_user_id () ,
                'hrw_receiver_id'   => $gift_data[ 'receiver' ] ,
                'hrw_receiver_name' => $gift_data[ 'receiver_name' ] ,
                'hrw_gift_reason'   => $gift_data[ 'gift_reason' ] ,
                'hrw_order_id'      => $order_id ,
                'hrw_created_date'  => current_time ( 'mysql' , true ) ,
                'hrw_expiry_date'   => $expiry_date ,
                    ) ;

            $gift_id = hrw_create_new_gift ( $meta_args , array ( 'post_status' => 'hrw_created' , 'post_author' => $order->get_user_id () ) ) ;

//Generatin PDF
            self::generate_gift_card_pdf ( $gift_id ) ;

            update_post_meta ( $order_id , 'hrw_gift_generated' , 'yes' ) ;

            do_action ( 'hrw_after_gift_card_created' , $gift_id ) ;
        }

        /*
         * Set Statment File Name
         */

        public function get_gift_file_name() {
            if ( $this->file_name_method == '1' ) {
                $statement_name = 'Wallet Gift Card-' . date ( $this->default_format , current_time ( 'timestamp' ) ) ;
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
         * Get Gift Code
         */

        public function get_gift_code() {
            $args      = array (
                'character_type'    => $this->code_type ,
                'exclude_alphbates' => $this->exclude_alphbates ,
                'length'            => $this->code_char_count ,
                'prefix'            => $this->code_prefix ,
                'suffix'            => $this->code_suffix ,
                    ) ;
            $gift_code = hrw_generate_random_codes ( $args ) ;

            return $gift_code ;
        }

        /**
         * Generate Gift Card PDF
         */
        public function generate_gift_card_pdf( $gift_id ) {
// set pdf file name
            $gift_file_name = $this->get_gift_file_name () ;
// construct pdf
            ob_start () ;
            $pdf            = new HRWP_Wallet_Gift_Card_PDF ( $gift_id , $gift_file_name ) ;
            $gift_card_file = $pdf->generate_pdf () ;

            hrw_update_gift ( $gift_id , array ( 'hrw_gift_attachment' => $gift_card_file ) ) ;
// Update pdf sequence number
            $this->update_option ( 'sequence_number' , (( int ) $this->sequence_number) + 1 ) ;
        }

        /**
         * Process Gift Card Form
         */
        public function process_gift_card_form() {

            if ( ! isset ( $_POST[ 'hrw_gift' ] ) ) {
                return ;
            }

            try {

                if ( ( isset ( $_POST[ 'hrw-gift-submit-nonce' ] ) && ! wp_verify_nonce ( $_POST[ 'hrw-gift-submit-nonce' ] , 'hrw-gift-submit' ) ) ) {
                    throw new Exception ( __ ( "Invalid Request" , HRW_LOCALE ) ) ;
                }

                $gift_data = $_POST[ 'hrw_gift' ] ;

                if ( empty ( $gift_data[ 'receiver' ] ) ) {
                    throw new Exception ( __ ( $this->invalid_email_err , HRW_LOCALE ) ) ;
                }

                if ( empty ( $gift_data[ 'receiver_name' ] ) ) {
                    throw new Exception ( __ ( $this->empty_name_err , HRW_LOCALE ) ) ;
                }

                if ( empty ( $gift_data[ 'amount' ] ) ) {
                    throw new Exception ( __ ( $this->empty_amnt_err , HRW_LOCALE ) ) ;
                }

                if ( $this->amount_type == 3 && $gift_data[ 'gift_card_select' ] == 'user-defined' && $gift_data[ 'amount' ] < $this->min_amount ) {
                    throw new Exception ( str_replace ( '[minimum_amount]' , $this->min_amount , $this->min_amnt_err ) ) ;
                }

                if ( $this->amount_type == 3 && $gift_data[ 'gift_card_select' ] == 'user-defined' && ! empty ( $this->max_amount ) && $gift_data[ 'amount' ] > $this->max_amount ) {
                    throw new Exception ( str_replace ( '[maximum_amount]' , $this->max_amount , $this->max_amnt_err ) ) ;
                }

                if ( ($this->amount_type == '1' ) && ! empty ( $this->min_amount ) && $gift_data[ 'amount' ] < $this->min_amount ) {
                    throw new Exception ( str_replace ( '[minimum_amount]' , $this->min_amount , $this->min_amnt_err ) ) ;
                }
                if ( ( $this->amount_type == '1' ) && ! empty ( $this->max_amount ) && $gift_data[ 'amount' ] > $this->max_amount ) {
                    throw new Exception ( str_replace ( '[maximum_amount]' , $this->max_amount , $this->max_amnt_err ) ) ;
                }

                $cart_item_data = array (
                    'hrw_gift_card' => array (
                        'price'         => $gift_data[ 'amount' ] ,
                        'receiver'      => $gift_data[ 'receiver' ] ,
                        'receiver_name' => $gift_data[ 'receiver_name' ] ,
                        'gift_reason'   => $gift_data[ 'reason' ] ,
                        'product_id'    => HRW_GiftCard::$hrw_gift_product ,
                        'gift_data'     => $gift_data ,
                    )
                        ) ;

//Remove previous cart
                WC ()->cart->empty_cart () ;
//Topup product in cart
                WC ()->cart->add_to_cart ( HRW_GiftCard::$hrw_gift_product , '1' , 0 , array () , $cart_item_data ) ;
//Redirect to checkout page
                wp_safe_redirect ( wc_get_checkout_url () ) ;
                exit () ;
            } catch ( Exception $ex ) {
                HRW_Form_Handler::add_error ( $ex->getMessage () ) ;
            }
        }

        /**
         * Process Gift Card Redeem
         */
        public function process_gift_card_redeem() {

            if ( ! isset ( $_POST[ 'hrw_gift_redeem' ] ) ) {
                return ;
            }

            try {
                if ( ( isset ( $_POST[ 'hrw-gift-redeem-nonce' ] ) && ! wp_verify_nonce ( $_POST[ 'hrw-gift-redeem-nonce' ] , 'hrw-gift-redeem' ) ) ) {
                    throw new Exception ( __ ( "Invalid Request" , HRW_LOCALE ) ) ;
                }

                $gift_id = hrw_get_gift_ids_by ( 'code' , hrw_sanitize_text_field ( $_POST[ 'hrw_gift_redeem' ] ) ) ;

                if ( empty ( $gift_id ) ) {
                    throw new Exception ( esc_html__ ( $this->invalid_code_err ) ) ;
                }

                $gift_obj = hrw_get_gift ( reset ( $gift_id ) ) ;

                if ( (hrw_get_user_mail_by ( 'id' , get_current_user_id () ) != $gift_obj->get_receiver_id () ) || $gift_obj->get_status () != 'hrw_created' ) {
                    throw new Exception ( esc_html__ ( $this->invalid_code_err ) ) ;
                }

                $order = wc_get_order ( $gift_obj->get_order_id () ) ;
                //Gift Order Check - Some Exceptional Cases
                if ( in_array ( $order->get_status () , array ( 'cancelled' , 'refunded' , 'failed' ) ) ) {
                    throw new Exception ( esc_html__ ( $this->invalid_code_err ) ) ;
                }

                $current_date = strtotime ( current_time ( 'timestamp' ) ) ;
                if ( $gift_obj->get_expiry_date () && strtotime ( $gift_obj->get_expiry_date () ) < $current_date ) {
                    //updating this code as expired
                    hrw_update_gift ( reset ( $gift_id ) , array () , array ( 'post_status' => 'hrw_expired' ) ) ;
                    throw new Exception ( esc_html__ ( 'Code Expired' ) ) ;
                }

                //Validation for maximum wallet balance for user
                $thresholed_value = ( float ) get_option ( 'hrw_general_topup_maximum_wallet_balance' ) ;
                if ( ! empty ( $thresholed_value ) && (HRW_Wallet_User::get_available_balance () + $gift_obj->get_amount ()) > $thresholed_value ) {
                    throw new Exception ( str_replace ( '[threshold_amount]' , hrw_price ( $thresholed_value ) , $this->max_threshold_reaching_err ) ) ;
                }

                $credit_args = array (
                    'wallet_id' => get_current_user_id () ,
                    'amount'    => $gift_obj->get_amount () ,
                    'event'     => $this->redeem_event_msg ,
                    'currency'  => get_woocommerce_currency ()
                        ) ;

                HRW_Credit_Debit_Handler::credit_amount_to_wallet ( $credit_args ) ;

                hrw_update_gift ( reset ( $gift_id ) , array ( 'hrw_redeemed_date' => current_time ( 'mysql' , true ) , ) , array ( 'post_status' => 'hrw_redeemed' ) ) ;

                do_action ( 'hrw_after_gift_card_redeemed' , reset ( $gift_id ) ) ;

                $success_msg = str_replace ( '[amount]' , hrw_price ( $gift_obj->get_amount () ) , $this->redeem_success_msg ) ;

                HRW_Form_Handler::add_message ( __ ( $success_msg ) ) ;
            } catch ( Exception $ex ) {
                HRW_Form_Handler::add_error ( $ex->getMessage () ) ;
            }
        }

        /*
         * Display Geft Gateways - Configure by admin
         */

        public function handle_payment_gateways( $wc_gateways ) {
            if ( ! hrw_gift_product_in_cart () ) {
                return $wc_gateways ;
            }

            if ( array_key_exists ( 'HR_Wallet_Gateway' , $wc_gateways ) )
                unset ( $wc_gateways[ 'HR_Wallet_Gateway' ] ) ;

            $restricted_gatways = $this->gift_gateways ;
            if ( hrw_check_is_array ( $wc_gateways ) ) {
                foreach ( $wc_gateways as $gateway_id => $gateways ) {

                    if ( in_array ( $gateway_id , $restricted_gatways ) )
                        continue ;

                    unset ( $wc_gateways[ $gateway_id ] ) ;
                }
            }
            return $wc_gateways ;
        }

        /*
         * Extra Fields
         */

        public function extra_fields() {

            if ( ! class_exists ( 'HRW_Gift_Card_Table' ) )
                require_once( HRW_PLUGIN_PATH . '/premium/inc/admin/wp-list-table/class-hrw-gift-card-table.php' ) ;

            echo '<div class="' . $this->plugin_slug . '_table_wrap">' ;
            $post_table = new HRW_Gift_Card_Table () ;
            $post_table->prepare_items () ;
            $post_table->views () ;
            $post_table->display () ;
            echo '</div>' ;
        }

    }

}