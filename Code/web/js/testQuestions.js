
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
	$(question).addClass("question");
	question.innerHTML = question_text;
	$(choices).addClass("choice_list");
	for (var i = 1; i < 4; i++){
		var choice_wrapper = document.createElement('div');
		choices.appendChild(choice_wrapper);
		var input = document.createElement('input');
		var choice = document.createElement('span');
		choice_wrapper.appendChild(input);
		choice_wrapper.appendChild(choice);
		$(input).attr("type","radio","name",questionNumber.toString(),"value",i.toString(),"class","choice_input");
		$(choice).addClass("choice_text");
		$(choice).addClass(questionNumber.toString());
		switch(i){
			case 1:
				choice.innerHTML = choiceA;
			break;
			case 2:
				choice.innerHTML = choiceB;
			break;
			case 3:
				choice.innerHTML = choiceC;
			break;
		}

	}

	// var el = document.getElementsByClassName(questionNumber.toString());
	// console.log("got here");
	// console.log(el);
	// el[0].text(choiceA);
	// el[1].text(choiceB);
	// el[2].text(choiceC);


	$('#Test').append(testQuestion);
	
}



// we wait for the DOM to load
$(document).ready(function () {

	$('#Test').empty();
	//just a test category
	var category = "mathematics";
	
	var jqxhr = $.get( baseUrl + "/test", function(data) {
	  
	  for (var i = 0; i < data.length; i++) {
		 // Add the question to the UI
		  addTestQuestion(data[i],i); 
	  }
	  var submit = document.createElement("input");
	  $(submit).attr("type","submit","value","Submit","id","submit")
	  $('#Test').append(submit);
	}).fail(function() {
	    console.log("error loading questions");
	  });		

});