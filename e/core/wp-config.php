<?php

if ( ! defined( 'SITE' ) ){
	/** Used to ensure files are not accessed directly. */
	define( 'SITE', true );
}

/** Default: false */
define( 'WP_CACHE', false );

if ( file_exists( __DIR__ . '/../alt/config/cfg-load.php' ) ) {
	require_once( __DIR__ . '/../alt/config/cfg-load.php' );
}
else {
	exit( 'Please check the path to the config file (wp-config.php).' );
}
// The rest can be found in the file referenced above.

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
