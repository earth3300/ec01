<?php
/*
Plugin Name:    Theme: 3700-Natural
Plugin URI:     https://wp.cbos.ca/themes/the-natural/
Description:    The Natural. A no-scroll theme.
Version:        2016.02.15
License:        GPLv2+
*/ 

defined( 'ABSPATH' ) || die();

require_once( dirname(__FILE__) . '/taxonomy.php' );
require_once( dirname(__FILE__) . '/metabox.php' );

function get_hd_body( $body='' ) {
    require_once( dirname(__FILE__) . '/data.php' );
    require_once( dirname(__FILE__) . '/template.php' );
    $str = get_hd_body_html( $body );
    return $str;
}

function enqueue_theme_hd_scripts(){
    wp_enqueue_script( 'jquery-hd',  plugin_dir_url(__FILE__) . 'js/javascript.js', array( 'jquery' ), time(), true );
}
add_action( 'wp_enqueue_scripts', 'enqueue_theme_hd_scripts', 3400 );

if ( ! function_exists( 'auth_dump' ) ) {
    function auth_dump( $arr ) {
        if ( current_user_can( 'manage_options' ) ) {
            echo '<pre>';
            var_dump( $arr );
            echo '</pre>';
        }
    }
}

function get_hd_nav_links(){
    $items = get_hd_body_data();
    $sort = $items['sort'] ? 'DESC' : 'ASC';
    $query = new WP_Query( array( 'post_type' => 'hd', 'orderby' => 'date', 'order' => $sort, 'post_status' => 'publish', 'posts_per_page' => -1 ) );
    while ( $query->have_posts() ) {
        $query->the_post(); 
        global $post; 
        $ids[] = $post->ID;
    }
    wp_reset_query();
    global $post;
    $key = array_search( $post->ID, $ids );
    if ( $items['sort'] ) {
        $next_id =  $key > 0 ? $ids[ $key - 1 ] : '';
        $prev_id = $key < count( $ids ) - 1 ? $ids[ $key + 1 ] : '';
    }
    else {
        $next_id = $key < count( $ids ) - 1 ? $ids[ $key + 1 ] : '';
        $prev_id =  $key > 0 ? $ids[ $key - 1 ] : '';
    }
    $next_url = ! empty( $next_id ) ? get_the_permalink( $next_id ) : '';
    $prev_url = ! empty( $prev_id ) ? get_the_permalink( $prev_id ) : '';
    $links = array (
        'next' => array( 'url' =>  $next_url ),
        'prev' => array( 'url' => $prev_url ),
    );
     return $links;
}

function enqueue_theme_device_hd() {
    wp_enqueue_style( 'device-hd', plugin_dir_url(__FILE__) . 'css/style.css', array(), time() );
}
add_action( 'wp_enqueue_scripts', 'enqueue_theme_device_hd', 45 );

function enqueue_theme_device_hd_admin() {
	wp_enqueue_style( 'device-hd-admin', plugin_dir_url(__FILE__) . 'css/admin.css', array(), time() );
}
add_action( 'admin_enqueue_scripts', 'enqueue_theme_device_hd_admin' );

function the_plugin_theme_title( $title, $seperator ) {
    global $post;
    if ( ! empty ( $post ) ) {
        $items = get_hd_page_title( $post );
        if ( isset( $items['series'] ) && $items['series'] ) {
            $title = sprintf( "%s %s %s", $items['title'] , $seperator, $items['series'] );
        }
        else {
            $site = get_bloginfo( 'title' );
            $title = $site ? $site : ucfirst( $post->post_type );
            $title = sprintf( "%s %s %s", $items['title'] , $seperator, $title );
        }
    }
    return $title;
}
add_filter( 'wp_title', 'the_plugin_theme_title', 10, 2 );

function get_hd_page_title( $post ) {
    if ( get_option('page_on_front') == $post->ID  ) {
        if ( $page = get_first_hd_page( $post ) ) {
            $items['title'] = $page->post_title;
            $series = get_post_meta( $page->ID, '_hd_series', true );
            $items['series'] = $series != 'false' ? $series: '';
            return $items;
        }
    }
    else if ( empty ( $page ) ) {
        $items['title'] = $post->post_title;
        $series = get_post_meta( $post->ID, '_hd_series', true );
        $items['series'] = $series != 'false' ? $series: '';
        return $items;
    } 
}

function get_first_hd_page( $post ){
    require_once( dirname(__FILE__) . '/data.php' );
    $body = get_hd_body_data();
    $sort = $body['sort'] ? 'DESC' : 'ASC';
    $pages = get_posts( array( 'post_type' => 'hd', 'order' => $sort, 'orderby' => 'post_date', 'post_status' => 'publish', 'posts_per_page' => 1 ) );
    if ( ! empty ( $pages[0] ) ) {
        return $pages[0];
    }
    else {
        return $post;
    }
}
