$(document).ready(function () {

$('#sortSelection').change(sortAnswers);
$('#gobut').click(sortAnswers);
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
	startDate = document.sortform.startdate.value
    	endDate = document.sortform.enddate.value
    	isStart = (startDate.length === 10);
    	isEnd = (endDate.length === 10);
    	if (isEnd || isStart){
        	$('.tutorViewAnswerContainer').hide();
        	for (ind = 0; ind < $('.tutorViewAnswerContainer').length; ind++){
            		var node = $('.tutorViewAnswerContainer')[ind];
            		var ansdate = $(node).attr('answerDate');
            		if (isStart && isEnd && ansdate.localeCompare(startDate) > 0 && ansdate.localeCompare(endDate) < 0){
                    		$(node).show();
            		}
            		else if (isEnd && ansdate.localeCompare(endDate) < 0){
                    		$(node).show();
            		}
            		else if (isStart && ansdate.localeCompare(startDate) > 0){
                    		$(node).show();
            		} //end if-else-if
        	}//end for loop
    	}//end outer if

}//end sortAnswers function
