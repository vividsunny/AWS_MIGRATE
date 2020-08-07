<?php

namespace Woo_MP\Payment_Gateways\Eway;

defined( 'ABSPATH' ) || die;

/**
 * eWAY payment meta box helper.
 *
 * The core payment meta box controller uses this class to add
 * all the gateway-specific parts of the frontend.
 */
class Payment_Meta_Box_Helper implements \Woo_MP\Payment_Gateway\Payment_Meta_Box_Helper {

    /**
     * The eWAY API key.
     *
     * @var string
     */
    private $api_key;

    /**
     * The eWAY API password.
     *
     * @var string
     */
    private $api_password;

    /**
     * Set up initial values.
     */
    public function __construct() {
        $this->api_key      = get_option( 'woo_mp_eway_api_key' );
        $this->api_password = get_option( 'woo_mp_eway_api_password' );
    }

    public function get_currency( $order_currency ) {
        return get_woocommerce_currency();
    }

    public function validation() {
        $validation   = [];
        $settings_url = WOO_MP_SETTINGS_URL . '&section=eway';

        if ( ! $this->api_key ) {
            $validation[] = [
                'message' => "Please <a href='$settings_url'>set your API key</a>. " . WOO_MP_CONFIG_HELP,
                'type'    => 'info',
                'valid'   => false,
            ];
        }

        if ( ! $this->api_password ) {
            $validation[] = [
                'message' => "Please <a href='$settings_url'>set your API password</a>. " . WOO_MP_CONFIG_HELP,
                'type'    => 'info',
                'valid'   => false,
            ];
        }

        return $validation;
    }

    public function enqueue_assets() {
        \Woo_MP\script( 'eway-script', WOO_MP_URL . '/includes/payment-gateways/eway/assets/script.js' );
        wp_enqueue_script( 'woo-mp-payment-processor-script', 'https://api.ewaypayments.com/JSONP/v3/js', [], null, true );
    }

    public function client_data() {
        return [
            'responseCodeMessages' => Transaction_Processor::get_response_code_messages(),
        ];
    }

    public function get_templates() {
        return [
            'charge-form' => WOO_MP_PATH . '/includes/payment-gateways/eway/templates/charge-form.php',
        ];
    }

}
