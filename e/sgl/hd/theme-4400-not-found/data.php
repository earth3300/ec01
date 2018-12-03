<?php
    
defined( 'ABSPATH' ) || die();

function get_not_found_data(){
    $items = array( 
        'search' => 1,
        'text' => array( 'show' => 1, 'text' => '<p>We&apos;re sorry, the item you requested was not found. Please try a search below:</p>' ),
        'mail' => array( 'show' => 0, 'text' => '<p>If you wish, you may send us a message to see if we can help.</p>' ),
        'phone' => array( 'show' => 0, 'text' => '<p>Or, give us a call and we will do our best to help.</p>' ),
    );
    return $items;
}
