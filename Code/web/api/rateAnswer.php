<?php

//eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee//
//									//
//	This function set the rate of an answers to a specific value 	//
//									//
//eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee//

	//connect to database
	$dbConnection = mysql_connect("localhost", "cProject", "snap2ask");
	if (!$dbConnection)
	{
		die('Connection failure: ' . mysql_error());
	}

	//select sanap2ask  database 
	mysql_select_db("snap2ask", $dbConnection) or die ("It couldn't select snap2ask database. Error: " . mysql_error());

	//prepare query
	$answerID = $_POST['answerID'];
	$rate = $_POST['newRate'];
	$myQuery = "UPDATE answers set rating = {$rate} where id = {$answerID};";
	
	if (!mysql_query($myQuery))
	{
		die("Impossible to change the rating of the answer" . mysql_error());
	}

	echo "rate of the answer with id " . $answerID . "sucesfully changed";

	mysql_close($dbConnection);

?>
