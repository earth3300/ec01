<?php
/*
Plugin Name:    Theme: 7100-Effects
Plugin URI:     https://wp.cbos.ca/theme/plugins/effects/
Description:    Transitions to "soften" effect changes.
Version:        2015.11.25
License:        GPLv2+
*/ 

defined( 'ABSPATH' ) || die();

add_action( 'wp_enqueue_scripts', 'enqueue_theme_effects', 50 );

function enqueue_theme_effects() {
    wp_enqueue_style( 'theme-effects', plugin_dir_url(__FILE__) . 'css/style.css', array(), time() );    
}
