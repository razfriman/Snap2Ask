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
    document.enablesort.color.onchange=setcol;
    
    function setcol(e){
        choice = document.enablesort.color.value;
        if(choice == "Blue")
            $(".tutorViewAnswerContainer").css('box-shadow', '10px 10px 20px blue');
        else if(choice == "Red")
           $(".tutorViewAnswerContainer").css('box-shadow', '10px 10px 20px red');
        else if(choice == "Green")
            $(".tutorViewAnswerContainer").css('box-shadow', '10px 10px 20px green');
        else if(choice == "Orange")
            $(".tutorViewAnswerContainer").css('box-shadow', '10px 10px 20px orange');
        else if(choice == "Gray")
            $(".tutorViewAnswerContainer").css('box-shadow', '10px 10px 20px gray');
        else if(choice == "Black")
            $(".tutorViewAnswerContainer").css('box-shadow', '10px 10px 20px black');
    }
    backup = $(".tutorViewAnswerContainer");
    oldformhtml = document.enablesort.innerHTML;
     document.enablesort.onsubmit = function(e) {
            e.preventDefault();
            newhtml = $("#hiddenhtml").html();
            newhtml = newhtml.substring(newhtml.indexOf("<!--") + 4, newhtml.indexOf("-->"));
            document.enablesort.innerHTML = newhtml;
            sortall();
            document.enablesort.disable.onclick = function(e){
                    e.preventDefault();
                    document.enablesort.innerHTML = oldformhtml;
                    array = backup;
                    finalhtml = "";
                    for (k = 0; k < array.length; k++){
                    finalhtml += array[k].outerHTML;
                    }
                    $("#results").html(finalhtml);
                    document.enablesort.color.onchange = setcol;
                };
            document.enablesort.startsort.onclick = function(e){
                e.preventDefault();
                sortall();
            };
    }; 
    function sortall(){
        //contid = $(".tutorViewAnswerContainer").eq(0).find(".datetime").attr("id");
        start = document.enablesort.startdate.value
        end = document.enablesort.enddate.value
        validdate = check(start);
        validdate2 = check(end);
        array = backup;
        array2 = [];
        if (validdate && validdate2){
            for (ind = 0; ind < array.length; ind++){
                ansdate = $(array[ind]).find(".datetime").attr("id");
                if (ansdate.localeCompare(start) > 0 && ansdate.localeCompare(end) < 0){
                    array2.push(array[ind]);
                }
            }
            array = array2;
        }
        else if (validdate){
            for (ind = 0; ind < array.length; ind++){
                ansdate = $(array[ind]).find(".datetime").attr("id");
                if (ansdate.localeCompare(start) > 0){
                    array2.push(array[ind]);
                }
            }
            array = array2;
        }
        else if (validdate2){
            for (ind = 0; ind < array.length; ind++){
                ansdate = $(array[ind]).find(".datetime").attr("id");
                if (ansdate.localeCompare(end) < 0){
                    array2.push(array[ind]);
                }
            }
            array = array2;
        }
        function anscmp(a,b){
            str1 = $(a).find(".datetime").attr("id");
            str2 = $(b).find(".datetime").attr("id");
            return str2.localeCompare(str1);
        }//end anscmp
        function paycmp(a,b){
            str1 = $(a).find(".snappay").attr("id");
            str2 = $(b).find(".snappay").attr("id");
            return str2.localeCompare(str1);
        }//end anscmp
        function opppaycmp(a,b){
            str1 = $(a).find(".snappay").attr("id");
            str2 = $(b).find(".snappay").attr("id");
            return str1.localeCompare(str2);
        }//end anscmp
        if(document.enablesort.sortopts.value == "Highest Pay First")
            array.sort(paycmp);
        else if(document.enablesort.sortopts.value == "Lowest Pay First")
            array.sort(opppaycmp);
        else
            array.sort(anscmp);
        mainhtml = $("#mainContent").html();
        finalhtml = "";
        for (k = 0; k < array.length; k++){
            finalhtml += array[k].outerHTML;
        }
        $("#results").html(finalhtml);
    }//end sortAll
    function check(instr){
        if(instr.length !== 10)
            return false;
        else
            return true;
    }
    function submithandle(){
        document.enablesort.startsort.onclick = sortall();
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
            <!--
                    <input type="submit" name="disable" value="Disable Advanced Search" /><br/>
            <label for="sortopts">Sort By:</label>
                      <select name="sortopts">
                        <option>Most Recent First</option>
                        <option>Highest Pay First</option>
                        <option>Lowest Pay First</option>
                    </select>
                    <input type="date" placeholder="Start Date" name = "startdate"/>
                    <input type="date" placeholder="End Date" name="enddate"/>
                    <input type='submit' name="startsort" value='submit'/>
            -->
        </div>
		<div id="mainContent">
        <?php
            if(sizeof($answerInfo['questions']) < 1)
                    echo "<h1>Click 
                    <a href='browse.php'>here</a>
                    to answer questions now.</h1>";
            else{
                
        ?>
    		<h1 title="Your answer history">MY ANSWERS</h1>
                <form name="enablesort" action="profile.php" method="post" class="oneliner" id="sortform">
                    <input type="submit" value="Enable Advanced Search" name="enablebut" />
                    <select id="color" name="color">
                        <option>Customize</option>
                        <option>Blue</option>
                        <option>Red</option>
                        <option>Green</option>
                        <option>Black</option>
                        <option>Orange</option>
                        <option>Gray</option>
                    </select>
                </form>
                <div id="results">
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
                                    <?php $pay = rand(1,10) * 10; ?>
									<p class="snappay" id="<?php echo $pay; ?>"><?php echo $pay ?></p>
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
