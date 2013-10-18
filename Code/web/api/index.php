<?php

// Include the Amazon S3 SDK and Slim REST API Framework
require 'vendor/autoload.php';
use Aws\S3\S3Client;

// Include the config file with Database credentials
require_once __DIR__ . '/config.php';

// Open the MySQL Connection using PDO
// PDO is a robust method to access Databases and provides built in security to protect from MySQL injections 
try {

	// Connect to the database using the Constants defined in config.php
	$db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);

	// This displays any SQL Query syntax errors
	$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	print 'MySQL PDO Connection Error: ' . $e->getMessage();
	die();
}


// This is part of the required code to use the Slim Framework
// Create the Slim app
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->add(new \Slim\Middleware\ContentTypes());

// Allow cross domain calls via javascript
$app->response()->header('Access-Control-Allow-Origin','*');


// This function takes a password as an input
// It returns an array with the hashed password and the salt used to create the hash
function hashPassword($password) {
	
	// This is the syntax to use PHP's built-in hashing methods
	// it uses 5 rounds of the blowfish algorithm to create the hash 
	$Blowfish_Pre = '$2a$05$';
	$Blowfish_End = '$';

    // these are the allowed blowfish salt characters
	$Allowed_Chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789./';
	$Chars_Len = 63;

	// The length of the salt
	$Salt_Length = 21;

	// The salt string
	$salt = "";

	// Generate the salt string by randomly selcting letters from the allowed salt characters list
	for($i=0; $i < $Salt_Length; $i++)
	{
		$salt .= $Allowed_Chars[mt_rand(0,$Chars_Len)];
	}

	// Add the hash information to the salt
	// This is required to use the crypt() method that PHP provides
	$bcrypt_salt = $Blowfish_Pre . $salt . $Blowfish_End;

	// Creates the hashed password using PHP's crypt() method
	$hashed_password = crypt($password, $bcrypt_salt);

	// Return an array of the hash and the salt
	return array($hashed_password,$salt);
}

// Verifies a password using the salt to recreate the hash
function hashPasswordWithSalt($password, $salt) {
	
	// 5 rounds of blowfish
	$Blowfish_Pre = '$2a$05$';
	$Blowfish_End = '$';

	// Create the hash using the password and the salt
	// Later we can check if this matches the Database's hash, thus verifying the validity of the password
	$hashed_pass = crypt($password, $Blowfish_Pre . $salt . $Blowfish_End);

	return $hashed_pass;

}

function verifyGoogleToken($user_id,$token) {

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://www.googleapis.com/oauth2/v1/tokeninfo?access_token=' . $token);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	$response = curl_exec($ch);
	curl_close($ch);

	$responseObj = json_decode($response,true);

	if (isset($responseObj['user_id']) && $responseObj['user_id'] == $user_id) {
		return true;
	} else {
		return false;
	}

}

function verifyFacebookToken($user_id,$token) {

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/me?fields=id&access_token=' . $token);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	$response = curl_exec($ch);
	curl_close($ch);

	$responseObj = json_decode($response,true);

	if (isset($responseObj['id']) && $responseObj['id'] == $user_id) {
		return true;
	} else {
		return false;
	}

}

// Adds the category data to each question object
// Gets the value of the category and subcategory name using the foreign key to look-up the name value of the category
function addCategories(&$question,$db) {
	try {

		// Get the foreign keys for the category
		$category_id = $question['category_id'];
		$subcategory_id = $question['subcategory_id'];

		// Get the name of the category by looking up the foreign key value
		$sth = $db->prepare("SELECT name FROM categories WHERE id=:category_id");
		$sth->bindParam(':category_id',$category_id);
		$sth->execute();
		$category = $sth->fetch(PDO::FETCH_ASSOC);

		// Add the category name to the question's category property
		$question['category'] = $category['name'];

		// Get the name of the subcategory by looking up the foreign key value
		$sth = $db->prepare("SELECT name FROM subcategories WHERE id=:subcategory_id");
		$sth->bindParam(':subcategory_id',$subcategory_id);
		$sth->execute();
		$subcategory = $sth->fetch(PDO::FETCH_ASSOC);

	// Add the category name to the question's subcategory property
		$question['subcategory'] = $subcategory['name'];

	} catch(PDOException $e) {
     // SQL ERROR
	}
}

// Adds the answer data to a question object
function addAnswer(&$question,$db) {
	try {

		// Get the question id from the question object
		$id = $question['id'];

		// Select all the answer data for the matching question id
		$sth = $db->prepare("SELECT * FROM answers WHERE question_id=:question_id");
		$sth->bindParam(':question_id',$id);
		$sth->execute();
		$answerData = $sth->fetchAll(PDO::FETCH_ASSOC);

		// Append the array data of all answers of the specific question to the question object
		$question['answers'] = $answerData;

	} catch(PDOException $e) {
     // SQL ERROR
	}
}

/*
 * Now we provide methods for the Slim API to use to creat the REST API
 * these methods will be executed when a user visits the corresponding route
 */

// VALIDATE A LOGIN
$app->post(
	'/login',
	function () use ($app,$db) {

		// Get the JSON Request
		$request = $app->request()->getBody();

		// Load the JSON Request values
		$authentication_mode = $request['authentication_mode'];
		$password = $request['password'];
		$account_identifier = $request['account_identifier'];

		// Initialize the return values
		$success = false;
		$reason = '';
		$user_id = -1;

		// VALIDATE THE PASSWORD
		try {

			// Get the user data
			if ($authentication_mode == 'facebook' || $authentication_mode == 'google') 
			{
				// 3RD PARTY AUTHENTICATION
				$sth = $db->prepare('SELECT * FROM users WHERE oauth_id=:oauth_id AND authentication_mode=:authentication_mode');
				$sth->bindParam(':oauth_id', $account_identifier);
				$sth->bindParam(':authentication_mode', $authentication_mode);
				
			} else if ($authentication_mode == 'custom') {
				// CUSTOM AUTHENTICATION
				$sth = $db->prepare('SELECT * FROM users WHERE email=:email AND authentication_mode=:authentication_mode');
				$sth->bindParam(':email', $account_identifier);
				$sth->bindParam(':authentication_mode', $authentication_mode);
			}
			
			$sth->execute();

			if ($sth->rowCount() == 0 && ($authentication_mode == 'facebook' || $authentication_mode == 'google')) {
				$reason = 'No user data found using 3rd party authentication.';
			} else if ($sth->rowCount() == 0) {
				$reason = 'Incorrect email/password';
			} else {

				$row = $sth->fetch(PDO::FETCH_ASSOC);

				if ($authentication_mode == 'custom') {
					// Custom
					$hashed_pass = hashPasswordWithSalt($password, $row['salt']);
					$success = $hashed_pass == $row['password'];
				} else if ($authentication_mode == 'facebook') {
					// Facebook
					$success = verifyFacebookToken($account_identifier,$password);

				} else if ($authentication_mode == 'google') {
					// Google
					$success = verifyGoogleToken($account_identifier,$password);
				}

				if ($success)
				{
					$user_id = $row['id'];
				}
				else
				{
					$reason = 'Incorrect email/password';
				}
			}
		} catch(PDOException $e) {
			$success = false;
			$reason = 'Incorrect email/password';
		}

		$dataArray = array(
			'success' => $success,
			'reason' => $reason,
			'authentication_mode' => $authentication_mode,
			'user_id' => $user_id,
			'register_or_login' => false,
			);


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

		// Create a return array for the user data
		$userData = array();

		try {

			// Get the information for all users from the database
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

		// Return the JSON Data
		$response = $app->response();
		$response['Content-Type'] = 'application/json';
		$response->status(200);
		$response->write(json_encode($userData));
	}
	);

// GET SPECIFIC USER PROFILE INFORMATION
$app->get(
	'/users/:id',
	function ($id) use ($app,$db) {

		$userData = array();

		try {

			// Load a specific user's data
			$sth = $db->prepare('SELECT * FROM users WHERE id=:user_id');
			$sth->bindParam(':user_id',$id);
			$sth->execute();

			// Only fetch the data if the user exists
			if ($sth->rowCount() > 0) {
				$userData = $sth->fetch(PDO::FETCH_ASSOC);

	            // Remove the password/salt fields
				unset($userData['password']);
				unset($userData['salt']);
			} else {
				$userData['error'] = "User does not exists";	
			}
		} catch(PDOException $e) {
         // SQL ERROR
		}

		// Return the JSON Data
		$response = $app->response();
		$response['Content-Type'] = 'application/json';
		$response->status(200);
		$response->write(json_encode($userData));
	}
	);
	
// DELETE A USER ACCOUNT
$app->delete(
	'/users/:id',
	function ($id) use ($app,$db) {

		// Initialize the response 
		$success = false;
		$reason = '';
		
		try {
			$sth = $db->prepare('DELETE FROM users WHERE id=:user_id');
			$sth->bindParam(':user_id', $id);
			$sth->execute();

			$success = true;

		} catch(PDOException $e) {
			$success = false;
			$reason = 'Error deleting user';
		}
		
	    // Create the response data
		$dataArray = array(
			'success' => $success,
			'reason' => $reason,
			);
		
        // Return the JSON data
		$response = $app->response();
		$response['Content-Type'] = 'application/json';
		$response->status(200);
		$response->write(json_encode($dataArray));
	}
	);

// GET USER'S QUESTIONS
$app->get(
	'/users/:id/questions',
	function ($id) use ($app,$db) {

		$questionData = array();

		try {

			// Get questions for a specific user
			$sth = $db->prepare('SELECT * FROM questions WHERE student_id=:user_id ORDER BY date_created');
			$sth->bindParam(':user_id',$id);
			$sth->execute();

			// Fetch all the matching results
			$questionData = $sth->fetchAll(PDO::FETCH_ASSOC);

			// For each question, add the category and answer data
			foreach ($questionData as &$question) {
				addCategories($question,$db);
				addAnswer($question,$db);
			}

		} catch(PDOException $e) {
         // SQL ERROR
		}

		// Return the JSON Response
		$response = $app->response();
		$response['Content-Type'] = 'application/json';
		$response->status(200);
		$response->write(json_encode($questionData));
	}
	);

// CREATE A USER ACCOUNT
$app->post(
	'/users',
	function () use ($app,$db) {

		// Get the JSON Request
		$request = $app->request()->getBody();

		// Read all the Request properties used to create the account
		$email = $request['email'];
		$oauth_id = null;
		$password = $request['password'];
		$first_name = $request['first_name'];
		$last_name = $request['last_name'];
		$salt = '';
		$balance = 40;
		$is_tutor = $request['is_tutor'];
		$is_admin = false;
		$preferred_category_id = 0;
		$authentication_mode = $request['authentication_mode'];
		$date_created = date("Y-m-d H:i:s");
		$register_or_login = $request['register_or_login'];

		if (isset($request['oauth_id']))
		{
			$oauth_id = $request['oauth_id'];
		}
		
        // Ony add the preferred category id if the user is a tutor
		if ($is_tutor) { 
			$preferred_category_id = $request['preferred_category_id'];
		}

        // Create the password hash
		$hashResult = hashPassword($password);
		$hashed_password = $hashResult[0];
		$salt = $hashResult[1];

        // Initialize the return values
		$success = false;
		$reason = '';
		$user_id = 0;
		
		$need_to_register = true;

		try {
			
			if ($authentication_mode == 'facebook' || $authentication_mode == 'google') {
				
				$sth = $db->prepare('SELECT * FROM users WHERE email=:email AND authentication_mode=:authentication_mode');
				$sth->bindParam(':email', $email);
				$sth->bindParam(':authentication_mode', $authentication_mode);
				$sth->execute();
				$user_data = $sth->fetch(PDO::FETCH_ASSOC);
				
				if($sth->rowCount() > 0) {
					
					$need_to_register = false;
					
					if ($authentication_mode == 'facebook') {
						$success = verifyFacebookToken($oauth_id, $password);
					} else if ($authentication_mode == 'google') {
						$success = verifyGoogleToken($oauth_id, $password);
					}
					
					if ($success) {
						$user_id = $user_data['id'];
					}
				}
			}

			if ($need_to_register) {
				
				// Try to insert the user into the database
				// This uses named parameters, which prevent any SQL Injections
				$sth = $db->prepare('INSERT INTO users (email,oauth_id,first_name,last_name,password,salt,balance,is_tutor,is_admin,preferred_category_id,authentication_mode,date_created) 
					VALUES (:email,:oauth_id,:first_name,:last_name,:password,:salt,:balance,:is_tutor,:is_admin,:preferred_category_id,:authentication_mode,:date_created)');
				$sth->bindParam(':email', $email);
				$sth->bindParam(':oauth_id', $oauth_id);
				$sth->bindParam(':first_name', $first_name);
				$sth->bindParam(':last_name', $last_name);
				$sth->bindParam(':password', $hashed_password);
				$sth->bindParam(':salt', $salt);
				$sth->bindParam(':balance', $balance);
				$sth->bindParam(':is_tutor', $is_tutor);
				$sth->bindParam(':is_admin', $is_admin);
				$sth->bindParam(':preferred_category_id', $preferred_category_id);
				$sth->bindParam(':authentication_mode', $authentication_mode);
				$sth->bindParam(':date_created', $date_created);
				$sth->execute();

				$success = true;

				// Get the id of the user we just created
				$user_id = $db->lastInsertId();
			}

		} catch(PDOException $e) {
        	// An error occured
			$success = false;
			$reason = $e->getMessage();
		}

        // Create the return data
		$dataArray = array(
			'success' => $success,
			'reason' => $reason,
			'user_id' => $user_id,
			'register_or_login' => $register_or_login,
			'authentication_mode' => $authentication_mode
			);
		
        // Return the JSON data
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

		// First get a list of all the categories
		try {
			$sth = $db->prepare('SELECT * FROM categories');
			$sth->execute();
			$categoryData = $sth->fetchAll(PDO::FETCH_ASSOC);

		} catch(PDOException $e) {
         // SQL ERROR
		}

        // Then, Load subcategory data for each category object
		foreach ($categoryData as &$category) {


			$category_id = $category['id'];

			try {
				$sth = $db->prepare('SELECT id,name FROM subcategories WHERE category_id=:category_id');
				$sth->bindParam(':category_id',$category_id);
				$sth->execute();

				$subcategoryData = $sth->fetchAll(PDO::FETCH_ASSOC);

				// Add the subcategory data to its category
				$category['subcategories'] = $subcategoryData;

			} catch(PDOException $e) {
             // SQL ERROR
			}            
		}

		// Return the JSON Data
		$response = $app->response();
		$response['Content-Type'] = 'application/json';
		$response->status(200);
		$response->write(json_encode($categoryData));
	}
	);
	
// GET LIST OF QUESTIONS BY CATEGORY
$app->get(
	'/categories/:id/questions',
	function ($id) use ($app,$db) {

		$questionData = array();

		// First get a list of all the categories
		try {
			$sth = $db->prepare('SELECT * FROM questions WHERE category_id=:category_id ORDER BY date_created');
			$sth->bindParam(':category_id', $id);
			$sth->execute();
			$questionData = $sth->fetchAll(PDO::FETCH_ASSOC);
			
			// For each question, add the category and answer data
			foreach ($questionData as &$question) {
				addCategories($question,$db);
				addAnswer($question,$db);
			}

		} catch(PDOException $e) {
         // SQL ERROR
		}

		// Return the JSON Data
		$response = $app->response();
		$response['Content-Type'] = 'application/json';
		$response->status(200);
		$response->write(json_encode($questionData));
	}
	);
	
// GET LIST OF ALL QUESTIONS
$app->get(
	'/questions',
	function () use ($app,$db) {

		$questionData = array();

		try {
			// Select all the questions from MySQL
			$sth = $db->prepare('SELECT * FROM questions');
			$sth->execute();
			$questionData = $sth->fetchAll(PDO::FETCH_ASSOC);

        	// Add Answer and Category data to each question
			foreach ($questionData as &$question) {
				addCategories($question,$db);
				addAnswer($question,$db);
			}

		} catch(PDOException $e) {
         // SQL ERROR
		}

		// Return the JSON data
		$response = $app->response();
		$response['Content-Type'] = 'application/json';
		$response->status(200);
		$response->write(json_encode($questionData));
	}
	);

// GET SPECIFIC QUESTION INFORMATION
$app->get(
	'/questions/:id',
	function ($id) use ($app,$db) {

		$questionData = array();

		try {
			// Get the question data for a particular question using its id
			$sth = $db->prepare('SELECT * FROM questions WHERE id=:question_id');
			$sth->bindParam(':question_id',$id);
			$sth->execute();

			// If the question exists, then load the data
			if ($sth->rowCount() > 0) {
				$questionData = $sth->fetch(PDO::FETCH_ASSOC);

				// Add the category and answer data
				// Use the & here to pass the object by reference, and not by value
				//  This way changes made to the paraters in the method, retain with the object
				addCategories($question,$db);
				addAnswer($questionData,$db);
			} else {
				$questionData['error'] = "Question does not exists";
			}
		} catch(PDOException $e) {
         // SQL ERROR
		}

		// Return the JSON data
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

		// Load the JSON Request data
		$request = $app->request()->getBody();

		// Load each request property
		$student_id = $request['student_id'];
		$category_id = $request['category_id'];
		$subcategory_id = $request['subcategory_id'];
		$description = $request['description'];
		
        $status = 0; // Unanswered
        $times_answered = 0;
        $date_created = date("Y-m-d H:i:s");
        $image_url = '';
        $image_thumbnail_url = '';

		// Initialize the response 
        $success = false;
        $reason = '';
        $insert_id = 0;
        
        $ask_question_cost = ASK_QUESTION_COST;
        
        $can_ask_question = false;
        
        // Get the current balance
        $sth = $db->prepare('SELECT balance FROM users WHERE id=:user_id)');
        $sth->bindParam(':user_id', $student_id);
        $sth->execute();
                
        if ($sth->rowCount() > 0) {
	        $balance_data = $sth->fetch();
	        $current_balance = $balance[0];
	        
	        if ($current_balance > $ASK_QUESTION_COST)
	        {
	        	// The user has enough funds to ask a question
		        $can_ask_question = true;
	        } else {
		        $reason = 'Not enough SnapCash';
	        }
        } else {
	        $reason = 'User does not exist';
        }
        
        if ($can_ask_question) {
	        
	        // Create the prepared statement to insert the question to the database
	        try {
	        	$sth = $db->prepare('INSERT INTO questions (student_id,category_id,subcategory_id,description,image_url,image_thumbnail_url,status,times_answered,date_created) 
	        		VALUES (:student_id,:category_id,:subcategory_id,:description,:image_url,:image_thumbnail_url,:status,:times_answered,:date_created)');
	        	$sth->bindParam(':student_id', $student_id);
	        	$sth->bindParam(':category_id', $category_id);
	        	$sth->bindParam(':subcategory_id', $subcategory_id);
	        	$sth->bindParam(':description', $description);
	        	$sth->bindParam(':image_url', $image_url);
	        	$sth->bindParam(':image_thumbnail_url', $image_thumbnail_url);
	        	$sth->bindParam(':status', $status);
	        	$sth->bindParam(':times_answered', $times_answered);
	        	$sth->bindParam(':date_created', $date_created);
	
	        	$sth->execute();
	
	        	$success = true;
	
	        	// Return the new question's id
	        	$insert_id = $db->lastInsertId();
	
	
	        } catch(PDOException $e) {
	        	$success = false;
	        	$reason = $e->getMessage();
	        }
	    }
	    
	    if ($success) {
	    	
	    	// Deduct the balance from the user
		    $sth = $db->prepare('UPDATE users SET balance-=:question_cost WHERE id=:user_id)');
	        $sth->bindParam(':user_id', $student_id);
	        $sth->bindParam(':question_cost', $ask_question_cost);
	        $sth->execute();
	    }
        
	    // Create the response data
        $dataArray = array(
        	'success' => $success,
        	'reason' => $reason,
        	'insert_id' => $insert_id);
        
        // Return the JSON data
        $response = $app->response();
        $response['Content-Type'] = 'application/json';
        $response->status(200);
        $response->write(json_encode($dataArray));
    }
    );

// POST A QUESTION IMAGE
$app->post(
	'/questions/:id',
	function ($id) use ($app,$db) {

		// Initialize the response 
		$success = false;
		$reason = '';
		$image_url = '';
		$image_thumbnail_url = '';
		
		
		if(isset($_FILES['file'])) {
        	// Upload image to Amazon S3

			// Instantiate the S3 client with your AWS credentials
			$aws_client = S3Client::factory(array(
				'key'    => AWS_ACCESS_KEY_ID,
				'secret' => AWS_SECRET_ACCESS_KEY,
				));
			
	        // Amazon AWS S3 Bucket name
			$bucket = AWS_S3_BUCKET;

			// S3 Upload file name
			$upload_file_name = $id . '.jpeg';

			
			// Temporary image path provided by PHP when you upload an image
			$file_path =  $_FILES["file"]["tmp_name"];
			
			$result = $aws_client->putObject(array(
				'Bucket'     => $bucket,
				'Key'        => $upload_file_name,
				'SourceFile' => $file_path,
				'ACL'        => 'public-read'
				));

		    // URL of the uploaded image
			$image_url = $result['ObjectURL'];
			$image_thumbnail_url = $image_url;
			
			$reason = $image_url;
			
		    // Create the prepared statement to insert the question to the database
			try {
				$sth = $db->prepare('UPDATE questions SET image_url=:image_url, image_thumbnail_url=:image_thumbnail_url WHERE id=:question_id');
				$sth->bindParam(':question_id', $id);
				$sth->bindParam(':image_url', $image_url);
				$sth->bindParam(':image_thumbnail_url', $image_thumbnail_url);
				$sth->execute();

				$success = true;

			} catch(PDOException $e) {
				$success = false;
				$reason = 'Error updating database image_url';
			}
			
		} else {
			$success = false;
			$reason = 'Could not locate uploaded image data';
		}
		
	    // Create the response data
		$dataArray = array(
			'success' => $success,
			'reason' => $reason,
			'question_id' => $id,
			'image_url' => $image_url,
			'image_thumbnail_url' => $image_thumbnail_url
			);
		
        // Return the JSON data
		$response = $app->response();
		$response['Content-Type'] = 'application/json';
		$response->status(200);
		$response->write(json_encode($dataArray));
	}
	);

// DELETE A QUESTION
$app->delete(
	'/questions/:id',
	function ($id) use ($app,$db) {

		// Initialize the response 
		$success = false;
		$reason = '';
		
		try {
			$sth = $db->prepare('DELETE FROM questions WHERE id=:question_id');
			$sth->bindParam(':question_id', $id);
			$sth->execute();

			$success = true;

		} catch(PDOException $e) {
			$success = false;
			$reason = 'Error deleting question';
		}
		
	    // Create the response data
		$dataArray = array(
			'success' => $success,
			'reason' => $reason,
			);
		
        // Return the JSON data
		$response = $app->response();
		$response['Content-Type'] = 'application/json';
		$response->status(200);
		$response->write(json_encode($dataArray));
	}
	);
	
	
// GET A SPECIFIC QUESTION'S ANSWERS
$app->get(
	'/questions/:id/answers',
	function ($id) use ($app,$db) {

		$questionData = array();

		try {

			// Get the answers for a specific question
			$sth = $db->prepare('SELECT * FROM answers WHERE question_id=:question_id');
			$sth->bindParam(':question_id',$id);
			$sth->execute();

			// Only return if the question has any answers
			if ($sth->rowCount() > 0) {
				$questionData = $sth->fetchAll(PDO::FETCH_ASSOC);
			} else {
				$questionData['error'] = "Question/Answer does not exist";
			}
		} catch(PDOException $e) {
         // SQL ERROR
		}

		// Return the JSON data
		$response = $app->response();
		$response['Content-Type'] = 'application/json';
		$response->status(200);
		$response->write(json_encode($questionData));
	}
	);

// POST AN ANSWER TO A QUESTION
$app->post(
	'/questions/:id/answers',
	function ($id) use ($app,$db) {

		// Load the JSON Request
		$request = $app->request()->getBody();

		// Load the request properties
		$tutor_id = $request['tutor_id'];
		$answer_text = $request['answer_text'];

		// Initialize the retunr values
		$success = false;
		$reason = '';
		$insert_id = 0;


		// A debug variable to send push notificatino to the iOS users
		$send_push_notification = false;

		try {

            // GET QUESTION'S TIMES ANSWERED
			$sth = $db->prepare('SELECT times_answered FROM questions WHERE id=:question_id');
			$sth->bindParam(':question_id', $id);
			$sth->execute();
			$row = $sth->fetch();
			$times_answered = $row[0];

			// If the question is still open, then we can post a new answer to it
			if ($times_answered < 3) {

                // INSERT THE ANSWER
				$sth = $db->prepare('INSERT INTO answers (question_id,tutor_id,`text`) 
					VALUES (:question_id,:tutor_id,:answer_text)');

				$sth->bindParam(':question_id', $id);
				$sth->bindParam(':tutor_id', $tutor_id);
				$sth->bindParam(':answer_text', $answer_text);
				$sth->execute();

				// Get the new answer's id
				$insert_id = $db->lastInsertId();

                // INCREASE THE TIMES ANSWERED
				$times_answered++;

				// Set the question status as 'answered'
				$question_status = 1;

				// If the question has been answered 3 times now, then we need to close it
				if ($times_answered == 3) {
					// Set the question status as 'closed'
					$question_status = 2;
				}

                // MARK THE QUESTION AS ANSWERED
				$sth = $db->prepare("UPDATE questions SET status=:question_status,times_answered=:times_answered WHERE id=:question_id");
				$sth->bindParam(':times_answered', $times_answered);
				$sth->bindParam(':question_id', $id);
				$sth->bindParam(':question_status', $question_status);
				$sth->execute();


				// Send a push notification to the iOS user
				if ($send_push_notification) {

                    // GET THE QUESTION'S STUDENT_ID TO NOTIFY THE STUDENT
					$sth = $db->prepare("select users.id FROM users JOIN questions ON (users.id=questions.student_id) JOIN answers ON (answers.question_id = questions.id) WHERE answers.id=:answer_id");
					$sth->bindParam(':answer_id', $insert_id);
					$sth->execute();
					$row = $sth->fetch();

					$student_id = $row[0];

                    // SEND NOTIFICATION TO CLIENT HERE
                    // USE PARSE'S REST API

                    // PARSE APPLICATION INFO
					$APPLICATION_ID = "QbpUMWXgEKThPmDXUuAaqJw3caz1jCYORCeqGmn8";
					$REST_API_KEY = "xsd0U7EIVkWrZ2xOkPsp3A5aVMLq8pEgNYKfFxdk";

					// The push notification info
					$user_channel = "user_" . $student_id;
					$message = 'You received an answer to one of your questions!';
					
					// Create the Push notification request data
					$url = 'https://api.parse.com/1/push';
					$data = array(
						'channel' => $user_channel,
						'type' => 'ios',
						'data' => array(
							'alert' => $message
							),
						);

					// Encode the data as JSON
					$json_push_data = json_encode($data);

					// Set the Push notification request HTTP Headers
					$headers = array(
						'X-Parse-Application-Id: ' . $APPLICATION_ID,
						'X-Parse-REST-API-Key: ' . $REST_API_KEY,
						'Content-Type: application/json',
						'Content-Length: ' . strlen($json_push_data),
						);

					// Create the push notification request
					$curl = curl_init($url);
					curl_setopt($curl, CURLOPT_POST, 1);
					curl_setopt($curl, CURLOPT_POSTFIELDS, $json_push_data);
					curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
					
					// Send the push notification request
					curl_exec($curl);

				}

				$success = true;

			} else {
				$success = false;
				$reason = "Question has too many answers. It has been closed";
			}

		} catch(PDOException $e) {
			$success = false;
			$reason = $e->getMessage();
		}

        // Create the response data
		$dataArray = array(
			'success' => $success,
			'reason' => $reason,
			'insert_id' => $insert_id);
		
        // Send the JSON response data
		$response = $app->response();
		$response['Content-Type'] = 'application/json';
		$response->status(200);
		$response->write(json_encode($dataArray));

	}
	);

// UPDATE AN ANSWER
// OCCURS WHEN A USER REJECTS/ACCEPTS A QUESTION'S ANSWER
$app->put(
	'/answers/:id',
	function ($id) use ($app,$db) {

		// Get the JSON request
		$request = $app->request()->getBody();

		// Load the request property
		$answer_status = $request['status'];

		// Initialize the response data
		$success = false;
		$reason = '';
		$old_status = '';
		$new_status = '';

		// Only continue if the requested status is valid
		if ($answer_status === "pending" || $answer_status === "accepted" || $answer_status === "rejected") {
			try {

				//Select the specified answer from the database
				$sth = $db->prepare('SELECT * FROM answers WHERE id=:answer_id');
				$sth->bindParam(':answer_id', $id);
				$sth->execute();

				// If the answer exists, then we update its status
				if ($sth->rowCount() > 0) {

					// Load the answer data
					$answer_data = $sth->fetch(PDO::FETCH_ASSOC);

					// Get the old question status
					$old_status = $answer_data['status'];

					// Update the status of the answer
					$sth = $db->prepare("UPDATE answers SET status=:answer_status WHERE id=:answer_id");
					$sth->bindParam(':answer_id', $id);
					$sth->bindParam(':answer_status',$answer_status);
					$sth->execute();

					// Save the new status
					$new_status = $answer_status;				
					$success = true;
				} else {
					$reason = "Answer does not exist";
				}

			} catch(PDOException $e) {
				$success = false;
				$reason = $e->getMessage();
			}
		} else {
			$success = false;
			$reason = "Invalid answer status. The valid options are: 'pending', 'accepted', and 'rejected'";
		}

		// Create the response data
		$dataArray = array(
			'success' => $success,
			'reason' => $reason,
			'old_status' => $old_status,
			'new_status' => $new_status);

		// Send the JSON response
		$response = $app->response();
		$response['Content-Type'] = 'application/json';
		$response->status(200);
		$response->write(json_encode($dataArray));
	}
	);


// TODO: RATE AN ANSWER

// Run the Slim app as specified by the Slim Framework documentation
$app->run();
