//////
// newActionWindow()

function newActionWindow(cityId) {
	//Create the new action window
	//----------leverage input variables to determine what size action window to use, action window title, and what data to show.
	//----------Replace this with a generic page. Load appropriate data onto page based on what kind of window is being opened.
	$('#action').load('pages/gamesheets/wintest.html', function() {
		
		
		// Town Hall Data
		//---------Put some logic in before this section to load correct information based on which window was loaded.
		jQuery.ajax({
			url: "../api/game/city/"+cityId+"/members",
			type: "GET",
			contentType: 'application/json; charset=utf-8',
			headers: {'Authorization': 'Basic ' + localStorage.getItem("userAuth")},
			success: function(resultData) {
				var members = JSON.parse(resultData);
				var formattedData = "";
				for (var i = 0; i < members.length; i++) {
					formattedData += '<tr><td>'+members[i].char_name+'</td></tr>';
				}
				$('#wdCityMembers').append(formattedData);
				
				//Position and show the action window
				var windowHeight = ($('.actionTitle').outerHeight()/2)+$('.actionData').outerHeight();
				var windowXCenter = ($('#action').outerWidth()/2)-($('.actionWindow').outerWidth()/2);
				var windowYCenter = ($('#game').outerHeight()/2)-(windowHeight/2);
				var titleXCenter = ($('.actionWindow').outerWidth()/2)-($('.actionTitle').outerWidth()/2);
				var dataPadding = ($('.actionData').outerWidth()-$('.actionData').width())/2;
				var closeXPosition = ($('.actionWindow').outerWidth()-$('.actionData').outerWidth())/2+(dataPadding);
				
				$('.actionWindow').css({"left":windowXCenter,"top":windowYCenter,"height":windowHeight});
				$('.actionTitle').css({"left":titleXCenter});
				$('.actionData').css({"top":($('.actionTitle').outerHeight()/2),"left":($('.actionWindow').outerWidth()/2)-($('.actionData').outerWidth()/2)});
				$('.actionClose').css({"top":$('.actionTitle').outerHeight(),"right":closeXPosition});
				$('.actionWindow').css({"visibility":"visible"});
			},
			error : function(jqXHR, textStatus, errorThrown) {
			},
			
			timeout: 120000,
		});
		
		$('.actionClose').click(function() {
			$('#action').empty();
		});
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

//////
// toggleCharSheet()

function toggleCharSheet() {
	$('#game').on('click', '#character', function() {
		
	});
}
