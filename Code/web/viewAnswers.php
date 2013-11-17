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
<script>
        window.onload = function(){
        main = document.getElementById("mainContent");
        buttons = document.getElementsByTagName("button");
        secret = document.getElementById("hiddenhtml");
        newhtml = secret.innerHTML;
        newhtml = newhtml.substring(newhtml.indexOf("<!--") + 4, newhtml.indexOf("-->"));
        mainhtml = main.innerHTML;
        $('.tutorViewAnswerImage').click(imageClick);
        for(var i = 0; i < buttons.length; i++){
            buttons[i].addEventListener("click", function(e){
            $("#mainContent").hide();
            index = this.name.substring(1);
            insert = "#qa" + index;
            data = $(insert).html();
            main.innerHTML = newhtml;
            img = document.getElementById("bigimg");
            img.src = this.id;
            back = document.getElementById("goback");
            $('#details').html(data);
            $("#mainContent").fadeIn(1000);
            back.addEventListener("click", function(e){
                $("#mainContent").hide();
                main.innerHTML = mainhtml;
                $("#mainContent").fadeIn(1000);
                processButtons();
            }, false);
            }, false);
        }
        };
        function imageClick(){
                    $("#mainContent").hide();
                    main.innerHTML = newhtml;
                    img = document.getElementById("bigimg");
                    img.src = this.src;
                     $("#mainContent").fadeIn(2000);
                    back = document.getElementById("goback");
                    back.addEventListener("click", function(e){
                    $("#mainContent").hide();
                    main.innerHTML = mainhtml;
                    $("#mainContent").fadeIn(2000);
                    processButtons();
            });
        }
        function processButtons(){
            $('.tutorViewAnswerImage').click(imageClick);
            for(var i = 0; i < buttons.length; i++){
                    buttons[i].addEventListener("click", function(e){
                    $("#mainContent").hide();
                    index = this.name.substring(1);
                insert = "#qa" + index;
                data = $(insert).html();
                main.innerHTML = newhtml;
                img = document.getElementById("bigimg");
                img.src = this.id;
                back = document.getElementById("goback");
                $('#details').html(data);
                $("#mainContent").fadeIn(2000);
            back.addEventListener("click", function(e){
                $("#mainContent").hide();
                main.innerHTML = mainhtml;
                $("#mainContent").fadeIn(2000);
                processButtons();
                }, false);
            }, false);
        }
        }
    </script>
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
				<li><a href="browse.php" title="Browse Unanswered Questions">Browse</a></li>
				<li><a href="balance.php" title="Withdraw Snapcash">Balance</a></li>
				<li><a href="profile.php" title="Browse Unanswered Questions">Profile</a></li>
				<li class="selected" ><a href="viewAnswers.php" title="View Answer History">My Answers</a></li>
			</ul>
		</div>
		<div id="hiddenhtml">
        <!--
            <div id="zoomborder">
                <h2><button id="goback">Go Back</button></h2>
                <div id="details"></div>
                <img id="bigimg" alt='question image' src=\"" + this.id + "\" >
            </div>
        -->
        </div>
		<div id="mainContent">
    		<h1 title="Your answer history">MY ANSWERS</h1>
            
            <?php
            
            for($i = 0; $i < sizeof($answerInfo['questions']); $i++)
            {
            
            $question = $answerInfo['questions'][$i];
            $answer = $answerInfo['answers'][$i];
            $imgurl = htmlentities($question['image_url'], ENT_QUOTES);
            ?>
            	<div class="tutorViewAnswerContainer" id="<?php echo 'container'. $i;?>">
	                <div class="tutorViewAnswersLeft">
	                	<img class="tutorViewAnswerImage" alt="Question Image" title="Click to zoom in" src="<?php echo $imgurl; ?>" />
                        <button name = "<?php echo 'a' . $i ?>" id="<?php echo $imgurl ?>" title="View all details.">View Full Size</button>
		                <label id="<?php echo 'cat'. $i;?>" ><?php echo $question['category'] . " > " . $question['subcategory']; ?></label>
		                <label id="<?php echo 'date'. $i;?>"><?php 
                        $str = $question['date_created'];
                        $year = substr($str,5,2) . "/" . substr($str,8,2) . "/" . substr($str,2,2);
                        $hour = intval(substr($str, 11, 2));
                        if ($hour > 12){
                            $hour = $hour - 12;
                            $suffix = "PM";
                        }
                        else
                            $suffix = "AM";
                        echo $year . " " . $hour . substr($str, 13, 3) . " " . $suffix; ?></label>
                    </div>
                    
	                <div class="tutorViewAnswersRight" id="<?php echo "qa" . $i ?>">	
						
						<label>Question</label>
						<p>
						<?php echo $question['description']; ?>
						</p>
						
						<label>Answer</label>
						<p>
						<?php echo $answer['text']; ?>
						</p>
	                </div>
	                
	                <div class="tutorViewAnswersRating">
						<table>
                        <tr>
                        <td>
                        <a id="balance" href="balance.php" title="Click to Withdraw SnapCash Now">
                        <h1><img src="http://monkbananas.com/cashicon.png" title="Your SnapCash" alt="SnapCash Earnings" height="60px"/>
    					<span id="<?php echo 'pay'. $i;?>"><?php echo rand(1,10) * 10 ?></span></h1></a>
						<?php
						
						 if (isset($answer['rating'])) {
						 	
						 	if ($answer['rating'] == 0) {
							 	
							 	echo '<p>&lt;Rejected&gt;</p>';
							 	
						 	} 
                             else {
						 
								$stars = '';
								for ($j = 0; $j < $answer['rating']; $j++) {
									$stars = $stars . '&#9733; ';
								}
								echo sprintf('<p class="ratingStars">%s</p>', $stars);	
							}
							
						} else {
							echo '<p>Awaiting student response</p>';
						}
						?>
						</td>
                        </tr>
                        </table>
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
