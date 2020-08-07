<?php
/**
 * Single Product Meta
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/meta.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;
?>
<div class="product_meta">

	<?php do_action( 'woocommerce_product_meta_start' ); ?>

    <div class="customfields_wrapper">
	
        <?php 
            $key_available = get_post_custom_values($key = 'available');
        ?>
        <?php if ( !empty ($key_available) ) { 
            $key_available = $key_available[0];
            $rest = substr($key_available, 0, -8);
            $key_available = date("F j, Y", strtotime($rest));?>
            <p><strong><?php esc_html_e( 'Available: ', 'woocommerce' ); ?></strong> <? echo $key_available;?></p>
        <?php } ?>
        <?php 
            $key_writer = get_post_custom_values($key = 'writer');
            $key_writer = $key_writer[0];
        ?>
        <?php if ( !empty ($key_writer) ) { ?>
            <p><strong><?php esc_html_e( 'Writer: ', 'woocommerce' ); ?></strong>  <? echo $key_writer;?></p>
        <?php } ?>
        <?php 
            $key_artist = get_post_custom_values($key = 'artist');
            $key_artist = $key_artist[0];
        ?>
        <?php if ( !empty ($key_artist) ) { ?>
            <p><strong><?php esc_html_e( 'Artist: ', 'woocommerce' ); ?></strong>  <? echo $key_artist;?></p>
        <?php } ?>
        <?php 
            $key_cartist = get_post_custom_values($key = 'covert_artist');
            $key_cartist = $key_cartist[0];
        ?>
        <?php if ( !empty ($key_cartist) ) { ?>
            <p><strong><?php esc_html_e( 'Covert Artist: ', 'woocommerce' ); ?></strong>  <? echo $key_cartist;?></p>
        <?php } ?>
    </div>
	<?php //echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'woocommerce' ) . ' ', '</span>' ); ?>

	<?php //echo wc_get_product_tag_list( $product->get_id(), ', ', '<span class="tagged_as">' . _n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'woocommerce' ) . ' ', '</span>' ); ?>

	<?php do_action( 'woocommerce_product_meta_end' ); ?>

</div>
