<html>
<head>
	<title></title>
</head>
<body>

<?php

function isGibberish($s)
{
    $s = "10xl";
    $vow = 1;
    $cons = 1;
    for ($ind = 0; $ind < strlen($s); $ind++){
        $let = $s[$ind];
        if ($let === "a" OR $let === "e" OR $let === "i" OR $let === "o" OR $let === "u")
            $vow++;
        else
            $cons++;
    }
    $ratio = $vow/$cons;
    if ($ratio < 0.2 OR $ratio > 0.6)
        echo "gibberish";
    else
        echo "valid";
}

isGibberish("happy");
?>




</body>
</html>
