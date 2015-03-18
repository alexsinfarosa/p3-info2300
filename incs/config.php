<?php 

// Defining constants
define('DB_HOST', 'localhost');
define('DB_USER', 'as898sp15');
define('DB_PASSWORD', 'alessandro');
define('DB_NAME', 'info230_SP15_as898sp15');

// Create a database connection
$mysqli = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);

// Test if connection was succesfull
if ( mysqli_connect_errno() ) {
	die("Database connection failed: " . 
		mysqli_connect_error() . // return empty string if no problems
		"(" . mysqli_connect_errno() . ")" // error number
	);
}

?>