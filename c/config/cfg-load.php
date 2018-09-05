<?php

defined( 'SITE' ) || exit;

/**
 * Loads the configuration files. May include some logic.
 * There are two types. The first is site specific, and the second is "model"
 * related, that is, geared towards solving real world (i.e. physical 3D)
 * problems. This may be moved to a json file.
 */

/** Site Main Breaker Switch. */
require_once( __DIR__ . '/site' . '/cfg-switch.php' );

/**
 * If called from an alternate location, we need to ensure we have this. Required.
 * May already have been loaded (in main.php).
 */
require_once( __DIR__ . '/site' . '/cfg-structure.php' );

if ( file_exists( __DIR__ . '/site' . '/cfg-site-user.dnp.php' ) ) {
	/** Site specific variables that can be edited. Required for a unique identity. */
	require_once( __DIR__ . '/site' . '/cfg-site-user.dnp.php' );
}

/** Boolean values. Required */
require_once( __DIR__ . '/site/other' . '/cfg-boolean.php' );

/** Site specific defaults. Required. */
require_once( __DIR__ . '/site' . '/cfg-site-default.php' );

/** These files depend on the "enhanced" configuration. */

if ( file_exists( __DIR__ . '/site/other' . '/cfg-debug.php' ) ) {
	/** Optional */
	require_once( __DIR__ . '/site/other' . '/cfg-debug.php' );
}

if ( file_exists( __DIR__ . '/site/other' . '/cfg-wordpress.php' ) ) {
	/** Required if WordPress used */
	require_once( __DIR__ . '/site/other' . '/cfg-wordpress.php' );
}

if ( file_exists( __DIR__ . '/site/other' . '/cfg-plugins.php' ) ) {
	/** Important if WordPress used */
	require_once( __DIR__ . '/site/other' . '/cfg-plugins.php' );
}

if ( ( defined( 'SITE_USE_CORE' ) && SITE_USE_CORE ) || ( defined( 'WP_ADMIN' ) && WP_ADMIN ) ) {

	if ( $_SERVER['SERVER_ADDR'] == '127.0.0.1'
	&& file_exists( __DIR__ . '/db' . '/db-local.dnp.php' ) ) {

		/** Eliminate or rename this file if on a production or staging site */
		require_once( __DIR__ . '/db' . '/db-local.dnp.php' );

	/** Eliminate or rename this file if on a production site */
	} else if ( file_exists( __DIR__ . '/db' . '/db-staging.dnp.php' ) ) {

		require_once( __DIR__ . '/db' . '/db-staging.dnp.php' );

	/** Ensure this file is available for use on a production site. */
	} else if ( file_exists( __DIR__ . '/db' . '/db-production.dnp.php' ) ) {

		require_once( __DIR__ . '/db' . '/db-production.dnp.php' );

	}
	else {
		echo 'The database settings are not available!';
	}
}

if ( 0 ) {
	if ( file_exists( __DIR__ . '/model' . '/cfg-model.php' ) ) {
	/** Load the "model" specific configuration */
	require_once( __DIR__ . '/model' . '/cfg-model.php' );
	}
}
