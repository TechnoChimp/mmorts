//////
// getCharacterData

function getCharacterData(charId) {
	jQuery.ajax({
			url: "../api/game/character/"+charId,
			type: "GET",
			contentType: 'application/json; charset=utf-8',
			headers: {'Authorization': 'Basic ' + localStorage.getItem("userAuth")},
			success: function(resultData) {
				
			},
			error : function(jqXHR, textStatus, errorThrown) {
			},
			
			timeout: 120000,
		});

}

//////
// getUserInfo

function getUserInfo() {
	var result;
	jQuery.ajax({
			url: "../api/game/user",
			type: "GET",
			contentType: 'application/json; charset=utf-8',
			headers: {'Authorization': 'Basic ' + localStorage.getItem("userAuth")},
			success: function(resultData) {
				
			},
			error : function(jqXHR, textStatus, errorThrown) {
			},
			
			timeout: 120000,
		});
}

//////
// stageCharacterSelect

function stageCharacterSelect() {
	$('#game').empty();
	jQuery.ajax({
			url: "../api/game/character",
			type: "GET",
			contentType: 'application/json; charset=utf-8',
			headers: {'Authorization': 'Basic ' + localStorage.getItem("userAuth")},
			success: function(resultData) {
				// Convert data to javascript object
				var charData = JSON.parse(resultData);
				
				// Load empty character select sheet
				$('#game').load('pages/gamesheets/characterselect.html', function () {
					for (i = 0; i < charData.length; i++) {
						// Load character onto sheet for each character in data
						$('.charSelect table tr').eq(0).append('<th>'+charData[i].char_name+'</th>');
						// Load character Id and city Id into DOM for later use
						$('.charSelect table tr').eq(1).append('<td><div class="character" charId="'+charData[i].char_id+'" cityId="'+charData[i].city_id+'"><img class="charImage" src="img/char1.jpg" /><ul class="charSpec"><li>Level: '+charData[i].char_level+'<li>City: '+charData[i].city_name+'</ul></div></td>');
					}
				});
			},
			error : function(jqXHR, textStatus, errorThrown) {
			},
			
			timeout: 120000,
		});
	
	
}

//////
// stageCity

function stageCity(cityId) {
	//Set the stage
	$('#stage').empty();
	$('#stage').css({"background-image":"(../img/citybg.png) repeat"});
	$('#stage').append('<table class="city"><tbody></tbody></table>');
	
	//Map specs
	var mapRow = 6;
	var mapCol = 10;
	
	//Draw the map
	var tileX = 0;
	var tileY = 0;
	
	while (tileX < mapRow) {
		var currentRow = "row-"+tileX;
		
		$('.city tbody').append('<tr class="'+currentRow+'">');
		
		while (tileY < mapCol) {
			var currentCol = "col-"+tileY;
			$('.'+currentRow).append('<td class="'+currentCol+'"><div class="tile"></div></td>');
			tileY++;
		}
		tileY=0;
		tileX++;
	}
	
	// This is temporary to place an building - will be replaced with map data
	$('.row-3 .col-3').replaceWith('<td class="col-3"><div class="tile" id="capitol"></div></td>');
	
	//Show the map
	$('.city').css({"visibility":"visible"});
	
	jQuery.ajax({
			url: "../api/game/city/"+cityId,
			type: "GET",
			contentType: 'application/json; charset=utf-8',
			headers: {'Authorization': 'Basic ' + localStorage.getItem("userAuth")},
			success: function(resultData) {
				// Convert data to javascript object
				var cityData = JSON.parse(resultData);

				// Set the stage title
				$('#stageTitle').empty();
				$('#stageTitle').append(cityData.city_name);
				
				
				
			},
			error : function(jqXHR, textStatus, errorThrown) {
			},
			
			timeout: 120000,
		});
	
}


