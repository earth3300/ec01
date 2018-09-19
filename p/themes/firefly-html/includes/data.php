<?php

defined( 'SITE' ) || exit;

/**
 * Get Tier Two Data.
 *
 * Who, What, When, Where, How and Why, shortened to:
 *
 * who, wha, whn, whe, how and why.
 *
 * @return array
 */
function get_tier_two_data(){
	$arr = [
		'who' => [ 'name' => 'who' ],
		'wha' => [ 'name' => 'what' ],
		'whn' => [ 'name' => 'when' ],
		'whe' => [ 'name' => 'where' ],
		'how' => [ 'name' => 'how' ],
		'why' => [ 'name' => 'why' ],
		];
	return $arr;
}

/**
 * Get Tier Three Data.
 *
 * These all are currently placed under the "Where" (whr) directory.
 * Formerly, this was called the "cluster" directory, as that had best
 * defined what that was at that time. However, with the addition of
 * other higher level categories which include: who, what, when, how and
 * why, it seemed best to change this to "where" (or "whr") for consistency.
 *
 * @return array
 */
function get_tier_three_data(){
	$arr = [
		'acad' => [ 'name' => 'academic' ],
		'arts' => [ 'name' => 'arts' ],
		'trad' => [ 'name' => 'trade' ],
		'appl' => [ 'name' => 'applied' ],
		'gard' => [ 'name' => 'gardening' ],
		'care' => [ 'name' => 'care' ],
		'cafe' => [ 'name' => 'cafe' ],
		'moni' => [ 'name' => 'monitoring' ],
		'anal' => [ 'name' => 'analysis' ],
		'natu' => [ 'name' => 'nature' ],
	];
	return $arr;
}

/**
 * Get Tier Four Data
 *
 * @return array
 */
function get_tier_four_data(){
	$arr = [
		'centr' => [ 'name' => 'center' ],

		'chemi' => [ 'name' => 'chemistry' ],
		'cogni' => [ 'name' => 'cognition' ],
		'cymat' => [ 'name' => 'cymatics' ],
		'genet' => [ 'name' => 'genetics' ],
		'geolo' => [ 'name' => 'geolo' ],
		'logic' => [ 'name' => 'logic' ],
		'mathe' => [ 'name' => 'mathematics' ],
		'physc' => [ 'name' => 'physics' ],

		'archi' => [ 'name' => 'architecture' ],
		'engin' => [ 'name' => 'engineering' ],
		'lands' => [ 'name' => 'landscaping' ],
		'perma' => [ 'name' => 'permaculture' ],
		'progr' => [ 'name' => 'programming' ],
		'robot' => [ 'name' => 'robotics' ],

		'music' => [ 'name' => 'music' ],
		'paint' => [ 'name' => 'painting' ],
		'percn' => [ 'name' => 'percussions' ],
		'piano' => [ 'name' => 'piano' ],
		'pttry' => [ 'name' => 'pottery' ],
		'saxop' => [ 'name' => 'saxophone' ],
		'sclpt' => [ 'name' => 'sculpture' ],
		'violn' => [ 'name' => 'violin' ],

		'bakng' => [ 'name' => 'baking' ],
		'brsta' => [ 'name' => 'barista' ],
		'clnup' => [ 'name' => 'cleanup' ],
		'cookg' => [ 'name' => 'cooking' ],
		'recyl' => [ 'name' => 'recycling' ],
		'servg' => [ 'name' => 'serving' ],
		'storg' => [ 'name' => 'storage' ],

		'bodie' => [ 'name' => 'baking' ],
		'cloth' => [ 'name' => 'barista' ],
		'hairc' => [ 'name' => 'haircare' ],
		'ntrtn' => [ 'name' => 'nutrition' ],
		'physo' => [ 'name' => 'physio' ],
		'psych' => [ 'name' => 'psyche' ],
		'skinc' => [ 'name' => 'skincare' ],

		'culti' => [ 'name' => 'cultivating' ],
		'hrvtg' => [ 'name' => 'harvesting' ],
		'plntg' => [ 'name' => 'planting' ],
		'prepg' => [ 'name' => 'preparing' ],
		'procg' => [ 'name' => 'processing' ],
		'weedg' => [ 'name' => 'weeding' ],

		'anlys' => [ 'name' => 'analysis' ],
		'audio' => [ 'name' => 'audio' ],
		'photo' => [ 'name' => 'photography' ],
		'presg' => [ 'name' => 'presenting' ],
		'video' => [ 'name' => 'video' ],
		'writg' => [ 'name' => 'writing' ],

		'carpt' => [ 'name' => 'carpentry' ],
		'drywl' => [ 'name' => 'drywall' ],
		'elect' => [ 'name' => 'electrical' ],
		'mason' => [ 'name' => 'mason' ],
		'mecha' => [ 'name' => 'mechanical' ],
		'plumb' => [ 'name' => 'plumb' ],
		'weldg' => [ 'name' => 'welding' ],
		'roofg' => [ 'name' => 'roofing' ],
	];
	return $arr;
}
