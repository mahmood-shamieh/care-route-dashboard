<?php
$currentUser = $db->select('select * from `users` where `username` =' . $db->sqlsafe($data['username']) . ' and `password` =' . $db->sqlsafe(md5($data['password'])));
if (count($currentUser) == 1) {
    $token = md5($currentUser[0]['username'] . $currentUser[0]['password'] . $currentUser[0]['creation_date']);
    $db->update(
        'users',
        array(
            'token' => $db->sqlsafe($token),
        ),
        ' `id` = ' . $currentUser[0]['id']
    );
    $userLocation = $db->select('select * from `locations` where `location_id` = ' . $currentUser[0]['location_id'])[0];
    $currentUser[0]['token'] = $token;
    $currentUser[0]['location'] = $userLocation;
    response('Welcome ' . $currentUser[0]['full_name'], 200, $currentUser[0]);
} else {
    response('Unauthorized', 401, null);
}
die;
