                <?php include("header.php"); ?>
<?php

        $page = "main.php";
        if (!$xview)
            $xview = "start";
            //echo $xview;
    	switch($xview)
		{
			case "forgotpass"		: $page = "forgotpass.php";			break;
            case "start"    	: $page = "getstarted.php";			break;
            case "sgnup"        : $page = "sgnup.php";			break;
            case "loginok"        : $page = "loggingin.php";    		break;
            case "account"        : $page = "account.php";        	break;
            case "results"        : $page = "results.php";            break;
		}
		include_once($page);

		?>
                                <?php include("footer.php"); ?>