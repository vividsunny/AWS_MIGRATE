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

<?php do_action ( 'woocommerce_email_before_order_table', $order, true, false ); ?>

    <h2>
        <?php printf ( esc_html__( 'Order #%s', 'yith-woocommerce-additional-uploads' ), $order->get_order_number () ); ?>
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
        $_items = new YWAU_Uploaded_Items();
        $rules  = new YWAU_Rules();

        $items = $order->get_items ();

        /** @var YWAU_Uploaded_Item $single_item */
        /** @var  YWAU_Rule $product_rule */
        foreach ( $items as $item_id => $item ) :
            $_product      = is_object ( $item ) ? $item->get_product () : $order->get_product_from_item ( $item );
            
            $_items->load_items ( false, $item_id );
            $product_rules = $rules->get_product_rules_settings ( yit_get_prop($_product, 'id') );
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
                    wc_display_item_meta($item);
                    // Variation                    

                    foreach ( $product_rules as $product_rule ) {
                        echo '<br><span style="font-weight:bold">' . $product_rule->label . '</span>';

                        $single_item = $_items->get_item ( $item_id, $product_rule->rule_id );

                        if ( $single_item != null ) {
                            if ( $single_item->is_accepted () ) {
                                echo '<br><span style="color: #008000">' . sprintf ( apply_filters( 'yith_ywau_email_file_accepted_message' , esc_html__( "The %s file has been accepted", 'yith-woocommerce-additional-uploads' ) ), basename ( $single_item->upload_filepath ) ) . '</span>';
                            } elseif ( $single_item->is_rejected () ) {
                                echo '<br><span style="color: red">' . sprintf ( apply_filters( 'yith_ywau_email_file_rejected_message' , esc_html__( "The %s file has been rejected.", 'yith-woocommerce-additional-uploads' ) ), basename ( $single_item->upload_filepath ) );

                                if ( ! empty( $single_item->admin_response ) ) {
                                    echo sprintf ( esc_html__( "This is the reason why: %s", 'yith-woocommerce-additional-uploads' ), $single_item->admin_response );
                                }

                                echo '</span>';
                            } elseif ( $single_item->is_upload_successful () ) {
                                echo '<br><span>' . esc_html__( "The file you sent is waiting for verification.", 'yith-woocommerce-additional-uploads' ) . '</span>';
                            } elseif ( $single_item->is_upload_failed () ) {
                                echo '<br><span>' . sprintf ( esc_html__( 'This file you sent was not compliant with the required specifications. (Max size: %s MB, allowed extensions: %s', 'yith-woocommerce-additional-uploads' ), $product_rule->file_max_size, $product_rule->extension ) . '</span>';
                            } else {
                                echo '<br><span>' . esc_html__( "There are no associated files.", 'yith-woocommerce-additional-uploads' ) . '</span>';
                            }
                        } else {
                            echo '<br><span>' . esc_html__( "There are no associated files.", 'yith-woocommerce-additional-uploads' ) . '</span>';
                        }
                    }

                    ?>
                </td>
                <td style="text-align:left; vertical-align:middle; border: 1px solid #eee;"><?php echo $item[ 'qty' ]; ?></td>
                <td style="text-align:left; vertical-align:middle; border: 1px solid #eee;"><?php echo $order->get_formatted_line_subtotal ( $item ); ?></td>
            </tr>
            <?php
        endforeach; ?>
        </tbody>
    </table>

<?php do_action ( 'woocommerce_email_footer' ); ?>