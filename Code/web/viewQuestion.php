<?php

// Start the named session
session_name('loginSession');
session_start();

// Allow the included files to be executed
define('inc_file', TRUE);

if (!isset($_SESSION['user_id'])) {
	// The user is not logged in
	header('Location: index.php');
	exit;
}

if (!isset($_GET['id'])) {
	// No question selected
	header('Location: browse.php');
	exit;

}

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

	
		<div id="linksNav">
			<ul>
				<li class="selected" ><a href="browse.php" >Browse</a></li>
				<li><a href="balance.php" >Balance</a></li>
				<li><a href="profile.php" >Profile</a></li>
			</ul>
		</div>
	
		
	
		<div id="mainContent">
			<p><?php echo 'Question ID: ' . $_GET['id']; ?></p>
		</div>
	
	</div>

	<?php include('footer.php') ?>

</body>
</html>
