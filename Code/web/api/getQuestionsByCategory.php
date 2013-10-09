<?php
/*
Author: Roman Stolyarov
Date: 10/08/13
This script accepts a "get" request for unanswered questions less than a week old. It can select them by a particular category or select all of them if $category_id="". It returns a JSON object of these questions in chronological order (FIFO). A question is considered "inactive" if it has been over 7 days and it is still unanswered.*/

	//Generic function to create a JSON object from selected rows and a list of column names.
	include 'makeJsonFromRows.php';
	//Connect to database.
	$host = "localhost:3306";
	$username = "cProject";
	$password = "snap2ask";
	$connection = mysql_connect($host, $username, $password);
	if (!$connection)
	{
		die('Connection failure: ' . mysql_error());
	}
	mysql_select_db("snap2ask", $connection) or die("It could select snap2ask database. Error: " . mysql_error());

	//Get the category_id. If it is empty, write query to select all unanswered questions less than a week old. If there is a specified category, add the category name to the WHERE clause.
	$category_id = $_POST['category'];
	$sql_select = "";
	if ($category_id != ""){
		$sql_select = "SELECT * FROM questions WHERE category_id = {$category_id} AND status=0 AND date_created > unix_timestamp(now() - interval 7 day)";
	}else{
		$sql_select = "SELECT * FROM questions WHERE status=0 AND date_created > unix_timestamp(now() - interval 7 day)";
	}

	//Execute the query and create the Json object.
	$result = mysql_query($sql_select);
	echo makeJsonFromRows($result, array('description','category_id','subcategory_id','image_url','date_created'));

?>