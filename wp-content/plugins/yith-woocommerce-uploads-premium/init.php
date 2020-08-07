<?php
/**
 * Plugin Name: YITH WooCommerce Uploads Premium
 * Plugin URI: https://yithemes.com/themes/plugins/yith-woocommerce-uploads/
 * Description: <code><strong>YITH WooCommerce Uploads</strong></code> A concrete way to customize your orders, load a file with your images, and complete your order according to your needs. <a href="https://yithemes.com/" target="_blank">Get more plugins for your e-commerce shop on <strong>YITH</strong></a>.
 * Version: 1.2.16
 * Author: YITH
 * Author URI: https://yithemes.com/
 * Text Domain: yith-woocommerce-additional-uploads
 * Domain Path: /languages/
 * WC requires at least: 3.3.0
 * WC tested up to: 4.2
 **/

/*  Copyright 2013-2018  Your Inspiration Themes  (email : plugins@yithemes.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( ! defined ( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly


//region    ****    Check if prerequisites are satisfied before enabling and using current plugin

if ( ! function_exists ( 'is_plugin_active' ) ) {
    require_once ( ABSPATH . 'wp-admin/includes/plugin.php' );
}

function yith_ywau_premium_install_woocommerce_admin_notice () {
    ?>
    <div class="error">
        <p><?php _e ( 'YITH WooCommerce Uploads is enabled but not effective. It requires WooCommerce in order to work.', 'yit' ); ?></p>
    </div>
    <?php
}

/**
 * Check if a free version is currently active and try disabling before activating this one
 */
if ( ! function_exists ( 'yit_deactive_free_version' ) ) {
    require_once 'plugin-fw/yit-deactive-plugin.php';
}
yit_deactive_free_version ( 'YITH_YWAU_FREE_INIT', plugin_basename ( __FILE__ ) );


if ( ! function_exists ( 'yith_plugin_registration_hook' ) ) {
    require_once 'plugin-fw/yit-plugin-registration-hook.php';
}
register_activation_hook ( __FILE__, 'yith_plugin_registration_hook' );

//endregion

//region    ****    Define constants

defined ( 'YITH_YWAU_PREMIUM' ) || define ( 'YITH_YWAU_PREMIUM', '1' );
defined ( 'YITH_YWAU_SLUG' ) || define ( 'YITH_YWAU_SLUG', 'yith-woocommerce-additional-uploads' );
defined ( 'YITH_YWAU_SECRET_KEY' ) || define ( 'YITH_YWAU_SECRET_KEY', 'f3EvZTK5edLj7mfHV2m5' );
defined ( 'YITH_YWAU_INIT' ) || define ( 'YITH_YWAU_INIT', plugin_basename ( __FILE__ ) );
defined ( 'YITH_YWAU_VERSION' ) || define ( 'YITH_YWAU_VERSION', '1.2.16' );
defined ( 'YITH_YWAU_FILE' ) || define ( 'YITH_YWAU_FILE', __FILE__ );
defined ( 'YITH_YWAU_DIR' ) || define ( 'YITH_YWAU_DIR', plugin_dir_path ( __FILE__ ) );
defined ( 'YITH_YWAU_URL' ) || define ( 'YITH_YWAU_URL', plugins_url ( '/', __FILE__ ) );
defined ( 'YITH_YWAU_ASSETS_URL' ) || define ( 'YITH_YWAU_ASSETS_URL', YITH_YWAU_URL . '/assets' );
defined ( 'YITH_YWAU_ASSETS_JS_URL' ) || define ( 'YITH_YWAU_ASSETS_JS_URL', YITH_YWAU_ASSETS_URL . '/js/' );
defined ( 'YITH_YWAU_TEMPLATES_DIR' ) || define ( 'YITH_YWAU_TEMPLATES_DIR', YITH_YWAU_DIR . 'templates' );
defined ( 'YITH_YWAU_ASSETS_IMAGES_URL' ) || define ( 'YITH_YWAU_ASSETS_IMAGES_URL', YITH_YWAU_ASSETS_URL . '/images/' );
defined( 'YITH_YWAU_VIEWS_PATH' ) || define( 'YITH_YWAU_VIEWS_PATH', YITH_YWAU_DIR . 'views/' );

$wp_upload_dir = wp_upload_dir ();

defined ( 'YITH_YWAU_SAVE_DIR' ) || define ( 'YITH_YWAU_SAVE_DIR', $wp_upload_dir[ 'basedir' ] . '/yith-additional-uploads/' );
defined ( 'YITH_YWAU_SAVE_URL' ) || define ( 'YITH_YWAU_SAVE_URL', $wp_upload_dir[ 'baseurl' ] . '/yith-additional-uploads/' );
//endregion

/* Plugin Framework Version Check */
if ( ! function_exists ( 'yit_maybe_plugin_fw_loader' ) && file_exists ( YITH_YWAU_DIR . 'plugin-fw/init.php' ) ) {
    require_once ( YITH_YWAU_DIR . 'plugin-fw/init.php' );
}

yit_maybe_plugin_fw_loader ( YITH_YWAU_DIR );

function yith_ywau_premium_init () {

    /**
     * Load text domain and start plugin
     */
    load_plugin_textdomain ( 'yith-woocommerce-additional-uploads', false, dirname ( plugin_basename ( __FILE__ ) ) . '/languages/' );

    require_once ( YITH_YWAU_DIR . 'lib/class.yith-woocommerce-additional-uploads.php' );
    require_once ( YITH_YWAU_DIR . 'lib/class.yith-woocommerce-additional-uploads-premium.php' );
    require_once ( YITH_YWAU_DIR . 'lib/class.ywau-plugin-fw-loader.php' );
    require_once ( YITH_YWAU_DIR . 'lib/class.ywau-rule.php' );
    require_once ( YITH_YWAU_DIR . 'lib/class.ywau-rules.php' );
    require_once ( YITH_YWAU_DIR . 'lib/class.ywau-uploaded-item.php' );
    require_once ( YITH_YWAU_DIR . 'lib/class.ywau-uploaded-items.php' );

    require_once ( YITH_YWAU_DIR . 'functions.php' );

    global $YWAU_loader;
    $YWAU_loader = YWAU_Plugin_FW_Loader::get_instance ();

    global $YWAU;
    $YWAU = YITH_WooCommerce_Additional_Uploads_Premium::get_instance ();
}

add_action ( 'yith_ywau_premium_init', 'yith_ywau_premium_init' );

function yith_ywau_premium_install () {
    if ( ! function_exists ( 'WC' ) ) {
        add_action ( 'admin_notices', 'yith_ywau_premium_install_woocommerce_admin_notice' );
    } else {
        do_action ( 'yith_ywau_premium_init' );
    }
}

add_action ( 'plugins_loaded', 'yith_ywau_premium_install', 11 );