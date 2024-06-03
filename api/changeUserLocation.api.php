<?php
$locationValid = $db->select('select * from `locations` where `location_id` = ' . $db->sqlsafe($data['location_id']));
if (count($locationValid)) {
    $insertionArr = array(
        'location_id' => $db->sqlsafe($data['location_id']),
    );
    $db->update('users', $insertionArr, ' `id` =  ' . $db->sqlsafe($user['id']));
    $userUpdated = $db->select('select * from `users` where `id` = ' . $db->sqlsafe($user['id']))[0];
    $userUpdated['location'] = $locationValid[0];
    response('Your location has been updated successfully ', 200, $userUpdated);
} else
// http_response_code(204);
{
    response('No location related to the sended ID', 204, null);
}
die;
