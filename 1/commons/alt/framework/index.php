<?php

/** Used as a check to ensure files are being called correctly */
define( 'FIREFLY', true );

if ( defined( 'SITE_PATH' ) ) {
	if ( file_exists ( SITE_CONFIG_PATH . '/site.php' ) ){
		require_once( SITE_CONFIG_PATH . '/site.php' );
	} else {
		exit( 'Please check the path to the config file (alt/framework/index.php).' );
	}
	require_once( __DIR__ . '/firefly/engine.php' );
	echo get_firefly_html();
} else {
	exit( 'The SITE_PATH needs to be set in the index.php file in the root directory of this site.' );
}
