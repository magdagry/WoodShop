<?php
add_action( 'wp_enqueue_scripts', 'metro_child_styles', 18 );
function metro_child_styles() {
	wp_enqueue_style( 'metro-child-style', get_stylesheet_uri() );
}

add_action( 'after_setup_theme', 'metro_child_theme_setup' );
function metro_child_theme_setup() {
    load_child_theme_textdomain( 'metro', get_stylesheet_directory() . '/languages' );
}