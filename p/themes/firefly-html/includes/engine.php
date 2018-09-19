<?php

defined( 'SITE' ) || exit;

/**
 * The FireFly HTML Engine.
 */
class FireFlyHTML
{

	/**
	 * Get.
	 *
	 * @return string
	 */
	public function get()
	{
		$this->load();
		$page = $this->getPage();
		$template = new FireFlyTemplate();
		$html = $template->getHtml( $page );
		return $html;
	}

	/**
	 * Load the required files.
	 */
	private function load(){
		require_once( __DIR__ . '/data.php' );
		require_once( __DIR__ . '/template.php' );
	}

	/**
	 * Get the page
	 * @return array
	 */
	private function getPage()
	{
		$page = $this->getUri();
		$page['slug'] = $this-> getPageSlug( $page );
		$page['header']['main'] = $this-> getHeader( $page );
		$page['article']= $this-> getArticle( $page );
		$page['article-title'] = $this-> getArticleTitle( $page['article'] );
		$page = $this-> getHtmlClass( $page );
		$page['header']['sub'] = $this-> getHeaderTierThree( $page );
		$page['page-title'] = $this-> getPageTitle( $page );
		$page['sidebar']= defined( 'SITE_USE_SIDEBAR' ) && SITE_USE_SIDEBAR ? $this->getSidebar() : '';
		$page['footer']= $this-> getFooter();
		return $page;
	}

	/**
	 * Get the filtered URI, ensuring it is safe, without the query string.
	 *
	 * Available: REQUEST_URI, QUERY_STRING and parse_url();
	 *
	 * @return boolean|string
	 */
	private function getUri()
	{
		$uri = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
		$uri = substr( $uri, 0, 65 );

		if ( empty ( $uri ) || $uri == '/' )
		{
			$page['front-page'] = true;
			$page['uri'] = '';
		}
		else
		{
			$page['front-page'] = false;
			$page['uri'] = $uri;
		}
		return $page;
	}

	/**
	 * Get the page slug.
	 *
	 * @param array $page
	 *
	 * @return string
	 */
	private function getPageSlug( $page )
	{
		$slug = rtrim( $page['uri'], '/' );
		return $slug;
	}

	/**
	 * Get the header.
	 *
	 * @param array $page
	 *
	 * @return str
	 */
	private function getHeader( $page )
	{
		$str = 'Header N/A';

		$file = SITE_HEADER_PATH . SITE_HEADER_DIR . SITE_HTML_EXT;

		if ( file_exists( $file ) )
		{
			$str = file_get_contents( $file );
			return $str;
		}
		else
		{
			return $str;
		}
	}

	/**
	 * Builds the Tier 3 Header.
	 *
	 * @param array $page
	 *
	 * @return array
	 */
	private function getHeaderTierThree( $page )
	{
		if ( isset( $page['tiers']['tier-4'] ) &&  $page['tiers']['tier-4'] )
		{
			$str = '<header class="site-header-sub">' . PHP_EOL;
			$str .= sprintf( '<div class="%s">%s', $page['class']['tier-4'], PHP_EOL );
			$str .= sprintf( '<div class="color lighter">%s', PHP_EOL );
			$str .= sprintf( '<div class="%s">%s', $page['class']['tier-3'], PHP_EOL );
			$str .= sprintf( '<a class="level-01 %s color darker" ', $page['class']['tier-3'], PHP_EOL );
			$str .= sprintf( 'href="/%s/%s%s/">', $page['tiers']['tier-2'], $page['tiers']['tier-3'], SITE_CENTER_DIR );
			$str .= sprintf( '<span class="icon"></span>%s</a>', ucfirst( $page['class']['tier-3'] ) );
			$str .= sprintf( '<span class="level-02 %s">', $page['class']['tier-4'] );
			$str .= '<span class="color lighter"><span class="icon"></span>';
			$str .= sprintf( '%s</span></span>%s', ucfirst( $page['class']['tier-4'] ), PHP_EOL );
			$str .= '</div><!-- .tier-3 -->' . PHP_EOL;
			$str .= '</div><!-- .inner -->' . PHP_EOL;
			$str .= '</div><!-- .tier-4-name -->' . PHP_EOL;
			$str .= '</header>' . PHP_EOL;
			return $str;
		} else
		{
			return false;
		}
	}

	/**
	 * Get the article.
	 *
	 * @param array $page
	 *
	 * @return string
	 */
	private function getArticle( $page )
	{
		$str = '<article>Article N/A.</article>';

		$file = $this->getArticleDirectory( $page );

		if ( file_exists( $file ) )
		{
			$str = file_get_contents( $file );
			return $str;
		}
		else
		{
			return $str;
		}
	}

	/**
	 * Get the article directory
	 * @param array $page
	 * @return string
	 */
	private function getArticleDirectory( $page )
	{
		if ( $page['front-page'] )
		{
			$file = SITE_PATH . SITE_ARTICLE_FILE;
		}
		else
		{
			$file = SITE_HTML_PATH . rtrim( $page['slug'], '/' ) . SITE_ARTICLE_FILE;
		}
		return $file;
	}

	/**
	 * Get the article title.
	 *
	 * @param array $page
	 *
	 * @return string
	 */
	private function getArticleTitle( $article )
	{
		$check = substr( $article, 0, 150 );
		$pattern = "/>(.*?)<\/h1>/";
		preg_match( $pattern, $check, $matches );
		if ( ! empty ( $matches[1] ) )
		{
			return ( $matches[1] );
		}
		else {
			return false;
		}
	}

	/**
	 * Get the class(es) to use in the HTML element.
	 *
	 * @param array
	 *
	 * @return string
	 */
	private function getHtmlClass( $page )
	{
		$page['class']['dynamic'] = $this->isPageDynamic( $page );

		$page['tiers'] = $this->getUriTiers( $page['uri'] );

		$page['class']['tier-2'] = $this->getUriTierTwo( $page['tiers'] );

		$page['class']['tier-3'] = $this->getUriTierThree( $page['tiers'] );

		$page['class']['tier-4'] = $this->getUriTierFour( $page['tiers'] );

		$page['class']['article'] = $this->getArticleClass( $page['article'] );

		$page['class']['html'] = $this->getHtmlClassStr( $page['class'] );

		return $page;
	}

	/**
	 * Build the HTML Class String From the Array.
	 *
	 * Do any other necessary processing.
	 *
	 * @param array $arr
	 *
	 * @return string
	 */
	private function getHtmlClassStr( $items )
	{
		if ( ! empty( $items ) )
		{
			$str = '';
			foreach ( $items as $item )
			{
				$str .= $item . ' ';
			}
			return trim( $str );
		}
		else
		{
			return null;
		}
	}

	/**
	 * Whether or not the Page is Dynamic or Fixed Width.
	 *
	 * @param array
	 *
	 * @return string
	 */
	private function isPageDynamic( $page )
	{
		if ( SITE_IS_FIXED_WIDTH && $page['front-page'] )
		{
			return 'fixed-width';
		}
		else
		{
			return 'dynamic';
		}
	}

	/**
	 * Get the class from the article element
	 *
	 * @param array
	 *
	 * @return str
	 */
	private function getArticleClass( $article )
	{
		$check = substr( $article, 0, 150 );
		$pattern = "/<article class=\"(.*?)\"/";
		preg_match( $pattern, $check, $matches );

		if ( ! empty ( $matches[1] ) )
		{
			return ( $matches[1] );
		}
		else
		{
			return false;
		}
	}

	/**
	 * Get the page and site title.
	 *
	 * @param array $page
	 *
	 * @return string
	 */
	private function getPageTitle( $page )
	{
		$str = "Site Title N/A";

		if ( defined( 'SITE_TITLE' ) )
		{
			if( $page['front-page'] )
			{
				$str = sprintf( '%s%s%s', SITE_TITLE, ' | ', SITE_DESCRIPTION );
				return $str;
			}
			else if ( ! empty ( $page['article-title'] ) )
			{
				$str = sprintf( '%s%s%s', $page['article-title'], ' | ', SITE_TITLE );
				return $str;
			}
			else
			{
				return SITE_TITLE;
			}
		}
		else
		{
			return $str;
		}
	}

	/**
	 * Get the menu.
	 *
	 * @param array $page
	 *
	 * @return string
	 */
	private function getMenu()
	{
		$str = 'Menu N/A';
		$file = SITE_MENU_PATH . SITE_MENU_DIR . SITE_HTML_EXT;
		if ( file_exists( $file ) )
		{
			$str = file_get_contents( $file );
			return $str;
		} else
		{
			return $str;
		}
	}

	/**
	 * Get the sidebar
	 *
	 * @param array $page
	 *
	 * @return string
	 */
	private function getSidebar( $page )
	{
		$str = 'Sidebar N/A';
		$file = SITE_SIDEBAR_PATH . SITE_SIDEBAR_DIR . SITE_HTML_EXT;
		if ( file_exists( $file ) )
		{
			$str = file_get_contents( $file );
			return $str;
		}
		else
		{
			return $str;
		}
	}

	/**
	 * Get the footer
	 *
	 * @return string
	 */
	private function getFooter()
	{
		$str = 'Footer N/A';
		$file = SITE_FOOTER_PATH . SITE_FOOTER_DIR . SITE_HTML_EXT;

		if ( file_exists( $file ) )
		{
			$str = file_get_contents( $file );
			return $str;
		} else
		{
			return $str;
		}
	}

	/**
	 * Firefly sanitize HTML
	 *
	 * Not currently used (2018.09.0)
	 * Remove everything but valid HTML
	 *
	 * @todo Needs some work
	 */
	private function sanitizeHtml( $str = '' )
	{
		if ( ! empty( $str ) )
		{
			$allowed = '<section><article><header><div><img><a><p><h1><h2><h3><h4><h5><h6><ol><li>';
			$stripped = strip_tags( $str, $allowed );
			return $stripped;
		} else
		{
			return false;
		}
	}

	/**
	 * Get Tier Two.
	 *	 *
	 * @param array $arr
	 *
	 * @return array|bool
	 */

	private function getUriTierTwo( $arr )
	{
		$items = get_tier_two_data();
		if ( ! empty( $arr['tier-2'] ) )
		{
			return 'tier-2 ' . $items[ $arr['tier-2'] ]['name'];
		}
		else
		{
			return false;
		}
	}

	/**
	 * Analyze the URI for Tier Three.
	 *
	 * Use this to add an html class based on an authorized cluster name.
	 * That is, we do not want this to be *too* flexible, we want
	 * to treat it as a concrete structure would be treated. We *can* move
	 * it, but not too frequently. Thus we need to think about it carefully,
	 * authorize it, and *then* use it, only if it matches.
	 *
	 * 1. Get the clusters.
	 * 2. Check the uri and get the word directly
	 * after the word 'cluster' (this can be changed).
	 * 3. Check to see if that word is in the authorized
	 * list of clusters. If it is, return it.
	 * It can then be used as, for example, an html class.
	 * The idea is to give earch cluster a unique color so that this
	 * can be used to quickly identify the section one is in. These are largely chosen already.
	 *
	 * @param array $arr
	 *
	 * @return array|bool
	 */

	private function getUriTierThree( $arr )
	{
		$items = get_tier_three_data();
		if ( ! empty( $arr['tier-3'] ) )
		{
			$name = isset( $items[ $arr['tier-3'] ]['name'] ) ? $items[ $arr['tier-3'] ]['name'] : '';
			return $name;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Analyze the URI for Tier Four
	 * @param array $arr
	 *
	 * @return array|bool
	 */
	private function getUriTierFour( $arr )
	{
		$items = get_tier_four_data();
		if ( ! empty( $arr['tier-4'] ) )
		{
			$name = isset( $items[ $arr['tier-4'] ]['name'] ) ? $items[ $arr['tier-4'] ]['name'] : '';
			return $name;
		} else
		{
			return false;
		}
	}

	/**
	 * Get the URI parts.
	 *
	 * This searches for the term only in the first two locations
	 * in the uri. This is where we expect it. If it is too far
	 * from this, we may not be that interested, as something is
	 * wrong with the directory structure then. We want to keep it
	 * simple and compact.
	 * This finds the position of the word 'cluster' and then returns
	 * the word directly after it, whatever it is (if present).
	 *
	 * *** The number of characters in the tier is one greater than the
	 * *** position of the tier in the URL structure.
	 *
	 * @param array $uri
	 *
	 * @return array|bool
	 *
	 * @example /whr/acad/
	 * @example /wha/bldg/
	 */
	private function getUriTiers( $uri )
	{
		/** Look for a grouping of three letters, followed by four. */
		$regex = '/\/([a-z]{3})\/([a-z]{4})\/([a-z]{5})\//';
		preg_match( $regex, $uri, $match );
		var_dump( $match );
		if ( ! empty( $match ) )
		{
			$arr['tier-2'] = ! empty( $match[1] ) ? $match[1] : null;
			$arr['tier-3'] = ! empty( $match[2] ) ? $match[2] : null;
			$arr['tier-4'] = ! empty( $match[3] ) ? $match[3] : null;
			return $arr;
		}
		else
		{
			return false;
		}
	}
} //end class
