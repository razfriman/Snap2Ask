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

$categories = getCategories();

?>

<!DOCTYPE html>
<html>

<head>
	<title>Snap-2-Ask | Browse</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="shortcut icon" type="image/x-icon" href="res/favicon.ico">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="js/jquery.pages.js" type="text/javascript" ></script>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/jPages.css">
	<script src="js/browseQuestions.js" type="text/javascript" ></script>

</head>

<body>

	<?php include('header.php') ?>
	
	<div id="content">

		<div id="browseNav">
			<h1> UNANSWERED QUESTIONS</h1>	
			<ul id="browseMenu">
				<li class="mainLink">All</li>
				<li id="categoriesTab" class="mainLink">Categories
					<ul id="categoriesMenu">
						<?php
						
						foreach($categories as $category)
						{
							
							$icon_url = sprintf('res/icons/%s',$category['name']);
							$img_element = sprintf('<img src="%s" />', $icon_url);
							
							echo sprintf('<li class="subLink" value="%s">%s %s</li>', $category['id'], $img_element, $category['name']);
						}
						
						?>
					</ul>
				</li>
				
				<li class="mainLink">Familiar</li>
			</ul>
		</div>
		
		
		
		<div id="linksNav">
			<ul>
				<li class="selected" ><a href="browse.php" >Browse</a></li>
				<li><a href="balance.php" >Balance</a></li>
				<li><a href="profile.php" >Profile</a></li>
				<li><a href="viewAnswers.php" >My Answers</a></li>
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
			<label id="searchLabel"></label>
			<label id="browseError" class="error"></label>
			<div class="holder"></div>
			<div id="pagedContent">
			<!--POPULATE QUESTIONS HERE-->
			</div>
			<div class="holder"></div>
		</div>
		
	</div>

	<?php include('footer.php') ?>

</body>
</html>
