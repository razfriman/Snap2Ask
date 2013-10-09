<?php
/*
Author: Roman Stolyarov
Date: 10-08-13
This file puts a newly asked question into the database, and saves the image in the file system where the script is located. The file naming convention used is student_id/year/month/timestamp.png and timestamp_t.png, the latter being the thumbnail (not yet implmemented).
*/

	//Connect to database	
	$host = "localhost:3306";
	$username = "cProject";
	$password = "snap2ask";
	$connection = mysql_connect($host, $username, $password);
	if (!$connection)
	{
		die('Connection failure: ' . mysql_error());
	}

	mysql_select_db("snap2ask", $connection) or die("It could select snap2ask database. Error: " . mysql_error());

	//Get all posted values, information about current time/date, and set up image directory names.
		$student_id = $_POST['student_id'];
		$description = $_POST['description'];
		$category_id = $_POST['category'];
		$subcategory_id = $_POST['subcategory'];
		$status = 0;
		$times_answered = 0;
		$date_created = $_SERVER['REQUEST_TIME']; 
		$today_date = date('m/d/Y',$date_created);
		$year = date('Y',$date_created);
		$month = date('m',$date_created);
		$image_directory = "{$student_id}/{$year}/{$month}/";
		$image_url = $image_directory . "{$date_created}.png";
		$image_thumbnail_url = substr($image_url,0,strlen($image_url)-4) . "_t.png";

		//Insert the question into database questions table in database.
		$sql_insert = "INSERT INTO questions (student_id, description, category_id, subcategory_id, status, times_answered, image_url, image_thumbnail_url, date_created) VALUES ({$student_id},'{$description}',{$category_id},{$subcategory_id},{$status},{$times_answered},'{$image_url}','{$image_thumbnail_url}','{$today_date}')";
		if (!mysql_query($sql_insert)){
			die('Insert was a failure' . mysql_error());
		}else{
			//If the insert executed successfully, go on to save the image using the path stored in the database ($image_url and $image_thumbnail_url)
			if ($_FILES["file"]["error"] > 0)
  			{
  				echo "Error: " . $_FILES["file"]["error"] . "<br>";
  			}
			else
  			{
  				//First print out some info about the file.
  				echo "Upload: " . $_FILES["file"]["name"] . "<br>";
  				echo "Type: " . $_FILES["file"]["type"] . "<br>";
  				echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
  				echo "Stored in: " . $_FILES["file"]["tmp_name"];

  				//Create the necessary directories if they are needed. Then, store the currently temporary image file in a permanent location.
  				if (!file_exists($image_directory)) {
    				mkdir($image_directory, 0777, true);
				}
  				if (file_exists($image_url))
      			{
      				echo $_FILES["file"]["name"] . " already exists. ";
      			}
   				else
      			{
      				move_uploaded_file($_FILES["file"]["tmp_name"],
      				$image_url);
      				echo "Stored in: " . $image_url;
      			}
  			}		
  			//Done.	
  			mysql_close($connection);
		}



?>
