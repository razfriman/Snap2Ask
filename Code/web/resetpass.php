<?php
$pass = "";
for ($x = 0; $x < 3; $x++)
    $pass .= chr(rand(65, 90)) . chr(rand(97, 122)) . chr(rand(48, 57));
    //pattern: upper,lower,number,upper,lower,number,upper,lower,number
$toUserEmail = "";
$from = "service@snap2ask.com";
$headers = "From:" . $from;
$message = "Your new Snap2Ask password is: " . $pass;
mail($toUserEmail,'',$message,$headers);
echo "Reset password e-mail Sent to" + $toUserEmail;
?>
