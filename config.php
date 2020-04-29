<?php

// config.php - handles all initial configuration, does not produce any output

# @todo: for links, if we are not using any, disable all other options for links
# @todo: venture selects itself after submission
# @todo: t2 links requires Director 5
# @todo: seperate gas sites into 2 tables, supply extra info such as space that it can be found in

ob_start("ob_gzhandler");

// These can be found in other projects of mine. Check github.com/blitzmann
require_once 'lib/class.DB.php';

define('ABS_PATH', str_replace('\\', '/', dirname(__FILE__)) . '/');
define('BASE_PATH',str_replace(DIRECTORY_SEPARATOR, "/", substr(dirname(__FILE__),strlen($_SERVER['DOCUMENT_ROOT'])).'/'));

# Switch between SQLite and database service 
//$DB = new DB(parse_ini_file('/home/http/private/db-eve-odyssey-write.ini'));
$DB = new DB(array('dsn'=>'sqlite:inc/gasSites.db', 'uname'=>null, 'passwd'=>null));

// END USER CONFIGURATION

$time = explode(' ', microtime());
$start = $time[1] + $time[0];

$page        = basename($_SERVER['PHP_SELF']);
$links       = array(0, .02, .025);              # link multiplier
$impMultiple = array(1=>1, 2=>3, 3=>5);          # implant multiplier

function romanNumerals($num){ 
    if ($num == 0) {
        return "0"; } # TIL there is no Roman Numeral for 0
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

# Set input values
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
    $ventureSkill) = str_split(trim((isset($_GET['inputs']) && !empty($_GET['inputs']) ? $_GET['inputs'] : $defaults) ));

if (isset($_GET['debug'])) {
    DEFINE("DEBUG", true); }
else {
    DEFINE("DEBUG", false); }

###########################
# Set variables and do calculations from input
###########################

if ($linkTech == 0) { $link = 0; }
else { $link = $links[$linkTech]; }

if ($ghSkill < 5) { # set t1 base values
    $mine_amount = 10;
    $duration    = 30;
}  
else { # set t2 values
    $mine_amount = 20;
    $duration =   40;
}

# Question: Since I always forget, why do we not calculate based off of Mining Foreman Skill?
# Answer:   Mining Foreman skill does nothing for gas harvester. Links only affect duration

# How many GH do we have? If it's a venture, take the min of level or 2 (venture max)
$gh_qty = ($ventureSkill != 0 ? min($ghSkill,2) : $ghSkill);

$dBonuses = array(); // duration bonuses array
$lBonuses = array(); // queue of bonuses caused by links

# the following relates to setting up link bonuses
if ($boolLinks != 0 && $linkTech != 0) {   
    if ($capShip != 0) {
        $capMultiple = array(1=>3, 2=>10);
        $capBonus = 1+(($capMultiple[$capShip] * $capSkill)/100);
    } else { $capBonus = 1; }
    
    $lNames   = array ('base','directorSkill','specialistSkill', 'mindlink', 'capital');
    $lBonuses = array_combine($lNames, array(
                                            $link, 
                                            (1+(($director*2)/10)), 
                                            (1+($warfare/10)), 
                                            ($boolMindlink != 0 ? 1.25 : 1), 
                                            $capBonus));
    
    # take the product of link bonuses and apply to main bonus array
    $dBonuses['resultantLinks'] = array_product($lBonuses); 
}

# venture duration bonuses
if ($ventureSkill != 0) {
    $dBonuses['venture'] = ($ventureSkill * 0.05); }

# implant duration bonuses
if ($implants != 0) {
   $dBonuses['implant'] = $impMultiple[$implants] / 100; }
    
# sum up the duration modifiers
$dResultant = $duration;
foreach ($dBonuses AS $bonus) { $dResultant = $dResultant - ($dResultant * $bonus); }    
 
define("MINE_AMOUNT", ($ventureSkill == 0 ? $mine_amount : $mine_amount*2));
define("CYCLE_TIME", $dResultant);
define("LEVEL", $gh_qty);

?>