<?php

defined( 'SITE' ) || exit;

if ( 1 ) {
	/** Set a timer to measure performance. */
	global $site_elapsed;

	/** Store the starting time to four decimal places in seconds (float) */
	$site_elapsed['start'] = microtime( true );

	/** Used as a check to ensure files are being called correctly */
	define( 'FIREFLY', true );

	if ( defined( 'SITE_PATH' ) ) {
		if ( 1 && file_exists ( SITE_CONFIG_PATH . '/cfg-site.dnp.php' ) ){
			require_once( SITE_CONFIG_PATH . '/cfg-site.dnp.php' );
		} else {
			exit( 'Please check the path to the config file (alt/framework/index.php).' );
		}
		if ( 1 ) {
			require_once( __DIR__ . '/firefly/engine.php' );
			if (! is_admin() ) {
				echo get_firefly_html();
			}
		}
	} else {
		exit( 'The SITE_PATH needs to be set in the index.php file in the root directory of this site.' );
	}
}
