<?php

//eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee//
//                                                                      //
//      This function gets the values related to the answer that has the//
//      tutorID and questionID passed as parameter in the function      //
//      It returns Json		                                        //
//                                                                      //
//eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee//


	include "makeJsonFromRows.php";

        $dbConnection = mysql_connect("localhost", "cProject", "snap2ask");
        if (!$dbConnection)
        {
                die('Connection failure: ' . mysql_error());
        }
        
        $questionID = $_POST['questionID2'];
        $tutorID = $_POST['tutorID2'];
        
        mysql_select_db("snap2ask", $dbConnection) or die ("It couldn't select snap2ask database. Error: " . mysql_error());

        $getAnswer = "SELECT id, question_id, tutor_id, text, rating, status, date_created FROM answers WHERE tutor_id = {$tutorID} AND question_id = {$questionID};";
        $myAnswer = mysql_query($getAnswer);
//	while ($row = mysql_fetch_assoc($myAnswer))
//	{
//		echo $row['id'] . " " . $row['question_id'] . " " . $row['text'];
//		echo "<br>";
//	}
	echo makeJsonFromRows($myAnswer, array('question_id', 'tutor_id', 'text', 'rating', 'status', 'date_created'));

	mysql_close($dbConnection);
?>
