<?php
var $user = $_POST['email'];
var $usersubject = "Feedback Submission";
var $userheaders = "From: " . "service@snap2ask.com"  . "\n";
var $usermessage = "We have received your feedback! A Snap2Ask representative will get in touch with you within 48 hours if necessary\nYour Message:";
var $usermessage .= $_POST['message'];
//Then set the code to mail that information.
mail($user,$usersubject,$usermessage,$userheaders);
?>