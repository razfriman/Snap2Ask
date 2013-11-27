<?php

if (!defined('inc_file')) {
    die('Direct access is forbidden');
}

// Require the functions file
require_once('functions.php');

$responseObj = getUserInfo(true);
$categories = getCategories();

// Load these from the session (set during log-in)
$first_name = '';
$balance = 0;

if (isset($responseObj['first_name'])) {
	$first_name = $responseObj['first_name'];
}

if (isset($responseObj['balance'])) {
	$balance = $responseObj['balance'];
}

$searchQuery = '';

if(isset($_GET['search'])) {
	$searchQuery = $_GET['search'];
}

include_once("ganalytics.php");

?>



<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script src="js/search.js" type="text/javascript"></script>
<script type="text/javascript" src="js/logoutWarning.js"></script>
    
<header class="short">
	<div id="cash">
		<h2>SnapCash</h2>
		<h3>
			<?php echo $balance; ?>
		</h3>
	</div>

	<a href="index.php"><img src="res/logo.png" alt="Snap-2-Ask Logo" id="logoShort"/></a>
	
	<?php echo sprintf('<p id="welcomeNotice">Welcome, <a href="profile.php" title="View your profile">%s</a></p>',htmlspecialchars($responseObj['first_name'])); ?>
	
	<a id="logoutLink" href="logout.php">Log-out</a>
	
	<form id="search" method="POST" action="#">
		<input type="submit" value="Search"/>
		<input type="text" name="searchQuery" id="searchQuery" list = "suggestionlist" value="<?php echo $searchQuery; ?>" placeholder="Search" title="Enter a search query" />
		<datalist id='suggestionlist'>
			<?php 
			
			// Add all categories and subcategories to the suggestion list
			foreach($categories as $category)
			{
				echo sprintf('<option value="%s">%s</option>', htmlspecialchars($category['name']), htmlspecialchars($category['name']));
				
				foreach($category['subcategories'] as $subcategory)
				{
					echo sprintf('<option value="%s">%s</option>', htmlspecialchars($subcategory['name']), htmlspecialchars($subcategory['name']));
				}
			}
			
			?>
		</datalist>
		
		
	</form>			
</header>
