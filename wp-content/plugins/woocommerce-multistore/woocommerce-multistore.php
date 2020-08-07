<?php
/*
Plugin Name: WooCommerce Multistore
Description: WooCommerce Multistore
Author: Lykke Media AS
Author URI: https://woomultistore.com/
Version: 2.0.14
WC tested up to: 3.5.7
Network: true
*/

if ( ! is_multisite() ) {
$message = <<<'EOD'
<h1>Oops, something is wrong.</h1>
<p>Did you configure your domain as multisite yet? Please make sure that the requirements for your Wordpress install are ok, as described in point 1 on this page: <a href="https://woomultistore.com/documentation/" target="_blank">“WooCommerce Multistore” Documentation</a></p>
<p>These links are useful, please check them out:<br/><a href="https://codex.wordpress.org/Create_A_Network" target="_blank">Create A Network</a><br/><a href="https://codex.wordpress.org/WordPress_Multisite_Domain_Mapping" target="_blank">WordPress Multisite Domain Mapping</a><br/><a href="https://woomultistore.com/wp-multisite-change-subsite-from-subdomain-to-domain/" target="_blank">WP Multisite – Change subsite from subdomain to domain</a></p>
<p>Also for copying stores (if you want to do that): <a href="https://wordpress.org/plugins/ns-cloner-site-copier/" target="_blank">NS Cloner – Site Copier</a><br/>(After copying a site please delete all products in the new store and republish them from the old to the new again so that they will sync).</p>
EOD;
wp_die( $message, '', array( 'back_link' => true ) );
}

    define('WOO_MSTORE_PATH',   plugin_dir_path(__FILE__));
    define('WOO_MSTORE_URL',    plugins_url('', __FILE__));
    define('WOO_MSTORE_APP_API_URL',      'http://woomultistore.com/index.php');
    
    define('WOO_MSTORE_VERSION', '2.0.14');
    define('WOO_MSTORE_DB_VERSION', '1.0');
    
    define('WOO_MSTORE_PRODUCT_ID',           'WCMSTORE');
    define('WOO_MSTORE_INSTANCE',             str_replace(array ("https://" , "http://"), "", network_site_url()));
    
    include(WOO_MSTORE_PATH . '/include/class.functions.php');
    
    if ( ! function_exists( 'woothemes_queue_update' ) || ! function_exists( 'is_woocommerce_active' ) ) 
        {
            require_once( 'woo-includes/woo-functions.php' );
        }

    include_once(WOO_MSTORE_PATH . '/include/licence.php');
    include_once(WOO_MSTORE_PATH . '/include/class.updater.php');

    //load language files
    add_action( 'plugins_loaded', 'WOO_MSTORE_load_textdomain'); 
    function WOO_MSTORE_load_textdomain() 
        {
            load_plugin_textdomain('woonet', FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang');
        }
    
    
    add_action('network_admin_notices', 'WOO_MSTORE_network_admin_notices');
    function WOO_MSTORE_network_admin_notices()
        {
            if ( current_user_can( 'manage_woocommerce' ) )
                WOO_MSTORE_setup_wizard_nottice();
                
            if ( current_user_can( 'manage_woocommerce' ) )
                WOO_MSTORE_update_wizard_nottice();
            
        }
    
    add_action( 'admin_notices', 'WOO_MSTORE_admin_notices' );
    function WOO_MSTORE_admin_notices()
        {
            if ( !is_multisite() ) 
                { 
                    echo '<div class="updated"><p>' . __('WooCommerce Multistore requires WordPress MultiSite environment', 'woonet') . '</p></div>';
                }
                
            if ( ! is_woocommerce_active() || version_compare( get_option( 'woocommerce_db_version' ), '2.1', '<' ) ) 
                {
                    $wc_url = 'http://www.woothemes.com/woocommerce/';
                    printf('<div class="updated"><p>' . __('WooCommerce Multistore requires', 'woonet') . ' <a href="%s">WooCommerce</a> '. __('to be installed', 'woonet') .'</p></div>', $wc_url);
                }
            
            if ( current_user_can( 'manage_woocommerce' ) ) 
                WOO_MSTORE_setup_wizard_nottice();    
        }
    
    /**
    * First time usage require a setip
    *     
    */
    function WOO_MSTORE_setup_wizard_nottice()
        {
            $setup_wizard_completed = get_site_option('mstore_setup_wizard_completed');
            if ( is_multisite() &&  is_woocommerce_active() &&  empty($setup_wizard_completed)) 
                {
                    include( WOO_MSTORE_PATH . '/include/admin/views/html-notice-setup.php' );
                }   
            
        }
    
    
    /**
    * Updates routines
    *     
    */
    function WOO_MSTORE_update_wizard_nottice()
        {
            global $WOO_MSTORE;
            
            $current_page   =   isset($_GET['page']) ?      $_GET['page']   :   '';
            
            if( $WOO_MSTORE->upgrade_require    === TRUE    &&  $current_page   !=  'woonet-upgrade')
                include( WOO_MSTORE_PATH . '/include/admin/views/html-notice-update.php' );

        }
    
    //check for other dependencies
    $options    =   WOO_MSTORE_functions::get_options();
    if($options['sequential-order-numbers'] ==  'yes')
        {
            include_once(WOO_MSTORE_PATH . '/include/class.sequential-order-numbers.php');
            
            new WOO_SON();
        }
        
    //init actions
    add_action('init',  'WOO_MSTORE_init');
	function WOO_MSTORE_init() {
		if( is_admin() ) {
			// Setup wizard
			if ( ! empty( $_GET['page'] ) ) {
				switch ( $_GET['page'] ) {
					case 'woonet-setup' :
						include_once( WOO_MSTORE_PATH . '/include/admin/class-wc-admin-setup-wizard.php' );
						new WC_Admin_Setup_Wizard();
						break;
				}
			}
		} else {
			$options = WOO_MSTORE_functions::get_options();
			if ( 'no' != $options['network-user-info'] ) {
				include_once( WOO_MSTORE_PATH . '/include/front/class-wc-front-my-account.php' );
				new WC_Front_My_Account();
			}
		}
	}
    
    if(defined('DOING_AJAX'))
        {
            include(WOO_MSTORE_PATH . '/include/class.ajax.php');    
            new WOO_MSTORE_ajax();
        }
             
//    if(is_admin())
//        {
            //admin
            include_once(WOO_MSTORE_PATH . '/include/class.admin.php');
            include_once(WOO_MSTORE_PATH . '/include/class.admin.network-orders.php');
            include_once(WOO_MSTORE_PATH . '/include/class.admin.network-products.php');
            include_once(WOO_MSTORE_PATH . '/include/class.admin.product.php');
            include_once(WOO_MSTORE_PATH . '/include/class-admin-product-category.php');
            include_once(WOO_MSTORE_PATH . '/include/class.options.php');
            
            global $WOO_MSTORE;
            $WOO_MSTORE = new WOO_MSTORE_admin();
            $WOO_MSTORE->init();
            
            $WOO_MSTORE_options_interface   =   new WOO_MSTORE_options_interface(); 
            
            
            //export functionality
            include_once(WOO_MSTORE_PATH . '/include/class.admin.export.php');
            new WOO_MSTORE_EXPORT();
//        }
        
    add_action('rest_api_init', 'do_something_only_if_api_request');

    function do_something_only_if_api_request($wp_rest_server)
        {
            include_once(WOO_MSTORE_PATH . '/include/class.admin.php');
            include_once(WOO_MSTORE_PATH . '/include/class.admin.network-orders.php');
            include_once(WOO_MSTORE_PATH . '/include/class.admin.network-products.php');
            include_once(WOO_MSTORE_PATH . '/include/class.admin.product.php');
            include_once(WOO_MSTORE_PATH . '/include/class-admin-product-category.php');
            include_once(WOO_MSTORE_PATH . '/include/class.options.php');
            
            global $WOO_MSTORE;
            
            if (! is_object( $WOO_MSTORE ) )
                {
                    $WOO_MSTORE = new WOO_MSTORE_admin();
                    $WOO_MSTORE->init();
                }

        }


