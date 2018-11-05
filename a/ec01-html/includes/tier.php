<?php

namespace Earth3300\EC01;

defined('NDA') || exit('NDA');

/**
 * EC01 Tiers
 */
class EC01Tiers extends EC01HTML
{

	/**
	 * Get the Sub Header
	 *
	 * @param array $page
	 *
	 * @return string|bool
   */
	protected function getHeaderTiersSub( $page )
	{
		if ( SITE_USE_TIERS )
		{
			if ( defined( 'SITE_USE_HEADER_SUB' )
					&& SITE_USE_HEADER_SUB
					&& ! isset( $page['tiers']['tier-4'] ) )
				{
					$str = $this-> getHeaderTierThree( $page );
				}
				else
				{
					$str = $this-> getHeaderTierFour( $page );
				}
				return $str;
		}
		else {
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
	private function getHeaderTierThree( $page )
	{
		/** We need Tier 4 Information to construct a unique Tier-3/Tier-4 header. */
		if ( isset( $page['tiers']['tier-3'] ) &&  $page['tiers']['tier-3'] )
		{
			$url_tier3 = '/' . $page['tiers']['tier-2'] . '/' . $page['tiers']['tier-3'];

			$str = '<div class="site-header-sub">' . PHP_EOL;

			/** The less specific overlays the more specific to get the effect we want. */
			$str .= sprintf( '<div class="inner">%s', PHP_EOL );

			/** Left div. (Tier 3). */
			$str .= sprintf( '<div class="%s">%s', $page['class']['tier-3'], PHP_EOL );
			$str .= '<div class="color darker">' . PHP_EOL;
			$str .= sprintf( '<a class="level-01 %s" ', $page['class']['tier-3'], PHP_EOL );
			$str .= sprintf( 'href="%s/">', $url_tier3 . SITE_CENTER_DIR );
			$str .= sprintf( '<span class="icon"></span>%s</a>%s', ucfirst( $page['class']['tier-3'] ), PHP_EOL );

			$str .= '</div><!-- .color .darker -->' . PHP_EOL;
			$str .= '</div><!-- .tier-3 -->' . PHP_EOL;
			$str .= SITE_USE_HEADER_SUB ? sprintf('<a href="/%s/" class="%s" title="%s"><span class="tier-2 level-1 icon"></span></a>%s',
				$page['tiers']['tier-2']['abbr'],
				$page['tiers']['tier-2']['class'],
				ucfirst( $page['tiers']['tier-2']['name'] ),
				PHP_EOL
				) : '';
			$str .= '</div><!-- .site-header-sub tier-3 -->' . PHP_EOL;

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
	 * @return string|bool
	 */
	private function getHeaderTierFour( $page )
	{
		/** We need Tier 4 Information to construct a unique Tier-3/Tier-4 header. */
		if ( isset( $page['tiers']['tier-4'] ) &&  $page['tiers']['tier-4'] )
		{
			$url_tier3 = '/' . $page['tiers']['tier-2']['abbr'] . '/' . $page['tiers']['tier-3'];
			$url_tier4 = $url_tier3  . '/' . $page['tiers']['tier-4'];

			$str = '<div class="site-header-sub">' . PHP_EOL;

			/** The less specific overlays the more specific to get the effect we want. */
			$str .= sprintf( '<div class="inner">%s', PHP_EOL );

			/** Left div. (Tier 3). */
			$str .= sprintf( '<div class="%s">%s', $page['class']['tier-3'], PHP_EOL );
			$str .= '<div class="color darker">' . PHP_EOL;
			$str .= sprintf( '<a class="level-01 %s" ', $page['class']['tier-3'], PHP_EOL );
			$str .= sprintf( 'href="%s/">%s', $url_tier3 . SITE_CENTER_DIR, PHP_EOL );
			$str .= '<span class="icon"></span>' . PHP_EOL;
			$str .= sprintf( '<span class="text hide-tablet">%s</span>%s', ucfirst( $page['class']['tier-3'] ), PHP_EOL );
			$str .= '</a><!-- .level-01 -->' . PHP_EOL;

			/** Right div. (Tier 4). Absolute Positioning, within Tier 3. */
			$str .= sprintf( '<div class="level-02 right absolute %s">%s', $page['class']['tier-4'], PHP_EOL );
			$str .= sprintf( '<div class="color lighter">%s', PHP_EOL );
			$str .= '<div class="header-height">' . PHP_EOL;

			$str .= sprintf( '<a href="%s/">%s', $url_tier4, PHP_EOL );
			$str .= '<span class="icon icon-height"></span>' . PHP_EOL;
			$str .= sprintf( '<span class="text hide-phone">%s</span>%s',
							ucfirst( $page['tier-4']['title'] ), PHP_EOL );
			$str .= '</a><!-- .tier-4 -->' . PHP_EOL;
			$str .= '</div><!-- .header-height -->' . PHP_EOL;
			$str .= '</div><!-- .color .lighter -->' . PHP_EOL;
			$str .= '</div><!-- .inner -->' . PHP_EOL;
			$str .= '</div><!-- .color .darker -->' . PHP_EOL;
			$str .= '</div><!-- .tier-3 -->' . PHP_EOL;
			$str .= sprintf('<a href="/%s/" class="%s" title="%s">%s',
				$page['tiers']['tier-2']['abbr'],
				$page['tiers']['tier-2']['class'],
				ucfirst( $page['tiers']['tier-2']['name'] ), PHP_EOL );
				$str .= '<span class="tier-2 level-1 icon"></span>' . PHP_EOL;
				$str .= '</a><!--- .tier-2 -->' . PHP_EOL;
			$str .= '</div><!-- .site-header-sub tier-3 tier-4 -->' . PHP_EOL;
			$str .= '</div><!-- .extra -->' . PHP_EOL;

			return $str;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Get Tier Two.
	 *
	 * @param array $arr
	 *
	 * @return array|bool  class, name OR false.
	 */
	protected function getUriTierTwo( $arr )
	{
		$items = get_tier_two_data();
		if ( ! empty( $arr['tier-2'] ) )
		{
			$tier['class'] = 'tier-2 ' . $items[ $arr['tier-2'] ]['name'];
			$tier['name'] = $items[ $arr['tier-2'] ]['name'];
			$tier['abbr'] = $arr['tier-2'];
			return $tier;
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

	protected function getUriTierThree( $arr )
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
	protected function getUriTierFour( $page )
	{
		$items = get_tier_four_data();

		if ( 'who' == $page['tiers']['tier-2'] )
		{
			{
				if ( isset( $items[ $page['tiers']['tier-4'] ]['who'] ) )
				{
					$arr['title'] = $items[ $page['tiers']['tier-4'] ]['who'];
				}
			}
		}

		if ( ! empty( $page['tiers']['tier-4'] ) )
		{
			$arr['class'] = isset( $items[ $page['tiers']['tier-4'] ]['name'] ) ? $items[ $page['tiers']['tier-4'] ]['name'] : '';

			if ( ! isset( $arr['title'] ) )
			{
				$arr['title'] = $arr['class'];
			}

		}
		else
		{
			$arr['class'] = null;
			$arr['title'] = null;
		}
		return $arr;
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
	protected function getUriTiers( $uri )
	{
		/** Have found nothing yet. */
		$tiers = false;

		/** Look for a grouping of three letters, followed by four. */
		$regex = '/\/([a-z]{3})\/([a-z]{4})\/([a-z]{5})\//';
		preg_match( $regex, $uri, $match );

		if ( ! empty( $match ) )
		{
			$tiers['tier-2'] = ! empty( $match[1] ) ? $match[1] : null;
			$tiers['tier-3'] = ! empty( $match[2] ) ? $match[2] : null;
			$tiers['tier-4'] = ! empty( $match[3] ) ? $match[3] : null;
		}
		else
		{
			$regex = '/\/([a-z]{3})\/([a-z]{4})\//';
			preg_match( $regex, $uri, $match );

			if ( ! empty( $match ) )
			{
				$tiers['tier-2'] = ! empty( $match[1] ) ? $match[1] : null;
				$tiers['tier-3'] = ! empty( $match[2] ) ? $match[2] : null;
			}
		}
		return $tiers;
	}
}
