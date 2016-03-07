// loginAction()
function loginAction() {
	// Replace Login button with Logout button
	$('#auth').empty();
	$('#auth').html('<a id="logout" href="#">'+localStorage.getItem("username")+' (Logout)</a>');
	
	// Redirect user to home page
	$('#content').load('pages/home.html');
	
	// Logout button action
	$('#logout').click(function() {
		// Empty userAuth variable (redo this so the login section can be reloaded)
		localStorage.clear();
		$('#auth').empty();
		$('#auth').load('templates/login.html');
	});
}

$(document).on("click", "#login", function(e) {
	// Disable posting form data to webpage
	e.preventDefault();

	// Gather form data and format it into basic auth
	var formData = $('#loginForm').serializeArray();
	var username = formData[0].value;
	var password = formData[1].value;
	var basicAuth = btoa(username+':'+password);
	
	// Store username into localStorage
	localStorage.setItem("username", username);
	
	$.ajax({
		url: "../api/game/login",
		type: "POST",
		headers: {'Authorization': 'Basic '+basicAuth},
		success: function(resultdata)
		{
			// Set the global userAuth variable
			localStorage.setItem("userAuth", basicAuth);
			
			// Run loginAction()
			loginAction();
			
		},
		error: function(resultdata)
		{
			console.log(resultdata);
		}
	});
});