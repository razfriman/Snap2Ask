<?php
// Start the named session
session_name('loginSession');
session_start();

// Allow the included files to be executed
define('inc_file', TRUE);

if (!isset($_SESSION['user_id'])) {
	// The user is not logged in
	header('Location: index.php');
	exit;
}

if(!isset($_GET['category_id'])) {
	// The URL request must include a category_id
	header('Location: index.php');
	exit;
}

// Require the functions file
require_once('functions.php');
require_once('api/config.php');

$responseObj = getUserInfo(true);
$categories = getCategories();
$questions = getValidationQuestions($_GET['category_id']);

$category_id = $_GET['category_id'];

$category_name = '';
foreach($categories as $category) {
	if ($category['id'] == $category_id) {
		$category_name = $category['name'];
	}
}



if (isset($_POST['submit']) && $_POST['submit'] == 'Submit Test') {

	$correct = 0;
	$incorrect = 0;
	$skipped = 0;
	$total = sizeof($questions);
	
	for($i = 0; $i < sizeof($questions); $i++) {
		if (isset($_POST[$i])) {
			if ($_POST[$i] == $questions[$i]['rightAnswer']) {
				$correct++;
			} else {
				$incorrect++;
			}
		} else {
			$skipped++;
		}
	}
	
	$percentCorrect = 100 * ($correct/$total);
	
	if ($percentCorrect > VALIDATION_TEST_PASS_THRESHOLD || $total == 0) {
		// Passed the test
		
		// Insert into verified categories
		
		$request = array('category_id' => $_GET['category_id']);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $base_url . '/api/index.php/users/' . $responseObj['id'] . '/verified_categories');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));
		$response = curl_exec($ch);
		curl_close($ch);
		
		header('Location: testPassed.php');
		exit;
	} else {
		// Failed the test
		header(sprintf('Location: testFailed.php?category=%s&correct=%s&total=%s&percentCorrect=%s&passingPercentage=%s', $category_id, $correct, $total, money_format('%i', $percentCorrect), VALIDATION_TEST_PASS_THRESHOLD));
		exit;
	}
}


?>

<!DOCTYPE html>
<html>

<head>
	<title>Snap-2-Ask | Subject Test</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="shortcut icon" type="image/x-icon" href="res/favicon.ico">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
	<?php include_once("ganalytics.php") ?>
	<header class="tall">
		<a href="index.php"> <img id="logoTall" src="res/logo.png" alt="Snap-2-Ask Logo"/> </a>
	</header>

	<div id="content">
		
		<div id="subjectTestContainer">
			
			<?php
			
			echo sprintf('<input type="hidden" id="category-id-hidden" name="category" value="%s" />', $_GET['category_id']);			

			echo "<h1 id='title'>Tutor Validation Test for " . $category_name . "</h1>"
			?>
			
			<form id="testQuestionsForm" method="post" action="#">
				<!---Population test questions here.-->	
				<?php
				
				$i = 0;
				foreach($questions as $question) {
					
					echo '<div class="question_wrapper">';
					echo sprintf('<div class="question">%s</div>',$question['question']);
					
					echo '<div class="choice_list">';
					
					for($j = 0; $j < 3; $j++) {
						
						$letter = '';
						
						if ($j == 0) {
							$letter = 'A';
						} else if ($j == 1) {
							$letter = 'B';
						} else if ($j == 2) {
							$letter = 'C';
						}
						
						echo '<div class="choice_wrapper">';
						echo sprintf('<input type="radio" name="%d" id="%s" value="%s" class="choice_input" />',$i, $i . $letter, $letter);
						echo sprintf('<label class="choice_text" for="%s">%s</label>', $i . $letter, $question['option' . $letter]);
						echo '</div>';
					}
					
					echo '</div></div>';
					$i++;
					
					}
				?>
				
				<input type="submit" name="submit" value="Submit Test" />
			</form>
		</div>
	</div>
	
	<?php include('footer.php') ?>
</body>
</html>
