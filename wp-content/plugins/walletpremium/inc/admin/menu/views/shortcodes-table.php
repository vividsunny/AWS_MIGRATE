<?php
/* Shortcodes */

if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
?>
<div class="hrw_shortcodes_wrapper">
    <table class="hrw_shortcodes_info_table">
        <thead>
            <tr>
                <th><?php esc_html_e( 'Shortcode' , HRW_LOCALE ) ; ?></th>
                <th><?php esc_html_e( 'Context where shortcode is valid' , HRW_LOCALE ) ; ?></th>
                <th><?php esc_html_e( 'Purpose' , HRW_LOCALE ) ; ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ( hrw_check_is_array( $shortcodes_info ) ) {
                foreach ( $shortcodes_info as $shortcode => $s_info ) {
                    ?>
                    <tr>
                        <td><?php echo $shortcode ; ?></td>
                        <td><?php echo $s_info[ 'where' ] ; ?></td>
                        <td><?php echo $s_info[ 'usage' ] ; ?></td>
                    </tr>
                    <?php
                }
            }
            ?>
        </tbody>
    </table>
    <?php echo do_action( 'hrw_after_shortcodes_content' ) ?>
</div><?php
