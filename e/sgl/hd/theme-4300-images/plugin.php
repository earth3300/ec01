<?php
/*
Plugin Name:    Theme: 4310-Images-HD
Plugin URI:     https://wp.cbos.ca/theme/plugins/images/HD/
Description:    Image sizes and additional image functionality. HD Optimized.
Version:        2015.11.25
License:        GPLv2+
*/ 

defined( 'ABSPATH' ) || die();
                                                             
function theme_image_sizes() {
    set_post_thumbnail_size( 150, 9999 );
    add_image_size( 'tablet', 550, 9999 );
    add_image_size( 'desktop', 750, 9999 );
    add_image_size( 'hd', 1920, 9999 );
    add_image_size( 'hd-viewport', 1690, 965 );
}
add_action( 'after_setup_theme', 'theme_image_sizes' );

function theme_image_names( $sizes ) {
    unset( $sizes['thumbnail'] );
    unset( $sizes['large'] );
    unset( $sizes['full'] );
    $arr = array_merge( $sizes, array(
        'tablet' => 'Tablet',
        'desktop' => 'Desktop',
        'hd' => 'HD',
        'hd-viewport' => 'HD Viewport',
        'full' => 'Full',
    ) );
    return $arr;
}
add_filter( 'image_size_names_choose', 'theme_image_names' );
