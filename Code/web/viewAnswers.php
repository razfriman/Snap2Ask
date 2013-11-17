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
    if (window.location.href.indexOf("&year=") > 0)
    {
        //alert("Submission succesful");
    }
     document.enablesort.onsubmit = function(e) {
            e.preventDefault();
            newhtml = $("#hiddenhtml").html();
            newhtml = newhtml.substring(newhtml.indexOf("<!--") + 4, newhtml.indexOf("-->"));
            document.enablesort.innerHTML = newhtml;
            document.enablesort.startsort.onclick = sortall();
    }; 
    function sortall(){
        //contid = $(".tutorViewAnswerContainer").eq(0).find(".datetime").attr("id");
        array = $(".tutorViewAnswerContainer");
        anscmp(array[0], array[1]);
        function anscmp(a,b){
            str1 = $(a).find(".datetime").attr("id");
            str2 = $(b).find(".datetime").attr("id");
            return str2.localeCompare(str1);
        }//end anscmp
        array.sort(anscmp);
        mainhtml = $("#mainContent").html();
        finalhtml = mainhtml.substring(0, mainhtml.indexOf("</form>"));
        for (k = 0; k < array.length; k++){
            finalhtml += array[k].outerHTML;
        }
        $("#mainContent").html(finalhtml);
    }//end sortAll
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
	<script src="js/lightbox-2.6.min.js"></script>
	<link href="css/lightbox.css" rel="stylesheet" />
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
            <!--<label for="sortopts">Sort By:</label>
                      <select name="sortopts">
                        <option>Most Recent First</option>
                        <option>Highest Pay First</option>
                        <option>Lowest Pay First</option>
                    </select>
                    <input type='submit' name="startsort" value='submit'/>
            -->
        </div>
		<div id="mainContent">
    		<h1 title="Your answer history">MY ANSWERS</h1>
                <form name="enablesort" action="profile.php" method="post">
                    <input type="submit" value="Enable Advanced Search" name="enablebut" />
                </form>
            <?php
            
            for($i = 0; $i < sizeof($answerInfo['questions']); $i++)
            {
            
            $question = $answerInfo['questions'][$i];
            $answer = $answerInfo['answers'][$i];
            $imgurl = htmlentities($question['image_url'], ENT_QUOTES);
            ?>
            	<div class="tutorViewAnswerContainer" id="<?php echo 'container'. $i;?>">
	                <div class="tutorViewAnswersLeft">

	                	<?php
	                	
	                	// Image
	                	echo sprintf('<a class="image-link" href="%s" title="%s" data-lightbox="example-%s">', $imgurl, $question['description'], $i);
	                	echo sprintf('<img class="tutorViewAnswerImage" alt="Question Image" src="%s" />', $imgurl);
	                	echo sprintf('</a>');
	                	
	                	// Category
	                	echo sprintf('<label>%s - %s</label>', $question['category'], $question['subcategory']);
	                	
	                	// Date
                        $str = $question['date_created'];
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
									<p><?php echo rand(1,10) * 10 ?></p>
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
            }
            ?>
			
		</div>
	</div>
	
	<?php include('footer.php') ?>

</body>
</html>
