<?php
/**
 * Admin order file status update
 *
 */

if ( ! defined ( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

?>

<?php do_action ( 'woocommerce_email_header', $email_heading ); ?>

    <p><?php printf ( esc_html__( 'The customer %s deleted a file from the order.', 'yith-woocommerce-additional-uploads' ), $order->get_billing_first_name() . ' ' . $order->get_billing_last_name() ); ?></p>

<?php do_action ( 'woocommerce_email_before_order_table', $order, true, false ); ?>

    <h2>
        <a href="<?php echo admin_url ( 'post.php?post=' . yit_get_prop($order, 'id') . '&action=edit' ); ?>"><?php printf ( esc_html__( 'Order #%s', 'yith-woocommerce-additional-uploads' ), $order->get_order_number () ); ?></a>
        (<?php printf ( '<time datetime="%s">%s</time>', date_i18n ( 'c', strtotime ( yit_get_prop($order, 'order_date') ) ), date_i18n ( wc_date_format (), strtotime ( yit_get_prop($order, 'order_date') ) ) ); ?>
        )
    </h2>

    <table cellspacing="0" cellpadding="6" style="width: 100%; border: 1px solid #eee;" border="1" bordercolor="#eee">
        <thead>
        <tr>
            <th scope="col"
                style="text-align:left; border: 1px solid #eee;"><?php _e ( 'Product', 'yith-woocommerce-additional-uploads' ); ?></th>
            <th scope="col"
                style="text-align:left; border: 1px solid #eee;"><?php _e ( 'Quantity', 'yith-woocommerce-additional-uploads' ); ?></th>
            <th scope="col"
                style="text-align:left; border: 1px solid #eee;"><?php _e ( 'Price', 'yith-woocommerce-additional-uploads' ); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        $items = $order->get_items ();

        foreach ( $items as $item_id => $item ) :
            $_product  = is_object ( $item ) ? $item->get_product () : $order->get_product_from_item ( $item );


            ?>
            <tr class="<?php echo esc_attr ( apply_filters ( 'woocoomerce_order_item_class', 'order_item', $item, $order ) ); ?>">
                <td style="text-align:left; vertical-align:middle; border: 1px solid #eee; word-wrap:break-word;">
                    <?php

                    // Product name
                    echo $item[ 'name' ];

                    // SKU
                    if ( is_object ( $_product ) && $_product->get_sku () ) {
                        echo ' (#' . $_product->get_sku () . ')';
                    }

                    // Variation
                    wc_display_item_meta($item);

                    // allow other plugins to add additional product information here
                    do_action ( 'yith_additional_uploads_order_item_meta_end', $item_id, $item, $order );

                    ?>
                </td>
                <td style="text-align:left; vertical-align:middle; border: 1px solid #eee;"><?php echo $item[ 'qty' ]; ?></td>
                <td style="text-align:left; vertical-align:middle; border: 1px solid #eee;"><?php echo $order->get_formatted_line_subtotal ( $item ); ?></td>
            </tr>
            <?php
        endforeach; ?>
        </tbody>
    </table>

<?php do_action ( 'woocommerce_email_footer' );