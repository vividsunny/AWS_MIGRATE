<?php
/**
 * Add image product and title in notification email
 *
 * @author  Carlos Rodríguez <carlos.rodriguez@yourinspiration.it>
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

?>

<table>
    <tr>
        <td><?php echo apply_filters( 'yith_wcact_email_auction_thumbnail', '<img src="' . ( $product->get_image_id() ? current( wp_get_attachment_image_src( $product->get_image_id(), 'thumbnail') ) : wc_placeholder_img_src() ) .'" alt="' . esc_attr__( 'Product Image', 'yith-auctions-for-woocommerce' ) . '"width="150px" style="vertical-align:middle; margin-right: 10px;" />', $product );?></td>
        <td><a href="<?php echo $url ;?>"><?php echo $product_name ?></a></td>
    </tr>
</table>
