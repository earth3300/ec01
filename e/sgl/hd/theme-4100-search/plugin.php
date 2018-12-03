<?php
/*
Plugin Name:    Theme: 4100-Search
Plugin URI:     https://wp.cbos.ca/theme/plugins/search/
Description:    HTML5 Search Form.
Version:        2015.11.25
License:        GPLv2+
*/ 

defined( 'ABSPATH' ) || die();

function enqueue_theme_search_hd() {
	wp_enqueue_style( 'theme-search', plugin_dir_url(__FILE__) . 'css/style.css', array(), time() );
}
add_action( 'wp_enqueue_scripts', 'enqueue_theme_search_hd', 4100 );

function get_theme_search_form( $placeholder = '' ) {
    global $delivered;
    if ( empty ( $placeholder ) ) {
        $placeholder = in_array( $delivered, array( 'm', 't' ) ) ? sprintf( 'Search %s...', get_bloginfo( 'title' ) ) : 'Search...';
    }
    $str .= '<div id="search" class="align-absolute-center">' . PHP_EOL;
    $str .= sprintf( '<form class="search" method="get" action="%s">%s', get_home_url( '/' ), PHP_EOL );
    $str .= sprintf( '<input type="search" placeholder="%s" value="%s" name="s"  />%s', $placeholder, get_search_query(), PHP_EOL );
    $str .= '</form>' . PHP_EOL;
    $str .= '</div>' . PHP_EOL;
    return $str;      
}
