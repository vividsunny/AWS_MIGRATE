<?php
add_action( 'init', 'Subscription_init' );
/**
 * Register a Subscription post type.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_post_type
 */
function Subscription_init() {
	$labels = array(
		'name'               => _x( 'Subscriptions', 'post type general name', 'popupcomicshops' ),
		'singular_name'      => _x( 'Subscription', 'post type singular name', 'popupcomicshops' ),
		'menu_name'          => _x( 'Subscriptions', 'admin menu', 'popupcomicshops' ),
		'name_admin_bar'     => _x( 'Subscription', 'add new on admin bar', 'popupcomicshops' ),
		'add_new'            => _x( 'Add New', 'Subscription', 'popupcomicshops' ),
		'add_new_item'       => __( 'Add New Subscription', 'popupcomicshops' ),
		'new_item'           => __( 'New Subscription', 'popupcomicshops' ),
		'edit_item'          => __( 'Edit Subscription', 'popupcomicshops' ),
		'view_item'          => __( 'View Subscription', 'popupcomicshops' ),
		'all_items'          => __( 'All Subscriptions', 'popupcomicshops' ),
		'search_items'       => __( 'Search Subscriptions', 'popupcomicshops' ),
		'parent_item_colon'  => __( 'Parent Subscriptions:', 'popupcomicshops' ),
		'not_found'          => __( 'No Subscriptions found.', 'popupcomicshops' ),
		'not_found_in_trash' => __( 'No Subscriptions found in Trash.', 'popupcomicshops' )
	);

	$args = array(
		'labels'             => $labels,
        'description'        => __( 'Description.', 'popupcomicshops' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'comics_subscriptions' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'menu_icon'			 => 'dashicons-welcome-learn-more',
		'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt')
	);

	register_post_type( 'comics_subscription', $args );
}
