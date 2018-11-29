<?php
/**
 * Loads the Configuration Files
 *
 * May include some logic.
 *
 * File: cfg-load.php
 * Created: 2018
 * Updated: 2018-11-14
 * Time: 09:27 EST
 */

defined('NDA') || exit('NDA');

/** Site Main Breaker Switch. */
require_once( __DIR__ . '/cfg-switch.php' );

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
require_once( __DIR__ . '/site' . '/cfg-boolean.php' );

/** Site specific defaults. Required. */
require_once( __DIR__ . '/site' . '/cfg-site-default.php' );

/** These files depend on the "enhanced" configuration. */

if ( file_exists( __DIR__ . '/site/wordpress' . '/cfg-debug.php' ) ) {
	/** Optional */
	require_once( __DIR__ . '/site/wordpress' . '/cfg-debug.php' );
}

if ( file_exists( __DIR__ . '/site/wordpress' . '/cfg-wordpress.php' ) ) {
	/** Required if WordPress used */
	require_once( __DIR__ . '/site/wordpress' . '/cfg-wordpress.php' );
}

if ( file_exists( __DIR__ . '/site/wordpress' . '/cfg-plugins.php' ) ) {
	/** Important if WordPress used */
	require_once( __DIR__ . '/site/wordpress' . '/cfg-plugins.php' );
}

if ( ( defined( 'SITE_USE_CORE' ) && SITE_USE_CORE ) || ( defined( 'WP_ADMIN' ) && WP_ADMIN ) ) {

	/**
	 * For efficiency, we start with the production site.
	 * If the following file is available, load it. Since it will be loaded first,
	 * on the local machine as well, it needs to be uploaded and then renamed. To
	 * make this works, it needs a different name, that shows it is not in production.
	 * This can be achieved by adding the word .local. to the name. Remove this after
	 * uploading. Doing it this way will also prevent automatic overriding when uploading.
	 */
	if ( file_exists( __DIR__ . '/db' . '/db-production.dnp.php' ) ) {

		require_once( __DIR__ . '/db' . '/db-production.dnp.php' );

	/** Do not have the above file on the staging server, so the below file can load. */
	} else if ( file_exists( __DIR__ . '/db' . '/db-staging.dnp.php' ) ) {

		require_once( __DIR__ . '/db' . '/db-staging.dnp.php' );
	}
	/** This file is called last. It should load only on a local machine.  */
	else if ( file_exists( __DIR__ . '/db' . '/db-local.dnp.php' ) ) {

		require_once( __DIR__ . '/db' . '/db-local.dnp.php' );
	}
	else {
		echo 'A database settings file is not available. Please provide one.';
	}
}
