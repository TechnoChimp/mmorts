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
	$query = "SELECT characters.id as char_id, characters.name as char_name, image.filename as char_img, characters.level as char_level, cities.id as city_id, cities.name as city_name FROM characters LEFT JOIN (users, cities) ON (users.id = characters.user_id AND cities.id = characters.city_id) LEFT JOIN image on characters.char_img_id = image.id WHERE users.username = '{$username}'";
	
	// Acquire database connection and perform query
	global $pdo;
	$statement = $pdo->query($query);
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
	
	// Return data in JSON format
	return json_encode($result);
});




//////////////////
// GET /game/character/inventory

// Get current user's characters
$app->get('/game/character/inventory', function(RequestInterface $request, ResponseInterface $response) use($app) {
	// Pull username from auth string and pull characters associated with that username
	$username = $_SERVER['PHP_AUTH_USER'];
	$query = "SELECT c.name as item_name, c.description as item_desc, c.icon as item_icon, b.item_count as item_count, b.slot as inv_slot FROM inventory b LEFT JOIN characters a ON a.id = b.character_id LEFT JOIN item c ON c.id = b.item_id WHERE a.name = '{$username}'";
	
	// Acquire database connection and perform query
	global $pdo;
	$statement = $pdo->query($query);
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
	// Return data in JSON format
	return json_encode($result);
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
// GET /game/city/{id}/members

// Get members of city with specified ID
$app->get('/game/city/{id}/members', function(RequestInterface $request, ResponseInterface $response) use($app) {
	// Get city ID from request
	$id = $request->getAttribute('id');

	$query = "SELECT a.name as char_name FROM characters a LEFT JOIN cities b ON a.city_id = b.id WHERE b.id = '{$id}'";
	
	// Acquire database connection and perform query
	global $pdo;
	$statement = $pdo->query($query);
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
	
	// Return data in JSON format
	return json_encode($result);
});




//////////////////
// User Routes

//////////////////
// GET /game/user

// Gathers user information for game initialization (move to new group after figuring out securing multiple groups)
$app->get('/game/user', function(RequestInterface $request, ResponseInterface $response) use($app) {
	// Get username and build query
	$username = $_SERVER['PHP_AUTH_USER'];
	$query = "SELECT characters.name as char_name, cities.name as city_name FROM characters JOIN users ON users.id = characters.user_id JOIN city_members ON characters.id = city_members.characer_id JOIN cities ON cities.id = city_members.city_id WHERE users.username = '{$username}'";

	// Acquire database connection and perform query
	global $pdo;
	$statement = $pdo->query($query);
	$result = $statement->fetch(PDO::FETCH_ASSOC);
	
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
	$query = "INSERT INTO characters (name, user_id, city_id, char_img_id, char_type_id, level, exp, silver) VALUES ('{$charName}', '{$userId}', '{$cityId}', '{$charImgId}', '0', '1', '0', '0')";
	
	try {
		global $pdo;
		$statement = $pdo->exec($query);
	} catch(exception $e) {
		$newResponse = $response->withStatus(409);
		$body = $newResponse->getBody();
		$body->write(json_encode($e));
		return $newResponse;
	}
});
