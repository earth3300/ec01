<?php

defined( 'SITE' ) || exit;

/***** SELF AWARE *****/

/** Who and Where (Important) */

define( 'SITE_CLUSTER_DIR', '/cluster' );

define( 'SITE_CENTER_DIR', '/center' );

/***** ROOT DIRECTORY *****/

/** Replaces {wp-content} */
define( 'SITE_BIN_DIR', '/bin' );

/** Status code directory. */
define( 'SITE_CODE_DIR', '/code' );

/** Cron directory */
define( 'SITE_CRON_DIR', '/cron' );

/** For csv, xml and other open sourced data files. */
define( 'SITE_DATA_DIR', '/data' );

/** The directory where static HTML goes. Empty if for root. */
define( 'SITE_HTML_DIR', '' );

/** The directory where static HTML goes. */
define( 'SITE_THEME_HTML_DIR', '/html' );

/** The directory root pages go. */
define( 'SITE_ROOT_PAGE_DIR', '/root' );

/** Intended for static HTML content. */
define( 'SITE_PAGE_DIR', '/page' );

/** Intended for more informal updates. */
define( 'SITE_POST_DIR', '/post' );

/** Used for traditional cached files. */
define( 'SITE_CACHE_DIR', '/html' );

/** For media: image/, pdf/, video/, audio/, etc.  */
define( 'SITE_MEDIA_DIR', '/media' );

/** For javascript. From root. */
define( 'SITE_SCRIPT_DIR', '/script' );

/***** THEME DIRECTORY *****/

/** Note the singular "theme". */
define( 'SITE_THEME_DIR', '/theme' );

/** Header HTML */
define( 'SITE_HEADER_DIR', '/header' );

/** Menu HTML */
define( 'SITE_MENU_DIR', '/menu' );

/** Footer HTML */
define( 'SITE_FOOTER_DIR', '/footer' );

/** Sidebar HTML  */
define( 'SITE_SIDEBAR_DIR', '/sidebar' );

/***** BIN DIRECTORY (Generally) *****/

/** Site Plugin Directory */
define( 'SITE_PLUGIN_DIR', '/plugins' );

/** Site Plugin Directory */
define( 'SITE_FRAMEWORK_DIR', '/frameworks' );

/** Site Required Plugins Directory (i.e. 'mu-plugins' directory) */
define( 'SITE_REQUIRED_DIR', '/required' );

/** As opposed to root theme directory. */
define( 'SITE_THEMES_DIR', '/themes' );

/** Default: languages  */
define( 'SITE_LANG_DIR', '/languages' );

/** Utilities **/

/** Archives are retained for historical purposes. May be a backup. */
define( 'SITE_ARCHIVE_DIR' , '/archive' );

/** Backups are "point in time". May be deleted. */
define( 'SITE_BACKUP_DIR' , '/backup' );

/** Cron */

/** Logs */
define( 'SITE_LOG_DIR', '/log' );

// Other
/** For flexibility, with ref. to WP_CONTENT_DIR. */
define( 'SITE_DIR_CLUTCH', '/..' );

/***** FILES *****/

/** Debug Log File Name */
define( 'SITE_LOG_DEBUG_FILE', '/debug.log' );

/***** BOOLEAN *****/

/** Default: false (Allows indexing by bots) */
define( 'SITE_INDEX_ALLOW', false );

/** Default: false */
define( 'SITE_FIXED_WIDTH', true );

/** Default: true */
define( 'SITE_USE_CSS_CHILD', true );

/** Default: false */
define( 'SITE_USE_CSS_MIN', false );

/** Default: false */
define( 'SITE_USE_CSS_COLOR', true );

/** Default: false */
define( 'SITE_USE_CSS_FONT', false );

/** Default: false */
define( 'SITE_USE_CSS_MONITORS', false );

/** Default: false */
define( 'SITE_USE_CSS_PRINT', true );

/** Default: false */
define( 'SITE_USE_JQUERY', false );

/** Default: false */
define( 'SITE_USE_JS', false );

/** Default: false */
define( 'SITE_USE_JS_MIN', false );

/** Default: false */
define( 'SITE_USE_SIDEBAR', false );

/** Default: false */
define( 'SITE_USE_WP_HEAD', false );

/** Default: false */
define( 'SITE_USE_WP_FOOTER', false );

/** Default: true (Changes double line breaks to paragraph tags) */
define( 'SITE_USE_WP_AUTO_PARA', false );

/** Default: true (Displays code processing time in milliseconds) */
define( 'SITE_ELAPSED_TIME', true );

/***** FILES *****/

/** Default: index.html (Used for caching purposes) */
define( 'SITE_INDEX_FILE', 'index.html' );

/*** AS ABOVE, SO BELOW (CONSTANTS BELOW BASED ON THOSE ABOVE) ***/

/*** SERVER FINE TUNING BEGIN **/

/* FILE UPLOAD SIZE */

if ( false ) {
	@ini_set( 'upload_max_size' , '64M' );
	@ini_set( 'post_max_size', '64M');
	@ini_set( 'max_execution_time', '300' );
}
/** Set to true to override default. Adjust as necessary. */

/*** SERVER FINE TUNING END **/

/***** PATHS *****/

/** */
define( 'SITE_CSS_DIR', '/css' );

/** */
define( 'SITE_JS_DIR', '/js' );

/** May be the same as WP_CONTENT_DIR. */
define( 'SITE_BIN_PATH', SITE_E_PATH . SITE_BIN_DIR );

/** In the "bin" directory. Some plugins assume it is here. */
define( 'SITE_CACHE_PATH', SITE_COMMONS_PATH . SITE_CACHE_DIR );

/** Folder name used by caching plugin */
define( 'SITE_CACHE_PLUGIN', '/cache-enabler' );

/** Path to the cached root index file. */
define( 'SITE_CACHE_PATH_INDEX', SITE_CACHE_PATH . SITE_CACHE_PLUGIN . SITE_CACHE_SLUG );

/** Frameworks like Laravel, Symfony and Firefly, a lighweight alternative. */
define( 'SITE_FRAMEWORK_PATH', SITE_BIN_PATH . SITE_FRAMEWORK_DIR );

/** {themes} = WP_CONTENT_DIR . '/themes'
 Theme folder hardcoded, relative to {wp-content} */
define( 'SITE_THEMES_PATH', SITE_BIN_PATH . SITE_THEMES_DIR );

/** Stores JavaScript and jQuery. */
define( 'SITE_SCRIPT_PATH', SITE_COMMONS_PATH . SITE_SCRIPT_DIR );

/**  JavaSript and jQuery files. */
define( 'SITE_JS_PATH', SITE_SCRIPT_PATH . SITE_JS_DIR );

/** Stores images, video, audio, pdfs, etc. */
define( 'SITE_MEDIA_PATH', SITE_COMMONS_PATH . SITE_MEDIA_DIR );

/*** PAGE PATH ***/

/** HTML Path */
define( 'SITE_HTML_PATH', SITE_PATH . SITE_HTML_DIR );

/** Page Path */
define( 'SITE_PAGE_PATH', SITE_HTML_PATH . SITE_PAGE_DIR );

/** Article Stub */
define( 'SITE_ARTICLE_STUB', '/article' );

/** HTML Extension */
define( 'SITE_HTML_EXT', '.html' );

/*** THEME PATH ***/

/** Theme Path */
define( 'SITE_THEME_PATH', SITE_COMMONS_PATH . SITE_THEME_DIR );

/** The CSS files that style the site. */
define( 'SITE_CSS_PATH', SITE_THEME_PATH . SITE_CSS_DIR );

/** Site HTML Path */
define( 'SITE_THEME_HTML_PATH', SITE_THEME_PATH . SITE_THEME_HTML_DIR );

/** Header Path */
define( 'SITE_HEADER_PATH', SITE_THEME_HTML_PATH . SITE_HEADER_DIR );

/** Footer Path */
define( 'SITE_FOOTER_PATH', SITE_THEME_HTML_PATH . SITE_FOOTER_DIR );

/** Sidebar Path */
define( 'SITE_SIDEBAR_PATH', SITE_THEME_HTML_PATH . SITE_SIDEBAR_DIR );

/** Menu Path */
define( 'SITE_MENU_PATH', SITE_THEME_HTML_PATH . SITE_MENU_DIR );

if ( ! defined( 'SITE_CONFIG_PATH' ) ) {
	/** Where most of the site configurations are stored. */
	define( 'SITE_CONFIG_PATH', SITE_ALT_PATH . SITE_CONFIG_DIR );
}

if ( ! defined( 'SITE_ALT_PATH' ) ) {
	/*** ALT PATH ***/
	define( 'SITE_ALT_PATH', SITE_COMMONS_PATH . SITE_ALT_DIR );
}

/** Backups are "point in time". May be deleted. */
define( 'SITE_BACKUP_PATH' , SITE_BIN_PATH . SITE_BACKUP_DIR );

/** Status codes such as 403, 404, 500, etc. */
define( 'SITE_CODE_PATH', SITE_ALT_PATH . SITE_CODE_DIR );

/** Handles server side crons. */
define( 'SITE_CRON_PATH', SITE_ALT_PATH . SITE_CRON_DIR );

/** Default: false. Shows errors if set to true. (See below for hiding these errors). */
define( 'SITE_LOG_PATH', SITE_ALT_PATH . SITE_LOG_DIR );

/***** URLS *****/

define( 'SITE_CLUSTER_URL', SITE_ROOT_URL . SITE_1_DIR . SITE_CLUSTER_DIR );

/** Site Theme URL */
define( 'SITE_THEME_URL', SITE_ROOT_URL . SITE_COMMONS_STUB . SITE_THEME_DIR );

/** Site Script URL (for JavaScript, etc.) */
define( 'SITE_SCRIPT_URL', SITE_ROOT_URL . SITE_COMMONS_STUB . SITE_SCRIPT_DIR );

/** Site JS URL */
define( 'SITE_JS_URL', SITE_ROOT_URL . SITE_COMMONS_STUB . SITE_SCRIPT_DIR . SITE_JS_DIR );

/** Site CSS URL */
if ( SITE_USE_CSS_MIN ) {
	/** Place the single minified css file in the root. */
	define( 'SITE_CSS_URL', SITE_ROOT_URL );
}
else {
	/** Place the working css file in the theme directory. */
	define( 'SITE_CSS_URL', SITE_THEME_URL . SITE_CSS_DIR );
}

/** Site CDN URL */
define( 'SITE_CDN_URL', '' );

/** Relative to ABSPATH. No leading slash. */
define( 'SITE_MEDIA_URL', SITE_ROOT_URL . SITE_COMMONS_STUB . SITE_MEDIA_DIR );

/* BUNDLE SPECIFIC CONSTANTS */

/**Values: true|false (Default: N/A) */
define( 'SITE_INTEGRATED_BUNDLE', true );

/* SELF_IDENTITY */

/** Site Type (Default: wp). */
define( 'SITE_TYPE', 'wp' );

/** Unique ID for this installation. */
define( 'SITE_UNIQUE_ID', md5( SITE_ROOT_URL ) );

/** Default: false (Develop first, then turn on) */
// define( 'SITE_CACHING', false );

// We may develop a different caching solution, but will use this for now:
