<?php

class WOO_MSTORE_EXPORT {
	private $system_messages = array();

	private $network_fields;
	private $order_fields;
	private $order_item_fields;
	private $order_item_product_fields;
	private $order_item_shipping_fields;
	private $order_item_tax_fields;
	private $order_item_coupon_fields;
	private $order_item_fee_fields;

	public function __construct() {
		$this->network_fields = array(
			'site_id' => 'Site ID',
		);

		$this->order_fields = array(
			// Abstract order props.
			'order_items'          => '',
			'parent_id'            => '',
			'status'               => '',
			'currency'             => '',
			'version'              => '',
			'prices_include_tax'   => '',
			'date_created'         => '',
			'date_modified'        => '',
			'discount_total'       => '',
			'discount_tax'         => '',
			'shipping_total'       => '',
			'shipping_tax'         => '',
			'cart_tax'             => '',
			'total'                => '',
			'total_tax'            => '',
			// Order props.
			'customer_id'          => '',
			'order_key'            => '',
			'billing_first_name'   => __( 'Billing First Name', 'woocommerce' ),
			'billing_last_name'    => __( 'Billing Last Name', 'woocommerce' ),
			'billing_company'      => __( 'Billing Company', 'woocommerce' ),
			'billing_address_1'    => __( 'Billing Address 1', 'woocommerce' ),
			'billing_address_2'    => __( 'Billing Address 2', 'woocommerce' ),
			'billing_city'         => __( 'Billing City', 'woocommerce' ),
			'billing_postcode'     => __( 'Billing Postal/Zip Code', 'woocommerce' ),
			'billing_state'        => __( 'Billing State', 'woocommerce' ),
			'billing_country'      => __( 'Billing Country', 'woocommerce' ),
			'billing_phone'        => __( 'Phone Number', 'woocommerce' ),
			'billing_email'        => __( 'Email Address', 'woocommerce' ),
			'shipping_first_name'  => __( 'Shipping First Name', 'woocommerce' ),
			'shipping_last_name'   => __( 'Shipping Last Name', 'woocommerce' ),
			'shipping_company'     => __( 'Shipping Company', 'woocommerce' ),
			'shipping_address_1'   => __( 'Shipping Address 1', 'woocommerce' ),
			'shipping_address_2'   => __( 'Shipping Address 2', 'woocommerce' ),
			'shipping_city'        => __( 'Shipping City', 'woocommerce' ),
			'shipping_postcode'    => __( 'Shipping Postal/Zip Code', 'woocommerce' ),
			'shipping_state'       => __( 'Shipping State', 'woocommerce' ),
			'shipping_country'     => __( 'Shipping Country', 'woocommerce' ),
			'payment_method'       => '',
			'payment_method_title' => '',
			'transaction_id'       => '',
			'customer_ip_address'  => '',
			'customer_user_agent'  => '',
			'created_via'          => '',
			'customer_note'        => '',
			'date_completed'       => '',
			'date_paid'            => '',
			'cart_hash'            => '',
			'meta'                 => '',
		);

		$this->order_item_fields = array(
			'product_id'             => '',
			'variation_id'           => '',
			'quantity'               => '',
			'tax_class'              => '',
			'subtotal'               => '',
			'subtotal_tax'           => '',
			'total'                  => '',
			'total_tax'              => '',
			'taxes'                  => '',
			'meta'                   => '',
		);

		$this->order_item_product_fields = array(
			'name'               => '',
			'slug'               => '',
			'date_created'       => '',
			'date_modified'      => '',
			'status'             => '',
			'featured'           => '',
			'catalog_visibility' => '',
			'description'        => '',
			'short_description'  => '',
			'sku'                => '',
			'price'              => '',
			'regular_price'      => '',
			'sale_price'         => '',
			'date_on_sale_from'  => '',
			'date_on_sale_to'    => '',
			'total_sales'        => '',
			'tax_status'         => '',
			'tax_class'          => '',
			'manage_stock'       => '',
			'stock_quantity'     => '',
			'stock_status'       => '',
			'backorders'         => '',
			'low_stock_amount'   => '',
			'sold_individually'  => '',
			'weight'             => '',
			'length'             => '',
			'width'              => '',
			'height'             => '',
			'upsell_ids'         => '',
			'cross_sell_ids'     => '',
			'parent_id'          => '',
			'reviews_allowed'    => '',
			'purchase_note'      => '',
			'attributes'         => '',
			'default_attributes' => '',
			'menu_order'         => '',
			'virtual'            => '',
			'downloadable'       => '',
			'category_ids'       => '',
			'tag_ids'            => '',
			'shipping_class_id'  => '',
			'downloads'          => '',
			'image_id'           => '',
			'gallery_image_ids'  => '',
			'download_limit'     => '',
			'download_expiry'    => '',
			'rating_counts'      => '',
			'average_rating'     => '',
			'review_count'       => '',
		);

		$this->order_item_shipping_fields = array(
			'method_title'  => '',
			'method_id'     => '',
			'instance_id'   => '',
			'total'         => '',
			'total_tax'     => '',
			'taxes'         => '',
		);

		$this->order_item_tax_fields = array(
			'rate_code'          => '',
			'rate_id'            => '',
			'label'              => '',
			'compound'           => '',
			'tax_total'          => '',
			'shipping_tax_total' => '',
		);

		$this->order_item_coupon_fields = array(
			'code'            => '',
			'discount'        => '',
			'discount_tax'    => '',
		);

		$this->order_item_fee_fields = array(
			'tax_class'          => '',
			'tax_status'         => '',
			'amount'             => '',
			'total'              => '',
			'total_tax'          => '',
			'taxes'              => '',
		);

		add_action( 'network_admin_menu', array( $this, 'network_admin_menu' ) );
		add_action( 'init', array( $this, 'init' ) );
	}

	public function network_admin_menu() {
		//only if superadmin
		if ( ! current_user_can( 'manage_sites' ) ) {
			return;
		}

		$menus_hook = add_submenu_page(
			'woonet-woocommerce',
			__( 'Orders Export', 'woonet' ),
			__( 'Orders Export', 'woonet' ),
			'manage_product_terms',
			'woonet-woocommerce-orders-export',
			array( $this, 'interface_orders_export_page' )
		);
		add_action( 'load-' . $menus_hook, array( $this, 'admin_notices' ) );
		add_action( 'admin_print_styles-' . $menus_hook, array( $this, 'admin_print_styles' ) );
		add_action( 'admin_print_scripts-' . $menus_hook, array( $this, 'admin_print_scripts' ) );
	}

	public function admin_print_styles() {
		wp_enqueue_style( 'jquery-ui-ms', WOO_MSTORE_URL . '/assets/css/jquery-ui.css' );
		wp_enqueue_style( 'woonet-woocommerce-orders-export', WOO_MSTORE_URL . '/assets/css/woosl-export.css' );
//		wp_enqueue_style( 'jquery-ui', '//code.jquery.com/ui/1.9.1/themes/eggplant/jquery-ui.css' );
	}

	public function admin_print_scripts() {
		wp_enqueue_script(
			'jquery-ms',
			WOO_MSTORE_URL . '/assets/js/jquery-3.3.1.min.js',
			array()
		);
		wp_enqueue_script(
			'jquery-ui-ms',
			WOO_MSTORE_URL . '/assets/js/jquery-ui.min.js',
			array( 'jquery-ms', )
		);
		wp_add_inline_script( 'jquery-ui-ms', 'var $ms = $.noConflict(true);' );

		wp_enqueue_script(
			'woonet-woocommerce-orders-export',
			WOO_MSTORE_URL . '/assets/js/woosl-export.js',
			array( 'jquery-ms', 'jquery-ui-ms' )
		);
	}

	public function init() {
		//check for any forms save
		if ( isset( $_POST['evcoe_form_submit'] ) && 'export' == $_POST['evcoe_form_submit'] ) {
			$this->form_submit_settings();
		}
	}

	private function form_submit_settings() {
		//include the export class
		include( WOO_MSTORE_PATH . '/include/class.admin.export.engine.php' );
		$export = new WOO_MSTORE_EXPORT_ENGINE();
		$export->process();

		foreach ( $export->errors_log as $error_log ) {
			$this->system_messages[] = array(
				'type'    => 'error',
				'message' => $error_log,
			);
		}
	}

	public function admin_notices() {
		if ( count( $this->system_messages ) < 1 ) {
			return;
		}

		foreach ( $this->system_messages as $system_message ) {
			echo "<div class='notice " . $system_message['type'] . "'><p>" . $system_message['message'] . "</p></div>";
		}
	}

	public function interface_orders_export_page() {
		include( WOO_MSTORE_PATH . '/include/admin/views/html-order-export.php' );
	}
}
