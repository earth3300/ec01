<?php

namespace Earth3300\EC01;

defined('NDA') || exit('NDA');

/**
 * The EC01 HTML Engine.
 *
 * This file contains the entire codeset and logic to construct the page. The
 * page is actually constructed using the `template.php` file.
 */
class EC01HTML
{
	/**
	 * Get the HTML Page.
	 *
	 * @return string
	 */
	public function get()
	{
		$this->load();
		$page = $this->getPage();
		$template = new EC01Template();
		$html = $template->getHtml( $page );
		return $html;
	}

	/**
	 * Load the required files.
	 *
	 * The `data.php` file is optional. It is being developed to allow
	 * more complex page headers to be constructed. In its most basic form
	 * only one header type is constructed. When this is the case, no extra
	 * data is needed. However, the `template.php` is required. It is not
	 * optional.
	 */
	private function load()
	{
		/** Contains optional information. */
		if( file_exists( __DIR__ . '/data.php' ) )
		{
			/** Using optional data to help define the site */
			require_once( __DIR__ . '/data.php' );

			/** Use tiers (to construct more complex headers). */
			define('SITE_USE_TIERS', true );

			if ( SITE_USE_TIERS )
			{
				/** Works together with the data file. */
				require_once( __DIR__ . '/tier.php' );
			}
		}
		else
		{
			/** Don't use tiers. */
			define('SITE_USE_TIERS', false );
		}
		require_once( __DIR__ . '/template.php' );
	}

	/**
	 * Get the page.
	 *
	 * This function takes no arguments. All of the work is done, starting from
	 * this function. Ths includes checking the URI, getting the page slug (which
	 * can include multiple directories. It also needs to include the name of
	 * the directory in which the file is found. This can then be used to check for
	 * a file of the same name as the containing direcory, in case the default
	 * name for the file (article.html) is not found. It returns the entire page
	 * as an array, which can then be translated into HTML by the template file.
	 *
	 * @param none
	 *
	 * @return array
	 */
	private function getPage()
	{
		$page = $this->getUri();
		$page['slug'] = $this->getPageSlug( $page );
		$page['dir'] = $this->getPageDir( $page );
		$page['file'] = $this->getArticlePathandFileName( $page );
		if( $page['file']['page'] )
		{
			$page['page'] = $this->getPageFile( $page );
			$page['article'] = false;
		}
		else
		{
			$page['page']= false;
			$page['article'] = $this->getArticleFile( $page );
		}
		$page = $this->getPageData( $page ); //needs the article, to get the class.
		$page['header']['main'] = $this->getHeader( $page );
		$page['article-title'] = $this->getArticleTitle( $page['article'] );
		if ( SITE_USE_TIERS )
		{
			if ( defined( 'SITE_USE_HEADER_SUB' )
					&& SITE_USE_HEADER_SUB
					&& ! isset( $page['tiers']['tier-4'] ) )
				{
					$page['header']['sub'] = $this-> getHeaderTierThree( $page );
				}
				else
				{
					$page['header']['sub'] = $this-> getHeaderTierFour( $page );
				}

		}
		$page['page-title'] = $this-> getPageTitle( $page );
		$page['sidebar']= defined( 'SITE_USE_SIDEBAR' ) && SITE_USE_SIDEBAR ? $this->getSidebar() : '';
		$page['footer']= $this-> getFooter();
		return $page;
	}

	/**
	 * Get the filtered URI, ensuring it is safe, without the query string.
	 *
	 * Available: REQUEST_URI, QUERY_STRING and parse_url(); Some work needs to
	 * be done here to be absolutely sure that the URI is safe.
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
	 * The page slug is the URI, with the following slash removed.
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
	 * Get the page directory.
	 *
	 * The page directory is the name of the directory containing the page.
	 * It is only one level deep (no nested directories).
	 *
	 * @param array $page
	 *
	 * @return string|bool
	 */
	private function getPageDir( $page )
	{
		$regex = '/\/([a-z]{3,25})$/';
		preg_match( $regex, $page['slug'], $match );
		if ( isset( $match[1] ) )
		{
			$dir = '/' . $match[1];
			return $dir;
		}
		else {
			return false;
		}
	}

	/**
	 * 	Get the Header (Tiers 1 and 2).
	 *
	 * 	Build from constants in the configuration (/c/config/...).
	 *
	 * 	@param array $page
	 *
	 * 	@return string
	 */
	private function getHeader( $page )
	{
		$str = '<header class="site-header">' . PHP_EOL;
		$str .= sprintf( '<a href="/" title="%s">%s', SITE_TITLE, PHP_EOL);
		$str .= '<div class="inner">' . PHP_EOL;
		$str .= '<div class="site-logo">' . PHP_EOL;
		$str .= '<div class="inner">' . PHP_EOL;
		$str .= '<img src="/0/theme/image/site-logo-75x75.png" alt="Site Logo" width="75" height="75" />' . PHP_EOL;
		$str .= '</div>' . PHP_EOL;
		$str .= '</div><!-- .site-logo -->' . PHP_EOL;
		$str .= '<div class="title-wrap">' . PHP_EOL;
		$str .= '<div class="inner">' . PHP_EOL;
		$str .= sprintf( '<div class="site-title">%s</div>%s', SITE_TITLE, PHP_EOL );
		$str .= sprintf( '<div class="site-description">%s</div>%s', SITE_DESCRIPTION, PHP_EOL );
		$str .= '</div><!-- .inner -->' . PHP_EOL;
		$str .= '</div><!-- .title-wrap -->' . PHP_EOL;
		$str .= '</div><!-- .inner -->' . PHP_EOL;
		$str .= '</a>' . PHP_EOL;
		$str .= '</header>' . PHP_EOL;

		return $str;
	}

	/**
	 * Get the header file. (Tiers 1 and 2).
	 *
	 * Not used by default.
	 *
	 * @param array $page
	 *
	 * @return string
	 */
	private function getHeaderFile( $page )
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
	 * Builds the Tier 2 Header.
	 *
	 * The Tier 2 Header is like the Tier 3 header (which was built first), but simpler
	 * as it does not contain the second part. However it is usual to visually differentiate
	 * between these Tiers as they contain different icons and colors.
	 *
	 * @param array $page
	 *
	 * @return array
	 */
	private function getHeaderTierThree( $page )
	{
		/** We need Tier 4 Information to construct a unique Tier-3/Tier-4 header. */
		if ( isset( $page['tiers']['tier-3'] ) &&  $page['tiers']['tier-3'] )
		{
			$url_tier3 = '/' . $page['tiers']['tier-2'] . '/' . $page['tiers']['tier-3'];

			$str = '<header class="site-header-sub">' . PHP_EOL;

			/** The less specific overlays the more specific to get the effect we want. */
			$str .= sprintf( '<div>%s', PHP_EOL );

			/** Left div. (Tier 3). */
			$str .= sprintf( '<div class="%s">%s', $page['class']['tier-3'], PHP_EOL );
			$str .= '<div class="color darker">' . PHP_EOL;
			$str .= sprintf( '<a class="level-01 %s" ', $page['class']['tier-3'], PHP_EOL );
			$str .= sprintf( 'href="%s/">', $url_tier3 . SITE_CENTER_DIR );
			$str .= sprintf( '<span class="icon"></span>%s</a>', ucfirst( $page['class']['tier-3'] ) );

			$str .= '</div><!-- .color .darker -->' . PHP_EOL;
			$str .= '</div><!-- .tier-3 -->' . PHP_EOL;
			$str .= SITE_USE_HEADER_SUB ? sprintf('<a href="/%s/" class="%s" title="%s"><span class="tier-2 level-1 icon"></span></a>',
				$page['tiers']['tier-2']['abbr'],
				$page['tiers']['tier-2']['class'],
				ucfirst( $page['tiers']['tier-2']['name'] )
				) : '';
			$str .= '</header>' . PHP_EOL;

			return $str;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Builds the Tier 3 and 4 Header.
	 *
	 * Needs to differentiate between the "Where" (name) and the "Who".
	 * We have $page['tiers'], which contains the Tier-2 short form (i.e.
	 * who, wha, how, whe, whn, and why.
	 *
	 * @param array $page
	 *
	 * @return array
	 */
	private function getHeaderTierFour( $page )
	{
		/** We need Tier 4 Information to construct a unique Tier-3/Tier-4 header. */
		if ( isset( $page['tiers']['tier-4'] ) &&  $page['tiers']['tier-4'] )
		{
			$url_tier3 = '/' . $page['tiers']['tier-2']['abbr'] . '/' . $page['tiers']['tier-3'];
			$url_tier4 = $url_tier3  . '/' . $page['tiers']['tier-4'];

			$str = '<header class="site-header-sub">' . PHP_EOL;

			/** The less specific overlays the more specific to get the effect we want. */
			$str .= sprintf( '<div>%s', PHP_EOL );

			/** Left div. (Tier 3). */
			$str .= sprintf( '<div class="%s">%s', $page['class']['tier-3'], PHP_EOL );
			$str .= '<div class="color darker">' . PHP_EOL;
			$str .= sprintf( '<a class="level-01 %s" ', $page['class']['tier-3'], PHP_EOL );
			$str .= sprintf( 'href="%s/">', $url_tier3 . SITE_CENTER_DIR );
			$str .= sprintf( '<span class="icon"></span>%s</a>', ucfirst( $page['class']['tier-3'] ) );

			/** Right div. (Tier 4). Absolute Positioning, within Tier 3. */
			$str .= sprintf( '<div class="level-02 right absolute %s">', $page['class']['tier-4'] );
			$str .= sprintf( '<div class="color lighter">%s', PHP_EOL );
			$str .= '<span class="header-height">';
			$str .= sprintf( '<a href="%s/">', $url_tier4 );
			$str .= sprintf( '<span class="icon icon-height"></span>%s</span>', ucfirst( $page['tier-4']['title'] ) );
			$str .= '</a>' . PHP_EOL;
			$str .= '</div>' . PHP_EOL;
			$str .= '</div><!-- .color .lighter -->' . PHP_EOL;
			$str .= '</div><!-- .tier-4 -->' . PHP_EOL;
			$str .= '</div><!-- .color .darker -->' . PHP_EOL;
			$str .= '</div><!-- .tier-3 -->' . PHP_EOL;
			$str .= SITE_USE_HEADER_SUB ? sprintf('<a href="/%s/" class="%s" title="%s"><span class="tier-2 level-1 icon"></span></a>',
				$page['tiers']['tier-2']['abbr'],
				$page['tiers']['tier-2']['class'],
				ucfirst( $page['tiers']['tier-2']['name'] ) ) : '';
			$str .= '</header>' . PHP_EOL;

			return $str;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Get the article.
	 *
	 * Do a basic check on the file length, to make sure nothing
	 * squirrely is happening here.
	 *
	 * @param array $page
	 *
	 * @return string
	 */
	private function getArticleFile( $page )
	{
		$str = '<article>Article N/A.</article>';

		$file = $page['file']['name'];

		if ( ! empty( $file ) && strlen ( $file ) < 120 )
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
	 * Get the page.
	 *
	 * The page exists. We are just being nice and delivering it as is. A basic
	 * check on the file length is done. Otherwise, nothing much here.
	 *
	 * @param array $page
	 *
	 * @return string
	 */
	private function getPageFile( $page )
	{
		$str = "This page doesn't exist.";
		$file = $page['file']['name'];
		if ( strlen ( $file ) < 120 )
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
	 * Get the Verfied Article Path and File Name.
	 *
	 * We need to do quite a bit of work here because we want to ensure that
	 * natural ways of saving a file are taken into account. Also, it is possible
	 * that the page is already saved as a complete HTML page with a DOCTYPE. If
	 * this is the case then we don't want to wrap a complete page inside a another
	 * page.
	 *
	 * @param array $page
	 *
	 * @return array
	 */
	private function getArticlePathandFileName( $page )
	{
		if ( $page['front-page'] )
		{
			if ( file_exists( SITE_PATH . SITE_ARTICLE_FILE ) )
			{
				$file['name'] = SITE_PATH . SITE_ARTICLE_FILE;
				$file['page'] = false;
			}
			elseif ( file_exists( SITE_PATH . SITE_DEFAULT_FILE ) )
			{
				$file['name'] = SITE_PATH . SITE_DEFAULT_FILE;
				$file['page'] = true;
			}
		}
		elseif ( isset( $page['slug'] ) )
		{
			if ( file_exists( SITE_HTML_PATH . $page['slug'] . SITE_ARTICLE_FILE ) )
			{
				$file['name'] = SITE_HTML_PATH . $page['slug'] . SITE_ARTICLE_FILE;
				$file['page'] = false;
			}
			elseif( file_exists( SITE_HTML_PATH . $page['slug'] . $page['dir'] . SITE_HTML_EXT ) )
			{
				$file['name'] = SITE_HTML_PATH . $page['slug'] . $page['dir'] . SITE_HTML_EXT;
				$file['page'] = false;
			}
			elseif ( file_exists( SITE_HTML_PATH . $page['slug'] . SITE_DEFAULT_FILE ) )
			{
				$file['name'] = SITE_HTML_PATH . $page['slug'] . SITE_DEFAULT_FILE;
				$file['page'] = true;
			}
			else
			{
				$file['name'] = false;
				$file['page'] = false;
			}
		}
		elseif ( file_exists( SITE_HTML_PATH . SITE_DEFAULT_FILE ) )
		{
			$file['name'] = SITE_HTML_PATH . SITE_DEFAULT_FILE;
			$file['page'] = true;
		}
		else {
				$file['name'] = false;
				$file['page'] = false;
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
	 * Get the Page Data (including the Classes) to Use in Construction the Page.
	 *
	 * There is a nod to more complex page functionality here that isn't included
	 * in the basic default version. Don't worry about this if using the basic
	 * version as the first section of this function isn't used if the files
	 * (tier.php and data.php) are not available.
	 *
	 * @param array
	 *
	 * @return string
	 */
	private function getPageData( $page )
	{
		if ( SITE_USE_TIERS )
		{
			$tiers = new EC01Tiers();

			$page['tiers'] = $tiers->getUriTiers( $page['uri'] );

			$page['tiers']['tier-2'] = $tiers->getUriTierTwo( $page['tiers'] );

			$page['class']['tier-2'] = $page['tiers']['tier-2']['class'];

			$page['class']['tier-3'] = $tiers->getUriTierThree( $page['tiers'] );

			$tier4 = $tiers->getUriTierFour( $page );

			$page['tier-4']['title'] = $tier4['title'];

			$page['class']['tier-4'] = $tier4['class'];
		}

		$page['class']['dynamic'] = $this->isPageDynamic( $page );

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
			$cnt = 0;
			foreach ( $items as $item )
			{
				/** Only need the first two items. */
				$cnt++;
				if ( $cnt > 2 )
				{
					break;
				}
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
				$description = str_replace( '<br />', ' ', SITE_DESCRIPTION );
				$str = sprintf( '%s%s%s', SITE_TITLE, ' | ', $description );
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
	 * Get the Footer
	 *
	 * @return string
	 */
	private function getFooter()
	{
		$str = '<footer class="nav">' . PHP_EOL;
		$str .= '<nav class="align-center">' . PHP_EOL;
		$str .= '<a href="../../../../" class="icon-up-4" title="Up 4 Directories">^4</a>&nbsp;&nbsp;' . PHP_EOL;
		$str .= '<a href="../../../" class="icon-up-3" title="Up 3 Directories">^3</a>&nbsp;&nbsp;' . PHP_EOL;
		$str .= '<a href="../../" class="icon-up-2" title="Up 2 Directories">^2</a>&nbsp;&nbsp;' . PHP_EOL;
		$str .= '<a href="../" class="icon-up-1" title="Up 1 Directory">^1</a>' . PHP_EOL;
		$str .= '</nav>' . PHP_EOL;
		$str .= '</footer>' . PHP_EOL;
		$str .= '<footer class="site-footer">' . PHP_EOL;
		$str .= '<div class="inner">' . PHP_EOL;
		/** SITE_YEAR_TO_NOW is empty string if same as SITE_YEAR_START, else '&ndash' . date('Y'); */
		if ( SITE_USE_BASIC )
		{
			$str .= sprintf( '<span class="copyright">Copyright &copy; %s %s</span>', date('Y'), SITE_TITLE );
		}
		else
		{
			$str .= sprintf( '<span class="copyright">Copyright &copy; %s%s %s</span>', SITE_YEAR_START, SITE_YEAR_TO_NOW, SITE_TITLE );
		}
		$str .= '<nav class="hide">' . PHP_EOL;
		$str .= '<ul class="horizontal-menu">' . PHP_EOL;
		$str .= '<li><a href="/page/privacy/">Privacy</a></li>' . PHP_EOL;
		$str .= '<li><a href="/page/terms/">Terms</a></li>' . PHP_EOL;
		$str .= '</ul>' . PHP_EOL;
		$str .= '</nav>' . PHP_EOL;
		$str .= '</div>' . PHP_EOL;
		$str .= '</footer>' . PHP_EOL;

		return $str;
	}

	/**
	 * Get the footer
	 *
	 * @return string
	 */
	private function getFooterFile()
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
	 * Sanitize HTML
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
} //end class
