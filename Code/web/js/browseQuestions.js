



function loadAllQuestions() {
	$('#browseNav li').removeClass('selected');
	$('#browseNav li:nth-child(1)').addClass('selected');
	
	// LOAD ALL QUESTIONS
	
	$('#mainContent').empty();
	
	for (var i = 0; i < 50; i++) {
		var item = document.createElement('div');
		$(item).addClass('questionItem');
		
		var image = document.createElement('img');
		$(image).addClass('questionImage');
		image.setAttribute('src', 'http://placebox.es/150');
	
		var categoryLabel = document.createElement('label');
		$(categoryLabel).text('Category: Math');
		
		var subcategoryLabel = document.createElement('label');
		$(subcategoryLabel).text('Subategory: Geometry');
		
		var dateLabel = document.createElement('label');
		$(dateLabel).text('Date: 01/05/2013 01:52:13 PM');
		
		item.appendChild(image);
		item.appendChild(categoryLabel);
		item.appendChild(subcategoryLabel);
		item.appendChild(dateLabel);
		
		$('#mainContent')[0].appendChild(item);
	
	}
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