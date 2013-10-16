<!DOCTYPE html>
<html>

<head>
	<title>Snap-2-Ask | Browse</title>
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
		
		<form id="search" method="POST" action="#">
			<input type="text" name="search" placeholder="Search" title="Search a Question" />
		</form>

	</header>
	
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

	<footer>
		Snap-2-Ask 2013
	</footer>

</body>
</html>
