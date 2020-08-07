<?php 

/* Admin Menu */

	if ( ! defined( "ABSPATH" ) ) exit; // Exit if accessed directly

	// Reorder menu
		function popupcomicshops_custom_menu_order( $menu_order ) {
			if ( !$menu_order ) return true;
			if ( current_user_can( 'shop_manager' ) && !is_super_admin() ){				
				$menu_order = array(
						'edit.php?post_type=product',
						'woocommerce',
						'edit.php?post_type=shop_order',
						'edit.php?post_type=shop_subscription',
						'edit.php', 
						'edit.php?post_type=page',
						'index.php',					
					);			
			}
			return $menu_order;
		}

		add_filter( 'custom_menu_order', 'popupcomicshops_custom_menu_order' );
		add_filter( 'menu_order', 'popupcomicshops_custom_menu_order' ); 

		function popupcomicshops_rename_menu(){	
			if ( current_user_can( 'shop_manager' ) && !is_super_admin() ){
				global $menu;
				
				foreach( $menu as $key => $item ) {
					if ( $item[0] === 'WooCommerce' ) {
						$menu[$key][0] = 'Orders';
					} 
				}
			}
			return false;
		}
		
		add_action( "admin_menu", "popupcomicshops_rename_menu", 999 );		
		
	// Add menu
	
		function popupcomicshops_add_menu(){
			if ( current_user_can( 'shop_manager' ) && !is_super_admin() ){
			// Initial defaults
				$capability = 'shop_manager';
				$function = '';
				$icon_url = '';
				$position = 0;

			// New menus				
					$page_title = 'Style';
					$menu_title = 'Style';
					$menu_slug = 'admin.php?page=be-options';
					$function = '';
					$parent_page = 'admin.php?page=be-options';
					add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
				
					$page_title = 'Slider';
					$menu_title = 'Slider';
					$menu_slug = 'admin.php?page=revslider&view=slide&id=1';
					$function = '';
					$parent_page = 'admin.php?page=revslider&view=slide&id=1';
					add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
					
					$page_title = 'Subscription';
					$menu_title = 'Subscription';
					$menu_slug = 'edit.php?post_type=shop_subscription';
					$function = '';
					$parent_page = 'edit.php?post_type=shop_subscription';
					add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position ); 
					
					$page_title = 'Reports';
					$menu_title = 'Reports';
					/*$menu_slug = 'popupcomicshops_reports';
					$function = 'popupcomicshops_report_menu';
					$parent_page = 'popupcomicshops_reports'; */	
					$menu_slug = 'admin.php?page=wc-reports';
					$function = '';
					$parent_page = 'admin.php?page=wc-reports';				
					add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );

					// Submenu
						//$title = "Submenu";
						//$menu_slug = "edit.php?post_status=publish&post_type=course_unit";
						//add_submenu_page( $parent_page, $title, $title, $capability, $menu_slug, $function, $icon_url, $position );
			}
		}
		
		add_action( "admin_menu", "popupcomicshops_add_menu", 999 );
		
		function popupcomicshops_report_menu(){
			require_once( get_stylesheet_directory() . "/asset/template/admin/report.php" ); 
		}
		
	// Remove menu pages from admin dashboard
		add_action( 'admin_menu', 'popupcomicshops_remove_admin_menu', 999 ); 

		function popupcomicshops_remove_admin_menu() {	
			if ( current_user_can( 'shop_manager' ) && !is_super_admin() ){	
				remove_menu_page( 'index.php' );					//Dashboard
				remove_menu_page( 'edit-comments.php' );		
				remove_menu_page( 'edit.php?post_type=calendar' );
				remove_menu_page( 'edit.php?post_type=client' );
				remove_menu_page( 'upload.php' );			
				remove_menu_page( 'jetpack' );            			//Jetpack* 				
				remove_menu_page( 'themes.php' );                 	//Appearance
				remove_menu_page( 'plugins.php' );                	//Plugins
				remove_menu_page( 'users.php' );                 	//Users
				remove_menu_page( 'tools.php' );                  	//Tools
				remove_menu_page( 'options-general.php' );        	//Settings
				remove_menu_page( 'flamingo' );
				remove_menu_page( 'wpcf7' );
				remove_menu_page( 'edit.php?post_type=offer' );
				remove_menu_page( 'edit.php?post_type=portfolio' );
				remove_menu_page( 'betheme' );
				remove_menu_page( 'edit.php?post_type=slide' );			// Slide
				remove_menu_page( 'edit.php?post_type=testimonial' );
				remove_menu_page( 'edit.php?post_type=layout' );
				remove_menu_page( 'edit.php?post_type=template' );
				remove_menu_page( 'wpcf7' );
				remove_menu_page( 'vc-general' );
				remove_menu_page( 'vc-welcome' );
				remove_menu_page( 'revslider' );
				remove_menu_page( 'pmxi-admin-home' );
				remove_menu_page( 'edit.php?post_type=acf' );
				remove_menu_page( 'edit.php?post_type=acf-field-group' );
				remove_menu_page( 'edit.php?post_type=tribe_events' );
				remove_menu_page( 'wpclever' );
				remove_menu_page( 'yith_wcwl_panel' );				
			}
		} 
		
	// Deactivate plugins for store owners
		function popupcomicshops_deactivate_plugin(){
			if ( current_user_can( 'shop_manager' ) && !is_super_admin() ){
				if ( is_plugin_active( 'error-log-monitor/plugin.php' ) ) {
					// deactivate_plugins( array( 'error-log-monitor/plugin.php' ) );	// Need to just disable, not deactivate
				}
			}
		}
		
		add_action( 'admin_init', 'popupcomicshops_deactivate_plugin' );