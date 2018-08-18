<?php

/** Set a constant for security. Use it to ensure files are not accessed directly. */
define( 'SITE', true );

/** Set a timer to measure performance. */
global $site_elapsed;

/** Store the starting time to four decimal places in seconds (float) */
$site_elapsed['start'] = microtime( true );

/** Record the path we are in, for later. */
define( 'SITE_PATH', __DIR__ );

/** We have to hard code the path to this file,
* as we also need to access it from another location */
require_once( __DIR__ . '/e/alt/config/cfg-basic.php' );

/** Record which directory we are in, for later. */
define( 'SITE_DIR', '/' . basename(__DIR__) );

if ( $_SERVER['REQUEST_URI'] == '/' ) {
	/* If we are in the root directory (of the sub domain), use the "center" directory. */
	define( 'SITE_DEPT_DIR', '/center' );
} else {
	/* Else, use the folder we are in, which is leftmost in the URI requested. */
	define( 'SITE_DEPT_DIR', substr( $_SERVER['REQUEST_URI'], 0, strpos( $_SERVER['REQUEST_URI'], '/', 1 ) ) );
}

/** Use this directory as the domain name. Comment out if not. Set in site.php otherwise. */
//define( 'SITE_DOMAIN_NAME', basename(__DIR__) );

/** NEVER||MAYBE. Default: false. If false, NEVER use it. If true, MAYBE, depending on next constant. */
define( 'SITE_USE_CORE', 0 );

/** ALWAYS||MAYBE. Default: true. If false, ONLY if POST or GET */
define( 'SITE_USE_CORE_ALWAYS', 1 );

/** Use the alternative framework, if available. */
define( 'SITE_USE_ALT', 1 );

/** Use the core if we have decided to. If we have decided to for a request and if it is there. */

/** [ true && ( true: ALWAYS ) ][ ( true && ( false||* ): SOMETIMES ][ false && (*): NEVER ) ] */
if 	( 	SITE_USE_CORE && ( SITE_USE_CORE_ALWAYS
		|| ( ! empty( $_GET ) || $_SERVER['REQUEST_METHOD'] === 'POST' )
		&& file_exists( SITE_CORE_PATH . '/index.php' ) ) )  {

	require_once( SITE_CORE_PATH . '/index.php' );

}
/** If the core is not used, use an alternate framework, if it is available. */
else if ( SITE_USE_ALT && file_exists( SITE_ALT_FRAMEWORK_PATH . '/index.php' ) ) {
	require_once( SITE_ALT_FRAMEWORK_PATH . '/index.php' );

}
/** Otherwise, look for a plain text index.html file and serve that. */
else if ( file_exists( __DIR__ . "/index.html" ) ){
	echo file_get_contents( __DIR__ . '/index.html' );
}
/** If not, bail and ask for help. */
else {
	echo "<div style='font:16px/1.6 sans-serif;text-align:center;'><br>";
	echo "Nothing here.</div>" . PHP_EOL;
}

/**
 * To get to where we are going, we need to define where that is.
 * If we get there via another path, we *also* need to define it from there.
 * Therefore, we need to place the directions to where we are going in a generic location,
 * and access it once.
 */
