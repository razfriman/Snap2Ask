  (function() {
       var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
       po.src = 'https://apis.google.com/js/client:plusone.js';
       var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
     })();
     
          
     // LOGOUT
     //gapi.auth.signOut();
     
     function signinCallback(authResult) {
     
	  if (authResult['access_token']) {
	
		  // Update the app to reflect a signed in user
		  // Hide the sign-in button now that the user is authorized, for example:
		  document.getElementById('signinButton').setAttribute('style', 'display: none');

		  gapi.auth.setToken(authResult);
	  
		  console.log('Sign-in state: SUCCESS');
		  
		  // Load Profile Info
		  gapi.client.load('plus','v1', function(){	
			  var request = gapi.client.plus.people.get({
			   'userId': 'me'
			 });
			 request.execute(function(resp1) {
			 
			 var firstName  = resp1.name.familyName;
			 var lastName = resp1.name.givenName;
			 var oauthId = resp1.id;
			 
			 console.log('First Name: ' + resp1.name.familyName);
			 console.log('Last Name: ' + resp1.name.givenName);
			 console.log('OAuth ID: ' + resp1.id);
			 
			 // Load Email
			  gapi.client.load('oauth2', 'v2', function() {
				  gapi.client.oauth2.userinfo.get().execute(function(resp2) {
				    
				    var email = resp2.email;
				    
				    console.log('Email: ' + resp2.email);
				    
				    // REDIRECT				    
			       var form = $('<form></form>');
			   	   $(form).hide().attr('method','post').attr('action','index.php');
			   	   $(form).append($('<input type="hidden" />').attr('name','token').val(authResult['access_token']));
			   	   $(form).append($('<input type="hidden" />').attr('name','authentication_mode').val('google'));
			   	   $(form).append($('<input type="hidden" />').attr('name','first_name').val(firstName));
			   	   $(form).append($('<input type="hidden" />').attr('name','last_name').val(lastName));
			   	   $(form).append($('<input type="hidden" />').attr('name','oauth_id').val(oauthId));
			   	   $(form).append($('<input type="hidden" />').attr('name','email').val(email));
			 	   $(form).appendTo('body').submit();
				 	   
				  })
				});
			 });
			});
       
       }
       else if (authResult['error']) {
	    // Update the app to reflect a signed out user
	    // Possible error values:
	    //   "user_signed_out" - User is signed-out
	    //   "access_denied" - User denied access to your app
	    //   "immediate_failed" - Could not automatically log in the user
	    console.log('Sign-in state: ' + authResult['error']);
	  }
	}