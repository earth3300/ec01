<?php

defined('NDA') || exit('NDA');

/**
 * WordPress Constants.
 *
 * Section I. Constants using a SITE_ prefix.
 *
 * Section II. All constants defined in this section should also be found
 * in the WordPress core.
 *
 * Note: In order to retain simplicity, the WordPress framework is assumed when
 * "core" or CORE is used. However, as the `/core` directory is used within the
 * `/e` directory AND this directory is now restricted to framework like filesets
 * that need not change often (as opposed to plugins and themes and the like,
 * _additional_ folders could be used for _other_ frameworks or versions of the
 * _same_ framework. Thus, a different core version could be `core-2`, `core-3`,
 * etc. OR a different framework could be `/wiki` (for mediaWiki) OR `/project`
 * for a project file.
 *
 * An additional consideration we are taking into account here is that a restriction
 * on the number of directories and the depths of these directories means that
 * the entire system will be more efficient when "AllowOverride" is set to "On"
 * in the server configuration file of Apache. When this is the case, the server
 * must look through _each and every directory_ on _each and every page request_
 * to find and assemble all of the .htaccess directives. As this is the case on
 * many shared hosts, we want to ensure the entire system remains as efficient
 * as possible in this respect.
 */

/**** CONSTANTS USING A SITE_ PREFIX ****/

/**
 * WP Admin directory (actual, not virtual)
 *
 * This may be the same as SITE_ADMIN_DIR (set in cfg-structure.php).
 */
if ( ! defined( 'SITE_ADMIN_DIR' ) ) {
	define( 'SITE_ADMIN_DIR', '/wp-admin' );
}

/** Path part: root to wp core. */
define( 'SITE_PLUGIN_STUB', SITE_P_DIR . SITE_PLUGIN_DIR );

/**** WORDPRESS SPECIFIC CONSTANTS ****/

/** Uncomment only if using https. Default: false  */
// define( 'FORCE_SSL_ADMIN', true );

/** Example: https://www.example.ca (The URL of your domain) */
define( 'WP_HOME', SITE_URL );

/** Where the core WordPress files reside, relative to site root. */
define( 'WP_SITEURL', SITE_URL . SITE_CORE_DIR );

/** Default: post (Other examples: "books", "cars" or "spaceships") */
define( 'WP_POST_TYPE', 'post' );

/** A "Catch All" for everything "not core". A path, not a directory */
define( 'WP_CONTENT_DIR', SITE_P_PATH );

/** Absolute path based on location of this file. */
define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . SITE_PLUGIN_DIR );

/** Absolute path based on location of this file. A path, not a directory */
define( 'WP_LANG_DIR', WP_CONTENT_DIR . SITE_LANG_DIR );

/** Important if different than default. */
define( 'WP_CONTENT_URL', SITE_URL . SITE_P_DIR );

/** Important if different than default. */
define( 'WP_PLUGIN_URL', WP_CONTENT_URL . SITE_PLUGIN_DIR );

/** Default: uploads */
//define( 'UPLOADS', '..' . SITE_MEDIA_DIR );

/** Default: uploads */
//define( 'BLOGUPLOADDIR', SITE_MEDIA_DIR );

/** Default: 'latest bundled theme' Comment out to use different for local, production, etc.*/
// define( 'WP_DEFAULT_THEME', 'twentyseventeen' );

/** Useful constants that can't be overriden */

/** Parent theme (wp-includes/default-constants.php) */
// 'TEMPLATEPATH' : get_template_directory();

/** Child theme (wp-includes/default-constants.php)  */
// 'STYLESHEETPATH' : get_stylesheet_directory();

/*** WORDPRESS FINE TUNING BEGIN ***/

/* FILE MANAGEMENT */

/**Values: true|false|int (Default: true)
//Maximum amount post revisions stored. If true, no limit? */
define( 'WP_POST_REVISIONS', 5 );

/** Values 0.. Default: 60)(seconds) */
define( 'AUTOSAVE_INTERVAL', 180 );

/** Default: false (Overwrites image edits) */
define( 'IMAGE_EDIT_OVERWRITE', true );

/** Default: 30 */
define( 'EMPTY_TRASH_DAYS', 30 );

/* SECURITY */

/** Default: false */
define( 'DISALLOW_UNFILTERED_HTML', true );

/** Default: false (true: Disable plugin and theme file editing) */
define( 'DISALLOW_FILE_EDIT', true );

/* UPDATES */

/** Default: false. (true: Disable plugin updating and deleting) */
define( 'DISALLOW_FILE_MODS', true );

/** Default: false (When true, disables plugin updating & deleting) */
define( 'WP_AUTO_UPDATE_CORE', false );

/* ACCESS */

/**
 * Block requests through the proxy. "Prevents plugins from working and core
 * functionality OR include api.wordpress.org.
 * see: core/wp-includes/class-http.php
 */

/** Default: undefined (When true, blocks external requests. Allows only site and localhost */
// define( 'WP_HTTP_BLOCK_EXTERNAL', true );

/** Default: undefined  (a comma seperated list of allowable hostnames) */
// define( 'WP_ACCESSIBLE_HOSTS', 'api.wordpress.org' );

/* OPTIMIZATION */

/** Default: false (Disable virtual cron for better performance)
// Set up server side cron for automatic backups (for example) */
define( 'DISABLE_WP_CRON', true );

/** Default: 60 (seconds). */
define( 'WP_CRON_LOCK_TIMEOUT', 60 );

/** Default: . Concatenate Scripts. */
//define('CONCATENATE_SCRIPTS', false );

/* MEMORY */

/** Default: 40M */
define( 'WP_MEMORY_LIMIT', '40M' );

/** Last Default: 256M */
define( 'WP_MAX_MEMORY_LIMIT', '256M' );

/*** WORDPRESS FINE TUNING END ***/

/*** MUST-USE (REQUIRED) PLUGINS DIR ***/

/** Default: 'wp-content/mu-plugins' (Must Use Plugin Directory *Stub*) */
define( 'MUPLUGINDIR', SITE_BIN_DIR . SITE_REQUIRED_DIR );

/** Default: 'wp-content/mu-plugins' (Must Use Plugins *Path*) */
define( 'WPMU_PLUGIN_DIR', SITE_BIN_PATH . SITE_REQUIRED_DIR );

/** Default: 'wp-content/mu-plugins' (Must Use Plugins URL) */
define( 'WPMU_PLUGIN_URL', WP_CONTENT_URL . SITE_REQUIRED_DIR );

/**** COOKIE PATHS (Copied from default-constants.php) ****/

/*
 * Since these are being defined first, there is no check to see if they
 * have been already set.
 *
 */

/**
 * Defines cookie related WordPress constants
 *
 * Defines constants after multisite is loaded.
 * @since 3.0.0
 */

/** Default: wordpress. */
define( 'SITE_COOKIE_PREFIX_WP' , 'wordpress' );

/** Default: site url */
define( 'SITE_CORE_WP_URL' , SITE_URL . SITE_CORE_DIR );

/**
 * Used to guarantee unique hash cookies
 *
 * Site url (of the wp core). Using generic here.
 *
 * @since 1.5.0
 */
define( 'COOKIEHASH', md5( SITE_CORE_WP_URL ) );

/**
 * @since 2.0.0
 */
define('USER_COOKIE', SITE_COOKIE_PREFIX_WP . 'user_' . COOKIEHASH);

/**
 * @since 2.0.0
 */
define('PASS_COOKIE', SITE_COOKIE_PREFIX_WP . 'pass_' . COOKIEHASH);

/**
 * @since 2.5.0
 */
define('AUTH_COOKIE', SITE_COOKIE_PREFIX_WP . '_' . COOKIEHASH);

/**
 * @since 2.6.0
 */
define('SECURE_AUTH_COOKIE', SITE_COOKIE_PREFIX_WP . '_sec_' . COOKIEHASH);

/**
 * @since 2.6.0
 */
define('LOGGED_IN_COOKIE', SITE_COOKIE_PREFIX_WP . '_logged_in_' . COOKIEHASH);

/**
 * @since 2.3.0
 */
define('TEST_COOKIE', SITE_COOKIE_PREFIX_WP . '_test_cookie');

/**
 * The path to the site (may be root), with a trailing "/"
 *
 * @since 1.2.0
 */
define('COOKIEPATH', SITE_DOMAIN . '/' );

/**
 * The path to the WordPress core from the root of the site.
 *
 * @since 1.5.0
 */
define('SITECOOKIEPATH', SITE_CORE_DIR . '/' );

/**
 * @since 2.6.0
 *
 * No trailing slash (in default).
 */
define( 'ADMIN_COOKIE_PATH', SITE_CORE_DIR . SITE_ADMIN_DIR ); // No trailing slash???

/**
 * @since 2.6.0
 *
 * No trailing '/'
 */
define( 'PLUGINS_COOKIE_PATH', SITE_PLUGIN_STUB );

/**
 * @since 2.0.0
 */
if ( 0 ) {
	define('COOKIE_DOMAIN', false);
}
