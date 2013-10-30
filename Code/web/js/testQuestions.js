
var baseUrl = "../web/api/index.php";


function addTestQuestion(testQuestionData) {

	var id = testQuestionData.id;
	var question = testQuestionData.question;
	var choiceA = testQuestionData.choiceA;
	var choiceB = testQuestionData.choiceB;
	var choiceC = testQuestionData.choiceC;
	$('#Test').html(question);
	var testQuestion = document.createElement('div');
	$(testQuestion).addClass("question_wrapper");
	var question = document.createElement('div');
	$(question).addClass("question");
	var choices = document.createElement('div');
	#(choices).addClass("choices");
	testQuestion.appendChild(question);
	testQuestion.appendChild(choices);
	for (var i = 1; i < 4; i++){
		var possibleChoice = document.createElement('div');
		$(choices).appendChild(possibleChoice);
		var input = document.createElement('input');
		$(possibleChoice).appendChild(input);
		$(input).attr("type","radio","name",id.toString(),"value",i.toString(),"class","choice");
	}

	$(answerTable)[0].html(choiceA);
	$(answerTable)[1].html(choiceB);
	$(answerTable)[2].html(choiceC);

	$('#Test').appendChild(testQuestion);
	
}



// we wait for the DOM to load
$(document).ready(function () {

	$('#Test').empty();
	console.log("byebye");
	//just a test category
	var category = "mathematics";
	
	var jqxhr = $.get( baseUrl + "/test", function(data) {
	  
	  for (var i = 0; i < data.length; i++) {
		 console.log("hello");
		 // Add the question to the UI
		  addTestQuestion(data[i]); 
	  }
	}).fail(function() {
	    console.log("error loading questions");
	  });		

});