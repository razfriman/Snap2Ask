//input validation for out account creation and login form

// we wait for the DOM to load
$(document).ready(function () {

    var balance = parseInt($('div#cash h3').html().trim());
                

	//Checks the withdrawal amount
	var validator = $('#withdrawSnapCashForm').validate({ 
		rules: {
			withdraw_amount: {
				required: true,
                digits: true,
                is0: true,
				min: 1,
                
			}
		},
        messages:{
            withdraw_amount:{
                digits: "1Please use numbers",
                required: "2Please enter an amount to withdraw",
                min: "3You cannot withdraw negative SnapCash",
            }
        },
		submitHandler: function(form) {
			form.submit();
		},
		errorPlacement: function(error, element) {
			error.insertBefore(element);
		}
	});



    // this code sends is another warning to tell the user 0 is not a valid number
    // to withdraww though it may be unnecessary
    /*$('#withdrawSnapCashForm').validator.addMethod("is0", function(value, element) {
        var withdraw = value;
        if (withdraw === 0){
            return false;
        }

        return true;

    }, "You cannot withdraw 0 SnapCash");*/
});
