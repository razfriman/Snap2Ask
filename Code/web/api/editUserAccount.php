<?
// A function to edit an Account already existing in the Database

function editUser($new_fname, $new_lname, $new_email, $old_email)
{

	$host = "localhost";
	$username = "cProject";
	$password = "snap2ask";

	$dbConnection = mysql_connect($host, $username, $password);
	if (!$dbConnection)
	{
		die('Connection failure: ' . mysql_error());
	}


	mysql_select_db("snap2ask", $connection) or die("It could select snap2ask database. Error: " . mysql_error());	

	$editUser = "UPDATE users SET first_name = '{$new_fname}', last_name = '{$new_lname}', email = '{$new_email}' WHERE email = '{$old_email}';";

	
	if(!mysql_query($editUser))
	{	
		die("Cannot edit User" . mysql_error());
	}

	mysql_close($dbConnection);
}


?>
