

<?php

/*Gets result of json query and a list of the table's column names and puts it into a JSON object, packaged and ready to be sent back to the client.*/
	function makeJsonFromRows($result, $column_names){
		$len = count($column_names);
		$wrapper = array();
		while ($row=mysql_fetch_assoc($result)){
			for ($i = 0; $i < $len; $i++){
				$wrapper[] = array();
				$wrapper[$i][] = $row[$column_names[$i]];
			}
		}
		$data = array();
		for ($i = 0; $i < $len; $i++){
		 	$data[$column_names[$i]] = $wrapper[$i];
		 }
		return json_encode($data);
	}
?>