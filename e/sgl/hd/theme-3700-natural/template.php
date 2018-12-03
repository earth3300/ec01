<?php

defined( 'ABSPATH' ) || die();

function get_hd_body_html( $body ){
	if ( $post = get_hd_page() ) {
		$body = get_hd_body_data();
		$str = '';
		$str .= $body['corral'] ? sprintf( '<div id="corral">' . PHP_EOL ) : '';
		$str .= get_header_html( $post );
		$str .= $body['frame'] ? '<div id="frame" class="pages">' .  PHP_EOL : '';
		$str .= $body['inner'] ? '<div class="inner">' . PHP_EOL : '';
		$str .= get_hds_html( $post );
		$str .= $body['inner'] ? '</div>' . PHP_EOL : '';
		$str .= $body['peripheral'] ? get_hd_peripheral() : '';
		$str .= $body['frame'] ? '</div>' . PHP_EOL : '';
		$str .= $body['nav'] && is_hd_page( $post ) ? get_hd_nav_html() : '';
		$str .= $body['footer'] ? get_hd_footer_html( $post ) : '';
		$str .= $body['corral'] ? sprintf( '</div>' . PHP_EOL ) : '';
		$str .= $body['hd-controls'] && is_hd_page( $post ) ? get_hd_controls_html() : '';
		return $str;
	}
	else {
		$str = get_not_found();
		return $str;
	}
}

function get_hd_frame_classes( $post, $args ){
	if ( $type = get_hd_type( $post, $args ) ) {
		$str = sprintf( ' class="%s"', $type );
		return $str;
	}
	else {
		return false;
	}
}

function is_hd_page( $post ) {
	if ( $post->post_type == 'hd' ) {
		return true;
	}
	else {
		return false;
	}
}

function is_hd_video( $hd ) {
	if ( strstr( $hd, 'youtu' ) ){
		return true;
	}
	else {
		return false;
	}
}

function get_header_html( $post ){
	$header = get_hd_header_data();
	$hd_series = get_post_meta( $post->ID, '_hd_series', true );
	$hd_series_url = get_post_meta( $post->ID, '_hd_series_url', true );
	if ( $header['header'] ) {
		$str = '<header>' . PHP_EOL;
		$str .= $header['inner'] ? '<div class="inner">' : '';
		if ( $header['left'] ) {
			if ( $header['tagline'] && is_front_page() ) {
				$str .= sprintf( '<span class="align-float-left mobile-hide tablet-hide"><a href="%s">%s</a></span>', home_url(), get_bloginfo( 'description' ) );
			}
			else {
				$str .= $header['title'] ? sprintf( '<span id="title" class="title align-float-left mobile-hide tablet-hide"><a href="%s">%s</a></span>', get_the_permalink( $post->ID ), $post->post_title ) : '';
			}
		}
		if ( $header['center'] && $header['search'] ) {
			$str .= get_theme_search_form();
		}
		if ( $header['right'] && $hd_series != 'false' ) {
			if ( $header['series'] && $hd_series ) {
				$str .= ! $hd_series_url ? sprintf( '<span class="align-float-right mobile-hide">%s</span>', $hd_series ) : '';
				$str .= $hd_series_url ? sprintf( '<span class="align-float-right mobile-hide""><a href="%s" title="%s" target="_blank"> %s</span>', $hd_series_url, $hd_series, $hd_series ) : '';
			}
			else if ( $header['author'] && $header['university'] && $author['university'] ) {
				$str .= ! $author['university'] ? sprintf( '<span class="align-float-right mobile-hide">%s</span>', $author['university'] ) : '';
				$str .= $author['university_url'] ? sprintf( '<span class="align-float-right mobile-hide""><a href="%s" title="%s" target="_blank"> %s</span>', $author['university_url'], $author['university'], $author['university'] ) : '';
			}
			else if ( $header['author'] && $author['name'] && ! $author['name'] != 'false' ) {
				$str .= sprintf( '<span class="align-float-right mobile-hide">%s</span>', $author['author'] );
			}
			else if ( $header['date'] ) {
				$str .= sprintf( '<span class="align-float-right mobile-hide">%s</span>', $post->post_date );
			}
			else if ( $header['address'] ) {
				$str .=  sprintf( '<span class="align-float-right mobile-hide">%s &middot; %s</span>', $header['phone'], $header['location'] );
			}
			else if ( $header['nav-bar'] ) {
				$str .= get_hd_nav_bar_html();
			}
			else {}
		}
		$str .= $header['inner'] ? '</div>' . PHP_EOL : '';
		$str .= '</header>' . PHP_EOL;
		return $str;
	}
	else {
		return false;
	}
}

function get_hd_nav_bar_html() {
	$str = '';
	$nav = get_hd_nav_bar_data();
	$str .= sprintf( '<div id="nav-bar" class="nav-bar">%s', PHP_EOL );
	$str .= $nav['inner'] ? '<div class="inner">' . PHP_EOL : '';
	$str .= $nav['home'] ? '<span id="home" class="icon icon-home"></span>' : '';
	$str .= $nav['info'] ? '<span  id="info" class="icon icon-info"></span>' : '';
	$str .= $nav['mail'] ? '<span id="mail" class="icon icon-mail"></span>' : '';
	$str .= $nav['handset'] ? '<span id="handset" class="icon icon-handset"></span>' : '';
	$str .= $nav['inner'] ?  '</div>' . PHP_EOL : '';
	$str .= '</div>' . PHP_EOL;
	return $str;
}

function get_hd_peripheral() {
	$str = '';
	$items = array(
			'A',  'B',  'C',  'D',
			'1',  '2',  '3',  '',  
			'4',  '5',  '6',  '',  
			'7',  '8',  '9',  '',
			'',   '',  'joe@example.ca',   '(123) 456-7890',
			);
	
	$str .= sprintf( '<div id="peripheral" class="peripheral">%s', PHP_EOL );
	$str .= '<div class="inner">' . PHP_EOL;
	/*
	$str .= sprintf( '<section id="section-1" class="unit size1of4"><div class="inner">%s</div></section>%s', $items[0], PHP_EOL );
	$str .= sprintf( '<section id="section-2" class="unit size1of4"><div class="inner">%s</div></section>%s', $items[1], PHP_EOL );
	$str .= sprintf( '<section id="section-3" class="unit size1of4"><div class="inner">%s</div></section>%s', $items[2], PHP_EOL );
	$str .= sprintf( '<section id="section-4" class="unit size1of4"><div class="inner">%s</div></section>%s', $items[3], PHP_EOL );
	*/
	$str .= sprintf( '<section id="section-1" class="goto unit size1of4"><div class="inner">%s</div></section>%s', $items[4], PHP_EOL );
	$str .= sprintf( '<section id="section-2" class="goto unit size1of4"><div class="inner">%s</div></section>%s', $items[5], PHP_EOL );
	$str .= sprintf( '<section id="section-3" class="goto unit size1of4"><div class="inner">%s</div></section>%s', $items[6], PHP_EOL );
	$str .= sprintf( '<section id="site-map" class="unit size1of4 icon icon-hierarchy" title="Site Map"><div class="inner">%s</div></section>%s', $items[7], PHP_EOL );
	
	$str .= sprintf( '<section id="section-4" class="goto unit size1of4"><div class="inner">%s</div></section>%s', $items[8], PHP_EOL );
	$str .= sprintf( '<section id="section-5" class="goto unit size1of4"><div class="inner">%s</div></section>%s', $items[9], PHP_EOL );
	$str .= sprintf( '<section id="section-6" class="goto unit size1of4"><div class="inner">%s</div></section>%s', $items[10], PHP_EOL );
	$str .= sprintf( '<section id="comment" class="unit size1of4 icon icon-comment" title="Comment"><div class="inner">%s</div></section>%s', $items[11], PHP_EOL );
	
	$str .= sprintf( '<section id="section-7" class="goto unit size1of4"><div class="inner">%s</div></section>%s', $items[12], PHP_EOL );
	$str .= sprintf( '<section id="section-8" class="goto unit size1of4"><div class="inner">%s</div></section>%s', $items[13], PHP_EOL );
	$str .= sprintf( '<section id="section-9" class="goto unit size1of4"><div class="inner">%s</div></section>%s', $items[14], PHP_EOL );
	$str .= sprintf( '<section id="share" class="unit size1of4 icon icon-share" title="Share"><div class="inner">%s</div></section>%s', $items[15], PHP_EOL );
	
	$str .= sprintf( '<section id="home" class="unit size1of4 icon icon-home" title="Home"><div class="inner">%s</div></section>%s', $items[16], PHP_EOL );
	$str .= sprintf( '<section id="show-search" class="unit size1of4 icon icon-search" title="Search"><div class="inner">%s</div></section>%s', $items[17], PHP_EOL );
	$str .= sprintf( '<section id="mail" class="unit size1of4 icon icon-mail" title="Email"><div class="inner"><span id="email" class="fixed center middle opaque display" style="display: none;">%s</span></div></section>%s', $items[18], PHP_EOL );
	$str .= sprintf( '<section id="handset" class="unit size1of4 icon icon-handset" title="Phone Number"><div class="inner"><span id="phone" class="fixed center middle opaque display" style="display: none;">%s</span></div></section>%s', $items[19], PHP_EOL );
	$str .= '</div>' . PHP_EOL;
	$str .= '</div>' . PHP_EOL;
	return $str;
}

function get_hds_html( $post ){
	$args['pages_per_load'] = 4;
	$args['sort'] = 0;
	$wp_query = new WP_Query();
	$sort = $args['sort'] ? 'ASC' : 'DESC';
	$params = array( 
		'post_type' => 'hd', 
		'sort_order' => $sort, 
		'sort_column' => 'post_title', 
		'post_status' => 'publish', 
		'posts_per_page' => $args['pages_per_load'], 
	);
	$posts = $wp_query -> query( $params  );
	$str = format_hd_pages( $posts, $args );
	return $str;
}

function format_hd_pages( $posts ){
	if ( ! empty( $posts ) ){
		$str = '';
		$cnt = 1;
		$page = get_hd_page_data();
		foreach ( array_reverse( $posts ) as $post ){
			$args = get_hd_page_args( $post );
			$visible = $cnt == 1 ? '' : ' hide';
			$cnt++;
			$str .= sprintf( '<section id="page-%s" class="page%s">%s', $post -> ID, $visible, PHP_EOL );
			$str .= '<div class="inner">' . PHP_EOL;
			$str .= $page['title'] ? sprintf( '<h4 class="title absolute top left negative-top-margin left-offset">%s</h4>', $post-> post_title ) : '';
			$str .= get_hd_html( $post );
			$str .= $page['author-bar'] && ! is_hd_video( $args['url'] ) ? get_hd_author_bar_html( $post, $author ) : '';
			$str .= $page['bottom-left'] ? get_hd_bottom_left_html( $post, $args ) : '';
			$str .= $page['bottom-right'] ? get_hd_bottom_right_html( $post, $args ) : '';
			$str .= $page['mp3'] ? get_hd_mp3( $args ) : '';
			$str .= '</div>' . PHP_EOL;
			$str .= sprintf( '<div class="page-number">%s</div>%s', $post -> ID, PHP_EOL );
			$str .= '</section>' . PHP_EOL;
		}
		return $str;
	}
}

function get_hd_html( $post ){
	global $media;
	if ( $post->post_type == 'hd' ) {
		$html = '';
		$args = get_hd_page_args( $post );
		$hd = $args['url'];
		$page = get_hd_page_data();
		$format = get_hd_format_data();
		$author = get_hd_author( $post );
		$type = get_hd_type( $post, $args );
		$html .= $page['thumbnail'] && isset( $args['poster'] ) ? $args['poster'] : '';
		switch ( $type ) {
			case 'excerpt' :
				$html .= get_hd_excerpt( $post, $args );
				return $html;
				break;				
			case 'book' :
				$html .= get_hd_book_pages();
				return $html;
				break;
			case 'youtube' :
				$args['prefix'] = $format['youtube'];
				$html .= get_hd_iframe_youtube( $args );
				return $html;
				break;
			case 'vimeo' :
				$args['prefix'] = $format['vimeo'];
				$html .= get_hd_iframe_vimeo( $args );
				return $html;
				break;
			case 'mp4' : //mp4 preference
				$args['prefix'] = $format['image'];
				$args['mp4'] = $args['prefix'] . '/' . $hd;
				$args['webm'] = $args['prefix'] . '/' . str_replace( 'mp4', 'webm', $hd );
				$args['poster'] = get_the_hd_poster_thumnail_url( $post->ID, 'hd-viewport' );
				$html .= get_hd_video_tag( $args );
				return $html;
				break;
			case 'webm' : //webm preference
				$args['prefix'] = $format['image'];
				$args['webm'] = $args['prefix'] . '/' . $hd;
				$args['mp4'] = $args['prefix'] . '/' . str_replace( 'webm', 'mp4', $hd );
				$args['poster'] = get_the_hd_poster_thumnail_url( $post->ID, 'hd-viewport' );
				$html .= get_hd_video_tag( $args );
				return $html;
				break;
			case 'author' :
				$html .= get_author_bio_html( $author );
				return $html;
				break;
			case 'search' :
				$args['prefix'] = $format['image'];
				$html .= 'search';
				return $html;
				break;
			default:
				
		}
		return $html;
	}
}

function get_hd_type( $post, $args ) {
	$page = get_hd_page_data();
	if ( isset( $args['type'] ) && $args['type'] == 'pages' ) {
		$type = 'pages';
	}
	else if ( isset( $args['type'] ) && $args['type'] == 'book' ) {
		$type = 'book';
	}
	else if ( isset( $args['layout'] ) && $args['layout'] == 'page' ) {
		$type = 'page';
	}
	else if ( isset( $args['layout'] ) && $args['layout'] == 'full' ) {
		$type = 'full';
	}
	else if ( $page['youtube'] && strstr( $args['url'], 'youtu' ) ) {
		$type = 'youtube';
	}
	else if ( $page['vimeo'] && strstr( $args['url'], 'vimeo' ) ) {
		$type = 'vimeo';
	}
	else if ( $page['mp4'] && ( strstr( $args['url'], 'mp4' ) ) ) {
		$type = 'mp4';
	}
	else if ( $page['webm'] && ( strstr( $args['url'], 'webm' ) ) ) {
		$type = 'webm';
	}
	else if ( strstr( $args['url'], 'jpg' || strstr( $args['url'], 'png' ) ) ) {
		$type = 'image';
	}
	else if ( isset( $args['excerpt'] ) && $args['excerpt'] != 'bottom-left' && $args['excerpt'] != 'bottom-right' ) {
		$type = 'excerpt';
	}
	else if ( isset( $args['poster'] ) ) {
		$type = 'image';
	}
	else {
		$type = false;
	}
	return $type;
}

function get_hd_page_args( $post ) {
	$hd = get_post_meta( $post->ID, '_hd_url', true );
	$args['url'] = esc_attr( $hd );
	$args['width'] = '1600';
	$args['height'] = '900';
	$args['poster'] = get_the_post_thumbnail( $post->ID, 'full' );
	if ( $post -> post_type == 'hd' ) {
		$parameters = get_post_meta( $post->ID, '_hd_parameters', true );
		$items = explode( ',', $parameters );
		if ( ! empty ( $items ) ) {
			foreach ( $items as $item ) {
				$ex = explode( '=', $item );
				if ( isset( $ex[0] ) && $ex[0] && isset( $ex[1] ) && $ex[1] ) {
					$args[ $ex[0] ] = $ex[1];
				}
			}
			if ( ! empty ( $args ) ) {
				return $args;
			}
			else {
				return false;
			}
		}
	}
	else {
		return false;
	}
}

function get_hd_excerpt( $post, $args ) {
	$str = '<div id="excerpt" class="text">' . PHP_EOL;
	$str .= '<div class="inner">' . PHP_EOL;
	$str .= $post->post_excerpt;
	$str .= '</div>' . PHP_EOL;
	$str .= '</div>' . PHP_EOL;
	return $str;
}

function get_hd_book_pages(){
	$wp_query = new WP_Query();
	$pages = $wp_query -> query( array( 'post_type' => 'page', 'sort_order' => 'ASC', 'sort_column' => 'post_title', 'post_status' => 'publish' ) );
	$book =  get_page_by_title('Book');
	$book_pages = get_page_children( $book -> ID, $pages );
 	$str = format_hd_book_pages( $book_pages );
 	return $str;	
}

function format_hd_book_pages( $items ){
	if ( ! empty( $items ) ){
		$str = '<div id="book">' . PHP_EOL;
		$cnt = 1;
		foreach ( array_reverse( $items ) as $item ){
			$visible = $cnt == 1 ? '' : 'hide';
			$cnt++;
			$str .= sprintf( '<section id="page-%s" class="book-page text %s">%s', $item->ID, $visible, PHP_EOL );
			$str .= '<div class="inner">' . PHP_EOL;
			$str .=  apply_filters( 'the_content', $item -> post_content );
			$str .= '</div>' . PHP_EOL;
			$str .= sprintf( '<div class="page-number">%s</div>%s', $item->ID, PHP_EOL );
			$str .= '</section>' . PHP_EOL;
		}
		$str .= '<section id="page-turner"><div id="page-left" class="mid-circle">&laquo;</div><div id="page-right" class="mid-circle">&raquo;</div></section>';
		$str .= '</div>' . PHP_EOL;
		return $str;
	}
}

function format_hd_book_page( $str ){
	$html = wordwrap ( '<section class="hidden"><div class="inner">' . $str, 1180, $break = '</div></section><section class="hidden"><div class="inner">' );
	return $html;
}

function get_the_hd_poster_thumnail_url( $post_id, $size ) {
	$src = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), $size, false, '' );
	if ( ! empty( $src[0] ) ) {
		return $src[0];
	}
	else {
		return false;
	}
}

function get_hd_image( $post, $hd ){
	$image = ( get_the_post_thumbnail( $post->ID, 'hd-viewport' ) );
	if ( ! empty ( $image ) && ! is_hd_video( $hd ) && $post->post_type == 'hd' )  {
		return $image;
	}
	else {
		return false;
	}
}

function get_hd_mp3( $args ){
	if ( strstr( $args['url'], 'mp3' ) ){
		$str = get_hd_audio_tag( $args );
		return $str;
	}
	else {
		return false;
	}
}

function get_hd_iframe_youtube( $args ){
	$str = sprintf( '<iframe width="%s" height="%s"', $args['width'], $args['height'] );
	$str .= sprintf( 'source src="%s/%s" ', $args['prefix'], get_hd_suffix( $args ) );
	$str .= 'frameborder="0" allowfullscreen ';
	$str .= '></iframe>' . PHP_EOL;
	return $str;
}

function get_hd_iframe_vimeo( $args ){
	$str = sprintf( '<iframe src="%s/%s" ', $args['prefix'], get_hd_suffix( $args ) );
	$str .= sprintf( 'width="%s" height="%s" ', $args['width'], $args['height'] );
	$str .= 'frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>' . PHP_EOL;
	return $str;
}

function get_hd_video_tag( $args ){
	$str = sprintf( '<video width="%s" height="%s" ', $args['width'], $args['height'] );
	$str .= 'controls="" ';
	$str .= sprintf( 'poster="%s" >%s', $args['poster'], PHP_EOL );
	$str .= sprintf( '<source src="%s" type="video/mp4" />%s', $args['mp4'], PHP_EOL );
	$str .= sprintf( '<source src="%s" type="video/webm" />%s', $args['webm'], PHP_EOL );
	$str .= 'Your browser does not support the video tag.' . PHP_EOL;
	$str .= '</video>' . PHP_EOL;
	return $str;
}

function get_hd_audio_tag( $args ){
	$str = sprintf( '<audio id="audio" controls src="%s">%s', hd_url( $args['url'] ) , PHP_EOL );
	$str .= '</audio>' . PHP_EOL;
	return $str;
}

function get_hd_url( $url ) {
	if ( ! strstr( $url, 'http' ) ) {
		$dir = wp_upload_dir();
		$url = $dir['baseurl'] . '/' . $url;
		return $url;
	}
	else {
		return $url;
	}
}

function get_hd_frame_image( $args ){
	$str = sprintf( '<img src="%s/%s" ', $args['prefix'], get_hd_suffix( $args ) );
	$str .= sprintf( 'width="%s" height="%s" ', $args['width'], $args['height'] );
	$str .= '/>' . PHP_EOL;
	return $str;
}

function get_hd_suffix( $args ) {
	$ex = explode( '/', $args['url'] );
	if (  ! empty( $ex ) ) {
		$url_id = $ex[ count( $ex ) - 1 ];
		return $url_id;
	}
	else {
		return false;
	}
}

function get_hd_link( $prefix='', $args ) {
	$ex = explode( '/', $args['url'] );
	if (  ! empty( $ex ) ) {
		$url_id = $ex[ count( $ex ) - 1 ];
		$link = sprintf( '%s%s?rel=0', $prefix, $url_id );
	}
	else {
		$link = '';
	}
	return $link;
}

function get_hd_video( $args ){
	$str = sprintf( '<video width="%s" height="%s"', $args['width'], $args['height'] );
	$str .= sprintf( 'poster="%s" controls="">', $args['poster'] );
	$str .= sprintf( '<source src="%s" type="video/mp4">', $args['url'], PHP_EOL );
	$str .= 'Your browser does not support the video tag.' . PHP_EOL;
	$str .= '</video>';
	return $str;
}

function get_hd_format_two(){
	$str = sprintf( '<iframe width="%s" height="%s"', $args['width'], $args['height'] );
	$str .= sprintf( 'source src="%s" ', get_hd_link( 'https://www.youtube.com/embed/', $args ) );
	$str .= 'frameborder="0" allowfullscreen' . PHP_EOL;
	$str .= '></iframe>';
	return $str;

}

function get_hd_nav_html(){
	$links = get_hd_nav_links();
	$str = '';
	$str .= ! empty( $links['prev']['url'] ) ? sprintf( '<nav class="left-middle"><a href="%s" title="Prev"><span class="align-float-left mid-circle hover-opaque">&laquo;</span></a></nav>%s', $links['prev']['url'], PHP_EOL ) : '';
	$str .= ! empty( $links['next']['url'] ) ? sprintf( '<nav class="right-middle"><a href="%s" title="Next"><span class="align-float-right mid-circle hover-opaque">&raquo;</span></a></nav>%s', $links['next']['url'], PHP_EOL ) : '';
	return $str;
}

function get_hd_controls_html(){
	$controls = get_hd_controls_data();
	$str = '';
	$str .= '<div id="hd-controls">' . PHP_EOL;
	$str .= $controls['inner'] ? '<div class="inner">' : '';
	$str .= $controls['page-left'] ? '<span id="prev-page" class="icon icon-collapse rotate-270" title="Prev"></span>' . PHP_EOL : '';
	$str .= $controls['header-up'] ? '<span id="hide-header" class="icon icon-collapse" title="Partial Full Screen"></span>' . PHP_EOL : '';
	$str .= $controls['default-screen'] ? '<span id="default-screen" class="icon icon-expand" title="Default View"></span>' . PHP_EOL : '';
	$str .= $controls['full-screen'] ? '<span id="full-screen" class="icon icon-fullscreen"></span>' . PHP_EOL : '';
	$str .= $controls['page-right'] ? '<span id="next-page" class="icon icon-collapse rotate-90" title="Next"></span>' . PHP_EOL : '';
	$str .= $controls['inner'] ? '</div>' : '';
	$str .= '</div>';
	return $str;
	//rotate-90
}

function get_hd_author_bar_html( $post, $author ){
	$bar = get_hd_author_bar_data();
	if ( $author && ( $author['credentials'] || $author['university'] ) ) {
		$str = '<section id="author-bar">' . PHP_EOL;
		$str .= $bar['inner'] ? '<div class="inner">' : '';
		$str .= $bar['image'] && $author['image'] ? $author['image'] : sprintf( '<img src="%s" alt="author" />', get_hd_url( $thumb ), PHP_EOL );
		$str .= '<section class="author">' . PHP_EOL;
		$str .= '<div class="inner">' . PHP_EOL;
		$str .= $bar['name'] && $author['name'] ? sprintf( '<h1 class="name">%s</h1>', $author['name'], PHP_EOL ) : '';
		$str .= $bar['credentials'] && $author['credentials'] ? sprintf( '<span class="credentials">%s</span>', $author['credentials'], PHP_EOL ) : '';
		$str .= $bar['title'] && $author['title'] ? sprintf( '<span class="title">%s</span>', $author['title'], PHP_EOL ) : '';
		$str .= $bar['university'] && $author['university'] ? sprintf( '<span class="university">%s</span>', $author['university'], PHP_EOL ) : '';
		$str .= $bar['university_url'] && $author['university_url'] ? sprintf( '<a href="%s" title="%s"><span class="university">%s</span></a>', $author['university_url'], $author['university'], PHP_EOL ) : '';
		$str .= '</div>' . PHP_EOL;
		$str .= '</section>' . PHP_EOL;
		$str .= $bar['inner'] ? '</div>' : '';
		$str .= '</section>';
		return $str;
	}
	else {
		false;
	}
}

function get_hd_author( $post ) {
	if ( ! empty ( $post ) ) {
		$name = get_post_meta( $post->ID, '_hd_author', true );
		if ( ! empty ( $name ) ) {
			$page = get_page_by_title( $name, OBJECT, 'author' );
			if ( ! empty( $page ) ) {
				$found = true;
			}
			else {
				$page = get_page_by_path( $name, OBJECT, 'author' );
				if ( ! empty( $page ) ) {
					$found = true;
				}
				else {
					$found = false;
				}
			}
			$author['name'] = $found ? $page->post_title : $name;
			$author['link'] = $found ? sprintf( '<a href="%s" target="_blank">%s</a>', get_the_permalink( $page->ID ),  $page->post_title ) : '';
			$author['url'] = $found ? get_post_meta( $page->ID, '_author_url', true ) : '';
			$author['image'] = $found ? get_the_post_thumbnail( $page->ID, 'thumbnail' ) : '';
			$author['title'] = $found ? get_post_meta( $page->ID, '_author_title', true ) : '';
			$author['university'] = $found ? get_post_meta( $page->ID, '_author_university', true ) : '';
			$author['university_url'] = $found ? get_post_meta( $page->ID, '_author_university_url', true ) : '';
			$author['credentials'] = $found ? get_post_meta( $page->ID, '_author_credentials', true ) : '';
			return $author;
		}
	}
	else {
		return false;
	}
}

function get_hd_author_data( $post ) {
	$items = get_hd_metabox_items();
	if ( ! empty ( $items ) ) {
		foreach ( $items as $item ) {
			if ( strstr( $item['name'], 'author' ) ) {
				$author[ $item['name'] ] = $hd_credit_url = get_post_meta( $post->ID, '_' . $item['name'], true );
			}
		}
		return $author;
	}
	else {
		return false;
	}
}

function get_hd_admin_name() {
	$email = get_hd_admin_email();
	$user = get_user_by( 'email', $email );
	if ( ! empty ( $user->display_name ) ){
		return $user->display_name;
	}
	else {
		return false;
	}
}

function get_hd_admin_email() {
	$admin = get_user_by( 'login', 'admin' );
	if ( ! empty ( $admin->user_email ) ){
		return $admin->user_email;
	}
	else {
		return get_option( 'admin_email' );
	}
}

function get_hd_bottom_right_html( $post, $args ){
	$right = get_hd_bottom_right_data();
	$hd_url = get_post_meta( $post->ID, '_hd_url', true );
	if ( $right['right'] && isset( $args['excerpt'] ) && $args['excerpt'] == 'bottom-right' ) {
		$class = ' class="hd-excerpt"';
		$str = sprintf( '<aside id="bottom-right"%s>%s', $class, PHP_EOL );
		$str .= $right['inner'] ? '<div class="inner">' . PHP_EOL : '';
		$str .= $right['text'] ? $post->post_excerpt : '';
		$str .= $right['inner'] ? '</div>' . PHP_EOL : '';
		$str .= '</aside>' . PHP_EOL;
		return $str;
	}
	else {
		return false;
	}
}

function get_hd_bottom_left_html( $post, $args ){
	$left = get_hd_bottom_left_data();
	$hd_url = get_post_meta( $post->ID, '_hd_url', true );
	if ( $left['left'] && isset( $args['excerpt'] ) && $args['excerpt'] == 'bottom-left' ) {
		$class = ' class="hd-excerpt"';
		$str = sprintf( '<aside id="bottom-left"%s>%s', $class, PHP_EOL );
		$str .= $left['inner'] ? '<div class="inner">' . PHP_EOL : '';
		$str .= $left['text'] ? $post->post_excerpt : '';
		$str .= $left['inner'] ? '</div>' . PHP_EOL : '';
		$str .= '</aside>' . PHP_EOL;
		return $str;
	}
	else {
		return false;
	}
}

function get_coffee_button_html(){
	$str = '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank" style="border: none; margin: 0; padding: 0;">' . PHP_EOL;
	$str .= '<input type="hidden" name="cmd" value="_s-xclick">' . PHP_EOL;
	$str .= '<input type="hidden" name="hosted_button_id" value="5VTWPTSMPG5PA">' . PHP_EOL;
	$str .= '<input type="image" src="https://www.cbos.ca/images/coffee-dark.png" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!" style="border: none; padding: 0;">' . PHP_EOL;
	$str .= '<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1" style="border: none; margin: 0; padding: 0; display: none;">' . PHP_EOL;
	$str .= '</form>' . PHP_EOL;
	return $str;
}

function get_hd_footer_html( $post ){
	$footer = get_hd_footer_data();
	$items = get_hd_footer_items();
	$hd_url = get_post_meta( $post->ID, '_hd_url', true );
	$hd_license_url = get_post_meta( $post->ID, '_hd_license_url', true );
	$hd_license_text = get_post_meta( $post->ID, '_hd_license_text', true );
	$hd_reference = get_post_meta( $post->ID, '_hd_reference_url', true );

	$left = '';
	if ( $footer['left'] ) {
		$left .= '<span class="align-float-left mobile-hide tablet-hide">';
		if ( $footer['reference'] && ! empty ( $hd_reference ) && $hd_reference != 'false' ) {
			$left .=  sprintf( '<a href="%s" class="align-float-left" target="_blank">%s</a>', esc_attr( $hd_reference ), 'Reference' );
		}
		if ( $footer['reference'] && empty ( $hd_reference ) ) {
			$left .=  sprintf( '<a href="%s" class="align-float-left" target="_blank">%s</a>', esc_attr( $hd_url ), 'Reference' );
		}
		else if ( $footer['license_url'] && $hd_license_url != 'false' && ! empty ( $hd_license_url ) ) {
			$hd_license_text = ! empty( $hd_license_text ) ? $hd_license_text : 'License';
			$left .= sprintf( '<a href="%s" class="align-float-left mobile-hide tablet-hide" target="_blank">%s</a>', esc_attr( $hd_license_url ), $hd_license_text );
		}
		else if ( $footer['license_text'] && $hd_license_text != 'false' && ! empty ( $hd_license_text )) {
			$hd_license_text = ! empty( $hd_license_text ) ? $hd_license_text : 'License';
			$left .= esc_attr( $hd_license_text );
		}
		else if ( $footer['date'] ) {
			$left .= $post->post_date;
		}
		else if ( $footer['email'] ) {
			$left .= $items['email'];
		}
		$left .= '</span>';
	}

	$center = '';
	if ( $footer['middle'] ) {
		$center = sprintf( '<span class="align-absolute-center">%s</span>', $items['link'] );
	}

	$right = '';
	if ( $footer['right'] ) {
		$right = '<span class="align-float-right mobile-hide">';
		if ( current_user_can( 'manage_options' ) ) {
			$right .= sprintf( '<a href="%s" target="_blank">Edit</a> &nbsp;', admin_url( 'edit.php?post_type=hd' ) );
		}
		if ( $footer['author'] && $author['name'] != 'false' && ! empty ( $author['name'] ) ) {
			$right .= ! isset( $author['link'] ) ? $author['name'] : '';
			$right .=  isset( $author['link'] ) ? $author['link'] : '';
		}
		else if ( $footer['promo'] ) {
			$right .= sprintf( '%s', $items['promo'] );
		}
		$right .= '</span>';
	}

	//build footer
	$str = '<footer>' . PHP_EOL;
	$str .= $footer['inner'] ? '<div class="inner">' : '';
	$str .= $footer['left'] ? $left : '';
	$str .= $footer['middle'] ? $center : '';
	$str .= $footer['right'] ? $right : '';
	$str .= $footer['inner'] ? '</div>' . PHP_EOL : '';
	$str .= '</footer>' . PHP_EOL;
	return $str;
}

function get_hd_slide( $post ){
	$text = substr( $post->post_excerpt, 0, 250 );
	if ( ! empty( $text ) ) {
		$str = '<section id="slide">' . PHP_EOL;
		$str .= sprintf( '<div>%s</div>', $text );
		$str .= '</section>' . PHP_EOL;
		return $str;
	}
	else {
		return false;
	}
}
