<?php 
/**
 * widget Tabbed Settings Page
 */

// add_action( 'init', 'widget_admin_init' );
add_action( 'admin_menu', 'widget_settings_page_init' );



// function widget_admin_init() {
// 	$settings = get_option( "widget_theme_settings" );
// 	if ( empty( $settings ) ) {
// 		$settings = array(
// 			'widget_intro' => 'Some intro text for the home page',
// 			'widget_tag_class' => false,
// 			'widget_ga' => false
// 		);
// 		add_option( "widget_theme_settings", $settings, '', 'yes' );
// 	}	
// }

function widget_settings_page_init() {
	$theme_data = get_theme_data( TEMPLATEPATH . '/style.css' );
	
	$settings_page = add_menu_page( 'Widget Settings', 'Widget Settings', 'manage_options', 'theme-settings', 'widget_settings_page' );
	add_action( "load-{$settings_page}", 'widget_load_settings_page' );
}

function widget_load_settings_page() {
	if ( $_POST["widget-settings-submit"] == 'Y' ) {
		check_admin_referer( "widget-settings-page" );
		widget_save_theme_settings();
		$url_parameters = isset($_GET['tab'])? 'updated=true&tab='.$_GET['tab'] : 'updated=true';
		wp_redirect(admin_url('admin.php?page=theme-settings&'.$url_parameters));
		exit;
	}
}

function widget_save_theme_settings() {
	global $pagenow;
	$settings = get_option( "widget_theme_settings" );
	
	if ( $pagenow == 'admin.php' && $_GET['page'] == 'theme-settings' ){ 
		if ( isset ( $_GET['tab'] ) )
	        $tab = $_GET['tab']; 
	    else
	        $tab = 'homepage'; 

	    switch ( $tab ){ 
	        case 'general' :
				$settings['widget_text_color']	  = $_POST['widget_text_color'];
				$settings['widget_column_shadow_color']	  = $_POST['widget_column_shadow_color'];
				$settings['widget_text_size']	  = $_POST['widget_text_size'];
			break; 
	        
			case 'homepage' : 
				$settings['widget_grid_setting']	= $_POST['widget_grid_setting'];
				$settings['widget_per_page']	  	= $_POST['widget_per_page'];
			break;
	    }
	}

	$updated = update_option( "widget_theme_settings", $settings );
}

function widget_admin_tabs( $current = 'general' ) { 
    $tabs = array(  'general' => 'Content Setting', 'homepage' => 'Display Setting', 'script' => 'Generate Script' ); 
    $links = array();

    echo '<div id="icon-themes" class="icon32"><br></div>';
    echo '<h2 class="nav-tab-wrapper">';
    foreach( $tabs as $tab => $name ){
        $class = ( $tab == $current ) ? ' nav-tab-active' : '';
        echo "<a class='nav-tab$class' href='?page=theme-settings&tab=$tab'>$name</a>";
        
    }
    echo '</h2>';
}

function widget_settings_page() {
	global $pagenow;
	
	wp_enqueue_script( "widget_admin" );
	$settings = get_option( "widget_theme_settings" );
	$theme_data = get_theme_data( TEMPLATEPATH . '/style.css' );
	?>
	<style type="text/css">
		tr.show_code{
			display: none;
		}
	</style>
	<div class="wrap">
		<h2><?php echo $theme_data['Name']; ?> Theme Settings</h2>
		
		<?php
			if ( 'true' == esc_attr( $_GET['updated'] ) ) echo '<div class="updated" ><p>Theme Settings updated.</p></div>';
			
			if ( isset ( $_GET['tab'] ) ) widget_admin_tabs($_GET['tab']); else widget_admin_tabs('general');
		?>

		<div id="poststuff">
			<form method="post" action="<?php admin_url( 'admin.php?page=theme-settings' ); ?>">
				<?php
				wp_nonce_field( "widget-settings-page" ); 
				
				if ( $pagenow == 'admin.php' && $_GET['page'] == 'theme-settings' ){ 
				
					if ( isset ( $_GET['tab'] ) ) $tab = $_GET['tab']; 
					else $tab = 'general'; 
					
					echo '<table class="form-table">';
					switch ( $tab ){
						case 'general' :
							include 'tabs/general.php';
						break; 

						case 'homepage' : 
							include 'tabs/homepage.php';
						break;

						case 'script' : 
							include 'tabs/script.php';
						break;

					}
					echo '</table>';
				}
				?>
				<p class="submit" style="clear: both;">
					<input type="submit" name="Submit"  class="button-primary" value="Update Settings" />
					<input type="hidden" name="widget-settings-submit" value="Y" />
				</p>
			</form>
			
		</div>

	</div>
<?php
}