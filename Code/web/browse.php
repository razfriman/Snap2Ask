<?php

// Allow the included files to be executed
define('inc_file', TRUE);
?>

<!DOCTYPE html>
<html>

<head>
	<title>Snap-2-Ask | Browse</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>

	<?php include('header.php') ?>
	
	<div id="content">


		<div id="browseNav">
			<ul>
				<li>All</li>
				<li>Categories</li>
				<li>Familiar</li>
				<li>Recent</li>
			</ul>
		</div>
	
		<div id="linksNav">
			<ul>
				<li class="selected" ><a href="browse.php" >Browse</a></li>
				<li><a href="balance.php" >Balance</a></li>
				<li><a href="profile.php" >Profile</a></li>
			</ul>
		</div>
	
		
	
		<div id="mainContent">
			<!--POPULATE QUESTIONS HERE-->
		</div>
	
	</div>

	<?php include('footer.php') ?>

</body>
</html>
