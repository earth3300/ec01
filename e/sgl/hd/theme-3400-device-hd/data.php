<?php

defined( 'ABSPATH' ) || die();

function get_hd_header_data() {
    $items = array(
    	'header' => 1,
        'inner' => 1,
		'left' => 1,
    	'title' => 0,
    	'center' => 1,
    	'right' => 1,
    	'nav-bar' => 0,
    	'logo' => 1,
    	'search' => 1,
        'tagline' => 0,
        'author' => 0,
        'university' => 0,
        'series' => 1,
        'date' => 0,
        'address' => 0,
    	'location' => '',
    	'phone' => '',
    );
    return $items;
}

function get_hd_body_data() {
    $items = array(
    	'scroll' => 0,
    	'type' => 'pages',
    	'pages_per_load' => 3,
    	'corral' => 1,
        'frame' => 1,
    	'peripheral' => 0,
    	'inner' => 1,
    	'nav' => 1,
    	'hd-controls' => 0, 
        'sort' => 1, // 0 = ASC
        'slides' => 1,
    	'book' => 1,
        'footer' => 1,
    );
    return $items;
}

function get_hd_page_data() {
	$items = array(
		'section' => 0,
		'inner' => 0,
		'title' => 1,
		'author' => 1,
		'author-bar' => 0,
		'thumbnail' => 1,
		'bottom-left'  => 1,
		'bottom-right' => 1,
		'page-number' => 1,
		'audio' => 1,
		'mp3' => 1,
		'image' => 1,
		'video' => 1,
		'vimeo' => 1,
		'youtube' => 1,
		'mp4' => 1,
		'webm' => 1,
	);
	return $items;
}

function get_hd_format_data(){
    $items = array( 
        'youtube' => 'https://www.youtube.com/embed',
        'vimeo' => 'https://player.vimeo.com/video',
        'mp4' => '',
        'image' => home_url( '/wp-content/uploads' ),
    );
    return $items;
}

function get_hd_metabox_data(){
    $desc = 'Youtube: <strong>https://youtu.be/----</strong><br />';
    $desc .= 'Vimeo: <strong>https://vimeo.com/----</strong><br />';
    $desc .= 'mp4: <strong>video-name.mp4</strong> (/uploads/*.*)<br />';
    $desc .= 'Image: <strong>image-name.jpg</strong> (/uploads/*.*)';
    
    $items = array(
        'title' => 'HD',
        'desc' => $desc,
    );
    return $items;
}

function get_hd_metabox_items(){
    $items = array(
        array( 'title' => 'URL', 'name' => 'hd_url', 'desc' => '', 'show' => 1 ),
    	array( 'title' => 'Parameters', 'name' => 'hd_parameters', 'desc' => '', 'show' => 1 ),
        array( 'title' => 'Author', 'name' => 'hd_author', 'desc' => '', 'show' => 1 ),
        array( 'title' => 'Series', 'name' => 'hd_series', 'desc' => '', 'show' => 1 ),
        array( 'title' => 'Series URL', 'name' => 'hd_series_url', 'desc' => '', 'show' => 1 ),
        array( 'title' => 'Reference', 'name' => 'hd_reference_url', 'desc' => '', 'show' => 1 ),
        array( 'title' => 'Credit Text', 'name' => 'hd_credit_text', 'desc' => '', 'show' => 1 ),
        array( 'title' => 'Credit URL', 'name' => 'hd_credit_url', 'desc' => '', 'show' => 1 ),
        array( 'title' => 'License Text', 'name' => 'hd_license_text', 'desc' => '', 'show' => 1 ),
        array( 'title' => 'License URL', 'name' => 'hd_license_url', 'desc' => '', 'show' => 1 ),
        array( 'title' => 'Value', 'name' => 'hd_value', 'desc' => '', 'show' => 1 ),        
    );
    return $items;
}

function get_hd_author_bar_data() {
    $items = array( 
        'inner' => 1,
        'image' => 1,
        'thumb_url' => 1,
        'name' => 1,
        'credentials' => 1,
        'title' => 1,
        'university' => 1,
        'university_url' => 1,
    );
    return $items;
}

function get_hd_bottom_right_data(){
    $items = array( 
        'right' => 1,
        'div' => 1,
        'text' => 1,
        'support' => 1,
        'coffee' => 0,
    );
    return $items;
}

function get_hd_bottom_left_data(){
    $items = array( 
        'left' => 1,
        'inner' => 1,
        'text' => 1,
        'image' => 0,
    );
    return $items;
}

function get_hd_controls_data(){
	$items = array(
			'inner' => 1,
			'page-left' => 1,
			'header-up' => 1,
			'default-screen' => 1,
			'full-screen' => 0,
			'page-right' => 1,
	);
	return $items;
}

function get_hd_nav_bar_data() {
	$items = array(
			'inner' => 1,
			'home' => 1,
			'info' => 1,
			'mail' => 1,
			'handset' => 1,			
	);
	return $items;
}

function get_hd_footer_data() {
    $items = array( 
        'inner' => 1,
        'devices' => 1,
        'left' => 1,
        'middle' => 1,
        'right' => 1,
        'author' => 0,
        'license_text' => 1,
        'license_url' => 1,
        'date' => 0,
        'reference' => 0,
        'exo_tape' => 1,
        'email' => 0,
        'promo' => 0,
    );
    return $items;
    
}

function get_hd_footer_items() {
    $items = array( 
        'email' => '&#119;&#112;&#064;&#099;&#098;&#111;&#115;&#046;&#099;&#097;',
        'link' => '<a href="https://wp.cbos.ca/themes/the-hd-theme/" target="_blank">The HD Theme</a>',
        'promo' => '<a href="https://wp.cbos.ca/">Let&apos;s find out! &raquo;</a>',
    );
    return $items;
}
