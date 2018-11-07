<?php
/**
 * EC01 XML Reader
 *
 * Reads an XML file in the directory in which it is placed and displays it in
 * valid HTML. Can also be used as a WordPress plugin.
 *
 * @package Earth3300\EC01
 * @version 0.0.1
 * @author Clarence J. Bos <cbos@tnoep.ca>
 * @copyright Copyright (c) 2018, Clarence J. Bos
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPL v3.0
 * @link https://github.com/earth3300/ec01-xml-reader
 *
 * @wordpress-plugin
 * Plugin Name: EC01 XML Reader
 * Plugin URI:  https://github.com/earth3300/ec01-xml-reader
 * Description: Reads and XML file and displays it in HTML.  Shortcode [xml-reader dir=""].
 * Version: 0.0.1
 * Author: Clarence J. Bos
 * Author URI: https://github.com/earth3300/
 * Text Domain: ec01-xml-reader
 * License:  GPL v3.0
 * License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
 *
 * Standards: https://semver.org/  Versioning
 * Standards: https://www.php-fig.org/psr/  PHP Formatting
 * Standards: http://docs.phpdoc.org/references/phpdoc/tags/ Documentation.
 *
 * File: index.php
 * Created: 2018-10-07
 * Updated: 2018-11-07
 * Time: 16:18 EST
 */

namespace Earth3300\EC01;

/**
* Reads an XML file and displays it in HTML.
 *
 * See the bottom of this file for a more complete description
 * and the switch for determining the context in which this file
 * is found.
 */
class XMLReader
{

	/** @var array Default options. */
	protected $opts = [
		'max' => 1,
    'type' => 'xml',
    '.ext' => '.xml',
    'index' => false,
    'title' => 'XML Reader',
    'css' => '/0/theme/css/style.css',
		'url' => 'https://github.com/earth3300/ec01-xml-reader',
	];

  /**
   * Convert a SimpleXML object to an associative array
   *
   * @link https://gist.github.com/jasondmoss/7344311
   *
   * @param object $xmlObject
   *
   * @return array
   * @access public
   */
  private function simpleXmlToArray($xmlObject)
  {
      $array = [];
      foreach ($xmlObject->children() as $node) {
          $array[$node->getName()] = is_array($node) ? simplexml_to_array($node) : (string) $node;
      }

      return $array;
  }

  /**
	 * Embed the provided HTML in a Valid HTML Page
	 *
	 * Uses the HTML5 DOCTYPE (`<!DOCTYPE html>`), the UTF-8 charset, sets the
	 * initial viewport for mobile devices, disallows robot indexing, and
	 * references a single stylesheet.
	 *
	 * @param string $str
	 *
	 * @return string
	 */
	public function getPageHtml( $html, $args )
	{
		$str = '<!DOCTYPE html>' . PHP_EOL;
		$str .= sprintf( '<html class="dynamic theme-dark %s" lang="en-CA">%s', $args['type'], PHP_EOL );
		$str .= '<head>' . PHP_EOL;
		$str .= '<meta charset="UTF-8">' . PHP_EOL;
		$str .= '<meta name="viewport" content="width=device-width, initial-scale=1"/>' . PHP_EOL;
		$str .= sprintf( '<title>%s</title>%s', $this->opts['title'], PHP_EOL);
		$str .= $this->opts['index'] ? '' : '<meta name="robots" content="noindex,nofollow" />' . PHP_EOL;
		$str .= sprintf('<link rel=stylesheet href="%s">%s', $this->opts['css'], PHP_EOL);
		$str .= '</head>' . PHP_EOL;
		$str .= '<body>' . PHP_EOL;
		$str .= '<main>' . PHP_EOL;
		$str .= $html;
		$str .= '</main>' . PHP_EOL;
		$str .= '<footer>' . PHP_EOL;
		$str .= '<div class="text-center"><small>';
		$str .= sprintf( 'Note: This page has been <a href="%s">automatically generated</a>. No header, footer, menus or sidebars are available.', $this->opts['url'] );
		$str .= '</small></div>' . PHP_EOL;
		$str .= '</footer>' . PHP_EOL;
		$str .= '</html>' . PHP_EOL;

		return $str;
	}

} // End Class
