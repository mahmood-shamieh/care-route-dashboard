<?php
$login_status = $_COOKIE['logged_in'];
$md5_all = md5(substr(md5($_COOKIE['username']),5,20).substr($_COOKIE['session'],5,20).substr($_COOKIE['login_time'],5,20));
if ($login_status != $md5_all){
	print '<script language="JavaScript">window.location="logout.php";</script>';
	exit;
}else{
	require ("conf.php");
	$chk_security = $db->select('SELECT * FROM `admin_sessions` WHERE logged_in='.$db->sqlSafe($login_status).' ');
}
if ($chk_security[0]['logged_in'] == $_COOKIE['logged_in'] || $chk_security[0]['username'] == $_COOKIE['username'] || $chk_security[0]['session'] == $_COOKIE['session'] || $chk_security[0]['login_time'] == $_COOKIE['login_time']){
	$admin_arrays = $db->select('SELECT * FROM `administrators` WHERE id='.$db->sqlSafe($chk_security[0]['admin_id']).' ');
	
	//print_r($admin_arrays);
}

?>