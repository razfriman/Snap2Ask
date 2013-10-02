<!--CHANGE ACTION TO PROCESS EACH FORM-->
<form action="processcontactus.php" method="POST">
<h1>Your e-mail:</h1>
<input id="email" class="text" name="email" value="" type="text">

<br>
<h1>Your Message:</h1>
<textarea name="message" COLS=40 ROWS=6>
</textarea>
<br>

<input type="submit" name="button" value = "Submit">
    </form>

<?php
/*

<?php
$datalines = file ("write.txt");

foreach ($datalines as $zz) {
echo $zz; }

?>
$mail = file_get_contents("mailtemplates/suggestion_box.txt");

xMail("vkohli678@yahoo.com", $lang['MAILSUBJECT_EMAIL_THIS_AD'], $mail, "vkohli678@hotmail.com");
*/
?>
