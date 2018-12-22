<?php
/**
 * EC01 Engine (Earth3300\EC01)
 *
 * This file constructs the page.
 *
 * File: engine.php
 * Created: 2018-10-01
 * Update: 2018-12-15
 * Time: 10:25 EST
 */

namespace Earth3300\EC01;

/** No direct access (NDA). */
defined('NDA') || exit('NDA');

/**
 * The EC01 HTML Engine.
 *
 * This file contains the entire codeset and logic to construct the page. The
 * page is actually constructed using the `template.php` file.
 */
class EC01HTML
{
  /** @var $opts */
  protected $opts = [
    'screen' => 'screen',
  ];

  /**
   * Get the HTML Page.
   *
   * @return string
   */
  public function get()
  {
    $page = $this->getPage();
    $template = new EC01Template();
    $html = $template->getHtml( $page );
    return $html;
  }

  /**
   * Echo the HTML Page.
   *
   * @return string
   */
  public function the_html( $cache = true )
  {
    $page = $this->getPage();
    $template = new EC01Template();
    $html = $template->getHtml( $page );
    echo $html;

    if ( $cache )
    {
      $resp = $this->cache( $page, $html );
    }
  }

  /**
   * Cache
   *
   * Cache the HTML page.
   *
   * @param array $page
   * @param string $html
   *
   * @return void
   */
  private function cache( $page, $html )
  {
    /** We have cache file information, and it is of a reasonable length. */
    if ( isset( $page['file']['cache'] )
      && strlen( $page['file']['cache'] ) > 10 )
    {
      /** The $html variable we are about to save is a string. */
      if ( is_string( $html ) )
      {
        /** We need to check the length, to make sure--also--it is of a reasonable length. */
        $len = strlen( $html );

        /** Not too long and not too short. */
        if ( $len > 10 && $len < 100000 )
        {
          /** Let's pull the file out, so that we can deal with it explicitly. */
          $file = $page['file']['cache'];

          /** If it exists, we want to see whether or not is has changed. */
          if ( file_exists( $file ) )
          {
            /** Get the md5 hash of the $html string. */
            $md5_html = md5( $html );

            /** Get the md5 hash of the cached file. */
            $md5_cached = md5_file( $file );

            /** Compare the cached md5 with the $html md5. */
            if ( $md5_cached !== $md5_html )
            {
              /** They are not the same, cache it. */
              $resp = file_put_contents( $file, $html );
            }
          }
          else
          {
            /** It didn't exist, cache it. */
            $resp = file_put_contents( $file, $html );
          }
        }
      }
    }
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
      $page['article'] = 'Not available.';
    }
    else
    {
      $page['page']= false;
      $page['article']['text'] = $this->getArticleFile( $page );
    }
    $page['tiers'] = $this->getPageData( $page ); //needs the article, to get the class.
    $page['class'] = $this->getPageClasses( $page );
    $page['aside']['get'] = $this -> isPageAside( $page );
    $page['screen']['full'] = $this->isFullScreen( $page['class'] );
    $page['header']['main'] = $page['screen']['full'] ? '' : $this->getHeader( $page );
    $page['article']['title'] = $this->getArticleTitle( $page['article'] );
    $page['page']['title'] = $this-> getPageTitle( $page );
    $page['aside']['text'] = $page['aside']['get'] && ! $page['screen'] ? $this->getAside( $page ) : '';
    $page['footer']['text'] = $page['screen']['full'] ? '' : $this-> getFooter( $page );

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

    if ( empty ( $uri ) || '/' == $uri || '/index.php' == $uri )
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
        $file['cache'] = SITE_PATH . SITE_DEFAULT_FILE;
        $file['page'] = false;
      }
      elseif ( file_exists( SITE_PATH . SITE_DEFAULT_FILE ) )
      {
        $file['name'] = SITE_PATH . SITE_DEFAULT_FILE;
        $file['cache'] = SITE_PATH . SITE_DEFAULT_FILE;
        $file['page'] = true;
      }
    }
    elseif ( isset( $page['slug'] ) )
    {
      if ( file_exists( SITE_HTML_PATH . $page['slug'] . SITE_ARTICLE_FILE ) )
      {
        $file['name'] = SITE_HTML_PATH . $page['slug'] . SITE_ARTICLE_FILE;
        $file['cache'] = SITE_HTML_PATH . $page['slug'] . SITE_DEFAULT_FILE;;
        $file['page'] = false;
      }
      elseif( file_exists( SITE_HTML_PATH . $page['slug'] . $page['dir'] . SITE_HTML_EXT ) )
      {
        $file['name'] = SITE_HTML_PATH . $page['slug'] . $page['dir'] . SITE_HTML_EXT;
        $file['cache'] = SITE_HTML_PATH . $page['slug'] . SITE_DEFAULT_FILE;
        $file['page'] = false;
      }
      elseif ( file_exists( SITE_HTML_PATH . $page['slug'] . SITE_DEFAULT_FILE ) )
      {
        $file['name'] = SITE_HTML_PATH . $page['slug'] . SITE_DEFAULT_FILE;
        $file['cache'] = SITE_HTML_PATH . $page['slug'] . SITE_DEFAULT_FILE;;
        $file['page'] = true;
      }
      else
      {
        $file['name'] = false;
        $file['cache'] = false;
        $file['page'] = false;
      }
    }
    elseif ( file_exists( SITE_HTML_PATH . SITE_DEFAULT_FILE ) )
    {
      $file['name'] = SITE_HTML_PATH . SITE_DEFAULT_FILE;
      $file['cache'] = SITE_HTML_PATH . SITE_DEFAULT_FILE;
      $file['page'] = true;
    }
    else {
      $file['name'] = false;
      $file['cache'] = false;
      $file['page'] = false;
    }

    return $file;
  }

  /**
   * Get the Article Title
   *
   * @param array $page
   *
   * @return string
   */
  private function getArticleTitle( $article )
  {
    $check = substr( $article, 0, 300 );
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
   * @param array $page
   *
   * @return array
   */
  private function getPageData( $page )
  {
    if ( SITE_USE_TIERS )
    {
      $tiers = new EC01Tiers();

      /** Get the tiers data ('tiers') */
      $data = $tiers->getTiersData( $page );
      return $data;
    }
    else
    {
      return false;
    }
  }

  /**
   * Get the HTML Class.
   *
   * @param array $page
   *
   * @return array
   */
  private function getPageClasses( $page )
  {
    $class['type'] = $this->isPageResponsive( $page );

    $class['article'] = $this->getArticleClass( $page['article'] );

    /** Get the HTML class (from what is needed). */
    $class['html'] = $this->getHtmlClass( $page, $class );

    $class['body'] = $this->getBodyClass( $page['tiers'] );

    return $class;
  }

  /**
   * Get Full Screen
   *
   * @param string $class['article']
   *
   * @return boolean
   */
  private function isFullScreen( $class )
  {
    /** If the "screen" class is in the article class, return true. */
    if ( strpos( $class['article'], $this->opts['screen'] ) !== false )
    {
      return true;
    }
    else
    {
      /** Else, return false. */
      return false;
    }
  }

  /**
   * Build the HTML Class String From the Array.
   *
   * Do any other necessary processing.
   *
   * @param string $class
   * @param array $tiers
   *
   * @return string
   */
  private function getHtmlClass( $page, $class )
  {
    $tiers = $page['tiers'];

    /** Type of page (fixed-width or dynamic), with a trailing space. */
    $str = $class['type'] . ' ';

    /** Add the article class, if there is one (with a trailing space). */
    $str .= strlen( $class['article'] ) > 0 ? $class['article'] . ' ' : '';

    if ( strpos( $class['article'], 'screen' ) !== false )
    {
      $screen_full = true;
    }
    else
    {
      $screen_full = false;
    }

    if ( strpos( $class['article'], 'aside-off' ) !== false )
    {
      $aside_off = true;
    }
    else
    {
      $aside_off = false;
    }

    /** Add an 'aside' class, but not on the front page, on Tier 1 pages or wide screen pages. */
    if (
      SITE_USE_ASIDE
      && $page['tiers']['tier-1']['get']
      && $page['tiers']['tier-2']['get']
      && ! $page['tiers']['tier-3']['get']
      && ! $page['front-page']
      && ! $screen_full
      && ! $aside_off
    )
    {
      $str .= 'aside ';
    }
    else
    {
      $str .=  '';
    }

    if ( ! empty( $tiers ) )
    {
      /** Exclude these tiers in the html level element */
      $exclude = [ 'tier-2', 'tier-3' ];

      foreach ( $tiers as $tier )
      {

        if ( ! empty( $tier['tier'] ) && ! in_array( $tier['tier'], $exclude ) )
        {
          $str .= $tier['class'] . ' ';
        }
      }

      /** Remove the trailing space. */
      $str = trim( $str );

      /** Need a trailing space, but not a leading space. */
      $class = sprintf( 'class="%s" ', $str );

      /** Return the class. */
      return $class;
    }
    else
    {
      return null;
    }
  }

  /**
   * Build the Body Class String.
   *
   * Do any other necessary processing.
   *
   * @param array $tiers
   *
   * @return string|bool
   */
  private function getBodyClass( $tiers )
  {
    /** Nothing here (yet). */
    return null;
  }

  /**
   * Get the class from the article element
   *
   * @param string $article
   *
   * @return string
   */
  private function getArticleClass( $article )
  {
    /** Check to ensure it is a string and that it has at least the length of `<article></article>`. */
    if ( is_string( $article ) && strlen( $article ) > 17 )
    {
      /** Instantiate the variable to null. */
      $class = null;

      /** Check for the class in the first 150 characters only, for optimization purposes. */
      $check = substr( $article, 0, 150 );

      /** Define the pattern. */
      $pattern = "/<article class=\"(.*?)\"/";

      /** Find the match (assigned to $matches). */
      preg_match( $pattern, $check, $matches );

      /** Assign the match to a variable and trim, just in case. */
      $class = isset( $matches[1] ) ? trim( $matches[1] ) : '';

      /** Make sure it is not too big, and not too small. */
      if ( strlen( $class ) > 0 && strlen( $class ) < 50 )
      {
        /** Return it as a string (just to make sure). */
        return (string)$class;
      }
      else
      {
        /** Nothing (that we need) there. */
        return false;
      }
    }
    else
    {
      /** Nothing there in the article, or not a string. */
      return false;
    }
  }

  /**
   * Is Page Aside
   *
   * @param array $page
   *
   * @return bool
   */
  private function isPageAside( $page )
  {
    if ( is_array( $page['class'] ) && isset( $page['class']['article'] ) )
    {
      if( strpos( $page['class']['article'], 'aside-off' ) !== false )
      {
        return true;
      }
      else
      {
        return false;
      }
    }
    else
    {
      /** Default true. */
      return true;
    }
  }

  /**
   * Whether or not the Page is Dynamic or Fixed Width.
   *
   *
   *
   * @param array
   *
   * @return string
   */
  private function isPageResponsive( $page )
  {
    /** The class for a fixed width page. */
    $fixed_width = 'fixed-width';

    /** The class for a responsive page. */
    $responsive = 'responsive';

    if ( SITE_IS_FIXED_WIDTH )
    {
      return $fixed_width;
    }
    elseif( $page['front-page'] )
    {
      return $fixed_width;
    }
    elseif (
      isset( $page['tiers']['tier-1']['get'] )
      && $page['tiers']['tier-1']['get']
      && ! isset( $page['tiers']['tier-2']['get'] )
      )
    {
      return $fixed_width;
    }
    else
    {
      return $responsive;
    }
  }

  /**
   * Get the Page and Site Title.
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
   * 	Get the Header
   *
   * 	The SITE_TITLE and SITE_DESCRIPTION constants are used here.
   *
   * 	@param array $page
   *
   * 	@return string
   */
  private function getHeader( $page )
  {
    $str = '<header class="site-header">' . PHP_EOL;
    $str .= '<div class="inner">' . PHP_EOL;

    /** The front page link needs to wrap around the logo and title, but nothing else. */
    $str .= sprintf( '<a href="/" class="color" title="%s">%s', SITE_TITLE, PHP_EOL);

      $str .= '<div class="site-logo">' . PHP_EOL;
      $str .= '<div class="inner">' . PHP_EOL;

      /** Use the smaller site logo on pages with the sub header active. */
      if ( isset( $page['tiers']['tier-2']['get'] ) && $page['tiers']['tier-2']['get'] )
      {
        $str .= sprintf( '<img src="%s/image/site-logo-25x25.png"', SITE_THEME_DIR );
        $str .= ' alt="Site Logo" width="25" height="25" />' . PHP_EOL;
      }
      else
      {
        $str .= sprintf( '<img src="%s/image/site-logo-75x75.png"', SITE_THEME_DIR );
        $str .= ' alt="Site Logo" width="75" height="75" />' . PHP_EOL;
      }
      $str .= '</div><!-- .inner -->' . PHP_EOL;
      $str .= '</div><!-- .site-logo -->' . PHP_EOL;

      /** The title wrap includes the title and description, but nothing else. */
      $str .= '<div class="title-wrap">' . PHP_EOL;
      $str .= '<div class="inner">' . PHP_EOL;

      /** The site title and descriptions are constants set in /c/config/ or the index file of this package. */
      $str .= sprintf( '<div class="site-title">%s</div>%s', SITE_TITLE, PHP_EOL );
      $str .= sprintf( '<div class="site-description">%s</div>%s', SITE_DESCRIPTION, PHP_EOL );

      $str .= '</div><!-- .inner -->' . PHP_EOL;
      $str .= '</div><!-- .title-wrap -->' . PHP_EOL;

    /** Close the front page link. */
    $str .= '</a><!-- .front-page-link -->' . PHP_EOL;

    /** The sub header needs to be self closing. Turn it on or off using the constant below. */
    $str .= SITE_USE_HEADER_SUB ? $this->getHeaderSub( $page ) : '';

    /** Close the inner wrap and the header element. */
    $str .= '</div><!-- .inner -->' . PHP_EOL;
    $str .= '</header>' . PHP_EOL;

    return $str;
  }

  /**
   * Get the Sub Header.
   *
   * This is being used here to construct a rather complex three (or four) part
   * header. This is so that it can be used to provide better visual cues
   * as to where one is on the site. Color, blocking and icons are all used for
   * maximum effect. If necessary, this can be replaced as needed. Note where the
   * sub header is being placed with respect to the containing header and style
   * accordingly. Since this is using the tiers concept, we can do another check
   * for the file and then call it only if there. Checking if the class file_exist
   * may not work.
   *
   * @param array $page
   *
   * @return string|bool
   */
   private function getHeaderSub( $page )
   {
      /** The sub header is constructed in the `tiers.php` file. */
      $tiers = new EC01Tiers();
      $sub = $tiers->getHeaderSubTiered( $page );
      return $sub;
   }

   /**
    * Get the Aside
    *
    * The "aside" is also referred to as the "sidebar".
    *
    * @param array $page
    *
    * @return string|bool
    */
    private function getAside( $page )
    {
        $str = '';

        if (
          SITE_USE_ASIDE && ! $page['front-page']
          && $page['tiers']['tier-1']['get']
          && $page['tiers']['tier-2']['get']
          )
        {
          $str .= $this->getAsideFile( $page );

          return $str;
        }
        else
        {
          return false;
        }
    }

  /**
   * Get the Footer
   *
   * Adds the Site Title and Copyright information based on SITE_TITLE,
   * SITE_YEAR_TO_NOW and SITE_TITLE.
   *
   * @return string
   */
  private function getFooter( $page )
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

    /** Displays the time it took to generate the page. */
    $str .= SITE_ELAPSED_TIME && ! $page['file']['cache'] ? get_site_elapsed() : '';

    return $str;
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

  //**** GET HEADER, MENU, ARTICLE, SIDEBAR, FOOTER AND PAGE FILES *****/

  /**
   * Get the Header File.
   *
   * This can be used if this template part is static and rarely changes.
   * It just retrieves the template part saved to disk as an HTML file and
   * may be faster than if constructing the template part dynamically for every
   * page load.
   *
   * @param array $page
   *
   * @return string
   */
  private function getHeaderFile( $page )
  {
    $str = 'Header N/A';

    $file = SITE_HEADER_PATH . SITE_HEADER_CACHE_DIR . SITE_HTML_EXT;

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
   * Get the Menu File
   *
   * This can be used if this template part is static and rarely changes.
   * It just retrieves the template part saved to disk as an HTML file and
   * may be faster than if constructing the template part dynamically for every
   * page load.
   *
   * @param array $page
   *
   * @return string
   */
  private function getMenuFile()
  {
    $str = 'Menu N/A';
    $file = SITE_MENU_PATH . SITE_MENU_CACHE_DIR . SITE_HTML_EXT;
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
   * Get the Article File.
   *
   * This can be used if this template part is static and rarely changes.
   * It just retrieves the template part saved to disk as an HTML file and
   * may be faster than if constructing the template part dynamically for every
   * page load. Also performs a basic check on the file name length and the file
   * itself, to make sure nothing squirrely is happening here and that the content
   * is not trivial.
   *
   * @param array $page
   *
   * @return string|bool
   */
  private function getArticleFile( $page )
  {
    $str = '<article>Article N/A.</article>';

    $file = $page['file']['name'];
    if ( ! empty( $file ) && strlen ( $file ) < 180 )
    {
      if ( file_exists( $file ) )
      {
        $str = file_get_contents( $file );
      }
    }
    return $str;
  }

  /**
   * Get the Sidebar File.
   *
   * This can be used if this template part is static and rarely changes.
   * It just retrieves the template part saved to disk as an HTML file and
   * may be faster than if constructing the template part dynamically for every
   * page load.
   *
   * @param array $page
   *
   * @return string
   */
  private function getAsideFile( $page )
  {
    $str = 'Sidebar N/A';
    $file = SITE_SIDEBAR_PATH . SITE_SIDEBAR_CACHE_DIR . SITE_HTML_EXT;

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
   * Get the Footer File
   *
   * This can be used if this template part is static and rarely changes.
   * It just retrieves the template part saved to disk as an HTML file and
   * may be faster than if constructing the template part dynamically for every
   * page load.
   *
   * @return string
   */
  private function getFooterFile( $page )
  {
    $str = 'Footer N/A';
    $file = SITE_FOOTER_PATH . SITE_FOOTER_CACHE_DIR . SITE_HTML_EXT;

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
   * Get the Page File.
   *
   * The entire page exists. We are just being nice and delivering it as is.
   * A basic check on the file length is done. Otherwise, nothing much here.
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
} //end class


/**
 * Device
 *
 * Detects the device from the User Agent String. This is readily available
 * information.
 */
class DetectDevice
{

  /**
   * Get
   *
   * Get the device being requested.
   *
   * @return array
   */
  public function get()
  {
    /** Try to detect the device. */
    $device['detected'] = $this->getDetectedDevice();

    /** Get the device requested (if any) from the URL query string. */
    $device['requested'] = $this->getRequestedDevice();

    /** Determine the priority and the theme to deliver. */
    $device['theme'] = $this->getThemeToDeliver( $device );

    return $device;
  }

  /**
   * Try to Detect the Device from the USER AGENT String
   *
   * @return array
   */
  private function tryDetectDevice()
  {
    /** Get the user agent (Browser, device, etc.) and assigned it to an internal variable. */
    $ua = $_SERVER['HTTP_USER_AGENT'];

    /** Match common browsers (need Safari). */
    preg_match("/(Firefox|Chrome|MSIE)[.\/]([\d.]+)/", $ua, $matches);

    /** Match IE explicitly. */
    preg_match("/(MSIE) ([\d.]+)/", $ua, $ie);

    /** Generic mobile device. */
    $detected['mobile'] = strstr( strtolower( $ua ), 'mobile' ) ? true : false;

    /** Android. */
    $detected['android'] = strstr( strtolower( $ua ), 'android' ) ? true : false;

    /** Phone. */
    $detected['phone'] = strstr( strtolower( $ua ), 'phone' ) ? true : false;

    /** Ipad. */
    $detected['ipad'] = strstr( strtolower( $ua ), 'ipad' ) ? true : false;

    /** IE (again). */
    $detected['msie'] = strstr( strtolower( $ua ), 'msie' ) ? true : false;

    /** Version. */
    $detected['version'] = isset( $matches[2] ) ? $matches[2] : null;

    /** Not serviced (lower IE versions). */
    $detected['ns'] = isset( $ie[2] ) && $ie[2] < 10 ? true : false;

    /** Return the detected array. */
    return $detected;
  }

  /**
   * Get the Detected Device
   *
   * Reduce the attempt to detect the device to a global variable which is
   * a single letter. Also set the global.
   *
   * @return string
   */
  private function getDetectedDevice()
  {
    /** Get the array containing the detected devices. */
    if ( $device = $this->tryDetectDevice() )
    {
      if ( $device['phone'] )
      {
        return 'm';
      }
      elseif ( $device['mobile'] && $device['android'] )
      {
        return 'm';
      }
      elseif ( ! $device['mobile'] && $device['android'] )
      {
        return 't';
      }
      elseif ( $device['ipad'] )
      {
        return 't';
      }
      elseif ( $device['ns'] )
      {
        return 'ns';
      }
      else
      {
        return 'd'; // desktop (default).
      }
    }
    else
    {
      return false;
    }
  }

  /**
   * Get the Request for the Device from the URL.
   *
   * These cannot conflict with any other request parameters.
   *
   * @return array|false
   */
  function getRequestedDevice()
  {
    /** If the request is for a mobile device (generic), set to mobile (m). */
    if ( isset( $_GET['m'] ) )
    {
      return 'm';
    }
    /** If the request is for a tablet, set to tablet (t). */
    elseif ( isset( $_GET['t'] ) )
    {
      return 't';
    }
    /** If the request is for a desktop (d), set to desktop (d). */
    elseif ( isset( $_GET['d'] ) )
    {
      return 'd';
    }
    /** If the request is for an hd screen, set to High Definition (hd). */
    elseif ( isset( $_GET['hd'] ) )
    {
      return 'hd';
    }
    else
    {
      /** We don't know what it is. Return false. */
      return false;
    }
  }

  /**
   * Get the Theme to Deliver
   *
   * Merge the Detected Device with the Theme to Deliver.
   * Give priority to the requested format.
   *
   * @param array $device
   *
   * @return string
   */
  function getThemeToDeliver( $device )
  {
    if ( 'm' == $device['requested'] )
    {
      return 'm';
    }
    elseif ( 't' == $device['requested'] )
    {
      return 't';
    }
    elseif ( 'd' == $device['requested'] )
    {
      return 'd'; // Desktop
    }
    elseif ( 'hd' == $device['requested'] )
    {
      return 'hd';
    }
    else
    {
      /** Return whatever device is detected. */
      return $device['detected'];
    }
  }
} // End Device Class


function pre_dump( $arr )
{
  if ( 1 ) {
    echo "<pre>" . PHP_EOL;
    var_dump( $arr );
    echo "</pre>" . PHP_EOL;
  }
}
