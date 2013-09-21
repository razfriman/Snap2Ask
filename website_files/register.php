<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

if (!empty($_POST))
{

	$request = array('email' => $_POST['email'], 'username' => $_POST['username'], 'password' => $_POST['password']);
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'http://snap2ask/api/index.php/users');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));
	$response = curl_exec($ch);
	curl_close($ch);


	$responseObj = json_decode($response,true);

	if ($responseObj['success']) {
		echo 'Successfully created account: ' . $request['username'];
	} else {
		echo 'Error creating account: ' . $responseObj['reason'];
	}

}


?>

<!DOCTYPE html>
<html>
<head>
	<title>Snap2Ask | Register</title>
</head>
<body>
	<form action="" method="POST">
		<input type="text" name="username" id="username" placeholder="Username" required />	
		<input type="text" name="email" id="email" placeholder="Email Address" required />
		<input type="password" name="password" id="password" placeholder="Password" required />
		<input type="submit" value="Register" />
	</form>
</body>
</html>