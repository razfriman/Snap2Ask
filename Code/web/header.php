<script>
//REMEMBER TO CHANGE URL QUERY STRING AS NECESSARY
//BASED ON QUERY STRING WE WILL MANIPULATE INNER PHP QUESTION LOOKUP
window.onload = function(){
    alert('loaded');
     document.searchfield.addEventListener('submit', function(e){
            e.preventDefault();
            validateSearch();
     }
     , false); 
    }; 

function validateSearch(){
    var y=document.forms["searchfield"]["search"].value;
        window.location.href = "http://snap2ask.com/git/snap2ask/Code/web/browse.php?search=" + y; 
    return true;
}//end function
<?php

if (!defined('inc_file')) {
	die('Direct access is forbidden');
}

// Load these from the session (set during log-in)
$first_name = '';
$balance = 0;

if (isset($_SESSION['first_name'])) {
	$first_name = $_SESSION['first_name'];
}

if (isset($_SESSION['balance'])) {
	$balance = $_SESSION['balance'];
}



?>
<header>
	<div id="cash">
		<h3>SnapCash</h3>
		<h2>
		<?php echo money_format('%i', $balance); ?></h2>
	</div>
	
	<a href="http://www.snap2ask.com"><img src="res/temp_logo.png" alt="Snap-2-Ask Logo" id="logoSmall"/></a>
	
	<div id="rightHeader">		
	
		<div id="welcomeTab">
			<p><?php echo 'Welcome, ' . $first_name; ?></p>	
			<a id="logoutLink" href="logout.php">Log-out</a>
		</div>
					
		<form id="search" name="searchfield" method="POST" action="#">
			<input type="text" name="search" list = "suggestionlist" placeholder="Search" title="Search a Question" x-webkit-speech />
			<?php include("suggestionlist.php");?>
			
			<input type="submit" value="Search"/>
			
		</form>		
	</div>		
</header>
	
