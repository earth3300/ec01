<?php
  
defined( 'ABSPATH' ) || die();

function the_plugin_theme_html(){
    require_once( dirname(__FILE__) . '/data.php' );
    $html = get_html_data();
    $doctype = $html['doctype'] ? '<!DOCTYPE html>' . PHP_EOL : '';
    if ( $html['html'] ) {
        $doctype .= $html['languages'] ? sprintf( '<html %s>%s', get_language_attributes(), PHP_EOL ) : '<html>' . PHP_EOL;
    }
    echo $doctype;
    if ( $html['head'] ) {
        the_plugin_theme_head();
    }
    if( $html['body'] ) {
        echo $html['classes'] ? sprintf( '<body class="%s">%s', implode( ' ', get_body_class() ), PHP_EOL ) : '<body>' . PHP_EOL;
    }
    if ( $html['inner'] ) {
        the_plugin_theme_body( $html );
    }
    if ( $html['wp_footer'] ) {
        wp_footer();
    }
    echo $html['body'] ? '</body>' . PHP_EOL : '';
    echo $html['html'] ? '</html>' . PHP_EOL : '';
}

function the_plugin_theme_head(){
    global $delivered;
    $head = get_head_data();
    $str = '<head>' . PHP_EOL;
    $str .= $head['charset'] ? sprintf( '<meta charset="%s" />%s', get_bloginfo( 'charset' ), PHP_EOL ) : '';
    $str .= $head['viewport'] ? '<meta name="viewport" content="width=device-width" />' . PHP_EOL : '';
    $str .= $head['title'] ? $str .= sprintf( '<title>%s</title>%s', wp_title( '|', false, 'left' ),  PHP_EOL ) : '';
    $str .= $head['pingback'] ? sprintf( '<link rel="pingback" href="%s" />%s', get_bloginfo( 'pingback_url' ), PHP_EOL ) : '';
    echo $str;
    if ( $head['wp_head'] ) {
        wp_head();
    }
    echo '</head>' . PHP_EOL;
}

function the_plugin_theme_body( $html ){
    if ( $html['body'] && function_exists( 'the_theme_body' ) ) {
        echo get_theme_body(); 
    }
    else {
        echo get_plugin_theme_html();
    }
}
