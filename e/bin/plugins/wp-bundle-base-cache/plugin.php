<?php
/**
Plugin Name: WP Bundle Base Cache
Plugin URI: http://wp.cbos.ca/plugins/wp-bundle-base-cache/
Description: Caches the contents of a page or posts on save or update to a text file called article.html. This can then be used elsewhere.
Author: wp.cbos.ca
Author URI: http://wp.cbos.ca
Version: 2018.08.04
License: GPLv2+
*/

/**
 * Notes: We want to save in the directory that is identical to the slug of the url.
 * Thus, if the url is /category/airplanes/wwii/, the path will be identical.
 * This may mean that the file that *this* plugin (and core) is in will not be used.
 * Rather, the file will be saved *outside* of this directory and be as a peer to it
 * (or, really, anywhere at all to which the installation has access).
 * We may also need to *exlude* some directories and decide whether or not we want
 * to overwrite if a file already exists in that location. This may be important.
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined ( 'SITE_PATH' ) ) {
	exit( 'SITE_PATH not defined' );
}
if ( ! defined ( 'SITE_COMMONS_PATH' ) ) {
	exit( 'SITE_COMMONS_PATH not defined' );
}
if ( ! defined ('SITE_CACHE_DIR' ) ) {
	/** We likely DON'T want to use this... Default: /page */
	define( 'SITE_CACHE_DIR', '/1' );
}
if ( ! defined ('SITE_CACHE_PATH' ) ) {
	define( 'SITE_CACHE_PATH', $_SERVER['DOCUMENT_ROOT'] . SITE_CACHE_DIR );
}
if ( ! defined ('SITE_INDEX_FILE' ) ) {
	define( 'SITE_INDEX_FILE', 'index.html' );
}
if ( ! defined ('SITE_ARTICLE_FILE' ) ) {
	define( 'SITE_ARTICLE_FILE', 'article.html' );
}

add_action( 'save_post', 'wp_bundle_cache_save_post' );
add_action( 'wp_update_nav_menu', 'wp_bundle_cache_save_menu' );
add_action( 'trash_post', 'wp_bundle_cache_trash_post' );

function wp_bundle_cache_save_post( $post_id ) {
	//and not trash
	if ( ! wp_is_post_revision( $post_id ) && ! wp_is_post_autosave( $post_id ) ) {
		//get the whole post object, then send that.
		$post = get_post( $post_id );
		wp_bundle_cache_file( $post );
	}
}

function wp_bundle_cache_file( $post ){
	$overwrite = false;
	$link = get_permalink( $post -> ID );
	if ( defined( 'SITE_COMMONS_PATH' ) ) {
		$slug = get_post_field( 'post_name', $post -> ID );
		if ( $path = wp_bundle_get_cache_path( $post -> ID, $slug ) ) {
			$file = $path . '/' . SITE_ARTICLE_FILE;
			if ( $result = wp_bundle_refresh_cache_path( $path, $file ) ){
				if ( file_exists( $file ) ) {
					$file = str_replace( '.html', '-2.html', $file );
						wp_bundle_put_cache( $path, $str );
				} else {
					if ( $str = wp_bundle_get_page_internal( $post, $link ) ) {
						wp_bundle_put_cache( $path, $str );
						wp_bundle_copy_front_page( $file, $slug );
					}
				}
			}
		}
	}
}

function wp_bundle_get_cache_path( $post_id, $slug ){
	require_once( __DIR__ . '/data.php' );
	$items = get_wp_bundle_cache_data();
	if ( in_array( $slug, $items['root-pages'] ) ){
		$cache_path = '';
	}
	else {
		$post_type = get_post_field( 'post_type', $post_id );
		$cache_path = '/' . $post_type;
	}
	$path = SITE_PATH . $cache_path . '/' . $slug;
	return $path;
}

function wp_bundle_refresh_cache_path( $path, $file ){
	if( $mkdir = wp_mkdir_p( $path ) ) {
		return true;
	}
	else {
		return false;
	}

}

function wp_bundle_get_page_internal( $post, $link ){
	if ( function_exists( 'get_html5_page' ) ){
		$str = get_html5_page( $post );
		$sub = substr( $str, 250, 20 );
		if ( ! empty ( $str ) ) {
			return $str;
		}
		else {
			return false;
		}
	}
}

function wp_bundle_get_page_url( $link ){
	$str = wp_bundle_get_contents_curl( $link );
	$sub = substr( $str, 250, 20 );
	sleep( .1 );
	if ( ! empty ( $str ) ) {
		return $str;
	}
	else {
		return false;
		//do_action( 'wp_log_error', 'str-error', $link );
	}
}

function wp_bundle_put_cache( $file, $str ){
	if ( $result = file_put_contents( $file, $str ) ) {
		//do_action( 'wp_log_info', 'file', $file );
		return true;
	}
	else {
		//do_action( 'wp_log_error', 'put', 'result: ' . $result . ' file: ' . $file );
		return false;
	}
}

function wp_bundle_copy_front_page( $file, $slug ){
	if ( $slug == 'front-page' || $slug == 'home' ) {
		$dest = SITE_PATH . '/' . SITE_ARTICLE_FILE;
		if ( $result = copy( $file, $dest ) ) {
			//do_action( 'wp_log_info', 'front-page:115', $file );
			return true;
		}
		else {
			//do_action( 'wp_log_error', 'copy:118', $result );
			return false;
		}
	}
}

function wp_bundle_cache_page_exists( $link ) {
	$headers = @get_headers( $link );
	if ( strpos( $file_headers[0], '404' ) !== FALSE ) {
		return false;
	} else {
		return true;
	}
}

function wp_bundle_cache_save_menu( $nav_id ) {
	$arr = array (
		'post_type' => 'page',
		'post_status' => 'publish',
		'numberposts' => -1,
	);
	$posts = get_posts( $arr );
	if ( ! empty ( $posts ) ) {
		foreach ( $posts as $post ) {
			wp_bundle_cache_file( $post );
			sleep( .1 );
		}
	}
}

/* Delete Cached Pages and Posts */

function wp_bundle_cache_trash_post( $post_id ) {
	$post = get_post( $post_id );
	$slug = get_post_field( 'post_name', $post -> ID );
	if ( $path = wp_bundle_get_cache_path( $post -> ID, $slug ) ) {
		$file = $path . '/' . SITE_ARTICLE_FILE;
		if ( $result = wp_bundle_refresh_cache_path( $path, $file ) ){
			if ( $str = wp_bundle_get_page_internal( $post, $link ) ) {
				unlink( $file );
				unlink ( $path );
			}
		}
	}
}
