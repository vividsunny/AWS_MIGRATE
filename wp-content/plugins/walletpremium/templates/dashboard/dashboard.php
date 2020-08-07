<?php
/**
 * This template displays Dashboard
 * 
 * This template can be overridden by copying it to yourtheme/wallet/dashboard/dashboard.php
 * 
 * To maintain compatibility, Wallet will update the template files and you have to copy the updated files to your theme
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
?>
<div class="hrw_frontend_dashboard_wrapper">
    <?php
    /*
     * Hook: hrw_frontend_dashboard_content
     */
    do_action( 'hrw_frontend_dashboard_content' ) ;
    ?>
</div>
<?php
