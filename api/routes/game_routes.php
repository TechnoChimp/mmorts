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

	// Acquire database connection
	global $pdo;
	
	$statement = $pdo->query($query);
	$result = $statement->fetch(PDO::FETCH_ASSOC);
	return json_encode($result);
});
