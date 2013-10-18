



function loadAllQuestions() {
	$('#browseNav li').removeClass('selected');
	$('#browseNav li:nth-child(1)').addClass('selected');
	
	// LOAD ALL QUESTIONS
	$('#mainContent p').text("ALL QUESTIONS");
}

function loadCategoryQuestions() {
	$('#browseNav li').removeClass('selected');
	$('#browseNav li:nth-child(2)').addClass('selected');
	
	// LOAD QUESTIONS BY CATEGORY
	$('#mainContent p').text("QUESTIONS BY CATEGORY");
}

function loadPreferredSubjectQuestions() {
	$('#browseNav li').removeClass('selected');
	$('#browseNav li:nth-child(3)').addClass('selected');
	
	// LOAD PREFERRED SUBJECT QUESTIONS
	$('#mainContent p').text("PREFERRED SUBJECT QUESTIONS");
}

function loadRecentQuestions() {
	$('#browseNav li').removeClass('selected');
	$('#browseNav li:nth-child(4)').addClass('selected');
	
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