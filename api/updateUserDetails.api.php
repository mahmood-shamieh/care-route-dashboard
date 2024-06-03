<?php


if (isset($_POST['password']) && !empty($_POST['password'])) {
    $_POST['password'] = md5($_POST['password']);
}
foreach ($_POST as $key => $value) {
    $insertionArray[$key] = $db->sqlsafe($value);
}
// print_r($insertionArray);
// die;
if (isset($_FILES['img']['size'])) {
    $filename = upload_images('img', ".././" . $mediaPath . "/" . "users" . "/", time(), 'user');
    $insertionArray['img'] = $db->sqlsafe($filename);
}

$db->update('users', $insertionArray, ' `id` =  ' . $insertionArray['id']);
$updatedUser = $db->select('select * from `users` where `id` = ' . $insertionArray['id'])[0];
$userLocation = $db->select('select * from `locations` where `location_id` = ' . $updatedUser['location_id'])[0];
$updatedUser['location'] = $userLocation;
response('Your Data Has Been Updated', 200, $updatedUser);
