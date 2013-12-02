$(document).ready(function() {
	// Validate the form to ensure a value is entered
	$('#search').validate({
		rules: {
			searchQuery: {
				required: true
			}
		},
		submitHandler: function(form) {
			// Encode the search query into a URL-compatible string
			var queryString = {
				"search": $('#searchQuery')[0].value
			};
			var encodedQuery = $.param(queryString, true);
			// Redirect to the browse page with the search query encoded into the queryString
			window.location.href = 'browse.php?' + encodedQuery;
		},
		errorPlacement: function(error, element) {
			// Search query is empty
			//error.insertAfter(element);
		}
	});
});