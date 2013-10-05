<?php require_once 'config.php'; $title='Wormhole'; require_once 'form.php';

if (isset($_GET['inputs']) && !empty($_GET['inputs'])) {
    
    $gas = array(
        30375 => array("Fullerite-C28",  2),
        30376 => array("Fullerite-C32",  5),
        30377 => array("Fullerite-C320", 5),
        30370 => array("Fullerite-C50",  1),
        30378 => array("Fullerite-C540", 10),
        30371 => array("Fullerite-C60",  1),
        30372 => array("Fullerite-C70",  1),
        30373 => array("Fullerite-C72",  2),
        30374 => array("Fullerite-C84",  2)
    );

    $ladar = array(
        "Barren"       => array(30370 => 3000, 30371 => 1500),
        "Token"	       => array(30371 => 3000, 30372 => 1500), 
        "Minor"        => array(30372 => 3000, 30373 => 1500),
        "Ordinary"     => array(30373 => 3000, 30374 => 1500),
        "Sizable"      => array(30374 => 3000, 30370 => 1500),
        "Bountiful"    => array(30375 => 5000, 30376 => 1000),
        "Vast"         => array(30375 => 1000, 30376 => 5000),
        "Vital"        => array(30377 => 500,  30378 => 6000),
        "Instrumental" => array(30378 => 500,  30377 => 6000)
    );

    echo"
    <hr id='gasSites' />
    <table id='siteTable' class='table table-bordered table-striped'>
    <thead>
        <tr>
        <th>Site</th>
        <th>Gas</th>
        <th>Gas Quantity</th>
        <th>Gas Sell Price</th>

        <th>Total Profit*</th>
        <th>Total m3</th>
        <th># of cycles</th>
        <th># hours</th>
        <th>ISK/HOUR</th>
        </tr>
    </thead>
    <tbody>";

    foreach ($ladar AS $site => $data) {
        $info = array();
        
        // info : type key, quantity, sell amount, total quantity of gas, #of cycles
        reset($data); 
            $price = json_decode($emdr->get(key($data)), true);
            $info[1] = array(
                key($data), 
                current($data), 
                $price['orders']['sell'][0], 
                $gas[key($data)][1] * current($data),
                floor((($gas[key($data)][1] * current($data))/MINE_AMOUNT)/LEVEL)
                );
        end($data);   
            $price = json_decode($emdr->get(key($data)), true);
            $info[2] = array(
                key($data), 
                current($data), 
                $price['orders']['sell'][0], 
                $gas[key($data)][1] * current($data),
                floor((($gas[key($data)][1] * current($data))/MINE_AMOUNT)/LEVEL)
                );

        $profit = ($info[1][1] * $info[1][2]) + ($info[2][1] * $info[2][2]);
        $cycles = floor((($info[1][3] + $info[2][3])/MINE_AMOUNT)/LEVEL);
        
        echo "<tr>
            <td rowspan='2'>$site</td>
            <td>".$gas[$info[1][0]][0]."</td>
            <td>".$info[1][1]."</td>
            <td>".number_format($info[1][2])/*sell*/."</td>
            <td>".number_format($info[1][1] * $info[1][2])."</td>
            <td>".number_format($info[1][3])."</td>
            <td>".$info[1][4]."</td>
            <td>".round((($info[1][4] * CYCLE_TIME)/60)/60, 2)."</td>
            <td>".number_format(($info[1][1] * $info[1][2]) / ((($info[1][4] * CYCLE_TIME)/60)/60))."</td>
            </tr>
            <tr>
            <td>".$gas[$info[2][0]][0]."</td>
            <td>".$info[2][1]."</td>
            <td>".number_format($info[2][2])."</td>
            <td>".number_format($info[2][1] * $info[2][2])."</td>
            <td>".number_format($info[2][3])."</td>
            <td>".$info[2][4]."</td>
            <td>".round((($info[2][4] * CYCLE_TIME)/60)/60, 2)."</td>
            <td>".number_format(($info[2][1] * $info[2][2]) / ((($info[2][4] * CYCLE_TIME)/60)/60))."</td>
            </tr>";

    }
    echo "</tbody></table><small>* Estimated via EMDR network using The Forge sell data. Please see <a href='https://github.com/blitzmann/emdr-py'>emdr-py</a> for more details.</small>";
}
require 'foot.php';
?>