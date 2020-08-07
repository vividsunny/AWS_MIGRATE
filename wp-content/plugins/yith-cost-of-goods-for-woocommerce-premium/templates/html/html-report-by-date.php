<?php
/**
 * Admin View: Report by Date (with date filters)
 */

require_once ( YITH_COG_PATH . '/includes/admin/reports/class.yith-cog-report-table.php' );



if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

//Template of the 'Sales by ... ' links
wc_get_template( 'html/html-admin-report-links.php', array(), '' , YITH_COG_TEMPLATE_PATH );



?>
<div id="poststuff" class="woocommerce-reports-wide">
    <div class="postbox">

        <?php if ( 'custom' === $current_range && isset( $_GET['start_date'], $_GET['end_date'] ) ) : ?>
            <h3 class="screen-reader-text"><?php
                /* translators: 1: start date 2: end date */
                printf(
                    esc_html__( 'From %1$s to %2$s', 'yith-cost-of-goods-for-woocommerce' ),
                    esc_html( wc_clean( $_GET['start_date'] ) ),
                    esc_html( wc_clean( $_GET['end_date'] ) )
                );
                ?></h3>
        <?php else : ?>
            <h3 class="screen-reader-text"><?php echo esc_html( $ranges[ $current_range ] ); ?></h3>
        <?php endif; ?>

        <div class="stats_range">
            <?php $this->get_export_button(); ?>
            <ul>
                <?php
                foreach ( $ranges as $range => $name ) {
                    echo '<li class="' . ( $current_range == $range ? 'active' : '' ) . '"><a href="' . esc_url( remove_query_arg( array( 'start_date', 'end_date' ), add_query_arg( 'range', $range ) ) ) . '">' . $name . '</a></li>';
                }
                ?>
                <li class="custom <?php echo ( 'custom' === $current_range ) ? 'active' : ''; ?>">
                    <?php _e( 'Custom:', 'yith-cost-of-goods-for-woocommerce' ); ?>
                    <form method="GET">
                        <div>
                            <?php
                            // Maintain query string
                            foreach ( $_GET as $key => $value ) {
                                if ( is_array( $value ) ) {
                                    foreach ( $value as $v ) {
                                        echo '<input type="hidden" name="' . esc_attr( sanitize_text_field( $key ) ) . '[]" value="' . esc_attr( sanitize_text_field( $v ) ) . '" />';
                                    }
                                } else {
                                    echo '<input type="hidden" name="' . esc_attr( sanitize_text_field( $key ) ) . '" value="' . esc_attr( sanitize_text_field( $value ) ) . '" />';
                                }
                            }
                            ?>
                            <input type="hidden" name="range" value="custom" />
                            <input type="text" size="11" placeholder="yyyy-mm-dd" value="<?php echo ( ! empty( $_GET['start_date'] ) ) ? esc_attr( $_GET['start_date'] ) : ''; ?>" name="start_date" id="start_date" class="range_datepicker from" />
                            <span>&ndash;</span>
                            <input type="text" size="11" placeholder="yyyy-mm-dd" value="<?php echo ( ! empty( $_GET['end_date'] ) ) ? esc_attr( $_GET['end_date'] ) : ''; ?>" name="end_date" id="end_date" class="range_datepicker to" />
                            <input type="submit" class="button" value="<?php esc_attr_e( 'Go', 'yith-cost-of-goods-for-woocommerce' ); ?>" />
                            <?php wp_nonce_field( 'custom_range', 'wc_reports_nonce', false ); ?>
                        </div>
                    </form>
                </li>
            </ul>
        </div>
        <div class="main"></div>
        <div class="inside"></div>
    </div>
</div>

