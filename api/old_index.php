<?php

use Phalcon\Loader;
use Phalcon\Mvc\Micro;
use Phalcon\Di\FactoryDefault;
use Phalcon\Db\Adapter\Pdo\Mysql as PdoMysql;
use Phalcon\Http\Response;
use Phalcon\Mvc\Application;
use Phalcon\Session\Adapter\Files as Session;

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
	
	$phql = "INSERT INTO users (username, password, email, active) VALUES (:username:, :password:, :email:, '1')";
	
	$status = $app->modelsManager->executeQuery($phql, array (
		'username'	=> $register->username,
		'password'	=> sha1($register->password),
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

//Process user login information
$app->post('/api/login', function() use ($app) {
		
	$login = $app->request->getJsonRawBody();
	
	$user = Users::findFirst(array(
                "(username = :username:) AND password = :password: AND active = '1'",
                'bind' => array('username' => $login->username, 'password' => sha1($login->password))
            ));
	
	//Create a response
	$response = new Response();
	
	if ($user != false) {
		
		//Start the user session
		$di = new FactoryDefault();
		$di->setShared('session',function() {
			$session = new Session();
			$session->start();
			$this->session->set("auth", TRUE);
			$this->session->set("username", $login->username);
			return $session;
		});
		
		//Change the HTTP status and send response
		$response->setStatusCode(201, "Success");
		$response->setJsonContent(
			array(
				'status'	=> 'OK',
				'data'		=> $login,
			)
		);
	} else {
		//Change the HTTP status and send response
		$response->setStatusCode(409, "Error");
		$response->setJsonContent(
			array(
				'status'	=> 'ERROR',
				'data'		=> $login,
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
