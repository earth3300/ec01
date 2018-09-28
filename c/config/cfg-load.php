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
