<?php

//////////////////
//
// SETUP
//
//////////////////

// Enable Slim's request and response interfaces
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;




//////////////////
//
// TEST ROUTE
//
//////////////////

$app->get('/game/test', function(RequestInterface $request, ResponseInterface $response) use($app) {
	echo rollD20();
});




//////////////////
//
// GET ROUTES
//
//////////////////

//////////////////
// Character Routes
//

//////////////////
// GET /game/character

// Get current user's characters
$app->get('/game/character', function(RequestInterface $request, ResponseInterface $response) use($app) {
	// Pull username from auth string and pull characters associated with that username
	$username = $_SERVER['PHP_AUTH_USER'];
	$query = "SELECT c.id as char_id, c.name as char_name, i.filename as char_img, c.level as char_level, ci.id as city_id, ci.name as city_name FROM characters c JOIN users u on c.user_id = u.id JOIN image i on c.char_img_id = i.id JOIN city_character cc on cc.char_id = c.id JOIN cities ci on cc.city_id = ci.id WHERE u.username = '{$username}'";
	
	// Acquire database connection and perform query
	global $pdo;
	$statement = $pdo->query($query);
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
	
	// Return data in JSON format
	return json_encode($result);
});




//////////////////
// GET /game/character/{id}/inventory

// Get current user's characters
$app->get('/game/character/{id}/inventory', function(RequestInterface $request, ResponseInterface $response) use($app) {
	// Pull username from auth string and pull characters associated with that username
	$username = $_SERVER['PHP_AUTH_USER'];
	$id = $request->getAttribute('id');
	$query = "SELECT i.name as item_name, i.description as item_desc, i.icon as item_icon, inv.item_count as item_count, inv.slot as inv_slot FROM inventory inv JOIN characters c ON c.id = inv.character_id JOIN item i ON i.id = inv.item_id JOIN users u on c.user_id = u.id WHERE c.id = '{$id}' AND u.username = '{$username}'";
	
	// Acquire database connection and perform query
	global $pdo;
	$statement = $pdo->query($query);
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
	// Return data in JSON format
	return json_encode($result);
});




//////////////////
// GET /game/character/{id}/quest

// Get quest information by quest id
$app->get('/game/character/{id}/quest', function(RequestInterface $request, ResponseInterface $response) use($app) {
	// Get username from request
	$username = $_SERVER['PHP_AUTH_USER'];
	$id = $request->getAttribute('id');
	
	$query = "SELECT q.id as quest_id, q.title as quest_title, qt.type as quest_type, q.description as description, qst.status as status, qs.title as step_title, qs.next_step_id as next_step, gt.type as goal_type, o.name as obj_name, g.quantity as quantity FROM quest q JOIN quest_type qt on q.quest_type_id = qt.id JOIN quest_step qs on q.first_step_id = qs.id JOIN goal g on qs.goal_id = g.id JOIN goal_type gt on g.goal_type_id = gt.id JOIN object o on g.object_id = o.id JOIN character_quest cq on cq.quest_id = q.id JOIN quest_status qst on cq.status_id = qst.id JOIN characters c on cq.char_id = c.id JOIN users u on c.user_id = u.id WHERE c.id = '{$id}' AND u.username = '{$username}'";
	
	// Acquire database connection and perform query
	global $pdo;
	$statement = $pdo->query($query);
	$quests = $statement->fetchAll(PDO::FETCH_ASSOC);
	$questCount = 0;
	
	// Loop through each quest
	foreach ($quests as $quest) {
		// Form return object
		// Add first level array indexes
		$questResult[$questCount]["title"] = $quest["quest_title"];
		$questResult[$questCount]["type"] = $quest["quest_type"];
		$questResult[$questCount]["description"] = $quest["description"];
		$questResult[$questCount]["status"] = $quest["status"];
		$questResult[$questCount]["steps"] = array();
		$questResult[$questCount]["rewards"] = array();
		
		// Enumerate and add each quest step
		// Pull first step information from first query
		$questStep = 0;
		$questResult[$questCount]["steps"][$questStep] = array(	"step_title" => $quest["step_title"],
														"step_goal" => $quest["goal_type"],
														"step_value" => $quest["obj_name"],
														"step_qty" => $quest["quantity"]
												);
		$questStep++;
		
		// Check if there is a next step
		if ($quest["next_step"] != null) {
			// There are more steps - Perform a new query for the next step
			$nextStep = $quest["next_step"];
			
			do {
				// Query for the next step
				$query = "SELECT qs.title as step_title, gt.type as goal_type, o.name as obj_name, g.quantity as quantity, qs.next_step_id as next_step FROM quest_step qs JOIN goal g on qs.goal_id = g.id JOIN goal_type gt on g.goal_type_id = gt.id JOIN object o on g.object_id = o.id WHERE qs.id = '{$nextStep}'";
				
				// Acquire database connection and perform query
				global $pdo;
				$statement = $pdo->query($query);
				$step = $statement->fetch(PDO::FETCH_ASSOC);
				
				// Add the step to the array
				$questResult[$questCount]["steps"][$questStep] = array(	"step_title" => $step["step_title"],
														"step_goal" => $step["goal_type"],
														"step_value" => $step["obj_name"],
														"step_qty" => $step["quantity"]
												);
				
				// Check if there is another step
				$nextStep = $step["next_step"];
				
				$questStep++;
				
			} while ($nextStep != null);
		}
		
		// Enumerate and add each quest reward
		$quest_id = $quest["quest_id"];
		$query = "SELECT qr.id as reward_id, o.name as object_name, o.description as object_desc, ot.type as object_type, of.field as object_field, od.value as field_value, qr.qty as quantity, i.filename as image, s.sprite as sprite FROM quest_reward qr JOIN quest q on qr.quest_id = q.id JOIN quest_type qt on q.quest_type_id = qt.id JOIN object o on qr.object_id = o.id JOIN obj_type ot on o.obj_type_id = ot.id JOIN obj_data od on od.obj_id = o.id JOIN obj_field of on od.field_id = of.id JOIN sprite s on o.sprite_id = s.id JOIN image i on s.image_id = i.id WHERE q.id = '{$quest_id}'";
		
		// Acquire database connection and perform query
		global $pdo;
		$statement = $pdo->query($query);
		$rewards = $statement->fetchAll(PDO::FETCH_ASSOC);
		foreach ($rewards as $reward) {
			$rewardCount = $reward["reward_id"]-1;
			$questResult[$questCount]["rewards"][$rewardCount]["object_name"] = $reward["object_name"];
			$questResult[$questCount]["rewards"][$rewardCount]["object_desc"] = $reward["object_desc"];
			$questResult[$questCount]["rewards"][$rewardCount]["object_type"] = $reward["object_type"];
			$questResult[$questCount]["rewards"][$rewardCount]["quantity"] = $reward["quantity"];
			$questResult[$questCount]["rewards"][$rewardCount]["object_details"][$reward["object_field"]] = $reward["field_value"];
			$questResult[$questCount]["rewards"][$rewardCount]["image"] = $reward["image"];
			$questResult[$questCount]["rewards"][$rewardCount]["sprite"] = $reward["sprite"];
		}
		
		// Done with this quest - move on
		$questCount++;
	}
	
	// Return data in JSON format
	return json_encode($questResult);
});




//////////////////
// GET /game/character/{id}

// Get current character information for specific character
$app->get('/game/character/{id}', function(RequestInterface $request, ResponseInterface $response) use($app) {
	// Get character ID from request and username from auth string
	$id = $request->getAttribute('id');
	$username = $_SERVER['PHP_AUTH_USER'];
	
	// Check if this character belongs to this user
	$query = "SELECT characters.id as char_id, users.username as user_name FROM characters LEFT JOIN users ON users.id = characters.user_id WHERE characters.id = '{$id}'";

	// Acquire database connection and perform query
	global $pdo;
	$statement = $pdo->query($query);
	$result = $statement->fetch(PDO::FETCH_ASSOC);
		
	// If character belongs to this user get all relevant data, otherwise only get publicly available data
	if (strcasecmp($result["user_name"], $username) != 0) {
		// Character does not belong to this user (this needs to be built out once user can look up other users' characters)
		$query = "";
		return 'This is not your character.';
	} else {
		// Character belongs to this user
		$query = "SELECT c.id as char_id, c.name as user_name, c.level as char_level, cities.id as city_id, cities.name as city_name FROM characters c LEFT JOIN users u ON u.id = c.user_id LEFT JOIN cities	ON cities.id = c.city_id WHERE c.id = '{$id}'";
		
		// Perform a new query
		$statement = $pdo->query($query);
		$result = $statement->fetch(PDO::FETCH_ASSOC);
	}
	
	// Return data in JSON format
	return json_encode($result);
});




//////////////////
// City Routes 

//////////////////
// GET /game/city/{id}

// Get city information by city ID
$app->get('/game/city/{id}', function(RequestInterface $request, ResponseInterface $response) use($app) {
	// Get city ID from request
	$id = $request->getAttribute('id');
	$query = "SELECT b.name as city_name, a.filename as bg_img FROM image a LEFT JOIN cities b ON b.bg_image_id = a.id WHERE b.id = '{$id}'; SELECT e.type as tile_type, a.x_coord as pos_x, a.y_coord as pos_y, d.filename as sheet, c.sheet_x_pos as sprite_x, c.sheet_y_pos as sprite_y FROM city_map_tiles a LEFT JOIN cities b ON a.city_id = b.id LEFT JOIN tile c ON a.tile_id = c.id LEFT JOIN image d ON c.image_id = d.id LEFT JOIN tile_type e ON c.tile_type = e.id WHERE b.id = '{$id}'";
	
	// Acquire database connection and perform query
	global $pdo;
	$statement = $pdo->query($query);
	$result = $statement->fetch(PDO::FETCH_ASSOC);
	$statement->nextRowset();
	$result += $statement->fetchAll(PDO::FETCH_ASSOC);

	// Return data in JSON format
	return json_encode($result);
});




//////////////////
// GET /game/city/{id}/cityhall

// Get city hall of city with specified ID
$app->get('/game/city/{id}/cityhall', function(RequestInterface $request, ResponseInterface $response) use($app) {
	// Get city ID from request
	$id = $request->getAttribute('id');

	$query = "SELECT c.name as name, 'NotDefined' as class, cc.join_date as joined, cr.rank as rank FROM city_character cc JOIN characters c on cc.char_id = c.id JOIN cities ci on cc.city_id = ci.id JOIN city_rank cr on cc.rank_id = cr.id WHERE ci.id = '{$id}'; SELECT ci.motd as motd FROM cities ci WHERE ci.id = '{$id}'";
	
	// Acquire database connection and perform query
	global $pdo;
	$statement = $pdo->query($query);
	$members = $statement->fetchAll(PDO::FETCH_ASSOC);
	$statement->nextRowset();
	$motd = $statement->fetch(PDO::FETCH_ASSOC);
	
	// Add each city member to result
	$memberNum = 0;
	foreach ($members as $member) {
		$result["members"][$memberNum] = $member;
		$memberNum++;
	}
	
	// Add MOTD to the result
	$result += $motd;
	
	// Return data in JSON format
	return json_encode($result);
});




//////////////////
// Quest Routes

//////////////////
// GET /game/quest/{id}

// Get quest information by quest id
$app->get('/game/quest/{id}', function(RequestInterface $request, ResponseInterface $response) use($app) {
	// Get quest ID and username from request
	$id = $request->getAttribute('id');
	$username = $_SERVER['PHP_AUTH_USER'];
	
	$query = "SELECT qt.type, q.title, q.description FROM quest q JOIN quest_type qt on q.quest_type_id = qt.id JOIN character_quest cq on cq.quest_id = q.id JOIN characters c on cq.char_id = c.id JOIN users u on c.user_id = u.id WHERE q.id = '{$id}' and u.username = '{$username}'; SELECT qr.id as reward_id, o.name as object_name, ot.type as object_type, of.field as object_field, od.value as field_value, qr.qty as quantity FROM quest_reward qr JOIN quest q on qr.quest_id = q.id JOIN character_quest cq on cq.quest_id = q.id JOIN characters c on cq.char_id = c.id JOIN users u on c.user_id = u.id JOIN quest_type qt on q.quest_type_id = qt.id JOIN object o on qr.object_id = o.id JOIN obj_type ot on o.obj_type_id = ot.id JOIN obj_data od on od.obj_id = o.id JOIN obj_field of on od.field_id = of.id WHERE q.id = '{$id}' and u.username = '{$username}';";
	
	// Acquire database connection and perform query
	global $pdo;
	$statement = $pdo->query($query);
	$result = $statement->fetch(PDO::FETCH_ASSOC);
	$statement->nextRowset();
	$rewards = $statement->fetchAll(PDO::FETCH_ASSOC);
	foreach ($rewards as $reward) {
		$result ["rewards"] [$reward["reward_id"]] ['object_name'] = $reward["object_name"];
		$result ["rewards"] [$reward["reward_id"]] ['object_type'] = $reward["object_type"];
		$result ["rewards"] [$reward["reward_id"]] ['quantity'] = $reward["quantity"];
		$result ["rewards"] [$reward["reward_id"]] ["object_details"] [$reward["object_field"]] = $reward["field_value"];
	}
	
	// Return data in JSON format
	return json_encode($result);
});




//////////////////
//
// POST ROUTES
//
//////////////////

//////////////////
// Character Routes
//

//////////////////
// POST /game/character

// Create a new charater for the current user

// Get data from user and generate a character
$app->post('/game/character', function(RequestInterface $request, ResponseInterface $response) use($app) {
	// Get data from user
	$username = $_SERVER['PHP_AUTH_USER'];
	$body = $request->getParsedBody();
	$charName = $body['charName'];
	$charImgId = $body['charImgId'];
	
	// Get userId and a random city from DB
	$query = "SELECT id as user_id FROM users WHERE username = '{$username}'; SELECT id as city_id FROM cities ORDER BY RAND() LIMIT 1";
	global $pdo;
	$statement = $pdo->query($query);
	$result = $statement->fetch(PDO::FETCH_ASSOC);
	$userId = $result['user_id'];
	$statement->nextRowset();
	$result = $statement->fetch(PDO::FETCH_ASSOC);
	$cityId = $result['city_id'];
	
	// Insert new character into DB
	$query = "INSERT INTO characters (name, user_id, char_img_id, char_type_id, level, exp, silver) VALUES ('{$charName}', '{$userId}', '{$charImgId}', '0', '1', '0', '0')";
	
	try {
		global $pdo;
		$statement = $pdo->prepare($query);
		$statement->execute();
		
		// New character added successfully
		// Get user ID and add user to city
		$charId = $pdo->lastInsertId();
		
		$query = "INSERT INTO city_character (char_id, city_id, rank_id) VALUES ('{$charId}', '{$cityId}', '1'); INSERT INTO inventory (character_id, item_id, item_count, slot) VALUES ('{$charId}', '3', '1', '1'); INSERT INTO character_quest (char_id, quest_id, status_id) VALUES ('{$charId}', '1', '2')";
		
		try {
			global $pdo;
			$statement = $pdo->exec($query);
			
		} catch(exception $e) {
			$newResponse = $response->withStatus(409);
			$body = $newResponse->getBody();
			$body->write(json_encode($e));
			return $newResponse;
		}
		
	} catch(exception $e) {
		$newResponse = $response->withStatus(409);
		$body = $newResponse->getBody();
		$body->write(json_encode($e));
		return $newResponse;
	}
});
