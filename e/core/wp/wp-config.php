<?php

if ( ! defined( 'SITE' ) ){
	/** Used to ensure files are not accessed directly. */
	define( 'SITE', true );
}

/** Default: false */
define( 'WP_CACHE', false );

/** SITE_PATH should be defined in `main.php` in the root of the site. */
if ( defined( 'SITE_PATH' ) ) {
	if ( file_exists( SITE_PATH . 'c/config/cfg-load.php' ) ) {
		/** We are hard coding the directory "stub" here to reduce dependencies. */
		require_once( SITE_PATH . 'c/config/cfg-load.php' );
	}
	else {
		exit( 'Please check the path to the config file (wp-config.php).' );
	}
// The rest can be found in the file referenced above.
} else {
	exit( 'The constant SITE_PATH needs to be defined in the root of the site.' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
