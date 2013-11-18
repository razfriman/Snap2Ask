//input validation for out account creation and login form

// we wait for the DOM to load
$(document).ready(function () {
	
	//login form
	//must be an email and password that is 8 characters in length
	$('#editTutorProfile').validate({ 
		rules: {
			first_name: {
				required: true,
			},
			last_name: {
				required: true,
			},
			currentPassword: {
				required: false,
			},
			newPassword: {
				minlength: 8,
			},
			confirmNewPassword: {
				equalTo: "#confirmPassword",
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