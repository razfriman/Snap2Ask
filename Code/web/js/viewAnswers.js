window.onload = function(){
    backup = $(".tutorViewAnswerContainer");
    oldformhtml = document.enablesort.innerHTML;
     document.enablesort.onsubmit = function(e) {
            e.preventDefault();
            newhtml = $("#hiddenhtml").html();
            newhtml = newhtml.substring(newhtml.indexOf("<!--") + 4, newhtml.indexOf("-->"));
            document.enablesort.innerHTML = newhtml;
            sortall();
            document.enablesort.disable.onclick = function(e){
                    e.preventDefault();
                    document.enablesort.innerHTML = oldformhtml;
                    array = backup;
                    finalhtml = "";
                    for (k = 0; k < array.length; k++){
                    finalhtml += array[k].outerHTML;
                    }
                    $("#results").html(finalhtml);
                };
            document.enablesort.startsort.onclick = function(e){
                e.preventDefault();
                sortall();
            };
    }; 
    function sortall(){
        //contid = $(".tutorViewAnswerContainer").eq(0).find(".datetime").attr("id");
        start = document.enablesort.startdate.value
        end = document.enablesort.enddate.value
        validdate = check(start);
        validdate2 = check(end);
        array = backup;
        array2 = [];
        if (validdate && validdate2){
            for (ind = 0; ind < array.length; ind++){
                ansdate = $(array[ind]).find(".datetime").attr("id");
                if (ansdate.localeCompare(start) > 0 && ansdate.localeCompare(end) < 0){
                    array2.push(array[ind]);
                }
            }
            array = array2;
        }
        else if (validdate){
            for (ind = 0; ind < array.length; ind++){
                ansdate = $(array[ind]).find(".datetime").attr("id");
                if (ansdate.localeCompare(start) > 0){
                    array2.push(array[ind]);
                }
            }
            array = array2;
        }
        else if (validdate2){
            for (ind = 0; ind < array.length; ind++){
                ansdate = $(array[ind]).find(".datetime").attr("id");
                if (ansdate.localeCompare(end) < 0){
                    array2.push(array[ind]);
                }
            }
            array = array2;
        }
        function anscmp(a,b){
            str1 = $(a).find(".datetime").attr("id");
            str2 = $(b).find(".datetime").attr("id");
            return str2.localeCompare(str1);
        }//end anscmp
        function paycmp(a,b){
            str1 = $(a).find(".snappay").attr("id");
            str2 = $(b).find(".snappay").attr("id");
            return str2.localeCompare(str1);
        }//end anscmp
        function opppaycmp(a,b){
            str1 = $(a).find(".snappay").attr("id");
            str2 = $(b).find(".snappay").attr("id");
            return str1.localeCompare(str2);
        }//end anscmp
        if(document.enablesort.sortopts.value == "Highest Pay First")
            array.sort(paycmp);
        else if(document.enablesort.sortopts.value == "Lowest Pay First")
            array.sort(opppaycmp);
        else
            array.sort(anscmp);
        mainhtml = $("#mainContent").html();
        finalhtml = "";
        for (k = 0; k < array.length; k++){
            finalhtml += array[k].outerHTML;
        }
        $("#results").html(finalhtml);
    }//end sortAll
    function check(instr){
        if(instr.length !== 10)
            return false;
        else
            return true;
    }
    function submithandle(){
        document.enablesort.startsort.onclick = sortall();
    }
}
