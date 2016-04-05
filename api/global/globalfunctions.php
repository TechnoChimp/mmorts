<?php

//////////////////
//
// Global Functions
//
//////////////////

//////////////////
// Establish database connection

$host	= 'localhost';
$dbname	= 'mmorts';
$user	= 'root';
$pass	= '';
$pdo = new \PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


//////////////////
// rollD20()

function rollD20() {
	return rand(1,20);
}
