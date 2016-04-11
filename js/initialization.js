//////////////////
//
// initialization.js
//
// Contains code that is used to initilize game on initial load.
//
//////////////////

//////////////////
//
// FUNCTIONS
//
//////////////////

//////////////////
// Function: initObject

// Creates new array of objects from the object provided
function initObject(obj, num) {
	var a = [];
	for (var i = 0; i < num; i++) {
		a[i] = new obj(i);
	}
	return a;
}




//////////////////
//
// INITIALIZATION
//
//////////////////

//////////////////
// Buildings

// Initialize buildings
var building = initObject(building, 6);




//////////////////
// City

// Initialize city
var city = initObject(city, 1);




//////////////////
// Quest System

// Initialize quest and quest goal as an empty array
// Quests and goals will be added as needed
var questList = [];
var questGoalList = [];




//////////////////
// Inventory

// Initialize player inventory
var inv = initObject(invSlot, 9);

// Get inventory data from API and load into objects
jQuery.ajax({
			url: "api/game/character/inventory",
			type: "GET",
			contentType: 'application/json; charset=utf-8',
			headers: {'Authorization': 'Basic ' + localStorage.getItem("userAuth")},
			success: function(resultData) {
				var inventory = JSON.parse(resultData);
				for (var i = 0; i < inventory.length; i++) {
					var slot = inventory[i].inv_slot;
					var qty = inventory[i].item_count;
					var name = inventory[i].item_name;
					var desc = inventory[i].item_desc;
					var img = inventory[i].item_icon;
					
					// Set the data for the current slot
					inv[slot].setSlot(qty, name, desc, img);
				}
				
				
			},
			error : function(jqXHR, textStatus, errorThrown) {
			},
			
			timeout: 120000,
		});









