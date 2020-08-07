<?php
/**
 * Frontend Manager Products
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post_type, $post_type_object, $wp_post_types, $wp_query;

/**
 * Before query
 */
$post_type        = 'product';
$post_type_object = get_post_type_object( $post_type );
$current_screen   = YITH_Frontend_Manager()->is_wc_3_3_or_greather ? 'edit-product' : $post_type;
set_current_screen( $current_screen );

$GLOBALS['hook_suffix'] = 'product';
$act                    = ! empty( $_GET['act'] ) ? $_GET['act'] : '';

if ( 'delete' == $act && ! empty( $_GET['product_id'] ) ) {
	YITH_Frontend_Manager_Section_Products::delete( $_GET['product_id'] );
}

$wp_list_table = new YITH_Products_List_Table( array( 'screen' => $post_type, 'section_obj' => $section_obj ) );
$pagenum       = $wp_list_table->get_pagenum();
$doaction      = $wp_list_table->current_action();
$wp_list_table->prepare_items();

wp_enqueue_script( 'inline-edit-post' );
wp_enqueue_script( 'heartbeat' );

do_action( 'yith_wcfm_before_section_template', $section, $subsection, $act );

$title = $post_type_object->labels->name;
?>
    <div id="yith-wcfm-coupons">
        <h1>
			<?php echo __( 'Products', 'yith-frontend-manager-for-woocommerce' ); ?>
        </h1>
        <form id="product-search" classe="product-search" action="<?php echo $section_obj->get_url(); ?>" method="GET">
            <input class="text-field" type="text" name="search" value="<?php echo isset( $_GET['search'] ) ? $_GET['search'] : '' ?>" />
            <input class="search-submit" type="submit" value="<?php _ex( 'Search', 'Frontend Button Label', 'yith-frontend-manager-for-woocommerce' ); ?>" />
        </form>
		<?php
		$wp_list_table->display();
		?>
    </div>
<?php

do_action( 'yith_wcfm_after_section_template', $section, $subsection, $act );

/**
 * Frontend Manager Products.
 *
 * @since 1.0.0
 */
do_action( 'yith_wcfm_products' );
