<?php

defined( 'ABSPATH' ) || die();

function get_html_data(){
    $items = array(
        'doctype' => 1,
        'html' => 1,
        'languages' => 1,
        'head' => 1,
        'body' => 1,
        'classes' => 1,
        'inner' => 1,
        'wp_footer' => 1,
    );
    return $items;
}

function get_head_data(){
    $items = array(
        'wp_head' => 1,
        'charset' => 1,
        'viewport' => 1,
        'title' => 1,
        'pingback' => 0,
    );
    return $items;
}
