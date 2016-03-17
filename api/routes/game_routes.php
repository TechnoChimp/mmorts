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
	$query = "SELECT characters.id as char_id, characters.name as char_name, characters.level as char_level, cities.id as city_id, cities.name as city_name FROM characters LEFT JOIN (users, cities) ON (users.id = characters.user_id AND cities.id = characters.city_id) WHERE users.username = '{$username}'";
	
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
	// NEW Query:
	// $query = "SELECT  e.type as tile_type, a.x_coord as pos_X, a.y_coord as pos_Y, d.filename as sheet, c.sheet_x_pos as sprite_x, c.sheet_y_pos as sprite_y FROM city_map_tiles a LEFT JOIN cities b ON a.city_id = b.id LEFT JOIN tile c ON a.tile_id = c.id LEFT JOIN image d ON c.image_id = d.id LEFT JOIN tile_type e ON c.tile_type = e.id WHERE b.id = '{$id}'"
	$query = "SELECT c.id as city_id, c.name as city_name FROM cities c WHERE c.id = '{$id}'";
	
	// Acquire database connection and perform query
	global $pdo;
	$statement = $pdo->query($query);
	$result = $statement->fetch(PDO::FETCH_ASSOC);
	
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

	$username = $_SERVER['PHP_AUTH_USER'];
	
	$query = "SELECT characters.name as char_name, cities.name as city_name FROM characters JOIN users ON users.id = characters.user_id JOIN city_members ON characters.id = city_members.characer_id JOIN cities ON cities.id = city_members.city_id WHERE users.username = '{$username}'";

	// Acquire database connection and perform query
	global $pdo;
	
	$statement = $pdo->query($query);
	$result = $statement->fetch(PDO::FETCH_ASSOC);
	return json_encode($result);
});

