<?php
/*
Plugin Name:    Theme: 0500-Setup
Plugin URI:     https://wp.cbos.ca/theme/plugins/setup/
Description:    Theme settings, including default image sizes.
Version:        2015.11.25
License:        GPLv2+
*/ 

defined( 'ABSPATH' ) || die();
 
add_theme_support( 'html5', array( 'search-form' ) );

function the_plugin_theme_setup() {
    add_editor_style();
    add_theme_support( 'post-formats', array( 'aside', 'image', 'link', 'quote', 'status' ) );
    register_nav_menu( 'primary', 'Primary Menu' );
    register_nav_menu( 'secondary', 'Secondary Menu' );
    add_theme_support( 'post-thumbnails' );
}
add_action( 'after_setup_theme', 'the_plugin_theme_setup' );

function the_plugin_theme_scripts() {
    wp_enqueue_style( 'plugin-theme', get_stylesheet_uri() );
}
add_action( 'wp_enqueue_scripts', 'the_plugin_theme_scripts' );

function visit_plugin_site( $plugin_meta ){
    if ( isset( $plugin_meta[1] ) && ! isset( $plugin_meta[2] ) && strpos( $plugin_meta[1], 'https://wp.cbos.ca' ) !==FALSE ) {
        $link = $plugin_meta[1];
        $a = new SimpleXMLElement( $link );
        $url = isset( $a['href'] ) ? ltrim( $a['href'], 'https://' ) : '';
        if ( ! empty ( $url ) ) {
            $plugin_meta[1] = sprintf( '<a href="%s">%s</a>', $a['href'], $url );
        }
    }
    return $plugin_meta;
}
add_filter( 'plugin_row_meta', 'visit_plugin_site' );

function add_device_classes( $classes ) {
    global $delivered;
    global $post;
    $post = get_hd_page( $post );
    $hd = get_post_meta( $post->ID, '_hd_url', true );
    if ( strstr( $hd, 'youtu' ) ) {
        $classes[] = 'youtube';
    }
    else if ( strstr( $hd, 'vimeo' ) ) {
        $classes[] = 'vimeo';    
    }
    else if ( strstr( $hd, 'mp4' ) ) {
        $classes[] = 'mp4';
    }
    else if ( strstr( $hd, 'mp3' ) ) {
        $classes[] = 'mp3';
    }
    else if ( strstr( $hd, 'jpg' ) ) {
        $classes[] = 'jpg';
    }
    else {
        
    }
    if ( $delivered == 'm' ) {
        $classes[] = 'mobile';
    }
    else if ( $delivered == 't' ) {
        $classes[] = 'tablet';
    }
    else if ( $delivered == 'd' ) {
        $classes[] = 'desktop';
    }
    else if ( $delivered == 'hd' ) {
        $classes[] = 'hd';
    }
    else { } 
    
    return $classes;
}
add_filter( 'body_class', 'add_device_classes', 100 );

if ( ! function_exists( 'get_hd_page' ) ) {
    function get_hd_page() {
        global $post;
        if ( ! empty ( $post ) ) {
            if ( is_front_page() ) {
                $post = get_first_hd_page( $post );
            }
            return $post;
        }
        else {
            return false;
        }
    }
}