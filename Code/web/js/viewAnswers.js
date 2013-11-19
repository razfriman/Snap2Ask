$(document).ready(function () {

$('#sortSelection').change(sortAnswers);

});

function sortAnswers() {

	var index = $('#sortSelection')[0].selectedIndex;
		
	switch(index) {
		case 0:
		$('.tutorViewAnswerContainer').tsort('',{order:'desc',attr:'answerDate'});
		break;
		case 1:
		$('.tutorViewAnswerContainer').tsort('',{order:'asc',attr:'answerDate'});
		break;
		case 2:
		$('.tutorViewAnswerContainer').tsort('',{order:'desc',attr:'pay'});
		break;
		case 3:
		$('.tutorViewAnswerContainer').tsort('',{order:'asc',attr:'pay'});
		break;
		case 4:
		$('.tutorViewAnswerContainer').tsort('',{order:'desc',attr:'rating'});
		break;
		case 5:
		$('.tutorViewAnswerContainer').tsort('',{order:'asc',attr:'rating'});
		break;
	}

}
