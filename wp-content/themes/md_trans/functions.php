<?php
// Add some bind_textdomain_codeset

//Drag and drop menu support
register_nav_menu( 'transit', 'Transitional Menu' );


//Enqueue Styles
function sgi_load_stuff() {

	wp_register_style( 'skeleton-style', get_template_directory_uri() . '/style.css');
	wp_register_style( 'skeleton-base', get_template_directory_uri() . '/stylesheets/base.css');
	wp_register_style( 'skeleton-layout', get_template_directory_uri() . '/stylesheets/layout.css');
	wp_register_style( 'merricons', get_template_directory_uri() . '/stylesheets/merricons.css');

	wp_enqueue_style( 'skeleton-style' );
	wp_enqueue_style( 'skeleton-base' );
	wp_enqueue_style( 'skeleton-layout' );
	wp_enqueue_style( 'merricons' );

}
add_action('wp_enqueue_scripts', 'sgi_load_stuff');




function sgi_loadnew_stuff() {

wp_register_style( 'trans-style', get_template_directory_uri() . '/../md_trans/trans.css');
wp_enqueue_style( 'trans-style' );
}
add_action('wp_enqueue_scripts', 'sgi_loadnew_stuff');
