<?php

if (!defined('inc_file')) {
	die('Direct access is forbidden');
}

// Create the dynamic base_url for the REST API request
$prefix = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
$domain = $_SERVER['HTTP_HOST'];
$base_url = $prefix . $domain . dirname($_SERVER['PHP_SELF']);

// Use official REST API
$base_url = "http://snap2ask.com/git/snap2ask/Code/web";


function refreshSessionInfo($responseObj) {
		
	$_SESSION['first_name'] = $responseObj['first_name'];
	$_SESSION['balance'] = $responseObj['balance'];
}

function getUserInfo($refresh_session_info) {

	// a function cannot reference variables outside of the function's scope.
	// Declare the $base_url variable global while in the scope of the function/
	global $base_url;
	
	$user_id = $_SESSION['user_id'];
	
	// Load the user information to populate the name and balance for the user
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $base_url . '/api/index.php/users/' . $user_id);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	$response = curl_exec($ch);
	curl_close($ch);

	//decode the response from JSON into PHP
	$responseObj = json_decode($response,true);
	
	if ($refresh_session_info) {
		refreshSessionInfo($responseObj);
	}
	
	return $responseObj;
}

function getQuestionInfo($question_id) {

	// a function cannot reference variables outside of the function's scope.
	// Declare the $base_url variable global while in the scope of the function/
	global $base_url;
	
	$user_id = $_SESSION['user_id'];
	
	// Load the question information 
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $base_url . '/api/index.php/questions/' . $question_id);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	$response = curl_exec($ch);
	curl_close($ch);

	//decode the response from JSON into PHP
	$responseObj = json_decode($response,true);
	
	return $responseObj;
}

?>