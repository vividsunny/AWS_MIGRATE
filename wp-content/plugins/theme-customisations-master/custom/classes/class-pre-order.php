<?php
/**
 * preorder product checking
 */
class PopupPreOrder {
	
	function __construct(){
		
		//checking the plugin is active or not
		if ( in_array( 'woocommerce-pre-orders/woocommerce-pre-orders.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			remove_filter( 'woocommerce_add_to_cart_validation', array( $GLOBALS['wc_pre_orders']->cart, 'validate_cart' ), 15, 2 );
		}
		//adding meta box in pre-order products
		add_action( 'add_meta_boxes', array( $this , 'popup_add_preorder_metabox' ) );
		//add_filter( 'product_type_options', array( $this, 'add_rms_checkbox' ), 15 );
	}

	public function popup_add_preorder_metabox(){
		 add_meta_box( 'global-notice', __( 'Custom Meta Values', 'wc-popup' ), array( $this, 'popup_add_meta_box_cb' ), 'product' , 'side' );
	}

	public function popup_add_meta_box_cb(){
		?>
		<p>
			<label> Add to RMS 
				<input type="checkbox" name="add_to_rms" class="checkbox"/>
			</label>
		</p>
		<?php
	}

	public function add_rms_checkbox(){
		$product_type_options[ 'popup_rms' ] =  array(
		        'id'            => '_popup_rms',
                'wrapper_class' => 'show_if_simple hide_if_bundle',
                'label'         => esc_html_x( 'Add to RMS', 'Set the product in RMS.','wc-popup' ),
                'description'   => esc_html__( 'Set the Pre-Order status for this product.', 'wc-popup' ),
                'default'       => 'no'
        );

		return $product_type_options;
	}
}

$popuppreorder = new PopupPreOrder();
?>