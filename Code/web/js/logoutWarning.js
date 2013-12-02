//waiting for DOM to load
$(document).ready(function() {
	// a click listener is implement on the log out button to warn a user that they are about to logout
	$('#logoutLink').click(function(event) {
		var r = confirm("Are you sure you want to logout?");
		//if user returns yes, then they are logged out, else they stay logged in the session
		if (!r) {
			event.preventDefault();
		}
	});
});