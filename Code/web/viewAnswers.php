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

<?php

function stars($index)
{
   echo '<span class="rating">Rating: </span>
   <span class="stars">';
   $numStars = rand(1,5);
   for ($count = 0; $count < $numStars; $count++)
        echo '&#9733';
    echo '</span>';
}
            
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
		</div><!--end linksNav-->
		
		<div id="mainContent">
    		<h1>MY ANSWERS</h1>
            
            <?php
            include ('ans.php');
            
            for($i = 27; $i < 37; $i++)
            {
                $str = "https://snap2ask.s3.amazonaws.com/" . $i . ".jpeg";
            ?>
            	<div class="tutorViewAnswerContainer">
	                
	                <div class="tutorViewAnswersLeft">
	                	<img class="tutorViewAnswerImage" alt="Question Image" src="<?php echo $str; ?>" />
		                <label>Science</label>
		                <label>Chemistry</label>
		                <label>2013-11-07 04:07:20</label>
	                </div>
	
	                <div class="tutorViewAnswersRight">	
						<label>Question</label>
						<p>
						Lorem ipsum dolor sit amet.
						</p>
						
						<label>Answer</label>
						<p>
						Lorem ipsum dolor sit amet.
						</p>
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
