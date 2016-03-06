function newActionWindow() {

	//Action Window Data
	var window =
		'<div class="actionWindow">'
			+'<div class="actionTitle">'
				+'<h3>Capitol Building</h3>'
			+'</div>'
			+'<div class="actionClose">'
				+'X'
			+'</div>'
			+'<div class="actionData mediumAction">'
				+'<h3>Lorem Ipsum</h3>'
				+'<p>'
				+'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum bibendum, neque id dignissim faucibus, turpis arcu ullamcorper nulla, nec finibus justo nulla at dolor. In hac habitasse platea dictumst. In pretium ex vel justo euismod commodo. Donec eu eleifend metus, quis interdum purus. Maecenas ultrices elit nunc. Nam ut bibendum nisl. Ut interdum erat non tempor euismod. Phasellus leo risus, laoreet in risus ut, dignissim faucibus libero. Vivamus mollis efficitur libero, quis vulputate diam lobortis sit amet. Mauris blandit blandit justo, ornare maximus risus bibendum quis. Nulla lectus enim, semper vel dolor et, convallis accumsan nisl.'
				+'</p>'
				+'<p>'
				+'Integer metus tellus, condimentum in enim vitae, interdum porttitor ante. Pellentesque nec efficitur odio. Pellentesque magna ligula, lobortis quis venenatis sit amet, sollicitudin ac est. Quisque sit amet est fringilla, tempor odio non, tempus tellus. Pellentesque nec neque vel metus luctus ornare vitae vel leo. Phasellus condimentum mi sem. Nunc a risus sit amet enim tempus aliquam ac nec eros. Maecenas non neque diam. Aenean aliquet ac leo id interdum. Aliquam ac aliquet eros. Sed vehicula eleifend ligula, eu commodo velit tincidunt ut. Vestibulum eget urna quam. Fusce congue tempus maximus.'
				+'</p>'
			+'</div>'
		+'</div>';
	
	//Create the new action window
	//----------leverage input variables to determine what size action window to use, action window title, and what data to show.
	$('#action').append(window);
	
	//Position and show the action window
	
	var windowHeight = ($('.actionTitle').outerHeight()/2)+$('.actionData').outerHeight();
	var windowXCenter = ($('#action').outerWidth()/2)-($('.actionWindow').outerWidth()/2);
	var windowYCenter = ($('#game').outerHeight()-windowHeight)/2;
	
	var titleXCenter = ($('.actionWindow').outerWidth()/2)-($('.actionTitle').outerWidth()/2);
	
	var dataPadding = ($('.actionData').outerWidth()-$('.actionData').width())/2;
	var closeXPosition = ($('.actionWindow').outerWidth()-$('.actionData').outerWidth())/2+(dataPadding);
	
	
	
	$('.actionWindow').css({"left":windowXCenter,"top":windowYCenter,"height":windowHeight});
	$('.actionTitle').css({"left":titleXCenter});
	$('.actionData').css({"top":($('.actionTitle').outerHeight()/2),"left":($('.actionWindow').outerWidth()/2)-($('.actionData').outerWidth()/2)});
	$('.actionClose').css({"top":$('.actionTitle').outerHeight(),"right":closeXPosition});
	$('.actionWindow').css({"visibility":"visible"});
	
	$('.actionClose').click(function() {
		$('#action').empty();
	});
}


$('#charimage').click(function() {
	$('#chartab').toggle();
	$('#charsheet').toggle();
	
	var charData="<ul>";
	charData+="<li>Name: " + localStorage.getItem("char_name") + "</li>";
	charData+="</ul>";
	
	$('#charsheet').html(charData);
		
	
});
