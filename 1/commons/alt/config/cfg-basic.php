<?php

( defined( 'SITE' ) || defined( 'WP_ADMIN' ) ) || exit;

/** CDN (Default: /0) */
define( 'SITE_A_DIR', '/0' );

/** Who and Where (Default: /1) */
define( 'SITE_B_DIR', '/1' );

/** How and What (Default: /2 */
define( 'SITE_C_DIR', '/2' );

/** Why (Default: /3) */
define( 'SITE_D_DIR', '/3' );

define( 'SITE_COMMONS_DIR', '/commons' );

define( 'SITE_CONFIG_DIR', '/config' );

define( 'SITE_ALT_DIR', '/alt' );

define( 'SITE_CORE_DIR', '/core' );

define( 'SITE_FRAMEWORK_DIR', '/framework' );

/**** STUBS ****/

define( 'SITE_COMMONS_STUB', SITE_B_DIR . SITE_COMMONS_DIR );

define( 'SITE_ALT_STUB', SITE_COMMONS_STUB . SITE_ALT_DIR );

define( 'SITE_CONFIG_STUB', SITE_ALT_STUB . SITE_CONFIG_DIR );

define( 'SITE_CORE_STUB', SITE_COMMONS_STUB . SITE_CORE_DIR );

/**** PATHS ****/

if ( ! defined( 'SITE_PATH' ) ) {
	define( 'SITE_PATH', str_replace( SITE_CONFIG_STUB , '', __DIR__ ) );
}

define( 'SITE_B_PATH', SITE_PATH . SITE_B_DIR );

define( 'SITE_COMMONS_PATH', SITE_B_PATH . SITE_COMMONS_DIR );

define( 'SITE_ALT_PATH', SITE_COMMONS_PATH . SITE_ALT_DIR );

define( 'SITE_CONFIG_PATH', SITE_ALT_PATH . SITE_CONFIG_DIR );

define( 'SITE_CORE_PATH', SITE_COMMONS_PATH . SITE_CORE_DIR );

define( 'SITE_ALT_PATH', SITE_COMMONS_PATH . SITE_ALT_DIR );

define( 'SITE_FRAMEWORK_PATH', SITE_ALT_PATH . SITE_FRAMEWORK_DIR );
