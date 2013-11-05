//input validation for out account creation and login form

// we wait for the DOM to load
$(document).ready(function () {
	
	//login form
	//must be an email and password that is 8 characters in length
	$('#withdrawSnapCashForm').validate({ 
		rules: {
			withdraw_amount: {
				required: true,
				min: 1,
				digits: true
			}
		},
		submitHandler: function(form) {
			form.submit();
		},
		errorPlacement: function(error, element) {
			error.insertBefore(element);
		}
	});
});