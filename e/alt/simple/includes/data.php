<?php

defined( 'SITE' ) || exit;

/**
 * Data.
 */

/**
 * Data.
 */

 /**
  * A flat array of cluster names that we can use for a simple
  * search using in_array(). Including the name here authorizes it.
  * If it is not included, it won't be authorized.
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
