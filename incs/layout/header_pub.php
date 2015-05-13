<?php $site_root = "/project3-CS2300/public/"; ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Scanna</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="description" content="Photo Gallery">
		<meta name="author" content="Alex Sinfarosa">

		<!-- Mobile Specific Meta -->
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- Font Awesome -->
		<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">	

		<!-- Custom Style Sheet -->
		<link rel="stylesheet" href="<?php echo($site_root); ?>/css/style.css">

		<!-- JQuery -->
		<script src="http://code.jquery.com/jquery-2.1.1.min.js"></script>

		<!-- MAIN JS-->
		<script src="<?php echo($site_root); ?>/js/scripts.js"></script>
		
	</head>
	<body>


	<!-- HEADER -->
	<nav class="col-12 bg-scuro bottom-margin">
		<ul class="col-3">
			<li><a class="logo" href="<?php echo $site_root; ?>/index.php">Scanna</a></li>
		</ul>
		<ul class="col-9 text-right">
			<li><a class="nav" href="<?php echo $site_root; ?>/php/albums_pub.php">Albums</a></li>
			<li><a class="nav" href="<?php echo $site_root; ?>/php/login.php">Login</a></li>
			<!-- <li><a class="nav user" ><?php echo $_SESSION[ 'logged_user' ]; ?></a></li> -->
		</ul>
	</nav>

	