<?php

defined( 'ABSPATH' ) || die();

function get_middle_html( $body, $size ) {
    global $delivered;
    global $post;    
    $middle = get_middle_data();
    if ( have_posts() ) {
        the_post();
        $cnt = get_section_count_middle( $body );
        $class = $cnt > 1 ? sprintf( ' class="%s"', get_middle_size_of( $cnt ),  PHP_EOL ) : '';
        $str = $middle['article'] ? sprintf( '<article%s>', $class, PHP_EOL ) : '';
        if ( $middle['title'] ) {
            $str .= ! is_front_page() && $middle['title-link'] ? sprintf( '<h1><a href="%s" title="%s">%s</a></h1>%s', get_the_permalink(), get_the_title(), get_the_title(), PHP_EOL ): '';
            $str .= ! is_front_page() && ! $middle['title-link'] ? sprintf( '<h1>%s</h1>%s', get_the_title(), PHP_EOL ): '';
        }
        $str .= $middle['body'] ? apply_filters( 'the_content', get_the_content() ) : '';
        $str .= $middle['article'] ? '</article>' . PHP_EOL : '';
        $str .= $middle['nav'] ? get_article_nav() : '';
        $str .= $middle['comments'] ? get_theme_comments() : '';
        return $str;
    } else {
        $str = get_not_found();
        return $str;
    }
}

function get_article_nav(){
    return get_article_nav_html();
}

function get_article_nav_html(){
    $ids = get_prev_next_page_ids();
    $prev = get_post( $ids['prev'], OBJECT, 'page' );
    $next = get_post( $ids['next'], OBJECT, 'page' );
    $str = '<div style="clear: both;"></div>';
    $str .= '<nav>';
    $str .= ! empty( $ids['prev'] ) ? sprintf( '<span class="alignleft">&laquo; <a href="%s">%s</a></span>' , get_the_permalink ( $ids['prev'] ), $prev->post_title ) : '';
    $str .= ! empty( $ids['next'] ) ? sprintf( '<span class="alignright"><a href="%s">%s</a> &raquo;</span>' , get_the_permalink ( $ids['next'] ), $next->post_title ) : '';
    $str .= '</nav>';
    return $str;
}

function get_section_count_middle( $items ){
    $cnt = count( array_filter( array( $items['left'], $items['middle'], $items['right'] ) ) );
    return $cnt;
} 

function get_middle_size_of( $cnt ){
    global $delivered;
    if ( $cnt == 1 || in_array( $delivered, array( 'm', 't' ) ) ) {
        return 'size1of1';
    } 
    else if ( $cnt == 2 ) {
        return 'size3of4';
    }
    else if ( $cnt == 3 ) {
        return 'size2of4';
    }
}
