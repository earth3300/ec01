<?php
/*
Plugin Name: EC01 Community Design
Plugin URI: http://wp.cbos.ca/plugins/ec01-community-design/
Description: EC01 Community Design. Shortcode `[ec01-community]`.
Version: 2018.09.11
Author: wp.cbos.ca
Author URI: http://wp.cbos.ca
License: GPLv2+
*/

defined( 'ABSPATH' ) || exit;

add_shortcode( 'ec01-community', 'get_ec01_community' );

function get_ec01_community(){
	require_once( __DIR__ . '/data/class-data.php' );
	require_once( __DIR__ . '/includes/class-css.php' );
	require_once( __DIR__ . '/includes/class-html.php' );

	$css = new Integrated_Framework_CSS();
	echo $css->css();
	$html = new Integrated_Framework_HTML();
	return $html->html();
}
