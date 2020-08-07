<?php
/**
 * Admin View: Quick Edit Product
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $WOO_MSTORE;

$nonce    = wp_create_nonce( 'woocommerce_multisite_quick_edit_nonce' );
$options  = $WOO_MSTORE->functions->get_options();
$blog_ids = $WOO_MSTORE->functions->get_active_woocommerce_blog_ids();

?>

<fieldset id="woonet-quick-edit-fields" class="woocommerce-multistore-fields inline-edit-col">

	<h4><?php _e( 'Multisite - Publish to', 'woonet' ); ?></h4>

	<div class="inline-edit-col">

		<p class="form-field no_label woonet_toggle_all_sites inline">
			<input class="woonet_toggle_all_sites inline" name="woonet_toggle_all_sites" id="woonet_toggle_all_sites" value="yes" type="checkbox" />
			<b><span class="description"><?php _e( 'Toggle all Sites', 'woonet' ); ?></span></b>
		</p>

		<div class="woonet_sites">
			<?php
				foreach ( $blog_ids as $blog_id ) {
//					if ( get_current_blog_id() == $blog_id ) {
//						echo '<input type="hidden" name="master_blog_id" value="' . $blog_id . '">';
//					}

					switch_to_blog( $blog_id );

					echo '<p class="form-field no_label _woonet_publish_to inline" data-group-id="' . $blog_id . '">';

					echo '<label class="alignleft">';
					printf(
						'<input type="checkbox" value="yes" name="_woonet_publish_to_%1$d" class="_woonet_publish_to" />',
						$blog_id
					);
					printf(
						'<span class="checkbox-title">%s <span class="warning">%s</span></span>',
						get_bloginfo('name'),
						__( '<b>Warning:</b> By unselecting this shop the product is unasigned, but not deleted from the shop, witch should be done manually.', 'woonet' )
					);
					echo '</label><br class="clear">';

					echo '<label class="alignleft pl">';
					printf(
						'<input type="checkbox" value="yes" name="_woonet_publish_to_%1$d_child_inheir">',
						$blog_id
					);
					printf(
						'<span class="checkbox-title">%s</span>',
						__( 'Child product inherit Parent changes', 'woonet' )
					);
					echo '</label><br class="clear">';

					echo '<label class="alignleft pl">';
					printf(
						'<input type="checkbox" value="yes" name="_woonet_%1$d_child_stock_synchronize" %2$s />',
						$blog_id,
						'yes' == $options['synchronize-stock'] ? 'disabled="disabled"' : ''
					);
					printf(
						'<span class="checkbox-title">%s</span>',
						__( 'If checked, any stock change will syncronize across product tree.', 'woonet' )
					);
					echo '</label><br class="clear">';

					echo '</p>';

					restore_current_blog();
				}
			?>
		</div>

	</div>

</fieldset>

<fieldset id="woonet-quick-edit-fields-slave" class="woocommerce-multistore-fields inline-edit-col">

	<p class="form-field _woonet_description inline">
		<span class="description"><?php _e( 'Child product, can\'t be re-published to other sites', 'woonet' ); ?></span>
	</p>
	<p class="form-field no_label _woonet_child_inherit_updates inline">
		<input type="checkbox" class="_woonet_child_inherit_updates inline" name="_woonet_child_inherit_updates" id="_woonet_child_inherit_updates" value="yes" />
		<span class="description"><?php _e( 'If checked, this product will inherit any parent updates', 'woonet' ); ?></span>
	</p>
	<p class="form-field no_label _woonet_child_stock_synchronize inline">
		<input type="checkbox" class="_woonet_child_stock_synchronize inline" name="_woonet_child_stock_synchronize" id="_woonet_child_stock_synchronize" value="yes" <?php disabled( $options['synchronize-stock'], 'yes' ); ?> />
		<span class="description"><?php _e( 'If checked, any stock change will syncronize across product tree.', 'woonet' ); ?></span>
	</p>

</fieldset>

<input type="hidden" name="_is_master_product" value="" />
<input type="hidden" name="master_blog_id" value="" />
<input type="hidden" name="product_blog_id" value="" />
<input type="hidden" name="woocommerce_multisite_quick_edit" value="1" />
<input type="hidden" name="woocommerce_multisite_quick_edit_nonce" value="<?php echo $nonce; ?>" />
