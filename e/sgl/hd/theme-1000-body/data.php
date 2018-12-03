<?php

defined( 'ABSPATH' ) || die();

function get_body_data(){
    $items = array(
        'body' => 1,
        'hd-theme' => 1,
        'desktop-theme' => 0,
        'tablet-theme' => 0,
        'mobile-theme' => 0,
        'announcement-bar' => 0,
        'header' => 0,
        'header-title' => 0,
        'header-widgets' => 0,
        'header-full-width' => 0,
        'nav' => 0,
        'nav-top' => 0,
        'nav-top-flat' => 0,
        'nav-left-slide' => 0,
        'section' => 0,
        'div' => 0,
        'body-middle' => 1,
        'left' => 0, 
        'middle' => 1, 
        'middle-widgets' => 0,
        'right' => 0, 
        'footer' => 0,
        'footer-widgets' => 0,
        'nav-bottom' => 0,
        'footer-links' => 0,
        'footer-devices' => 0,
        'footer-full-width' => 0,
    );
    return $items;
}
