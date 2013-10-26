<?php /*
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
		$headers = "From: snap2ask";
		mail($to, $subject, $message, $headers); 
		echo "An email has been sent with your new password";

 */
?>
<?php include('logoutheader.php');?>
    <div id="mainContainer">

    	<div class="divider"></div>

		<div id="loginContainer" >
            <h1>CHECK YOUR E-MAIL</H1>
            <p>
            <a href="http://mail.yahoo.com" name="yahoo" id="yahoo" class="email">Yahoo!</a>
            <a href="http://hotmail.com" name="yahoo" id="yahoo" class="email">Hotmail</a>
            <a href="http://gmail.com" name="yahoo" id="yahoo" class="email">Gmail</a>
            </p>
<<<<<<< HEAD
            <p>Your new password has been sent to <?php echo $_POST['toemail'] . 
            "</p><p>For Iteration 1 verification, your new password is:" . $_POST['newpass']?></p>
            <p>Confirm this password with the password received in your e-mail inbox!</p> 
	   <p>IMPORTANT: It may take 48 hours for your new password to take effect on our site. Until then use your old password.</p>
=======
            <p>Your new password has been sent to 
            
            
            <?php 
            echo $_POST['toemail'] . 
            "</p><p>For Iteration 1 verification, your new password is:" . $_POST['newpass'];
            
            
            
            //ELENA THIS SPACE JUST FOR YOU! YOUR CODE IS IN COMMENTS ABOVE!
            
            //SORRY FOR THE CONFUSION! I ALREADY DID THIS JUST FORGOT TO COMMIT IT PROPERLY!
            
            
            
            
            
            ?></p>
            <p>Confirm this password with the password received in your e-mail inbox!</p>
            <p>IMPORTANT: It may take 48 hours for your new password to take effect on our site. Until then use your old password.</p>
>>>>>>> 6dc52caf19e7f730154085b91bf60aefb2c4b854
    		<!--POPULATE PROFILE INFORMATION HERE-->
		</div>
        <p><a href="resetpass.php" name="login" id="login">Send another E-mail</a></p>
        <p><a href="index.php" name="login" id="login">Login</a></p>

		<div class="divider"></div>
<?php include('footer.php');?>
	</div>
</body>
<<<<<<< HEAD
</html>
<?php
	$email = $_POST['toemail'];
	$password = $_POST['newpass'];
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
if ($myUser != NULL)
{
	$resetPass = "UPDATE users set password = '{$password}' where id = {$myUser['id']};";
        if (!mysql_query($resetPass))
        {
                die ("Impossible to reset Password" . mysql_error());
        }

}
else
{
echo "couldn't find email " . $email . " in our database\r\n";
}
?>
=======
</html>
>>>>>>> 6dc52caf19e7f730154085b91bf60aefb2c4b854
