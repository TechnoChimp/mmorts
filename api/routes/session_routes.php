<?php
// Enable Middleware PdoAuthenticator
use \Slim\Middleware\HttpBasicAuthentication\PdoAuthenticator;
// Enable Slim's request and response interfaces
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

// Configure authentication middleware
$app->add(new \Slim\Middleware\HttpBasicAuthentication(array(
    "path" => "/game",
    "realm" => "Protected",
    "relaxed" => array("localhost", "192.168.0.24"),
    "environment" => "REDIRECT_HTTP_AUTHORIZATION",
    "authenticator" => new PdoAuthenticator(array(
        "pdo" => $pdo,
        "table" => "users",
        "user" => "username",
        "hash" => "password"
    ))
)));


// Register user
$app->post("/register", function(RequestInterface $request, ResponseInterface $response) {
	// Acquire database connection
	global $pdo;
	
	// Read request body and format into array
	$body = $request->getParsedBody();
	$username = $body['username'];
	$email = $body['username'];
	// Hash the password
	$password = password_hash($body['password'], PASSWORD_DEFAULT);
	
	$status = $pdo->exec(
		"INSERT INTO users (username, password, email, active) VALUES ('{$username}', '{$password}', '{$email}', '1')" 
	);
	
	// Change the HTTP status
	$newResponse = $response->withStatus(201);
	
	// Work on error handling	
	//if ($status->success() == true) {
		
	//} else {
		//Change the HTTP status
	//	$newResponse = $response->withStatus(409);
	//}
	return $newResponse;
});

// Login user (Temporarily making this game/login to secure via middleware.)
$app->post("/game/login", function(RequestInterface $request, ResponseInterface $response) {
	return 'Success!';
});
