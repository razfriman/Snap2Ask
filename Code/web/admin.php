<?php

// Allow the included files to be executed
define('inc_file', TRUE);
?>

<!DOCTYPE html>
<html ng-app="adminApp">

<head>
	<title>Snap-2-Ask | Admin</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.4/angular.min.js"></script>
	<script type="text/javascript" src ="js/admin.js"></script>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body ng-controller="UserListCtrl">

<body>
	<?php include_once("ganalytics.php") ?>
	<header class="tall">
		<a href="index.php"> <img id="logoTall" src="res/logo.png" alt="Snap-2-Ask Logo"/> </a>
	</header>

	<div id="content">
		<div id="adminContainer">
			<table>
				<thead>
					<tr>
						<td>Email</td>
						<td>First Name</td>
						<td>Last Name</td>
						<td>Authentication Mode</td>
					</tr>
				</thead>
				<tbody>
					<tr ng-repeat="user in users">
						<td>{{user.email}}</td>
						<td>{{user.first_name}}</td>
						<td>{{user.last_name}}</td>
						<td>{{user.authentication_mode}}</td> 
					</tr>
				</tbody>
			</table>
		</div>	
	</div>
	
	<?php include('footer.php') ?>
</body>
</html>
