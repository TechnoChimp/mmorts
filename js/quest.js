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
function quest(type, title, description, reward, progress, goal) {
	// Define object variables ('this' being the instance of the object that gets instanciated)
	this.type = type;
	this.title = title;
	this.description = description;
	this.reward = reward; // Accepts an array of item objects
	this.progress = progress;
	this.goal = goal; // Accepts an array of questGoal objects
}

// Overwrite prototype with custom methods
quest.prototype = {
	constructor: quest,
	
	// displayQuest method will display quest window
	displayQuest: function() {
		var type = this.type;
		var title = this.title;
		var description = this.description;
		var reward = this.reward;
		
		var questWindow = new actionWindow('', '', '', 'questwindow');
		questWindow.newWindow(function() {
			// After window loads, fill data into window (Maybe update window object to accept title and data input)
			$('#questWindow .title').html(type + ' Quest');
			$('#questWindow .quest').html(title);
			$('#questWindow .quest').after('<p>'+description+'</p>');
			
			// Loop through rewards array and display each reward
			var rewardLength = reward.length;
			for (var i = 0; i < rewardLength; i++) {
				var sheet = reward[i].img.split('-', 1);
				
				$('#questWindow .rewards').append('<div class="item">'+reward[i].qty+'</div>');
				$('#questWindow .item').last().addClass(sheet+' '+reward[i].img);
			}
			
		});
		
	},
	
	// setProgress method will set the quest goal progress
	setProgress: function() {
		
	},
	
	// claimReward method will claim the quest reward
	claimReward: function() {
		
	}
};




//////////////////
//
// TESTING
//
//////////////////

$('#game').on('click', '#stageTitle', function() {
	
	var reward = [inv[0], inv[1], inv[2]];
	var questList = [];
	questList[0] = new quest('Player', 'Introduction', 'This is the quest description.', reward, 1, 'Goal Here');
	questList[0].displayQuest();
});
