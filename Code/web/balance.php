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


if (isset($_POST['submit']) && $_POST['submit'] == 'Withdraw SnapCash') {

	// WITHDRAW FUNDS
	$current = $responseObj['balance'];
	$withdraw = $_POST['withdraw_amount'];
	
	if ($withdraw < 0) {
		$withdraw = 0;
	}
	
	if ($withdraw > $current) {
		$withdraw = $current;
	}
	
	// UPDATE THE USER INFO VIA REST API 

	$request = array(
		'last_name' => $responseObj['last_name'],
		'first_name' => $responseObj['first_name'],
		'balance' => $responseObj['balance'] - $withdraw,
		'is_tutor' => $responseObj['is_tutor'],
		'rating' => $responseObj['rating']
		);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $base_url . '/api/index.php/users/' . $responseObj['id']);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));
	$updateResponse = curl_exec($ch);
	curl_close($ch);

	
	//sent to the be decoded
	$updateResponseObj = json_decode($updateResponse,true);

	//depending on the response we either ask for different credentials or log the user in
	if($updateResponseObj['success'])
	{
		header('Location: balance.php');
	} else {
		die($updateResponseObj['reason']);
	}
	
}



// FOR TESTING ONLY (THIS WOULD NEVER REALLY HAPPEN)
// TUTORS ONLY WITHDRAW THEIR EARNED SNAPCASH
if (isset($_POST['submit']) && $_POST['submit'] == 'Deposit 50 SnapCash') {

	$request = array(
		'last_name' => $responseObj['last_name'],
		'first_name' => $responseObj['first_name'],
		'balance' => $responseObj['balance'] + 50,
		'is_tutor' => $responseObj['is_tutor'],
		'rating' => $responseObj['rating']
		);
	
	

	//cURL used to collect login information
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $base_url . '/api/index.php/users/' . $responseObj['id']);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));
	$updateResponse = curl_exec($ch);
	curl_close($ch);

	
	//sent to the be decoded
	$updateResponseObj = json_decode($updateResponse,true);

	//depending on the response we either ask for different credentials or log the user in
	if($updateResponseObj['success'])
	{
		header('Location: balance.php');
	} else {
		die($updateResponseObj['reason']);
	}
	
}

?>

<!DOCTYPE html>
<html>

<head>
	<title>Snap-2-Ask | Balance</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="shortcut icon" type="image/x-icon" href="res/favicon.ico">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="js/validateBalance.js" type="text/javascript"></script>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>

	<?php include('header.php') ?>
	
	<div id="content">

		<div id="linksNav">
			<ul>
				<li><a href="browse.php" >Browse</a></li>
				<li class="selected"><a href="balance.php" >Balance</a></li>
				<li><a href="profile.php" >Profile</a></li>
			</ul>
		</div>
		
		
		
		<div id="mainContent">
			
			<h1>ACCOUNT BALANCE</h1>
			<?php

				// Echo the information using sprintf
				// Escape special html characters to enhance XSS security
			echo sprintf("<h3>Available SnapCash: %s</h3>", htmlspecialchars($responseObj['balance']));

			?>

			
			<form id="withdrawSnapCashForm" action="#" method="post">
				<input type="text" name="withdraw_amount" placeholder="Withdraw Amount" autocomplete="off" title="Please enter a valid amount to withdraw">
				<input type="submit" name="submit" value="Withdraw SnapCash">
			</form>
			
			<!-- TODO:
			REMOVE THIS IN THE FUTURE
			THIS IS ONLY FOR TESTING PURPOSES -->
			<form action="#" method="post">
				<input type="submit" name="submit" value="Deposit 50 SnapCash">
			</form>
			
		</div>
	</div>
	


	<?php include('footer.php') ?>

</body>
</html>
