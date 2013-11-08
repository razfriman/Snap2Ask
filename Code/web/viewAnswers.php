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
		</div>
		
		
		
		<div id="mainContent">
			<style>
td.questionItem {
	margin-left: -1em;
    margin-top: 0.55em;
    width: 500px;
    height: 224px;
}
    </style>
			<h1>MY ANSWERS</h1>
			<table>
            <?php
            for($x = 0; $x < 20; $x++)
            {
                                $y = $x+80;
                $b = "https://snap2ask.s3.amazonaws.com/" . $y . ".jpeg";
            ?>
                <tr>
                <td>
                <div class="questionItem" style="display: inline-block; opacity: 1;">
                <img class="questionImage" src=<?php echo $b; ?> >
                <label>Science</label><label> Chemistry</label>
                <label>Date: 2013-11-07 04:07:20</label>
                <!--Insert star images here!-->
                <label>&#9733;&#9734;&#9734;&#9734;&#9734;</label>
                </div>
                </td>
                <td class="questionItem">
                <div id="view-question-right">
    				
					<label>Your Pay:</label>
					<p><?php echo $question_info['category']; ?></p>
					
					<label>Description:</label>
					<p><?php echo $question_info['date_created']; ?></p>
					
					<label>Answer:</label>
					<p><?php echo $question_info['description']; ?></p>
				</div>
                </td>
                </tr>
                
            <?php
            }
            ?>
                
                </table>
			
		</div>
	</div>
	


	<?php include('footer.php') ?>

</body>
</html>
