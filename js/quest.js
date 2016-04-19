//////////////////
//
// quest.js
//
// Contains all functions that relate to the quest system, including: quests, quest goals, etc.
//
//////////////////

//////////////////
//
// OBJECTS
//
//////////////////

//////////////////
// Object: questGoal

// Initialize the instance properties
function questGoal(title, goal, value, qty, progress) {
	// Define object variables ('this' being the instance of the object that gets instanciated)
	this.title = title;
	this.goal = goal;
	this.value = value;
	this.qty = qty;
	this.progress = progress;
}

// Overwrite prototype with custom methods
questGoal.prototype = {
	constructor: questGoal,
	
	// displayGoal method will display quest goal onto quest window
	displayGoal: function() {

	},
	
	// setProgress method will set the quest goal progress
	setProgress: function() {
		
	}
};




//////////////////
// Object: quest

// Initialize the instance properties
function quest(type, title, description, rewards, status, steps, journalID) {
	// Define object variables ('this' being the instance of the object that gets instanciated)
	this.type = type;
	this.title = title;
	this.description = description;
	this.rewards = rewards; // Accepts an array of invSlot objects
	this.status = status;
	this.steps = steps; // Accepts an array of questStep objects 
	this.journalID = journalID;
}

// Overwrite prototype with custom methods
quest.prototype = {
	constructor: quest,
	
	// getQuest method will display quest window
	getQuest: function() {
		var type = this.type;
		var title = this.title;
		var description = this.description;
		var rewards = this.rewards;
		
		var questWindow = new actionWindow('', '', '', 'questwindow');
		questWindow.newWindow(function() {
			// After window loads, fill data into window (Maybe update window object to accept title and data input)
			$('#questwindow .title').html(type + ' Quest');
			$('#questwindow .sectionTitle').html(title);
			$('#questwindow .sectionTitle').after('<p>'+description+'</p>');
			
			// Loop through rewards array and display each reward
			var rewardLength = rewards.length;
			for (var i = 0; i < rewardLength; i++) {
				var sheet = rewards[i].img;
				
				$('#questwindow .rewards').append('<div class="item">'+rewards[i].qty+'</div>');
				$('#questwindow .item').last().addClass(rewards[i].img.split('-', 1)+' '+rewards[i].img);
			}
			
		});
		
	},
	
	// setProgress method will set the quest goal progress
	setProgress: function() {
		
	},
	
	// claimReward method will claim the quest reward
	claimReward: function() {
		
	},
	
	// displayQuest method will display the quest in the quest journal
	displayQuest: function() {
		var type = this.type;
		var title = this.title;
		var journalID = this.journalID;
		
		$('#journalWindow p[quest="'+type+'"]').append('<div class="questButton" journalID="'+journalID+'">'+title+'</div>');
	}
};




//////////////////
//
// CLICK EVENTS
//
//////////////////

//////////////////
// Quest Button

$('#game').on('click', '.questButton', function() {
	var quest = $(this).attr('journalID');
	questJournal[quest].getQuest();
});