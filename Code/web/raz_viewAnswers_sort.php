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
            class Answer
            {
                public $id;
                public $cat;
                public $rating;
                public $desc;
                public $pay;
                public $datestr;
                public $answer;
                public $imgurl;
            }
            $answers = array();
            for($k = 0; $k < sizeof($answerInfo['questions']); $k++)
            {
                $question = $answerInfo['questions'][$i];
                $answer = $answerInfo['answers'][$i];
                        $answers[$k] = new Answer();
                        $answers[$k]->id = $question['id'];
                        $answers[$k]->pay = rand(1,100);
                        $answers[$k]->datestr = $question["date_created"];
                        $answers[$k]->cat = $question["category"];
                        $answers[$k]->rating = rand(1,5);
                        $answers[$k]->desc = $question['description'];
                        $answers[$k]->answer = $answer['text'];;
                        $answers[$k]->imgurl = $question["image_url"];
            }
            function recent($a, $b)
            {
                return $b->id - $a->id;
            }
            function ratinghigh($a, $b)
            {
                return $b->rating - $a->rating;
            }
            function ratinglow($a, $b)
            {
                return -1 * ($b->rating - $a->rating);
            }
            function payhigh($a, $b)
            {
                return $b->pay - $a->pay;
            }
            function paylow($b, $a)
            {
                return $b->pay - $a->pay;
            }
            function select($lab)
            {
                if ($_POST['order'] === $lab)
                        echo 'selected'; 
            }
            ?>
           <form id='sort' action="<?=$PHP_SELF?>" method='post'>
            <label for="order">Sort By:</label>
            <select name="order">
            <option>Most Recent First</option>
            <option <?php select('Highest Pay First');?>>Highest Pay First</option>
            <option <?php select('Lowest Pay First');?>>Lowest Pay First</option>
            <option <?php select('Highest Rating First');?>>Highest Rating First</option>
            <option <?php select('Lowest Rating First');?>>Lowest Rating First</option>
            </select>
            <input type='submit' value='submit'/>
            </form>
            <?php
            
            for($i = 0; $i < sizeof($answers); $i++)
            {
            ?>
            	<div class="tutorViewAnswerContainer">
	                
	                <div class="tutorViewAnswersLeft">
	                	<img class="tutorViewAnswerImage" alt="Question Image" src="<?php echo $answers[$i]->imgurl; ?>" />
		                <label><?php echo $answers[$i]->cat; ?></label>
		                <label><?php echo $answers[$i]->subcat; ?></label>
		                <label><?php echo $answers[$i]->datestr; ?></label>
	                </div>
	
	                <div class="tutorViewAnswersRight">	
						
						<label>Question</label>
						<p>
						<?php echo $answers[$i]->desc; ?>
						</p>
						
						<label>Answer</label>
						<p>
						<?php echo $answers[$i]->answer; ?>
						</p>
						
						<label>Rating</label>
						<?php
						
						 if (isset($answer['rating'])) {
							$stars = '';
							for ($j = 0; $j < $answer['rating']; $j++) {
								$stars = $stars . '&#9733; ';
							}
							
							echo sprintf('<p class="ratingStars">%s</p>', $stars);
							
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
