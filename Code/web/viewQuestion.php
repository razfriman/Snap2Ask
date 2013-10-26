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
	
	// DEBUG
	header('Location viewQuestion.php?id=40');
	//header('Location: browse.php');
	exit;

}

// Require the functions file
require_once('functions.php');

$question_info = getQuestionInfo($_GET['id']);

if ($question_info['status'] != 0) {
	// Question has already been answered. Redirect to browse page
	header('Location: browse.php');
}

//echo var_dump($question_info);
//die;

?>

<!DOCTYPE html>
<html>

<head>
	<title>Snap-2-Ask | View Question</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
	<script src="js/answerQuestion.js" type="text/javascript"></script>
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
		
			<div id="view-question">
				<div id="view-question-left">
					<img src="<?php echo $question_info['image_url']; ?>" width="400px" height="400px" />
				</div>
				
				<div id="view-question-right">
					
					<label>Category:</label>
					<p><?php echo $question_info['category']; ?></p>
					
					<label>Subcategory:</label>
					<p><?php echo $question_info['subcategory']; ?></p>
					
					<label>Description:</label>
					<p><?php echo $question_info['description']; ?></p>
					
					<label>Date Asked:</label>
					<p><?php echo $question_info['date_created']; ?></p>
				</div>			
			</div>
			
			<div id="submit-answer-container">
           <?php
           include ("submitanswer.php");
           ?>
			</div>
		</div>
	
	</div>

	<?php include('footer.php') ?>

</body>
</html>
