<?php

defined( 'SITE' ) || exit;

/**
 * An enchanced configuration including booleans and values.
 */

/***** FILE NAMES *****/

/** Debug Log File Name (Same as above). */
define( 'SITE_DEBUG_FILE', '/debug.log' );

/** Default: index.html (Used for caching purposes) */
define( 'SITE_INDEX_FILE', 'index.html' );

/***** BOOLEAN *****/

/** Default: false (Allows indexing by bots) */
define( 'SITE_INDEX_ALLOW', false );

/** Default: false */
define( 'SITE_FIXED_WIDTH', true );

/** Default: false */
define( 'SITE_USE_FIXED_WIDTH', true );

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

/** Default: true (Displays code processing time in milliseconds) */
define( 'SITE_USE_ELAPSED_TIME', true );

/*** SERVER FINE TUNING BEGIN **/

/* FILE UPLOAD SIZE */

if ( false ) {
	@ini_set( 'upload_max_size' , '64M' );
	@ini_set( 'post_max_size', '64M');
	@ini_set( 'max_execution_time', '300' );
}
/** Set to true to override default. Adjust as necessary. */

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
