<?php

/** Set a constant for security. Use it to ensure files are not accessed directly. */
define( 'NDA', true );

/** Set a timer to measure performance. */
global $site_elapsed;

/** Store the starting time to four decimal places in seconds (float) */
$site_elapsed['start'] = microtime( true );

/** Record the path we are in, for later. */
define( 'SITE_PATH', __DIR__ );

/** Record which directory we are in, for later. */
define( 'SITE_DIR', '/' . basename(__DIR__) );

/**
 * The first path is hard coded here. It sets up the directory structure of the
 * site and assigns it to constants so that it can these can be used site wide.
 * and by the different frameworks, that will then inherit these values.
 */
require_once( __DIR__ . '/c/config/cfg-load.php' );

/** Use this directory as the domain name if it is not commented out. Set in /c/config/ otherwise. */
//define( 'SITE_DOMAIN_NAME', basename(__DIR__) );

/** Use the core if we have decided to. If we have decided to for a request and if it is there. */

/** [ true && ( true: ALWAYS ) ][ ( true && ( false||* ): SOMETIMES ][ false && (*): NEVER ) ] */
if ( defined('SITE_USE_CORE') && SITE_USE_CORE && ( SITE_USE_CORE_ALWAYS
		|| ( ! empty( $_GET ) || $_SERVER['REQUEST_METHOD'] === 'POST' )
		&& file_exists( SITE_CORE_PATH . '/index.php' ) ) )
{
	require_once( SITE_CORE_PATH . '/index.php' );
}
/** If the core is not used, use an alternative (simpler) framework, if it is available. */
else if ( defined('SITE_USE_MINIMAL') && SITE_USE_MINIMAL
		 && file_exists( SITE_MINIMAL_PATH . '/index.php' ) )
{
	require_once( SITE_MINIMAL_PATH . '/index.php' );
}
/** Otherwise, look for index.html file and serve that. */
else if ( file_exists( __DIR__ . "/index.html" ) )
{
	echo file_get_contents( __DIR__ . '/index.html' );
}
/** Otherwise, look default.html file and serve that. */
else if ( file_exists( __DIR__ . "/default.html" ) )
{
	echo file_get_contents( __DIR__ . '/default.html' );
}
/** If not, bail and ask for help. */
else {
	echo "<div style='font:16px/1.6 sans-serif;text-align:center;'><br>";
	echo "Nothing here.</div>" . PHP_EOL;
}
