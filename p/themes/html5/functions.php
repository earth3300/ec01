<?php

if ( 1 && file_exists( __DIR__ . '/data/data.php' ) ) {
	require_once( __DIR__ . '/data/data.php' );
}

if ( 1 && file_exists( __DIR__ . '/template.php' ) ) {
	require_once( __DIR__ . '/template.php' );
}

if ( 1 && file_exists( __DIR__ . '/actions/actions.php' ) ) {
	require_once( __DIR__ . '/actions/actions.php' );
}

function html5_theme_setup() {
	if ( 0 ) {
		//load_theme_textdomain( 'html5' );

		//add_editor_style();

		//add_theme_support( 'post-formats', array( 'aside', 'image', 'link', 'quote', 'status' ) );

		add_theme_support( 'post-formats', array( 'aside' ) );

		register_nav_menu( 'primary', __( 'Primary Menu', 'html5' ) );

		register_nav_menu( 'footer', __( 'Footer Menu', 'html5' ) );

		add_theme_support( 'post-thumbnails' );
	}
}
add_action( 'after_setup_theme', 'html5_theme_setup' );

function do_html5_theme(){
	if ( 0 ) {
		do_action( 'html5_head' );

		do_action( 'html5_header' );

		do_action( 'html5_article' );

		do_action( 'html5_aside' );

		do_action( 'html5_footer' );
	}
}

function html5_theme_init(){
	if ( 0 ) {
		do_html5_theme();
	}
}
