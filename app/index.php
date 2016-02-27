<?php

use Phalcon\Loader;
use Phalcon\Mvc\Micro;
use Phalcon\Di\FactoryDefault;
use Phalcon\Db\Adapter\Pdo\Mysql as PdoMysql;
use Phalcon\Http\Response;
use Phalcon\Mvc\Application;

//Use Loader() to autoload our model
$loader = new Loader();

$loader->registerDirs(
	array(
		__DIR__ . '/models/'
	)
)->register();

$di = new FactoryDefault();

//Set up the database service
$di->set('db', function() {
	return new PdoMysql(
		array(
			"host"		=> "localhost",
			"username"	=> "root",
			"password"	=> "",
			"dbname"	=> "mmorts"
		)
	);
});

//Create and bind the DI to the application
$app = new Micro($di);

//Define routes here

//Retrieves character stats
$app->get('/api/character', function() use ($app) {
	$phql = "SELECT * FROM character";
	$character = $app->modelsManager->executeQuery($phql)->getFirst();

	//Create a response
	$response = new Response();
	
	$response->setJsonContent(
		array(
			'character_id'	=> $character->character_id,
			'name'	=> $character->name,
			'stat1'	=> $character->stat1,
			'stat2'	=> $character->stat2,
			'stat3'	=> $character->stat3,
			'stat4'	=> $character->stat4,
			'stat5'	=> $character->stat5,
			'stat6'	=> $character->stat6,
		)
	);
	
	return $response;
});

//Submits registration form
$app->post('/api/register', function() use ($app) {

	$register = $app->request->getJsonRawBody();
	
	$phql = "INSERT INTO users (username, password, email) VALUES (:username:, :password:, :email:)";
	
	$status = $app->modelsManager->executeQuery($phql, array (
		'username'	=> $register->username,
		'password'	=> $register->password,
		'email'		=> $register->email,
	));
	
	//Create a response
	$response = new Response();
	
	//Check if the insertion was successful
	if ($status->success() == true) {
		
		//Change the HTTP status
		$response->setStatusCode(201, "Created");
		
		$register->user_id = $status->getModel()->user_id;
		
		$response->setJsonContent(
			array(
				'status'	=> 'OK',
				'data'		=> $register,
			)
		);
	} else {
		
		//Change the HTTP status
		$response->setStatusCode(409, "Conflict");
		
		//Send errors to the client
		$errors = array();
		foreach ($status->getMessages() as $message) {
			$errors[] = $message->getMessage();
		}
		
		$response->setJsonContent(
			array(
				'status'	=> 'ERROR',
				'messages'	=> $errors,
			)
		);
	}
	
	return $response;
});

//Default 404 page
$app->notFound(function () use ($app) {
    $app->response->setStatusCode(404, "Not Found")->sendHeaders();
    echo 'This is crazy, but this page was not found!';
});

$app->handle();
