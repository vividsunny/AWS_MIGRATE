<?php

/**
 * Refund Tab.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( class_exists( 'HRR_Refund_Tab' ) ) {
	return new HRR_Refund_Tab() ;
}

/**
 * HRR_Refund_Tab.
 */
class HRR_Refund_Tab extends HRR_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'refund' ;
		$this->label = esc_html__( 'Refund' , 'refund' ) ;
		$this->code  = 'fa-handshake-o' ;

		parent::__construct() ;
	}

	/**
	 * Get sections.
	 */
	public function get_sections() {
		$sections = array(
			'settings'     => array(
				'label' => esc_html__( 'Settings' , 'refund' ) ,
				'code'  => 'fa-sliders'
			) ,
			'notification' => array(
				'label' => esc_html__( 'Notifications' , 'refund' ) ,
				'code'  => 'fa-bell-o'
			) ,
			'localization' => array(
				'label' => esc_html__( 'Localization' , 'refund' ) ,
				'code'  => 'fa-pencil-square-o'
			) ,
				) ;

		return apply_filters( $this->plugin_slug . '_get_sections_' . $this->id , $sections ) ;
	}

	/**
	 * Get settings for refund section array.
	 */
	public function settings_section_array() {
		$section_fields = array() ;

		//General Section Start.
		$section_fields[] = array(
			'type'      => 'hrr_custom_fields' ,
			'hrr_field' => 'section_start' ,
				) ;
		$section_fields[] = array(
			'type'  => 'title' ,
			'title' => esc_html__( 'General Settings' , 'refund' ) ,
			'id'    => 'hrr_refund_options' ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Enable Refund' , 'refund' ) ,
			'type'    => 'checkbox' ,
			'default' => 'no' ,
			'desc'    => esc_html__( 'If enabled, customers can request refund from their "My Account" page under "Orders" menu.' , 'refund' ) ,
			'id'      => $this->get_option_key( 'refund_request' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Enable Partial Refund' , 'refund' ) ,
			'type'    => 'checkbox' ,
			'default' => 'no' ,
			'desc'    => esc_html__( 'If enabled, customers can request partial refund for their orders.' , 'refund' ) ,
			'id'      => $this->get_option_key( 'partial_refund' ) ,
			'class'   => 'hrr-premium-info-settings' ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Enable Refund for Sale Products' , 'refund' ) ,
			'type'    => 'checkbox' ,
			'default' => 'no' ,
			'desc'    => esc_html__( 'If enabled, customers can request a refund for products with sale price.' , 'refund' ) ,
			'id'      => $this->get_option_key( 'refund_for_sale_items' ) ,
			'class'   => 'hrr-premium-info-settings' ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Refund Tax Cost' , 'refund' ) ,
			'type'    => 'checkbox' ,
			'default' => 'no' ,
			'desc'    => esc_html__( 'If enabled, applicable tax cost will be refunded along with the product price.' , 'refund' ) ,
			'id'      => $this->get_option_key( 'refund_tax' ) ,
				) ;
		$section_fields[] = array(
			'title'    => esc_html__( 'Display Refund Button when Order Status becomes' , 'refund' ) ,
			'type'     => 'multiselect' ,
			'class'    => 'hrr_select2 hrr-premium-info-settings' ,
			'default'  => array( 'completed' ) ,
			'options'  => hrr_get_wc_order_statuses() ,
			'desc_tip' => true ,
			'desc'     => esc_html__( 'Refund button will be displayed on the each order[My Account Page] when the order status reaches the status which are selected here' , 'refund' ) ,
			'id'       => $this->get_option_key( 'order_status' ) ,
				) ;
		$section_fields[] = array(
			'type' => 'sectionend' ,
			'id'   => 'hrr_refund_options' ,
				) ;
		$section_fields[] = array(
			'type'      => 'hrr_custom_fields' ,
			'hrr_field' => 'section_end' ,
				) ;
		//General Section End.
		//Restriction Section Start.
		$section_fields[] = array(
			'type'      => 'hrr_custom_fields' ,
			'hrr_field' => 'section_start' ,
				) ;
		$section_fields[] = array(
			'type'  => 'title' ,
			'title' => esc_html__( 'Refund Restriction Settings' , 'refund' ) ,
			'id'    => 'hrr_restrict_options' ,
				) ;
		$section_fields[] = array(
			'title'             => esc_html__( 'Minimum Order Amount Required to Request a Refund' , 'refund' ) ,
			'type'              => 'number' ,
			'default'           => '1' ,
			'custom_attributes' => array( 'min' => '0.01' , 'required' => 'required' , 'step' => 'any' ) ,
			'id'                => $this->get_option_key( 'minimum_order_amount' ) ,
			'class'             => 'hrr-premium-info-settings' ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Refund Requesting Time' , 'refund' ) ,
			'type'    => 'select' ,
			'options' => array( '1' => esc_html__( 'No Limit' , 'refund' ) , '2' => esc_html__( 'Limited Duration' , 'refund' ) ) ,
			'default' => '1' ,
			'id'      => $this->get_option_key( 'request_time_period' ) ,
			'class'   => 'hrr-premium-info-settings' ,
				) ;
		$section_fields[] = array(
			'title'             => esc_html__( 'Allow Requesting Refund for' , 'refund' ) ,
			'type'              => 'number' ,
			'custom_attributes' => array( 'min' => '1' , 'required' => 'required' ) ,
			'default'           => '1' ,
			'id'                => $this->get_option_key( 'request_time_period_value' ) ,
			'desc'              => esc_html__( 'day(s) from the date of purchase' , 'refund' ) ,
			'class'             => 'hrr-premium-info-settings' ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Enable Refund for' , 'refund' ) ,
			'type'    => 'select' ,
			'options' => array(
				'1' => esc_html__( 'All Product (s)' , 'refund' ) ,
				'2' => esc_html__( 'Include Product(s)' , 'refund' ) ,
				'3' => esc_html__( 'All Categories' , 'refund' ) ,
				'4' => esc_html__( 'Include Categories' , 'refund' )
			) ,
			'default' => '1' ,
			'id'      => $this->get_option_key( 'refundanable_product' ) ,
			'class'   => 'hrr-premium-info-settings' ,
				) ;
		$section_fields[] = array(
			'title'       => esc_html__( 'Include Product(s)' , 'refund' ) ,
			'id'          => $this->get_option_key( 'included_product' ) ,
			'class'       => 'hrr-premium-info-settings' ,
			'action'      => 'hrr_product_search' ,
			'type'        => 'hrr_custom_fields' ,
			'list_type'   => 'products' ,
			'hrr_field'   => 'ajaxmultiselect' ,
			'desc_tip'    => true ,
			'desc'        => esc_html__( 'You can also choose multiple products' , 'refund' ) ,
			'placeholder' => esc_html__( 'Select a Product' , 'refund' ) ,
			'allow_clear' => true ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Include Categories' , 'refund' ) ,
			'type'    => 'multiselect' ,
			'class'   => 'hrr_select2 hrr-premium-info-settings' ,
			'options' => hrr_get_wc_categories() ,
			'id'      => $this->get_option_key( 'included_category' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Enable Refund for' , 'refund' ) ,
			'type'    => 'select' ,
			'options' => array(
				'1' => esc_html__( 'All user(s)' , 'refund' ) ,
				'2' => esc_html__( 'Selected user(s)' , 'refund' ) ,
				'3' => esc_html__( 'All User Role(s)' , 'refund' ) ,
				'4' => esc_html__( 'Selected User Role(s)' , 'refund' )
			) ,
			'default' => '1' ,
			'id'      => $this->get_option_key( 'refundable_user' ) ,
			'class'   => 'hrr-premium-info-settings' ,
				) ;
		$section_fields[] = array(
			'title'       => esc_html__( 'Include User(s)' , 'refund' ) ,
			'id'          => $this->get_option_key( 'included_user' ) ,
			'action'      => 'hrr_customers_search' ,
			'class'       => 'hrr-premium-info-settings' ,
			'type'        => 'hrr_custom_fields' ,
			'list_type'   => 'customers' ,
			'hrr_field'   => 'ajaxmultiselect' ,
			'placeholder' => esc_html__( 'Select a User' , 'refund' ) ,
			'allow_clear' => true ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Include User Roles' , 'refund' ) ,
			'type'    => 'multiselect' ,
			'class'   => 'hrr_select2 hrr-premium-info-settings' ,
			'options' => hrr_get_user_roles() ,
			'id'      => $this->get_option_key( 'included_user_role' ) ,
				) ;
		$section_fields[] = array(
			'type' => 'sectionend' ,
			'id'   => 'hrr_restrict_options' ,
				) ;
		$section_fields[] = array(
			'type'      => 'hrr_custom_fields' ,
			'hrr_field' => 'section_end' ,
				) ;
		//Restriction Section End.
		//From Settings Section Start.
		$section_fields[] = array(
			'type'      => 'hrr_custom_fields' ,
			'hrr_field' => 'section_start' ,
				) ;
		$section_fields[] = array(
			'type'  => 'title' ,
			'title' => esc_html__( 'Refund Request Form Settings' , 'refund' ) ,
			'id'    => 'hrr_form_options' ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Display Refund Mode' , 'refund' ) ,
			'type'    => 'checkbox' ,
			'default' => 'yes' ,
			'id'      => $this->get_option_key( 'refund_method' ) ,
			'class'   => 'hrr-premium-info-settings' ,
				) ;
		$section_fields[] = array(
			'title'    => esc_html__( 'Preloaded Reason for Refund' , 'refund' ) ,
			'type'     => 'textarea' ,
			'default'  => "Incorrect Product,Incorrect Size,Incorrect Color,Product Damaged,Product did not match Description,Didn't meet the Expectation" ,
			'desc'     => esc_html__( "Reasons separated by comma(',')" , 'refund' ) ,
			'desc_tip' => true ,
			'id'       => $this->get_option_key( 'refund_reason' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Dispaly Reason in Detail Field' , 'refund' ) ,
			'type'    => 'checkbox' ,
			'class'   => 'hrr-premium-info-settings' ,
			'desc'    => esc_html__( 'If enabled, field to enter the refund reason in detail will be displayed' , 'refund' ) ,
			'default' => 'yes' ,
			'id'      => $this->get_option_key( 'enable_reason_in_detail' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Reason in Detail Mandatory' , 'refund' ) ,
			'type'    => 'checkbox' ,
			'class'   => 'hrr-premium-info-settings hrr_refund_mandatory_reason_field' ,
			'desc'    => esc_html__( 'If enabled, the user must have to fill the reason in detail for requesting refund' , 'refund' ) ,
			'default' => 'yes' ,
			'id'      => $this->get_option_key( 'mandatory_reason_field' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Enable Conversation' , 'refund' ) ,
			'type'    => 'checkbox' ,
			'default' => 'no' ,
			'id'      => $this->get_option_key( 'enable_conversation' ) ,
			'class'   => 'hrr-premium-info-settings' ,
			'desc'    => esc_html__( 'If enabled, follow up conversation will be allowed for the customer after requesting refund' , 'refund' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Enable File Upload' , 'refund' ) ,
			'type'    => 'checkbox' ,
			'default' => 'no' ,
			'id'      => $this->get_option_key( 'enable_attachment' ) ,
			'class'   => 'hrr-premium-info-settings' ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'File Upload Mandatory' , 'refund' ) ,
			'type'    => 'checkbox' ,
			'default' => 'no' ,
			'id'      => $this->get_option_key( 'upload_mandatory' ) ,
			'desc'    => esc_html__( 'If enabled, the user must have to upload a file for requesting refund' , 'refund' ) ,
			'class'   => 'hrr-premium-info-settings hrr_file_uploads' ,
				) ;
		$section_fields[] = array(
			'title'             => esc_html__( 'Maximum File Size(in KB)' , 'refund' ) ,
			'type'              => 'number' ,
			'default'           => 'no' ,
			'id'                => $this->get_option_key( 'file_size' ) ,
			'desc'              => esc_html__( 'If enabled, the user must have to upload a file for requesting refund' , 'refund' ) ,
			'class'             => 'hrr-premium-info-settings hrr_file_uploads' ,
			'custom_attributes' => array( 'min' => '1' ) ,
				) ;
		$section_fields[] = array(
			'title'    => esc_html__( 'Supported File Type(s)' , 'refund' ) ,
			'type'     => 'textarea' ,
			'default'  => 'jpg,jpeg,png, gif, doc,docx,pdf' ,
			'id'       => $this->get_option_key( 'file_type' ) ,
			'desc_tip' => true ,
			'desc'     => esc_html__( 'Multiple file types separated by comma(,)' , 'refund' ) ,
			'class'    => 'hrr-premium-info-settings hrr_file_uploads' ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'File Upload Mandatory Error Message' , 'refund' ) ,
			'type'    => 'text' ,
			'default' => 'Attach a file to request a refund' ,
			'id'      => $this->get_option_key( 'error_message_for_mandatory' ) ,
			'class'   => 'hrr-premium-info-settings hrr_file_uploads' ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Maximum File size error Message' , 'refund' ) ,
			'type'    => 'text' ,
			'default' => 'Maximum file size must be less than {file_size}KB' ,
			'id'      => $this->get_option_key( 'error_message_for_size' ) ,
			'class'   => 'hrr-premium-info-settings hrr_file_uploads' ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Unsupported File type error Message' , 'refund' ) ,
			'type'    => 'text' ,
			'default' => 'File type not supported. Supported file types: {supported_file_types}' ,
			'id'      => $this->get_option_key( 'error_message_for_type' ) ,
			'class'   => 'hrr-premium-info-settings hrr_file_uploads' ,
				) ;
		$section_fields[] = array(
			'type' => 'sectionend' ,
			'id'   => 'hrr_form_options' ,
				) ;
		$section_fields[] = array(
			'type'      => 'hrr_custom_fields' ,
			'hrr_field' => 'section_end' ,
				) ;
		//From Settings Section End.

		return $section_fields ;
	}

	/**
	 * Get settings for Notification section array.
	 */
	public function notification_section_array() {
		$section_fields = array() ;

		//Email Settings Section Start.
		$section_fields[] = array(
			'type'      => 'hrr_custom_fields' ,
			'hrr_field' => 'section_start' ,
				) ;
		$section_fields[] = array(
			'type'  => 'title' ,
			'title' => esc_html__( 'Email Settings' , 'refund' ) ,
			'id'    => 'hrr_notification_options' ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Email Type' , 'refund' ) ,
			'type'    => 'select' ,
			'options' => array(
				'woo'  => __( 'Woocommerce Template' , 'refund' ) ,
				'html' => __( 'HTML Template' , 'refund' ) ,
			) ,
			'default' => 'woo' ,
			'id'      => $this->get_option_key( 'email_type' ) ,
			'desc'    => esc_html__( 'If "HTML Template" is selected, plain text emails will be sent. If "WooCommerce Template" is selected, email will be sent with customization made in WooCommerce Email' , 'refund' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'From Name' , 'refund' ) ,
			'type'    => 'text' ,
			'default' => get_option( 'woocommerce_email_from_name' ) ,
			'id'      => $this->get_option_key( 'from_name' ) ,
			'desc'    => esc_html__( 'Sender name for refund emails' , 'refund' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'From Email' , 'refund' ) ,
			'type'    => 'text' ,
			'default' => get_option( 'woocommerce_email_from_address' ) ,
			'id'      => $this->get_option_key( 'from_email' ) ,
			'desc'    => esc_html__( 'Sender Email Id for refund emails' , 'refund' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Show Unsubscription Option' , 'refund' ) ,
			'type'    => 'checkbox' ,
			'default' => 'no' ,
			'class'   => 'hrr-premium-info-settings' ,
			'id'      => $this->get_option_key( 'enable_unsubscribe_option' ) ,
			'desc'    => esc_html__( 'If enabled, a checkbox will be displayed on "My Account Page" for the user to unsubscribe' , 'refund' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Customize Unsubscription Heading' , 'refund' ) ,
			'type'    => 'text' ,
			'class'   => 'hrr_refund_unsubscription hrr-premium-info-settings' ,
			'default' => esc_html__( 'Unsubscription Settings' , 'refund' ) ,
			'id'      => $this->get_option_key( 'unsub_heading' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Customize Unsubscription Text' , 'refund' ) ,
			'type'    => 'text' ,
			'class'   => 'hrr_refund_unsubscription hrr-premium-info-settings' ,
			'default' => esc_html__( 'Unsubscribe here to stop receiving Refund Emails' , 'refund' ) ,
			'id'      => $this->get_option_key( 'unsub_label' ) ,
				) ;
		$section_fields[] = array(
			'type' => 'sectionend' ,
			'id'   => 'hrr_notification_options' ,
				) ;
		$section_fields[] = array(
			'type'      => 'hrr_custom_fields' ,
			'hrr_field' => 'section_end' ,
				) ;
		//Email Settings Section End.
		//New Request Notification Section Start.
		$section_fields[] = array(
			'type'      => 'hrr_custom_fields' ,
			'hrr_field' => 'section_start' ,
				) ;
		$section_fields[] = array(
			'type'  => 'title' ,
			'title' => esc_html__( 'New Refund Request - Notification Email' , 'refund' ) ,
			'id'    => 'hrr_new_request_notification_options' ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Notify Customer' , 'refund' ) ,
			'type'    => 'checkbox' ,
			'default' => 'no' ,
			'id'      => $this->get_option_key( 'new_request_user_notification' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Email Subject' , 'refund' ) ,
			'type'    => 'text' ,
			'class'   => 'hrr_new_request_user_notification' ,
			'default' => 'Refund Request Submitted on {hrr-refund.sitename}' ,
			'id'      => $this->get_option_key( 'new_request_subject_for_user_notification' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Email Message' , 'refund' ) ,
			'type'    => 'textarea' ,
			'class'   => 'hrr_new_request_user_notification' ,
			'default' => 'Your Refund Request {hrr-refund.requestid} for Order {hrr-refund.orderid} has been submitted successfully on {hrr-refund.sitename} at {hrr-refund.date} {hrr-refund.time}' ,
			'id'      => $this->get_option_key( 'new_request_msg_for_user_notification' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Notify Admin' , 'refund' ) ,
			'type'    => 'checkbox' ,
			'default' => 'no' ,
			'id'      => $this->get_option_key( 'new_request_admin_notification' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Email Subject' , 'refund' ) ,
			'type'    => 'text' ,
			'class'   => 'hrr_new_request_admin_notification' ,
			'default' => 'New Refund Request on {hrr-refund.sitename}' ,
			'id'      => $this->get_option_key( 'new_request_subject_for_admin_notification' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Email Message' , 'refund' ) ,
			'type'    => 'textarea' ,
			'class'   => 'hrr_new_request_admin_notification' ,
			'default' => 'Customer {hrr-refund.customername} has submitted a Refund Request {hrr-refund.requestid} for Order {hrr-refund.orderid} on {hrr-refund.sitename} at {hrr-refund.date} {hrr-refund.time}' ,
			'id'      => $this->get_option_key( 'new_request_msg_for_admin_notification' ) ,
				) ;
		$section_fields[] = array(
			'type' => 'sectionend' ,
			'id'   => 'hrr_new_request_notification_options' ,
				) ;
		$section_fields[] = array(
			'type'      => 'hrr_custom_fields' ,
			'hrr_field' => 'section_end' ,
				) ;
		//New Request Notification Section End.
		//Refund Conversation Notification Section Start.
		$section_fields[] = array(
			'type'      => 'hrr_custom_fields' ,
			'hrr_field' => 'section_start' ,
				) ;
		$section_fields[] = array(
			'type'  => 'title' ,
			'title' => esc_html__( 'Refund Conversation - Notification Email' , 'refund' ) ,
			'id'    => 'hrr_refund_conversation_notification_options' ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Notify Customer' , 'refund' ) ,
			'type'    => 'checkbox' ,
			'class'   => 'hrr-premium-info-settings' ,
			'default' => 'no' ,
			'id'      => $this->get_option_key( 'refund_conversation_user_notification' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Email Subject' , 'refund' ) ,
			'type'    => 'text' ,
			'class'   => 'hrr_refund_conversation_user_notification hrr-premium-info-settings' ,
			'default' => 'Reply regarding with Refund Request on {hrr-refund.sitename}' ,
			'id'      => $this->get_option_key( 'refund_conversation_subject_for_user_notification' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Email Message' , 'refund' ) ,
			'type'    => 'textarea' ,
			'class'   => 'hrr_refund_conversation_user_notification hrr-premium-info-settings' ,
			'default' => 'You have got a reply from site Admin on {hrr-refund.sitename} at {hrr-refund.date} {hrr-refund.time} regarding with your Refund Request {hrr-refund.requestid} for Order {hrr-refund.orderid}' ,
			'id'      => $this->get_option_key( 'refund_conversation_msg_for_user_notification' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Notify Admin' , 'refund' ) ,
			'type'    => 'checkbox' ,
			'class'   => 'hrr-premium-info-settings' ,
			'default' => 'no' ,
			'id'      => $this->get_option_key( 'refund_conversation_admin_notification' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Email Subject' , 'refund' ) ,
			'type'    => 'text' ,
			'class'   => 'hrr_refund_conversation_admin_notification hrr-premium-info-settings' ,
			'default' => 'Reply regarding with Refund Request on {hrr-refund.sitename}' ,
			'id'      => $this->get_option_key( 'refund_conversation_subject_for_admin_notification' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Email Message' , 'refund' ) ,
			'type'    => 'textarea' ,
			'class'   => 'hrr_refund_conversation_admin_notification hrr-premium-info-settings' ,
			'default' => 'You have got a reply from Customer {hrr-refund.customername} on {hrr-refund.sitename} at {hrr-refund.date} {hrr-refund.time} regarding with Refund Request {hrr-refund.requestid} for Order {hrr-refund.orderid}' ,
			'id'      => $this->get_option_key( 'refund_conversation_msg_for_admin_notification' ) ,
				) ;
		$section_fields[] = array(
			'type' => 'sectionend' ,
			'id'   => 'hrr_refund_conversation_notification_options' ,
				) ;
		$section_fields[] = array(
			'type'      => 'hrr_custom_fields' ,
			'hrr_field' => 'section_end' ,
				) ;
		//Refund Conversation Notification Section End.
		//Request Accepted Notification Section Start.
		$section_fields[] = array(
			'type'      => 'hrr_custom_fields' ,
			'hrr_field' => 'section_start' ,
				) ;
		$section_fields[] = array(
			'type'  => 'title' ,
			'title' => esc_html__( 'Refund Request Accepted - Notification Email' , 'refund' ) ,
			'id'    => 'hrr_req_accepted_notification_options' ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Notify Customer' , 'refund' ) ,
			'type'    => 'checkbox' ,
			'default' => 'no' ,
			'id'      => $this->get_option_key( 'request_accepted_user_notification' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Email Subject' , 'refund' ) ,
			'type'    => 'text' ,
			'class'   => 'hrr_req_accepted_user_notification' ,
			'default' => 'Refund Request Accepted on {hrr-refund.sitename}' ,
			'id'      => $this->get_option_key( 'request_accepted_subject_for_user_notification' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Email Message' , 'refund' ) ,
			'type'    => 'textarea' ,
			'class'   => 'hrr_req_accepted_user_notification' ,
			'default' => 'Your Refund Request {hrr-refund.requestid} for Order {hrr-refund.orderid} has been Accepted by site Admin on {hrr-refund.sitename} at {hrr-refund.date} {hrr-refund.time}' ,
			'id'      => $this->get_option_key( 'request_accepted_msg_for_user_notification' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Notify Admin' , 'refund' ) ,
			'type'    => 'checkbox' ,
			'default' => 'no' ,
			'id'      => $this->get_option_key( 'request_accepted_admin_notification' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Email Subject' , 'refund' ) ,
			'type'    => 'text' ,
			'class'   => 'hrr_req_accepted_admin_notification' ,
			'default' => 'Refund Request Accepted on {hrr-refund.sitename}' ,
			'id'      => $this->get_option_key( 'request_accepted_subject_for_admin_notification' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Email Message' , 'refund' ) ,
			'type'    => 'textarea' ,
			'class'   => 'hrr_req_accepted_admin_notification' ,
			'default' => 'Refund Request {hrr-refund.requestid} for Order {hrr-refund.orderid} by Customer {hrr-refund.customername} has been Accepted on {hrr-refund.sitename} at {hrr-refund.date} {hrr-refund.time}' ,
			'id'      => $this->get_option_key( 'request_accepted_msg_for_admin_notification' ) ,
				) ;
		$section_fields[] = array(
			'type' => 'sectionend' ,
			'id'   => 'hrr_req_accepted_notification_options' ,
				) ;
		$section_fields[] = array(
			'type'      => 'hrr_custom_fields' ,
			'hrr_field' => 'section_end' ,
				) ;
		//Request Accepted Notification Section End.
		//Request Rejected Notification Section Start.
		$section_fields[] = array(
			'type'      => 'hrr_custom_fields' ,
			'hrr_field' => 'section_start' ,
				) ;
		$section_fields[] = array(
			'type'  => 'title' ,
			'title' => esc_html__( 'Refund Request Rejected - Notification Email' , 'refund' ) ,
			'id'    => 'hrr_req_rejected_notification_options' ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Notify Customer' , 'refund' ) ,
			'type'    => 'checkbox' ,
			'default' => 'no' ,
			'id'      => $this->get_option_key( 'request_rejected_user_notification' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Email Subject' , 'refund' ) ,
			'type'    => 'text' ,
			'class'   => 'hrr_req_rejected_user_notification' ,
			'default' => 'Refund Request Rejected on {hrr-refund.sitename}' ,
			'id'      => $this->get_option_key( 'request_rejected_subject_for_user_notification' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Email Message' , 'refund' ) ,
			'type'    => 'textarea' ,
			'class'   => 'hrr_req_rejected_user_notification' ,
			'default' => 'Your Refund Request {hrr-refund.requestid} for Order {hrr-refund.orderid} has been Rejected by site Admin on {hrr-refund.sitename} at {hrr-refund.date} {hrr-refund.time}' ,
			'id'      => $this->get_option_key( 'request_rejected_msg_for_user_notification' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Notify Admin' , 'refund' ) ,
			'type'    => 'checkbox' ,
			'default' => 'no' ,
			'id'      => $this->get_option_key( 'request_rejected_admin_notification' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Email Subject' , 'refund' ) ,
			'type'    => 'text' ,
			'class'   => 'hrr_req_rejected_admin_notification' ,
			'default' => 'Refund Request Rejected on {hrr-refund.sitename}' ,
			'id'      => $this->get_option_key( 'request_rejected_subject_for_admin_notification' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Email Message' , 'refund' ) ,
			'type'    => 'textarea' ,
			'class'   => 'hrr_req_rejected_admin_notification' ,
			'default' => 'Refund Request {hrr-refund.requestid} for Order {hrr-refund.orderid} by Customer {hrr-refund.customername} has been Rejected on {hrr-refund.sitename} at {hrr-refund.date} {hrr-refund.time}' ,
			'id'      => $this->get_option_key( 'request_rejected_msg_for_admin_notification' ) ,
				) ;
		$section_fields[] = array(
			'type' => 'sectionend' ,
			'id'   => 'hrr_req_rejected_notification_options' ,
				) ;
		$section_fields[] = array(
			'type'      => 'hrr_custom_fields' ,
			'hrr_field' => 'section_end' ,
				) ;
		//Request Rejected Notification Section End.
		//Request Status Updated Notification Section Start.
		$section_fields[] = array(
			'type'      => 'hrr_custom_fields' ,
			'hrr_field' => 'section_start' ,
				) ;
		$section_fields[] = array(
			'type'  => 'title' ,
			'title' => esc_html__( 'Refund Request Status Update - Notification Email' , 'refund' ) ,
			'id'    => 'hrr_req_status_update_notification_options' ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Notify Customer' , 'refund' ) ,
			'type'    => 'checkbox' ,
			'default' => 'no' ,
			'id'      => $this->get_option_key( 'request_status_update_user_notification' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Email Subject' , 'refund' ) ,
			'type'    => 'text' ,
			'class'   => 'hrr_req_status_update_user_notification' ,
			'default' => 'Refund Request is {hrr-refund.newstaus} on {hrr-refund.sitename}' ,
			'id'      => $this->get_option_key( 'request_status_update_subject_for_user_notification' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Email Message' , 'refund' ) ,
			'type'    => 'textarea' ,
			'class'   => 'hrr_req_status_update_user_notification' ,
			'default' => 'Your Refund Request {hrr-refund.requestid} for Order {hrr-refund.orderid} is now {hrr-refund.newstaus} on {hrr-refund.sitename} at {hrr-refund.date} {hrr-refund.time}' ,
			'id'      => $this->get_option_key( 'request_status_update_msg_for_user_notification' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Notify Admin' , 'refund' ) ,
			'type'    => 'checkbox' ,
			'default' => 'no' ,
			'id'      => $this->get_option_key( 'request_status_update_admin_notification' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Email Subject' , 'refund' ) ,
			'type'    => 'text' ,
			'class'   => 'hrr_req_status_update_admin_notification' ,
			'default' => 'Refund Request is {hrr-refund.newstaus} on {hrr-refund.sitename}' ,
			'id'      => $this->get_option_key( 'request_status_update_subject_for_admin_notification' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Email Message' , 'refund' ) ,
			'type'    => 'textarea' ,
			'class'   => 'hrr_req_status_update_admin_notification' ,
			'default' => 'Refund Request {hrr-refund.requestid} by Customer {hrr-refund.customername} for Order {hrr-refund.orderid} is now {hrr-refund.newstaus} on {hrr-refund.sitename} at {hrr-refund.date} {hrr-refund.time}' ,
			'id'      => $this->get_option_key( 'request_status_update_msg_for_admin_notification' ) ,
				) ;
		$section_fields[] = array(
			'type' => 'sectionend' ,
			'id'   => 'hrr_req_status_update_notification_options' ,
				) ;
		$section_fields[] = array(
			'type'      => 'hrr_custom_fields' ,
			'hrr_field' => 'section_end' ,
				) ;
		//Request Status Updated Notification Section End.

		return $section_fields ;
	}

	/**
	 * Get settings for localizations section array.
	 */
	public function localization_section_array() {
		$section_fields = array() ;

		//Button Label Section Start.
		$section_fields[] = array(
			'type'      => 'hrr_custom_fields' ,
			'hrr_field' => 'section_start' ,
				) ;
		$section_fields[] = array(
			'type'  => 'title' ,
			'title' => esc_html__( 'Refund Button Label' , 'refund' ) ,
			'id'    => 'hrr_button_label_options' ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Request Refund Button' , 'refund' ) ,
			'type'    => 'text' ,
			'default' => 'Request Refund' ,
			'id'      => $this->get_option_key( 'full_order_button_label' ) ,
				) ;
		$section_fields[] = array(
			'type' => 'sectionend' ,
			'id'   => 'hrr_button_label_options' ,
				) ;
		$section_fields[] = array(
			'type'      => 'hrr_custom_fields' ,
			'hrr_field' => 'section_end' ,
				) ;
		//Button Label Section End.
		//Request Table Section Start.
		$section_fields[] = array(
			'type'      => 'hrr_custom_fields' ,
			'hrr_field' => 'section_start' ,
				) ;
		$section_fields[] = array(
			'type'  => 'title' ,
			'title' => esc_html__( 'Refund Request Table Labels' , 'refund' ) ,
			'id'    => 'hrr_request_table_options' ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Refund Requests Label' , 'refund' ) ,
			'type'    => 'text' ,
			'default' => 'Refund Requests' ,
			'id'      => $this->get_option_key( 'request_title' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'ID' , 'refund' ) ,
			'type'    => 'text' ,
			'default' => 'ID' ,
			'id'      => $this->get_option_key( 'request_id' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Order Number' , 'refund' ) ,
			'type'    => 'text' ,
			'default' => 'Order Number' ,
			'id'      => $this->get_option_key( 'order_id' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Request Status' , 'refund' ) ,
			'type'    => 'text' ,
			'default' => 'Status' ,
			'id'      => $this->get_option_key( 'status' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Refund Type' , 'refund' ) ,
			'type'    => 'text' ,
			'default' => 'Type' ,
			'id'      => $this->get_option_key( 'type' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Refund Mode' , 'refund' ) ,
			'type'    => 'text' ,
			'default' => 'Mode' ,
			'id'      => $this->get_option_key( 'mode' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Refund Amount' , 'refund' ) ,
			'type'    => 'text' ,
			'default' => 'Amount' ,
			'id'      => $this->get_option_key( 'amount' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'View' , 'refund' ) ,
			'type'    => 'text' ,
			'default' => 'View' ,
			'id'      => $this->get_option_key( 'view' ) ,
				) ;
		$section_fields[] = array(
			'type' => 'sectionend' ,
			'id'   => 'hrr_request_table_options' ,
				) ;
		$section_fields[] = array(
			'type'      => 'hrr_custom_fields' ,
			'hrr_field' => 'section_end' ,
				) ;
		//Request Table Section End.
		//Form Label Section Start.
		$section_fields[] = array(
			'type'      => 'hrr_custom_fields' ,
			'hrr_field' => 'section_start' ,
				) ;
		$section_fields[] = array(
			'type'  => 'title' ,
			'title' => esc_html__( 'Refund Form Labels' , 'refund' ) ,
			'id'    => 'hrr_form_label_options' ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Reason for Refund' , 'refund' ) ,
			'type'    => 'text' ,
			'default' => 'Reason for Requesting Refund' ,
			'id'      => $this->get_option_key( 'request_reason' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Refund Mode' , 'refund' ) ,
			'type'    => 'text' ,
			'default' => 'Refund Mode' ,
			'id'      => $this->get_option_key( 'refund_mode' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Reason in Detail' , 'refund' ) ,
			'type'    => 'text' ,
			'default' => 'Reason in Detail' ,
			'id'      => $this->get_option_key( 'detail_request_reason' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Attachment' , 'refund' ) ,
			'type'    => 'text' ,
			'default' => 'Attachment' ,
			'id'      => $this->get_option_key( 'attachment' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Submit Button' , 'refund' ) ,
			'type'    => 'text' ,
			'default' => 'Request Refund' ,
			'id'      => $this->get_option_key( 'submit_button' ) ,
				) ;
		$section_fields[] = array(
			'type' => 'sectionend' ,
			'id'   => 'hrr_form_label_options' ,
				) ;
		$section_fields[] = array(
			'type'      => 'hrr_custom_fields' ,
			'hrr_field' => 'section_end' ,
				) ;
		//Form Label Section End.

		return $section_fields ;
	}

}

return new HRR_Refund_Tab() ;
