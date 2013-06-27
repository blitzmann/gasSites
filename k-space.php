<?php require 'config.php'; $title='K-Space'; require 'head.php';

if (isset($_GET['level'])) {

    // $link = multiple to reduce cycle time
    if (!isset($_GET['link'])) { $link = 0; }
    else { $link = $links[$_GET['link']]; }
    
    $venture = isset($_GET['frigate']);
    if ($venture) {
        $frigate = (int)$_GET['frigate']; }

    if ($_GET['level'] < 5) { // set t1 values
        $mine_amount = 10;
        $duration    = 30;
    }  

    else { // set t2 values
        $mine_amount = 20;
        $duration    = 40;
    }

    // how many GH do we have? If it's a venture, take the min of level or 2 (venture max)
    $gh_qty = ($venture ? min((int)($_GET['level']),2):(int)($_GET['level']));
    
    if ($venture) {
        $mine_amount = $mine_amount * 2;  // 100% bonus
        $duration    = $duration - ($duration * ($frigate * 0.05)); // mining frigate modifier
    }

    $duration = $duration - ($duration * $link); // link modifier
   
    define("MINE_AMOUNT", $mine_amount);
    define("CYCLE_TIME", $duration);
    define("LEVEL", $gh_qty);
   
    $results = $DB->qa("SELECT a.*, b.volume, b.typeName FROM gasSites a INNER JOIN invTypes b ON (a.typeID = b.typeID) ORDER BY a.name ASC", array());

    echo "
    <hr />
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

    foreach ($results AS $data) {
        $price = json_decode($emdr->get($data['typeID']), true);
        // {"orders": {"sell": ["19986.52", 88955],

        $profit = ($data['qty'] * $price['orders']['sell'][0]);
        $cycles = floor((($data['qty'] * $data['volume'])/MINE_AMOUNT)/LEVEL);
        
        echo "<tr>
            <td>".$data['name']."</td>
            <td>".$data['typeName']."</td>
            <td>".$data['qty']."</td>
            <td>".number_format($price['orders']['sell'][0])." ISK</td>
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