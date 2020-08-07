<?php
add_action( 'init', 'custom_taxonomy_Import_date' );
// Register Custom Taxonomy
function custom_taxonomy_Import_date()  {

	$labels = array(
		'name'                       => 'Import dates',
		'singular_name'              => 'Import date',
		'menu_name'                  => 'Import date',
		'all_items'                  => 'All Import dates',
		'parent_item'                => 'Parent Import date',
		'parent_item_colon'          => 'Parent Import date:',
		'new_item_name'              => 'New Import date Name',
		'add_new_item'               => 'Add New Import date',
		'edit_item'                  => 'Edit Import date',
		'update_item'                => 'Update Import date',
		'separate_items_with_commas' => 'Separate Item with commas',
		'search_items'               => 'Search Import dates',
		'add_or_remove_items'        => 'Add or remove Import dates',
		'choose_from_most_used'      => 'Choose from the most used Import dates',
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	/*register_taxonomy_for_object_type( 'import_date', 'product', $args );*/

	register_taxonomy( 'import_date', 'product', $args );
	register_taxonomy_for_object_type( 'import_date', 'product' );

}

add_action( 'init', 'custom_taxonomy_Become_member' );
// Register Custom Taxonomy
function custom_taxonomy_Become_member()  {

	$labels = array(
		'name'                       => 'Become Members',
		'singular_name'              => 'Become Member',
		'menu_name'                  => 'Become Member',
		'all_items'                  => 'All Become Members',
		'parent_item'                => 'Parent Become Member',
		'parent_item_colon'          => 'Parent Become Member:',
		'new_item_name'              => 'New Become Member Name',
		'add_new_item'               => 'Add New Become Member',
		'edit_item'                  => 'Edit Become Member',
		'update_item'                => 'Update Become Member',
		'separate_items_with_commas' => 'Separate Item with commas',
		'search_items'               => 'Search Become Members',
		'add_or_remove_items'        => 'Add or remove Become Members',
		'choose_from_most_used'      => 'Choose from the most used Become Members',
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	/*register_taxonomy_for_object_type( 'import_date', 'product', $args );*/

	register_taxonomy( 'become_member', 'product', $args );
	register_taxonomy_for_object_type( 'become_member', 'product' );

}