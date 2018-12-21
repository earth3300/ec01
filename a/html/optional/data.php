<?php
/**
 * EC01 Data (Earth3300\EC01)
 *
 * This data is used to construct the header on the site. There are three tiers.
 * Tier One: Who, What, When, Where, Why and How (and Store and Wiki)
 * Tier Two: Similar groupings for each top level tiers. May be minor variations.
 * Tier Three: Further Detail. May be greater variation at this level.
 * This data file is optional.
 *
 * File: data.php
 * Created: 2018-10-01
 * Update: 2018-12-21
 * Time: 10:35 EST
 */

namespace Earth3300\EC01;

/** No direct access (NDA). */
defined('NDA') || exit('NDA');

/**
 * Get Tier One Data (Three Characters).
 *
 * Who, What, When, Where, How and Why, shortened to:
 * who, wha, whn, whe, how and why.
 *
 * Although each tier one grouping can have about ten tier
 * two groupings, some (many?) may be duplicated at the tier two
 * and tier three levels, with minor variations in wording to reflect
 * the difference between the tier one groupings.
 *
 * @return array
 */
function get_tier_one_data()
{
  $items = [
    'who' => [ 'name' => 'who' ],
    'wha' => [ 'name' => 'what' ],
    'whn' => [ 'name' => 'when' ],
    'whe' => [ 'name' => 'where' ],
    'how' => [ 'name' => 'how' ],
    'why' => [ 'name' => 'why' ],
    'wki' => [ 'name' => 'wiki' ],
    'sto' => [ 'name' => 'store' ],
    'mem' => [ 'name' => 'members' ],
    ];
  return $items;
}

/**
 * Get Tier Two Data (Four Characters).
 *
 * These all are currently placed under the "Where" (whr) directory.
 * Formerly, this was called the "cluster" directory, as that had best
 * defined what that was at that time. However, with the addition of
 * other higher level categories which include: who, what, when, how and
 * why, it seemed best to change this to "where" (or "whe") for consistency.
 *
 * @return array
 */
function get_tier_two_data()
{
  $items = [
    // whe (where)
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

    // nature
    'natu' => [ 'name' => 'nature' ],

    // wha
    'agri' => [ 'name' => 'agriculture' ],
    'bldg' => [ 'name' => 'building' ],
    'equi' => [ 'name' => 'equipment' ],
    'supp' => [ 'name' => 'supply' ],
    'prop' => [ 'name' => 'property' ],
    'reso' => [ 'name' => 'resource' ],
    'stru' => [ 'name' => 'structure' ],
    'info' => [ 'name' => 'information' ],
    'prod' => [ 'name' => 'product' ],
    'desn' => [ 'name' => 'design' ],

    // how
    'stnd' => [ 'name' => 'standard' ],
    'proc' => [ 'name' => 'process' ],
    'mthd' => [ 'name' => 'method' ],
    'proj' => [ 'name' => 'project' ],
    'comm' => [ 'name' => 'communication' ],
    'news' => [ 'name' => 'news' ],
    'serv' => [ 'name' => 'service' ],
    'whsl' => [ 'name' => 'wholesale' ],
    'dsgn' => [ 'name' => 'design' ],
    'powr' => [ 'name' => 'power' ],

    // recycling
    'reco' => [ 'name' => 'recovery' ],

    // why
    'ethc' => [ 'name' => 'ethics' ],
    'sust' => [ 'name' => 'sustainability' ],
    'reln' => [ 'name' => 'religion' ],
    'motv' => [ 'name' => 'motivation' ],

    // wki
    'logc' => [ 'name' => 'logic' ],
    'math' => [ 'name' => 'mathematics' ],
    'comp' => [ 'name' => 'computing' ],
    'phyc' => [ 'name' => 'physics' ],

    'chem' => [ 'name' => 'chemistry' ],
    'biol' => [ 'name' => 'biology' ],
    'syst' => [ 'name' => 'systems' ],
    'cogn' => [ 'name' => 'cognition' ],
  ];
  return $items;
}

/**
 * Get Tier Three Data (Five Characters).
 *
 * There are eight to ten tier two groupings. With about eight
 * tier three groupings per tier two grouping, we have:
 * 10 x 8 = 80. About sixty to eighty tier three groupings.
 *
 * @return array
 */
function get_tier_three_data()
{
  $items = [
    'centr' => [ 'name' => 'center' ],

    // Who
    'chemi' => [ 'name' => 'chemistry', 'who' => 'chemist', ],
    'cogni' => [ 'name' => 'cognition', 'who' => 'psychologist', ],
    'cymat' => [ 'name' => 'cymatics', 'who' => 'cymaticist', ],
    'genet' => [ 'name' => 'genetics', 'who' => 'geneticist', ],
    'geolo' => [ 'name' => 'geology', 'who' => 'geologist', ],
    'ecolo' => [ 'name' => 'ecological', 'who' => 'ecologist', ],
    'logic' => [ 'name' => 'logic', 'who' => 'logician', ],
    'mathe' => [ 'name' => 'mathematics', 'who' => 'mathematician', ],
    'stats' => [ 'name' => 'statistics', 'who' => 'statistician', ],
    'physc' => [ 'name' => 'physics', 'who' => 'physicist', ],

    'archi' => [ 'name' => 'architecture', 'who' => 'architect', ],
    'engin' => [ 'name' => 'engineering', 'who' => 'engineering', ],
    'lands' => [ 'name' => 'landscaping', 'who' => 'landscaper', ],
    'perma' => [ 'name' => 'permaculture', 'who' => 'permaculturist', ],
    'progr' => [ 'name' => 'programming', 'who' => 'programmer', ],
    'robot' => [ 'name' => 'robotics', 'who' => 'roboticist', ],

    'music' => [ 'name' => 'music', 'who' => 'musician', ],
    'paint' => [ 'name' => 'painting', 'who' => 'painter', ],
    'percn' => [ 'name' => 'percussions', 'who' => 'percussionist', ],
    'piano' => [ 'name' => 'piano', 'who' => 'pianist', ],
    'pttry' => [ 'name' => 'pottery', 'who' => 'potter', ],
    'saxop' => [ 'name' => 'saxophone', 'who' => 'saxophonist', ],
    'sclpt' => [ 'name' => 'sculpture', 'who' => 'sculptor', ],
    'violn' => [ 'name' => 'violin', 'who' => 'violinist', ],

    'bakng' => [ 'name' => 'baking', 'who' => '', ],
    'brsta' => [ 'name' => 'barista', 'who' => 'barista', ],
    'clnup' => [ 'name' => 'cleanup', 'who' => 'cleaner', ],
    'cookg' => [ 'name' => 'cooking', 'who' => 'cook', ],
    'recyl' => [ 'name' => 'recycling', 'who' => 'recycler', ],
    'servg' => [ 'name' => 'serving', 'who' => 'server', ],
    'storg' => [ 'name' => 'storage', 'who' => 'storage', ],

    'bodyc' => [ 'name' => 'bodyc', 'who' => 'bodyc', ],
    'cloth' => [ 'name' => 'clothing', 'who' => 'tailor', ],
    'hairc' => [ 'name' => 'haircare', 'who' => 'hairdresser', ],
    'nutri' => [ 'name' => 'nutrition', 'who' => 'nutritionist', ],
    'physi' => [ 'name' => 'physio', 'who' => 'physiotherapist', ],
    'psych' => [ 'name' => 'psyche', 'who' => 'pscyhologist', ],
    'helth' => [ 'name' => 'health', 'who' => 'health-care', ],
    'shoes' => [ 'name' => 'shoes', 'who' => 'shoemaker', ],

    'culti' => [ 'name' => 'cultivating', 'who' => 'cultivator', ],
    'hrvtg' => [ 'name' => 'harvesting', 'who' => 'harvester', ],
    'plntg' => [ 'name' => 'planting', 'who' => 'planter', ],
    'prepg' => [ 'name' => 'preparing', 'who' => 'preparer', ],
    'procg' => [ 'name' => 'processing', 'who' => 'processer', ],
    'weedg' => [ 'name' => 'weeding', 'who' => 'weeder', ],

    'anlys' => [ 'name' => 'analysis', 'who' => 'analyst', ],
    'audio' => [ 'name' => 'audio', 'who' => 'audio-engineer', ],
    'photo' => [ 'name' => 'photography', 'who' => 'photographer', ],
    'presg' => [ 'name' => 'presenting', 'who' => 'presenter', ],
    'video' => [ 'name' => 'video', 'who' => 'videographer', ],
    'writg' => [ 'name' => 'writing', 'who' => 'writer', ],
    'grphc' => [ 'name' => 'graphics', 'who' => 'graphics-designer', ],

    'carpt' => [ 'name' => 'carpentry', 'who' => 'carpenter', ],
    'drywl' => [ 'name' => 'drywall', 'who' => 'drywaller', ],
    'elect' => [ 'name' => 'electrical', 'who' => 'electrician', ],
    'mason' => [ 'name' => 'masonry', 'who' => 'mason', ],
    'mecha' => [ 'name' => 'mechanical', 'who' => 'mechanic', ],
    'plumb' => [ 'name' => 'plumbing', 'who' => 'plumber', ],
    'weldg' => [ 'name' => 'welding', 'who' => 'welder', ],
    'roofg' => [ 'name' => 'roofing', 'who' => 'roofer', ],

    // Wha (What)
    'geolo' => [ 'name' => 'geology', 'who' => 'geologist', ],
    'miner' => [ 'name' => 'mineral', 'who' => 'geologist', ],
    'strat' => [ 'name' => 'strata', 'who' => 'geologist', ],
    'terrn' => [ 'name' => 'terrain', 'who' => 'geologist', ],
    'water' => [ 'name' => 'water', 'who' => 'hydrologist', ],
    'atmos' => [ 'name' => 'atmosphere', 'who' => 'meterologist', ],
    'climt' => [ 'name' => 'climate', 'who' => 'meterologist', ],
    'wther' => [ 'name' => 'weather', 'who' => 'meterologist', ],
    'lakes' => [ 'name' => 'lakes', 'who' => '', ],
    'ecosy' => [ 'name' => 'ecosystem', 'who' => '', ],
    'plant' => [ 'name' => 'plant', 'who' => '', ],
    'insct' => [ 'name' => 'insect', 'who' => '', ],
    'animl' => [ 'name' => 'animal', 'who' => '', ],

    'packs' => [ 'name' => 'packs', 'who' => 'tailor', ],
    'shelt' => [ 'name' => 'shelter', 'who' => 'carpenter', ],
    'sleep' => [ 'name' => 'sleep', 'who' => 'carpenter', ],
    'dwell' => [ 'name' => 'dwelling', 'who' => 'builder', ],
    'wkshp' => [ 'name' => 'workshop', 'who' => 'builder', ],
    'compu' => [ 'name' => 'computing', 'who' => 'programmer', ],

    'agric' => [ 'name' => 'agricultural', 'who' => 'farmer', ],
    'mecha' => [ 'name' => 'mechanical', 'who' => 'mechanic', ],
    'elect' => [ 'name' => 'electrical', 'who' => 'electrician', ],
    'gener' => [ 'name' => 'generator', 'who' => 'electrician', ],

    'fastn' => [ 'name' => 'fasteners', 'who' => 'fasteners', ],
    'sheet' => [ 'name' => 'sheet-goods', 'who' => 'sheet-goods', ],
    'struc' => [ 'name' => 'structural', 'who' => 'structural', ],

    // How (How)
    'softw' => [ 'name' => 'software', 'who' => 'programmer', ],
    'grwth' => [ 'name' => 'growth', 'who' => 'planner', ],
  ];
  return $items;
}
