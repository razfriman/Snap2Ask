<?php

if (!defined('inc_file')) {
	die('Direct access is forbidden');
}

// Load these from the session (set during log-in)
$first_name = $_SESSION['first_name'];
$balance = $_SESSION['balance'];

// Debug values
$first_name = 'Raz';
$balance = 10.00;

?>
<header>
	<div id="cash">
		<h3>SnapCash</h3>
		<h2>
		<?php echo money_format('$%i', $balance); ?></h2>
	</div>
	
	<a href="http://www.snap2ask.com"><img src="res/temp_logo.png" alt="Snap-2-Ask Logo" id="logoSmall"/></a>
	
	<div id="rightHeader">		
	
		<div id="welcomeTab">
			<p><?php echo 'Welcome, ' . $first_name; ?></p>	
			<a id="logoutLink" href="logout.php">Log-out</a>
		</div>
					
		<form id="search" method="POST" action="#">
			<input type="text" name="search" placeholder="Search" title="Search a Question" />
		</form>		
	</div>		
</header>
	