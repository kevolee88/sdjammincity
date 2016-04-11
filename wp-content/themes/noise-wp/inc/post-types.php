<?php

add_action( 'init', 'noise_register_post_types' );

/**
 *  Register custom post types
 *
 * @return void
 */
function noise_register_post_types()
{
	// Track
	$labels = array(
		'name'                => _x( 'Tracks', 'Post Type General Name', 'noise' ),
		'singular_name'       => _x( 'Track', 'Post Type Singular Name', 'noise' ),
		'menu_name'           => __( 'Track', 'noise' ),
		'parent_item_colon'   => __( 'Parent Track:', 'noise' ),
		'all_items'           => __( 'All Tracks', 'noise' ),
		'view_item'           => __( 'View Track', 'noise' ),
		'add_new_item'        => __( 'Add New Track', 'noise' ),
		'add_new'             => __( 'New Track', 'noise' ),
		'edit_item'           => __( 'Edit Track', 'noise' ),
		'update_item'         => __( 'Update Track', 'noise' ),
		'search_items'        => __( 'Search tracks', 'noise' ),
		'not_found'           => __( 'No tracks found', 'noise' ),
		'not_found_in_trash'  => __( 'No tracks found in Trash', 'noise' ),
	);
	$args = array(
		'labels'    => $labels,
		'public'    => true,
		'menu_icon' => 'dashicons-format-audio',
		'supports'  => array( 'title', 'editor', 'thumbnail' ),
	);
	register_post_type( 'track', $args );

	// Artist
	$labels = array(
		'name'                => _x( 'Artists', 'Post Type General Name', 'noise' ),
		'singular_name'       => _x( 'Artist', 'Post Type Singular Name', 'noise' ),
		'menu_name'           => __( 'Artist', 'noise' ),
		'parent_item_colon'   => __( 'Parent Artist:', 'noise' ),
		'all_items'           => __( 'All Artists', 'noise' ),
		'view_item'           => __( 'View Artist', 'noise' ),
		'add_new_item'        => __( 'Add New Artist', 'noise' ),
		'add_new'             => __( 'New Artist', 'noise' ),
		'edit_item'           => __( 'Edit Artist', 'noise' ),
		'update_item'         => __( 'Update Artist', 'noise' ),
		'search_items'        => __( 'Search artists', 'noise' ),
		'not_found'           => __( 'No artists found', 'noise' ),
		'not_found_in_trash'  => __( 'No artists found in Trash', 'noise' ),
	);
	$args = array(
		'labels'    => $labels,
		'public'    => true,
		'menu_icon' => 'dashicons-groups',
		'supports'  => array( 'title', 'thumbnail' ),
	);
	register_post_type( 'artist', $args );

	// Album
	$labels = array(
		'name'                => _x( 'Albums', 'Post Type General Name', 'noise' ),
		'singular_name'       => _x( 'Album', 'Post Type Singular Name', 'noise' ),
		'menu_name'           => __( 'Album', 'noise' ),
		'parent_item_colon'   => __( 'Parent Album:', 'noise' ),
		'all_items'           => __( 'All Albums', 'noise' ),
		'view_item'           => __( 'View Album', 'noise' ),
		'add_new_item'        => __( 'Add New Album', 'noise' ),
		'add_new'             => __( 'New Album', 'noise' ),
		'edit_item'           => __( 'Edit Album', 'noise' ),
		'update_item'         => __( 'Update Album', 'noise' ),
		'search_items'        => __( 'Search albums', 'noise' ),
		'not_found'           => __( 'No albums found', 'noise' ),
		'not_found_in_trash'  => __( 'No albums found in Trash', 'noise' ),
	);
	$args = array(
		'labels'    => $labels,
		'public'    => true,
		'menu_icon' => 'dashicons-category',
		'supports'  => array( 'title', 'thumbnail' ),
	);
	register_post_type( 'album', $args );

	// Event
	$labels = array(
		'name'                => _x( 'Events', 'Post Type General Name', 'noise' ),
		'singular_name'       => _x( 'Event', 'Post Type Singular Name', 'noise' ),
		'menu_name'           => __( 'Event', 'noise' ),
		'parent_item_colon'   => __( 'Parent Event:', 'noise' ),
		'all_items'           => __( 'All Events', 'noise' ),
		'view_item'           => __( 'View Event', 'noise' ),
		'add_new_item'        => __( 'Add New Event', 'noise' ),
		'add_new'             => __( 'New Event', 'noise' ),
		'edit_item'           => __( 'Edit Event', 'noise' ),
		'update_item'         => __( 'Update Event', 'noise' ),
		'search_items'        => __( 'Search events', 'noise' ),
		'not_found'           => __( 'No events found', 'noise' ),
		'not_found_in_trash'  => __( 'No events found in Trash', 'noise' ),
	);
	$args = array(
		'labels'    => $labels,
		'public'    => true,
		'menu_icon' => 'dashicons-calendar',
		'supports'  => array( 'title' ),
	);
	register_post_type( 'event', $args );

	// Gallery
	$labels = array(
		'name'                => _x( 'Galleries', 'Post Type General Name', 'noise' ),
		'singular_name'       => _x( 'Gallery', 'Post Type Singular Name', 'noise' ),
		'menu_name'           => __( 'Gallery', 'noise' ),
		'parent_item_colon'   => __( 'Parent Gallery:', 'noise' ),
		'all_items'           => __( 'All Galleries', 'noise' ),
		'view_item'           => __( 'View Gallery', 'noise' ),
		'add_new_item'        => __( 'Add New Gallery', 'noise' ),
		'add_new'             => __( 'New Gallery', 'noise' ),
		'edit_item'           => __( 'Edit Gallery', 'noise' ),
		'update_item'         => __( 'Update Gallery', 'noise' ),
		'search_items'        => __( 'Search galleries', 'noise' ),
		'not_found'           => __( 'No galleries found', 'noise' ),
		'not_found_in_trash'  => __( 'No galleries found in Trash', 'noise' ),
	);
	$args = array(
		'labels'    => $labels,
		'public'    => true,
		'menu_icon' => 'dashicons-format-gallery',
		'supports'  => array( 'title', 'thumbnail' ),
	);
	register_post_type( 'gallery', $args );
}