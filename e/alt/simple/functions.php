<?php

/**
 * We want to be able to use these files, as is, in a theme folder that WordPress
 * is used to. In addition, we will assume that this is happening within this
 * bundle package. Therefore the constant 'SITE' is defined, so we can use that
 * to ensure these files are not called directly.  Finally, we will need a file
 * called "functions.php", as this is what WordPress looks for. Thus we will
 * use that. Finally, we want to be able to use this file so that it can be used as
 * is, very simply, in a directory to deliver HTML. Thus, we need to have the initial
 * file being called named "index.php", as that is the file (after index.html) that
 * is being looked for by the server, by default.
 */

defined( 'SITE' ) || exit;

if ( defined( 'SITE_PATH' ) ) {
	if ( file_exists ( SITE_CONFIG_PATH . '/cfg-load.php' ) ){
		require_once( SITE_CONFIG_PATH . '/cfg-load.php' );
	} else {
		exit( 'Please check the path to the config file (alt/simple/index.php).' );
	}
	require_once( __DIR__ . '/includes/engine.php' );
	echo get_firefly_html();
} else {
	exit( 'The SITE_PATH needs to be set in the index.php file in the root directory of this site.' );
}
