<?php
  
defined( 'ABSPATH' ) || die();

function get_middle_data(){
    $items = array(        
        'article' => 0,
        'nav' => 0,
        'title-link' => 0,
        'title' => 1,
        'body' => 1,
        'comments' => 0,
    );
    return $items;
}
