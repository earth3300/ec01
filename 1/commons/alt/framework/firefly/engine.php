<?php

defined( 'FIREFLY' ) || exit;

if ( file_exists( __DIR__ . '/log.php' ) ) {
	require_once( __DIR__ . '/log.php' );
}
require_once( __DIR__ . '/authorize.php' );
require_once( __DIR__ . '/template.php' );

function get_firefly_page () {
	$page = get_firefly_page_uri();
	$page['slug'] = get_firefly_page_slug( $page );
	$page['header'] = get_firefly_header( $page );
	$page['article']= get_firefly_article( $page );
	$page['article-title'] = get_firefly_article_title( $page['article'] );
	$page = get_firefly_html_class( $page );
	$page['header-sub'] = get_firefly_header_sub( $page );
	$page['page-title'] = get_firefly_page_title( $page );
	$page['sidebar']= SITE_USE_SIDEBAR ? get_firefly_sidebar() : '';
	$page['footer']= get_firefly_footer();
	return $page;
}

/**
 * Get the filtered URI, ensuring it is safe, without the query string.
 *
 * Available to us: REQUEST_URI, QUERY_STRING and parse_url();
 *
 * @return boolean|string
 */
function get_firefly_page_uri(){
	$uri = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
	$uri = substr( $uri, 0, 65 );
	if ( empty ( $uri ) || $uri == '/' ){
		$page['front-page'] = true;
		$page['uri'] = '';
	}
	else {
		$page['front-page'] = false;
		$page['uri'] = $uri;
	}
	return $page;
}

function get_firefly_page_slug( $page ){
	$slug = rtrim( $page['uri'], '/' );
	return $slug;
}

function get_firefly_header( $page ){
	$str = 'Header N/A';
	$file = SITE_HEADER_PATH . SITE_HEADER_DIR . SITE_HTML_EXT;
	if ( file_exists( $file ) ){
		//opening header tag in header.html file.
		$str = file_get_contents( $file );
		//$str .= get_firefly_menu();
		//$str .= '</header>' . PHP_EOL;
		return $str;
	} else {
		return $str;
	}
}
/**
 * Builds the sub header
 * @param array $page
 * @return array
 */
function get_firefly_header_sub( $page ){
	if ( strpos( $page['html-class'], 'cluster' ) !== FALSE ) {
		$str = '<header class="site-header-sub">' . PHP_EOL;
		$str .= sprintf( '<div class="%s">%s', $page['cluster-sub'], PHP_EOL );
		$str .= sprintf( '<div class="color lighter">%s', PHP_EOL );
		$str .= sprintf( '<div class="%s">%s', $page['cluster'], PHP_EOL );
		$str .= sprintf( '<a class="level-01 %s color darker" href="%s/%s%s/"><span class="icon"></span>%s</a>', $page['cluster'], SITE_CLUSTER_URL, $page['cluster'], SITE_CENTER_DIR, ucfirst( $page['cluster'] ) );
		$str .= sprintf( '<span class="level-02 %s"><span class="color lighter"><span class="icon"></span>%s</span></span>%s', $page['cluster-sub'], ucfirst( $page['cluster-sub'] ), PHP_EOL );
		$str .= '</div><!-- .cluster -->' . PHP_EOL;
		$str .= '</div><!-- .inner -->' . PHP_EOL;
		$str .= '</div><!-- .cluster-sub-name -->' . PHP_EOL;
		$str .= '</header>' . PHP_EOL;
		return $str;
	} else {
		return false;
	}
}

function get_firefly_article( $page ){
	$str = '<article>Article N/A.</article>';
	$file = get_firefly_article_directory( $page );
	if ( file_exists( $file ) ){
		$str = file_get_contents( $file );
		return $str;
	} else {
		return $str;
	}
}

function get_firefly_article_directory( $page ){
	if ( $page['front-page'] ) {
		$file = SITE_HTML_PATH  . SITE_ARTICLE_STUB. SITE_HTML_EXT;
	}
	else {
		$file = SITE_HTML_PATH . rtrim( $page['slug'], '/' ) . SITE_ARTICLE_STUB. SITE_HTML_EXT;
	}
	return $file;
}

function get_firefly_article_title( $article ){
	$check = substr( $article, 0, 150 );
	$pattern = "/>(.*?)<\/h1>/";
	preg_match( $pattern, $check, $matches );
	if ( ! empty ( $matches[1] ) ){
		return ( $matches[1] );
	}
	else {
		return false;
	}
}

/**
 * Get the class to use in the html element
 * @param array
 * @return str
 */
function get_firefly_html_class( $page ){
	if ( SITE_FIXED_WIDTH && $page['front-page'] ) {
		$arr[] = 'fixed-width';
	} else {
		$arr[] = 'dynamic';
	}
	if ( $class = analyze_uri_for_cluster( $page['uri'] ) ) {
		$arr[] = 'cluster';
		$page['cluster'] = $class;
		$arr[] = $class;
	}
	if ( $class = analyze_uri_for_sub_cluster( $page['uri'] ) ) {
		$page['cluster-sub'] = $class;
	}
	if ( $class = get_firefly_article_class( $page['article'] ) ) {
		$arr[] = $class;
	}
	if ( ! empty( $arr ) ){
		$page['html-class'] = implode( ' ', $arr );
	}
	return $page;
}

/**
 * Get the class from the article element
 * @param array
 * @return str
 */
function get_firefly_article_class( $article ){
	$check = substr( $article, 0, 150 );
	$pattern = "/<article class=\"(.*?)\"/";
	preg_match( $pattern, $check, $matches );
	if ( ! empty ( $matches[1] ) ){
		return ( $matches[1] );
	}
	else {
		return false;
	}
}

function get_firefly_page_title( $page ){
	$str = "Site Title N/A";
	if ( defined( 'SITE_TITLE' ) ) {
		if( $page['front-page'] ) {
			$str = sprintf( '%s%s%s', SITE_TITLE, ' | ', SITE_DESCRIPTION );
			return $str;
		}
		else if ( ! empty ( $page['article-title'] ) ){
			$str = sprintf( '%s%s%s', $page['article-title'], ' | ', SITE_TITLE );
			return $str;
		}
		else {
			return SITE_TITLE;
		}
	}
	else {
		return $str;
	}
}

function get_firefly_menu() {
	$str = 'Menu N/A';
	$file = SITE_MENU_PATH . SITE_MENU_DIR . SITE_HTML_EXT;
	if ( file_exists( $file ) ){
		$str = file_get_contents( $file );
		return $str;
	} else {
		return $str;
	}
}

function get_firefly_sidebar( $page, $items ){
	$str = 'Sidebar N/A';
	$file = SITE_SIDEBAR_PATH . SITE_SIDEBAR_DIR . SITE_HTML_EXT;
	if ( file_exists( $file ) ){
		$str = file_get_contents( $file );
		return $str;
	} else {
		return $str;
	}
}

function get_firefly_footer(){
	$str = 'Footer N/A';
	$file = SITE_FOOTER_PATH . SITE_FOOTER_DIR . SITE_HTML_EXT;
	if ( file_exists( $file ) ){
		$str = file_get_contents( $file );
		return $str;
	} else {
		return $str;
	}
}
