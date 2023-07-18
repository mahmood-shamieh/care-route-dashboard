<?php
define ('BASEPATH', 'ounzo');
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
$db->connect($dbname , $server , $user , $password);
function formatDate($date){
    return date("Y-m-d H:i:s",$date);
}
