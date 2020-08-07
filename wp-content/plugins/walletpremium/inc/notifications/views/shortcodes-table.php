<?php
/* Shortcodes Table */

if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
?>
<table class="hrw_notification_shortcodes_table">
    <thead>
        <tr>
            <th>
                <?php esc_html_e( 'Shortcode' , HRW_LOCALE ) ; ?>
            </th>
            <th>
                <?php esc_html_e( 'Context where Shortcode is valid' , HRW_LOCALE ) ; ?>
            </th>
            <th>
                <?php esc_html_e( 'Purpose' , HRW_LOCALE ) ; ?>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ( $shortcodes as $shortcode => $s_info ) {
            ?>
            <tr>
                <td>
                    <?php echo $shortcode ; ?>
                </td>
                <td>
                    <?php echo $s_info[ 'where' ] ; ?>
                </td>
                <td>
                    <?php echo $s_info[ 'usage' ] ; ?>
                </td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>