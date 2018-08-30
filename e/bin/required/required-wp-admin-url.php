<?php
/*
Plugin Name: WP Bundle Admin URL Change
Plugin URI: http://wp.cbos.ca/plugins/wp-bundle-admin-url-change/
Description: Change to a standard and generic /admin, rather than /wp-admin url.
Version: 2018.08.30
Author: wp.cbos.ca
Author URI: http://wp.cbos.ca
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

*/

/**
 * Copyright (C) 2010  hakre <http://hakre.wordpress.com/>
 *
 * For .htaccess:
 *
 * RewriteRule ^admin/(.*)$ wp-admin/$1 [QSA,L]
 *
 * @author hakre <http://hakre.wordpress.com>
 * @see http://wordpress.stackexchange.com/questions/4037/how-to-redirect-rewrite-all-wp-login-requests/4063
 * @link https://gist.github.com/hakre/701245
 */

/**
 * Change the /wp-admin url to /admin
 * 
 * Adapted from {@link https://gist.github.com/hakre/701245}
 */
class WPAdminUrl {

	/**
	 * The instance of the class
	 */
	static $instance;

	/**
	 * Bootstrap function
	 */
	static public function bootstrap() {
		null === self::$instance
			&& self::$instance = new self()
			;
		return self::$instance;
	}

	/**
	 * The cookie path has been set already (see config).
	 */
	public function __construct() {
		add_action('init', array($this, 'init'));
	}

	/**
	 * Filter the admin url
	 */
	public function init() {
		add_filter('admin_url', array($this, 'admin_url'), 10, 3);
	}

	/**
	 * Provide the admin url set in /config
	 */
	public function admin_url() {
		return SITE_ADMIN_URL . '/';
	}
}

return WPAdminUrl::bootstrap();

/* May need the following:
	public function admin_url($url, $path, $blog_id) {
    $scheme = (0 === strpos($url, '/')) ? 'relative' : 'admin';
    $find = get_site_url($blog_id, $this->renameFrom . '/', $scheme);
    $replace = get_site_url($blog_id, $this->renameTo . '/', $scheme);
    (0 === strpos($url, $find))
        && $url = $replace . substr($url, strlen($find));
    return $url;
	}
*/
