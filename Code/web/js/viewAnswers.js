$(document).ready(function () {
    $('#sortSelection, #catlist').change(sortAnswers);
    $('#gobut, #withrat').click(sortAnswers);
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
	startDate = document.sortform.startdate.value;
    endDate = document.sortform.enddate.value;
    cat = document.sortform.catlist.value;
    isStart = (startDate.length === 10);
    isEnd = (endDate.length === 10);
    rated = document.sortform.withrat.checked;
    $('.tutorViewAnswerContainer').hide();
    if (isEnd || isStart){
        for (ind = 0; ind < $('.tutorViewAnswerContainer').length; ind++){
            node = $('.tutorViewAnswerContainer')[ind];
            var ansdate = $(node).attr('answerDate');
            anscat = $(node).attr('cat');
            rating = $(node).attr('rating');
            if (cat !== "All" && cat !== anscat)
                continue;
            if (rated == true && rating < 1)
                continue;
            if (isStart && isEnd && ansdate.localeCompare(startDate) > 0 && ansdate.localeCompare(endDate) < 0){
                $(node).show();
                continue;
            }
            if (!isEnd && isStart && ansdate.localeCompare(startDate) > 0){
                $(node).show();
                continue;
            }
            if (!isStart && isEnd && ansdate.localeCompare(endDate) < 0){
                $(node).show();
                continue;
            }
        }//end for loop
    }//end outer if
    else if(cat !== "All"){
        for (ind = 0; ind < $('.tutorViewAnswerContainer').length; ind++){
            node = $('.tutorViewAnswerContainer')[ind];
            anscat = $(node).attr('cat');
            rating = $(node).attr('rating');
            if (cat !== "All" && cat !== anscat)
                continue;
            if (rated == true && rating < 1)
                continue;
            if (cat === anscat)
                $(node).show();
        }//end for loop
    }
    else{
        for (ind = 0; ind < $('.tutorViewAnswerContainer').length; ind++){
            node = $('.tutorViewAnswerContainer')[ind];
            rating = $(node).attr('rating');
            if (!rated || rating > 0)
                $(node).show();
        }//end for loop
    }
}//end sortAnswers function
