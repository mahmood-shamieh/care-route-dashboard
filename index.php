<?php
require('includes/sess.php');
require_once("functions.php");

if (empty($_COOKIE['admin_id']) || empty($_COOKIE['logged_in']) || empty($_COOKIE['username']) || empty($_COOKIE['session']) || empty($_COOKIE['login_time'])) {
	print '<script language="JavaScript">window.location="login.php";</script>';
}
$admin = $db->select('select * from `administrators` where `id` = ' . $db->sqlsafe($_COOKIE['admin_id']))[0];
$modules = $db->select('SELECT cms_modules.`id`, cms_modules.`name`, cms_modules.`status`, cms_modules.`link`,cms_modules. `icon` ,admin_prev.`edit`, admin_prev.`add`, admin_prev.`delete`, admin_prev.`view` from `cms_modules` INNER JOIN `admin_prev` on cms_modules.id = admin_prev.module_id AND admin_prev.admin_id = ' . $db->sqlsafe($admin['id']) . 'order by cms_modules.`id`');
if (isset($_GET['cmd']) && !empty($_GET['cmd'])) {
	$currentSection = $db->select('SELECT cms_modules.`id`, cms_modules.`name`,cms_modules.`have_actions`, cms_modules.`status`, cms_modules.`link`,cms_modules. `icon` ,admin_prev.`edit`, admin_prev.`add`, admin_prev.`delete`, admin_prev.`view` from `cms_modules` INNER JOIN `admin_prev` on cms_modules.id = admin_prev.module_id AND cms_modules.link = ' . $db->sqlsafe($_GET['cmd']))[0];
}
?>


<!DOCTYPE html>
<html lang="en" dir=ltr>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">
	<title><?php include PAGE_TITLE; ?></title>

	<!-- Global stylesheets -->
	<!-- <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css"> -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link href="assets/css/icons/icomoon/styles.min.css" rel="stylesheet" type="text/css">
	<link href="assets/css/all.min.css" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	<script src="assets/js/main/jquery.min.js"></script>
	<script src="assets/js/main/bootstrap.bundle.min.js"></script>
	<!-- /core JS files -->


	<script src="assets/js/plugins/ui/moment/moment.min.js"></script>
	<script src="assets/js/plugins/pickers/daterangepicker.js"></script>
	<script src="assets/js/plugins/tables/datatables/datatables.min.js"></script>
	<script src="assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js"></script>
	<script src="assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js"></script>
	<script src="assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>
	<script src="assets/js/plugins/notifications/sweet_alert.min.js"></script>
	<!-- <script src="assets/js/demo_pages/extra_sweetalert.js"></script> -->

	<script src="assets/js/app.js"></script>
	<!-- <script src="assets/js/demo_pages/dashboard.js"></script> -->

</head>

<body>

	<!-- Main navbar -->
	<div class="navbar navbar-expand-lg navbar-dark navbar-static">
		<div class="d-flex flex-1 d-lg-none">
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
				<i class="icon-paragraph-justify3"></i>
				<span class="badge badge-mark border-warning m-1"></span>
			</button>
		</div>
		<a href="index.php?cmd=home" class="d-inline-block">
			<div class="navbar-brand wmin-0 mr-lg-5">
				<img src="assets/images/logo.png" class="d-inline-block mr-1" alt="">
				<img src="assets/images/logo-name.png" class="d-inline-block" alt="">
			</div>
		</a>

		<div class="collapse navbar-collapse order-2 order-lg-1" id="navbar-mobile">
			<ul class="navbar-nav">
				<li class="nav-item dropdown">
					<a href="index.html#" class="navbar-nav-link" data-toggle="dropdown">
						<i class="icon-people"></i>
						<span class="d-lg-none ml-3">Users</span>
						<span class="badge badge-mark border-warning ml-auto ml-lg-0"></span>
					</a>

					<div class="dropdown-menu dropdown-content wmin-lg-300">
						<div class="dropdown-content-header">
							<span class="font-weight-semibold">Users online</span>
							<a href="index.html#" class="text-body"><i class="icon-search4 font-size-base"></i></a>
						</div>

						<div class="dropdown-content-body dropdown-scrollable">
							<ul class="media-list">
								<li class="media">
									<div class="mr-3">
										<img src="assets/images/demo/users/face18.jpg" width="36" height="36" class="rounded-circle" alt="">
									</div>
									<div class="media-body">
										<a href="index.html#" class="media-title font-weight-semibold">Jordana Ansley</a>
										<span class="d-block text-muted font-size-sm">Lead web developer</span>
									</div>
									<div class="ml-3 align-self-center"><span class="badge badge-mark border-success"></span></div>
								</li>

								<li class="media">
									<div class="mr-3">
										<img src="assets/images/demo/users/face24.jpg" width="36" height="36" class="rounded-circle" alt="">
									</div>
									<div class="media-body">
										<a href="index.html#" class="media-title font-weight-semibold">Will Brason</a>
										<span class="d-block text-muted font-size-sm">Marketing manager</span>
									</div>
									<div class="ml-3 align-self-center"><span class="badge badge-mark border-danger"></span></div>
								</li>

								<li class="media">
									<div class="mr-3">
										<img src="assets/images/demo/users/face17.jpg" width="36" height="36" class="rounded-circle" alt="">
									</div>
									<div class="media-body">
										<a href="index.html#" class="media-title font-weight-semibold">Hanna Walden</a>
										<span class="d-block text-muted font-size-sm">Project manager</span>
									</div>
									<div class="ml-3 align-self-center"><span class="badge badge-mark border-success"></span></div>
								</li>

								<li class="media">
									<div class="mr-3">
										<img src="assets/images/demo/users/face19.jpg" width="36" height="36" class="rounded-circle" alt="">
									</div>
									<div class="media-body">
										<a href="index.html#" class="media-title font-weight-semibold">Dori Laperriere</a>
										<span class="d-block text-muted font-size-sm">Business developer</span>
									</div>
									<div class="ml-3 align-self-center"><span class="badge badge-mark border-warning"></span></div>
								</li>

								<li class="media">
									<div class="mr-3">
										<img src="assets/images/demo/users/face16.jpg" width="36" height="36" class="rounded-circle" alt="">
									</div>
									<div class="media-body">
										<a href="index.html#" class="media-title font-weight-semibold">Vanessa Aurelius</a>
										<span class="d-block text-muted font-size-sm">UX expert</span>
									</div>
									<div class="ml-3 align-self-center"><span class="badge badge-mark border-secondary"></span></div>
								</li>
							</ul>
						</div>

						<div class="dropdown-content-footer bg-light">
							<a href="index.html#" class="text-body mr-auto">All users</a>
							<a href="index.html#" class="text-body"><i class="icon-gear"></i></a>
						</div>
					</div>
				</li>

				<li class="nav-item dropdown">
					<a href="index.html#" class="navbar-nav-link" data-toggle="dropdown">
						<i class="icon-pulse2"></i>
						<span class="d-lg-none ml-3">Activity</span>
						<span class="badge badge-mark border-warning ml-auto ml-lg-0"></span>
					</a>

					<div class="dropdown-menu dropdown-content wmin-lg-350">
						<div class="dropdown-content-header">
							<span class="font-weight-semibold">Latest activity</span>
							<a href="index.html#" class="text-body"><i class="icon-search4 font-size-base"></i></a>
						</div>

						<div class="dropdown-content-body dropdown-scrollable">
							<ul class="media-list">
								<li class="media">
									<div class="mr-3">
										<a href="index.html#" class="btn btn-success rounded-pill btn-icon"><i class="icon-mention"></i></a>
									</div>

									<div class="media-body">
										<a href="index.html#">Taylor Swift</a> mentioned you in a post "Angular JS. Tips and tricks"
										<div class="font-size-sm text-muted mt-1">4 minutes ago</div>
									</div>
								</li>

								<li class="media">
									<div class="mr-3">
										<a href="index.html#" class="btn btn-pink rounded-pill btn-icon"><i class="icon-paperplane"></i></a>
									</div>

									<div class="media-body">
										Special offers have been sent to subscribed users by <a href="index.html#">Donna Gordon</a>
										<div class="font-size-sm text-muted mt-1">36 minutes ago</div>
									</div>
								</li>

								<li class="media">
									<div class="mr-3">
										<a href="index.html#" class="btn btn-primary rounded-pill btn-icon"><i class="icon-plus3"></i></a>
									</div>

									<div class="media-body">
										<a href="index.html#">Chris Arney</a> created a new <span class="font-weight-semibold">Design</span> branch in <span class="font-weight-semibold">Limitless</span> repository
										<div class="font-size-sm text-muted mt-1">2 hours ago</div>
									</div>
								</li>

								<li class="media">
									<div class="mr-3">
										<a href="index.html#" class="btn btn-purple rounded-pill btn-icon"><i class="icon-truck"></i></a>
									</div>

									<div class="media-body">
										Shipping cost to the Netherlands has been reduced, database updated
										<div class="font-size-sm text-muted mt-1">Feb 8, 11:30</div>
									</div>
								</li>

								<li class="media">
									<div class="mr-3">
										<a href="index.html#" class="btn btn-warning rounded-pill btn-icon"><i class="icon-comment"></i></a>
									</div>

									<div class="media-body">
										New review received on <a href="index.html#">Server side integration</a> services
										<div class="font-size-sm text-muted mt-1">Feb 2, 10:20</div>
									</div>
								</li>

								<li class="media">
									<div class="mr-3">
										<a href="index.html#" class="btn btn-teal rounded-pill btn-icon"><i class="icon-spinner11"></i></a>
									</div>

									<div class="media-body">
										<strong>January, 2018</strong> - 1320 new users, 3284 orders, $49,390 revenue
										<div class="font-size-sm text-muted mt-1">Feb 1, 05:46</div>
									</div>
								</li>
							</ul>
						</div>

						<div class="dropdown-content-footer bg-light">
							<a href="index.html#" class="text-body mr-auto">All activity</a>
							<div>
								<a href="index.html#" class="text-body" data-popup="tooltip" title="Clear list"><i class="icon-checkmark3"></i></a>
								<a href="index.html#" class="text-body ml-2" data-popup="tooltip" title="Settings"><i class="icon-gear"></i></a>
							</div>
						</div>
					</div>
				</li>
			</ul>
		</div>

		<ul class="navbar-nav flex-row order-1 order-lg-2 flex-1 flex-lg-0 justify-content-end align-items-center">

			<!-- profile section -->
			<li class="nav-item nav-item-dropdown-lg dropdown dropdown-user h-100">
				<a class="navbar-nav-link navbar-nav-link-toggler d-inline-flex align-items-center h-100 dropdown-toggle" data-toggle="dropdown">
					<img src="assets/images/demo/users/face11.jpg" class="rounded-pill" height="34" alt="">
					<span class="d-none d-lg-inline-block ml-2">Victoria</span>
				</a>
				<div class="dropdown-menu dropdown-menu-right">
					<a href="index.html#" class="dropdown-item"><i class="icon-user-plus"></i> My profile</a>
					<a href="logout.php" class="dropdown-item"><i class="icon-switch2"></i> Logout</a>
				</div>
			</li>
		</ul>
	</div>
	<!-- /main navbar -->


	<!-- Secondary navbar -->
	<div class="navbar navbar-expand navbar-light px-0 px-lg-3">
		<div class="overflow-auto overflow-lg-visible scrollbar-hidden flex-1">
			<ul class="navbar-nav flex-row text-nowrap">
				<?php foreach ($modules as $key => $value) {
				?>
					<li class="nav-item">
						<a href="index.php?cmd=<?php print($value['link']) ?>" class="navbar-nav-link <?php ($_GET['cmd'] == $value['link'] ? print('active') : print('')) ?> ">
							<?php print($value['icon']) ?>
							<?php print($value['name']) ?>

						</a>
					</li>
				<?php
				} ?>




			</ul>
		</div>
	</div>
	<!-- /secondary navbar -->


	<!-- Page header -->

	<!-- /page header -->


	<!-- Page content -->
	<div class="page-content pt-0">

		<!-- Main content -->
		<div class="content-wrapper">
			<?php include PAGE_INCLUDE; ?>
		</div>
		<!-- /main content -->

	</div>
	<!-- /page content -->


	<!-- Footer -->
	<div class="navbar navbar-expand-lg navbar-light border-bottom-0 border-top">
		<div class="text-center d-lg-none w-100">
			<button type="button" class="navbar-toggler dropdown-toggle" data-toggle="collapse" data-target="#navbar-footer">
				<i class="icon-unfold mr-2"></i>
				Footer
			</button>
		</div>

		<div class="navbar-collapse collapse" id="navbar-footer">
			<span class="navbar-text">
				&copy; 2022 - 2023. <a href="https://5v.ae">Care Route system</a> by <a href="https://5v.ae" target="_blank">5V</a>
			</span>

			<!-- <ul class="navbar-nav ml-lg-auto">
				<li class="nav-item"><a href="https://kopyov.ticksy.com/" class="navbar-nav-link" target="_blank"><i class="icon-lifebuoy mr-2"></i> Support</a></li>
				<li class="nav-item"><a href="https://demo.interface.club/limitless/docs/" class="navbar-nav-link" target="_blank"><i class="icon-file-text2 mr-2"></i> Docs</a></li>
				<li class="nav-item"><a href="https://themeforest.net/item/limitless-responsive-web-application-kit/13080328?ref=kopyov" class="navbar-nav-link font-weight-semibold"><span class="text-pink"><i class="icon-cart2 mr-2"></i> Purchase</span></a></li>
			</ul> -->
		</div>
	</div>
	<!-- /footer -->

</body>

</html>