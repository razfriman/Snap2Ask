
var baseUrl = "http://www.snap2ask.com/git/snap2ask/Code/web/api/index.php";


function addTestQuestion(testQuestionData) {

	var question = testQuestionData.question;
	var choiceA = testQuestionData.choiceA;
	var choiceB = testQuestionData.choiceB;
	var choiceC = testQuestionData.choiceC;
	$('#Test').html(question);
	var testQuestion = document.createElement('div');
	$(testQuestion).addClass("question_wrapper");
	var answerTable = document.createElement('table');
	$(answerTable).addClass("choices");
	for (var i = 0; i < 3; i++){
		var tr = document.createElement('tr');
		$(answerTable).appendChild(tr);
		var td = document.createElement('td');
		$(tr).appendChild(td);
	}
	$(answerTable)[0][0].html('A. '+choiceA);
	$(answerTable)[1][0].html('B. '+choiceB);
	$(answerTable)[2][0].html('C. '+choiceC);

	$('#Test').appendChild(testQuestion);
	
}



// we wait for the DOM to load
$(document).ready(function () {

	$('#Test').empty();
	console.log("byebye");
	//just a test category
	var category = "mathematics";
	
	var jqxhr = $.get( baseUrl + "/testQuestions", function(data) {
	  
	  for (var i = 0; i < data.length; i++) {
		 console.log("hello");
		 // Add the question to the UI
		  addTestQuestion(data[i]); 
	  }
	}).fail(function() {
	    console.log("error loading questions");
	  });		

});