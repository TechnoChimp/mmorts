<?php
// Load Slim components
require 'vendor/autoload.php';

// Load global functions
require 'global/globalfunctions.php';

// Create slim app and link routes files
$app = new \Slim\App();
	require 'routes/game_routes.php';
	require 'routes/session_routes.php';
$app->run();