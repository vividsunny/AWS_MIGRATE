<?php
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

$user_id             = get_current_user_id();
$downloaded_products = YITH_WCMBS_Downloads_Report()->get_download_ids_for_user( $user_id );
$member              = YITH_WCMBS_Members()->get_member( $user_id );
if ( !$member->is_valid() || !$member->is_member() || !$downloaded_products || !is_array( $downloaded_products ) )
    return;

$downloads = array();
foreach ( $downloaded_products as $product_id ) {
    $links        = YITH_WCMBS_Products_Manager()->get_download_links( array( 'return' => 'links_names', 'id' => $product_id ) );
    $product_name = get_the_title( $product_id );
    $product_link = get_the_permalink( $product_id );
    $product_name = "<a href='{$product_link}'>{$product_name}</a>";
    if ( !empty( $links ) ) {
        foreach ( $links as $link ) {
            $unlocked = isset( $link[ 'unlocked' ] ) ? !!$link[ 'unlocked' ] : false;
            if ( $unlocked ) {
                $downloads[] = array(
                    'download_name' => $product_name . ' - ' . $link[ 'name' ],
                    'download_url'  => $link[ 'link' ]
                );
            }
        }
    }
}
?>
<table class="yith-wcmbs-membership-table">
    <thead>
    <tr>
        <th class="yith-wcmbs-membership-table-title"><?php _e( 'Product', 'yith-woocommerce-membership' ) ?></th>
        <th class="yith-wcmbs-membership-table-download"><?php _e( 'Download', 'yith-woocommerce-membership' ) ?></th>
    </tr>
    </thead>

    <tbody>
    <?php foreach ( $downloads as $download ) :
        ?>
        <tr>
            <?php
            echo '<td class="yith-wcmbs-membership-table-title">';

            echo $download[ 'download_name' ] ;

            echo '</td>';

            echo '<td class="yith-wcmbs-membership-table-download">';
            echo '<a href="' . esc_url( $download[ 'download_url' ] ) . '" class="yith-wcmbs-membership-content-button unlocked">' . __( 'Download', 'yith-woocommerce-membership' ) . '</a>';
            echo '</td>';
            ?>
        </tr>
        <?php
    endforeach;
    ?>
    </tbody>
</table>

