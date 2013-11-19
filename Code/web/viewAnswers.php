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
	<script src="js/lightbox-2.6.min.js"></script>
	<link href="css/lightbox.css" rel="stylesheet" />
	<script src="js/viewAnswers.js"></script>
	<script src="js/jquery.tinysort.min.js"></script>

</head>

<body>

	<?php include('header.php') ?>
	
	<div id="content">

		<div id="linksNav">
			<ul>
				<li><a href="browse.php" title="Browse Unanswered Questions">Browse</a></li>
				<li><a href="balance.php" title="Withdraw Snapcash">Balance</a></li>
				<li><a href="profile.php" title="Browse Unanswered Questions">Profile</a></li>
				<li class="selected" ><a href="viewAnswers.php" title="View Answer History">My Answers</a></li>
			</ul>
		</div>
		<div id="mainContent">
		
			<h1 title="Your answer history">MY ANSWERS</h1>

	        <?php
	            if(sizeof($answerInfo['questions']) < 1) {
	                    echo "<h2>Click <a href='browse.php'>here</a> to answer questions now.</h2>";
	            } else {
	        ?>
            	
            	<div id="advancedSearchOptions" >
            		<label for="sortSelection">Sort By:</label>
					<select name="sortSelection" id="sortSelection">
						<option>Most Recent First</option>
						<option>Least Recent First</option>
						<option>Highest Pay First</option>
						<option>Lowest Pay First</option>
						<option>Highest Rating First</option>
						<option>Lowest Rating First</option>						
					</select>
            	</div>
            	
                <div id="results">
            <?php
            for($i = 0; $i < sizeof($answerInfo['questions']); $i++)
            {
            
            $question = $answerInfo['questions'][$i];
            $answer = $answerInfo['answers'][$i];
            $imgurl = htmlentities($question['image_url'], ENT_QUOTES);
            
            $answer['pay'] = rand(1,10) * 10;
            ?>
            	<?php
            	echo sprintf('<div class="tutorViewAnswerContainer" answerDate="%s" pay="%s" rating="%s">', $answer['date_created'], $answer['pay'], $answer['rating']);
            	?>
	                <div class="tutorViewAnswersLeft">

	                	<?php
	                	
	                	// Image
	                	echo sprintf('<a class="image-link" href="%s" title="%s" data-lightbox="example-%s">', $imgurl, $question['description'], $i);
	                	echo sprintf('<img class="tutorViewAnswerImage" alt="Question Image" src="%s" />', $imgurl);
	                	echo sprintf('</a>');
	                	
	                	// Category
	                	echo sprintf('<label>%s - %s</label>', $question['category'], $question['subcategory']);
	                	
	                	// Date
                        //$str = $question['date_created'];
                        $str = $answer['date_created'];
                        $year = substr($str,5,2) . "/" . substr($str,8,2) . "/" . substr($str,2,2);
                        $hour = intval(substr($str, 11, 2));

                        if ($hour > 12) {
                            $hour = $hour - 12;
                            $suffix = "PM";
                        } else {
                            $suffix = "AM";
                        }

	                	echo '<label class="datetime" id="' . $str . '">';
                        echo $year . " " . $hour . substr($str, 13, 3) . " " . $suffix; 	                	
	                	echo '</label>';
	                	?>
                    </div>
                    
	                <div class="tutorViewAnswersMiddle">	
						
						<label>Question</label>
						<p>
						<?php echo $question['description']; ?>
						</p>
						
						<label>Answer</label>
						<p>
						<?php echo $answer['text']; ?>
						</p>
	                </div>
	                
	                <div class="tutorViewAnswersRight">
	                	
	                	<div class="tutorViewAnswersEarnings">
	                		<a href="balance.php" title="Click to Withdraw SnapCash Now">
									<img src="res/dollars.png" />
									<p class="snappay"><?php echo $answer['pay']; ?></p>
							</a>
	                	</div>
						
						<div class="tutorViewAnswersRating">
						
						<label>Rating</label>
						<?php						
						if (isset($answer['rating'])) {
						 	
							if ($answer['rating'] == 0) { 	
							 	echo '<p>Rejected</p>';
						 	} else {
								$stars = '';
								for ($j = 0; $j < $answer['rating']; $j++) {
									$stars = $stars . '&#9733; ';
								}
								echo sprintf('<p class="ratingStars">%s</p>', $stars);	
							}
						} else {
							echo '<p>Pending</p>';
						}
						?>
						</div>
						
					</div>
				</div>
                
            <?php
            }//end for
            }//end else
            ?>
			</div>
		</div>
	</div>
	
	<?php include('footer.php') ?>

</body>
</html>
