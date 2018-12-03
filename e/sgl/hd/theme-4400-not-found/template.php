<?php

defined( 'ABSPATH' ) || die();

function get_not_found_html() {
    $not_found = get_not_found_data();
    $str = '<section>' . PHP_EOL;
    $str .= '<div>' . PHP_EOL;
    $str .= $not_found['text']['show'] ? $not_found['text']['text'] : '';
    if ( function_exists( 'get_search_form' ) ) {
        $str .= $not_found['search'] ? get_theme_search_form() : '';
    }
    $str .= $not_found['mail']['show'] ? $not_found['mail']['text'] : '';
    $str .= $not_found['mail']['show'] ? get_mailer_form() : '';
    $str .= $not_found['phone']['show'] ? $not_found['phone']['text'] : '';
    $str .= $not_found['phone']['show'] ? get_address_html() : '';
    $str .= '</div>' . PHP_EOL;
    $str .= '</section>' . PHP_EOL;
    return $str;
}


