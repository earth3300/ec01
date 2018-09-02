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

/** "Simple" framework directory. */
define( 'SITE_SIMPLE_DIR', '/simple' );

/** Main (core) directory */
define( 'SITE_CORE_DIR', '/core' );

/** Replaces {wp-content} */
define( 'SITE_BIN_DIR', '/bin' );

/** Site plugins directory  */
define( 'SITE_PLUGINS_DIR', '/plugins' );

/*
 * Here we can begin to think about including other frameworks in the
 * (core) engine directory. That is, WordPress has a specific function and
 * is optimized best for certain purposes (like blogs). Although it can handle
 * other tasks (such as forums), other frameworks may be better suited for these
 * functions as they have been developed specifically for that purpose. Some of these
 * are phpBB (which has been around for a while), Feng Office (a project management
 * framework), mediaWiki (best known for its use in Wikipedia) and others, some of which
 * may not even have been developed yet.
 *
 * Since we are developing out a community framework that can assist us in being more
 * flexible, constistent and standardized across different uses, this can be a big help.
 *
 * For example, the URL ending for the WordPress administrative section is /wp-admin by
 * default. This can be changed with a plugin (already done here). By starting the admin
 * URL with /admin, we can then attach /wp at the _end_ of it. This then frees us up to
 * think about these other frameworks. This would then look like:
 *
 * - /admin/wp
 * - /admin/forum
 * - /admin/project
 * - /admin/wiki
 *
 * Each of these is unique and already has open sourced packages available which then
 * perform much better already "out of the box". This is what we are after.
 */

/** Site admin directory. */
define( 'SITE_ADMIN_DIR', '/admin' );

/** Active core selected */
define( 'SITE_CORE_ACTIVE_DIR', '/wp' );

/**
 * STUBS (Path from the root of the domain (not the server)
 *
 * Path parts are certain reusable parts of a path (usually from the root
 * to the directory specified). In a sense they could be called "relative",
 * that is _relative_ to the root of the site (but not extending back to the
 * server root). The word "stub" conveys the essence of the meaning, i.e.
 * something that is short and stubby. Alternatives are "REL" or "PART".
 *
 * Perhaps, for the sake of clarification, the "STUB" as defined here should
 * refer ONLY to the path from the root of the site to the directory under
 * question and "PART" as a subset of that (if needed). In this way, the directory
 * at the end of the "stub" can be moved around by reconfiguring that stub
 * with much greater confidence that it will still work, PROVIDED that there
 * is only a single connection to the underlying directory FROM that stub.
 * That is, no other routes to get there that are used. So, it has then to
 * be used consistently.
 */

 /** Path part from root to the commons dir */
define( 'SITE_COMMONS_STUB', SITE_1_DIR . SITE_COMMONS_DIR );

/** Path part: root to core. */
define( 'SITE_CORE_STUB', SITE_E_DIR . SITE_CORE_DIR );

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

/** The path to the config directory. */
define( 'SITE_CONFIG_PATH', SITE_PATH . SITE_CONFIG_DIR );

/** The path to the core admin area. */
define( 'SITE_ADMIN_PATH', SITE_PATH . SITE_CORE_DIR . SITE_CORE_ACTIVE_DIR .SITE_ADMIN_DIR );
