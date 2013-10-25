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
