<?php 

//eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee//
//                                                                      //
//      This script inserts an answer in the database and increase the  //
//      status of the question it is answering                          //
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
	echo "<h3>Answer succesfully upload to the database. Captured time: " . $date . "<br/><h3>";	

	//update the status of the question that was just answered
      
        //get number of answers to that question
        $getNumberAnswers = "SELECT count(id) from answers where question_id = {$questionID};";

        $numberAnswersResponse = mysql_query($getNumberAnswers);
        $row = mysql_fetch_assoc($numberAnswersResponse);
        $numberAnswers = $row['count(id)'];
	
      	echo "<h3>Number answers = " . $numberAnswers . "<br /><h3>";
	$numberAnswers = $numberAnswers + 1;
	//update the status of the question
	$updateStatus = "UPDATE questions set status = {$numberAnswers} where id = {$questionID};";

	if (!mysql_query($updateStatus))
	{
		die("Impossible to update the status of the question " . mysql_error()); 
	}

?> 
