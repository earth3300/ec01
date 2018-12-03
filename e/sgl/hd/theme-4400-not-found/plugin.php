<?php
/*
Plugin Name:    Theme: 4400-Not-Found
Plugin URI:     https://wp.cbos.ca/theme/plugins/not-found/
Description:    Not Found Template.
Version:        2015.11.25
License:        GPLv2+
*/ 

defined( 'ABSPATH' ) || die();

function get_not_found() {
    require_once( dirname(__FILE__) . '/data.php' );
    require_once( dirname(__FILE__) . '/template.php' );
    $str = get_not_found_html();
    return $str;
}

