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
		
		<!--ans.php includes main content div-->
		<!--remove style tag from ans.php-->
		<!--answer file with sort functions by time, rating, and pay-->
 <style>
    td.questionItem {
        margin-left: -1em;
        margin-top: 0.55em;
        width: 500px;
        height: 206px;
        color:white
    }
    label.answer
    {
        position:relative;
        color: blue;
    }
    .stars
    {
        color: yellow;
    }
    .rating
    {
        margin-left: 1em;
    }
    </style>
    <?php
    $responseObj = getUserInfo(true);

$answerInfo = getAnswerInfo($responseObj['id']);
     /*for ($x = 0; $x < 150; $x++){
      $qdata = getQuestionInfo($x);
      //var_dump($qdata);
      for ($y = 0, $numAns = count($qdata["answers"]); $y < $numAns; $y++){
            $ansdata = $qdata["answers"][$y];
            if ($ansdata['tutor_id'] === $_SESSION['user_id'])
            {*/
                /*
                echo '<br>' . 'hello' . $_SESSION['user_id'];
                echo '<br>' . $qdata['id'];
                echo '<br>' . $qdata['student_id'];
                echo '<br>' . $qdata['description'];
                echo '<br>' . $qdata["subcategory"];
                echo '<br>' . $qdata["category"];
                echo '<br>' . $qdata["image_url"];
                echo '<br>' . $qdata["date_created"];
                echo '<br>' . $ansdata['text'];
                echo '<br>' . $ansdata['tutor_id'];
                var_dump($ansdata['rating']);
                
            }
        }
     }*/
      //echo '<br>' . $qdata["answers"][0]['tutor_id'] === $_SESSION['user_id'];
     ?>
<div id="mainContent">
            <h1>MY ANSWERS</h1>
            <?php 
            var_dump(getAnswerInfo($_SESSION['user_id']));
            
            class Answer
            {
                public $id;
                public $cat;
                public $subcat;
                public $rating;
                public $desc;
                public $pay;
                public $datestr;
                public $answer;
                public $imgurl;
            }
            $answers = array();
            $qdata = getAnswerInfo($_SESSION['user_id']);
                //var_dump($qdata);
                for ($y = 0, $numAns = count($qdata["answers"]); $y < $numAns; $y++){
                    $ansdata = $qdata["answers"][$y];
                        $answers[$k] = new Answer();
                        $answers[$k]->id = $qdata['questions'][$y]['id'];
                        $answers[$k]->pay = rand(1,10) * 10;
                        $answers[$k]->datestr = $qdata["date_created"];
                        $answers[$k]->cat = $qdata['questions'][$y]["category"];
                        $answers[$k]->subcat = $qdata['questions'][$y]["subcategory"];
                        $answers[$k]->rating = rand(1,5);
                        $answers[$k]->desc = $qdata['questions'][$y]['description'];
                        $answers[$k]->answer = $ansdata['text'];
                        $answers[$k]->imgurl = $qdata["image_url"];
                        $k++;
                    }
                }
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
            <table>
            <?php
            function stars($numStars)
            {
               echo '<span class="rating">Rating: </span>
               <span class="stars">';
               if (!$numStars){
                echo '&#9733';
                return;
               }
               for ($count = 0; $count < $numStars; $count++)
                    echo '&#9733';
                echo '</span>';
            }
            
            if ($_POST['order'] === 'Most Recent First')
            {
                usort($answers, "recent");
            }
            else if ($_POST['order'] === 'Highest Rating First')
            {
                usort($answers, "ratinghigh");
            }
            else if ($_POST['order'] === 'Lowest Rating First')
            {
                usort($answers, "ratinglow");
            }
            else if ($_POST['order'] === 'Highest Pay First')
            {
                usort($answers, "payhigh");
            }
            else if ($_POST['order'] === 'Lowest Pay First')
            {
                usort($answers, "paylow");
            }
                for ($y = 0, $max = count($answers); $y < $max; $y++){
            ?>
                <tr>
                <td>
                <div class="questionItem" style="display: inline-block; opacity: 1;">
                <img class="questionImage" src=<?php echo $answers[$y]->imgurl ?> >
                <label><?php echo $answers[$y]->cat ?></label><label><?php echo $answers[$y]->subcat ?></label>
                <label><?php echo $answers[$y]->datestr ?></label>
                </div>
                </td>
                <td class="questionItem">
                <div id="view-question-right">
        			
					<label>Your Pay: 
					<?php echo $answers[$y]->pay; stars($answers[$y]->rating); ?>
                    </label>
					<label class="answer">Question: </label>
					<label class="text"><?php echo $answers[$y]->desc ?></label>
					
					<label class="answer">Answer:</label>
					<label class="text"><?php echo $answers[$y]->answer ?></label>
				</div>
                </td>
                </tr>
                
            <?php
            }
            ?>
                
                </table>
			
		</div>

		
	</div> <!--end content div-->
	
	<?php include('footer.php') ?>

</body>
</html>
