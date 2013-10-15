<?php

//EEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEE//
//									//
//	This script will call the appropiate functions in the file to   //
//	populate the snap2ask database.					//
//									//
//EEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEE//

//connect to teh database
$dbConnection = mysql_connect("localhost", "cProject", "snap2ask");

//checking connection
if (!$dbConnection)
{
	die('Connection failure: ' . mysql_error());
}

//select snap2ask database
mysql_select_db("snap2ask", $dbConnection) or die("It couldn't select the snap2ask database. Error: " . msql_error());

//Calling the functions to populate each table
//insertCategories ($dbConnection, "categories.csv");
//insertSubcategories ($dbConnection, "subcategories.csv");



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

?>
