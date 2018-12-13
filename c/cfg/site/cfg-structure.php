<?php
/**
 * Loads the directory structure.
 *
 * Starts with the directory names, then builds the path based on these names.
 * Note that if the _structure_ of the path changes, the buildout of the path
 * will have to change accordingly. If everything is set up correctly, this
 * _should_ occur without anything breaking.
 *
 * After development, a relatively _flat_ alphabetical type directory structure appears
 * to be working quite well. That is, the individual directories _underneath_ the
 * single letter top level directories are easy to find and don't appear to get
 * lost in the shuffle, primarily _because_ the first letter (or another clearly
 * identifying letter) is used as the top level directory, and when clicking on
 * that letter, it reveals the full name directly underneath it. To handle a path
 * part that contains two, three or four or more directories in it, this was handled
 * at first by using the word `STUB` and then building that stub. However, this
 * resulted in too many variations, with more constants than seemed necessary.
 *
 * In its place, allowing the directory name to refer to the letter of the top
 * level directory _plus_ the directory underneath it, keeps everything nice and
 * brief, with fewer constants in total.  Even though this approach does not keep
 * the technical definition of the word "directory" intact, it still allows for
 * adjustment in the case that the directory being named needs to be moved. This can
 * happen in the definition, rather than elsewhere.
 *
 * @example define( 'SITE_CORE_DIR_STUB', SITE_E_DIR . SITE_CORE_DIR ); vs.
 * @example define( 'SITE_CORE_DIR', '/e/cre' );
 *
 * Clearly, the second example easier to work with as the main function of the
 * "/e" directory is the "engine" or the core. Simpler is better.
 *
 * File: cfg-structure.php
 * Created: 2018-00-00
 * Updated: 2018-11-29
 * Time: 13:44 EST
 */

( defined( 'NDA' ) || defined( 'WP_ADMIN' ) ) || exit;

/**** NUMBERED DIRECTORIES (content) ****/

/** CDN (Default: /0) */
define( 'SITE_0_DIR', '/0' );

/** CDN (Default: /0) */
define( 'SITE_CDN_DIR', '/0' );

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
 * Alternate Engine Directory.
 *
 * Separate from the Main Engine Directory to improve the chances it will
 * still be functional in the event of a core meltdown.
 */
define( 'SITE_A_DIR', '/a' );

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
define( 'SITE_COMMONS_DIR', '/1/cmn' );

/** Configuration directory. !Important */
define( 'SITE_CONFIG_DIR', '/c/cfg' );

/** Alt(ernative) framework directory. */
define( 'SITE_ALT_DIR', '/alt' );

/** Directory for the minimal framework. */
define( 'SITE_MINIMAL_DIR', '/a/htm' );

/** Main (core) directory */
define( 'SITE_CORE_DIR', '/e/cre' );

/** Replaces {wp-content} */
define( 'SITE_BIN_DIR', '/p' );

/** Contains plugins (and themes), etc. */
define( 'SITE_P_DIR', '/p' );

/** Site plugins directory (Singular to follow format of other constants).  */
define( 'SITE_PLUGIN_DIR', '/plugins' );

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
define( 'SITE_ADMIN_DIR', '/wp-admin' );

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

/***** SELF AWARE *****/
/** copied from cfg-enhanced **/
/** Who and Where (Important) */

define( 'SITE_CLUSTER_DIR', '/cluster' );

define( 'SITE_CENTER_DIR', '/centr' );

/***** ROOT DIRECTORY *****/

/** Status code directory. */
define( 'SITE_RESPONSE_CODE_DIR', '/r/response-code' );

/** Cron directory */
define( 'SITE_CRON_DIR', '/n/cron' );

/** The directory where static HTML goes. Empty if for root. */
define( 'SITE_HTML_DIR', '/html' );

/** The directory where static HTML goes. */
define( 'SITE_THEME_HTML_DIR', '/html' );

/** The directory root pages go. */
define( 'SITE_ROOT_PAGE_DIR', '/root' );

/** Intended for static HTML content. */
define( 'SITE_PAGE_DIR', '/page' );

/** Intended for more informal updates. */
define( 'SITE_POST_DIR', '/post' );

/** Used for traditional cached files. */
define( 'SITE_CACHE_DIR', '/html' );

/** For data. From root. */
define( 'SITE_DATA_DIR', '/0/d' );

/** For media: image/, pdf/, video/, audio/, etc.  */
define( 'SITE_MEDIA_DIR', '/0/m' );

/** For javascript. From root. */
define( 'SITE_SCRIPT_DIR', '/0/s' );

/** For CSS, SCSS, etc. From root. */
define( 'SITE_THEME_DIR', '/0/t' );

/***** THEME DIRECTORY *****/

/** Note the singular "theme". */
//define( 'SITE_THEME_DIR', '/theme' );

/** Header HTML */
define( 'SITE_HEADER_CACHE_DIR', '/header' );

/** Menu HTML */
define( 'SITE_MENU_CACHE_DIR', '/menu' );

/** Footer HTML */
define( 'SITE_FOOTER_CACHE_DIR', '/footer' );

/** Sidebar HTML  */
define( 'SITE_SIDEBAR_CACHE_DIR', '/sidebar' );

/***** BIN DIRECTORY (Generally) *****/

/** Site Plugin Directory */
define( 'SITE_FRAMEWORK_DIR', '/frameworks' );

/** Site Required Plugins Directory (i.e. 'mu-plugins' directory) */
define( 'SITE_REQUIRED_DIR', '/required' );

/** As opposed to root theme directory. */
define( 'SITE_THEMES_DIR', '/themes' );

/** Default: languages  */
define( 'SITE_LANG_DIR', '/languages' );

/** Utilities **/

/** Archives are retained for historical purposes. May be a backup. */
define( 'SITE_ARCHIVE_DIR' , '/b/archive' );

/** Backups are "point in time". May be deleted. */
define( 'SITE_BACKUP_DIR' , '/b/backup' );

/** Cron */

/** Logs */
define( 'SITE_LOG_DIR', '/l/log' );

// Other
/** For flexibility, with ref. to WP_CONTENT_DIR. */
define( 'SITE_DIR_CLUTCH', '/..' );

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

/** Path to the (Internal) CDN directory. */
define( 'SITE_CDN_PATH', SITE_PATH . SITE_CDN_DIR );

/** Path to the "Commons" directory. */
define( 'SITE_COMMONS_PATH', SITE_PATH . SITE_COMMONS_DIR );

/** Initially, the same as the "B" directory. */
define( 'SITE_2_PATH', SITE_PATH . SITE_2_DIR );

/** The path to the "engine" (PHP) directory. */
define( 'SITE_E_PATH', SITE_PATH . SITE_E_DIR );

/** May be the same as WP_CONTENT_DIR. Now same as SITE_P_PATH. */
define( 'SITE_BIN_PATH', SITE_PATH . SITE_P_DIR );

/** The path to the "engine" (PHP) directory. */
define( 'SITE_P_PATH', SITE_PATH . SITE_P_DIR );

/** Replace this with SITE_ENGINE_CORE_PATH */
define( 'SITE_CORE_PATH', SITE_PATH . SITE_CORE_DIR );

/** The path to the "minimal" engine framework. */
define( 'SITE_MINIMAL_PATH', SITE_PATH . SITE_MINIMAL_DIR );

/** The path to the config directory. */
define( 'SITE_CONFIG_PATH', SITE_PATH . SITE_CONFIG_DIR );

/** The path to the core admin area. */
define( 'SITE_ADMIN_PATH', SITE_PATH . SITE_CORE_DIR . SITE_ADMIN_DIR );

/**** PATHS ****/

/** Copied from cfg-enhanced.php */

/** */
define( 'SITE_CSS_DIR', '/css' );

/** */
define( 'SITE_JS_DIR', '/js' );

/** In the "bin" directory. Some plugins assume it is here. */
define( 'SITE_CACHE_PATH', SITE_COMMONS_PATH . SITE_CACHE_DIR );

/** Frameworks like Laravel, Symfony and Firefly, a lighweight alternative. */
define( 'SITE_FRAMEWORK_PATH', SITE_BIN_PATH . SITE_FRAMEWORK_DIR );

/** {themes} = WP_CONTENT_DIR . '/themes' (Theme folder hardcoded, relative to {wp-content}). */
define( 'SITE_THEMES_PATH', SITE_BIN_PATH . SITE_THEMES_DIR );

/** Stores JavaScript and jQuery. */
define( 'SITE_SCRIPT_PATH', SITE_PATH . SITE_SCRIPT_DIR );

/**  JavaSript and jQuery files. */
define( 'SITE_JS_PATH', SITE_SCRIPT_PATH . SITE_JS_DIR );

/** Stores images, video, audio, pdfs, etc. */
define( 'SITE_MEDIA_PATH', SITE_PATH . SITE_MEDIA_DIR );

/*** PAGE PATH ***/

/** HTML Path */
define( 'SITE_HTML_PATH', SITE_PATH . SITE_1_DIR );

/** Page Path */
define( 'SITE_PAGE_PATH', SITE_HTML_PATH . SITE_PAGE_DIR );

/** Article Stub */
define( 'SITE_ARTICLE_STUB', '/article' );

/** HTML Extension */
define( 'SITE_HTML_EXT', '.html' );

/** Article file name (including .html extension and preceding forward slash). */
define( 'SITE_ARTICLE_FILE', '/article.html' );

/** Default file name (assumes complete page, including DOCTYPE). */
define( 'SITE_DEFAULT_FILE', '/default.html' );

/*** THEME PATH ***/

/** Theme Path */
define( 'SITE_THEME_PATH', SITE_COMMONS_PATH . SITE_THEME_DIR );

/** The CSS files that style the site. */
define( 'SITE_CSS_PATH', SITE_PATH . SITE_THEME_DIR . SITE_CSS_DIR );

/** Site HTML Path */
define( 'SITE_THEME_HTML_PATH', SITE_THEME_PATH . SITE_THEME_HTML_DIR );

/** Header Path */
define( 'SITE_HEADER_PATH', SITE_THEME_HTML_PATH . SITE_HEADER_CACHE_DIR );

/** Footer Path */
define( 'SITE_FOOTER_PATH', SITE_THEME_HTML_PATH . SITE_FOOTER_CACHE_DIR );

/** Sidebar Path */
define( 'SITE_SIDEBAR_PATH', SITE_THEME_HTML_PATH . SITE_SIDEBAR_CACHE_DIR );

/** Menu Path */
define( 'SITE_MENU_PATH', SITE_THEME_HTML_PATH . SITE_MENU_CACHE_DIR );

if ( ! defined( 'SITE_ALT_PATH' ) ) {
  /*** ALT PATH ***/
  define( 'SITE_ALT_PATH', SITE_COMMONS_PATH . SITE_ALT_DIR );
}

/** Backups are "point in time". May be deleted. */
define( 'SITE_BACKUP_PATH' , SITE_PATH . SITE_BACKUP_DIR );

/** Archives are intended to be retained into the indefinite future (should NOT be deleted). */
define( 'SITE_ARCHIVE_PATH' , SITE_PATH . SITE_ARCHIVE_DIR );

/** Status (response) codes such as 403, 404, 500, etc. */
define( 'SITE_RESPONSE_CODE_PATH', SITE_PATH . SITE_RESPONSE_CODE_DIR );

/** Handles server side crons. */
define( 'SITE_CRON_PATH', SITE_PATH . SITE_CRON_DIR );

/** Default: false. Shows errors if set to true. (See below for hiding these errors). */
define( 'SITE_LOG_PATH',  SITE_PATH . SITE_LOG_DIR );
