<?php
if (!defined('ABSPATH')) {
    exit;
}

//$inline_css = '.ywbc-barcode-display-value {margin-left: 10px;}';

if ( isset( $item_ids ) && is_array( $item_ids ) ){

    foreach ( $item_ids as $product_id ) {
        $product = wc_get_product($product_id);
        echo '<br>';
        echo '<div class="main-barcode-container">';
        echo '<div style="text-align: center">' . $product->get_name() . '<div>';
        YITH_YWBC()->show_barcode( $product_id, '1', '', '' );
        echo '</div>';
        echo '<br>';
    }
}





