<?php
/*
Plugin Name:    Theme: 5210-Font-Slabo-Roboto
Plugin URI:     https://wp.cbos.ca/theme/plugins/font/slabo-roboto/
Description:    Slabo 27px (headings) and Roboto (everything else).
Version:        2015.11.25
License:        GPLv2+
*/ 

defined( 'ABSPATH' ) || die();

add_action( 'wp_enqueue_scripts', 'enqueue_theme_font_slabo_roboto', 50 );
function enqueue_theme_font_slabo_roboto() {
    wp_enqueue_style( 'theme-fonts', plugin_dir_url(__FILE__) . 'css/style.css' , array(), null );
}
