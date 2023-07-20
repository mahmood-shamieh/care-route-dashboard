<?php
define('BASEPATH', 'ounzo');
ini_set('memory_limit', '500M');
error_reporting(0);
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
require("db.class.php");
$server = '127.0.0.1';
$dbname = 'care_route';
// $user = 'sy4s_400u';
$user = 'root';
// $password = 'A123456s!!@@##';
$password = '';
$db = new db;
$db->connect($dbname, $server, $user, $password);
function formatDate($date)
{
    return date("Y-m-d H:i:s", $date);
}
$mediaPath = 'assets/images/development';
function upload_images($filename, $path, $time, $tag = '')
{
    $image_name = $_FILES[$filename]['name'];
    $type = explode(".", $image_name);
    $final_type = $type[count($type) - 1];
    $image_name = $path . $tag.'-' . $time . "." . $final_type;
    $ext = array('jpg', 'jpeg', 'png', 'gif', 'GIF', 'JPG', 'PNG', 'JPEG', 'svg', 'SVG', 'webp', 'WEBP');
    if (false/* !in_array($final_type, $ext) */) {
        print '<meta http-equiv="refresh" content="0;URL=index.php?cmd=' . $_GET["cmd"] . '&error=1" />';
        die;
    } else {
        copy($_FILES[$filename]['tmp_name'], $image_name) or move_uploaded_file($_FILES[$filename]['tmp_name'], $image_name);
        $save =$tag.'-'. $time .   '.' . $final_type;
        return $save;
    }
}
