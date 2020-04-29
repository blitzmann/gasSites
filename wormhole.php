<?php require_once 'config.php'; $title='Wormhole'; require_once 'form.php';

function get_data($url, $post) {
	$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_USERAGENT,'ladar thingy');
	curl_setopt($ch,CURLOPT_POSTFIELDS,$post);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}

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
    
    $types = implode(
                array_unique(
                    array_merge(
                        array_map(function($obj) { return $obj['typeID']; }, $results),
                        array_map(function($obj) { return $obj['typeID2']; }, $results)
                    )
                ), 
                "&typeid="
            ) ;
    $fields = "typeid=$types&usesystem=30000142";
    $data = get_data('https://api.evemarketer.com/ec/marketstat', $fields);
    $xml = new SimpleXMLElement($data);   
    // print_r($xml);

     foreach ($results AS $data) {
        $info = array();
        
        // info : type key, quantity, sell amount, total quantity of gas, #of cycles 
        $price = $xml->xpath('/exec_api/marketstat/type[@id="'.$data['typeID'].'"]/sell/min'); 

        $info[1] = array(
            $data['typeID'], 
            $data['qty'], 
            (float)$price[0], 
            $data['volume'] * $data['qty'],
            floor((($data['volume'] * $data['qty'])/MINE_AMOUNT)/LEVEL)
        );
  
        $price = $xml->xpath('/exec_api/marketstat/type[@id="'.$data['typeID2'].'"]/sell/min'); 
        
        $info[2] = array(
            $data['typeID2'], 
            $data['qty2'], 
            (float)$price[0], 
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
    echo "</tbody></table><small>* Estimated via https://evemarketer.com/ using Jita as the system</small>";
}

require 'foot.php';
?>