<?php
$input = fopen("categories.csv", "r") or exit ("Unable to open the file");
$dbConnection = mysql_connect("localhost", "cProject", "snap2ask");

//checking connection
if (!$dbConnection)
{
	die('Connection failure: ' . mysql_error());
}
//select database
mysql_select_db("snap2ask", $dbConnection) or die("It couldn't select the snap2ask database. Error: " . msql_error());

//skip first line
$line = fgets($input);
while (!feof($input))
{
	$line = fgets($input);
	$name = explode(",", $line);
	if ($name[0] != "")
	{
		$insertCategory = "INSERT INTO categories(name) VALUES ('{$name[0]}');";
		if (!mysql_query($insertCategory))
		{
			die("Imposible to insert the Category" . mysql_error());		}
	}
//	echo $name[0];
}

fclose($input);

?>
