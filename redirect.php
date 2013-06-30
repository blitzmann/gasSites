<?php
require_once 'config.php';

if ($_POST['redirect'] == 'k-space' || $_POST['redirect'] == 'wormhole') { 

    /*
        Input var (0 indexed):
            0 - GH skill level
            1 - Links (bool)
            2 - link type
            3 - director skill
            4 - warfare skill
            5 - Mindlink (bool)
            6 - Capital ship (0 for none)
            8 - ship skill level
            9 - implant (0 for none)
            10 - venture (bool)
            11 - frigate skill level
    */
    
    $inputs = array (
        filter_input(INPUT_POST, 'ghSkill', FILTER_VALIDATE_INT),
        ($_POST['boolLinks'] == true ? 1 : 0),
        filter_input(INPUT_POST, 'linkTech', FILTER_VALIDATE_INT),
        filter_input(INPUT_POST, 'director', FILTER_VALIDATE_INT),
        filter_input(INPUT_POST, 'warfare', FILTER_VALIDATE_INT),
        ($_POST['boolMindlink'] == true ? 1 : 0),
        ($_POST['capital'] == true ?  filter_input(INPUT_POST, 'capShip', FILTER_VALIDATE_INT) : 0),
        filter_input(INPUT_POST, 'capSkill', FILTER_VALIDATE_INT),
        ($_POST['implants'] == true ?  filter_input(INPUT_POST, 'ghImplant', FILTER_VALIDATE_INT) : 0),
        filter_input(INPUT_POST, 'ventureSkill', FILTER_VALIDATE_INT)
    );

    header("Location: ".BASE_PATH.$_POST['redirect']."/".implode($inputs,'')."/#gasSites");
    
    exit;
}
header("Location: ".BASE_PATH); // default: send to index