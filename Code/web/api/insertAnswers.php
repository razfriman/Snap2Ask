<?php 

//eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee//
//                                                                      //
//      This script inserts an answer in the database          	        //
//      It doesn't return anything                                      //
//                                                                      //
//eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee// 

	$dbConnection = mysql_connect("localhost", "cProject", "snap2ask");
	if (!$dbConnection)
	{
		die('Connection failure: ' . mysql_error());
	}
	
	$questionID = $_POST['questionID'];
	$tutorID = $_POST['tutorID'];
	$text = $_POST['text'];
	$date = time();
	$date =date("Y-m-d H:i:s");

	mysql_select_db("snap2ask", $dbConnection) or die ("It couldn't select snap2ask database. Error: " . mysql_error());

	$insertAnswer = "INSERT INTO answers(question_id, tutor_id, text, date_created) VALUES ({$questionID}, {$tutorID}, '{$text}', '{$date}');";
		
	if (!mysql_query($insertAnswer))
	{
		die("Impossible to insert Answer" . mysql_error());
	}
	echo "answer succesfully upload to the database. Captured time: " . $date;	
	mysql_close($dbConnection);

?> 