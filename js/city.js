//////////////////
//
// city.js
//
// Contains all functions that relate to the city, including: city map, city status, building status, etc.
//
//////////////////

//////////////////
//
// OBJECTS
//
//////////////////

//////////////////
// Object: building

// initialize the instance properties
function building(name, spritesheet, spriteXCoord, spriteYCoord, posX, posY) {
	// Define object variables ('this' being the instance of the object that gets instanciated)
	this.name = name;
	this.spritesheet = spritesheet;
	this.spriteXCoord = spriteXCoord;
	this.spriteYCoord = spriteYCoord;
	this.posX = posX;
	this.posY = posY;
}

// Overwrite prototype with custom methods
building.prototype = {
	constructor: building,
	
	// getBuilding will bring up building interaction window
	getBuilding: function() {

		// Determine if building variables have been set. By default all buildings get created
		//   with a name equal to its array key number, so if a building name >= 0 then it has
		//   not had its veriables defined. Otherwise a text string would fail the test.
		if (!(this.name >= 0)) {
			// String together spritesheet and coordinates to get classes
			var class1 = this.spritesheet.split('.',1);
			var class2 = class1 + '-' + this.spriteXCoord + '-' + this.spriteYCoord;
			var location = '.row-' + this.posX + ' .col-' + this.posY;
			var col = 'col-' + this.posY;

			// Draw the building to the map
			$(location).replaceWith('<td class="' + col + '"><div class="tile ' +class1 + ' ' + class2 + '" id="' + this.name + '"></div></td>');
			
		}
	},
	
	// setBuilding will set the building variables
	setBuilding: function(name, spritesheet, spriteXCoord, spriteYCoord, posX, posY) {
		this.name = name;
		this.spritesheet = spritesheet;
		this.spriteXCoord = spriteXCoord;
		this.spriteYCoord = spriteYCoord;
		this.posX = posX;
		this.posY = posY;
	}
};




//////////////////
// Object: city

// Initialize the instance properties
function city(title, bgImg) {
	// Define object variables ('this' being the instance of the object that gets instanciated)
	this.title = title;
	this.bgImg = bgImg;
}

// Overwrite prototype with custom methods
city.prototype = {
	constructor: city,
	
	// displayCity method will load the citymap onto the screen
	getCity: function(_callback) {
		// Pass object variables to local variables
		var title = this.title;
		var bgImg = this.bgImg;

		// Load the city structure
		$('#stage').load('pages/gamesheets/citymap.html', function() {
			// Set the stage title
			$('#stageTitle .titleContent').html(title);
			
			//Show the map
			$('#city').css({"visibility":"visible"});
			
			// Set the background image
			$('table#city').css({'background-image':'url("img/'+bgImg+'")'});
			
			// Run callback if required
			if (_callback) {
				_callback();
			}
		});
	},
	
	// setCity method will set the variables in the city object
	setCity: function(title, bgImg) {
		this.title = title;
		this.bgImg = bgImg;
	}
};




//////////////////
//
// CLICK EVENTS
//
//////////////////

//////////////////
// City Interaction

// Click capitol building (will be expanded to consume the click of any building, using just the capitol for testing)
$('#game').on('click', '.tile', function() {
	var buildingWindow = new actionWindow('large', this.id);
	buildingWindow.displayWindow();
});
