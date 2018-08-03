<?php

/**
 * Use this to add an html class based on an authorized cluster name.
 * That is, we do not want this to be *too* flexible, we want
 * to treat it as a concrete structure would be treated. We *can* move
 * it, but not too frequently. Thus we need to think about it carefully,
 * authorize it, and *then* use it, only if it matches.
 *
 */

 /**
  * A flat array of cluster names that we can use for a simple
  * search using in_array(). Including the name here authorizes it.
  * If it is not included, it won't be authorized.
  */
function get_clusters(){
	$arr = [
		'academic',
		'art',
		'trade',
		'applied',
		'gardening',
		'care',
		'cafe',
		'monitoring',
		'analysis',
	];
	return $arr;
}

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

function analyze_uri_for_cluster( $uri ){
	$clusters = get_clusters();
	$cluster = maybe_get_cluster_from_uri( $uri );
	if ( in_array( $cluster, $clusters ) ){
		return $cluster;
	} else {
		return false;
	}
}

function analyze_uri_for_sub_cluster( $uri ){
	$search = 'cluster'; // SITE_CLUSTER_DIR;
	if ( strpos( $uri, $search ) !== FALSE ) {
		//if 'cluster' is present, get word directly after
		$uri = trim( $uri, '/' );
		$ex = explode( '/', $uri );
		$i = 1;
		if ( $search == $ex[1] && isset( $ex[3] ) ) {
			$sub_cluster = $ex[3];
		}	else if ( $search == $ex[0] && isset( $ex[2])  ) {
			$sub_cluster = $ex[2];
		}
		return $sub_cluster;
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
 */
function maybe_get_cluster_from_uri( $uri ){
	//pattern: /1/cluster/academic
	$search = 'cluster'; // SITE_CLUSTER_DIR;
	if ( strpos( $uri, $search ) !== FALSE ) {
		//if 'cluster' is present, get word directly after
		$uri = trim( $uri, '/' );
		$ex = explode( '/', $uri );
		$i = 1;
		if ( $search == $ex[1] && isset( $ex[2] ) ) {
			$cluster = $ex[2];
		}	else if ( $search == $ex[0] && isset( $ex[1])  ) {
			$cluster = $ex[1];
		}
		return $cluster;
	} else {
		return false;
	}
}
