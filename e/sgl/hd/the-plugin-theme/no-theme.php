<?php

defined( 'ABSPATH' ) || die();
  
function get_plugin_theme_html(){
    $str = '<body style="background: #e6e6e6; font-family: sans-serif; color: #333;">' . PHP_EOL;
    $str .= '<html>' . PHP_EOL;
    $str .= '<div class="wrap" style="background-color: #fff; margin: 20px; padding: 20px; box-shadow: 0 2px 6px rgba(100, 100, 100, 0.3);">' . PHP_EOL;
    $str .= get_plugin_theme_message();
    $str .= '</div>' . PHP_EOL;
    $str .= '</html>' . PHP_EOL;
    $str .= '</body>' . PHP_EOL;
    return $str;
}

function get_plugin_theme_message(){
    $str = '<div style="width: 100%; max-width: 500px;">';
    $str .= sprintf( '<h3 style="margin-top: 0;">%s</h3>', 'Plugin Theme' );
    $str .= '<p>';
    $str .= 'This is a plugin based theme. It is built this way to encourage you ';
    $str .= 'to understand how a theme works, and should allow you to swap out ';
    $str .= 'components more easily. For more information, please see ';
    $str .= '<a href="https://wp.cbos.ca/theme/plugins/">wp.cbos.ca/theme/plugins/</a>.';
    $str .= '</p>' . PHP_EOL;
    $str .= '<p>If you are seeing this message, that means or more plugins are ';
    $str .= 'required. Please see the above link for assistance. Thank you.</p>';
    $str .= '</div>' . PHP_EOL;
    return $str;
}
