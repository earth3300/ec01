<?php

if ( ! defined( 'NDA' ) ){
  /** Used to ensure files are not accessed directly. */
  define( 'NDA', true );
}
;
/** Default: false */
define( 'WP_CACHE', false );

/**
 * SITE_PATH should be defined in `main.php` in the root of the site.
 * When approaching this file from the login, SITE_PATH is not defined yet.
 */
if ( ! defined( 'SITE_PATH' ) ) {
  define( 'SITE_PATH', str_replace( '/e/core' , '', __DIR__ ) );
}

if ( file_exists( SITE_PATH . '/c/cfg/cfg-load.php' ) ) {
  /** We are hard coding the directory "stub" here to reduce dependencies. */
  require_once( SITE_PATH . '/c/cfg/cfg-load.php' );
}
else {
  exit( 'Please check the path to the config file (wp-config.php).' );
}
// The rest can be found in the file referenced above.

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
