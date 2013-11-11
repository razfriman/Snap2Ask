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

$answerInfo = getAnswerInfo($responseObj['id']);
?>

<!DOCTYPE html>
<html>

<head>
	<title>Snap-2-Ask | View Answers</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="shortcut icon" type="image/x-icon" href="res/favicon.ico">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="js/validateBalance.js" type="text/javascript"></script>
	<link rel="stylesheet" type="text/css" href="css/style.css">    
</head>

<body>

	<?php include('header.php') ?>
	
	<div id="content">

		<div id="linksNav">
			<ul>
				<li><a href="browse.php" >Browse</a></li>
				<li><a href="balance.php" >Balance</a></li>
				<li><a href="profile.php" >Profile</a></li>
				<li class="selected" ><a href="viewAnswers.php" >My Answers</a></li>
			</ul>
		</div>
		
		<div id="mainContent">
    		<h1>MY ANSWERS</h1>
            
            <?php
            
            for($i = 0; $i < sizeof($answerInfo['questions']); $i++)
            {
            
            $question = $answerInfo['questions'][$i];
            $answer = $answerInfo['answers'][$i];
            ?>
            	<div class="tutorViewAnswerContainer">
	                
	                <div class="tutorViewAnswersLeft">
	                	<img class="tutorViewAnswerImage" alt="Question Image" src="<?php echo htmlentities($question['image_url'], ENT_QUOTES); ?>" />
		                <label><?php echo $question['category']; ?></label>
		                <label><?php echo $question['subcategory']; ?></label>
		                <label><?php echo $question['date_created']; ?></label>
	                </div>
	
	                <div class="tutorViewAnswersRight">	
						
						<label>Question</label>
						<p>
						<?php echo $question['description']; ?>
						</p>
						
						<label>Answer</label>
						<p>
						<?php echo $answer['text']; ?>
						</p>
						
						<label>Rating</label>
						<?php
						
						 if (isset($answer['rating'])) {
						 
						 	if ($answer['rating'] == 0) {
							 	
							 	echo '<p>&lt;Rejected&gt;</p>';
							 	
						 	} else {
						 
								$stars = '';
								for ($j = 0; $j < $answer['rating']; $j++) {
									$stars = $stars . '&#9733; ';
								}
								echo sprintf('<p class="ratingStars">%s</p>', $stars);	
							}
							
						} else {
							echo '<p>&lt;None&gt;</p>';
						}
						?>
						
					</div>
				</div>
                
            <?php
            }
            ?>
			
		</div>
	</div>
	
	<?php include('footer.php') ?>

</body>
</html>
