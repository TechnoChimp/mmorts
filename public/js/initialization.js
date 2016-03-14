//////
//
// initialization.js
//
// Contains code that is used to initilize game on initial load.
//
//////

// Initialize player inventory
var inv = [];
for (var i = 0; i < 9; i++) {
	inv[i] = new invSlot(i);
}
