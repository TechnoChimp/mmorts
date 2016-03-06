<?php

// Enable Slim's request and response interfaces
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

// Test Function
$app->get('/game', function(RequestInterface $request, ResponseInterface $response) use($app) {
    return 'Success!';
});

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

// Get current user's characters
$app->get('/game/character', function(RequestInterface $request, ResponseInterface $response) use($app) {
	// Pull username from auth string and pull characters associated with that username
	$username = $_SERVER['PHP_AUTH_USER'];
	$query = "SELECT characters.id as char_id, characters.name as char_name, characters.level as char_level, cities.name as city_name FROM characters LEFT JOIN (users, cities) ON (users.id = characters.user_id AND cities.id = characters.city_id) WHERE users.username = '{$username}'";
	
	// Acquire database connection and perform query
	global $pdo;
	$statement = $pdo->query($query);
	$result = $statement->fetch(PDO::FETCH_ASSOC);
	
	// Return data in JSON format
	return json_encode($result);
});

// Get current character information for specific character
$app->get('/game/character/{id}', function(RequestInterface $request, ResponseInterface $response) use($app) {
	
});
