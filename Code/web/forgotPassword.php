<?php
$email = $_GET['email'];
//$email = "mvillamilrod@smu.edu";
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
	$password = rand(100000, 1000000);
	$password = (string)$password;
	//update new password in the db 
	$resetPass = "UPDATE users set password = '{$password}' where id = {$myUser['id']};";
	if (!mysql_query($resetPass))
	{
		die ("Impossible to reset Password" . mysql_error());
	}
	$subject = "Snap2ask Reset Password";
	$message = "Your new password is: " . $password;
	$headers = "From: snap2ask.com";	
	if (mail($email, $subject, $message, $headers))
	{ 
		echo "An email has been sent to " . $email . " with your new password";
	}
	else{
		echo"Impossible to send email to " . $email . "\r\n";
	}
//email new password
}
else 
{
	echo "couldn't find email " . $email . "\n";
}
?>
