<?php

//EEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEE//
//									//
//	This script will call the appropiate functions in the file to   //
//	populate the snap2ask database.					//
//									//
//EEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEE//

//connect to the database
$dbConnection = mysql_connect("localhost", "cProject", "snap2ask");

//checking connection
if (!$dbConnection)
{
	die('Connection failure: ' . mysql_error());
}

//select snap2ask database
mysql_select_db("snap2ask", $dbConnection) or die("It couldn't select the snap2ask database. Error: " . msql_error());

//Calling the functions to populate each table
insertCategories ($dbConnection, "inputFiles/categories.csv");
insertSubcategories ($dbConnection, "inputFiles/subcategories.csv");
insertUsers($dbConnection, "inputFiles/users.csv");
insertQuestions($dbConnection, "inputFiles/questions.csv");

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
	echo "categories succesfully inserted\n\n";
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
	echo "subcategories sucesfully inserted\n\n";
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
			//get preferred category id
                        $sqlQuery = "SELECT id from categories WHERE name = '{$name[3]}';";
                        $response = mysql_query ($sqlQuery);
                        $row = mysql_fetch_assoc($response);
			//echo "\nrow['id'] = " . $row['id'] . "\n";
			//get date_created
			$date = time();
			$date = date("Y-m-d H:i:s");
			$insertUser = "INSERT into users(
				email, 
				password, 
				is_tutor, 
				preferred_category_id, 
				date_created, 
				authentication_mode, 
				first_name, 
				last_name, 
				rating) values (
				'{$name[0]}', 
				'{$name[1]}', 
				{$name[2]}, 
				'{$row['id']}',
				'{$date}',
				'{$name[4]}',
				'{$name[5]}', 
				'{$name[6]}', 
				{$name[7]});";  
			
			if (!mysql_query($insertUser))
                        {
                                die("Imposible to insert the user" . mysql_error());
                        }
		}
	}

	//closing the input file
        fclose($input);
        echo "sucesfully inserted the users\n";
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
		echo "loop\n";
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
                        //echo "\nrow['id'] = " . $row['id'] . "\n";
                        //get date_created
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
		
			if (!mysql_query($insertQuestion))
			{
				die("Impossible to insert the question" . mysql_error());
			}
		}
	}
	echo "questions sucsessfully inserted.\n";
}

?>
