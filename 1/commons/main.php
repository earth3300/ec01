<?php

/** Do not call directly */
defined( 'SITE' ) || exit;

/** Set a timer to measure performance. */
global $site_elapsed;

/** Store the starting time to four decimal places in seconds (float) */
$site_elapsed['start'] = microtime( true );

/** Record the path we are in, for later. */
define( 'SITE_PATH', __DIR__ );

/** Record which directory we are in, for later. */
define( 'SITE_DIR', '/' . basename(__DIR__) );

/** The "core" directory is the main set of files to handle requests. */
define( 'SITE_CORE_DIR', '/core' );

/** The "alt" directory is the alternate set of files to handle requests. */
define( 'SITE_FRAMEWORK_DIR', '/alt/framework' );

/** Use this directory as the domain name. Comment out if not. */
// define( 'SITE_DOMAIN_NAME', basename(__DIR__) );

/** Use this directory name as the sub domain name. Comment out if not. */
// define( 'SITE_SUB_DOMAIN_NAME', basename(__DIR__) );

/** Use the core, if available. Start by using only if needed. */
define( 'SITE_USE_CORE', 1 ); // If off, never use core.

/** IF core is enabled, use it to handle Requests (GET or POST) or not. Default is true. */
define( 'SITE_USE_CORE_ALWAYS', 0 ); // If off, use core only if request

/** Use the alternative framework, if available. */
define( 'SITE_USE_ALT', 1 );

/** Use the core if we have decided to. If we have decided to for a request and if it is there. */

/** [ true && ( true: ALWAYS ) ][ ( true && ( false||* ): SOMETIMES ][ false && (*): NEVER ) ] */

/** Set to true if we make it through the corn maze. */
$site_using_core = false;

if ( SITE_USE_CORE  &&
		( SITE_USE_CORE_ALWAYS ||
			( ! empty( $_GET ) || $_SERVER['REQUEST_METHOD'] === 'POST' ) ) &&
				file_exists( __DIR__ . SITE_CORE_DIR . '/index.php' ) ) {
					$site_using_core = true; // used later.
					require_once( __DIR__ . SITE_CORE_DIR . '/index.php' );
}
/** If the core is not used, use an alternate framework, if it is available. */
else if ( SITE_USE_ALT &&
		 file_exists( __DIR__ . SITE_FRAMEWORK_DIR . '/index.php' ) ) {
			require_once( __DIR__ . SITE_FRAMEWORK_DIR . '/index.php' );
}
/** Otherwise, look for a plain text index.html file and serve that. */
else if ( file_exists( __DIR__ . '/index.html' ) ){
	echo file_get_contents( __DIR__ . '/index.html' );
}
/** If not, bail and state what happened. */
else {
	echo "<p>Neither the core, nor an alternate nor an index.html file is available.</p>" . PHP_EOL;
}
