//////
// stageCharacterSelect

function stageCharacterSelect() {
	$('#game').empty();
	jQuery.ajax({
			url: "api/game/character",
			type: "GET",
			contentType: 'application/json; charset=utf-8',
			headers: {'Authorization': 'Basic ' + localStorage.getItem("userAuth")},
			success: function(resultData) {
				// Convert data to javascript object
				var charData = JSON.parse(resultData);
				
				// Load empty character select sheet
				$('#game').load('pages/gamesheets/characterselect.html', function () {
					for (i = 0; i < charData.length; i++) {
						// Get variables from result
						var charName = charData[i].char_name;
						var charId = charData[i].char_id;
						var cityId = charData[i].city_id;
						var charImg = charData[i].char_img;
						var charLevel = charData[i].char_level;
						var cityName = charData[i].city_name;
						
						
						// Load character onto sheet for each character in data
						$('.charSelect table tr').eq(0).prepend('<th>'+charName+'</th>');
						// Load character Id and city Id into DOM for later use
						$('.charSelect table tr').eq(1).prepend('<td><div class="character" charId="'+charId+'" cityId="'+cityId+'"><img class="charImage" src="img/'+charImg+'" /><ul class="charSpec"><li>Level: '+charLevel+'<li>City: '+cityName+'</ul></div></td>');
					}
				});
			},
			error : function(jqXHR, textStatus, errorThrown) {
			},
			
			timeout: 120000,
		});	
}