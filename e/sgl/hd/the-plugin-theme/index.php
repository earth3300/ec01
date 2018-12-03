<?php

defined( 'ABSPATH' ) || die();

//roll the mower out of the garage...

header('Content-type: text/html; charset=utf-8;');

function try_detect_device() {
    $ua = $_SERVER['HTTP_USER_AGENT'];
    preg_match("/(Firefox|Chrome|MSIE)[.\/]([\d.]+)/", $ua, $matches);
    preg_match("/(MSIE) ([\d.]+)/", $ua, $ie);
    $detected['mobile'] = strstr( strtolower( $ua ), 'mobile' ) ? true : false;
    $detected['android'] = strstr( strtolower( $ua ), 'android' ) ? true : false;
    $detected['phone'] = strstr( strtolower( $ua ), 'phone' ) ? true : false;
    $detected['ipad'] = strstr( strtolower( $ua ), 'ipad' ) ? true : false;
    $detected['msie'] = strstr( strtolower( $ua ), 'msie' ) ? true : false;
    $detected['version'] = isset( $matches[2] ) ? $matches[2] : null;
    $detected['ns'] = isset( $ie[2] ) && $ie[2] < 10 ? true : false;
    return $detected;
}

function get_device_request(){
    global $device;
    if ( isset( $_GET['t'] ) ) {
        $device = 't';
        return $device;
    }
    else if ( isset( $_GET['mobile'] ) ) {
        $device = 'm';
        return $device;
    }
    else if ( isset( $_GET['d'] ) ) {
        $device = 'd';
        return $device;
    }
    else if ( isset( $_GET['a'] ) ) {
        $device = 'a';
        return $device;
    }
    else if ( isset( $_GET['hd'] ) ) {
        $device = 'hd';
        return $device;
    }
    else {
        $device = '';
        return $device;
    }
}
get_device_request();

function get_detected_device() {
    $device = try_detect_device();
    global $detected;
    if ( $device['phone'] ) {
        $detected = 'm';
        return $detected;
    }
    else if ( $device['mobile'] && $device['android'] ) {
        $detected = 'm';
        return $detected;
    }
    else if ( ! $device['mobile'] && $device['android'] ) {
        $detected = 't';
        return $detected;
    }
    else if ( $device['ipad'] ) {
        $detected = 't';
        return $detected;
    }
    else if ( $device['ns'] ) {
        $detected = 'ns'; //not serviced
        return $detected;
    }
    else {
        $detected = 'd';
        return $detected;
    }
}
get_detected_device();

function get_delivered_theme() {
    global $delivered;
    global $device;
    global $detected;
    if ( $device == 'm' ) {
        $delivered = 'm';
    }
    else if ( $device == 't' ) {
        $delivered = 't';
    }
    else if ( $device == 'd' ){
        $delivered = 'd';
    }
    else if ( $device == 'hd' ) {
        $delivered = 'hd';
    }
    else {
        $delivered = $detected;
    }
}
get_delivered_theme();

require_once( dirname(__FILE__) . '/the-theme.php' );

require_once( dirname(__FILE__) . '/no-theme.php' );

//pull the cord...

the_plugin_theme_html();

//walk away
