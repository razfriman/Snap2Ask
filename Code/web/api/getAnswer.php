<?php

//eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee//
//                                                                      //
//      This function gets all the answers posted in response to the 	//
//      a specific question id						//
//      It returns Json		                                        //
//                                                                      //
//eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee//


	include "makeJsonFromRows.php";

	//connect to database
        $dbConnection = mysql_connect("localhost", "cProject", "snap2ask");
        if (!$dbConnection)
        {
                die('Connection failure: ' . mysql_error());
        }
        
        $questionID = $_POST['questionID2'];

        //select snap2ask database
        mysql_select_db("snap2ask", $dbConnection) or die ("It couldn't select snap2ask database. Error: " . mysql_error());

	//prepare query
        $getAnswer = "SELECT id, question_id, tutor_id, text, rating, status, date_created FROM answers WHERE question_id = {$questionID};";

        $myAnswer = mysql_query($getAnswer);
//	while ($row = mysql_fetch_assoc($myAnswer))
//	{
//		echo $row['id'] . " " . $row['question_id'] . " " . $row['text'];
//		echo "<br>";
//	}

	//get the answers in json
	echo makeJsonFromRows($myAnswer, array('question_id', 'tutor_id', 'text', 'rating', 'status', 'date_created'));

	mysql_close($dbConnection);
?>
