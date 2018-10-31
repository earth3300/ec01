<?php

namespace Earth3300\EC01;

defined( 'NDA' ) || exit;

/**
 * EC01 Tiers
 */
class EC01Tiers extends EC01HTML{

	/**
	 * Get Tier Two.
	 *	 *
	 * @param array $arr
	 *
	 * @return array|bool
	 */

	protected function getUriTierTwo( $arr )
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
		/** Look for a grouping of three letters, followed by four. */
		$regex = '/\/([a-z]{3})\/([a-z]{4})\/([a-z]{5})\//';
		preg_match( $regex, $uri, $match );

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
}
