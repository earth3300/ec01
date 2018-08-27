<?php

defined( 'SITE' ) || exit;

/**
 * Loads the configuration files. May include some logic.
 * There are two types. The first is site specific, and the second is "model" 
 * related, that is, geared towards solving real world (i.e. physical 3D) 
 * problems.
 */


/**
 * If called from an alternate location, we need to ensure we have this. Required.
 * May already have been loaded (in main.php).
 */
require_once( __DIR__ . '/site' . '/cfg-structure.php' );

/** Site specific variables. Required. */
require_once( __DIR__ . 'cfg-site.dnp.php' );

if ( file_exists( __DIR__ . '/site/other' . '/cfg-enhanced.php' ) ) {
	require_once( __DIR__ . '/site/other' . '/cfg-enhanced.php' );

	/** These files depend on the "enhanced" configuration. */

	if ( file_exists( __DIR__ . '/site/other' . '/cfg-plugins.php' ) ) {
		require_once( __DIR__ . '/site/other' . '/cfg-plugins.php' );
	}

	if ( file_exists( __DIR__ . '/site/other' . '/cfg-debug.php' ) ) {
		require_once( __DIR__ . '/cfg-debug.php' );
	}

	if ( file_exists( __DIR__ . '/site/other' . '/cfg-wordpress.php' ) ) {
		require_once( __DIR__ . '/site/other' . '/cfg-wordpress.php' );
	}
} else {
	exit( 'Cannot serve your files. The complete configuration is not available.' );
}

if ( ( defined( 'SITE_USE_CORE' ) && SITE_USE_CORE ) || ( defined( 'WP_ADMIN' ) && WP_ADMIN ) ) {

	if ( $_SERVER['SERVER_ADDR'] == '127.0.0.1'
	&& file_exists( __DIR__ . '/site/' . '/db/db-local.dnp.php' ) ) {

		/** Eliminate or rename this file if on a production or staging site */
		require_once( __DIR__ . '/site/' . '/db/db-local.dnp.php' );

	/** Eliminate or rename this file if on a production site */
	} else if ( file_exists( __DIR__ . '/site/' . '/db/db-staging.dnp.php' ) ) {

		require_once( __DIR__ . '/site/' . '/db/db-staging.dnp.php' );

	/** Ensure this file is available for use on a production site. */
	} else if ( file_exists( __DIR__ . '/site/' . '/db/db-production.dnp.php' ) ) {

		require_once( __DIR__ . '/site/' . '/db/db-production.dnp.php' );

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
