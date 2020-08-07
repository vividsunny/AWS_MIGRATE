<?php
/* Rule Configuration for Cashback */

if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
?>
<div class="hrw_cashback_section_start">
    <div class="hrw_cashback_section_header">
        <h2><?php esc_html_e( 'Rule Configuration' , HRW_LOCALE ) ; ?></h2>
        <button class="hrw_add_cashback_popup"><i class="fa fa-plus"></i><?php esc_attr_e( ' Add Rule' , HRW_LOCALE ) ; ?></button>
        <div class="hrw_cashback_rule_popup_wrapper" style="display:none">
            <span class="hrw_close_popup_wrapper"><i class="fa fa-times-circle"></i></span>
            <label><?php esc_html_e( 'Rule Name' , HRW_LOCALE ) ; ?></label>
            <input type="text" id="hrw_rule_name" value=""/>
            <input type="button" class="hrw_add_cashback_rule" value="<?php esc_attr_e( 'Add' , HRW_LOCALE ) ; ?>"/>
        </div>
    </div>
    <div class="hrw_cashback_rule_wrapper">
        <?php
        if ( hrw_check_is_array( $cashbackids ) ) {
            foreach ( $cashbackids as $ids ) {
                $postid   = $ids[ 'ID' ] ;
                $cashback = hrw_get_cashback( $ids[ 'ID' ] ) ;
                if ( ! is_object( $cashback ) )
                    continue ;

                include HRW_PLUGIN_PATH . '/inc/modules/views/cashback-rules.php' ;
            }
        }
        ?>
    </div>
</div>
            <?php
