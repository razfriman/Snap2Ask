/*
Description: 
	Scrip to test the conection between the database and the local host.

I created a new user in mysql called cProject with password sanp2ask.
If we all create this same user with the same password in our mysql this
scrip should run for all of us.
Please try it and let me know if it doesn't work for any of you.
*/

<html>
	<head>
		<title>Testing database/server conection</title>
	</head>
<body>
	<?php 
		echo '<h1>Virtual host is working</h1>';
		$connection = mysql_connect("localhost", "cProject", "snap2ask");
		if (!$connection)
		{
			die('Connection failure: ' . mysql_error());
		}
		echo '<h1>Connection stablished</h1>';

		mysql_select_db("snap2ask", $connection) or die("It could select snap2ask database. Error: " . mysql_error());
		echo '<h1>snap2ask database selected</h1>';

		echo '<h1>List of usernames in the database:</h1>';
		$result = mysql_query("SELECT username FROM users");
		while($row = mysql_fetch_array($result))
		{
			echo $row['username'];
			echo "<br />";
		} 
		
		mysql_close($connection);

	?>
</body>
</html> 
