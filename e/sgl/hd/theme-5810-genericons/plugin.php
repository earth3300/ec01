<?php
/*
Plugin Name:    Theme: 5810-Font-Genericons
Plugin URI:     https://wp.cbos.ca/theme/plugins/font/genericons/
Description:    Genericons
Version:        2016.02.14
License:        GPLv2+
*/ 

defined( 'ABSPATH' ) || die();

add_action( 'wp_enqueue_scripts', 'enqueue_theme_font_genericons', 5810 );
function enqueue_theme_font_genericons() {
    wp_enqueue_style( 'theme-genericons', plugin_dir_url(__FILE__) . 'css/style.css', array(), time() );
}
