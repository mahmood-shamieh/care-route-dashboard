<?php

require_once '../includes/conf.php';

require_once '../includes/db.class.php';

header("Content-Type: application/json; charset=UTF-8");

function response($msg, $statueCode = 200, $body)

{

    $data = array();

    $data['code'] = intval($statueCode);

    $data['message'] = $msg;

    $data['data'] = $body;

    print(json_encode($data));

    die;
}

$jsonData = file_get_contents('php://input');



$data = json_decode(

    $jsonData,

    true

);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    switch ($_GET['act']) {
        default:
            break;
    }
} else {

    switch ($_GET['act']) {
        case 'signIn':
            include('./signin.api.php');
            break;
        default:
            # code...
            break;
    }
}
$headers = getallheaders();
$authorizationHeader = isset($headers['Authorization']) ? $headers['Authorization'] : (isset($headers['authorization']) ? $headers['authorization'] : '');
$user;
if (!empty($authorizationHeader)) {
    if (preg_match('/Bearer\s(\S+)/', $authorizationHeader, $matches)) {
        $userValid = $db->select('select * from `users` where `token` = ' . $db->sqlsafe($matches[1]));

        if (count($userValid) != 1) {
            response('Unauthorized', 401, null);
            die;
        } else {
            $user = $userValid[0];
        }
    } else {
        response('Unauthorized', 401, null);
    }
} else {
    response('Unauthorized', 401, null);
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    switch ($_GET['act']) {
        case 'getAllOrder':
            include('./getAllOrder.php');
            break;

        default:
            break;
    }
} else {

    switch ($_GET['act']) {

        case 'insertOrder':
            include('./insertAPI.php');
            break;
        case 'trackOrder':

            include('./traceOrderApi.php');
            break;
        case 'sendNotification':
            include('./sendNotification.php');
            break;
        case 'updateUserDetails':
            include('./updateUserDetails.api.php');
            break;
        case 'changeUserLocation':
            include('./changeUserLocation.api.php');
            break;


        default:
            # code...
            break;
    }
}
