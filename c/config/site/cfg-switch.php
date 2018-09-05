<?php

defined( 'SITE' ) || exit;

/**
 * "High Level" Main Routing Switch
 *
 * See bottom for notes
 */

/** NEVER||MAYBE. Default: false. If false, NEVER use it. If true, MAYBE, depending on next constant. */
define( 'SITE_USE_CORE', 0);

/** ALWAYS||MAYBE. Default: true. If false, ONLY if POST or GET */
define( 'SITE_USE_CORE_ALWAYS', 1 );

/** Use the alternative framework, if available. */
define( 'SITE_USE_SIMPLE', 1 );

/** Use the alternative framework, if available. (This could be "complex" in the future)*/
define( 'SITE_USE_ALT', 1 );

/**
 * This is a high level switch that determines which framework or frameworks get loaded.
 * The word "core" as used here is inherited from the WordPress use of the word. That is,
 * everything that is contained within the files and folders NOT in /wp-content. However,
 * we want to be more flexible than that, and allow for the use of a minimal framework.
 *
 * If `SITE_USE_CORE` is `0` (false) , then NEVER use it. If it is set to `1` (true) ,
 * then use it MAYBE. The MAYBE depends on `SITE_USE_CORE` always. If _it_ is set to
 * `1` (true), then use it ALWAYS. If it is set to `0` (false), then the MAYBE depends on whether
 * the request is a POST or a GET. If it is, then the logic will call the core. Otherwise
 * NOT.
 *
 * SITE_USE_ALT determines whether or not the Alternate framework is used. The default
 * for _this_ is the minimal framework. However, the location of this could be changed.
 */
