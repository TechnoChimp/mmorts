<?php
// Load Slim components
require 'vendor/autoload.php';

// Establish database connection
$host	= 'localhost';
$dbname	= 'mmorts';
$user	= 'root';
$pass	= '';
$pdo = new \PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Create slim app and link routes files
$app = new \Slim\App();
	require 'routes/game_routes.php';
	require 'routes/session_routes.php';
$app->run();