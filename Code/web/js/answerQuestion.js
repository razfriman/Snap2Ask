
$( document ).ajaxError(function(event, jqxhr, settings, exception ) {
	console.log('Global Ajax Error Handler');
	console.log(event);
	console.log(jqxhr);
	console.log(settings);
	console.log(exception);
});

//var baseUrl = "http://www.snap2ask.com/git/snap2ask/Code/web/api/index.php";
var baseUrl = "./api/index.php";

function submitAnswer() {
	
	var answer = $('#answer')[0].value;
	
	console.log(answer);
	
	
	var questionId = $('#question-id-hidden')[0].value;
	var userId = $('#user-id-hidden')[0].value;
	
	var answerData = { 
		"tutor_id": userId,
		"answer_text": $.trim(answer)
	};
	
	$.ajax({
		type: 'POST',
		url: baseUrl + "/questions/" + questionId + "/answers",
		data: JSON.stringify(answerData),
		contentType: "application/json; charset=utf-8",
		dataType: "json",
		success: function (data) {
				            
            $('#answerQuestionMessageLabel').text('Thank you. Your answer has been submitted.');
            
            window.setTimeout(function() {
			 // Redirect to the browse page after 2 seconds
			 window.location.href = 'browse.php';
			}, 2000);
	            
        },
        failure: function(errMsg) {
        	console.log(errMsg);
        	$('#submitQuestionButton').prop('disabled', false);
        }
	    });
}

// we wait for the DOM to load
$(document).ready(function () {
	
	$('#submit-answer-form').validate({ 
		rules: {
			answer: {
				required: true
			}
		},
		submitHandler: function(form) {

			$('#answerQuestionMessageLabel').text('');
						
			// Disable the submit button from clicking multiple times
			$('#submitQuestionButton').prop('disabled', true);
            
            // SUBMIT THE ANSWER TO THE REST API!!!!
            submitAnswer();
            
        },
        errorPlacement: function(error, element) {
        	error.insertBefore(element);
        }
    });
});