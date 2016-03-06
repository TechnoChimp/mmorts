// 
function getUserInfo(callback) {
	var result;
	jQuery.ajax({
			url: "../api/game/user",
			type: "GET",
			contentType: 'application/json; charset=utf-8',
			headers: {'Authorization': 'Basic ' + localStorage.getItem("userAuth")},
			success: function(resultData) {
				result = resultData;
				callback(result);
			},
			error : function(jqXHR, textStatus, errorThrown) {
			},
			
			timeout: 120000,
		});
}

// Draw city
function stageCity() {
	
	//Set the stage
	$('#stage').empty();
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
	
	$('.row-3 .col-3').replaceWith('<td class="col-3"><div class="tile" id="capitol"></div></td>');
	
	//Show the map
	$('.city').css({"visibility":"visible"});
	
}
