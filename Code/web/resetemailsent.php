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
            <p>Your new password has been sent to <?php echo $_POST['toemail'] . 
            "</p><p>For Iteration 1 verification, your new password is:" . $_POST['newpass']?></p>
            <p>Confirm this password with the password received in your e-mail inbox!</p> 
	   <p>IMPORTANT: It may take 48 hours for your new password to take effect on our site. Until then use your old password.</p>
    		<!--POPULATE PROFILE INFORMATION HERE-->
		</div>
        <p><a href="resetpass.php" name="login" id="login">Send another E-mail</a></p>
        <p><a href="index.php" name="login" id="login">Login</a></p>

		<div class="divider"></div>
<?php include('footer.php');?>
	</div>
</body>
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
