 <?php
if ($_POST['answer']){
?>
<script>
window.scrollTo(0,document.body.scrollHeight);
</script>
<?php
echo "User ID: " . $_POST['user_id'] . " answered: <div id='tutoranswer'>" . $_POST['answer'] . "</div>";
}
else
{
?>
<a name="answerarea"></a>
    			<form name=submitanswer" action="<?=$PHP_SELF?>" method="POST">
					<label for="answer">Answer:</label>
					<textarea id="answer" name="answer"></textarea>
					
					<input type="hidden" id="user-id-hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>" />
					<input type="hidden" id="question-id-hidden" name="question_id" value="<?php echo $_GET['id']; ?>" />
					<input type="submit" id="submitQuestionButton" value="Submit Answer" />
				</form>
    <?php
    }
    ?>