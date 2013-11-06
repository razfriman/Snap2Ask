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

// Require the functions file
require_once('functions.php');

$question_info = getQuestionInfo($_GET['id']);

if ($question_info['status'] != 0) {
	// Question has already been answered. Redirect to browse page
	header('Location: browse.php');
	exit;
}

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
					
					<label>Date Asked:</label>
					<p><?php echo $question_info['date_created']; ?></p>
					
					<label>Description:</label>
					<p><?php echo $question_info['description']; ?></p>
				</div>			
			</div>
			
			<div id="submit-answer-container">
				<form id="submit-answer-form" action="#" method="POST">
					<label for="answer">Answer:</label>
					
					<label id="answerQuestionMessageLabel"></label>
					
					<textarea id="answer" name="answer"></textarea>
					
					<input type="hidden" id="user-id-hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>" />
					<input type="hidden" id="question-id-hidden" name="question_id" value="<?php echo $_GET['id']; ?>" />
					<input type="submit" id="submitQuestionButton" value="Submit Answer" />
				</form>
			</div>
		</div>
		
	</div>

	<?php include('footer.php') ?>

</body>
</html>
