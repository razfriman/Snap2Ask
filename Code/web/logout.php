<?php

session_start();

if(isset($_SESSION['user_id'])) {
	
	// Logout
	
	// Clear the session
	$_SESSION = array();
	session_destroy();
	
	// Go to login page
	header('Location: index.php');
	exit;
} else {

	// Go to login page
	header('Location: index.php');
	exit;
}

?>