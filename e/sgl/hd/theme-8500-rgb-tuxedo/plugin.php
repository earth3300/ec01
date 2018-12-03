<?php
/*
Plugin Name:    Theme: 8400-RBG-Grey
Plugin URI:     https://wp.cbos.ca/theme/plugins/grey/
Description:    Grey.
Version:        2015.11.25
License:        GPLv2+
*/ 

defined( 'ABSPATH' ) || die();

add_action( 'wp_enqueue_scripts', 'enqueue_theme_grey', 8400 );

function enqueue_theme_grey() {
	wp_enqueue_style( 'theme-grey', plugin_dir_url(__FILE__) . 'css/style.css', array(), time() );
}
