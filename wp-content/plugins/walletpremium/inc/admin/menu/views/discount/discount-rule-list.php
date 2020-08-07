<?php
/* Rule Configuration for Discount */

if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
?>
<div class="hrw_discount_section_start">
    <div class="hrw_discount_section_header">
        <h2><?php esc_html_e( 'Rule Configuration' , HRW_LOCALE ) ; ?></h2>
        <button class="hrw_add_discount_popup"><i class="fa fa-plus"></i><?php esc_attr_e( ' Add Rule' , HRW_LOCALE ) ; ?></button>
        <div class="hrw_discount_rule_popup_wrapper" style="display:none">
            <span class="hrw_close_popup_wrapper"><i class="fa fa-times-circle"></i></span>
            <label><?php esc_html_e( 'Rule Name' , HRW_LOCALE ) ; ?></label>
            <input type="text" id="hrw_rule_name" value=""/>
            <input type="button" class="hrw_add_discount_rule" value="<?php esc_attr_e( 'Add' , HRW_LOCALE ) ; ?>"/>
        </div>
    </div>
    <div class="hrw_discount_rule_wrapper">
        <?php
        if ( hrw_check_is_array( $discounts ) ) {
            foreach ( $discounts as $ids ) {
                $postid   = $ids[ 'ID' ] ;
                $discount = hrw_get_discount( $ids[ 'ID' ] ) ;
                if ( ! is_object( $discount ) )
                    continue ;

                include HRW_PLUGIN_PATH . '/inc/modules/views/discount-rules.php' ;
            }
        }
        ?>
    </div>
</div>
            <?php
