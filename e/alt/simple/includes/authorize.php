<?php

defined( 'SITE' ) || exit;

/**
 * Use this to add an html class based on an authorized cluster name.
 * That is, we do not want this to be *too* flexible, we want
 * to treat it as a concrete structure would be treated. We *can* move
 * it, but not too frequently. Thus we need to think about it carefully,
 * authorize it, and *then* use it, only if it matches.
 *
 */


/**
 * 1. Get the clusters.
 * 2. Check the uri and get the word directly
 * after the word 'cluster' (this can be changed).
 * 3. Check to see if that word is in the authorized
 * list of clusters. If it is, return it.
 * It can then be used as, for example, an html class.
 * The idea is to give earch cluster a unique color so that this
 * can be used to quickly identify the section one is in. These are largely chosen already.
 */

function analyze_uri_for_cluster( $arr ){
	$items = get_tier_three_data();
	if ( ! empty( $arr['four'] ) ) {
		return $items[ $arr['four'] ]['name'];
	} else {
		return false;
	}
}

function analyze_uri_for_sub_cluster( $arr ){
	$items = get_tier_four_data();
	if ( ! empty( $arr['five'] ) ) {
		return $items[ $arr['five'] ]['name'];
	} else {
		return false;
	}
}

/**
 * This searches for the term only in the first two locations
 * in the uri. This is where we expect it. If it is too far
 * from this, we may not be that interested, as something is
 * wrong with the directory structure then. We want to keep it
 * simple and compact.
 * This finds the position of the word 'cluster' and then returns
 * the word directly after it, whatever it is (if present).
 *
 * @example /whr/acad/
 * @example /wha/bldg/
 */
function get_uri_parts( $uri ){

	/** Look for a grouping of three letters, followed by four. */
	$regex = '/\/([a-z]{3})\/([a-z]{4})\/([a-z]{5})\//';
	preg_match( $regex, $uri, $match );

	if ( ! empty( $match ) ){
		$arr['three'] = ! empty( $match[1] ) ? $match[1] : null;
		$arr['four'] = ! empty( $match[2] ) ? $match[2] : null;
		$arr['five'] = ! empty( $match[3] ) ? $match[3] : null;
		return $arr;
	}
	else {
		return false;
	}
}
