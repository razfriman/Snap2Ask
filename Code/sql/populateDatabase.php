<?php

require_once('../web/api/config.php');

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


echo 'Populating database for Snap2Ask' . '<br />';
loadCategories($db);
loadSubcategories($db);
loadUsers($db);
loadQuestions($db);
loadValidationQuestions($db);
echo 'Finished populating database for Snap2Ask';




function loadCategories($db) {
	
	try {
		$rows = parseCsvFile('inputFiles/categories.csv');
		
		foreach($rows as $row) {
					
			$sth = $db->prepare('INSERT INTO categories (name) VALUES (:name)');
			$sth->bindParam(':name',$row['name']);
			$sth->execute();
		}
		
		echo sprintf('Loaded %s categories',sizeof($rows)) . '<br />';
	} catch (Exception $e) {
		echo 'Error loading categories: ' . $e->getMessage() . '<br />';
	}	
}

function loadSubcategories($db) {
	
	try {
		$rows = parseCsvFile('inputFiles/subcategories.csv');
		
		foreach($rows as $row) {
			
			$sth = $db->prepare('SELECT id FROM categories WHERE name=:name');
			$sth->bindParam(':name',$row['categoryName']);
			$sth->execute();
			$categoryIdRow = $sth->fetch();
			$row['category_id'] = $categoryIdRow[0];
			
			$sth = $db->prepare('INSERT INTO subcategories (name,category_id) VALUES (:name,:category_id)');
			$sth->bindParam(':name',$row['subcategoryName']);
			$sth->bindParam(':category_id',$row['category_id']);
			$sth->execute();
		}
		
		echo sprintf('Loaded %s subcategories',sizeof($rows)) . '<br />';
	} catch (Exception $e) {
		echo 'Error loading subcategories: ' . $e->getMessage() . '<br />';
	}	
}


function loadUsers($db) {
	
	try {
		$rows = parseCsvFile('inputFiles/users.csv');
		
		foreach($rows as $row) {
			
			$hashResult = hashPassword($row['password']);
			$row['hashed_password'] = $hashResult[0];
			$row['salt'] = $hashResult[1];
			$row['authentication_mode'] = 'custom';
			$row['date_created'] = date("Y-m-d H:i:s");
			
			$sth = $db->prepare('INSERT INTO users
			(email,first_name,last_name,password,salt,is_tutor,date_created,authentication_mode)
			VALUES
			(:email,:first_name,:last_name,:password,:salt,:is_tutor,:date_created,:authentication_mode)');
			$sth->bindParam(':email',$row['email']);
			$sth->bindParam(':first_name',$row['first_name']);
			$sth->bindParam(':last_name',$row['last_name']);
			$sth->bindParam(':password',$row['hashed_password']);
			$sth->bindParam(':salt',$row['salt']);
			$sth->bindParam(':is_tutor',$row['is_tutor']);
			$sth->bindParam(':date_created',$row['date_created']);
			$sth->bindParam(':authentication_mode',$row['authentication_mode']);		
			$sth->execute();
		}
		
		echo sprintf('Loaded %s users',sizeof($rows)) . '<br />';
	} catch (Exception $e) {
		echo 'Error loading users: ' . $e->getMessage() . '<br />';
	}	
}

function loadQuestions($db) {
	
	try {
		$rows = parseCsvFile('inputFiles/questions.csv');
		
		foreach($rows as $row) {
			
			$row['date_created'] = date("Y-m-d H:i:s");
			
			$sth = $db->prepare('SELECT id FROM categories WHERE name=:name');
			$sth->bindParam(':name',$row['category']);
			$sth->execute();
			$categoryIdRow = $sth->fetch();
			$row['category_id'] = $categoryIdRow[0];
			
			$sth = $db->prepare('SELECT id FROM subcategories WHERE name=:name');
			$sth->bindParam(':name',$row['subcategory']);
			$sth->execute();
			$subcategoryIdRow = $sth->fetch();
			$row['subcategory_id'] = $subcategoryIdRow[0];
			
			$sth = $db->prepare('INSERT INTO questions
			(student_id,description,category_id,subcategory_id,image_url,image_thumbnail_url,date_created)
			VALUES
			(:student_id,:description,:category_id,:subcategory_id,:image_url,:image_thumbnail_url,:date_created)');
			$sth->bindParam(':student_id',$row['student_id']);
			$sth->bindParam(':description',$row['description']);
			$sth->bindParam(':category_id',$row['category_id']);
			$sth->bindParam(':subcategory_id',$row['subcategory_id']);
			$sth->bindParam(':image_url',$row['image_url']);
			$sth->bindParam(':date_created',$row['date_created']);
			$sth->bindParam(':image_thumbnail_url',$row['image_thumbnail_url']);

			$sth->execute();
		}
		
		echo sprintf('Loaded %s questions',sizeof($rows)) . '<br />';
	} catch (Exception $e) {
		echo 'Error loading questions: ' . $e->getMessage() . '<br />';
	}	
}


function loadValidationQuestions($db) {
	
	try {
		$rows = parseCsvFile('inputFiles/validation.csv');
		
		foreach($rows as $row) {
			
			$sth = $db->prepare('SELECT id FROM categories WHERE name=:name');
			$sth->bindParam(':name',$row['categoryName']);
			$sth->execute();
			$categoryIdRow = $sth->fetch();
			$row['category_id'] = $categoryIdRow[0];
			
			$sth = $db->prepare('INSERT INTO validationQuestions
			(question,optionA,optionB,optionC,rightAnswer,category_id)
			VALUES
			(:question,:optionA,:optionB,:optionC,:rightAnswer,:category_id)');
			$sth->bindParam(':question',$row['question']);
			$sth->bindParam(':optionA',$row['optionA']);
			$sth->bindParam(':optionB',$row['optionB']);
			$sth->bindParam(':optionC',$row['optionC']);
			$sth->bindParam(':rightAnswer',$row['rightAnswer']);
			$sth->bindParam(':category_id',$row['category_id']);
			$sth->execute();
		}
		
		echo sprintf('Loaded %s validation questions',sizeof($rows)) . '<br />';
	} catch (Exception $e) {
		echo 'Error loading validation questions: ' . $e->getMessage() . '<br />';
	}	
}


// Parse a CSV file into an associative array
function parseCsvFile($filePath) {
	if (!file_exists($filePath)) {
		throw new Exception('Error: cannot open CSV file "' . $filePath . '"');
	}
	
	$file = file($filePath);
	$rows = array_map('str_getcsv', $file);
	$header = array_shift($rows);
	$csv = array();
	
	foreach ($rows as $row) {
	  $csv[] = array_combine($header, $row);
	}
	
	return $csv;
}

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
?>
