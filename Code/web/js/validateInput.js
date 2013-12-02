//input validation for out account creation and login form
// we wait for the DOM to load
$(document).ready(function() {
	//login form
	//must be an email and password that is 8 characters in length
	$('#loginForm').validate({
		rules: {
			email: {
				required: true,
				email: true
			},
			password: {
				required: true,
			}
		},
		submitHandler: function(form) {
			form.submit();
		},
		errorPlacement: function(error, element) {
			$('#loginError').html(error[0].innerHTML);
		},
		success: function() {
			$('#loginError').html('');
		}
	});
	//Account creation form where all elements are required
	//password is set to 8 characters
	//ZIP set to 5 digits
	//email must be an email
	//Telephone must be a legal US phone #
	$('#registerForm').validate({
		rules: {
			first_name: {
				required: true
			},
			last_name: {
				required: true
			},
			email: {
				required: true,
				email: true
			},
			password: {
				required: true,
				minlength: 8,
			},
			confirm_password: {
				equalTo: "#password"
			}
		},
		submitHandler: function(form) {
			form.submit();
		},
		// the output errors are inserted before the feild, notifying the user
		errorPlacement: function(error, element) {
			error.insertBefore(element);
		}
	});
});