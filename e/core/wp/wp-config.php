<?php

if ( ! defined( 'SITE' ) ){
	/** Used to ensure files are not accessed directly. */
	define( 'SITE', true );
}

/** Default: false */
define( 'WP_CACHE', false );

/**
 * SITE_PATH should be defined in `main.php` in the root of the site.
 * When approaching this file from the login, SITE_PATH is not defined yet.
 */
if ( ! defined( 'SITE_PATH' ) ) {
	define( 'SITE_PATH', str_replace( '/e/core/wp' , '', __DIR__ ) );
}

if ( file_exists( SITE_PATH . '/c/config/cfg-load.php' ) ) {
	/** We are hard coding the directory "stub" here to reduce dependencies. */
	require_once( SITE_PATH . '/c/config/cfg-load.php' );
}
else {
	exit( 'Please check the path to the config file (wp-config.php).' );
}
// The rest can be found in the file referenced above.

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
