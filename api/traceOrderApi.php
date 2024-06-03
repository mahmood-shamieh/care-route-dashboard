

<?php





$insertionArr = array();

foreach ($data as $key => $value) {

    if ($value != null && strcmp($value, 'null') != 0) {

        $insertionArr[$key] = $db->sqlsafe($value);
    } else {

        $insertionArr[$key] = 'null';
    }
}





$date = new DateTime(); // create a DateTime object with the current date and time

$currentDate = $date->format('Y-m-d H:i:s'); // format the date and time

$order = $db->select('select * from `rde_messages` where `order_number` = ' . $insertionArr['order_number'])[0];
$users = $db->select('select * from `users` where `status` = 1');
$locations = $db->select('select * from `locations` where `active` = 1');
$temp = array();
for ($i = 0; $i < count($locations); $i++) {
    $temp[$locations[$i]['location_id']] = $locations[$i];
}
$locations = $temp;

$routes = $db->select('select * from `routes_check_points` where `route_id` = ' . $order['medication_route_id'] . ' order by `order_number` asc');

$checkPoints = $db->select('select * from `check_points` where `RDE_message_id` = ' . $order['id'] . ' order  by `creation_date` desc');

$newCheckPoint;

if (count($checkPoints) == 0) {

    $newCheckPoint = array(

        'start_date' => $db->sqlsafe($currentDate),

        'end_date' => $db->sqlsafe($currentDate),

        'current_location' => $db->sqlsafe($routes[0]['location_id']),

        'routes_check_points_id' => $db->sqlsafe($routes[0]['id']),

        'check_in_or_check_out_status' => 0,

        'RDE_message_id' => $db->sqlsafe($order['id']),

        'creation_date' => $db->sqlsafe($currentDate),
        'user_id' => $insertionArr['user_id'],

    );

    $db->insert('check_points', $newCheckPoint);
    for ($i = 0; $i < count($users); $i++) {
        if ($routes[0]['location_id'] == $users[$i]['location_id']) {

            $firebase->sendNotification($users[$i]['fcm_token'], 'Order Tracking', 'Order ' . $order['id'] . ' is being tracked and the current order status is: ' . $locations[$routes[0]['location_id']]['check_in_status'] . '.');
        }
    }



    response('New Check Point Has Been Added', 200, null);
    die;
} else {

    $newCheckPoint = array(

        'start_date' => $db->sqlsafe($currentDate),

        'end_date' => $db->sqlsafe($currentDate),

        'RDE_message_id' => $db->sqlsafe($order['id']),

        'creation_date' => $db->sqlsafe($currentDate),
        'user_id' => $insertionArr['user_id'],

    );

    $currentCheckPoint = $checkPoints[0];

    if ($currentCheckPoint['check_in_or_check_out_status'] == 0) {


        $newCheckPoint['current_location'] = $currentCheckPoint['current_location'];

        $newCheckPoint['routes_check_points_id'] = $currentCheckPoint['routes_check_points_id'];

        $newCheckPoint['check_in_or_check_out_status'] = 1;

        for ($i = 0; $i < count($users); $i++) {

            if ($currentCheckPoint['current_location'] == $users[$i]['location_id']) {


                $firebase->sendNotification($users[$i]['fcm_token'], 'Order Tracking', 'Order ' . $order['id'] . ' has been checked out from: ' . $locations[$currentCheckPoint['current_location']]['location_name'] . ' and the order status now is: ' . $locations[$currentCheckPoint['current_location']]['check_out_status'] . '.');
            }
        }


        $newLocation;

        $routesLength = count($routes);


        for ($i = 0; $i < $routesLength; $i++) {




            if ($routes[$i]['id'] == $currentCheckPoint['routes_check_points_id']) {


                if ($routesLength > $i + 1) {



                    $newLocation = $routes[$i + 1];
                    for ($q = 0; $q < count($users); $q++) {
                        if ($newLocation['location_id'] == $users[$q]['location_id']) {


                            $firebase->sendNotification($users[$q]['fcm_token'], 'Order Tracking', 'Order ' . $order['id'] . ' has to be checked in at: ' . $locations[$newLocation['location_id']]['location_name'] . ' from the: ' . $locations[$currentCheckPoint['current_location']]['location_name'] . ' and the order status now is: ' . $locations[$currentCheckPoint['current_location']]['check_out_status'] . '.');
                        }
                    }
                }
            }
        }
    } else {

        $newCheckPoint['check_in_or_check_out_status'] = 0;



        $newLocation;

        $routesLength = count($routes);

        for ($i = 0; $i < $routesLength; $i++) {

            if ($routes[$i]['id'] == $currentCheckPoint['routes_check_points_id']) {

                if ($routesLength > $i + 1) {

                    $newLocation = $routes[$i + 1];

                    $newCheckPoint['current_location'] = $newLocation['location_id'];

                    $newCheckPoint['routes_check_points_id'] = $newLocation['id'];
                    for ($q = 0; $q < count($users); $q++) {
                        if ($newLocation['location_id'] == $users[$q]['location_id']) {
                            $firebase->sendNotification($users[$q]['fcm_token'], 'Order Tracking', 'Order ' . $order['id'] . ' has just been checked in at the: ' . $locations[$newLocation['location_id']]['location_name'] . ' and the order status now is: ' . $locations[$newLocation['location_id']]['check_in_status'] . '.');
                            // $firebase->sendNotification($users[$i]['fcm_token'], 'Order Tracking', 'Order ' . $order['id'] . ' should Be \'CheckedOut\' from the location: ' . $locations[$currentCheckPoint['current_location']]['location_name'] . '.');
                        }
                    }
                    // for ($i = 0; $i < count($users); $i++) {
                    //     if ($newLocation['id'] == $users[$i]['location_id']) {
                    //         $firebase->sendNotification($users[$i]['fcm_token'], 'Order Tracking', 'Order ' . $order['id'] . ' should Be \'CheckedIn\' from the location: ' . $locations[$newLocation['id']]['location_name'] . '.');
                    //     }
                    // }
                } else {

                    response('Order Tracking Has Finished', 200, null);
                    die;
                }

                break;
            }
        }
    }

    $db->insert('check_points', $newCheckPoint);





    // print(json_encode($response));
    response('New Check Point Has Been Added', 200, null);
    die;
}



// $db->insert('rde_messages', $insertionArr);

// print('<h1>data inserted successfully </h1>')

?>





