<?
// A function to delete a user's account from the database 
function deleteUser($email)
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

	$deleteUser = "DELETE * from users where email = " . $email . ";";

	
	if(!mysql_query($deleteUser))
	{	
		die("Cannot delete User" . mysql_error());
	}

	mysql_close($dbConnection);
}

?>
