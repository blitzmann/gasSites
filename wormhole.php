<?php require_once 'config.php'; $title='Wormhole'; require_once 'form.php';

if (isset($_GET['inputs']) && !empty($_GET['inputs'])) {
    
    $results = $DB->qa("
        SELECT g.*, a.volume, a.typeName, b.volume AS volume2, b.typeName AS typeName2
        FROM gasSites g 
        INNER JOIN invTypes a ON (g.typeID = a.typeID) 
        INNER JOIN invTypes b ON (g.typeID2 = b.typeID) 
        WHERE typeID2 is not NULL", array());

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
    
     foreach ($results AS $data) {
        $info = array();
        
        // info : type key, quantity, sell amount, total quantity of gas, #of cycles 
        $price = json_decode($emdr->get($data['typeID']), true);
        $info[1] = array(
            $data['typeID'], 
            $data['qty'], 
            $price['orders']['sell'][0], 
            $data['volume'] * $data['qty'],
            floor((($data['volume'] * $data['qty'])/MINE_AMOUNT)/LEVEL)
        );
  
        $price = json_decode($emdr->get($data['typeID2']), true);
        $info[2] = array(
            $data['typeID2'], 
            $data['qty2'], 
            $price['orders']['sell'][0], 
            $data['volume2'] * $data['qty2'],
            floor((($data['volume2'] * $data['qty2'])/MINE_AMOUNT)/LEVEL)
        );

        $profit = ($info[1][1] * $info[1][2]) + ($info[2][1] * $info[2][2]);
        $cycles = floor((($info[1][3] + $info[2][3])/MINE_AMOUNT)/LEVEL);
        
        echo "<tr>
            <td rowspan='2'>".$data['name']."</td>
            <td>".$data['typeName']."</td>
            <td>".$info[1][1]."</td>
            <td>".number_format($info[1][2])/*sell*/."</td>
            <td>".number_format($info[1][1] * $info[1][2])."</td>
            <td>".number_format($info[1][3])."</td>
            <td>".$info[1][4]."</td>
            <td>".round((($info[1][4] * CYCLE_TIME)/60)/60, 2)."</td>
            <td>".number_format(($info[1][1] * $info[1][2]) / ((($info[1][4] * CYCLE_TIME)/60)/60))."</td>
            </tr>
            <tr>
            <td>".$data['typeName2']."</td>
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