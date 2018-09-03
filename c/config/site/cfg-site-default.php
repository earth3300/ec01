<?php

defined( 'SITE' ) || exit;

/**
 * Site Default.
 *
 * Sets the defaults for the site and then constructs the URL based
 * on the defaults. Geared to the country of origin. May make certain
 * assumptions. Use this as a template and adjust as necessary,
 * if needed.
 */

if ( ! defined( 'SITE_LANG' ) ){
	/** Default: en-CA (Used in the HTML Page) */
	define( 'SITE_LANG', 'en' );
}

if ( ! defined( 'SITE_TITLE' ) ){
	/** Used in the browser tab and site header. */
	define( 'SITE_TITLE', 'Site Title' );
}
if ( ! defined( 'SITE_DESCRIPTION' ) ){
	/** Tagline. Used in site header. */
	define( 'SITE_DESCRIPTION', 'Site Description' );
}

if ( ! defined( 'SITE_START_YEAR' ) ){
	/** Site start year. Example: Copyright: START YEAR - END YEAR */
	define( 'SITE_START_YEAR', date('YYYY'));
}

/** If the root path is not defined from the root path, define it from the server variable. */
if ( ! defined( 'SITE_PATH' ) ){
	define( 'SITE_PATH', $_SERVER['DOCUMENT_ROOT']  );
}

if ( ! defined( 'SITE_SUB_DOMAIN_NAME' ) ){
	/** The subdomain. */
	define( 'SITE_SUB_DOMAIN_NAME', 'sub' );
}

if ( ! defined( 'SITE_SUB_DOMAIN' ) ){
	/** Default: false (Whether or not site is a subdomain. */
	define( 'SITE_SUB_DOMAIN', false );
}

if ( ! defined( 'SITE_USE_WWW' ) ){
	/** Default: true (Whether or not to use "www"). */
	define( 'SITE_USE_WWW', true );
}

/** From right to left:  Top Level Domain. (Include leading dot). */
if ( file_exists( SITE_PATH . '/.localhost' ) ){

	/** Can use http:// if local. */
	define( 'SITE_PROTOCOL', 'http://' );

	if ( ! defined( 'SITE_DOMAIN_EXT_LOCAL') ) {
		/** .local if we are on a local machine */
		define( 'SITE_DOMAIN_EXT_LOCAL', '.local' );
	}
	if ( ! defined( 'SITE_DOMAIN_NAME' ) ){
		/** The second level domain of the site. This needs to be unique. */
		define( 'SITE_DOMAIN_NAME', 'domain_name' );
	}

	/** Note: NO "www" on the local machine. */
	if ( SITE_SUB_DOMAIN ){
		/** Domain of the site (top, second, sub domain). */
		define( 'SITE_DOMAIN', SITE_SUB_DOMAIN_NAME . '.' . SITE_DOMAIN_NAME . SITE_DOMAIN_EXT_LOCAL );
	} else {
		/** Domain of the site (top, second, no sub domain). */
		define( 'SITE_DOMAIN', SITE_DOMAIN_NAME . SITE_DOMAIN_EXT_LOCAL );
	}

}
else {
	/** Should be https if production. If you must, change 1 to 0.*/
	if ( 0 ) {
		define( 'SITE_PROTOCOL', 'https://' );
	} else {
		define( 'SITE_PROTOCOL', 'http://' );
	}
	if ( ! defined( 'SITE_DOMAIN_EXT') ) {
		/** Localized according to region emanating from. Override above. */
		define( 'SITE_DOMAIN_EXT', '.ca' );
	}
	if ( ! defined( 'SITE_DOMAIN_NAME' ) ){
		/** The second level domain of the site. This needs to be unique. */
		define( 'SITE_DOMAIN_NAME', 'domain_name' );
	}
	/** Default: false (Whether or not site is a subdomain. */
	define( 'SITE_SUB_DOMAIN', true );

	if ( SITE_SUB_DOMAIN ){
		/** Domain of the site (top, second, sub). */
		define( 'SITE_DOMAIN', SITE_SUB_DOMAIN_NAME . '.' . SITE_DOMAIN_NAME . SITE_DOMAIN_EXT );
	} else if ( SITE_USE_WWW ) {
		/** Domain of the site (top, second, and www). */
		define( 'SITE_DOMAIN', 'www.' . SITE_DOMAIN_NAME . SITE_DOMAIN_EXT_LOCAL );
	} else {
		/** Domain of the site (top, second, no sub domain). */
		define( 'SITE_DOMAIN', SITE_DOMAIN_NAME . SITE_DOMAIN_EXT );
	}
}

/** The URL to the base of the site. */
define( 'SITE_ROOT_URL', SITE_PROTOCOL . SITE_DOMAIN );

/** The URL to the base of the site. */
define( 'SITE_URL', SITE_PROTOCOL . SITE_DOMAIN );

/** The URL to the core admin area. */
define( 'SITE_ADMIN_URL', SITE_URL . SITE_CORE_DIR . SITE_ADMIN_DIR );

/** May be the same as the domain. With leading forward slash. */
define( 'SITE_CACHE_SLUG', '/' . SITE_DOMAIN );

/** Default: InnoDB (Alt: MyISAM. InnoDB may be better) */
define( 'DB_ENGINE', 'InnoDB' );

/** Default: utf8 (Used in the Database) */
define( 'DB_CHARSET', 'utf8mb4' );

/** Change only if needed (Ambiguous) */
define( 'DB_COLLATE', '' );

/** Default: html (Used in the HTML Page) */
define( 'SITE_DOCTYPE', 'html' );

/** Default: UTF-8 (Used in the HTML Page) */
define( 'SITE_CHARSET', 'UTF-8' );

/***** STYLE BEGIN *****/

/** Use a minified stylesheet. Default: false */
define( 'SITE_USE_MIN', false );

/** Load external fonts. Default: false */
define( 'SITE_USE_FONTS', true );

/** Adjust default spacing. Default: false */
define( 'SITE_USE_SPACING', false );

/** Add a splash of color. Default: false */
define( 'SITE_USE_COLOR', true );

/** Use a child theme. Default: false */
define( 'SITE_USE_CHILD_THEME', false );

/***** STYLE END *****/

/** Define SITE_USE_CORE as true.  */
if ( ! defined( 'SITE_USE_CORE' ) ){
	define( 'SITE_USE_CORE', true );
}
