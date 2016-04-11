//////////////////
//
// ui.js
//
// Contains all functions that relate to the UI, including: windows, status bars, notifications, etc.
//
//////////////////

//////////////////
//
// OBJECTS
//
//////////////////

//////////////////
// Object: actionWindow

// Initialize the instance properties
function actionWindow(size, title, content, page) {
	// Define object variables ('this' being the instance of the object that gets instanciated)
	this.size = size; // Acceptable sizes include: small, medium, large
	this.title = title;
	this.content = content; //------Write up standard around acceptable window content types.
	this.page = page;
}

// Overwrite prototype with custom methods
actionWindow.prototype = {
	constructor: actionWindow,
	
	// displayWindow method will display the window on screen
	displayWindow: function(_callback) {
		// Pass object variables to local variables
		var title = this.title;
		var contentClass = this.size + 'Window'; // smallWindow, mediumWindow, largeWindow classes
		var content = this.content;
		
		$('#window_layer').load('pages/gamesheets/window.html', function() {
			$('#windowTitle').append(title);
			$('#windowContent').addClass(contentClass);
			$('#windowContent').append(content);
			
			//Position and show the action window
			var windowHeight = ($('#windowTitle').outerHeight()/2)+$('#windowContent').outerHeight();
			var windowXCenter = ($('#window_layer').outerWidth()/2)-($('#windowWrapper').outerWidth()/2);
			var windowYCenter = ($('#game').outerHeight()/2)-(windowHeight/2)+($('#stageTitle').outerHeight()/2);
			var contentXCenter = ($('#windowWrapper').outerWidth()/2)-($('#windowContent').outerWidth()/2);
			var contentYCenter = ($('#windowTitle').outerHeight()/2);
			var titleXCenter = ($('#windowWrapper').outerWidth()/2)-($('#windowTitle').outerWidth()/2);
			var dataPadding = ($('#windowContent').outerWidth()-$('#windowContent').width())/2;
			var closeXPosition = ($('#windowWrapper').outerWidth()-$('#windowContent').outerWidth())/2+(dataPadding);
			var closeYPosition = $('#windowTitle').outerHeight();
				
			$('#windowWrapper').css({"left":windowXCenter,"top":windowYCenter,"height":windowHeight});
			$('#windowTitle').css({"left":titleXCenter});
			$('#windowContent').css({"left":contentXCenter,"top":contentYCenter});
			$('#windowClose').css({"right":closeXPosition,"top":closeYPosition});
			$('#windowWrapper').css({"visibility":"visible"});
			
			// Run callback if required
			if (_callback) {
				_callback();
			}
		});
	},
	
	// newWindow method will display a new window on screen
	newWindow: function(_callback) {
		var page = this.page;
		
		$('#window_layer').load('pages/gamesheets/'+page+'.html', function() {
		$('.scrollbox').enscroll({
				verticalTrackClass: 'track',
				verticalHandleClass: 'handle',
				drawScrollButtons: true,
				scrollUpButtonClass: 'scroll-up',
				scrollDownButtonClass: 'scroll-down'
		});
		
		// Run callback if required
			if (_callback) {
				_callback();
			}
	});
	}
};




//////////////////
//
// CLICK EVENTS
//
//////////////////

//////////////////
// Click Event: Close Action Window

$('#game').on('click', '#windowClose', function() {
	$('#window_layer').empty();
});




//////////////////
// Click Event: Inventory

// Create new inventory action window
$('#game').on('click', '#inventory', function() {
	invWindow = new actionWindow('', '', '', 'inventory');
	invWindow.newWindow(function() {
		for (var i = 0; i < 9; i++) {
			inv[i].getSlot();
		}
	});
});




//////////////////
// Click Event: Character Sheet

$('#charimage').click(function() {
	$('#chartab').toggle();
	$('#charsheet').toggle();
	
	var charData="<ul>";
	charData+="<li>Name: " + localStorage.getItem("char_name") + "</li>";
	charData+="</ul>";
	
	$('#charsheet').html(charData);
});




//////////////////
// Click Event: Quest Journal

// Create new quest journal window
$('#game').on('click', '#journal', function() {
	journalWindow = new actionWindow('', '', '', 'questwindow');
	journalWindow.newWindow();
	$('#questWindow').on('click', '.button', function() {
		console.log('Accept button clicked.');
	});
});
