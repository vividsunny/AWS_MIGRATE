<?php
// echo 'innn';die();
define( 'WIDGET_PLUGIN_FILE', __FILE__ );
class PageCreate {

	/**
	 * A reference to an instance of this class.
	 */
	private static $instance;

	/**
	 * Returns an instance of this class.
	 */
	public static function get_instance() {

		if ( null == self::$instance ) {
			self::$instance = new PageCreate();
		}

		return self::$instance;

	}

	/**
	 * Initializes the plugin by setting filters and administration functions.
	 */
	public function __construct() {

		register_activation_hook( WIDGET_PLUGIN_FILE, array( $this, 'popupcomics_Widget_plugin_activation' ) );

	}

	/**
	 * Adds our template to the page dropdown for v4.7+
	 *
	 */
	public function popupcomics_Widget_plugin_activation() {
  
	  if ( ! current_user_can( 'activate_plugins' ) ) return;
	  
	  global $wpdb;
	  
	  if ( null === $wpdb->get_row( "SELECT post_name FROM {$wpdb->prefix}posts WHERE post_name = 'widget-script'", 'ARRAY_A' ) ) {
	     
	  	self::create_page();
	    
	  }
	}

	public function create_page(){
		$current_user = wp_get_current_user();

        // create post object
	    $page = array(
	      'post_title'  => __( 'Widget Script' ),
	      'post_status' => 'publish',
	      'post_author' => $current_user->ID,
	      'post_content'=> '[widget_weekly_product order="" orderby=""]',
	      'post_type'   => 'page',
	    );

	    // vivid( $page );die();
	    
	    // insert the post into the database
	    wp_insert_post( $page );

	}



}
