<?php

if ( ! defined( 'YITH_WCARC_VERSION' ) ) {
	exit( 'Direct access forbidden.' );
}

/**
 *
 *
 * @class      YITH_Role_Changer_User_Email
 * @package    Yithemes
 * @since      Version 1.0.0
 * @author     Carlos Mora <carlos.eugenio@yourinspiration.it>
 *
 */

if ( ! class_exists( 'YITH_Role_Changer_User_Email' ) ) {
	/**
	 * Class YITH_Role_Changer_User_Email
	 *
	 * @author Carlos Mora <carlos.eugenio@yourinspiration.it>
	 */
	class YITH_Role_Changer_User_Email extends WC_Email {

		public $user_id = null;
		public $order_id = null;


		public function __construct() {

			$this->id = 'yith_ywarc_user_email';
			$this->customer_email = true;


			$this->title = esc_html__( 'Automatic Role Changer email for User', 'yith-automatic-role-changer-for-woocommerce' );
			$this->description = esc_html__( 'The user will receive an email when a order with roles to be 
			granted passes to "Completed" or "Processing" status.', 'yith-automatic-role-changer-for-woocommerce' );

			$this->heading = esc_html__( "You've earned roles", 'yith-automatic-role-changer-for-woocommerce' );
			$this->subject = esc_html__( "You've earned roles", 'yith-automatic-role-changer-for-woocommerce' );

			$this->template_html = 'emails/role-changer-user.php';

			add_action( 'send_email_to_user', array( $this, 'trigger' ), 10, 3 );
			add_filter( 'woocommerce_email_styles', array( $this, 'style' ) );

			parent::__construct();
		}

		public function trigger( $valid_rules, $user_id, $order_id ) {
			if ( ! $this->is_enabled() ) {
				return;
			}
			$this->object = $valid_rules;
			$this->user_id = $user_id;
			$this->order_id = $order_id;

			$order = wc_get_order( $order_id );
			if ( $order instanceof WC_Data ) {
				$this->recipient = $order->get_billing_email();
			} else {
				$this->recipient = $order->billing_email;
			}

			$this->send( $this->get_recipient(),
				$this->get_subject(),
				$this->get_content(),
				$this->get_headers(),
				$this->get_attachments() );
		}


		public function style( $style ) {
			$style = $style .
				".ywarc_metabox_gained_role {
				border: #dcdada solid 1px;
				padding: 15px;
				text-align: center;
				margin: 10px auto;
				width: 270px;
				}
				
				.ywarc_metabox_role_name {
				font-size: 24px;
				color: grey;
				}
				.ywarc_metabox_dates {
				font-size: 12px;
				margin-top: 10px;
				}";
			return $style;
		}


		public function get_content_html() {
			return wc_get_template_html( $this->template_html, array(
				'email_heading' => $this->get_heading(),
				'sent_to_admin' => false,
				'plain_text'    => false,
				'email'         => $this
			),
				'',
				YITH_WCARC_TEMPLATE_PATH );
		}


		public function get_content_plain() {
			return wc_get_template_html( $this->template_plain, array(
				'email_heading' => $this->get_heading(),
				'sent_to_admin' => false,
				'plain_text'    => true,
				'email'         => $this
			),
				'',
				YITH_WCARC_TEMPLATE_PATH );
		}

		public function init_form_fields() {
			$this->form_fields = array(
				'enabled'    => array(
					'title'   => esc_html__( 'Enable/Disable', 'yith-automatic-role-changer-for-woocommerce' ),
					'type'    => 'checkbox',
					'label'   => esc_html__( 'Enable this email notification', 'yith-automatic-role-changer-for-woocommerce' ),
					'default' => 'yes'
				),
				'subject'    => array(
					'title'       => esc_html__( 'Subject', 'yith-automatic-role-changer-for-woocommerce' ),
					'type'        => 'text',
					'description' => sprintf( esc_html__( 'This controls the email subject line. Leave blank to use the default subject: <code>%s</code>.', 'yith-automatic-role-changer-for-woocommerce' ), $this->subject ),
					'placeholder' => '',
					'default'     => '',
					'desc_tip'    => true
				),
				'heading'    => array(
					'title'       => esc_html__( 'Email Heading', 'yith-automatic-role-changer-for-woocommerce' ),
					'type'        => 'text',
					'description' => sprintf( esc_html__( 'This controls the main heading included in the email notification. Leave blank to use the default heading: <code>%s</code>.', 'yith-automatic-role-changer-for-woocommerce' ), $this->heading ),
					'placeholder' => '',
					'default'     => '',
					'desc_tip'    => true
				),
				'email_type' => array(
					'title'       => esc_html__( 'Email type', 'yith-automatic-role-changer-for-woocommerce' ),
					'type'        => 'select',
					'description' => esc_html__( 'Choose which format of email to send.', 'yith-automatic-role-changer-for-woocommerce' ),
					'default'     => 'html',
					'class'       => 'email_type wc-enhanced-select',
					'options'     => $this->get_email_type_options(),
					'desc_tip'    => true
				)
			);
		}

	}

}
return new YITH_Role_Changer_User_Email();
