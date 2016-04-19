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
// Inventory

// Initialize player inventory
var inv = initObject(invSlot, 60);

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
			error: function(jqXHR, textStatus, errorThrown) {
			},
			timeout: 120000,
		});




//////////////////
// Items

// Initialize items as an empty array
// Items will store all item objects in the game that are not already an inventory object
var items = [];




//////////////////
// Quest System

// Initialize quest journal
var questJournal = [];

jQuery.ajax({
			url: "api/game/quest",
			type: "GET",
			contentType: 'application/json; charset=utf-8',
			headers: {'Authorization': 'Basic ' + localStorage.getItem("userAuth")},
			success: function(resultData) {
				var quests = JSON.parse(resultData);
				
				
				// Loop through each quest in result data
				for (i = 0; i < quests.length; i++) {
					var type = quests[i].type;
					var title = quests[i].title;
					var description = quests[i].description;
					var status = quests[i].status;
					var steps = quests[i].steps;
					var rewards = quests[i].rewards;
					var questItems = []; // Temporary array to hold each items[] indexed item
					var questSteps = []; // Temporary array to hold each quest step

					// Create invSlot objects for each reward and add to items array
					for (j = 0; j < Object.keys(rewards).length; j++) {
						// Get number of items already in items array
						// New item index will be this same number since index begins with 0 and length begins with 1
						var itemIndex = items.length;

						// Combine image and sprite information
						var sprite = rewards[j].image.split('.', 1)+'-'+rewards[j].sprite;

						items[itemIndex] = new invSlot('', rewards[j].quantity, rewards[j].object_name, rewards[j].object_desc, sprite);
						questItems[j] = items[itemIndex];
					}
					
					// Create questStep objects for each quest step and add to questSteps array
					for (j = 0; j < Object.keys(steps).length; j++) {
						questSteps[j] = steps[j];
					}
					
					// Create quest object for each quest and add to questJournal array
					questJournal[i] = new quest(type, title, description, questItems, status, questSteps, i);
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				
			},
			timeout: 120000
});
