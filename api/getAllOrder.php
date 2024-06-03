

<?php

// require(".././includes/conf.php");

// header('Content-Type: application/json');





$locations = $db->select('select * from `locations` where 1');
$computedLocation = array();
foreach ($locations as $key => $value) {
    $computedLocation[$value['location_id']] = $value;
}

$orders = $db->select('select * from `rde_messages` where 1 ');
for ($j = 0; $j < count($orders); $j++) {
    $checkPoints = $db->select('select * from `check_points` where `RDE_message_id` =  ' . $orders[$j]['id']);
    for ($i = 0; $i < count($checkPoints); $i++) {
        $checkPoints[$i]['locationDetails'] = $computedLocation[$checkPoints[$i]['current_location']];
    }
    $orders[$j]['check_points'] = $checkPoints;
}

response('Order List', 200, $orders);
die;

?>





