<?php
/*
Plugin Name:    Theme: 1000-Body
Plugin URI:     https://wp.cbos.ca/theme/plugins/body/
Description:    Important switching functions for the rest of the site.
Version:        2015.11.25
License:        GPLv2+
*/ 

defined( 'ABSPATH' ) || die();

function get_theme_body() {
    require_once( dirname(__FILE__) . '/template.php' );
    $str = get_body_html();
    return $str;
}

function add_body_classes( $classes ) {
    require_once( dirname(__FILE__) . '/data.php' );
    global $post;
    $body = get_body_data();
    if ( $body['header'] ) {
        if ( $body['header-full-width'] ) {
             $classes[] = 'header-full-width no-top-margin';
        }
    }
    if ( $body['body'] ) {
        if ( $body['left'] ) {
            $classes[] = 'body-left';
        }
        if ( $body['middle'] ) {
            $classes[] = 'body-middle';
        }
        if ( $body['right'] ) {
            $classes[] = 'body-right';
        }
    }
    if ( $body['footer'] && $body['footer-full-width'] ) {
            $classes[] = 'footer-full-width';
    }
    return $classes;
}
add_filter( 'body_class', 'add_body_classes' );

function the_theme_body(){
    echo get_theme_body();
}

function is_plugin_active_theme( $plugin ) {
    return in_array( $plugin, (array) get_option( 'active_plugins', array() ) );
}

function get_section_count( $items ) {
    if ( isset( $items['left'] ) && $items['left'] && isset( $items['right'] ) && $items['right'] ) {        
        return 3;
    }
    else if ( ( isset( $items['left'] ) && $items['left'] ) || ( isset( $items['right'] ) && $items['right'] ) ) {        
        return 2;
    }
    else {
        return 1;
    }
}
