<?php
/**
 * This template displays Menu Content
 * 
 * This template can be overridden by copying it to yourtheme/wallet/dashboard/menu-content.php
 * 
 * To maintain compatibility, Wallet will update the template files and you have to copy the updated files to your theme
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

global $hrw_current_menu , $hrw_current_submenu ;
?>
<div class="hrw_menu_content">
    <?php
    /*
     * Hook hrw_frontend_dashboard_menu_content;
     */
    do_action( 'hrw_frontend_dashboard_menu_content' , $hrw_current_menu , $hrw_current_submenu ) ;
    do_action( 'hrw_frontend_dashboard_menu_content_' . $hrw_current_submenu , $hrw_current_menu , $hrw_current_submenu ) ;
    ?>
</div>
<?php
