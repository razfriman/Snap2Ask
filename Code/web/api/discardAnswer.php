<?php

//eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee//
//									//
//	This function sets the status of an aswer to discarded  	//
//									//
//eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee//

	//connect to databse
	$dbConnection = mysql_connect("localhost", "cProject", "snap2ask");
	if (!$dbConnection)
	{
		die('Connection failure: ' . mysql_error());
	}
	
	//select snap2ask database
	mysql_select_db("snap2ask", $dbConnection) or die ("It couldn't select snap2ask databse. Error: " . mysql_error());

	$answerID = $_POST['answerID'];
	
	$myQuery = "UPDATE answers set status = 'discarded'  where id = {$answerID};";

	if (!mysql_query($myQuery))
	{
		die("Impossible to update the status of the answer" . mysql_error());
	}

?>
