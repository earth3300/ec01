<?php

defined( 'SITE' ) || exit;

/**
 * Loads all the configuration files. May include some logic.
 */

/**
 * If called from an alternate location, we need to ensure we have this. Required.
 * May already have been loaded (in main.php).
 */
require_once( __DIR__ . '/cfg-basic.php' );

/** Site specific variables. Required. */
require_once( __DIR__ . 'cfg-site.dnp.php' );

if ( file_exists( __DIR__ . '/cfg-enhanced.php' ) ) {
	require_once( __DIR__ . '/cfg-enhanced.php' );

	/** These files depend on the "enhanced" configuration. */

	if ( file_exists( __DIR__ . '/cfg-plugins.php' ) ) {
		require_once( __DIR__ . '/cfg-plugins.php' );
	}

	if ( file_exists( __DIR__ . '/cfg-debug.php' ) ) {
		require_once( __DIR__ . '/cfg-debug.php' );
	}

	if ( file_exists( __DIR__ . '/cfg-wordpress.php' ) ) {
		require_once( __DIR__ . '/cfg-wordpress.php' );
	}
} else {
	exit( 'Cannot serve your files. The complete configuration is not available.' );
}

if ( ( defined( 'SITE_USE_CORE' ) && SITE_USE_CORE ) || ( defined( 'WP_ADMIN' ) && WP_ADMIN ) ) {

	if ( $_SERVER['SERVER_ADDR'] == '127.0.0.1'
	&& file_exists( __DIR__ . '/db/db-local.dnp.php' ) ) {

		/** Eliminate or rename this file if on a production or staging site */
		require_once( __DIR__ . '/db/db-local.dnp.php' );

	/** Eliminate or rename this file if on a production site */
	} else if ( file_exists( __DIR__ . '/db/db-staging.dnp.php' ) ) {

		require_once( __DIR__ . '/db/db-staging.dnp.php' );

	/** Ensure this file is available for use on a production site. */
	} else if ( file_exists( __DIR__ . '/db/db-production.dnp.php' ) ) {

		require_once( __DIR__ . '/db/db-production.dnp.php' );

	}
	else {
		echo 'The database settings are not available!';
	}
}
