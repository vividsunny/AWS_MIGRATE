<?php

/**
 * Register new Payment Gateway id of Stripe.
 * 
 * @class HRW_Stripe_Gateway
 * @category Class
 */
class HRW_Stripe_Gateway extends WC_Payment_Gateway {

    /**
     * Logger instance
     *
     * @var WC_Logger
     */
    public static $log = false ;

    /**
     * HRW_Stripe_Gateway constructor.
     */
    public function __construct() {
        $this->id                 = 'hrw_stripe' ;
        $this->method_title       = __( 'HRW - Stripe' , HRW_LOCALE ) ;
        $this->method_description = __( 'This gateway is used only for Wallet Auto Top-Up feature. It will not be displayed for normal checkout.' , HRW_LOCALE ) ;
        $this->has_fields         = true ;
        $this->init_form_fields() ;
        $this->init_settings() ;
        $this->enabled            = $this->get_option( 'enabled' ) ;
        $this->title              = $this->get_option( 'title' ) ;
        $this->description        = $this->get_option( 'description' ) ;
        $this->cardiconfilter     = $this->get_option( 'cardiconfilter' , array() ) ;
        $this->testmode           = 'yes' === $this->get_option( 'testmode' ) ;
        $this->testsecretkey      = $this->get_option( 'testsecretkey' ) ;
        $this->testpublishablekey = $this->get_option( 'testpublishablekey' ) ;
        $this->livesecretkey      = $this->get_option( 'livesecretkey' ) ;
        $this->livepublishablekey = $this->get_option( 'livepublishablekey' ) ;
        $this->checkoutmode       = $this->get_option( 'checkoutmode' ) ;
        $this->supports           = array(
            'products' ,
            'hrw_auto_topup'
                ) ;

        include_once('class-hrw-stripe-api-request.php') ;
        add_action( 'woocommerce_update_options_payment_gateways_' . $this->id , array( $this , 'process_admin_options' ) ) ;
        add_action( 'wp_enqueue_scripts' , array( $this , 'payment_scripts' ) ) ;
        add_action( 'wc_ajax_hrw_stripe_verify_intent' , array( $this , 'verify_intent' ) ) ;
        add_action( 'hrw_process_stripe_success_response' , array( $this , 'process_upon_order_success' ) , 10 , 2 ) ;
        add_filter( "hrw_charge_user_via_{$this->id}" , array( $this , 'charge_user' ) , 10 , 2 ) ;
    }

    /**
     * Admin Settings
     */
    public function init_form_fields() {
        $this->form_fields = array(
            'enabled'            => array(
                'title'   => __( 'Enable/Disable' , HRW_LOCALE ) ,
                'type'    => 'checkbox' ,
                'label'   => __( 'Stripe' , HRW_LOCALE ) ,
                'default' => 'no'
            ) ,
            'title'              => array(
                'title'       => __( 'Title:' , HRW_LOCALE ) ,
                'type'        => 'text' ,
                'description' => __( 'This controls the title which the user see during checkout.' , HRW_LOCALE ) ,
                'default'     => __( 'HRW - Stripe' , HRW_LOCALE ) ,
            ) ,
            'description'        => array(
                'title'    => __( 'Description' , HRW_LOCALE ) ,
                'type'     => 'textarea' ,
                'default'  => __( 'Pay with Stripe. You can pay with your credit card, debit card and master card   ' , HRW_LOCALE ) ,
                'desc_tip' => true ,
            ) ,
            'cardiconfilter'     => array(
                'type'              => 'multiselect' ,
                'title'             => __( 'Card Brands to be Displayed' , HRW_LOCALE ) ,
                'class'             => 'wc-enhanced-select' ,
                'css'               => 'width: 450px;' ,
                'default'           => array(
                    'visa' ,
                    'mastercard' ,
                    'amex' ,
                    'discover' ,
                    'jcb'
                ) ,
                'description'       => __( 'Selected card brands will be displayed next to gateway title.' , HRW_LOCALE ) ,
                'options'           => array(
                    'visa'       => 'Visa' ,
                    'mastercard' => 'Mastercard' ,
                    'amex'       => 'Amex' ,
                    'discover'   => 'Discover' ,
                    'jcb'        => 'JCB'
                ) ,
                'desc_tip'          => true ,
                'custom_attributes' => array(
                    'data-placeholder' => __( 'Select Card Brands..' , HRW_LOCALE )
                )
            ) ,
            'testmode'           => array(
                'title'       => __( 'Test Mode' , HRW_LOCALE ) ,
                'type'        => 'checkbox' ,
                'label'       => __( 'Turn on testing' , HRW_LOCALE ) ,
                'description' => __( 'Use the test mode on Stripe dashboard to verify everything works before going live.' , HRW_LOCALE ) ,
                'default'     => 'no' ,
            ) ,
            'livesecretkey'      => array(
                'type'    => 'text' ,
                'title'   => __( 'Stripe API Live Secret key' , HRW_LOCALE ) ,
                'default' => '' ,
            ) ,
            'livepublishablekey' => array(
                'type'    => 'text' ,
                'title'   => __( 'Stripe API Live Publishable key' , HRW_LOCALE ) ,
                'default' => '' ,
            ) ,
            'testsecretkey'      => array(
                'type'    => 'text' ,
                'title'   => __( 'Stripe API Test Secret key' , HRW_LOCALE ) ,
                'default' => '' ,
            ) ,
            'testpublishablekey' => array(
                'type'    => 'text' ,
                'title'   => __( 'Stripe API Test Publishable key' , HRW_LOCALE ) ,
                'default' => '' ,
            ) ,
            'checkoutmode'       => array(
                'title'   => __( 'Checkout Mode' , HRW_LOCALE ) ,
                'type'    => 'select' ,
                'default' => 'default' ,
                'options' => array(
                    'default'        => __( 'Default' , HRW_LOCALE ) ,
                    'inline_cc_form' => __( 'Inline Credit Card Form' , HRW_LOCALE ) ,
                ) ,
            ) ,
                ) ;
    }

    /**
     * Return the gateway's icon.
     *
     * @return string
     */
    public function get_icon() {
        $icon = '' ;

        foreach( $this->cardiconfilter as $icon_name ) {
            if( ! $icon_name ) {
                continue ;
            }

            $icon .= '<img src="' . WC_HTTPS::force_https_url( WC()->plugin_url() . "/assets/images/icons/credit-cards/{$icon_name}.png" ) . '" alt="' . esc_attr( ucfirst( $icon_name ) ) . '" />' ;
        }

        return apply_filters( 'woocommerce_gateway_icon' , $icon , $this->id ) ;
    }

    /**
     * Gets the transaction URL linked to Stripe dashboard.
     */
    public function get_transaction_url( $order ) {
        $this->view_transaction_url = '' ;

        return parent::get_transaction_url( $order ) ;
    }

    /**
     * Outputs scripts for Stripe elements.
     */
    public function payment_scripts() {
        if( 'yes' !== $this->enabled && ! is_checkout() && ! is_add_payment_method_page() ) {
            return ;
        }

        wp_enqueue_script( 'stripe' , 'https://js.stripe.com/v3/' , array( 'jquery' ) , '3.0' , true ) ;
        wp_enqueue_script( 'hrw-stripe' , HRW_PLUGIN_URL . '/premium/assets/js/frontend/stripe.js' , array( 'jquery' , 'stripe' ) , HRW_VERSION , true ) ;
        wp_localize_script( 'hrw-stripe' , 'hrw_stripe_params' , array(
            'payment_method' => $this->id ,
            'key'            => $this->testmode ? $this->testpublishablekey : $this->livepublishablekey ,
            'checkoutmode'   => $this->checkoutmode ,
        ) ) ;
        wp_enqueue_style( 'hrw-stripe-style' , HRW_PLUGIN_URL . '/premium/assets/css/frontend/stripe.css' ) ;
    }

    /**
     * Render Elements
     */
    public function elements_form() {
        ?>
        <fieldset id="wc-<?php echo esc_attr( $this->id ) ; ?>-cc-form" class="wc-credit-card-form wc-payment-form">
            <?php
            if( 'inline_cc_form' === $this->checkoutmode ) {
                ?>
                <label for="stripe-card-element">
                    <?php esc_html_e( 'Credit or debit card' , HRW_LOCALE ) ; ?>
                </label>
                <div id="hrw-stripe-card-element" class="hrw-stripe-elements-field">
                    <!-- A Stripe Element will be inserted here. -->
                </div>
                <?php
            } else {
                ?>
                <div class="form-row form-row-wide">
                    <label for="stripe-card-element"><?php esc_html_e( 'Card Number' , HRW_LOCALE ) ; ?> <span class="required">*</span></label>
                    <div class="hrw-stripe-card-group">
                        <div id="hrw-stripe-card-element" class="hrw-stripe-elements-field">
                            <!-- a Stripe Element will be inserted here. -->
                        </div>

                        <i class="hrw-stripe-credit-card-brand hrw-stripe-card-brand" alt="Credit Card"></i>
                    </div>
                </div>

                <div class="form-row form-row-first">
                    <label for="stripe-exp-element"><?php esc_html_e( 'Expiry Date' , HRW_LOCALE ) ; ?> <span class="required">*</span></label>

                    <div id="hrw-stripe-exp-element" class="hrw-stripe-elements-field">
                        <!-- a Stripe Element will be inserted here. -->
                    </div>
                </div>

                <div class="form-row form-row-last">
                    <label for="stripe-cvc-element"><?php esc_html_e( 'Card Code (CVC)' , HRW_LOCALE ) ; ?> <span class="required">*</span></label>
                    <div id="hrw-stripe-cvc-element" class="hrw-stripe-elements-field">
                        <!-- a Stripe Element will be inserted here. -->
                    </div>
                </div>
                <?php
            }
            ?> 
            <!-- Used to display form errors. -->
            <div class="hrw-stripe-card-errors" role="alert"></div>
        </fieldset>
        <?php
    }

    /**
     * Add payment fields.
     */
    public function payment_fields() {
        if( $description = $this->get_description() ) {
            echo wpautop( wptexturize( $description ) ) ;
        }

        $this->elements_form() ;
    }

    /**
     * Process a Stripe Payment.
     */
    public function process_payment( $order_id ) {

        try {
            if( ! $order = wc_get_order( $order_id ) ) {
                throw new Exception( __( 'Authorization failed: Invalid order !!' , HRW_LOCALE ) ) ;
            }

            if( ! is_user_logged_in() ) {
                throw new Exception( __( 'Authorization failed: User should be logged in !!' , HRW_LOCALE ) ) ;
            }

            if( $order->get_total() > 0 || 'auto' !== get_post_meta( $order_id , 'hr_wallet_topup_mode' , true ) ) {
                throw new Exception( __( 'Authorization failed: Something went wrong while preparing authorization !!' , HRW_LOCALE ) ) ;
            }

            if( empty( $_POST[ 'hrw_stripe_pm' ] ) ) {
                throw new Exception( __( 'Authorization failed: Invalid payment method !!' , HRW_LOCALE ) ) ;
            }

            HRW_Stripe_API_Request::init( $this ) ;

            $pm = HRW_Stripe_API_Request::request( 'retrieve_pm' , array(
                        'id' => wc_clean( $_POST[ 'hrw_stripe_pm' ] ) ,
                    ) ) ;

            if( is_wp_error( $pm ) ) {
                throw new Exception( HRW_Stripe_API_Request::get_last_error_message() ) ;
            }

            $stripe_customer = $this->maybe_create_customer( $this->prepare_current_userdata() ) ;

            $this->save_stripe_pm_to_order( $order , $pm ) ;
            $this->save_topup_mode_to_order( $order , 'auto' ) ;
            $this->save_customer_to_order( $order , $stripe_customer ) ;
            $result = $this->process_order_without_payment( $order , $pm , $stripe_customer ) ;

            if( isset( $result[ 'result' ] , $result[ 'intent' ] ) && 'success' === $result[ 'result' ] ) {
                $this->attach_pm_to_customer( $result[ 'intent' ] ) ;
            }
        } catch( Exception $e ) {
            if( isset( $order ) ) {
                $order->add_order_note( esc_html( $e->getMessage() ) ) ;
                $order->save() ;
            }

            $this->log_err( HRW_Stripe_API_Request::get_last_log() ) ;
            wc_add_notice( esc_html( $e->getMessage() ) , 'error' ) ;

            return array(
                'result'   => 'failure' ,
                'redirect' => $this->get_return_url( $order )
                    ) ;
        }
        return $result ;
    }

    /**
     * Process an order that doesn't require payment.
     */
    public function process_order_without_payment( &$order , $pm , $stripe_customer ) {
        // To charge recurring payments make sure to confirm the si before the order gets completed.
        //Check if the si is already available for this order
        $si      = HRW_Stripe_API_Request::request( 'retrieve_si' , array( 'id' => $this->get_intent_from_order( $order ) ) ) ;
        $request = array(
            'payment_method' => $pm->id ,
            'customer'       => $stripe_customer->id ,
            'metadata'       => $this->prepare_metadata_from_order( $order->get_id() ) ,
            'description'    => sprintf( __( '%1$s - Order %2$s' , HRW_LOCALE ) , wp_specialchars_decode( get_bloginfo( 'name' ) , ENT_QUOTES ) , $order->get_id() ) ,
                ) ;

        if( is_wp_error( $si ) || $stripe_customer->id !== $si->customer ) {
            $request[ 'usage' ] = 'off_session' ;

            $si = HRW_Stripe_API_Request::request( 'create_si' , $request ) ;
        } else {
            $request[ 'id' ] = $si->id ;

            $si = HRW_Stripe_API_Request::request( 'update_si' , $request ) ;
        }

        if( is_wp_error( $si ) ) {
            throw new Exception( HRW_Stripe_API_Request::get_last_error_message() ) ;
        }

        $this->save_intent_to_order( $order , $si ) ;

        if( 'requires_confirmation' === $si->status ) {
            // An si with a payment method is ready to be confirmed.
            $si->confirm( array(
                'payment_method' => $pm->id ,
            ) ) ;
        }

        // If the intent requires a 3DS flow, redirect to it.
        if( 'requires_action' === $si->status || 'requires_source_action' === $si->status ) {
            return array(
                'result'   => 'success' ,
                'redirect' => $this->prepare_customer_intent_verify_url( $si , array(
                    'order'       => $order->get_id() ,
                    'redirect_to' => $this->get_return_url( $order ) ,
                ) ) ,
                    ) ;
        }

        //Process si response.
        $result = $this->process_response( $si , $order ) ;

        if( 'success' !== $result ) {
            throw new Exception( $result ) ;
        }

        return array(
            'result'   => 'success' ,
            'intent'   => $si ,
            'redirect' => $this->get_return_url( $order )
                ) ;
    }

    /**
     *  Process the given response
     */
    public function process_response( $response , $order = false , $err_log = false ) {

        switch( $response->status ) {
            case 'succeeded':
                if( $order ) {
                    if( ! $order->has_status( array( 'processing' , 'completed' ) ) ) {
                        $order->payment_complete( $response->id ) ;
                    }

                    $order->add_order_note( __( 'Stripe: payment complete. Customer has approved for future payments.' , HRW_LOCALE ) ) ;
                    $order->set_transaction_id( $response->id ) ;
                    $order->save() ;
                }

                do_action( 'hrw_process_stripe_success_response' , $response , $order ) ;
                return 'success' ;
                break ;
            case 'processing':
                if( $order ) {
                    if( ! $order->has_status( 'on-hold' ) ) {
                        $order->update_status( 'on-hold' ) ;
                    }

                    $order->add_order_note( sprintf( __( 'Stripe: awaiting confirmation by the customer to approve for future payments: %s.' , HRW_LOCALE ) , $response->id ) ) ;
                    $order->set_transaction_id( $response->id ) ;
                    $order->save() ;
                }

                do_action( 'hrw_process_stripe_success_response' , $response , $order ) ;
                return 'success' ;
                break ;
            case 'requires_payment_method':
            case 'requires_source':
            case 'canceled':
                if( $order ) {
                    if( ! $order->has_status( 'failed' ) ) {
                        $order->update_status( 'failed' ) ;
                    }
                }

                $message = $response->last_setup_error ? sprintf( __( 'Stripe: SCA authentication failed. Reason: %s' , HRW_LOCALE ) , $response->last_setup_error->message ) : __( 'Stripe: SCA authentication failed.' , HRW_LOCALE ) ;

                if( $order ) {
                    $order->add_order_note( $message ) ;
                    $order->save() ;
                }

                do_action( 'hrw_process_stripe_failed_response' , $response , $order ) ;

                if( $err_log ) {
                    if( isset( $response->last_setup_error ) ) {
                        $this->log_err( $response->last_setup_error ) ;
                    }
                }
                return $message ;
                break ;
        }

        return 'failure' ;
    }

    /**
     * Verify pi/si via Stripe.js
     */
    public function verify_intent() {

        try {
            if( empty( $_GET[ 'nonce' ] ) || empty( $_GET[ 'endpoint' ] ) || empty( $_GET[ 'intent' ] ) || ! wp_verify_nonce( sanitize_key( $_GET[ 'nonce' ] ) , 'hrw_stripe_confirm_intent' ) ) {
                throw new Exception( __( 'Stripe: Intent verification failed.' , HRW_LOCALE ) ) ;
            }

            if( in_array( $_GET[ 'endpoint' ] , array( 'checkout' ) ) ) {
                $order = wc_get_order( isset( $_GET[ 'order' ] ) ? absint( $_GET[ 'order' ] ) : 0  ) ;

                if( ! $order ) {
                    throw new Exception( __( 'Stripe: Invalid order while verifying intent confirmation.' , HRW_LOCALE ) ) ;
                }

                if( $this->id !== $order->get_payment_method() ) {
                    throw new Exception( __( 'Stripe: Invalid payment method while verifying intent confirmation.' , HRW_LOCALE ) ) ;
                }
            } else {
                $order = false ;
            }

            HRW_Stripe_API_Request::init( $this ) ;

            $intent = HRW_Stripe_API_Request::request( 'retrieve_si' , array( 'id' => wc_clean( ( string ) $_GET[ 'intent' ] ) ) ) ;

            if( is_wp_error( $intent ) ) {
                throw new Exception( HRW_Stripe_API_Request::get_last_error_message() ) ;
            }

            $result = $this->process_response( $intent , $order , true ) ;

            if( 'success' !== $result ) {
                throw new Exception( $result ) ;
            }

            if( $intent->customer ) {
                $this->attach_pm_to_customer( $intent ) ;
            }

            if( isset( $_GET[ 'is_ajax' ] ) ) {
                return ;
            }

            $redirect_url = ! empty( $_GET[ 'redirect_to' ] ) ? esc_url_raw( wp_unslash( $_GET[ 'redirect_to' ] ) ) : '' ;

            if( empty( $redirect_url ) ) {
                if( $order ) {
                    $redirect_url = $this->get_return_url( $order ) ;
                } else {
                    $redirect_url = WC()->cart->is_empty() ? get_permalink( wc_get_page_id( 'shop' ) ) : wc_get_checkout_url() ;
                }
            }

            wp_safe_redirect( $redirect_url ) ;
            exit ;
        } catch( Exception $e ) {
            $this->log_err( HRW_Stripe_API_Request::get_last_log() ) ;

            if( isset( $_GET[ 'is_ajax' ] ) ) {
                return ;
            }

            wc_add_notice( esc_html( $e->getMessage() ) , 'error' ) ;

            $redirect_url = ! empty( $_GET[ 'redirect_to' ] ) ? esc_url_raw( wp_unslash( $_GET[ 'redirect_to' ] ) ) : '' ;

            if( empty( $redirect_url ) ) {
                if( isset( $order ) && is_a( $order , 'WC_Order' ) ) {
                    $redirect_url = ! empty( $_GET[ 'redirect_to' ] ) ? esc_url_raw( wp_unslash( $_GET[ 'redirect_to' ] ) ) : $this->get_return_url( $order ) ;
                } else {
                    $redirect_url = WC()->cart->is_empty() ? get_permalink( wc_get_page_id( 'shop' ) ) : wc_get_checkout_url() ;
                }
            }

            wp_safe_redirect( $redirect_url ) ;
            exit ;
        }
    }

    /**
     * Charge the customer automatically to auto topup their wallet
     * 
     * @return bool
     */
    public function charge_user( $bool , $auto_topup ) {

        try {

            HRW_Stripe_API_Request::init( $this ) ;

            $customer = HRW_Stripe_API_Request::request( 'retrieve_customer' , array(
                        'id' => $this->get_stripe_customer_from_wallet( $auto_topup )
                    ) ) ;

            if( is_wp_error( $customer ) ) {
                throw new Exception( HRW_Stripe_API_Request::get_last_error_message() ) ;
            }

            if( HRW_Stripe_API_Request::is_customer_deleted( $customer ) ) {
                throw new Exception( sprintf( __( 'Stripe: Couldn\'t find the customer %s' , HRW_LOCALE ) , $customer->id ) ) ;
            }

            // make sure this is high priority
//            if( $customer->invoice_settings->default_payment_method && apply_filters( 'hrw_charge_stripe_default_payment_method' , true , $auto_topup ) ) {
//                $pm = $customer->invoice_settings->default_payment_method ;
//            }

            $pm = $this->get_stripe_pm_from_wallet( $auto_topup ) ;

            $pi = HRW_Stripe_API_Request::request( 'create_pi' , array(
                        'customer'       => $customer->id ,
                        'amount'         => floatval( $auto_topup->get_topup_amount() ) ,
                        'currency'       => $auto_topup->get_currency() ,
                        'metadata'       => $this->prepare_metadata_from_order( $auto_topup->get_last_order() , $auto_topup ) ,
                        'description'    => sprintf( __( '%1$s - Order %2$s' , HRW_LOCALE ) , wp_specialchars_decode( get_bloginfo( 'name' ) , ENT_QUOTES ) , $auto_topup->get_last_order() ) ,
                        'off_session'    => true ,
                        'confirm'        => true ,
                        'payment_method' => $pm ,
                    ) ) ;

            if( is_wp_error( $pi ) ) {
                throw new Exception( HRW_Stripe_API_Request::get_last_error_message() ) ;
            }

            //Process pi response.
            $result = $this->process_response( $pi ) ;

            if( 'success' !== $result ) {
                throw new Exception( $result ) ;
            }

            do_action( 'hrw_stripe_charge_successful' , $auto_topup ) ;
        } catch( Exception $e ) {
            $this->log_err( HRW_Stripe_API_Request::get_last_log() ) ;
            return false ;
        }
        return true ;
    }

    /**
     * Save Stripe customer, payment method after order success
     */
    public function process_upon_order_success( $response , $order ) {
        if( ! $order || $this->id !== $order->get_payment_method() ) {
            return ;
        }

        $auto_topup_id = get_post_meta( $order->get_id() , 'hrw_auto_topup_id' , true ) ;

        if( ! $auto_topup_id ) {
            return ;
        }

        update_post_meta( $auto_topup_id , 'hrw_payment_method' , $this->id ) ;
        update_post_meta( $auto_topup_id , 'hrw_stripe_customer_id' , $response->customer ) ;
        update_post_meta( $auto_topup_id , 'hrw_stripe_payment_method' , $response->payment_method ) ;
        update_post_meta( $auto_topup_id , 'hrw_last_charge_date' , current_time( 'mysql' , true ) ) ;
    }

    /**
     * Save Stripe paymentMethod in Order
     */
    public function save_stripe_pm_to_order( $order , $pm ) {
        update_post_meta( $order->get_id() , '_hrw_stripe_payment_method' , isset( $pm->id ) ? $pm->id : $pm  ) ;
    }

    /**
     * Save Stripe customer in Order
     */
    public function save_customer_to_order( $order , $customer ) {
        update_post_meta( $order->get_id() , '_hrw_stripe_customer_id' , isset( $customer->id ) ? $customer->id : $customer  ) ;
    }

    /**
     * Save Topup mode in Order
     */
    public function save_topup_mode_to_order( $order , $mode ) {
        update_post_meta( $order->get_id() , '_hrw_topup_mode' , 'auto' === $mode ? 'auto' : 'manual'  ) ;
    }

    /**
     * Save Stripe intent in Order
     */
    public function save_intent_to_order( $order , $intent ) {
        update_post_meta( $order->get_id() , '_hrw_stripe_si' , $intent->id ) ;
    }

    /**
     * Prepare pi/si verification url
     */
    public function prepare_customer_intent_verify_url( $intent , $query_args = array() ) {
        $query_args = wp_parse_args( $query_args , array(
            'intent'      => $intent->id ,
            'endpoint'    => '' ,
            'nonce'       => wp_create_nonce( 'hrw_stripe_confirm_intent' ) ,
            'redirect_to' => get_site_url() ,
                ) ) ;

        $query_args[ 'redirect_to' ] = rawurlencode( $query_args[ 'redirect_to' ] ) ;

        if( empty( $query_args[ 'endpoint' ] ) ) {
            $query_args[ 'endpoint' ] = 'checkout' ;
        }

        // Redirect into the verification URL thereby we need to verify the intent
        $verification_url = rawurlencode( add_query_arg( $query_args , WC_AJAX::get_endpoint( 'hrw_stripe_verify_intent' ) ) ) ;

        return sprintf( '#confirm-hrw-stripe-intent-%s:%s:%s:%s' , $intent->client_secret , $intent->object , $query_args[ 'endpoint' ] , $verification_url ) ;
    }

    /**
     * Prepare current userdata
     */
    public function prepare_current_userdata() {
        if( ! $user = get_user_by( 'id' , get_current_user_id() ) ) {
            return array() ;
        }

        $billing_first_name = get_user_meta( $user->ID , 'billing_first_name' , true ) ;
        $billing_last_name  = get_user_meta( $user->ID , 'billing_last_name' , true ) ;

        if( empty( $billing_first_name ) ) {
            $billing_first_name = get_user_meta( $user->ID , 'first_name' , true ) ;
        }

        if( empty( $billing_last_name ) ) {
            $billing_last_name = get_user_meta( $user->ID , 'last_name' , true ) ;
        }

        $userdata = array(
            'address' => array(
                'line1'       => get_user_meta( $user->ID , 'billing_address_1' , true ) ,
                'line2'       => get_user_meta( $user->ID , 'billing_address_2' , true ) ,
                'city'        => get_user_meta( $user->ID , 'billing_city' , true ) ,
                'state'       => get_user_meta( $user->ID , 'billing_state' , true ) ,
                'postal_code' => get_user_meta( $user->ID , 'billing_postcode' , true ) ,
                'country'     => get_user_meta( $user->ID , 'billing_country' , true ) ,
            ) ,
            'fname'   => $billing_first_name ,
            'lname'   => $billing_last_name ,
            'phone'   => get_user_meta( $user->ID , 'billing_phone' , true ) ,
            'email'   => $user->user_email ,
                ) ;
        return $userdata ;
    }

    /**
     * Prepare metadata to display in Stripe.
     * May be useful to keep track the users/orders
     */
    public function prepare_metadata_from_order( $order_id , $auto_topup = null ) {
        $metadata = array(
            'Order' => '#' . $order_id ,
                ) ;

        if( $auto_topup ) {
            $metadata[ 'User' ] = '#' . $auto_topup->get_user_id() ;
        }

        $metadata[ 'Wallet Topup Mode' ] = 'automatic' ;
        $metadata[ 'Site Url' ]          = esc_url( get_site_url() ) ;
        return $metadata ;
    }

    /**
     * Maybe create Stripe Customer
     */
    public function maybe_create_customer( $args = array() ) {

        //Check if the user has already registered as Stripe Customer
        $stripe_customer = HRW_Stripe_API_Request::request( 'retrieve_customer' , array(
                    'id' => $this->get_customer_from_user() ,
                ) ) ;

        //If so then create new stripe customer
        if( ! is_wp_error( $stripe_customer ) && ($saved_stripe_customer_deleted = HRW_Stripe_API_Request::is_customer_deleted( $stripe_customer )) ) {
            delete_user_meta( get_current_user_id() , '_hrw_stripe_customer_id' ) ;
        }

        if( is_wp_error( $stripe_customer ) || $saved_stripe_customer_deleted ) {
            $stripe_customer = HRW_Stripe_API_Request::request( 'create_customer' , HRW_Stripe_API_Request::prepare_customer_details( $args ) ) ;
        }

        if( is_wp_error( $stripe_customer ) ) {
            throw new Exception( HRW_Stripe_API_Request::get_last_error_message() ) ;
        }

        update_user_meta( get_current_user_id() , '_hrw_stripe_customer_id' , $stripe_customer->id ) ;
        return $stripe_customer ;
    }

    /**
     * Attach payment method to Customer via Intent
     */
    public function attach_pm_to_customer( $intent ) {
        if( ! isset( $intent->customer ) || ! $intent->customer ) {
            throw new Exception( __( 'Stripe: Couldn\'t find valid customer to attach payment method.' , HRW_LOCALE ) ) ;
        }

        $pm = HRW_Stripe_API_Request::request( 'retrieve_pm' , array( 'id' => $intent->payment_method ) ) ;

        if( is_wp_error( $pm ) ) {
            throw new Exception( HRW_Stripe_API_Request::get_last_error_message() ) ;
        }

        $pm->attach( array( 'customer' => $intent->customer ) ) ;
        return $pm ;
    }

    /**
     * Clear metas which is authorized by the User
     */
    public function clear_authorized_metas( $auto_topup_id ) {
        delete_post_meta( $auto_topup_id , 'hrw_stripe_customer_id' ) ;
        delete_post_meta( $auto_topup_id , 'hrw_stripe_payment_method' ) ;
    }

    /**
     * Stripe error logger
     */
    public function log_err( $log ) {
        if( empty( self::$log ) ) {
            self::$log = wc_get_logger() ;
        }
        self::$log->log( 'info' , wc_print_r( $log , true ) , array( 'source' => 'hrw-stripe' ) ) ;
    }

    /**
     * Get saved Stripe intent from Order
     */
    public function get_intent_from_order( $order ) {
        return get_post_meta( $order->get_id() , '_hrw_stripe_si' , true ) ;
    }

    /**
     * Get saved Stripe customer from the user
     * 
     * @return string
     */
    public function get_customer_from_user( $user_id = '' ) {
        $user_id = $user_id ? $user_id : get_current_user_id() ;
        return get_user_meta( $user_id , '_hrw_stripe_customer_id' , true ) ;
    }

    /**
     * Get saved Stripe customer ID from wallet
     * 
     * @return string
     */
    public function get_stripe_customer_from_wallet( $auto_topup ) {
        return get_post_meta( $auto_topup->get_id() , 'hrw_stripe_customer_id' , true ) ;
    }

    /**
     * Get saved Stripe paymentMethod ID from wallet
     * 
     * @return string
     */
    public function get_stripe_pm_from_wallet( $auto_topup ) {
        return get_post_meta( $auto_topup->get_id() , 'hrw_stripe_payment_method' , true ) ;
    }

}

return new HRW_Stripe_Gateway() ;
