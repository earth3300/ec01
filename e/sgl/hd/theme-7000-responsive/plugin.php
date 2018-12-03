<?php
/*
Plugin Name:    Theme: 7000-Responsive
Plugin URI:     https://wp.cbos.ca/theme/plugins/responsive/
Description:    Very lightweight responsive CSS.
Version:        2015.11.25
License:        GPLv2+
*/ 

defined( 'ABSPATH' ) || die();

add_action( 'wp_enqueue_scripts', 'enqueue_theme_responsive', 75 );
function enqueue_theme_responsive() {
    global $delivered;
    if ( in_array( $delivered, array( 'm', 't', 'd' ) ) ) {
        wp_enqueue_style( 'theme-responsive', plugin_dir_url(__FILE__) . 'css/style.css', array(), time() );
    }
}
