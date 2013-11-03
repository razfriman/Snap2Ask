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

// Require the functions file
require_once('functions.php');

$responseObj = getUserInfo(true);

?>

<!DOCTYPE html>
<html>

<head>
	<title>Snap-2-Ask | Browse</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="shortcut icon" type="image/x-icon" href="res/favicon.ico">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="js/browseQuestions.js" type="text/javascript"></script>
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
			</ul>
		</div>
		<ul id="categories">
			<li>Math</li>
			<li>Science</li>
			<li>English</li>
			<li>History</li>
		</ul>
		<div id="linksNav">
			<ul>
				<li class="selected" ><a href="browse.php" >Browse</a></li>
				<li><a href="balance.php" >Balance</a></li>
				<li><a href="profile.php" >Profile</a></li>
			</ul>
		</div>
	
		<?php 
		
		$concatenatedCategories = '';
		
		foreach($responseObj['verified_categories'] as $category)
		{
			if ($category['is_preferred']) {
				$concatenatedCategories = $concatenatedCategories . $category['category_id'] . ' ';
			}
		}
		
		echo ('<input type="hidden" id="verified-categories-hidden" value="' . $concatenatedCategories . '" />');
		
		if(isset($_GET['search']))
		{
			echo('<input type="hidden" id="search-query-hidden" value="' . $_GET['search'] . '" />');
		}
		
		?>
	
		<div id="mainContent">
			<!--POPULATE QUESTIONS HERE-->
		</div>
	
	</div>

	<?php include('footer.php') ?>

</body>
</html>
