<?php
/*
Plugin Name:    Theme: 3500-Controls
Plugin URI:     https://wp.cbos.ca/theme/plugins/controls/
Description:    Controls. Primarily Javascript/jQuery.
Version:        2015.12.15
License:        GPLv2+
*/ 

defined( 'ABSPATH' ) || die();

function enqueue_theme_controls(){
	wp_enqueue_style( 'theme-controls', plugin_dir_url(__FILE__) . 'css/style.css', array(), time() );
    wp_enqueue_script( 'controls',  plugin_dir_url(__FILE__) . 'js/javascript.js', array( 'jquery' ), time(), true );
}
add_action( 'wp_enqueue_scripts', 'enqueue_theme_controls', 3500 );
