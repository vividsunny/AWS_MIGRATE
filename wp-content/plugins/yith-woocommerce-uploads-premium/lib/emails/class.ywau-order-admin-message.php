<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( "WC_Email" ) ) {
	require_once( WC()->plugin_path() . '/includes/emails/class-wc-email.php' );
}

if ( ! class_exists( "YWAU_Order_Admin_Message" ) ) {
	/**
	 * A custom Order Email class
	 *
	 * @since 0.1
	 * @extends \WC_Email
	 */
	class YWAU_Order_Admin_Message extends WC_Email {
		/**
		 * Message sent by admin from the order page
		 *
		 * @var string
		 */
		public $admin_message = '';

		/**
		 * Set email defaults
		 *
		 * @since 0.1
		 */
		public function __construct() {
			// set ID, this simply needs to be a unique name
			$this->id = 'ywau_order_admin_message';

			// this is the title in WooCommerce Email settings
			$this->title = 'YITH Uploads Order Message';

			// this is the description in WooCommerce email settings
			$this->description = esc_html__( 'Send a message to the customer regarding the order', 'yith-woocommerce-additional-uploads' );

			// these are the default heading and subject lines that can be overridden using the settings
			$this->heading = esc_html__( 'Your order has been updated', 'yith-woocommerce-additional-uploads' );
			$this->subject = esc_html__( '[{site_title}] Your order ({order_number}) - {order_date} has been updated', 'yith-woocommerce-additional-uploads' );

			// these define the locations of the templates that this email should use, we'll just use the new order template since this email is similar
			$this->template_html  = 'emails/ywau-order-admin-message.php';
			$this->template_plain = 'emails/plain/ywau-order-admin-message.php';

			// Trigger on specific action call
			add_action( 'ywau_email_order_admin_message_notification', array( $this, 'trigger' ), 10, 2 );

			// Call parent constructor to load any other defaults not explicity defined here
			parent::__construct();
		}

		/**
		 * Determine if the email should actually be sent and setup email merge variables
		 *
		 * @since 0.1
		 *
		 * @param int $order_id
		 */
		public function trigger( $order_id, $message ) {
			//  Check if email notification is enabled...
			if ( ! $this->is_enabled() ) {
				return;
			}

			//  Check if order id is valid...
			if ( ! $order_id ) {
				return;
			}

			$this->object    = wc_get_order( $order_id );
			$this->recipient = $this->object->billing_email;

			if ( ! $this->get_recipient() ) {
				return;
			}

			$this->admin_message = $message;
			// setup order object


			$this->find['order-date']   = '{order_date}';
			$this->find['order-number'] = '{order_number}';

			$this->replace['order-date']   = date_i18n( wc_date_format(), strtotime( yit_get_prop( $this->object, 'order_date' ) ) );
			$this->replace['order-number'] = $this->object->get_order_number();

			$result = $this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );

			return $result;
		}


		/**
		 * get_content_html function.
		 *
		 * @since 0.1
		 * @return string
		 */
		public function get_content_html() {
			ob_start();
			wc_get_template( $this->template_html, array(
				'order'         => $this->object,
				'email_heading' => $this->get_heading(),
				'message'       => $this->admin_message,
				'email_type'    => $this->email_type,
				'sent_to_admin' => false,
				'plain_text'    => false,
			),
				'',
				YITH_YWAU_TEMPLATES_DIR . '/' );

			return ob_get_clean();
		}

		/**
		 * get_content_plain function.
		 *
		 * @since 0.1
		 * @return string
		 */
		public function get_content_plain() {
			ob_start();
			wc_get_template( $this->template_plain, array(
				'order'         => $this->object,
				'email_heading' => $this->get_heading(),
				'message'       => $this->admin_message,
				'email_type'    => $this->email_type,
				'sent_to_admin' => false,
				'plain_text'    => true,
			) );

			return ob_get_clean();
		}


		/**
		 * Initialize Settings Form Fields
		 *
		 * @since 0.1
		 */
		public function init_form_fields() {
			$this->form_fields = array(
				'enabled'    => array(
					'title'   => esc_html__( 'Enable/Disable', 'woocommerce' ),
					'type'    => 'checkbox',
					'label'   => esc_html__( 'Enable this email notification', 'woocommerce' ),
					'default' => 'yes',
				),
				'subject'    => array(
					'title'       => esc_html__( 'Subject', 'woocommerce' ),
					'type'        => 'text',
					'description' => sprintf( esc_html__( 'Defaults to <code>%s</code>', 'woocommerce' ), $this->subject ),
					'placeholder' => '',
					'default'     => '',
				),
				'heading'    => array(
					'title'       => esc_html__( 'Email Heading', 'woocommerce' ),
					'type'        => 'text',
					'description' => sprintf( esc_html__( 'Defaults to <code>%s</code>', 'woocommerce' ), $this->heading ),
					'placeholder' => '',
					'default'     => '',
				),
				'email_type' => array(
					'title'       => esc_html__( 'Email type', 'woocommerce' ),
					'type'        => 'select',
					'description' => esc_html__( 'Choose which format of email to send.', 'woocommerce' ),
					'default'     => 'html',
					'class'       => 'email_type wc-enhanced-select',
					'options'     => $this->get_email_type_options(),
				),
			);
		}
	} // end \YWAU_Order_Admin_Message class
}

return new YWAU_Order_Admin_Message();
