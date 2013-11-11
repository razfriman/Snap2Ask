<?php

//EEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEE//
//									//
//	This script will call the appropiate functions in the file to   //
//	populate the snap2ask database.					//
//									//
//EEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEE//

//connect to the database
$dbConnection = mysql_connect("127.0.0.1", "snap2ask", "snap2ask");

//checking connection
if (!$dbConnection)
{
	die('Connection failure: ' . mysql_error());
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

//select snap2ask database
mysql_select_db("snap2ask", $dbConnection) or die("It couldn't select the snap2ask database. Error: " . msql_error());

//Calling the functions to populate each table
insertCategories ($dbConnection, "inputFiles/categories.csv");
insertSubcategories ($dbConnection, "inputFiles/subcategories.csv");
insertUsers($dbConnection, "inputFiles/users.csv");
insertQuestions($dbConnection, "inputFiles/questions.csv");
insertAnswers($dbConnection, "inputFiles/answers.csv");
insertValidationQuestions($dbConnection, "inputFiles/validation.csv");


//eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee//
//									//
//	This function opens the csv file with categories and iserts the //
//	information in the database					//
//									//
//eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee//

function insertCategories ($dbConnection, $file)
{
	echo "inserting categories \n\n";
	//opening the file
	$input = fopen($file, "r") or exit ("Unable to open the file");

	//skip first line
	$line = fgets($input);

	//while there is some line to read
	while (!feof($input))
	{
		//gets the line
		$line = fgets($input);
		//breaks the line on every comme
		$name = explode(",", $line);
		
		//checks that hte line isn't empty
		if ($name[0] != "")
		{
			//inserting a category
			$insertCategory = "INSERT INTO categories(name) VALUES ('{$name[0]}');";
			if (!mysql_query($insertCategory))
			{
				die("Imposible to insert the Category" . mysql_error());		}
		}

	}

	//closing the input file
	fclose($input);
	echo "categories succesfully inserted\n\n<br />";
}



//eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee//
//									//
//	This function reads the input file with the subcategories and 	//
//	adds them to the database					//
//									//
//eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee//

function insertSubcategories ($dbConnection, $file)
{
	echo "inserting subcategories\n\n";
	
	//opening the input file
        $input = fopen($file, "r") or exit ("Unable to open the file subcategories.csv");

        //skip first line
        $line = fgets($input);

	//while there is something to read
        while (!feof($input))
        {
		//read line
                $line = fgets($input);

		//breaking into comma
                $name = explode(",", $line);

		//skeep white lines
                if ($name[0] != "")
                {
			//get the category_id corresponding to this subcategory
			$sqlQuery = "SELECT id from categories WHERE name = '{$name[0]}';";
			$response = mysql_query ($sqlQuery);
			$row = mysql_fetch_assoc($response);
			
			//inserting the subcategory
                        $insertSubcategory = "INSERT INTO subcategories(name, category_id) VALUES ('{$name[1]}', {$row['id']});";
                        if (!mysql_query($insertSubcategory))
                        {
                                die("Imposible to insert the subCategory" . mysql_error()); 
		 	}
                }
        }

	//closing input file
        fclose($input);
	echo "subcategories sucesfully inserted\n\n<br />";
}



//eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee//
//									//
//	This function reads an input file with the users information 	//
//	and adds it to the users table in the snap2ask database		//
//									//
//eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee//

function insertUsers ($dbConnection, $file)
{
        echo "inserting users \n\n";
        //opening the file
        $input = fopen($file, "r") or exit ("Unable to open the file");

        //skip first line
        $line = fgets($input);

        //while there is some line to read
        while (!feof($input))
        {
                //gets the line
                $line = fgets($input);
                //breaks the line on every comme
                $name = explode(",", $line);

                //checks that the line isn't empty
                if ($name[0] != "")
                {
                
                	$hashResult = hashPassword($name[1]);
                
			$date = time();
			$date = date("Y-m-d H:i:s");
			$insertUser = "INSERT into users(
				email, 
				password, 
				salt,
				is_tutor,
				date_created, 
				authentication_mode, 
				first_name, 
				last_name)
				 values (
				'{$name[0]}', 
				'{$hashResult[0]}', 
				'{$hashResult[1]}', 
				{$name[2]}, 
				'{$date}',
				'{$name[4]}',
				'{$name[5]}', 
				'{$name[6]}');";  

			//inserting the user in the databse
			if (!mysql_query($insertUser))
                        {
                                die("Imposible to insert the user" . mysql_error());
                        }
			
			
			if ($name[3] != '')
			{
			//get preferred category id
                        $sqlQuery = "SELECT id from categories WHERE name = '{$name[3]}';";
                        $response = mysql_query ($sqlQuery);
                        $row = mysql_fetch_assoc($response);
			$category_id = $row['id'];
			$sqlQuery = "SELECT id from users WHERE email = '{$name[0]}' AND authentication_mode = '{$name[4]}';";
			$response = mysql_query($sqlQuery);
			$row = mysql_fetch_assoc($response);
			$sqlQuery = "INSERT into verified_categories (user_id,category_id,is_preferred) values({$row['id']}, {$category_id}, true);";
			if (!mysql_query($sqlQuery))
			{
				die ("Imposible to insert prefered category" . mysql_error());
			}
			}
		}
	}

	//closing the input file
        fclose($input);
        echo "sucesfully inserted the users\n\n<br />";
}



//eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee//
//									//
//	This function read an input file with questions and use them	//
//	to populate the database					//
//									//
//eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee//
function insertQuestions($dbConnection, $file)
{
	echo "inserting questions \n\n";
        //opening the file
        $input = fopen($file, "r") or exit ("Unable to open the file");

        //skip first line
        $line = fgets($input);

        //while there is some line to read
        while (!feof($input))
        {
                //gets the line
                $line = fgets($input);
                //breaks the line on every comme
                $name = explode(",", $line);

                //checks that the line isn't empty
                if ($name[0] != "")
                {
                        //get preferred category id
                        $sqlQuery = "SELECT id FROM users WHERE email = '{$name[0]}';";
                        $response = mysql_query ($sqlQuery);
                        $student = mysql_fetch_assoc($response);
			$sqlQuery = "SELECT id FROM categories WHERE name = '{$name[2]}';";
			$response = mysql_query ($sqlQuery);
			$category = mysql_fetch_assoc($response);
			$sqlQuery = "SELECT id FROM subcategories where name = '{$name[3]}' and category_id = '{$category['id']}';";
			$response = mysql_query($sqlQuery);
			$subcategory = mysql_fetch_assoc($response);      
                        $date = time();
                        $date = date("Y-m-d H:i:s");	
			$insertQuestion = "INSERT into questions(
			student_id,
			description,
			category_id,
			subcategory_id,
			image_url,
			image_thumbnail_url,
			date_created) values (
			'{$student['id']}',
			'{$name[1]}',
			'{$category['id']}',
			'{$subcategory['id']}',
			'{$name[4]}',
			'{$name[5]}',
			'{$date}');";

			//inserting the questions in the database		
			if (!mysql_query($insertQuestion))
			{
				die("Impossible to insert the question" . mysql_error());
			}
		}
	}
	echo "questions sucsessfully inserted.\n\n<br />";
}



//eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee//
//									//
//	This function reads an input file with answers and populates 	//
//	the database with them						//
//									//
//eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee//

function insertAnswers($dbConnection, $file)
{
        echo "inserting answers \n\n";
        //opening the file
        $input = fopen($file, "r") or exit ("Unable to open the file");

        //skip first line
        $line = fgets($input);

        //while there is some line to read
        while (!feof($input))
        {
                //gets the line
                $line = fgets($input);
                //breaks the line on every comme
                $name = explode(",", $line);

                //checks that the line isn't empty
                if ($name[0] != "")
                {
                        //get preferred category id
                        $sqlQuery = "SELECT id FROM questions  WHERE description = '{$name[0]}';";
                        $response = mysql_query ($sqlQuery);
                        $question = mysql_fetch_assoc($response);
                        $sqlQuery = "SELECT id FROM users WHERE email = '{$name[1]}';";
                        $response = mysql_query ($sqlQuery);
                        $tutor = mysql_fetch_assoc($response);
                        $date = time();
                        $date = date("Y-m-d H:i:s");
                        $insertAnswer = "INSERT into answers(
					question_id,
					tutor_id,
					text,
					date_created) values(
					'{$question['id']}',
					'{$tutor['id']}',
					'{$name[2]}',
					'{$date}');";
					
						mysql_query("UPDATE questions SET status=1, times_answered=times_answered+1 WHERE id='{$question['id']}'");

			//inserting answer in the database
			if(!mysql_query($insertAnswer))
			{
				die("Imposible to insert the answer.<br />" . mysql_error());
			}
		}
	}
	echo "answers succesfully inserted \<br />n";
}



//eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee//
//                                                                      //
//      This function opens the csv file with validation questions and  //
//      inserts the information in the database       		        //
//                                                                      //
//eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee//

function insertValidationQuestions ($dbConnection, $file)
{
        echo "inserting validation questions \n\n";
        //opening the file
        $input = fopen($file, "r") or exit ("Unable to open the file");

        //skip first line
        $line = fgets($input);

        //while there is some line to read
        while (!feof($input))
        {
                //gets the line
                $line = fgets($input);
                //breaks the line on every comme
                $name = explode(",", $line);

                //checks that hte line isn't empty
                if ($name[0] != "")
                {
                        //get question ID
                        $selectCategoryID = "SELECT id from categories where name = '{$name[5]}';";
			$response = mysql_query ($selectCategoryID);
                        $category = mysql_fetch_assoc($response);
			
			//insert question
        		$insertQuestion = "INSERT into validationQuestions(
			question,
			optionA,
			optionB,
			optionC,
			rightAnswer,
			category_id) values (
			'{$name[0]}',
			'{$name[1]}',
			'{$name[2]}',
			'{$name[3]}',
			'{$name[4]}',
			'{$category['id']}');";

	                if (!mysql_query($insertQuestion))
                        {
				die ("Impossible ot insert the validation question. Error " . mysql_error()); 
			}
		}
	}
	echo "Validation questions sucesfully entered\n<br />";
}

					

?>
