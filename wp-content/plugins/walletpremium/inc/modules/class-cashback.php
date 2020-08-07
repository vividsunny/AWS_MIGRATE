<?php
/**
 * Cashback
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'HRW_Cashback_Module' ) ) {

    /**
     * Class HRW_Cashback_Module
     */
    class HRW_Cashback_Module extends HRW_Modules {
        /*
         * Data
         */

        protected $data = array (
            'enabled'                       => 'no' ,
            'issue_type'                    => 1 ,
            'promocode'                     => '' ,
            'order_status'                  => array ( 'completed' ) ,
            'rule_priority'                 => 1 ,
            'promocode_notice'              => 'To Get [cashback_value] Cashback, Apply' ,
            'order_total_msg'               => 'By completing this order, cashback of [cashback_value] will credit to your wallet.' ,
            'wallet_topup_msg'              => 'By completing this order, cashback of [cashback_value] will credit to your wallet.' ,
            'amnt_credited_for_order_total' => 'Cashback received toward purchase for Order [order_id]' ,
            'amnt_revised_for_order_total'  => 'Cashback revised for Order [order_id]' ,
            'amnt_credited_for_topup'       => 'Cashback received towards Wallet Top-up for [topup_amount]' ,
            'amnt_revised_for_topup'        => 'Cashback revised for Wallet Top-up for [topup_amount]' ,
                ) ;

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'cashback' ;
            $this->title = esc_html__( 'Cashback' , HRW_LOCALE ) ;

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
            return array (
                array (
                    'type'  => 'title' ,
                    'title' => esc_html__( 'Cashback Settings' , HRW_LOCALE ) ,
                    'id'    => 'cashback_settings' ,
                ) ,
                array (
                    'title'   => esc_html__( 'Cashback is Issued' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key( 'issue_type' ) ,
                    'type'    => 'select' ,
                    'default' => '1' ,
                    'options' => array (
                        '1' => esc_html__( 'Without Promocode' , HRW_LOCALE ) ,
                        '2' => esc_html__( 'With Promocode' , HRW_LOCALE ) ,
                    ) ,
                ) ,
                array (
                    'title'   => esc_html__( 'Promocode' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key( 'promocode' ) ,
                    'type'    => 'text' ,
                    'default' => '' ,
                ) ,
                array (
                    'title'   => esc_html__( 'Rule Priority' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key( 'rule_priority' ) ,
                    'type'    => 'select' ,
                    'default' => '1' ,
                    'options' => array (
                        '1' => esc_html__( 'First Matched Rule' , HRW_LOCALE ) ,
                        '2' => esc_html__( 'Last Matched Rule' , HRW_LOCALE ) ,
                        '3' => esc_html__( 'Minimum Cashback Value' , HRW_LOCALE ) ,
                        '4' => esc_html__( 'Maximum Cashback Value' , HRW_LOCALE ) ,
                    ) ,
                ) ,
                array (
                    'title'   => esc_html__( 'Order Status to Issue Cashback' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key( 'order_status' ) ,
                    'type'    => 'multiselect' ,
                    'class'   => 'hrw_select2' ,
                    'default' => array ( 'completed' ) ,
                    'options' => hrw_get_paid_order_statuses() ,
                    'desc'    => esc_html__( 'Cashbacks will be issued to users when the order reaches any one of the status set in this option' , HRW_LOCALE )
                ) ,
                array (
                    'type' => 'sectionend' ,
                    'id'   => 'cashback_settings' ,
                ) ,
                array (
                    'type'  => 'title' ,
                    'title' => esc_html__( 'Message Settings' , HRW_LOCALE ) ,
                    'id'    => 'cashback_msg_settings' ,
                ) ,
                array (
                    'title'   => esc_html__( 'Message to display in Promocode Notice' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key( 'promocode_notice' ) ,
                    'type'    => 'textarea' ,
                    'default' => 'To Get [cashback_value] Cashback, Apply' ,
                ) ,
                array (
                    'title'   => esc_html__( 'Message to display in Cart and Checkout for Order Total Cashback' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key( 'order_total_msg' ) ,
                    'type'    => 'textarea' ,
                    'default' => 'By completing this order, cashback of [cashback_value] will credit to your wallet.' ,
                    'desc'    => esc_html__( 'By completing this order, cashback of [cashback_value] will be credited to your wallet.' , HRW_LOCALE )
                ) ,
                array (
                    'title'   => esc_html__( 'Message to display in Cart and Checkout for Wallet Top-up' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key( 'wallet_topup_msg' ) ,
                    'type'    => 'textarea' ,
                    'default' => 'By completing this order, cashback of [cashback_value] will credit to your wallet.' ,
                    'desc'    => esc_html__( 'By completing this Top-up, cashback of [cashback_value] will be credited to your wallet.' , HRW_LOCALE )
                ) ,
                array (
                    'type' => 'sectionend' ,
                    'id'   => 'cashback_msg_settings' ,
                ) ,
                array (
                    'type'  => 'title' ,
                    'title' => esc_html__( 'Localization Settings' , HRW_LOCALE ) ,
                    'id'    => 'cashback_localization_settings' ,
                ) ,
                array (
                    'title'   => esc_html__( 'Order Total Cashback Credited Log' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key( 'amnt_credited_for_order_total' ) ,
                    'type'    => 'textarea' ,
                    'default' => 'Cashback received toward purchase for Order [order_id]' ,
                ) ,
                array (
                    'title'   => esc_html__( 'Order Total Cashback Revised Log' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key( 'amnt_revised_for_order_total' ) ,
                    'type'    => 'textarea' ,
                    'default' => 'Cashback revised for Order [order_id]'
                ) ,
                array (
                    'title'   => esc_html__( 'Wallet Top-up Cashback Credited Log' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key( 'amnt_credited_for_topup' ) ,
                    'type'    => 'textarea' ,
                    'default' => 'Cashback received towards Wallet Top-up for [topup_amount]' ,
                ) ,
                array (
                    'title'   => esc_html__( 'Wallet Top-up Cashback Revised Log' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key( 'amnt_revised_for_topup' ) ,
                    'type'    => 'textarea' ,
                    'default' => 'Cashback revised for Wallet Top-up for [topup_amount]' ,
                ) ,
                array (
                    'type' => 'sectionend' ,
                    'id'   => 'cashback_localization_settings' ,
                ) ,
                    ) ;
        }

        /*
         * Actions
         */

        public function actions() {
            if ( hrw_check_is_array( $this->order_status ) ) {
                foreach ( $this->order_status as $order_status )
                    add_action( "woocommerce_order_status_{$order_status}" , array ( $this , 'credit_cashback' ) , 1 ) ;

                add_action( 'woocommerce_order_status_refunded' , array ( $this , 'debit_cashback' ) , 1 ) ;
                add_action( 'woocommerce_order_status_cancelled' , array ( $this , 'debit_cashback' ) , 1 ) ;
                add_action( 'woocommerce_order_status_failed' , array ( $this , 'debit_cashback' ) , 1 ) ;
            }
        }

        /*
         * Admin action
         */

        public function admin_action() {
            add_action( $this->plugin_slug . '_section_cashback_localization_settings_after_end' , array ( $this , 'display_rules' ) ) ;
            add_action( 'wp_ajax_hrw_add_cashback_rule' , array ( $this , 'add_cashback_rule' ) ) ;
            add_action( 'wp_ajax_hrw_add_cashback_order_rule' , array ( $this , 'add_cashback_order_rule' ) ) ;
            add_action( 'wp_ajax_hrw_add_cashback_wallet_rule' , array ( $this , 'add_cashback_wallet_rule' ) ) ;
            add_action( 'wp_ajax_hrw_delete_cashback_rule' , array ( $this , 'delete_cashback_rule' ) ) ;
        }

        /*
         * Frontend action
         */

        public function frontend_action() {
            //Add fund transfer dashboard menu
            add_filter( 'hrw_frontend_dashboard_menu' , array ( $this , 'add_dashboard_menu' ) , 10 , 1 ) ;
            //Dashboard Menu content
            add_action( 'hrw_frontend_dashboard_menu_content_cashback' , array ( $this , 'render_cashback' ) ) ;
            //Show Promocode Field in Cart
            add_action( 'woocommerce_before_cart_table' , array ( $this , 'promocode_notice' ) ) ;
            //Show Promocode Field in Checkout
            add_action( 'woocommerce_before_checkout_form' , array ( $this , 'promocode_notice' ) ) ;
            //Show Messages in Cart
            add_action( 'woocommerce_before_cart_table' , array ( $this , 'cashback_notice' ) ) ;
            //Show Messages in Checkout
            add_action( 'woocommerce_before_checkout_form' , array ( $this , 'cashback_notice' ) ) ;
            //Apply Promocode in Cart/Checkout
            add_action( 'wp_head' , array ( $this , 'apply_promocode' ) ) ;
            //Remove Promocode in Cart/Checkout
            add_action( 'wp_head' , array ( $this , 'remove_promocode' ) ) ;
            //Update Matched Cashback Vaule in Order
            add_action( 'woocommerce_checkout_update_order_meta' , array ( $this , 'update_order_meta' ) ) ;
        }

        /*
         * Admin enqueue css files
         */

        public function admin_external_css_files() {
            wp_enqueue_style( 'hrw-cashback-module' , HRW_PLUGIN_URL . '/premium/assets/css/admin/cashback.css' , array () , HRW_VERSION ) ;
        }

        /*
         * Admin enqueue JS files
         */

        public function admin_external_js_files() {
            wp_enqueue_script( 'hrw-cashback' , HRW_PLUGIN_URL . '/premium/assets/js/admin/cashback.js' , array ( 'jquery' , 'blockUI' ) , HRW_VERSION ) ;
            wp_localize_script(
                    'hrw-cashback' , 'hrw_cashback_params' , array (
                'cashback_nonce' => wp_create_nonce( 'hrw-cashback-nonce' ) ,
                'ajaxurl'        => HRW_ADMIN_AJAX_URL
                    )
            ) ;
        }

        /*
         * Add Fund Transfer Dashboard Menu
         */

        public function add_dashboard_menu( $menus ) {

            $menus[ 'cashback' ] = array (
                'label' => esc_html__( 'Cashback' , HRW_LOCALE ) ,
                'code'  => 'fa fa-refresh' ,
                    ) ;

            return $menus ;
        }

        /*
         * Display Cashback menu content
         */

        public static function render_cashback() {
            $per_page     = 5 ;
            $current_page = HRW_Dashboard::get_current_page_number() ;

            $default_args = array (
                'post_type'      => HRWP_Register_Post_Types::CASHBACK_LOG_POSTTYPE ,
                'post_status'    => array ( 'publish' ) ,
                'author'         => HRW_Wallet_User::get_user_id() ,
                'fields'         => 'ids' ,
                'posts_per_page' => '-1'
                    ) ;

            /* Calculate Page Count */
            $overall_count = get_posts( $default_args ) ;
            $page_count    = ceil( count( $overall_count ) / $per_page ) ;

            $default_args[ 'offset' ]         = ($current_page - 1) * $per_page ;
            $default_args[ 'posts_per_page' ] = $per_page ;

            $data_args = array (
                'cashback'       => get_posts( $default_args ) ,
                'total_cashback' => hrw_get_total_cashback_credited( HRW_Wallet_User::get_user_id() ) ,
                'serial_number'  => ( $current_page * $per_page ) - $per_page + 1 ,
                'pagination'     => array (
                    'page_count'      => $page_count ,
                    'current_page'    => $current_page ,
                    'next_page_count' => (($current_page + 1) > ($page_count - 1)) ? ($current_page) : ($current_page + 1) ,
                ) ) ;

            hrw_get_template( 'dashboard/cashback-logs.php' , true , $data_args ) ;
        }

        /*
         * Display Rules
         */

        public function display_rules() {
            global $wpdb ;
            $post_query  = new HRW_Query( $wpdb->posts , 'p' ) ;
            $cashbackids = $post_query->select( 'DISTINCT `p`.`ID`' )
                    ->where( '`p`.post_type' , 'hrw_cashback' )
                    ->where( '`p`.post_status' , 'publish' )
                    ->fetchArray() ;

            include HRW_PLUGIN_PATH . '/inc/admin/menu/views/cashback/cashback-rule-list.php' ;
        }

        /*
         * After save
         */

        public function after_save() {
            if ( ! isset( $_POST[ 'hrw_cashback_rules' ] ) )
                return ;

            if ( ! hrw_check_is_array( $_POST[ 'hrw_cashback_rules' ] ) )
                return ;

            try {
                foreach ( hrw_sanitize_text_field( $_POST[ 'hrw_cashback_rules' ] ) as $post_id => $meta_values ) {
                    hrw_update_cashback( $post_id , $meta_values ) ;
                }
            } catch ( Exception $ex ) {
                HRW_Settings::add_error( $ex->getMessage() ) ;
            }
        }

        /*
         * Promocode Notice
         */

        public function cashback_notice() {
            if ( ! is_user_logged_in() )
                return ;

            $matched_rule = HRWP_Cashback_Handler::get_matched_rules() ;
            if ( ! hrw_check_is_array( $matched_rule ) )
                return ;

            if ( ! isset( $matched_rule[ 'order_rule' ] ) && ! isset( $matched_rule[ 'wallet_rule' ] ) )
                return ;

            $matched_value = array_sum( $matched_rule ) ;
            $display       = ($this->issue_type == 1) ? true : WC()->session->get( 'hrw_cashback_amount' ) ;

            if ( ! $display )
                return ;

            $notice_msg     = '' ;
            $cashback_value = 0 ;

            if ( isset( $matched_rule[ 'order_rule' ] ) ) {
                $cashback_value = $matched_rule[ 'order_rule' ] ;
                $notice_msg     = str_replace( '[cashback_value]' , hrw_price( $matched_rule[ 'order_rule' ] ) , $this->order_total_msg ) ;
            } elseif ( isset( $matched_rule[ 'wallet_rule' ] ) ) {
                $cashback_value = $matched_rule[ 'wallet_rule' ] ;
                $notice_msg     = str_replace( '[cashback_value]' , hrw_price( $matched_rule[ 'wallet_rule' ] ) , $this->wallet_topup_msg ) ;
            }

            $notice_msg = apply_filters( 'hrw_validate_cashback_form_display' , $notice_msg , $cashback_value ) ;

            echo self::hrw_display_cashback_notice( $notice_msg ) ;
        }

        public function hrw_display_cashback_notice( $notice_msg ) {
            if ( $notice_msg ) {
                ?>
                <div class="hrw_cashback_notice woocommerce-info">
                    <?php
                    echo $notice_msg ;
                    ?>
                </div>
                <?php
            }
        }

        /*
         * Promocode Notice
         */

        public function promocode_notice() {
            if ( ! is_user_logged_in() )
                return ;

            if ( $this->issue_type == 1 )
                return ;

            if ( WC()->session->get( 'hrw_cashback_amount' ) ) {
                $url = add_query_arg( array ( 'hrw_remove_promocode' => 'yes' ) , get_permalink() ) ;
                wc_add_notice( "Promocode Applied Successfully.<a href='" . $url . "' class='hrw_remove_promocode'>Remove</a>" , 'success' ) ;
                return ;
            }

            $matched_rule = HRWP_Cashback_Handler::get_matched_rules() ;


            if ( ! hrw_check_is_array( $matched_rule ) )
                return ;

            $matched_value = array_sum( $matched_rule ) ;

            if ( $msg = apply_filters( 'hrw_validate_cashback_form_display' , '' , $matched_value ) ) {
                wc_add_notice( $msg , 'error' ) ;
                return ;
            }

            WC()->session->set( 'hrw_matched_rule' , $matched_rule ) ;
            $notice = str_replace( '[cashback_value]' , get_woocommerce_currency_symbol() . $matched_value , $this->promocode_notice ) ;
            ?>
            <div class="hrw_cashback_promo_wrapper">
                <form method="post" class="hrw_promocode_notice">
                    <div class="hrw_promocode_notice_wrapper woocommerce-info">
                        <label class="hrw_msg_for_cashback"><?php echo esc_html( $notice ) ; ?></label>
                        <input type="hidden" value="<?php echo esc_attr( $matched_value ) ; ?>" name="hrw_cashback_value" class="hrw_cashback_value">
                        <input type="submit" value="<?php echo esc_attr( $this->promocode ) ; ?>" name="hrw_promocode_button" />
                    </div>
                </form>
            </div>
            <?php
        }

        /*
         * Apply Promocode
         */

        public function apply_promocode() {
            if ( ! isset( $_POST[ 'hrw_promocode_button' ] ) )
                return ;

            if ( ! isset( $_POST[ 'hrw_cashback_value' ] ) || empty( $_POST[ 'hrw_cashback_value' ] ) )
                return ;

            WC()->session->set( 'hrw_cashback_amount' , $_POST[ 'hrw_cashback_value' ] ) ;

            $url = add_query_arg( array ( 'hrw_remove_promocode' => 'yes' ) , get_permalink() ) ;

            $link = "<a href='" . esc_url( $url ) . "' class='hrw_remove_promocode'>" . esc_html__( 'Remove' , HRW_LOCALE ) . "</a>" ;

            wc_add_notice( sprintf( esc_html__( "Promocode Applied Successfully %s" , HRW_LOCALE ) , $link ) , 'success' ) ;
        }

        /*
         * Apply Promocode
         */

        public function remove_promocode() {
            if ( ! isset( $_GET[ 'hrw_remove_promocode' ] ) )
                return ;

            if ( ( $_GET[ 'hrw_remove_promocode' ] != 'yes' ) )
                return ;

            if ( ! WC()->session->get( 'hrw_cashback_amount' ) )
                return ;

            WC()->session->__unset( 'hrw_cashback_amount' ) ;
            $url = remove_query_arg( array ( 'hrw_remove_promocode' ) , get_permalink() ) ;
            wc_add_notice( esc_html__( 'Promocode removed successfully.' , HRW_LOCALE ) , 'success' ) ;
            wp_safe_redirect( $url ) ;
            exit() ;
        }

        /*
         * Update Matched Cashback Value in Order Meta
         */

        public function update_order_meta( $order_id ) {

            if ( $this->issue_type == 2 ) {
                if ( ! WC()->session->get( 'hrw_cashback_amount' ) )
                    return ;

                if ( apply_filters( 'hrw_do_cashback_validation' , false , WC()->session->get( 'hrw_cashback_amount' ) ) )
                    return ;

                update_post_meta( $order_id , 'hrw_cashback_value' , WC()->session->get( 'hrw_cashback_amount' ) ) ;
                update_post_meta( $order_id , 'hrw_matched_rule' , WC()->session->get( 'hrw_matched_rule' ) ) ;

                //unset add fee session
                WC()->session->__unset( 'hrw_cashback_amount' ) ;
                WC()->session->__unset( 'hrw_matched_rule' ) ;
            }else {
                $order          = new WC_Order( $order_id ) ;
                $payment_method = $order->get_payment_method() ;
                $matched_rule   = HRWP_Cashback_Handler::get_matched_rules( $payment_method ) ;

                if ( ! hrw_check_is_array( $matched_rule ) )
                    return ;

                $matched_value = array_sum( $matched_rule ) ;

                if ( apply_filters( 'hrw_do_cashback_validation' , false , $matched_value ) )
                    return ;

                update_post_meta( $order_id , 'hrw_cashback_value' , $matched_value ) ;
                update_post_meta( $order_id , 'hrw_matched_rule' , $matched_rule ) ;
            }
        }

        /*
         * Add Cashback to Wallet
         */

        public function credit_cashback( $order_id ) {
            if ( ! is_user_logged_in() )
                return ;

            if ( get_post_meta( $order_id , 'hrw_cashback_awarded' , true ) == 'yes' )
                return ;

            $cashback_amnt = get_post_meta( $order_id , 'hrw_cashback_value' , true ) ;
            if ( empty( $cashback_amnt ) )
                return ;

            $cashback_rules = get_post_meta( $order_id , 'hrw_matched_rule' , true ) ;
            if ( ! hrw_check_is_array( $cashback_rules ) )
                return ;

            $order      = new WC_Order( $order_id ) ;
            $order_amnt = $order->get_total() ;
            if ( isset( $cashback_rules[ 'order_rule' ] ) ) {
                $msg = $this->amnt_credited_for_order_total ;
                $msg = str_replace( '[order_id]' , '#' . $order_id , $msg ) ;
            } elseif ( isset( $cashback_rules[ 'wallet_rule' ] ) ) {
                $msg = $this->amnt_credited_for_topup ;
                $msg = str_replace( '[topup_amount]' , $order_amnt , $msg ) ;
            }

            $event = esc_html__( $msg , HRW_LOCALE ) ;

            $cashback_args = array (
                'hrw_event'           => $event ,
                'hrw_amount_credited' => $cashback_amnt ,
                'hrw_amount_debited'  => 0 ,
                'hrw_date'            => current_time( 'mysql' , true ) ,
                    ) ;

            hrw_create_new_cashback_log( $cashback_args , array ( 'post_author' => $order->get_user_id() , 'post_parent' => hrw_get_wallet_id_by_user_id( $order->get_user_id() ) ) ) ;

            $transaction_args = array (
                'user_id' => $order->get_user_id() ,
                'amount'  => $cashback_amnt ,
                'event'   => $event
                    ) ;
            HRW_Credit_Debit_Handler::credit_amount_to_wallet( $transaction_args ) ;

            do_action( 'hrw_cashback_credit_notification' , $order ) ;
            update_post_meta( $order_id , 'hrw_cashback_awarded' , 'yes' ) ;
            update_post_meta( $order_id , 'hrw_cashback_revised' , 'no' ) ;
        }

        /*
         * Revise Cashback from Wallet
         */

        public function debit_cashback( $order_id ) {
            if ( ! is_user_logged_in() )
                return ;

            if ( get_post_meta( $order_id , 'hrw_cashback_awarded' , true ) != 'yes' )
                return ;

            if ( get_post_meta( $order_id , 'hrw_cashback_revised' , true ) == 'yes' )
                return ;

            $cashback_amnt = get_post_meta( $order_id , 'hrw_cashback_value' , true ) ;
            if ( empty( $cashback_amnt ) )
                return ;

            $cashback_rules = get_post_meta( $order_id , 'hrw_matched_rule' , true ) ;
            $order          = new WC_Order( $order_id ) ;
            $order_amnt     = $order->get_total() ;
            if ( hrw_check_is_array( $cashback_rules ) ) {
                if ( isset( $cashback_rules[ 'order_rule' ] ) ) {
                    $msg = $this->amnt_revised_for_order_total ;
                    $msg = str_replace( '[order_id]' , '#' . $order_id , $msg ) ;
                } elseif ( isset( $cashback_rules[ 'wallet_rule' ] ) ) {
                    $msg = $this->amnt_revised_for_topup ;
                    $msg = str_replace( '[topup_amount]' , $order_amnt , $msg ) ;
                }
                $event = esc_html__( $msg , HRW_LOCALE ) ;
            } else {
                $event = esc_html__( 'Cashback' , HRW_LOCALE ) ;
            }

            $order         = new WC_Order( $order_id ) ;
            $cashback_args = array (
                'hrw_event'           => $event ,
                'hrw_amount_credited' => 0 ,
                'hrw_amount_debited'  => $cashback_amnt ,
                'hrw_date'            => current_time( 'mysql' , true ) ,
                    ) ;

            hrw_create_new_cashback_log( $cashback_args , array ( 'post_parent' => $order->get_user_id() ) ) ;

            $transaction_args = array (
                'user_id' => $order->get_user_id() ,
                'amount'  => $cashback_amnt ,
                'event'   => $event
                    ) ;
            HRW_Credit_Debit_Handler::debit_amount_from_wallet( $transaction_args ) ;

            update_post_meta( $order_id , 'hrw_cashback_awarded' , 'no' ) ;
            update_post_meta( $order_id , 'hrw_cashback_revised' , 'yes' ) ;
        }

        /*
         * Add Rule for Cashback
         */

        public static function add_cashback_rule() {
            check_ajax_referer( 'hrw-cashback-nonce' , 'hrw_security' ) ;

            try {
                if ( ! isset( $_REQUEST[ 'rule_name' ] ) || empty( $_REQUEST[ 'rule_name' ] ) )
                    throw new exception( esc_html__( 'Rule name cannot be empty' , HRW_LOCALE ) ) ;

                ob_start() ;
                $metadata                = array () ;
                $metadata[ 'rule_name' ] = hrw_sanitize_text_field( $_REQUEST[ 'rule_name' ] ) ;

                $postid   = hrw_create_new_cashback( $metadata ) ;
                $cashback = hrw_get_cashback( $postid ) ;
                include HRW_PLUGIN_PATH . '/inc/modules/views/cashback-rules.php' ;
                $field    = ob_get_contents() ;
                ob_end_clean() ;

                wp_send_json_success( array ( 'field' => $field ) ) ;
            } catch ( exception $ex ) {
                wp_send_json_error( array ( 'error' => $ex->getMessage() ) ) ;
            }
        }

        /*
         * Add Rule for Order Total
         */

        public static function add_cashback_order_rule() {
            check_ajax_referer( 'hrw-cashback-nonce' , 'hrw_security' ) ;

            try {
                if ( ! isset( $_REQUEST[ 'postid' ] ) )
                    throw new exception( esc_html__( 'Invalid Request' , HRW_LOCALE ) ) ;

                ob_start() ;
                $postid   = absint( $_REQUEST[ 'postid' ] ) ;
                $uniqueid = uniqid() ;

                include HRW_PLUGIN_PATH . '/inc/admin/menu/views/cashback/order-rule-list.php' ;

                $field = ob_get_contents() ;
                ob_end_clean() ;

                wp_send_json_success( array ( 'field' => $field ) ) ;
            } catch ( exception $ex ) {
                wp_send_json_error( array ( 'error' => $ex->getMessage() ) ) ;
            }
        }

        /*
         * Add Rule for Wallet Top-up
         */

        public static function add_cashback_wallet_rule() {
            check_ajax_referer( 'hrw-cashback-nonce' , 'hrw_security' ) ;

            try {
                if ( ! isset( $_REQUEST[ 'postid' ] ) )
                    throw new exception( esc_html__( 'Invalid Request' , HRW_LOCALE ) ) ;

                ob_start() ;
                $postid   = absint( $_REQUEST[ 'postid' ] ) ;
                $uniqueid = uniqid() ;

                include HRW_PLUGIN_PATH . '/inc/admin/menu/views/cashback/wallet-rule-list.php' ;

                $field = ob_get_contents() ;
                ob_end_clean() ;

                wp_send_json_success( array ( 'field' => $field ) ) ;
            } catch ( exception $ex ) {
                wp_send_json_error( array ( 'error' => $ex->getMessage() ) ) ;
            }
        }

        /*
         * Delete Cashback Rule
         */

        public static function delete_cashback_rule() {
            check_ajax_referer( 'hrw-cashback-nonce' , 'hrw_security' ) ;

            try {
                if ( ! isset( $_REQUEST[ 'postid' ] ) )
                    throw new exception( esc_html__( 'Invalid Request' , HRW_LOCALE ) ) ;

                hrw_delete_cashback( absint( $_REQUEST[ 'postid' ] ) ) ;

                wp_send_json_success() ;
            } catch ( exception $ex ) {
                wp_send_json_error( array ( 'error' => $ex->getMessage() ) ) ;
            }
        }

    }

}
