<?php

defined( 'ABSPATH' ) || die();

function get_body_html(){
    global $delivered, $post;
    $hd = ! empty ( $post ) && $post->post_type == 'hd' ? true : false;
    $body = get_body_data();     
    $str = '';
    if ( $body['body'] ) {
        $str .= is_front_page() && $body['announcement-bar'] && function_exists( 'get_announcement_bar' ) && in_array( $delivered, array( 'd' ) ) ? get_announcement_bar() : '';
        if ( $body['header'] ) {
            $str .= $body['header-title'] && function_exists( 'get_theme_header' ) && in_array( $delivered, array( 'd' ) ) ? get_theme_header() : '';
            $str .= $body['header-widgets'] && function_exists( 'get_header_widgets' ) && in_array( $delivered, array( 'd' ) ) ? get_header_widgets() : '';
        }
        if ( $body['nav'] ) {
            $str .= $body['nav-top'] && function_exists( 'get_nav_top' ) && in_array( $delivered, array( 'd' ) ) ? get_nav_top() : '';
            $str .= $body['nav-top-flat'] && function_exists( 'get_nav_top_flat' ) && in_array( $delivered, array( 'd' ) ) ? get_nav_top_flat() : '';
            $str .= $body['nav-left-slide'] && function_exists( 'get_nav_left_slide' ) && in_array( $delivered, array( 't', 'm' ) ) ? get_nav_left_slide() : '';
        }
        $str .= $body['section'] && ! $hd ? '<section>' . PHP_EOL : '';
        $str .= $body['div'] && ! $hd ? '<div>' . PHP_EOL : '';
        if ( $body['body-middle'] ) {
            $str .= $body['left'] && function_exists( 'get_body_left' ) && in_array( $delivered, array( 'd' ) ) && ! $hd ? get_body_left() : '';
            $str .= $body['middle'] && function_exists( 'get_body_middle' ) ? get_body_middle( $body ) : '';
            $str .= $body['right'] && function_exists( 'get_body_right' ) && in_array( $delivered, array( 'd' ) ) && ! $hd ? get_body_right() : '';
            $str .= $body['middle-widgets'] && function_exists( 'get_middle_widgets' ) && in_array( $delivered, array( 'd' ) ) && ! $hd ? get_middle_widgets() : '';
        }
        $str .= $body['div'] && ! $hd ? '</div>' . PHP_EOL : '';
        $str .= $body['section'] && ! $hd ? '</section>' . PHP_EOL : '';
        if ( $body['footer'] && ! $hd ) {
            $str .= $body['footer-widgets'] && function_exists( 'get_footer_widgets' ) && in_array( $delivered, array( 'd' ) ) ? get_footer_widgets() : '';
            $str .= $body['nav-bottom'] && function_exists( 'get_nav_bottom_flat' ) && in_array( $delivered, array( 'd', 't' ) ) ? get_nav_bottom_flat() : '';
            $str .= $body['footer-links']  && function_exists( 'get_footer_links' ) && in_array( $delivered, array( 'd', 't' ) ) ? get_footer_links() : '';
            $str .= $body['footer-devices'] && function_exists( 'get_footer_devices' ) && in_array( $delivered, array( 'd', 't', 'm', 'hd' ) ) ? get_footer_devices() : '';
        }
    }
    return $str;
}
