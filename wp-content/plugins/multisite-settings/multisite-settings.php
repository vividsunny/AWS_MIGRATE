<?php
/**
 * Plugin Name: Multisite Settings
 * Plugin URI: 
 * Description: Add Custom Tabs with Options on “Edit Site” Multisite Settings page
 * Version: 1.0
 * Author: Team Vivid
 * Author URI: http://vividwebsolutions.in
 * Text Domain: 
 *
 * @package Wordpress_Upload_Handler
 */

//define( '_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
//define( '_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );

class wp_multisite_settings
{

  public function __construct()
  {
    # code...
    add_action( 'admin_enqueue_scripts', array( $this, 'fileupload_admin_style' ) );
    add_action( 'admin_enqueue_scripts', array( $this, 'fileupload_admin_scripts' ) );

    # Hooks
    add_filter( 'network_edit_site_nav_links',array( $this, 'va_new_siteinfo_tab' ) );

    /*
    * Add submenu page under Sites
    */
    add_action( 'network_admin_menu', array( $this, 'comic_suite_new_page' ) );

    /*
    * Some CSS tricks to hide the link to our custom submenu page
    */
    add_action( 'admin_head', array( $this, 'comic_suite_trick' ) );

    /*
    * Save settings
    */
    add_action('network_admin_edit_comic_suiteupdate',  array( $this, 'comic_suite_save_options' ) );

    /*
    * Save Notification
    */
    add_action( 'network_admin_notices', array( $this, 'comic_suite_notice' ) );
  }

  public function fileupload_admin_style(){

    wp_register_style( 'bootstrap.min', plugin_dir_url( __FILE__ ).'admin/css/bootstrap.min.css', array(), "", "" );

  }

  public function fileupload_admin_scripts(){

    wp_register_script( 'bootstrap.min', plugin_dir_url( __FILE__ ).'admin/js/bootstrap.min.js' );

  }

  public function va_new_siteinfo_tab( $tabs ) {
    $tabs['site-comic_suite'] = array(
      'label' => 'ComicSuite',
      'url' => 'sites.php?page=comic_suitepage',
      'cap' => 'manage_sites'
    );
    return $tabs;
  }
  
  public function comic_suite_new_page(){
    add_submenu_page(
      'sites.php',
      'Edit website', // will be displayed in <title>
      'Edit website', // doesn't matter
      'manage_network_options', // capabilities
      'comic_suitepage',
      array( $this, 'comic_suite_handle_admin_page' ) // the name of the function which displays the page
    );
  }
  

  public function comic_suite_trick(){

    echo '<style>
      #menu-site .wp-submenu li:last-child{
        display:none;
      }
    </style>';

  }

  /*
  * Display the page and settings fields
  */
  public function comic_suite_handle_admin_page(){

    // do not worry about that, we will check it too
    $id = $_REQUEST['id'];
   
    // you can use $details = get_site( $id ) to add website specific detailes to the title
    $title = 'ComicSuite\'s settings';
   
   
    echo '<div class="wrap"><h1 id="edit-site">' . $title . '</h1>
    <p class="edit-site-actions"><a href="' . esc_url( get_home_url( $id, '/' ) ) . '">Visit</a> | <a href="' . esc_url( get_admin_url( $id ) ) . '">Dashboard</a></p>';
   
      // navigation tabs
      network_edit_site_nav( array(
        'blog_id'  => $id,
        'selected' => 'site-comic_suite' // current tab
      ) );
      
      if( get_blog_option( $id, 'comic_suite_field') == 'Yes'){
        $checked = 'checked=checked';
      }
      // more CSS tricks :)
      echo '
      <style>
      #menu-site .wp-submenu li.wp-first-item{
        font-weight:600;
      }
      #menu-site .wp-submenu li.wp-first-item a{
        color:#fff;
      }
      </style>
      <form method="post" action="edit.php?action=comic_suiteupdate">';
        wp_nonce_field( 'comic_suite-check' . $id );
        echo '<input type="hidden" name="id" value="' . $id . '" />
        <table class="form-table">
          <tr>
            <th scope="row"><label for="comic_suite_field">Uses ComicSuite</label></th>
            <td><input name="comic_suite_field" class="regular-text" type="checkbox" id="comic_suite_field" value="Yes" '.$checked.' /></td>
            
          </tr>
        </table>';
        submit_button();
      echo '</form></div>';
   
  }

  Public function comic_suite_save_options() {

    $blog_id = $_POST['id'];

    check_admin_referer('comic_suite-check'.$blog_id); // security check

    update_blog_option( $blog_id, 'comic_suite_field', $_POST['comic_suite_field'] );

    wp_redirect( add_query_arg( array(
      'page' => 'comic_suitepage',
      'id' => $blog_id,
      'updated' => 'true'), network_admin_url('sites.php')
    ));
    // redirect to /wp-admin/sites.php?page=comic_suitepage&blog_id=ID&updated=true

    exit;

  }

  public function comic_suite_notice() {
 
    if( isset( $_GET['updated'] ) && isset( $_GET['page'] ) && $_GET['page'] == 'comic_suitepage' ) {
   
      echo '<div id="message" class="updated notice is-dismissible">
        <p>Congratulations!</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
      </div>';
   
    }
   
  }


} /* End Class */

$wp_upload = new wp_multisite_settings();

?>