<?php
$user = $_POST['email'];
$usersubject = "Your Snap2Ask Login Credentials";
$userheaders = "From: " . "service@snap2ask.com"  . "\n";
$usermessage = "Thanks for signing up to be a member of Snap2Ask.com. You have been accepted.Here is your password:";
$usermessage .= $_POST['pass'];
//Then set the code to mail that information.
mail($user,$usersubject,$usermessage,$userheaders);
echo "Check youe e-mail @" . $_POST['email'];
?>