<?php



// define('BASEPATH', 'ounzo');

ini_set('memory_limit', '500M');

error_reporting(0);

ini_set('display_errors', 0);

ini_set('display_startup_errors', 0);
// ini_set('upload_max_filesize', '4000');
// ini_set('post_max_size', '4000');


error_reporting(E_ALL);

require("db.class.php");
require("FireBase.class.php");

$server = '127.0.0.1';

$dbname = 'fivevae_care_route';

$user = 'fivevae_care_route';

$password = 'W{r7((CIWy,o';

// $dbname = 'fivevae_care_route';

// $user = 'fivevae_care_route';

// $password = '6@bqu?^5WQ?)';

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

    $image_name = $path . $tag . '-' . $time . "." . $final_type;

    $ext = array('jpg', 'jpeg', 'png', 'gif', 'GIF', 'JPG', 'PNG', 'JPEG', 'svg', 'SVG', 'webp', 'WEBP');

    if (false/* !in_array($final_type, $ext) */) {

        print '<meta http-equiv="refresh" content="0;URL=index.php?cmd=' . $_GET["cmd"] . '&error=1" />';

        die;
    } else {

        copy($_FILES[$filename]['tmp_name'], $image_name) or move_uploaded_file($_FILES[$filename]['tmp_name'], $image_name);

        $save = $tag . '-' . $time .   '.' . $final_type;

        return $save;
    }
}
$firebase = new FireBase('AAAApu9JSIg:APA91bH9QVzd4SxkSgQy-T1OD9iJkFVed8SdlQjRMc7ltXEbkFmDvytFE4iThbNuAsAYNfC-j6aw7vyupana5DM6Rnz9MZwKvUEXt03N0DNi99pOqupBmOjDUpbbQMEHTr27lT-yJIuh');
