<?php
require_once 'config.php';

if ($_POST['redirect'] == 'k-space' || $_POST['redirect'] == 'wormhole') { 
    $level = filter_input(INPUT_POST, 'level', FILTER_VALIDATE_INT);
    $link = filter_input(INPUT_POST, 'link', FILTER_VALIDATE_INT);
    
    if ($_POST['venture'] == true){
        $frigate = filter_input(INPUT_POST, 'frigate', FILTER_VALIDATE_INT); 
        header("Location: ".BASE_PATH.$_POST['redirect']."/level/".$level."/link/".$link."/frigate/".$frigate); }
    else {
        header("Location: ".BASE_PATH.$_POST['redirect']."/level/".$level."/link/".$link); }
    exit;
}
header("Location: ".BASE_PATH); // default: send to index