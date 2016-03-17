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
function building(type, spritesheet, spriteXCoord, spriteYCoord, posX, posY) {
	// Define object variables ('this' being the instance of the object that gets instanciated)
	this.type = type;
	this.spritesheet = spritesheet;
	this.spriteXCoord = spriteXCoord;
	this.spriteYCoord = spriteYCoord;
	this.posX = posX;
	this.posY = posY;
	
	// Check if spritesheet has been defined
	// String together spritesheet and coordinates to get classes
	if (typeof spritesheet != 'undefined') {
		var class1 = spritesheet.split('.',1);
		var class2 = class1 + '-' + spriteXCoord + '-' + spriteYCoord;
	}
	
}

// Overwrite prototype with custom methods
building.prototype = {
	constructor: building,
	
	// getBuilding will bring up building interaction window
	getBuilding: function(_callback) {
		
	},
	
	// setBuilding will set the building variables
	setBuilding: function(name, spritesheet, spriteXCoord, spriteYCoord) {
		this.name = name;
		this.spritesheet = spritesheet;
		this.spriteXCoord = spriteXCoord;
		this.spriteYCoord = spriteYCoord;
		
		// Check if spritesheet has been defined
		// String together spritesheet and coordinates to get classes
		if (typeof spritesheet != 'undefined') {
			var class1 = spritesheet.split('.',1);
			var class2 = class1 + '-' + spriteXCoord + '-' + spriteYCoord;
		}
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
	displayCity: function() {
		// Pass object variables to local variables
		var title = this.title;
		var bgImg = this.bgImg;
		
		// Set the stage title
		$('#stageTitle').html(title);
		
		// Set the background image
		$('table#city').css({'background-image':'url("../img/'+bgImg+'")'});
		
		$('#stage').load('pages/gamesheets/citymap.html', function() {
			
		});
	},
	
	// setCity method will set the variables in the city object
	setCity: function() {
		
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
$('#game').on('click', '#capitol', function() {
	var buildingWindow = new actionWindow('medium', 'City Hall');
	buildingWindow.displayWindow();
});
