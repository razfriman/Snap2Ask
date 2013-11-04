
var baseUrl = "./api/index.php";


function addTestQuestion(testQuestionData,questionNumber) {
	
	var id = testQuestionData.id;
	var question_text = testQuestionData.question;
	
	var choiceA = testQuestionData.optionA;
	var choiceB = testQuestionData.optionB;
	var choiceC = testQuestionData.optionC;
	var testQuestion = document.createElement('div');
	var question = document.createElement('div');
	var choices = document.createElement('div');
	
	testQuestion.appendChild(question);
	testQuestion.appendChild(choices);
	
	$(testQuestion).addClass("question_wrapper");
	$(testQuestion).attr("id",id);
	$(question).addClass("question");
	
	question.innerHTML = questionNumber.toString() + ". " + question_text;
	$(choices).addClass("choice_list");
	
	for (var i = 1; i < 4; i++){
		var choice_wrapper = document.createElement('div');
		$(choice_wrapper).addClass("choice_wrapper");
		choices.appendChild(choice_wrapper);
		
		var input = document.createElement('input');
		var choice = document.createElement('label');
		var letter;
		
		choice_wrapper.appendChild(input);
		choice_wrapper.appendChild(choice);
		$(choice).addClass("choice_text");
		
		switch(i){
			case 1:
				choice.innerHTML = choiceA;
				letter = 'A';
			break;
			case 2:
				choice.innerHTML = choiceB;
				letter = 'B';
			break;
			case 3:
				choice.innerHTML = choiceC;
				letter = 'C';
			break;
		}
		
		$(choice).attr({
			"for": questionNumber.toString() + letter
		});
		
		$(input).attr({
			type:"radio",
			name:questionNumber.toString(),
			value:letter,
			"id": questionNumber.toString() + letter,
			class:"choice_input"
		});
		
	}

	$('#Test').append(testQuestion);	
}



// we wait for the DOM to load
$(document).ready(function () {

	$('#Test').empty();
	//just a test category
	var jqxhr = $.get( baseUrl + "/test", function(data) {
	  
	  for (var i = 1; i <= data.length; i++) {
		 // Add the question to the UI
		  addTestQuestion(data[i-1],i); 
	  }
	  var submit = document.createElement("input");
	  $(submit).attr({
	  	type:"submit",
	  	value:"Submit",
	  	id:"submit_test"
	  });
	  $('#Test').append(submit);
	}).fail(function() {
	    console.log("error loading questions");
	  });		
});