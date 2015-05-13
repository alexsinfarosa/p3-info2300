<?php require_once '../../incs/session.php'; ?> 
<?php require_once '../../incs/config.php'; ?>
<?php require_once '../../incs/functions.php'; ?>
<?php require_once '../../incs/validation_functions.php'; ?>
<?php
if(!empty($_SESSION["header"])) {
	$header = $_SESSION["header"];
} else {
	$header = "header_pub.php";
}
?>
<?php include '../../incs/layout/'.$header; ?>

<?php
$username = "";

	if ( isset($_POST["submit"]) ) {

		// Validation
		$fields_required = array("username", "password");
		validate_presences($fields_required);

		$fields_with_max_lengths = array("username" => 15, "password" => 15);
		validate_max_lengths($fields_with_max_lengths);

		
		if (empty($errors)) {
			$username = trim($_POST["username"]);
			$username = mysqli_real_escape_string($mysqli, $username);
			
			$password = trim($_POST["password"]); 
			$password = filter_input(INPUT_POST, $password);
			$hashpassword = hash('sha256', $password);

			// Success
			$query  = "SELECT * ";
			$query .= "FROM logins ";
			$query .= "WHERE username = '{$username}' AND hashpassword = '{$hashpassword}' ";
			$query .= "Limit 1";
			$user_set = $mysqli->query($query); 

			// Check for query errrors
			confirm_query($user_set);
			
			if ($user_set && $user_set -> num_rows == 1) {
				$message = "Welcome ".ucfirst($username)." !";
				$_SESSION['header'] = "header.php";
				
				// Create SESSION
				$_SESSION['logged_user'] = $username;
				
				$message .= '<br><br><a id="message" href="admin.php" title="admin">Check out the gallery in admin mode</a>';
				
				
			} else {
				$message =  "Username/password are not valid.";
			}
		}
	}
?>

<!-- LOG IN FORM -->
<div class="col-12">
	<div class="container-small text-center">

		<!-- DISPLAYING ERRORS -->
		<?php echo form_errors($errors) ?>
		<?php echo "<h3>$message</h3>"; ?>
		
		<form action="login.php" method="POST">
			<div class="col-5">
				<input type="text" name="username" value="" placeholder="username">
			</div>
			<div class="col-5">
				<input type="password" name="password" value="" placeholder="password">
			</div>
			<div class="col-2 text-left"> 
				<input class="btn-primary" type="submit" name="submit" value="LOGIN" >
			</div>
		</form>

	</div>
</div>


<?php include '../../incs/layout/footer.php'; ?>