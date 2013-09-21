<?php

require_once __DIR__ . '/config.php';
require 'Slim/Slim.php';

try {

	// Open MySQL PDO Connection
    $db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
    $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    print 'MySQL PDO Connection Error: ' . $e->getMessage();
    die();
}

// Create the Slim app
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->add(new \Slim\Middleware\ContentTypes());

/*
 * Step 3: Define the Slim application routes
 */

// VALIDATE A LOGIN
$app->post(
    '/login',
    function () use ($app,$db) {

    $request = $app->request()->getBody();
        
    $username = $request['username'];
    $password = $request['password'];

    $success = false;
    $reason = '';

    // VALIDATE THE PASSWORD
    try {
        $sth = $db->prepare('SELECT * FROM users WHERE username = :username');
        $sth->bindParam(':username', $username);
        $sth->execute();
        $row = $sth->fetch(PDO::FETCH_ASSOC);
        
        // 5 rounds of blowfish
        $Blowfish_Pre = '$2a$05$';
        $Blowfish_End = '$';

        $hashed_pass = crypt($password, $Blowfish_Pre . $row['salt'] . $Blowfish_End);

        $success = $hashed_pass == $row['password'];
        if (!$success)
        {
            $reason = 'Incorrect username/password';
        }
    } catch(PDOException $e) {
         $success = false;
         $reason = 'Incorrect username/password';
    }

    $dataArray = array('success' => $success, 'reason' => $reason);
        

    $response = $app->response();
    $response['Content-Type'] = 'application/json';
    $response->status(200);
    $response->write(json_encode($dataArray));
    }
);

// GET LIST OF ALL USERS
$app->get(
    '/users',
    function () use ($app,$db) {

    $userData = array();

    try {
        $sth = $db->prepare('SELECT * FROM users');
        $sth->execute();
        $userData = $sth->fetchAll(PDO::FETCH_ASSOC);

        // Remove password and salt from returned data
        foreach ($userData as &$user) {
            unset($user['password']);
            unset($user['salt']);
        }

    } catch(PDOException $e) {
         // SQL ERROR
    }
    
 	$response = $app->response();
	$response['Content-Type'] = 'application/json';
	$response->status(200);
	$response->write(json_encode($userData));
    }
);

// GET USER PROFILE INFORMATION
$app->get(
	'/users/:id',
	function ($id) use ($app,$db) {

    $userData = array();

    try {
        $sth = $db->prepare('SELECT * FROM users WHERE id=:user_id');
        $sth->bindParam(':user_id',$id);
        $sth->execute();
        $userData = $sth->fetch(PDO::FETCH_ASSOC);

        unset($userData['password']);
        unset($userData['salt']);

    } catch(PDOException $e) {
         // SQL ERROR
    }
    
    $response = $app->response();
    $response['Content-Type'] = 'application/json';
    $response->status(200);
    $response->write(json_encode($userData));
	}
);

// CREATE A USER ACCOUNT
$app->post(
    '/users',
    function () use ($app,$db) {
    	
        $request = $app->request()->getBody();
    	
        $username = $request['username'];
        $email = $request['email'];
        $password = $request['password'];


        // HASH PASSWORD
        // 5 rounds of blowfish
        $Blowfish_Pre = '$2a$05$';
        $Blowfish_End = '$';

        // allowed blowfish characters
        $Allowed_Chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789./';
        $Chars_Len = 63;

        $Salt_Length = 21;

        $mysql_date = date( 'Y-m-d' );
        $salt = "";

        for($i=0; $i < $Salt_Length; $i++)
        {
            $salt .= $Allowed_Chars[mt_rand(0,$Chars_Len)];
        }

        $bcrypt_salt = $Blowfish_Pre . $salt . $Blowfish_End;

        $hashed_password = crypt($password, $bcrypt_salt);


        $success = false;
        $reason = '';


        $balance = 40;
        $is_tutor = false;
        $is_admin = false;
        $authentication_mode_id = 1; // custom
        
        try {
            $sth = $db->prepare('INSERT INTO users (username,email,password,salt,balance,is_tutor,is_admin,authentication_mode_id) 
                                 VALUES (:username,:email,:password,:salt,:balance,:is_tutor,:is_admin,:authentication_mode_id)');
            $sth->bindParam(':username', $username);
            $sth->bindParam(':email', $email);
            $sth->bindParam(':password', $hashed_password);
            $sth->bindParam(':salt', $salt);
            $sth->bindParam(':balance', $balance);
            $sth->bindParam(':is_tutor', $is_tutor);
            $sth->bindParam(':is_admin', $is_admin);
            $sth->bindParam(':authentication_mode_id', $authentication_mode_id);
            $sth->execute();
            
            $success = true;

        } catch(PDOException $e) {
            $success = false;
            $reason = $e->getMessage();
        }

    	$dataArray = array('success' => $success, 'reason' => $reason);
		
        $response = $app->response();
		$response['Content-Type'] = 'application/json';
		$response->status(200);
        $response->write(json_encode($dataArray));
    }
);


// GET LIST OF ALL CATEGORIES
$app->get(
    '/categories',
    function () use ($app,$db) {

    $categoryData = array();

    try {
        $sth = $db->prepare('SELECT * FROM categories');
        $sth->execute();
        $categoryData = $sth->fetchAll(PDO::FETCH_ASSOC);
        
    } catch(PDOException $e) {
         // SQL ERROR
    }
    
    $response = $app->response();
    $response['Content-Type'] = 'application/json';
    $response->status(200);
    $response->write(json_encode($categoryData));
    }
);

// GET LIST OF ALL QUESTIONS
$app->get(
    '/questions',
    function () use ($app,$db) {

    $questionData = array();

    try {
        $sth = $db->prepare('SELECT * FROM questions');
        $sth->execute();
        $questionData = $sth->fetchAll(PDO::FETCH_ASSOC);

        // Remove password and salt from returned data
        foreach ($questionData as &$question) {
            //unset($user['password']);
            //unset($user['salt']);
        }

    } catch(PDOException $e) {
         // SQL ERROR
    }
    
    $response = $app->response();
    $response['Content-Type'] = 'application/json';
    $response->status(200);
    $response->write(json_encode($questionData));
    }
);

// GET DETAILED QUESTION INFORMATION
$app->get(
    '/questions/:id',
    function ($id) use ($app,$db) {

    $questionData = array();

    try {
        $sth = $db->prepare('SELECT * FROM questions WHERE id=:question_id');
        $sth->bindParam(':question_id',$id);
        $sth->execute();
        $questionData = $sth->fetch(PDO::FETCH_ASSOC);

    } catch(PDOException $e) {
         // SQL ERROR
    }
    
    $response = $app->response();
    $response['Content-Type'] = 'application/json';
    $response->status(200);
    $response->write(json_encode($questionData));
    }
);

// POST A QUESTION
$app->post(
    '/questions',
    function () use ($app,$db) {
        
        $request = $app->request()->getBody();
        
        $student_id = $request['student_id'];
        $category_id = $request['category_id'];
        $description = $request['description'];
        //$image_data = $request['image_data'];
        $image_id = $request['image_id'];

        $success = false;
        $reason = '';

        try {
            $sth = $db->prepare('INSERT INTO questions (student_id,category_id,description,image_id) 
                                 VALUES (:student_id,:category_id,:description,:image_id)');
            $sth->bindParam(':student_id', $student_id);
            $sth->bindParam(':category_id', $category_id);
            $sth->bindParam(':description', $description);
            $sth->bindParam(':image_id', $image_id);
            $sth->execute();
            
            $success = true;

        } catch(PDOException $e) {
            $success = false;
            $reason = $e->getMessage();
        }

        $dataArray = array('success' => $success, 'reason' => $reason);
        
        $response = $app->response();
        $response['Content-Type'] = 'application/json';
        $response->status(200);
        $response->write(json_encode($dataArray));
    }
);
















$app->put(
    '/put',
    function () use ($app,$db) {
        echo 'This is a PUT route';
    }
);

$app->delete(
    '/delete',
    function () use ($app,$db) {
        echo 'This is a DELETE route';
    }
);


// Run the Slim app
$app->run();
