<?php

if (!defined('inc_file')) {
	die('Direct access is forbidden');
}

?>
<header>
	<div id="cash">
		<h3>SnapCash</h3>
		<h2>$0.00</h2>
	</div>
	
	<a href="http://www.snap2ask.com"><img src="res/temp_logo.png" alt="Snap-2-Ask Logo" id="logoSmall"/></a>
	
	<div id="rightHeader">		
	
		<div id="welcomeTab">
			<p>Welcome, Raz</p>	
			<a id="logoutLink" href="logout.php">Log-out</a>
		</div>
					
		<form id="search" method="POST" action="#">
			<input type="text" name="search" placeholder="Search" title="Search a Question" />
		</form>		
	</div>		
</header>
	