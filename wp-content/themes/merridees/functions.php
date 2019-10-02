<?php
/**
 * @package WordPress
 * @subpackage WP-Skeleton
 */

//Drag and drop menu support
register_nav_menu( 'primary', 'Primary Menu' );
//This theme uses post thumbnails
add_theme_support( 'post-thumbnails', array( 'page' ) );
add_image_size('page-headers', 9999, 315, true);
add_image_size('event-img', 800, 800, true);
//Apply do_shortcode() to widgets so that shortcodes will be executed in widgets
add_filter( 'widget_text', 'do_shortcode' );

//Widget support for a right sidebar
register_sidebar( array(
	'name' => 'Left Sidebar',
	'id' => 'left-sidebar',
	'description' => 'Widgets in this area will be shown on the left-hand side of your secondary pages.',
	'before_widget' => '<div id="%1$s">',
	'after_widget'  => '</div>',
	'before_title' => '<h3>',
	'after_title' => '</h3>'
));

//Widget support for a right sidebar
register_sidebar( array(
	'name' => 'Home Sidebar',
	'id' => 'home-sidebar',
	'description' => 'Widgets in this area will be shown on the left-hand side of your home page.',
	'before_widget' => '<div id="%1$s">',
	'after_widget'  => '</div>',
	'before_title' => '<h3>',
	'after_title' => '</h3>'
));

//Enqueue Styles
function jlb_load_stuff() {

	wp_register_style( 'skeleton-style', get_template_directory_uri() . '/style.css');
	wp_register_style( 'skeleton-base', get_template_directory_uri() . '/stylesheets/base.css');
	wp_register_style( 'skeleton-layout', get_template_directory_uri() . '/stylesheets/layout.css');
	wp_register_style( 'merricons', get_template_directory_uri() . '/stylesheets/merricons.css');

	wp_enqueue_style( 'skeleton-style' );
	wp_enqueue_style( 'skeleton-base' );
	wp_enqueue_style( 'skeleton-layout' );
	wp_enqueue_style( 'merricons' );
}
add_action('wp_enqueue_scripts', 'jlb_load_stuff');

function jlb_load_mobile_menu() {
	
	wp_register_script( 'modernizr', get_template_directory_uri() . '/js/modernizr.custom.js' );
	wp_register_script( 'mlpush', get_template_directory_uri() . '/js/mlpushmenu.js', 'false', '1.0', 'true' );
	
	wp_enqueue_script('jquery');
	wp_enqueue_script( 'modernizr' );
	wp_enqueue_script( 'mlpush' );

}
add_action( 'wp_enqueue_scripts', 'jlb_load_mobile_menu' );

add_theme_support( 'post-thumbnails', array( 'post','slides','page', 'events' ) );

// Register Custom Post Type
function merridees_events_cpt() {

	$labels = array(
		'name'                => _x( 'Events', 'Post Type General Name', 'text_domain' ),
		'singular_name'       => _x( 'Event', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'           => __( 'Events', 'text_domain' ),
		'parent_item_colon'   => __( 'Parent Event', 'text_domain' ),
		'all_items'           => __( 'All Events', 'text_domain' ),
		'view_item'           => __( 'View Event', 'text_domain' ),
		'add_new_item'        => __( 'Add New Event', 'text_domain' ),
		'add_new'             => __( 'Add New', 'text_domain' ),
		'edit_item'           => __( 'Edit Event', 'text_domain' ),
		'update_item'         => __( 'Update Event', 'text_domain' ),
		'search_items'        => __( 'Search Event', 'text_domain' ),
		'not_found'           => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'text_domain' ),
	);
	$args = array(
		'label'               => __( 'events', 'text_domain' ),
		'description'         => __( 'A list of upcoming events at Merridee\'s', 'text_domain' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'thumbnail', 'revisions', 'custom-fields', ),
		'taxonomies'          => array( 'category' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 10,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
	);
	register_post_type( 'events', $args );

}

// Hook into the 'init' action
add_action( 'init', 'merridees_events_cpt', 0 );

// Web Life Reporting Tool
//include('web-life-options.php');
