<?php require_once 'config.php'; $title='K-Space'; require_once 'form.php';

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
    // @todo: fix the null portion here
    $results = $DB->qa("
        SELECT a.*, b.volume, b.typeName 
        FROM gasSites a 
        INNER JOIN invTypes b ON (a.typeID = b.typeID) 
        WHERE typeID2 = '' OR typeID2 is NULL
        ORDER BY a.name ASC", array());

    echo "
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
    <tbody>
    ";

    $types = implode(
                array_unique(
                    array_map(function($obj) { return $obj['typeID']; }, $results),
                ),
                "&typeid="
            ) ;
    $fields = "typeid=$types&usesystem=30000142";
    $data = get_data('https://api.evemarketer.com/ec/marketstat', $fields);
    $xml = new SimpleXMLElement($data);   
    // print_r($xml);

    foreach ($results AS $data) {
        $price = $xml->xpath('/exec_api/marketstat/type[@id="'.$data['typeID'].'"]/sell/min'); 
        $price = (int)$price[0];
        // {"orders": {"sell": ["19986.52", 88955],

        $profit = ($data['qty'] * $price);
        $cycles = floor((($data['qty'] * $data['volume'])/MINE_AMOUNT)/LEVEL);
        
        echo "<tr>
            <td>".$data['name']."</td>
            <td>".$data['typeName']."</td>
            <td>".$data['qty']."</td>
            <td>".number_format($price)." ISK</td>
            <td>".number_format($profit)."</td>
            <td>".number_format($data['qty'] * $data['volume'])."</td>
            <td>".$cycles."</td>
            <td>".round((($cycles * CYCLE_TIME)/60)/60, 2)."</td>
            <td>".number_format($profit / ((($cycles * CYCLE_TIME)/60)/60))."</td>
            </tr>
            ";
    }
    echo "</tbody></table><small>* Estimated via EMDR network using The Forge sell data. Please see <a href='https://github.com/blitzmann/emdr-py'>emdr-py</a> for more details.</small>";


}
require 'foot.php';
?>