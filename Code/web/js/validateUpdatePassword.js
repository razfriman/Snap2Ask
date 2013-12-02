//input validation for out account creation and login form
// we wait for the DOM to load
$(document).ready(function() {
	//login form
	//must be an email and password that is 8 characters in length
	$('#updatePasswordForm').validate({
		rules: {
			password: {
				required: true,
				minlength: 8,
			},
			confirmPassword: {
				equalTo: "#password"
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