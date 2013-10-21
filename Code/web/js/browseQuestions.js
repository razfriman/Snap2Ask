
var baseUrl = "http://www.snap2ask.com/git/snap2ask/Code/web/api/index.php";
//var baseUrl = "./api/index.php";



function addQuestion(questionData) {
	
	var item = document.createElement('div');
	$(item).addClass('questionItem');
	
	var image = document.createElement('img');
	$(image).addClass('questionImage');
	var image_url = questionData.image_url;
	
	if (!image_url) {
		// Skip questions with an invalid image url
		
		// DEBUG
		//image_url = 'http://placebox.es/150';
		
		return;
	}
	
	image.setAttribute('src', image_url);

	var categoryLabel = document.createElement('label');
	$(categoryLabel).text('Category: ' + questionData.category);
	
	var subcategoryLabel = document.createElement('label');
	$(subcategoryLabel).text('Subategory: ' + questionData.subcategory);
	
	var dateLabel = document.createElement('label');
	$(dateLabel).text('Date: ' + questionData.date_created);
	
	item.appendChild(image);
	item.appendChild(categoryLabel);
	item.appendChild(subcategoryLabel);
	item.appendChild(dateLabel);
	
	$(item).click(function() {
		loadSelectedQuestion(questionData);
	});
	
	$('#mainContent')[0].appendChild(item);
}

function loadSelectedQuestion(questionData) {
	console.log(questionData);
	window.location.href = 'viewQuestion.php?id=' + questionData.id;
}

function loadAllQuestions() {
	$('#browseNav li').removeClass('selected');
	$('#browseNav li:nth-child(1)').addClass('selected');
	
	// LOAD ALL QUESTIONS
	
	$('#mainContent').empty();
	
	var jqxhr = $.get( baseUrl + "/questions", function(data) {
	  
	  for (var i = 0; i < data.length; i++) {
		 
		 // Add the question to the UI
		  addQuestion(data[i]); 
	  }
	}).fail(function() {
	    console.log("error loading questions");
	  });
}

function loadCategoryQuestions() {
	$('#browseNav li').removeClass('selected');
	$('#browseNav li:nth-child(2)').addClass('selected');
	
	$('#mainContent').empty();

	// LOAD QUESTIONS BY CATEGORY
	$('#mainContent p').text("QUESTIONS BY CATEGORY");
}

function loadPreferredSubjectQuestions() {
	$('#browseNav li').removeClass('selected');
	$('#browseNav li:nth-child(3)').addClass('selected');

	$('#mainContent').empty();
		
	// LOAD PREFERRED SUBJECT QUESTIONS
	$('#mainContent p').text("PREFERRED SUBJECT QUESTIONS");
}

function loadRecentQuestions() {
	$('#browseNav li').removeClass('selected');
	$('#browseNav li:nth-child(4)').addClass('selected');
	
	$('#mainContent').empty();
	
	// LOAD RECENT QUESTIONS
	$('#mainContent p').text("RECENT QUESTIONS");
}



// we wait for the DOM to load
$(document).ready(function () {
	loadAllQuestions();
	
	$('#browseNav li:nth-child(1)').click(loadAllQuestions);
	$('#browseNav li:nth-child(2)').click(loadCategoryQuestions);
	$('#browseNav li:nth-child(3)').click(loadPreferredSubjectQuestions);
	$('#browseNav li:nth-child(4)').click(loadRecentQuestions);
});