<?php

// config.php - handles all initial configuration, does not produce any output

ob_start("ob_gzhandler");
//die('LP store down for database update.     Check back in a few minutes.');
require_once '/home/http/lib/class.DB.php';
require_once '/home/http/lib/class.EMDR.php';

define('ABS_PATH', str_replace('\\', '/', dirname(__FILE__)) . '/');
define('BASE_PATH','/'.substr(dirname(__FILE__),strlen($_SERVER['DOCUMENT_ROOT'])).'/');

$DB = new DB(parse_ini_file('/home/http/private/db-eve-odyssey-dev.ini'));
$emdrVersion = 1;

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
$links = array(0,.02,.025);

?>
    