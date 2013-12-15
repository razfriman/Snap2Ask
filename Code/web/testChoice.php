<?php
// Start the named session
session_name('loginSession');
session_start();

// Allow the included files to be executed
define('inc_file', TRUE);

if (!isset($_SESSION['user_id'])) {
    // The user is not logged in
    header('Location: index.php');
    exit;
}

// Require the functions file
require_once('functions.php');

$responseObj = getUserInfo(true);

if (isset($responseObj['error'])) {
    // Invalid user
    header('Location: logout.php');
    exit;
}
$categories = getCategories();

if (isset($_POST['submit'])) {

    if ($_POST['submit'] == 'Take Now' && isset($_POST['category'])) {
        $category_id = $_POST['category'];
        header(sprintf('Location: subjectTest.php?category_id=%s', $category_id));
    } else if ($_POST['submit'] == 'Take Later') {
        header('Location: index.php');
    } else {
        header('Location: index.php');
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Snap-2-Ask | Test-Choice</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="shortcut icon" type="image/x-icon" href="res/favicon.ico">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
    <script src="js/validateSelectTest.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
<?php include_once("ganalytics.php") ?>
<header class="tall">
    <a href="index.php"> <img id="logoTall" src="res/logo.png" alt="Snap-2-Ask Logo"/> </a>
</header>

<div id="content">

    <form id="categoryTestForm" method="post" action="#">
        <p>Our records indicate you are a first time tutor. In order to start answering questions and get paid, you have to pass a test. You can choose to take it now or later.</p>

        <select name="category">
            <option value="Select Category" selected="true" disabled="disabled">Select Category</option>
            <!-- Populate menu -->
            <?php

            foreach($categories as $category) {
                echo sprintf('<option value="%s">%s</option>"', $category['id'], $category['name']);
            }

            ?>
        </select>

        <input  class="decision_point button" name="submit" type="submit" value="Take Now"/>
    </form>

    <form id="takeTestLater" method="post" action="#">
        <input class="decision_point button" name="submit"  type="submit" value="Take Later"/>
    </form>
</div>

<?php include('footer.php') ?>
</body>
</html>
