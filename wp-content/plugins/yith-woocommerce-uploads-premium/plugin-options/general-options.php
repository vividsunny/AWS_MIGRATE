<?php
/**
 * This file belongs to the YIT Plugin Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

$general_options = array(

	'general' => array(

		'section_general_settings'       => array(
			'name' => esc_html__( 'General settings', 'yith-woocommerce-additional-uploads' ),
			'type' => 'title',
			'id'   => 'ywau_section_general',
		),
		'ywau_thumbnail_width'           => array(
			'name'              => esc_html__( 'Thumbnail width', 'yith-woocommerce-additional-uploads' ),
            'type'    => 'yith-field',
            'yith-type' => 'number',
			'desc'              => esc_html__( 'Set the width of the thumbnails in pixel.', 'yith-woocommerce-additional-uploads' ),
			'id'                => 'ywau_thumbnail_width',
			'default'           => '100',
            'min'      => 10,
            'step'     => 1,
            'required' => 'required',
		),
		'ywau_thumbnail_height'          => array(
			'name'              => esc_html__( 'Thumbnail height', 'yith-woocommerce-additional-uploads' ),
            'type'    => 'yith-field',
            'yith-type' => 'number',
			'desc'              => esc_html__( 'Set the height of the thumbnails in pixel.', 'yith-woocommerce-additional-uploads' ),
			'id'                => 'ywau_thumbnail_height',
			'default'           => '100',
            'min'      => 10,
            'step'     => 1,
            'required' => 'required',

		),
		'ywau_thumbnail_quality'         => array(
			'name'              => esc_html__( 'Thumbnail quality', 'yith-woocommerce-additional-uploads' ),
            'type'    => 'yith-field',
            'yith-type' => 'number',
			'desc'              => esc_html__( 'Set the quality (in %) of the thumbnails.', 'yith-woocommerce-additional-uploads' ),
			'id'                => 'ywau_thumbnail_quality',
			'default'           => '100',
            'min'      => 1,
            'max'      => 100,
            'step'     => 1,
            'required' => 'required',

		),
		'ywau_allow_upload_on_cart'      => array(
			'name'    => esc_html__( 'Allow on cart', 'yith-woocommerce-additional-uploads' ),
			'desc'    => esc_html__( 'Use this option to allow users to attach a file even from the cart', 'yith-woocommerce-additional-uploads' ),
			'id'      => 'ywau_allow_upload_on_cart',
			'default' => 'no',
            'type'    => 'yith-field',
            'yith-type' => 'onoff',
            ),
		'ywau_allow_upload_on_checkout'  => array(
			'name'    => esc_html__( 'Allow on checkout page', 'yith-woocommerce-additional-uploads' ),
			'desc'    => esc_html__( 'Use this option to allow users to attach a file from the checkout page', 'yith-woocommerce-additional-uploads' ),
			'id'      => 'ywau_allow_upload_on_checkout',
			'default' => 'no',
            'type'    => 'yith-field',
            'yith-type' => 'onoff',
        ),
		'ywau_allow_upload_on_thankyou'  => array(
			'name'    => esc_html__( 'Allow on thank you page', 'yith-woocommerce-additional-uploads' ),
			'desc'    => esc_html__( 'Use this option to allow users to attach a file from the thankyou page', 'yith-woocommerce-additional-uploads' ),
			'id'      => 'ywau_allow_upload_on_thankyou',
			'default' => 'no',
            'type'    => 'yith-field',
            'yith-type' => 'onoff',
        ),
		'ywau_allow_upload_on_myaccount' => array(
			'name'    => esc_html__( 'Allow on myaccount', 'yith-woocommerce-additional-uploads' ),
			'desc'    => esc_html__( 'Use this option to allow users to attach a file to an order from myaccount page', 'yith-woocommerce-additional-uploads' ),
			'id'      => 'ywau_allow_upload_on_myaccount',
			'default' => 'no',
            'type'    => 'yith-field',
            'yith-type' => 'onoff',
        ),

	),
);

$statuses = wc_get_order_statuses();
/**
 * List the order status where the upload feature is enabled
 */
$i = 0;
foreach ( $statuses as $status => $status_name ) {

	$general_options['general']["ywau_allowed_order_status_{$status}"] = array(
		"desc"          => $status_name,
		"id"            => "ywau_allow_upload_{$status}",
        'type'    => 'checkbox',
		'default'       => 'yes',
		'checkboxgroup' => '',
	);

	if ( 0 == $i ) {
		$general_options['general']["ywau_allowed_order_status_{$status}"]["checkboxgroup"] = 'start';
		$general_options['general']["ywau_allowed_order_status_{$status}"]["name"]          = esc_html__( 'Allow the file upload when the order status is:', 'yith-woocommerce-additional-uploads' );
	} else if ( ( count( $statuses ) - 1 ) == $i ) {
		$general_options['general']["ywau_allowed_order_status_{$status}"]["checkboxgroup"] = 'end';
	}

	$i ++;
}

/**
 * List the order status in which the uploaded file can be deleted
 */
$i = 0;
foreach ( $statuses as $status => $status_name ) {

	$general_options['general']["ywau_allow_file_deletion_{$status}"] = array(
		"desc"          => $status_name,
		"id"            => "ywau_allow_file_deletion_{$status}",
        'type'    => 'checkbox',
		'default'       => 'yes',
		'checkboxgroup' => '',
	);

	if ( 0 == $i ) {
		$general_options['general']["ywau_allow_file_deletion_{$status}"]["checkboxgroup"] = 'start';
		$general_options['general']["ywau_allow_file_deletion_{$status}"]["name"]          = esc_html__( 'Allow the file deleting when the order status is:', 'yith-woocommerce-additional-uploads' );
	} else if ( ( count( $statuses ) - 1 ) == $i ) {
		$general_options['general']["ywau_allow_file_deletion_{$status}"]["checkboxgroup"] = 'end';
	}

	$i ++;
}


$general_options['general']["ywau_upload_folder"] = array(
	'name'    => esc_html__( 'Upload folder', 'yith-woocommerce-additional-uploads' ),
    'type'    => 'yith-field',
    'yith-type' => 'text',
	'desc'    => esc_html__( 'Set a folder in which saving the files that are uploaded by users. The folder will be create in wp-content/uploads/yith-additional-uploads.', 'yith-woocommerce-additional-uploads' ),
	'id'      => 'ywau_upload_folder',
	'default' => '',
);

$general_options['general']["ywau_folder_by_order_type"] = array(
	'name'    => esc_html__( 'Storing mode', 'yith-woocommerce-additional-uploads' ),
    'type' => 'yith-field',
    'yith-type' => 'radio',
	'desc'    => esc_html__( 'Choose whether to use the ID or the order number as name of the folder where you want to store the files linked to a specific order.', 'yith-woocommerce-additional-uploads' ),
	'id'      => 'ywau_folder_by_order_type',
	'options' => array(
		'order_id'     => esc_html__( 'Order ID', 'yith-woocommerce-additional-uploads' ),
		'order_number' => esc_html__( 'Order number', 'yith-woocommerce-additional-uploads' ),
	),
	'default' => 'order_id',
);

$general_options['general']["ywau_split_products_on_cart"] = array(
	'name'    => esc_html__( 'Split products on cart', 'yith-woocommerce-additional-uploads' ),
    'type'    => 'yith-field',
    'yith-type' => 'onoff',
	'desc'    => esc_html__( 'Choose whether to use standard WooCommerce behaviour that groups more items of the same products in one line, giving users the possibility to  upload the same files for all the items (e.g. 3 exact copies of the same "Fashion Calendar") or to add to cart one item for each line and allow users to upload different files for each item (e.g. 3 items of "Fashion Calendar" with different pictures each)','yith-woocommerce-additional-uploads').' <a href="http://yithemes.com/docs-plugins/yith-woocommerce-uploads/04-uploads-rules.html" title="' . esc_html__( "Learn more", 'yith-woocommerce-additional-uploads' ) . '">' . esc_html__( "Learn more", 'yith-woocommerce-additional-uploads' ) . '</a>',
	'id'      => 'ywau_split_products_on_cart',
	'default' => 'no',
);

$general_options['general']["ywau_multi_upload_settings"] = array(
	'name' => esc_html__( 'Main upload rules', 'yith-woocommerce-additional-uploads' ),
	'type' => 'ywau_multi_upload_settings',
	'id'   => 'ywau_multi_upload_settings',
    'value'=> ''
);

$general_options['general']['ywau_enable_product_upload'] = array(
	'name'    => esc_html__( 'Enable uploads for products', 'yith-woocommerce-additional-uploads' ),
	'desc'    => esc_html__( 'Enable the upload rules for products, the customer could attach files to the products, according to the upload rules', 'yith-woocommerce-additional-uploads' ),
	'id'      => 'ywau_enable_product_upload',
	'default' => 'yes',
    'type'    => 'yith-field',
    'yith-type' => 'onoff',
);

$general_options['general']['ywau_enable_product_variations_upload'] = array(
    'name'    => esc_html__( 'Enable uploads for products variations', 'yith-woocommerce-additional-uploads' ),
    'desc'    => esc_html__( 'Enable the upload rules for products variations by default, the customer could attach files to the products variations, according to the upload rules', 'yith-woocommerce-additional-uploads' ),
    'id'      => 'ywau_enable_product_variations_upload',
    'default' => 'no',
    'type'    => 'yith-field',
    'yith-type' => 'onoff',
);


$general_options['general']['ywau_enable_order_upload'] = array(
	'name'    => esc_html__( 'Enable uploads for orders', 'yith-woocommerce-additional-uploads' ),
	'desc'    => esc_html__( 'Enable the upload rules for orders, the customer could attach files to the while order, according to the upload rules', 'yith-woocommerce-additional-uploads' ),
	'id'      => 'ywau_enable_order_upload',
	'default' => 'no',
    'type'    => 'yith-field',
    'yith-type' => 'onoff',
);

$general_options['general']['ywau_order_upload_text']       = array(
	'name'    => esc_html__( 'Order upload text', 'yith-woocommerce-additional-uploads' ),
	'desc'    => esc_html__( 'Set the message to show on cart and checkout pages for uploads attached to the whole order.', 'yith-woocommerce-additional-uploads' ),
	'id'      => 'ywau_order_upload_text',
	'default' => esc_html__( "You can customize your order attaching files to the current order.", 'yith-woocommerce-additional-uploads' ),
    'type'    => 'yith-field',
    'yith-type' => 'textarea',
	'css'     => 'width:100%',

);

$general_options['general']['ywau_accept_product_file_automatically'] = array(
    'name'    => esc_html__( 'Accept upload product automatically', 'yith-woocommerce-additional-uploads' ),
    'desc'    => esc_html__( 'Enable this option to automatically accept the uploaded file of the product on the order created after purchasing', 'yith-woocommerce-additional-uploads' ),
    'id'      => 'ywau_accept_product_file_automatically',
    'default' => 'no',
    'type'    => 'yith-field',
    'yith-type' => 'onoff',
);

$general_options['general']['ywau_accept_order_file_automatically'] = array(
    'name'    => esc_html__( 'Accept upload order automatically', 'yith-woocommerce-additional-uploads' ),
    'desc'    => esc_html__( 'Enable this option to automatically accept the uploaded file of the order created after purchasing', 'yith-woocommerce-additional-uploads' ),
    'id'      => 'ywau_accept_order_file_automatically',
    'default' => 'no',
    'type'    => 'yith-field',
    'yith-type' => 'onoff',
);

$general_options['general']["section_general_settings_end"] = array(
	'type' => 'sectionend',
	'id'   => 'ywau_section_general_end',
);

return $general_options;