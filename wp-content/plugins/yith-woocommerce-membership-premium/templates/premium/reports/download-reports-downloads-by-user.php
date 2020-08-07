<?php
/*
 * Template for Reports Page
 */

$per_page = !empty( $_REQUEST[ 'per_page' ] ) && intval( $_REQUEST[ 'per_page' ] ) > 0 ? intval( $_REQUEST[ 'per_page' ] ) : 20;
$order_by = !empty( $_REQUEST[ 'orderby' ] ) ? $_REQUEST[ 'orderby' ] : 'downloads';
$order    = !empty( $_REQUEST[ 'order' ] ) ? $_REQUEST[ 'order' ] : 'DESC';
?>
<div id="yith-wcmbs-download-reports-downloads-details-by-user-search">
    <div style="width:350px; display: inline-block;">
        <select class="yith_wcmbs_ajax_select2_select_customer"
                id="yith-wcmbs-download-reports-downloads-details-by-user-search-select"
                data-placeholder="<?php _e( 'Search user', 'yith-woocommerce-membership' ); ?>" data-allow-clear="true" style="width:100%">
        </select>
    </div>
    <input type="button" id="yith-wcmbs-download-reports-downloads-details-by-user-search-show-button" class="button primary-button"
           value="<?php _e( 'Show Details', 'yith-woocommerce-membership' ) ?>">
</div>
<div class="clear"></div>
<div id="yith-wcmbs-download-reports-downloads-by-user" class="yith-wcmbs-ajax-table"
     data-action="yith_wcmbs_get_download_reports_by_user"
     data-per_page="<?php echo $per_page; ?>"
     data-order="<?php echo $order; ?>"
     data-orderby="<?php echo $order_by; ?>"
>
    <div class="yith-wcmbs-reports-filters">
        <label><?php _e( 'Items per page', 'yith-woocommerce-membership' ) ?></label>
        <input type="number" class="yith-wcmbs-ajax-table-per-page" value="<?php echo $per_page ?>">
        <input type="button" class="yith-wcmbs-ajax-table-apply-button button" value="<?php _ex( 'Apply', 'Download reports: apply button', 'yith-woocommerce-membership' ) ?>">
    </div>

    <div class="yith-wcmbs-reports-download-reports-table">
        <?php $table = new YITH_WCMBS_Download_Reports_By_User_Table();
        $table->prepare_items();
        $table->display(); ?>
    </div>

</div>