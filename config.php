<?php

// config.php - handles all initial configuration, does not produce any output

ob_start("ob_gzhandler");

// These can be found in other projects of mine. Check github.com/blitzmann
require_once '/home/http/lib/class.DB.php';
require_once '/home/http/lib/class.EMDR.php';

define('ABS_PATH', str_replace('\\', '/', dirname(__FILE__)) . '/');
define('BASE_PATH','/'.substr(dirname(__FILE__),strlen($_SERVER['DOCUMENT_ROOT'])).'/');

$DB = new DB(parse_ini_file('/home/http/private/db-eve-odyssey-readonly.ini'));
$emdrVersion = 1;

// No plans to make this configurable as it is in lpStore
$defaultPrefs = array(
    'region'      => 10000002,
    'marketMode'  => 'sell'
);

// END USER CONFIGURATION

$time = explode(' ', microtime());
$start = $time[1] + $time[0];

$page = basename($_SERVER['PHP_SELF']);

function testRegionInput($input) {
    global $defaultPrefs, $regions;
    if (isset($regions[$input])) {
        return (int)$input; }
    return $defaultPrefs['region'];
}    

function testMarketModeInput($input) {
    global $defaultPrefs;
    if ($input == 'sell' || $input == 'buy') {
        return $input; }
    return $defaultPrefs['marketMode'];
}    

$filterArgs = array(
    'region'    => array(
                'filter' => FILTER_CALLBACK,
                'options'=>'testRegionInput'),
    'marketMode' => array(
                'filter' => FILTER_CALLBACK,
                'options'=>'testMarketModeInput'),
);

if (isset($_COOKIE['preferences'])){
	$prefs = filter_var_array(unserialize($_COOKIE['preferences']), $filterArgs); }
else {
	$prefs = $defaultPrefs; }

$emdr  = new EMDR($prefs['region'], $emdrVersion);
$links = array(0,.02,.025); // set multiplier for links

function romanNumerals($num){ 
    if ($num == 0) {
        return "0"; } // TIL there is no Roman Numeral for 0
    $n = intval($num); 
    $res = ''; 
    /*** roman_numerals array  ***/ 
    $roman_numerals = array( 
        'M'  => 1000, 
        'CM' => 900, 
        'D'  => 500, 
        'CD' => 400, 
        'C'  => 100, 
        'XC' => 90, 
        'L'  => 50, 
        'XL' => 40, 
        'X'  => 10, 
        'IX' => 9, 
        'V'  => 5, 
        'IV' => 4, 
        'I'  => 1); 
    foreach ($roman_numerals as $roman => $number){ 
        /*** divide to get  matches ***/ 
        $matches = intval($n / $number); 
        /*** assign the roman char * $matches ***/ 
        $res .= str_repeat($roman, $matches); 
        /*** substract from the number ***/ 
        $n = $n % $number; 
    } 
    /*** return the res ***/ 
    return $res; 
} 

// Set input values
$defaults = '0000000000';
list (
    $ghSkill,
    $boolLinks,
    $linkTech,
    $director,
    $warfare,
    $boolMindlink,
    $capShip,
    $capSkill,
    $implants,
    $ventureSkill) = str_split(trim((isset($_GET['inputs']) ? $_GET['inputs'] : $defaults) ));

/*
** Set variables and do calculations from input
*/

if ($linkTech == 0) { $link = 0; }
else { $link = $links[$linkTech]; }

if ($ghSkill < 5) { // set t1 base values
    $mine_amount = 10;
    $duration    = 30;
}  
else { // set t2 values
    $mine_amount = 20;
    $duration    = 40;
}

// how many GH do we have? If it's a venture, take the min of level or 2 (venture max)
$gh_qty = ($ventureSkill != 0 ? min($ghSkill,2) : $ghSkill);

$dBonuses = array(); // duration bonuses array
// the following relates to setting up link bonuses
if ($boolLinks != 0 && $linkTech != 0) {   
    if ($capShip != 0) {
        $capMultiple = array(1=>3, 2=>10);
        $capBonus = 1+(($capMultiple[$capShip] * $capSkill)/100);
    } else { $capBonus = 1; }
    
    $lBonuses = array($link, (int)$director, (1+($warfare/10)), ($boolMindlink != 0 ? 1.5 : 1), $capBonus);
    array_push($dBonuses, array_product($lBonuses));
}

// venture duration bonuses
if ($ventureSkill != 0) {
    array_push($dBonuses, ($ventureSkill * 0.05)); }

// implant duration bonuses
if ($implants != 0) {
    $impMultiple = array(1=>1, 2=>3, 3=>5);
    array_push($dBonuses, $impMultiple[$implants] / 100); }
    
// sum it all up
foreach ($dBonuses AS $bonus) {
    $duration = $duration - ($duration * $bonus); }

// var_dump($dBonuses); // dump all duration bonuses
   
define("MINE_AMOUNT", $mine_amount);
define("CYCLE_TIME", $duration);
define("LEVEL", $gh_qty);

?>