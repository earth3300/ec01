<?php
/**
 * EC01 Tiers (Earth3300\EC01)
 *
 * This file helps to constructs the header. Optional.
 *
 * File: tiers.php
 * Created: 2018-10-01
 * Update: 2018-11-06
 * Time: 07:58 EST
 */

namespace Earth3300\EC01;

/** No direct access (NDA). */
defined('NDA') || exit('NDA');

/**
 * EC01 Tiers
 */
class EC01Tiers extends EC01HTML
{

	/**
	 * Get the Tiered Sub Header
	 *
	 * This is being called from engine.php and no checks are being done there.
	 * This means that ...
	 *
	 * @param array $page
	 *
	 * @return string|bool
   */
	protected function getHeaderSubTiered( $page )
	{
    if ( SITE_HAS_TIERS )
		{
      if ( $page['tiers']['tier-1']['get'] && ! $page['tiers']['tier-2']['get'] )
			{
					/** Get Header Tier 1. */
					$str = $this-> getHeaderTierOne( $page );

          /** Return the string. */
					return $str;
			}
			elseif ( $page['tiers']['tier-2']['get'] && ! $page['tiers']['tier-3']['get'] )
			{
				/** Get Header Tier 2. */
				$str = $this-> getHeaderTierTwo( $page );

        /** Return the string. */
				return $str;
			}
			elseif ( $page['tiers']['tier-3']['get'] )
			{
				/** Get Header Tier 2 and 3. */
				$str = $this-> getHeaderTierTwoThree( $page );

        /** Return the string. */
				return $str;
			}
			else
      {
        /** Return false. Nothing there. */
				return false;
			}
		}
		else
		{
			return false;
		}
	}

  /**
   * Builds the Tier 1 Header.
   *
   * The Tier 1 Header is like the Tier 2 header (which was built first), but simpler
   * as it does not contain Tier 2. However it is usual to visually differentiate
   * between these Tiers as they contain different icons and colors.
   *
   * @param array $page
   *
   * @return string|bool
   */
  private function getHeaderTierOne( $page )
  {
    /** We need Tier 3 Information to construct a unique Tier-2/Tier-3 header. */
    if ( $page['tiers']['tier-1']['get'] )
    {
      $url_tier1 = '/' . $page['tiers']['tier-1']['abbr'] . '/';

      /** Open the sub header div. */
      $str = '<div class="site-header-sub color lighter">' . PHP_EOL;

      /** Set the inner div. */
      $str .= sprintf( '<div class="inner">%s', PHP_EOL );

      /** Open the sub header link. */
      $str .= sprintf( '<a class="full-width %s" ', $page['tiers']['tier-1']['class'] );
      $str .= sprintf(
        'href="%s" title="%s">',
        $url_tier1,
        $page['tiers']['tier-1']['title']
      );

      /** Set the icon and the text for the icon (tier 1). */
      $str .= '<span class="icon"></span>';
      $str .= sprintf( '<span class="text">%s</span>%s', $page['tiers']['tier-1']['title'], PHP_EOL );

      /** Close the tier 1 link. */
      $str .= '</a>' . PHP_EOL;

      /** Close the inner div. */
      $str .= '</div><!-- .inner -->' . PHP_EOL;

      /** Close the sub header div. */
      $str .= '</div><!-- .sub header -->' . PHP_EOL;

      /** Return the string. */
      return $str;
    }
    else
    {
      return false;
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
	 * @return string|bool
	 */
	private function getHeaderTierTwo( $page )
	{
		/** We need Tier 3 Information to construct a unique Tier-2/Tier-3 header. */
		if ( $page['tiers']['tier-2']['get'] )
		{
			$url_tier2 = '/' . $page['tiers']['tier-1']['abbr'] . '/' . $page['tiers']['tier-2']['abbr'];

			$str = '<div class="site-header-sub">' . PHP_EOL;

			/** The less specific overlays the more specific to get the effect we want. */
			$str .= sprintf( '<div class="inner">%s', PHP_EOL );

			/** Left div. (Tier 3). */
			$str .= sprintf( '<div class="%s">%s', $page['tiers']['tier-2']['class'], PHP_EOL );
			$str .= '<div class="header-height color darker">' . PHP_EOL;
			$str .= sprintf( '<a class="left %s" ', $page['tiers']['tier-2']['class'], PHP_EOL );
			$str .= sprintf( 'href="%s/" title="%s">', $url_tier2 . SITE_CENTER_DIR, $page['tiers']['tier-2']['title'] );
			$str .= '<span class="icon"></span>';
			$str .= sprintf( '<span class="text">%s</span></a>%s', ucfirst( $page['tiers']['tier-2']['title'] ), PHP_EOL );

			$str .= '</div><!-- .darker -->' . PHP_EOL;
			$str .= '</div><!-- .tier-2 -->' . PHP_EOL;

			$str .= sprintf(
				'<a href="/%s/" class="%s" title="%s">',
				$page['tiers']['tier-1']['abbr'],
				$page['tiers']['tier-1']['class'],
				$page['tiers']['tier-1']['title']
				);
			$str .=	'<span class="tier-1 right icon"></span></a>' . PHP_EOL;
			$str .= '</div><!-- .tier-2 -->' . PHP_EOL;
			$str .= '</div><!-- .extra -->' . PHP_EOL;

			return $str;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Builds the Tier 2 and 3 Header.
	 *
	 * Needs to differentiate between the "Where" (name) and the "Who".
	 * We have $page['tiers'], which contains the Tier-2 short form (i.e.
	 * who, wha, how, whe, whn, and why.
	 *
	 * @param array $page
	 *
	 * @return string|bool
	 */
	private function getHeaderTierTwoThree( $page )
	{
		/** We need Tier 4 Information to construct a unique Tier-2/Tier-3 header. */
		if ( isset( $page['tiers']['tier-3'] ) &&  $page['tiers']['tier-3'] )
		{
			$url_tier2 = '/' . $page['tiers']['tier-1']['abbr'] . '/' . $page['tiers']['tier-2']['abbr'];

			$url_tier3 = $url_tier2  . '/' . $page['tiers']['tier-3']['abbr'];

			$str = '<div class="site-header-sub">' . PHP_EOL;

			/** The less specific overlays the more specific to get the effect we want. */
			$str .= sprintf( '<div class="inner">%s', PHP_EOL );

			/** Left div. (Tier 2). */
			$str .= sprintf( '<div class="%s">%s', $page['tiers']['tier-2']['class'], PHP_EOL );

			/** This is full width, encapsulating tier 3 */
			$str .= '<div class="color darker header-height">' . PHP_EOL;

					$str .= sprintf( '<a class="left %s" ', $page['tiers']['tier-2']['class'], PHP_EOL );
					$str .= sprintf(
						'href="%s/" title="%s">%s',
						$url_tier2 . SITE_CENTER_DIR,
						$page['tiers']['tier-2']['title'],
						PHP_EOL
						);
					$str .= '<span class="icon"></span>' . PHP_EOL;
					$str .= sprintf( '<span class="text hide-tablet">%s</span>%s', $page['tiers']['tier-2']['title'], PHP_EOL );
					$str .= '</a><!-- .left -->' . PHP_EOL;

					/** Right div. (Tier 3). Absolute Positioning, within Tier 2. */
					$str .= sprintf( '<div class="right absolute %s">%s', $page['tiers']['tier-3']['class'], PHP_EOL );

					/** Open the lighter div (tier 3) */
					$str .= sprintf( '<div class="color lighter header-height">%s', PHP_EOL );

					/** Try removing */
					//$str .= '<div class="header-height">' . PHP_EOL;

					/** Open the tier 3 link. */
					$str .= sprintf(
						'<a href="%s/" title="%s">%s',
						$url_tier3,
						$page['tiers']['tier-3']['title'],
						PHP_EOL
					);

					/** Tier 3 icon. */
					$str .= '<span class="icon icon-height"></span>' . PHP_EOL;

					/** Tier 3 text. */
					$str .= sprintf( '<span class="text hide-phone">%s</span>%s',
									ucfirst( $page['tiers']['tier-3']['title'] ), PHP_EOL );

					/** Close the tier 3 link. */
					$str .= '</a><!-- .tier-3 -->' . PHP_EOL;

					/** Remove. Close the div to keep the header at the right height. */
					//$str .= '</div><!-- .header-height -->' . PHP_EOL;

					/** Close the lighter div (tier 3) */
					$str .= '</div><!-- .lighter -->' . PHP_EOL;

					/** Close the inner div. */
					$str .= '</div><!-- .inner -->' . PHP_EOL;

					/** Close the darker (tier 2) div. */

					$str .= '</div><!-- .darker -->' . PHP_EOL;

					/** Tier 3 div */
					$str .= '</div><!-- .tier-3 -->' . PHP_EOL;

				/** The tier 1 link, open */
				$str .= sprintf(
					'<a href="/%s/" class="%s" title="%s">%s',
					$page['tiers']['tier-1']['abbr'],
					$page['tiers']['tier-1']['class'],
					$page['tiers']['tier-1']['title'],
					PHP_EOL
				);

			/** Tier 1 icon, link and title */
			$str .= '<span class="tier-1 right icon"></span>' . PHP_EOL;

			/** Close the Tier 1 link */
			$str .= '</a><!--- .tier-1 -->' . PHP_EOL;

			/** Close the Tier 2 wrap */
			$str .= '</div><!-- tier-2 tier-3 -->' . PHP_EOL;

			/** And extra div. */
			$str .= '</div><!-- .extra -->' . PHP_EOL;

			return $str;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Get Tiers Data
	 *
	 * This will be first extracted from the URI,
	 * From there, the abbreviations, names, title and class will be built.
	 *
	 * @param array $page
	 *
	 * @return array
	 */
	protected function getTiersData( $page )
	{

		/** Tier 0 is the top level (front page only. */
		$data['tier-0'] = $page['front-page'] ? true : false;

		/** Initialize these values to false. */
		$data['tier-1'] = false;
		$data['tier-2'] = false;
		$data['tier-3'] = false;

		/** If it is not the front page, look for the information. */
		if ( ! $page['front-page'] )
		{
			/** Extract the tier information from the URI, if available */
			$tiers = $this->getTiersFromURI( $page['uri'] );

			/** Build Tier One (This is always there). */
			$data['tier-1'] = $this->getTierOneData( $tiers );

			/** Build Tier Two. This may not always be present. */
			$data['tier-2'] = $this->getTierTwoData( $tiers );

			/** Build Tier Three. This may not always be present. */
			$data['tier-3'] = $this->getTierThreeData( $tiers );
		}

		/** Return the data. */
		return $data;
}

	/**
	 * Get Tier One Data.
	 *
	 * The abbreviation comes from the URI. It is $tiers['tier-1'], if it is set.
	 * The name of the tier (usually only one word), is all in lower case.
	 * The *title* of the tier has the first letter of the name capitalized, and
	 * is used as the title for the link for that tier in the header. Finally,
	 * the *class* is the name of the tier (i.e. 'tier-1'), with the name of the
	 * tier appended. This is then used to style the header appropriately and ensure
	 * the right icon is used to help the viewer know where they are on the site.
	 *
	 * @param array $arr
	 *
	 * @return array|bool  class, name OR false.
	 */
		protected function getTierOneData( $tiers )
	{
		if ( ! empty( $tiers['tier-1'] ) )
		{
			$items = get_tier_one_data();

			$tier['get'] = true;
			$tier['tier'] = 'tier-1';
			$tier['abbr'] = $tiers['tier-1'];
			$tier['name'] = $items[ $tier['abbr'] ]['name'];
			$tier['title'] = ucfirst( $tier['name'] );
			$tier['class'] = 'tier-1 ' . $items[ $tiers['tier-1'] ]['name'];
			return $tier;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Get Tier Two Data.
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

	protected function getTierTwoData( $tiers )
	{
		if ( ! empty( $tiers['tier-2'] ) )
		{
			$items = get_tier_two_data();

			if ( isset( $items[ $tiers['tier-2'] ]['name'] ) )
			{
				$tier['get'] = true;
				$tier['tier'] = 'tier-2';
				$tier['abbr'] = $tiers['tier-2'];
				$tier['name'] = $items[ $tiers['tier-2'] ]['name'];
				$tier['class'] = 'tier-2 ' . $tier['name'];
				$tier['title'] = ucfirst( $tier['name'] );
				return $tier;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}

	/**
	 * Get Tier Three Data.
	 *
	 * There is a subtle inheritance happening here, whereby the "who" (say, the carpenter)
	 * inherits the class (the style, the dust and wood chips) from the where (the workshop).
	 * On the one hand, this keeps things simple. We do not need to change the style here, only
	 * the icon. On the other hand, this is how it actually is.
	 *
	 * @param array $arr
	 *
	 * @return array|bool
	 */
	protected function getTierThreeData( $tiers )
	{
		if ( ! empty( $tiers['tier-3'] ) )
		{
			$items = get_tier_three_data();

			if ( isset( $items[ $tiers['tier-3'] ]['name'] ) )
			{
				$tier['get'] = true;
				$tier['tier'] = 'tier-3';
				$tier['abbr'] = $tiers['tier-3'];
				$tier['name'] = $items[ $tiers['tier-3'] ]['name'];
				$tier['class'] = 'tier-3 ' . $tier['name'];
				$tier['title'] = ucfirst( $tier['name'] );
				return $tier;
			}
			else
			{
				return false;
			}
		}
		else
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
	protected function getTiersFromURI( $uri )
	{
		/** Have found nothing yet. Set to null. */
		$tiers['tier-1'] = null;
    $tiers['tier-2'] = null;
    $tiers['tier-3'] = null;

		/** Look for a grouping of three letters, followed by four. */
		$regex = '/\/([a-z]{3})\/([a-z]{4})\/([a-z]{5})\//';
		preg_match( $regex, $uri, $match );

		if ( ! empty( $match ) )
		{
			$tiers['tier-1'] = ! empty( $match[1] ) ? $match[1] : null;
			$tiers['tier-2'] = ! empty( $match[2] ) ? $match[2] : null;
			$tiers['tier-3'] = ! empty( $match[3] ) ? $match[3] : null;
		}
		else
		{
			$regex = '/\/([a-z]{3})\/([a-z]{4})\//';
			preg_match( $regex, $uri, $match );

			if ( ! empty( $match ) )
			{
				$tiers['tier-1'] = ! empty( $match[1] ) ? $match[1] : null;
				$tiers['tier-2'] = ! empty( $match[2] ) ? $match[2] : null;
			}
      /** One last try. */
      else
      {
        $regex = '/\/([a-z]{3})\//';
        preg_match( $regex, $uri, $match );

        if ( ! empty( $match ) )
        {
          $tiers['tier-1'] = ! empty( $match[1] ) ? $match[1] : null;
        }
      }
		}
		return $tiers;
	}
} // End Class.
