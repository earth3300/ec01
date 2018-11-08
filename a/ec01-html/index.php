<?php
/**
 * EC01 HTML.
 *
 * A lighweight alternative to displaying HTML. Can be used on its own, or as a
 * WordPress theme. A basic set of constants are in this index file.
 *
 * @package Earth3300\EC01
 * @since 2018.10.29
 * @author Clarence J. Bos <cbos@tnoep.ca>
 * @copyright Copyright (c) 2018, Clarence J. Bos
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html vGPL-3.0
 * @link https://github.com/earth3300/ec01-html
 *
 * @wordpress-theme
 * Theme Name: EC01 HTML
 * Theme URI: https://github.com/earth3300/ec01-html
 * Description: A lightweight alternative to displaying HTML. Can be used on its own or as a WordPress theme.
 * Version: 2018.11.06
 * Author: Clarence J. Bos
 * Author URI: https://github.com/earth3300
 * Text Domain: ec01-html
 * License: GPL v3.0
 * License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
 *
 * File: index.php
 * Created: 2018-10
 * Updated: 2018-11-06
 * Time: 16:17 EST
 */

namespace Earth3300\EC01;

/**
 * If `wp_get_server_protocol` exists, we are in WordPress, otherwise not.
 */
if( function_exists( 'wp_get_server_protocol' ) )
{
	/** We are in WordPress, and check for direct access. */
	defined('ABSPATH') || exit('NDA');
}
else
{
	/** We are not in WordPress, and check for direct access. */
	defined('NDA') || exit('NDA');
}

if ( ! defined( 'SITE_PATH' ) && defined('ABSPATH') )
{
	/** Set SITE_PATH to ABSPATH, if in WordPress. */
	define( 'SITE_PATH', ABSPATH );
}
elseif ( ! defined( 'SITE_PATH' ) )
{
	/** Last ditch effort. The site path is two levels up. */
	define( 'SITE_PATH', __DIR__ . '/../../' );
}
elseif( defined( 'SITE_PATH' ) )
{
	/**
	 * It should be entirely possible to run this thing without a humungous config file.
	 * That means if it isn't there, this should still load but set a basic set of constants.
	 * Lets see what they are.
	 */

	/** The following is conditional. If these conditions are not met, it won't work. */
	if ( file_exists( SITE_PATH . '/c/config/cfg-load.php' ) )
	{
		/** Load the complete config file set, and use it. */
		define( 'SITE_USE_BASIC', false );

		/** Require the configuration files. */
		require_once( SITE_PATH . '/c/config/cfg-load.php' );
	}
	else
	{
		/** Lets set some basic config here. */
		define( 'SITE_TITLE', 'Site Title' );
		define( 'SITE_DESCRIPTION', 'Site Description' );
		define( 'SITE_USE_BASIC', true );
		define( 'SITE_USE_HEADER_SUB', true );
		define( 'SITE_USE_ASIDE', true );
		define( 'SITE_LANG', 'en' );
		define( 'SITE_CHARSET', 'UTF-8' );
		define( 'SITE_ELAPSED_TIME', false );
		define( 'SITE_IS_FIXED_WIDTH', false );
		define( 'SITE_USE_CSS_CHILD', false );
		define( 'SITE_HTML_EXT', '.html' );
		define( 'SITE_ARTICLE_FILE', '/article.html' );
		define( 'SITE_DEFAULT_FILE', '/default.html' );
		define( 'SITE_HTML_PATH', SITE_PATH . '/1' );
	}

	/** Require the "engine" file. This is expected to be there. */
	require_once( __DIR__ . '/includes/engine.php' );

	/**
	 * Instantiate the EC01HTML class and echo it.
	 *
	 * The class does all the rest of the work.
	 * It does not use a database. If we got this far, the class exists
	 * in the engine directory, otherwise it is *really* broken.
	 */
	$html = new EC01HTML();
	echo $html->get();
}
else
{
	/** Bail, and ask for help. */
	exit( 'The SITE_PATH needs to be set to the root directory of this site.' );
}
