<?php
/*
Plugin Name:    Theme: 1200-Body-Middle 
Plugin URI:     https://wp.cbos.ca/theme/plugins/body/middle/
Description:    The middle of the page. Required. Typically used for pages and posts.
Version:        2015.11.25
License:        GPLv2+
*/ 

defined( 'ABSPATH' ) || die();

function get_body_middle( $body ) {
    require_once( dirname(__FILE__) . '/data.php' );
    require_once( dirname(__FILE__) . '/template.php' );
    $str = switch_body_middle( $body );
    return $str;                                                                
}

function switch_body_middle(){
    global $delivered, $post;
    $body = get_body_data();
    $post_type = ! empty( $post ) ? $post->post_type : '';
    if ( $delivered == 'm' && function_exists( 'get_mobile_body' ) ) {
        return get_mobile_body( $body, $size = '1of1' ); 
    }
    else if (  $delivered == 't' && function_exists( 'get_tablet_body' ) ) {
        return get_tablet_body( $body, $size = '1of1' );
    }
    else if ( $delivered == 'd' && function_exists( 'get_desktop_body' ) ) {
        return get_desktop_body( $body, $size = '3of4' );
    }
    else if ( $body['hd-theme'] || ( in_array( 'hd' , array( $delivered, $post_type ) ) && function_exists( 'get_hd_body' ) ) ) {
        return get_hd_body( $body, $size = '1of1' );
    }
    else {
        return get_middle_html( $body, $size = '1of1' );
    }
}

function get_prev_next_page_ids(){
    $pages = get_pages( array( 'sort_column' => 'menu_order', 'sort_order' => 'asc' ) );
    $page_ids = array();
    foreach ( $pages as $page ) {
       $page_ids[] += $page -> ID;
    }
    $current = array_search( get_the_ID(), $page_ids );
    $ids['prev'] = isset( $page_ids[ $current - 1 ] ) ? $page_ids[ $current - 1 ] : '';
    $ids['next'] = isset( $page_ids[ $current + 1 ] ) ? $page_ids[ $current + 1 ] : '';
    return $ids;
}
  