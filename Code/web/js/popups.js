$(document).ready(function(){

    $('#deleteAccountButton').click(function(event){
    
        var r = confirm("Are you sure you want to deleted your account?");
        
        if (r==true){
            x="You have deleted your account";
        }
        else{
            x="You canceled deleted your account";
            event.preventDefault();
        }
    
    });    

});
