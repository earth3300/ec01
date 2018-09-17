<?php

/**
 * FireFly HTML.
 *
 * A lighweight alternative to displaying HTML. Can be used on its own, or as a WordPress theme.
 * Requires SITE_ constants, defined in: `/c/cfg-structure.php`.
 *
 * @package FireFlyHTML
 * @since 2018.9.0
 * @author Clarence Bos <cbos@tnoep.ca>
 * @copyright Copyright (c) 2018, Clarence Bos
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPL-3.0+
 * @link http://wp.cbos.ca/themes/firefly-html/
 *
 * @wordpress-theme
 * Plugin Name: FireFly HTML
 * Plugin URI: http://wp.cbos.ca/themes/firefly-html/
 * Description: A lightweight alternative to displaying HTML. Can be used on its own or as a WordPress theme.
 * Version: 2018.9.0
 * Author: Clarence Bos
 * Author URI: https://www.tnoep.ca/
 * Text Domain: firefly-html
 * License: GPL-3.0+
 * License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * if `wp_get_server_protocol` exists, we are in WordPress, otherwise not.
 */
if( function_exists( 'wp_get_server_protocol' ) )
{
	defined('ABSPATH') || exit('No direct access.');
}
else
{
	defined('SITE') || exit('No direct access.');
}

if ( ! defined( 'SITE_PATH' ) )
{
	define( 'SITE_PATH', $_SERVER['DOCUMENT_ROOT'] );
}

if ( file_exists( SITE_PATH . '/c/config/cfg-load.php' ) )
{
	require_once( SITE_PATH . '/c/config/cfg-load.php' );
}
else
{
	exit( 'Please check the path to the config file (alt/firefly-html/index.php).' );
}
require_once( __DIR__ . '/includes/engine.php' );


/**
 * Instantiate the FireFlyHTML class and echo it.
 *
 * The class does all the rest of the work.
 * It does not use a database.
 */

$html = new FireFlyHTML();
echo $html->get();
