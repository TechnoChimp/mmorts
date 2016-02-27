function stageCity() {
	//Set the stage
	$('#stage').empty();
	$('#stage').append('<table class="city"><tbody></tbody></table>');
	
	//Map specs
	var mapHeight = 16;
	var mapWidth = 16;
	
	//Draw the map
	var tileX = 0;
	var tileY = 0;
	
	while (tileX < mapWidth) {
		var currentRow = "row-"+tileX;
		
		$('.city tbody').append('<tr class="'+currentRow+'">');
		
		while (tileY < mapHeight) {
			var currentCol = "col-"+tileY;
			$('.'+currentRow).append('<td class="'+currentCol+'"><div class="tile"><div class="tilexx"></div></div></td>');
			tileY++;
		}
		tileY=0;
		tileX++;
	}
	
	$('.row-5 .col-5').replaceWith('<td class="col-5"><div class="tile"><div class="tile00"></div></div></td>')
}
