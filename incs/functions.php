<?php
$message = "";
// Creates a file with the specified fields.
function add_registered_user ($name, $by, $status, $rating) {
	file_put_contents('data.txt', "$name | $by | $status | $rating | \n", FILE_APPEND);
}

// It allows to insert only particular characters into the input fields
function check_input($data) {
	$data = preg_replace("#[^0-9a-z\-\+\.?\!]#i"," ", $data);
    $data = trim($data);
    $data = stripslashes($data);
    $data = filter_var($data, FILTER_SANITIZE_STRING);
    $data = htmlspecialchars($data);
    return $data;
}

// EMAIL FILTER
function valid_email($email) {
	return filter_var($email, FILTER_VALIDATE_EMAIL); #returns true or false
}

// Confirm query
function confirm_query($result) {
// Test if there was a query error
	if (!$result) {
		die("Database query failed.");
	}
}

// Preparing data before insert it into mysql
function mysql_prep($string) {
		global $mysqli;		
		$escaped_string = mysqli_real_escape_string($mysqli, $string);
		return $escaped_string;
}

// Redirect
function redirect_to($new_location) {
	header("Location: ", $new_location);
	exit;
}


?>