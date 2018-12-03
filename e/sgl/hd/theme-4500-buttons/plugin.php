<?php
/*
Plugin Name:    Theme: 4500-Buttons
Plugin URI:     https://wp.cbos.ca/theme/plugins/buttons/
Description:    Cool (and not so cool) button styles.
Version:        2015.11.25
License:        GPLv2+
*/ 

defined( 'ABSPATH' ) || die();

function enqueue_theme_buttons() {
    wp_enqueue_style( 'theme-buttons', plugin_dir_url(__FILE__) . 'css/style.css', array(), time() );
}
add_action( 'wp_enqueue_scripts', 'enqueue_theme_buttons', 50 );
