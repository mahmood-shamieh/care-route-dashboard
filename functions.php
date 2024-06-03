<?php



require_once('includes/sess.php');
if (!empty($_GET['cmd'])) {
    $cmd = $_GET['cmd'];
    $cmdId = $db->select('select * from `cms_modules` where `link` =' . $db->sqlsafe($cmd) . ' ');
    if (count($cmdId) == 0) {
        $cmd = '2';
    } else {

        $cmdId = $cmdId[0];

        if (isset($_COOKIE['user_id'])) {

            $section = $db->select('SELECT * from `users_prev` WHERE `user_id` =  ' . $db->sqlsafe($_COOKIE['user_id']) . ' AND `module_id` = ' . $db->sqlsafe($cmdId['id']))[0];
        } else {

            $section = $db->select('SELECT * from `admin_prev` WHERE `admin_id` =  ' . $db->sqlsafe($_COOKIE['admin_id']) . ' AND `module_id` = ' . $db->sqlsafe($cmdId['id']))[0];
        }

        if ($cmdId['status'] != 1) {

            $cmd = '1';
        } else if ($section['view'] == 0) {

            $cmd = '0';
        } else {

            $cmd = $cmdId['link'];
        };
    }
} else {
    $cmd = 'orders';
}
chk_security($cmd);
function chk_security($cmd)
{



    setcookie('cmd_id', $cmd);



    switch ($cmd) {



        case '0':

            define('PAGE_INCLUDE', "modules/unauthorized/unauthorized.php");

            define('PAGE_TITLE', "Unauthorized");

            break;

        case '1':

            define('PAGE_INCLUDE', "modules/UnActive/UnActive.php");

            define('PAGE_TITLE', "UnActive");

            break;

        case '2':

            define('PAGE_INCLUDE', "modules/pageNotFound.php");

            define('PAGE_TITLE', "Not found");

            break;

        default:

            define('PAGE_INCLUDE', "modules/" . $cmd . "/" . $cmd . ".php");

            define('PAGE_TITLE', "users page");
    }
}
