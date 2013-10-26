<?php include('logoutheader.php');?>
    <div id="mainContainer">

		<div class="divider"></div>

		<div id="loginContainer" >
<form action='resetemailsent.php' name="resetpass" id="resetpass" method= "post">
			<?php 
            $pass = ""; 
            for ($x = 0; $x < 3; $x++) 
                $pass .= chr(rand(65, 90)) . chr(rand(97, 122)) . chr(rand(48, 57));
            //pattern: upper,lower,number,upper,lower,number,upper,lower,number 
            // Echo the information using sprintf 
            // Escape special html characters to enhance XSS security 
            echo sprintf("<label>Enter your E-mail Address</label><input type='text' name='toemail'>", htmlspecialchars($responseObj['first_name'])); 
            echo sprintf("<input type='hidden' name='newpass' value='" . $pass ."'>", htmlspecialchars($responseObj['first_name'])); ?> 
            <input type="submit" id="submitButton" value="Send New Password"> <p>IMPORTANT: It takes 48 hours for your new password to take effect. Until then you can use your old password.</p> <p>For security purposes, you have been logged out.</p> </form>
			<p><a href="index.php">Return to Main Login page</a>
</form>
				
		</div>

		<div class="divider"></div>
<?php include('footer.php');?>
	</div>
</body>
</html>
