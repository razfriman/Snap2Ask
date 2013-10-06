<?php 
	echo "<p>Stablishing connection to database <br/></p>";

	$connection = mysql_connect("localhost", "cProject", "snap2ask");
	if (!$connection)
	{
		die('Connection failure: ' . mysql_error());
	}
	echo "<p>Conection stablished sucessfully <br/><p>";
		
	$questionID = $_POST['questionID'];
	$tutorID = $_POST['tutorID'];
	$text = $_POST['text'];
	$date = $_SERVER['RESQUEST_TIME'];

	//calling the function to insert the a new answer in the database
	insertAnswer($connection, $questionID, $tutorID, $text, $date);

	echo "<p>The question was succesfully inserted<br/><p>";

	//closing the db connection
	mysql_close($connection);

	
//eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee//
//									//
//	This fucntion inserts an answer in the database			//
//	It doesn't return anything					//
//									//
//eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee//									
	function insertAnswer($dbConnection, $questionID, $tutorID, $text, $date)
	{
		mysql_select_db("snap2ask", $dbConnection) or die ("It couldn't select snap2ask database. Error: " . mysql_error());

		$insertAnswer = "INSERT INTO answers(question_id, tutor_id, text, date_created) VALUES ({$questionID}, {$tutorID}, '{$text}', '{$date}');";
		
		if (!mysql_query($insertAnswer))
		{
			die("Impossible ot insert Answer" . mysql_error());
		}
	}

?> 
