<?php
require("includes/conf.php");
if (isset($_POST['username']) && isset($_POST['password'])) {
	$login_status = $db->select('SELECT * FROM `administrators` WHERE username=' . $db->sqlSafe($_POST['username']) . ' AND password=' . $db->sqlSafe(md5($_POST['password'])) . '');
	
	// die;
	if (count($login_status) != 0) {
		$md5_user = md5($login_status[0]['username']);
		$md5_pass = md5($login_status[0]['password']);
		$md5_time = md5(time());
		$logged_in_true = md5(substr($md5_user, 5, 20) . substr($md5_pass, 5, 20) . substr($md5_time, 5, 20));
		$logged_in_false = '';
		if ($login_status) {
			setcookie("admin_id", $login_status[0]['id']);
			setcookie("logged_in", $logged_in_true);
			setcookie("username", $login_status[0]['username']);
			setcookie("session", $md5_pass);
			setcookie("login_time", $md5_time);
			$admin_info = array(
				"logged_in" => $db->sqlSafe($logged_in_true),
				"username" => $db->sqlSafe($login_status[0]['username']),
				"session" => $db->sqlSafe($md5_pass),
				"login_time" => $db->sqlSafe($md5_time),
				"admin_id" => $db->sqlSafe($login_status[0]['id'])
			);
			$insert_admin = $db->insert('admin_sessions', $admin_info, false);
			print '<script language="JavaScript">window.location="index.php";</script>';
			exit;
		} else {
			setcookie("logged_in", "");
			setcookie("username", "");
			setcookie("session", "");
			setcookie("login_time", "");
		}
	} else {
		print '<script language="JavaScript">window.location="login.php?error=1";</script>';
	}
}
?>
<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>LogIn - 400 CMS</title>
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="assets/css/icons/icomoon/styles.min.css" rel="stylesheet" type="text/css">
	<link href="assets/css/rtl.all.min.css" rel="stylesheet" type="text/css">
	<script src="assets/js/main/jquery.min.js"></script>
	<script src="assets/js/main/bootstrap.bundle.min.js"></script>
	<script src="assets/js/app.js"></script>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Limitless - Responsive Web Application Kit by Eugene Kopyov</title>

	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="assets/css/icons/icomoon/styles.min.css" rel="stylesheet" type="text/css">
	<link href="assets/css/all.min.css" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	<script src="assets/js/main/jquery.min.js"></script>
	<script src="assets/js/main/bootstrap.bundle.min.js"></script>
	<!-- /core JS files -->

	<!-- Theme JS files -->
	<script src="assets/js/plugins/visualization/d3/d3.min.js"></script>
	<script src="assets/js/plugins/visualization/d3/d3_tooltip.js"></script>
	<script src="assets/js/plugins/ui/moment/moment.min.js"></script>
	<script src="assets/js/plugins/pickers/daterangepicker.js"></script>

	<script src="assets/js/app.js"></script>
	<script src="assets/js/demo_pages/dashboard.js"></script>
	<script src="assets/js/demo_charts/pages/dashboard/light/streamgraph.js"></script>
	<script src="assets/js/demo_charts/pages/dashboard/light/sparklines.js"></script>
	<script src="assets/js/demo_charts/pages/dashboard/light/lines.js"></script>
	<script src="assets/js/demo_charts/pages/dashboard/light/areas.js"></script>
	<script src="assets/js/demo_charts/pages/dashboard/light/donuts.js"></script>
	<script src="assets/js/demo_charts/pages/dashboard/light/bars.js"></script>
	<script src="assets/js/demo_charts/pages/dashboard/light/progress.js"></script>
	<script src="assets/js/demo_charts/pages/dashboard/light/heatmaps.js"></script>
	<script src="assets/js/demo_charts/pages/dashboard/light/pies.js"></script>
	<script src="assets/js/demo_charts/pages/dashboard/light/bullets.js"></script>
	<!-- /theme JS files -->
</head>

<body>
	<div class="page-content">
		<div class="content-wrapper text-center">
			<!-- Basic layout-->
			<div class="card col-lg-6 col-md-12 col-sm-12 d-flex justify-content-center align-content-center bg-dark p-4 m-auto" style="border-radius: 50px;">
				<div class="card-header text-center">
					<img src="assets/images/logo-full.png" alt="" style="width:150px">
				</div>
				<?php if (isset($_GET['error']) && !empty($_GET['error'])) : ?>
					<div>
						<h2 class="text-weight-light text-danger">Login error</h2>
					</div>
				<?php endif ?>
				<div class="card-body text-center ">
					<form action="" method="POST">
						<div class="form-group text-center " style="color: var(--light);width:100%">
							<label>User name</label>
							<input type="text" class="form-control text-center" placeholder="User name" name="username">
						</div>

						<div class="form-group text-center " style="color: var(--light);width:100%">
							<label>Password </label>
							<input type="password" class="form-control text-center" placeholder="Password" name="password">
						</div>
						<div class="text-center ">
							<button type="submit" class="box-shadow-hover btn text-center" style="background-color:#9d9d9c;color:#fff;font-weight: 500; ">login </button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</body>

</html>