<?php

defined( 'SITE' ) || exit;

require_once( __DIR__ . '/data.php' );
require_once( __DIR__ . '/authorize.php' );
require_once( __DIR__ . '/template.php' );

/**
 * Get the page
 * @return array
 */
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
 * Available: REQUEST_URI, QUERY_STRING and parse_url();
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

/**
 * Get the page slug
 * @param array $page
 * @return string
 */
function get_firefly_page_slug( $page ){
	$slug = rtrim( $page['uri'], '/' );
	return $slug;
}

/**
 * Get the header
 * @param array $page
 * @return str
 */
function get_firefly_header( $page ){

	$str = 'Header N/A';
	$file = SITE_HEADER_PATH . SITE_HEADER_DIR . SITE_HTML_EXT;
	if ( file_exists( $file ) ){
		$str = file_get_contents( $file );
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
	if ( isset( $page['html-class'] ) && strpos( $page['html-class'], 'cluster' ) !== FALSE ) {
		$str = '<header class="site-header-sub">' . PHP_EOL;
		$str .= sprintf( '<div class="%s">%s', $page['cluster-sub'], PHP_EOL );
		$str .= sprintf( '<div class="color lighter">%s', PHP_EOL );
		$str .= sprintf( '<div class="%s">%s', $page['cluster'], PHP_EOL );
		$str .= sprintf( '<a class="level-01 %s color darker" href="%s/%s%s/"><span class="icon"></span>%s</a>', $page['cluster'], '/whr', $page['clust']['four'], SITE_CENTER_DIR, ucfirst( $page['cluster'] ) );
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

/**
 * Get the article
 * @param array $page
 * @return string
 */
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

/**
 * Get the article directory
 * @param array $page
 * @return string
 */
function get_firefly_article_directory( $page ){
	if ( $page['front-page'] ) {
		$file = SITE_PATH . SITE_ARTICLE_FILE;
	}
	else {
		$file = SITE_HTML_PATH . rtrim( $page['slug'], '/' ) . SITE_ARTICLE_FILE;
	}
	return $file;
}

/**
 * Get the article title
 * @param array $page
 * @return string
 */
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
	if ( SITE_IS_FIXED_WIDTH && $page['front-page'] ) {
		$arr[] = 'fixed-width';
	} else {
		$arr[] = 'dynamic';
	}
	$arr = get_uri_parts( $page['uri'] );
	$page['clust'] = $arr;

	if ( $class = analyze_uri_for_cluster( $arr ) ) {
		$arr[] = 'cluster';
		$page['cluster'] = $class;
		$arr[] = $class;
	}
	if ( $class = analyze_uri_for_sub_cluster( $arr ) ) {
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

/**
 * Get the page and site title
 * @param array $page
 * @return string
 */
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

/**
 * Get the menu
 * @param array $page
 * @return string
 */
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

/**
 * Get the sidebar
 * @param array $page
 * @return string
 */
function get_firefly_sidebar( $page ){
	$str = 'Sidebar N/A';
	$file = SITE_SIDEBAR_PATH . SITE_SIDEBAR_DIR . SITE_HTML_EXT;
	if ( file_exists( $file ) ){
		$str = file_get_contents( $file );
		return $str;
	} else {
		return $str;
	}
}

/**
 * Get the footer
 * @return string
 */
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

/**
 * Firefly sanitize HTML
 *
 * Remove everything but valid HTML
 * @todo Needs some work
 */
function firefly_sanitize_html( $str = '' ){
	if ( ! empty( $str ) ) {
		$allowed = '<section><article><header><div><img><a><p><h1><h2><h3><h4><h5><h6><ol><li>';
		$stripped = strip_tags( $str, $allowed );
		return $stripped;
	} else {
		return false;
	}
}

/**
 * Analyze the URI
 *
 * Use this to add an html class based on an authorized cluster name.
 * That is, we do not want this to be *too* flexible, we want
 * to treat it as a concrete structure would be treated. We *can* move
 * it, but not too frequently. Thus we need to think about it carefully,
 * authorize it, and *then* use it, only if it matches.
 *
 * 1. Get the clusters.
 * 2. Check the uri and get the word directly
 * after the word 'cluster' (this can be changed).
 * 3. Check to see if that word is in the authorized
 * list of clusters. If it is, return it.
 * It can then be used as, for example, an html class.
 * The idea is to give earch cluster a unique color so that this
 * can be used to quickly identify the section one is in. These are largely chosen already.
 */

function analyze_uri_for_cluster( $arr ){
	$items = get_tier_three_data();
	if ( ! empty( $arr['four'] ) ) {
		return $items[ $arr['four'] ]['name'];
	} else {
		return false;
	}
}

function analyze_uri_for_sub_cluster( $arr ){
	$items = get_tier_four_data();
	if ( ! empty( $arr['five'] ) ) {
		return $items[ $arr['five'] ]['name'];
	} else {
		return false;
	}
}

/**
 * This searches for the term only in the first two locations
 * in the uri. This is where we expect it. If it is too far
 * from this, we may not be that interested, as something is
 * wrong with the directory structure then. We want to keep it
 * simple and compact.
 * This finds the position of the word 'cluster' and then returns
 * the word directly after it, whatever it is (if present).
 *
 * @example /whr/acad/
 * @example /wha/bldg/
 */
function get_uri_parts( $uri ){

	/** Look for a grouping of three letters, followed by four. */
	$regex = '/\/([a-z]{3})\/([a-z]{4})\/([a-z]{5})\//';
	preg_match( $regex, $uri, $match );

	if ( ! empty( $match ) ){
		$arr['three'] = ! empty( $match[1] ) ? $match[1] : null;
		$arr['four'] = ! empty( $match[2] ) ? $match[2] : null;
		$arr['five'] = ! empty( $match[3] ) ? $match[3] : null;
		return $arr;
	}
	else {
		return false;
	}
}

