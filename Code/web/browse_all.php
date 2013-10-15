<!DOCTYPE html>
<html>

<head>
	<title>Browse All</title>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>

	<header>
	<div id="cash">
		<h3>SnapCash</h3>
		<h2>$0.00</h2>
	</div>

	
	<h1><img src="res/temp_logo.png" alt="Snap-2-Ask Logo" id="logoSmall"/></h1>
		
	<form id="search" method="POST" action="NONE">
			<input type="text" name="search" placeholder="Search" title="Search a Question" />
	</form>
	</header>

	<nav>
		<a href="browse_all.php" ><h2>Browse</h2></a>
		<a href="balance.php"><h2>Balance</h2></a>
		<a href="profile.php"><h2>Profile</h2></a>
	</nav>

	<h2>Browsing all Questions</h2>

	<div id="browseNav">
		<h3>All</h3>
		
		<h3>Categories</h3>

		<h3>Familiar</h3>

		<h3>Recent</h3>

		<!--POPULATE QUESTIONS HERE-->
	</div>

</body>
</html>
