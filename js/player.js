//////////////////
//
// player.js
//
// Contains all functions that relate to the player, including: player object, inventory, items, etc.
//
//////////////////

//////////////////
//
// OBJECTS
//
//////////////////

//////////////////
// Object: item

// Initialize the instance properties
function item(name, desc, img) {
	// Define object variables ('this' being the instance of the object that gets instanciated)
	this.name = name;
	this.desc = desc;
	this.img = img;
}

// Overwrite prototype with custom methods
item.prototype = {
	constructor: item,
	
	// getItem method will return all details about item into a table(itemDetail)
	getItem: function() {
		console.log('Name: ' + this.name + '\nDesc: ' + this.desc + '\nImg: ' + this.img);
	},
	
	// setItem method will set the variables in the item object
	setItem: function(name, desc, img) {
		this.name = name;
		this.desc = desc;
		this.img = img;
	},
	
	// delItem will empty the variables in the item object
	delItem: function() {
		this.name = "";
		this.desc = "";
		this.img = "";
	}
};




//////////////////
// Object: invSlot

// Initialize the instance properties
function invSlot(slot, qty, name, desc, img) {
	// Define object variables ('this' being the instance of the object that gets instanciated)
	this.slot = slot;
	this.qty = qty;
	
	// Call the item constructor
	item.call(this, name, desc, img);
}

// Inherit the item prototype
inheritPrototype(invSlot, item);

//////
// Add additional methods to invSlot

// getSlot will write the item information to the inventory slot(invTable) defined in slot
invSlot.prototype.getSlot = function() {
	var slot = '#invSlot' + this.slot;
	var img = this.img;
	if (typeof img != 'undefined') {
		sheet = img.split("-",1);
		$(slot).addClass(sheet + ' ' + img);
	}
	//----------Add in logic here to include an item count if > 0. 0 indicates the item does not stack.
};

// setSlot will update the properties of the invSlot and attached item object
invSlot.prototype.setSlot = function(qty, name, desc, img) {
	this.qty = qty;
	item.call(this, name, desc, img);
};




//////////////////
//
// CLICK EVENTS
//
//////////////////

//////////////////
// Character Creation

// Click capitol building (will be expanded to consume the click of any building, using just the capitol for testing)
$('#game').on('click', '#createCharacter #submitCharacter', function() {
	// Get character data from user input
	var charName = $('#charName').val();
	var charImgId = $('#charImage').attr('charImg');
	var charData = {
		charName:charName,
		charImgId:charImgId
	};
	jsonData = JSON.stringify(charData);
	
	// Perform character creation post request
	jQuery.ajax({
		url: "api/game/character",
		type: "POST",
		contentType: 'application/json; charset=utf-8',
		data: jsonData,
		datatype : 'application/json',
		headers: {'Authorization': 'Basic ' + localStorage.getItem("userAuth")},
		success: function(resultData) {	
			// Reload the character select screen
			stageCharacterSelect();
		},
		error : function(jqXHR, textStatus, errorThrown) {
			if (jqXHR.status == '409') {
				// Write error message to screen (character name already in use)
			}
		},
		
		timeout: 120000,
	});
});