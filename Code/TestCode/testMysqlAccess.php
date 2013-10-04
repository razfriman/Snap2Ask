<?php 
	$connection = mysql_connect("localhost", "cProject", "snap2ask");
	if (!$connection)
	{
		die('Connection failure: ' . mysql_error());
	}

	mysql_select_db("snap2ask", $connection) or die("It could select snap2ask database. Error: " . mysql_error());
	
	$result = mysql_query("SELECT username FROM users");
	while($row = mysql_fetch_array($result))
	{
		echo $row['username'];
		echo "<br />";
	} 
		
	mysql_close($connection);


?> 
