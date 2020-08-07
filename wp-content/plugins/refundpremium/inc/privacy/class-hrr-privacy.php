<?php
/*
 * GDPR Compliance.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'HRR_Privacy' ) ) :

	/**
	 * HRR_Privacy class.
	 */
	class HRR_Privacy {

		/**
		 * HRR_Privacy constructor.
		 */
		public function __construct() {
			$this->init_hooks() ;
		}

		/**
		 * Register plugin.
		 */
		public function init_hooks() {
			//This hook registers Booking System privacy content.
			add_action( 'admin_init' , array( __CLASS__ , 'register_privacy_content' ) , 20 ) ;
		}

		/**
		 * Register Privacy Content.
		 */
		public static function register_privacy_content() {
			if ( ! function_exists( 'wp_add_privacy_policy_content' ) ) {
				return ;
			}

			$content = self::get_privacy_message() ;
			if ( $content ) {
				wp_add_privacy_policy_content( esc_html__( 'Refund' , 'refund' ) , $content ) ;
			}
		}

		/**
		 * Prepare Privacy Content.
		 */
		public static function get_privacy_message() {

			return self::get_privacy_message_html() ;
		}

		/**
		 * Get Privacy Content.
		 */
		public static function get_privacy_message_html() {
			ob_start() ;
			?>
			<p><?php esc_html_e( 'This includes the basics of what personal data your store may be collecting, storing and sharing. Depending on what settings are enabled and which additional plugins are used, the specific information shared by your store will vary.' , 'refund' ); ?></p>
			<h2><?php esc_html_e( 'WHAT DOES THE PLUGIN DO?' , 'refund' ) ; ?></h2>
			<p><?php esc_html_e( 'Allow users to request refund from their my account page.' , 'refund' ) ; ?> </p>
			<h2><?php esc_html_e( 'WHAT WE COLLECT AND STORE?' , 'refund' ) ; ?></h2>
			<h4><?php esc_html_e( '- USER ID' , 'refund' ) ; ?></h4>
			<ul>
				<li>
					<?php esc_html_e( 'The User id is used for identifying the user whom the refund request have been processed' , 'refund' ) ; ?>
				</li>
			</ul>
			<?php
			$contents = ob_get_contents() ;
			ob_end_clean() ;

			return $contents ;
		}

	}

	new HRR_Privacy() ;

endif;
