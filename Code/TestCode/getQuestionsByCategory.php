<!-- Accepts a "get" request for a particular category of questions. Returns a json object of unanswered questions in that particular category, in chronological order (FIFO), posted less than a week ago. A question is considered "inactive" if it has been over 7 days and it is still unanswered.-->

<?php

	include 'makeJsonFromRows.php';

	$host = "localhost:3306";
	$username = "root";
	$password = "ArikElik0058";
	$connection = mysql_connect($host, $username, $password);
	if (!$connection)
	{
		die('Connection failure: ' . mysql_error());
	}
	mysql_select_db("snap2ask", $connection) or die("It could select snap2ask database. Error: " . mysql_error());
	$category_id = $_POST['category'];
	$sql_test = "SELECT description, category_id, subcategory_id, image_url, date_created FROM questions";
	$sql_select = "SELECT * FROM questions WHERE category_id = {$category_id} AND status=0 AND date_created > unix_timestamp(now() - interval 7 day)";
	$result = mysql_query($sql_select);
	echo makeJsonFromRows($result, array('description','category_id','subcategory_id','image_url','date_created'));

?>