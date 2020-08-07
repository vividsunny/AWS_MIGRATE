<?php

namespace Woo_MP\Payment_Gateways\Eway;

use Woo_MP\Detailed_Exception;

defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Eway\Rapid' ) ) {
    require WOO_MP_PATH . '/includes/payment-gateways/eway/libraries/eway-rapid-php-1.3.4/include_eway.php';
}

/**
 * Parent class for eWAY transaction processors.
 */
class Transaction_Processor extends \Woo_MP\Payment_Gateway\Transaction_Processor {

    /**
     * eWAY API client.
     *
     * @var \Eway\Rapid\Contract\Client
     */
    private $client;

    /**
     * Initialize eWAY SDK.
     */
    public function __construct() {
        $this->client = \Eway\Rapid::createClient(
            get_option( 'woo_mp_eway_api_key' ),
            get_option( 'woo_mp_eway_api_password' ),
            get_option( 'woo_mp_eway_sandbox_mode' ) === 'yes'
            ? \Eway\Rapid\Client::MODE_SANDBOX
            : \Eway\Rapid\Client::MODE_PRODUCTION
        );
    }

    /**
     * Make a request to eWAY.
     *
     * Errors are automatically handled.
     *
     * @param  string             $method   The method to call on the client.
     * @param  mixed              $args,... The arguments to pass to the method.
     * @return mixed                        The response.
     * @throws Detailed_Exception           For detailed errors.
     */
    protected function request( $method ) {
        $response   = call_user_func_array( [ $this->client, $method ], array_slice( func_get_args(), 1 ) );
        $error_code = null;

        if ( $response->getErrors() ) {
            $error_code = $response->getErrors()[0];

            if ( $error_code === 'S9993' ) {
                throw new Detailed_Exception(
                    'Sorry, the API Key, API Password, or both, are not valid. Please check your settings and try again.',
                    $error_code
                );
            }
        }

        if ( isset( $response->Transactions ) && ! $response->Transactions[0]->TransactionStatus ) {
            $error_code = explode( ',', $response->Transactions[0]->ResponseMessage )[0];
        }

        if ( $error_code ) {
            throw new Detailed_Exception( \Eway\Rapid::getMessage( $error_code ), $error_code );
        }

        return $response;
    }

    /**
     * Get all eWAY response code messages.
     *
     * @return array Associative array with codes as keys and messages as values.
     */
    public static function get_response_code_messages() {
        return parse_ini_file( WOO_MP_PATH . '/includes/payment-gateways/eway/libraries/eway-rapid-php-1.3.4/resource/lang/en.ini' );
    }

}
