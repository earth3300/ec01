<?php
/**
 * SITE constants with Boolean Values
 *
 * It is helpful to use normal english here to ensure the user understands
 * the meaning. To provide this, three verbs are used: "USE", "IS" and "ALLOW".
 * Depending on the contenxt one of these may be more relevant.
 *
 * @example SITE_IS_SUB_DOMAIN  The entire site as defined here is a sub domain.
 * @example SITE_INDEX_ALLOW  Whether or not to _allow_ bots to index the site.
 * @example SITE_USE_CSS_CHILD  Using a css child stylesheet may be helpful in some cases.
 *
 * File: cfg-boolean.php
 * Created: 2018
 * Updated: 2018-12-12
 * Time: 09:27 EST
 */

defined('NDA') || exit('NDA');

/** Default: false (Whether or not site is a subdomain. */
define( 'SITE_IS_SUB_DOMAIN', true );

/** Default: false */
define( 'SITE_IS_FIXED_WIDTH', false );

/** Default: false */
define( 'SITE_ALLOW_FIXED_WIDTH', true );

/** Default: false (Allows indexing by bots) */
define( 'SITE_INDEX_ALLOW', false );

if ( '127.0.0.1' == $_SERVER['SERVER_ADDR'] )
{
  /** Do not use a minified file on a local machine. */
  define( 'SITE_USE_CSS_MIN', false );

  /** Do  not use a single concatenated file if on a local machine. */
  define( 'SITE_USE_CSS_ALL', false );
}
else
{
  /** Use a minified file if online, or not. */
  define( 'SITE_USE_CSS_MIN', true );

  /** Use a single concatenated file, if SITE_USE_CSS_MIN (above) is set to false. */
  define( 'SITE_USE_CSS_ALL', true );
}

/** Default: true (more basic). */
define( 'SITE_USE_CSS_CHILD', true );

/** Default: true */
define( 'SITE_USE_CSS_BOOTSTRAP', true );

/** Default: true */
define( 'SITE_USE_CSS_MAIN', true );

/** Default: false */
define( 'SITE_USE_CSS_SPRITE', true );

/** Default: false */
define( 'SITE_USE_CSS_COLOR', true );

/** Default: false */
define( 'SITE_USE_CSS_DEVICE', true );

/** Default: true (more fine tuned). */
define( 'SITE_USE_CSS_ADJUSTMENTS', true );

/** Default: false */
define( 'SITE_USE_JQUERY', false );

/** Default: false */
define( 'SITE_USE_JS', false );

/** Default: false */
define( 'SITE_USE_JS_MIN', false );

/** Default: false */
define( 'SITE_USE_HEADER_SUB', true );

/** Default: false */
define( 'SITE_USE_ASIDE', true );

/** Default: false */
define( 'SITE_USE_WP_HEAD', false );

/** Default: false */
define( 'SITE_USE_WP_FOOTER', false );

/** Default: true (Changes double line breaks to paragraph tags) */
define( 'SITE_USE_WP_AUTO_PARA', false );

/** Default: true (Displays code processing time in milliseconds) */
define( 'SITE_ELAPSED_TIME', true );

/** Default: true (Displays code processing time in milliseconds) */
define( 'SITE_USE_ELAPSED_TIME', true );

/* BUNDLE SPECIFIC CONSTANTS */

/**Values: true|false (Default: N/A) */
define( 'SITE_INTEGRATED_BUNDLE', true );

/** Default: false (Develop first, then turn on) */
// define( 'SITE_CACHING', false );

// We may develop a different caching solution, but will use this for now:
