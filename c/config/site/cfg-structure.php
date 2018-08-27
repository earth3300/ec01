<?php

( defined( 'SITE' ) || defined( 'WP_ADMIN' ) ) || exit;

/**
 * Loads the directory structure.
 *
 * Starts with the directory names, then builds the path based on these names.
 * Note that if the _structure_ of the path changes, the buildout of the path
 * will have to change accordingly. If everything is set up correctly, this
 * _should_ occur without anything breaking.
 */

/**** NUMBERED DIRECTORIES (content) ****/

/** CDN (Default: /0) */
define( 'SITE_0_DIR', '/0' );

/** Who and Where (Default: /1) */
define( 'SITE_1_DIR', '/1' );

/** How and What (Default: /2 */
define( 'SITE_2_DIR', '/2' );

/** Why (Default: /3) */
define( 'SITE_3_DIR', '/3' );

/**** LETTERED DIRECTORIES (NOT content) ****/

/** Dependencies manager (i.e. composer) */
define( 'SITE_D_DIR', '/d' );

/**
 * Main Engine Directory (Yes, this is a starship).
 * Includes the "core" which can then contain different versions of the same core
 * or different complex frameworks, so that we can choose the one we need for the
 * job at hand. It also contains an "alt(ernate)" framework, which starts off as
 * very simple. This can also contain a different version of a simple framework
 * or a moderately complex one, as needed. But each should inherit the configuration
 * schema and values as defined in these configuartion files.
 */
define( 'SITE_E_DIR', '/e' );

/** Log files. { @see http://12factor.net/ for how these can be viewed as part of the whole. } */
define( 'SITE_L_DIR', '/l' );

/** Response codes. */
define( 'SITE_R_DIR', '/r' );

/** User directories. These are protected by default by an .htaccess permissions script. */
define( 'SITE_U_DIR', '/u' );

/** Commons directory. */
define( 'SITE_COMMONS_DIR', '/commons' );

/** Configuration directory. !Important */
define( 'SITE_CONFIG_DIR', '/c/config' );

/** Alt(ernative) framework directory. */
define( 'SITE_ALT_DIR', '/alt' );

/** Main (core) directory */
define( 'SITE_CORE_DIR', '/core' );

/** Active core selected */
define( 'SITE_CORE_ACTIVE_DIR', '/wp' );

/** "Simple" framework directory. */
define( 'SITE_SIMPLE_DIR', '/simple' );

/**
 * STUBS
 *
 * These should be removed as we want to be able to rearrange the directory structure
 * from the root of the site
 */

// define( 'SITE_COMMONS_STUB', SITE_B_DIR . SITE_COMMONS_DIR );

// define( 'SITE_CONFIG_STUB', SITE_ALT_STUB . SITE_CONFIG_DIR );

// define( 'SITE_CORE_STUB', SITE_E_DIR . SITE_CORE_DIR );

/**
 * PATHS
 *
 * These paths are built from the root of the site (SITE_PATH) so that
 * they can be rearranged without breaking the site. Each of paths defined
 * here should be separate from the other and distinct in terms of the
 * concept or "dimension" that they represent.
 */

if ( ! defined( 'SITE_PATH' ) ) {
	define( 'SITE_PATH', str_replace( SITE_CONFIG_STUB , '', __DIR__ ) );
}

/** Path to the "Commons" directory. */
define( 'SITE_COMMONS_PATH', SITE_PATH . SITE_1_DIR . SITE_COMMONS_DIR );

/** Initially, the same as the "B" directory. */
define( 'SITE_2_PATH', SITE_PATH . SITE_2_DIR );

/** The path to the "engine" (PHP) directory. */
define( 'SITE_E_PATH', SITE_PATH . SITE_E_DIR );

/** Replace this with SITE_ENGINE_CORE_PATH */
define( 'SITE_CORE_PATH', SITE_PATH . SITE_E_DIR . SITE_CORE_DIR . SITE_CORE_ACTIVE_DIR );

/** Same as SITE_CORE_PATH */
define( 'SITE_ENGINE_CORE_PATH', SITE_PATH . SITE_E_DIR . SITE_CORE_DIR . SITE_CORE_ACTIVE_DIR );

/** The path to the "simple" engine. */
define( 'SITE_ENGINE_SIMPLE_PATH', SITE_PATH . SITE_E_DIR . SITE_ALT_DIR . SITE_SIMPLE_DIR );

define( 'SITE_CONFIG_PATH', SITE_PATH . SITE_CONFIG_DIR );
