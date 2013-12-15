<?php

// Allow the included files to be executed
define('inc_file', TRUE);

// Require the functions file
require_once('functions.php');

?>

<!DOCTYPE html>
<html>

<head>
    <title>Snap-2-Ask | Student</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <link rel="shortcut icon" type="image/x-icon" href="res/favicon.ico">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <meta name="apple-itunes-app" content="app-id=757257906">

</head>

<body>
<?php include_once("ganalytics.php") ?>
<header class="tall">
    <a href="index.php"><img id="logoTall" src="res/logo.png" alt="Snap-2-Ask Logo"/></a>
</header>

<div id="content">

    <h1>Student Information Page</h1>
    <div class="divider"></div>

    <div id="infoContainer">

        <img src="res/icons/SAT.png" />
        <h2>Snap-2-Ask helps answer your academic questions</h2>

        <img src="res/icons/Academic.png" />
        <h2>All Questions on Snap-2-Ask are picture based and answered by certified tutors.</h2>
    </div>
    <div id="moreInfoContainer">
        <p>
            Start asking questions by downloading the mobile application
        </p>
        <a href="https://itunes.apple.com/us/app/snap2ask/id757257906"><img src="res/applogo.jpeg" /></a>
    </div>
</div>

<?php include('footer.php') ?>
</body>
</html>
