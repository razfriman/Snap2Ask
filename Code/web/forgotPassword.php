<?php
	$email = $_GET['email'];
	//connect to databe
	$dbConnection = mysql_connect("localhost", "cProject", "snap2ask");
	if (!$dbConnection)
	{
		die("Connection failure: " . mysql_error());
	}
	mysql_select_db("snap2ask", $dbConnection) or die ("It couldn'tslect snap2ask database. Error: " . mysql_error());
	
	$getUser = "SELECT * from users WHERE email = '{$email}';";
	$user = mysql_query($getUser);
	$myUser = mysql_fetch_assoc($user);
	//if email in our database 
	if ($myUser != NULL)
	{
		//generate password
		$password = "elena";
		//update new password in the db 
		$resetPass = "UPDATE users set password = '{$password}' where id = {$myUser['id']};";
		if (!mysql_query($resetPass))
		{
			die ("Impossible to reset Password" . mysql_error());
		}
		echo "new password: " . $password;
;
	//email new password
	}
?>
