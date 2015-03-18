<?php

// Creates a file with the specified fields.
function add_registered_user ($name, $by, $status, $rating) {
	file_put_contents('data.txt', "$name | $by | $status | $rating | \n", FILE_APPEND);
}

// It allows to insert only particular characters into the input fields
function check_input($data) {
	$data = preg_replace("#[^0-9a-z\-\+\.]#i"," ", $data);
    $data = trim($data);
    $data = stripslashes($data);
    $data = filter_var($data, FILTER_SANITIZE_STRING);
    $data = htmlspecialchars($data);
    return $data;
}

// Retreive old value
function old($key) {
	if ( !empty($_POST[$key]) ) {
		return htmlspecialchars( $_POST[$key] );
	}
	return '';
}

// EMAIL FILTER
function valid_email($email) {
	return filter_var($email, FILTER_VALIDATE_EMAIL); #returns true or false
}

// Displaying ERRORS
function form_errors( $errors=array() ) {
	$output = "";
	if ( !empty($errors) ) {
		$output .= "<h3>Please fix the following errors:</h3>";
		$output .= "<ul>";
		foreach ($errors as $key => $error) {
			$output .= "<li>$error</li>";
		}
		$output .= "<ul>";
	}
	return $output;
}

// Confirm query
function confirm_query($result) {
// Test if there was a query error
	if (!$result) {
		die("Database query failed.");
	}
}

// Redirect
function redirect_to($new_location) {
	header("Location: ", $new_location);
	exit;
}


?>