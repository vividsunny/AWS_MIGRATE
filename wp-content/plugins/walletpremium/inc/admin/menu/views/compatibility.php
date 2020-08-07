<?php
/* Shortcodes */

if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
?>
<div class='hrw_compatiblity_wrapper'>

    <?php echo do_action( 'hrw_before_compatibility_plugins' ) ?>

    <?php foreach ( $compatibility_plugins as $plugins ): ?>
        <div class="hrw_compatible">
            <div class="hrw_compatible_img">
                <a href="<?php echo esc_url( $plugins[ 'site_url' ] ) ; ?>" target="blank"><img src="<?php echo esc_url( $plugins[ 'img_url' ] ) ; ?>"/></a>
            </div>
            <div class="hrw_compatible_title">
                <p><?php echo esc_html( $plugins[ 'name' ] ) ; ?></p>
            </div>
            <div class="hrw_compatible_buynow">
                <a href="<?php echo esc_url( $plugins[ 'site_url' ] ) ; ?>"><?php esc_html_e( 'Buy Now' , HRW_LOCALE ) ; ?></a>
            </div>
        </div>    
    <?php endforeach ; ?>

    <?php echo do_action( 'hrw_after_compatibility_content' ) ?>  

</div><?php
