<!--?php

// Start the named session
session_name('loginSession');
session_start();

// Allow the included files to be executed
define('inc_file', TRUE);

?-->
<!DOCTYPE html>
<html>
  <head>
    <title>Snap-2-Ask | About Us</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="shortcut icon" type="image/x-icon" href="res/favicon.ico">
    <link rel="stylesheet" type="text/css" href="css/style.css">
  </head>
  <body>
    <!--?php include_once("ganalytics.php") ?-->
    <header class="tall"> <a href="index.php"> <img id="logoTall"
          src="res/logo.png" alt="Snap-2-Ask Logo"> </a> </header>
    <div id="content">
      <div id="aboutUsContainer">
        <h1>About Us</h1>
        <div class="aboutUsBio"> <img src="res/raz-bw.png" alt="Raz
            Friman CSE3330/3345" title="Raz Friman CSE3330/3345">
          <h3>Raz Friman</h3>
          <p> Raz is a 2nd year student at SMU. He currently works at
            In-Com Data Systems as a senior programmer. Raz designed and
            published the iOS application to the Apple App Store. </p>
        </div>
        <div class="aboutUsBio"> <img src="res/raymond.jpg"
            alt="Raymond Martin CSE3330/3345" title="Vipul Kohli CSE
            3330/3345">
          <h3>Raymond Martin</h3>
          <p> Raymond is the expert web site designer. He is responsible
            for the beautiful style and logo you see on every Snap2Ask
            web page. </p>
        </div>
        <div class="aboutUsBio"> <img src="http://placehold.it/200x200">
          <h3>Roman Stolyarov</h3>
          <p> Roman made Snap2Ask more reliable by introducing the tutor
            validation tests. Tutors who pass these tests are paid more
            per question. </p>
        </div>
        <div class="aboutUsBio"> <img src="http://placehold.it/200x200">
          <h3>Elena Villamil</h3>
          <p> Elena is the expert database manager for the web site.
            With her work, tutors can change their passwords as well as
            view the questions they have answered. </p>
        </div>
        <div class="aboutUsBio"> <img src="res/vipul.png" alt="Vipul
            Kohli CSE3345" title="Vipul Kohli CSE 3345">
          <h3>Vipul Kohli</h3>
          <p> Vipul is also a second year programmer at SMU. He is a
            programmer specialized in JavaScript. He implemented the
            sorting algorithms used on the tutor's My Answers. Vipul is
            also the Customer Care agent for the site. Every week he
            performs a usability test with a future Snap2Ask user to
            ensure users are happy with our website. </p>
        </div>
      </div>
    </div>
    <!--?php include('footer.php') ?-->
  </body>
</html>
