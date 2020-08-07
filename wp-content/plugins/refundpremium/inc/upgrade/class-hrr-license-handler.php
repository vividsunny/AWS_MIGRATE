<?php
/**
 * Plugin Name: WordPress Auto Updater.
 * Version: 2.0
 */
if ( ! class_exists( 'HRR_License_Handler' ) ) {

	/**
	 * Class.
	 */
	class HRR_License_Handler {

		/**
		 * Plugin Version Number.
		 */
		protected $version ;

		/**
		 * Plugin Directory Slug.
		 */
		protected $dir_slug ;

		/**
		 * Secret Key.
		 */
		protected $secret_key = '8b1a9953c4611296a827abf8c47804d7' ;

		/**
		 * Site Url.
		 */
		protected $update_path = 'https://hoicker.com/' ;

		/**
		 * Item Key Name.
		 */
		protected $item_key_name = 'refund-premium' ;

		/**
		 * Option name.
		 */
		private $license_option = 'hrrp_products_license_activation' ;

		/**
		 * Key option name.
		 */
		private $license_key_option = 'hrrp_products_license_activation_key' ;

		/**
		 * Class Initialization.
		 */
		public function __construct( $plugin_version, $plugin_slug ) {
			$this->version  = $plugin_version ;
			$this->dir_slug = $plugin_slug ;
			add_action( 'wp_ajax_hrr_license_handler' , array( $this , 'license_handler' ) ) ;
		}

		/**
		 * Include activation page template.
		 */
		public function show_activation_panel( $notice = '' ) {
			$name    = ( $this->license_key() ) ? 'Deactivate' : 'Activate' ;
			$handler = ( $this->license_key() ) ? 'deactivate' : 'activate' ;
			?>
			<form method='POST'>
				<div class="hrr-license-verification-label">
					<label><?php esc_html_e( 'Purchase Code' , 'refund' ) ; ?></label>
				</div>
				<div class="hrr-license-activation-content">
					<input type='text' id='hrr_license_key' name='hrr_license_key'/>
					<input type='hidden' id='hrr_license_handler_value' name='hrr_license_handler_value' value='<?php echo esc_attr( $handler ) ; ?>'/>
					<input type="submit" id='hrr-license-verification-btn' name="hrr_license_handler_button" value="<?php echo esc_attr( $name ) ; ?>" class="button button-primary"/>
					<p class='hrr-error'></p>
										<p class='hrr-success'></p>
					<h4><?php esc_html_e( 'Where can I find my License Key?' , 'refund' ) ; ?></h4>
					<ul>
						<li><a href="https://hoicker.com/my-account/" target="blank"><?php esc_html_e( 'Login' , 'refund' ) ; ?></a><?php esc_html_e( ' to Hoicker' , 'refund' ) ; ?></li>
						<li><?php esc_html_e( 'Go to My Account page' , 'refund' ) ; ?></li>
						<li><?php esc_html_e( 'In Orders section you will find your License Key' , 'refund' ) ; ?></li>
					</ul>
				</div>
				<div class='clear'></div>
			</form>
			<?php
		}

		/**
		 * Activate or Deactivate License Key.
		 */
		public function license_handler() {

			check_ajax_referer( 'hrr-license-security' , 'hrr_security' ) ;

			try {
				$license_key        = isset($_POST[ 'license_key' ]) ? wc_clean( $_POST[ 'license_key' ] ) : '';
				$activation_handler = isset($_POST[ 'handler' ]) ? wc_clean( $_POST[ 'handler' ] ) : '';
				if ( 'deactivate' == $activation_handler ) {
					$this->deactivate( $license_key ) ;
				} elseif ( 'activate' == $activation_handler ) {
					$this->activate( $license_key ) ;
				}
			} catch ( Exception $ex ) {
				wp_send_json_error( array( 'error' => $ex->getMessage() ) ) ;
			}
		}

		/**
		 * Verify data from API Endpoint.
		 */
		protected function verify_activate_data( $action, $license_key ) {
			$necessary_data = array(
				'action'         => $action ,
				'license_key'    => $license_key ,
				'current_site'   => site_url() ,
				'plugin_version' => $this->version ,
				'slug'           => $this->dir_slug ,
				'secret_key'     => $this->secret_key ,
				'item_key_name'  => $this->item_key_name ,
				'free'           => hrr_is_premium() ,
				'wc_version'     => WC_VERSION ,
				'wp_version'     => get_bloginfo( 'version' ) ,
					) ;

			$request = wp_remote_post( $this->query_arg_url() , array( 'body' => $necessary_data ) ) ;

			return $request ;
		}

		/**
		 * Activate license key for this site.
		 */
		protected function activate( $license_key ) {
			try {
				$response_data      = array() ;
				$activated_response = $this->verify_activate_data( 'activate_licensekey' , $license_key ) ;
				if ( ! is_wp_error( $activated_response ) || 200 === wp_remote_retrieve_response_code( $activated_response )) {
					$response = json_decode( wp_remote_retrieve_body( $activated_response ) ) ;
					if ( is_object( $response ) && $response->success ) {
						update_option( $this->license_option , $response ) ;
						update_option( $this->license_key_option , $response->license_key ) ;
						$response_data[ 'success_msg' ] = esc_html__( 'Activated Successfully' , 'refund' ) ;
					} else {
						throw new Exception( $this->error_messages( $response->errorcode ) ) ;
					}
				} else {
					throw new Exception( $activated_response->get_error_message() ) ;
				}
				wp_send_json_success( $response_data ) ;
			} catch ( Exception $ex ) {
				wp_send_json_error( array( 'error_msg' => $ex->getMessage() ) ) ;
			}
		}

		/**
		 * Deactivate license key for this site.
		 */
		protected function deactivate( $license_key ) {

			try {
				$saved_license_key = $this->license_key() ;
				if ( $license_key != $saved_license_key ) {
					throw new Exception( esc_html__( 'Please Provide Activated License Key' , 'refund' ) ) ;
				}

				$response_data        = array() ;
				$deactivated_response = $this->verify_activate_data( 'deactivate_licensekey' , $license_key ) ;
				if ( ! is_wp_error( $deactivated_response ) || 200 === wp_remote_retrieve_response_code( $deactivated_response ) ) {
					$response = json_decode( wp_remote_retrieve_body( $deactivated_response ) ) ;
					if ( is_object( $response ) && $response->success ) {
						delete_option( $this->license_option ) ;
						delete_option( $this->license_key_option ) ;
						$response_data[ 'success_msg' ] = esc_html__( 'Deactivated Successfully' , 'refund' ) ;
					} else {
						throw new Exception( $this->error_messages( $response->errorcode ) ) ;
					}
				} else {
					throw new Exception( $deactivated_response->get_error_message() ) ;
				}
				wp_send_json_success( $response_data ) ;
			} catch ( Exception $ex ) {
				wp_send_json_error( array( 'error_msg' => $ex->getMessage() ) ) ;
			}
		}

		/**
		 * License Key.
		 */
		public function license_key() {

			return get_option( $this->license_key_option ) ;
		}

		/**
		 * API Endpoint Url.
				 * 
				 * @return string
		 */
		protected function query_arg_url() {
			return esc_url( add_query_arg( array( 'wc-api' => 'hr_autoupdater' ) , $this->update_path ) ) ;
		}

		/**
		 * Error Codes.
				 * 
				 * @return string
		 */
		protected function error_messages( $error_code ) {

			$error_messages_array = array(
				'5001' => esc_html__( 'Invalid License Key' , 'refund' ) ,
				'5002' => esc_html__( 'Already Verified License Key' , 'refund' ) ,
				'5003' => esc_html__( 'Support Expired' , 'refund' ) ,
				'5004' => esc_html__( 'License Key not Verified' , 'refund' ) ,
				'5005' => esc_html__( 'Invalid Credentials' , 'refund' ) ,
				'5006' => esc_html__( 'license Count exist' , 'refund' ) ,
				'5007' => esc_html__( 'Incorrect Product' , 'refund' ) ,
					) ;

			$error_message = isset( $error_messages_array[ $error_code ] ) ? $error_messages_array[ $error_code ] : '' ;

			return $error_message ;
		}

	}

}

