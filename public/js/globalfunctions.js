//////////////////
//
// globalfunctions.js
//
// Contains functions that are used in all areas of the game.
//
//////////////////

//////////////////
// Function: inheritPrototype

// Gives the prototype from a parent object to a child object
function inheritPrototype(childObject, parentObject) {
	// Create a copy of the parent object that contains the same properties and methods​
	var copyOfParent = Object.create(parentObject.prototype);
	
	// Set the constructor of the new object to point to the childObject​
	copyOfParent.constructor = childObject;
	
	// Set the childObject prototype to copyOfParent so that the childObject can inherit everything from copyOfParent (from parentObject)​
	childObject.prototype = copyOfParent;
}




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
