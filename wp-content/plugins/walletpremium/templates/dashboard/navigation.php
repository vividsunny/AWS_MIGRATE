<?php
/**
 * This template displays Navigation
 * 
 * This template can be overridden by copying it to yourtheme/wallet/dashboard/navigation.php
 * 
 * To maintain compatibility, Wallet will update the template files and you have to copy the updated files to your theme
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
global $hrw_current_menu , $hrw_current_submenu ;

do_action( 'hrw_before_dashboard_navigation' ) ;
?>
<div class="hrw_menus_wrapper">
    <div class="hrw_mobile_menu">
        <h4><?php esc_html_e( 'Wallet Dashboard' , HRW_LOCALE ) ; ?> <i class="fa fa-bars hrw_frontend_dashboard_mobile_menu"></i></h4>
    </div>
    <ul class="hrw_menu_ul">
        <?php foreach ( hrw_get_dashboard_menus() as $menu_key => $menu ): ?>
            <li>
                <a class="<?php echo hrw_get_dashboard_menu_classes( $menu_key ) ; ?>" href="<?php echo esc_url( HRW_Dashboard:: prepare_menu_url( $menu_key ) ) ; ?>">
                    <i class="<?php echo esc_attr( $menu[ 'code' ] ) ; ?>"></i><?php echo esc_attr( $menu[ 'label' ] ) ; ?>
                </a>

                <?php $sub_menus = apply_filters( 'hrw_frontend_dashboard_' . $menu_key . '_submenus' , array() ) ; ?>

                <?php if ( hrw_check_is_array( $sub_menus ) ): ?>
                    <ul class='hrw_frontend_dashboard_submenu'>
                        <?php foreach ( $sub_menus as $sub_menu_key => $sub_menu_label ): ?>
                            <li>
                                <a class="<?php echo hrw_get_dashboard_submenu_classes( $sub_menu_key ) ; ?>" href="<?php echo esc_url( HRW_Dashboard:: prepare_menu_url( $sub_menu_key ) ) ; ?>"><?php echo esc_html( $sub_menu_label ) ; ?></a>
                            </li>
                        <?php endforeach ; ?>
                    </ul>
                <?php endif ; ?>

            </li>

        <?php endforeach ; ?>
    </ul>
</div>
<?php
do_action( 'hrw_after_dashboard_navigation' ) ;
