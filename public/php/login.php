<?php require_once '../../incs/session.php'; ?> 
<?php require_once '../../incs/config.php'; ?> 
<?php require_once '../../incs/functions.php'; ?>
<?php require_once '../../incs/validation_functions.php'; ?>
<?php include '../../incs/layout/header.php'; ?>

<?php foreach(array_keys($_SESSION) as $k) unset($_SESSION[$k]); ?>
<?php 
$username = "";	
	if ( isset($_POST["submit"]) ) {

		$username = trim($_POST["username"]);
		$password = trim($_POST["password"]);
	
		$_SESSION[ 'logged_user' ] = $username;

		// Validation
		$fields_required = array("username", "password");
		validate_presences($fields_required);

		$fields_with_max_lengths = array("username" => 15, "password" => 15);
		validate_max_lengths($fields_with_max_lengths);

		$query  = "SELECT * ";
		$query .= "FROM logins ";
		$query .= "WHERE username = '{$username}' AND password = '{$password}'";

		$user_set = $mysqli -> query($query); 
		
		// Check for query errrors
		confirm_query($user_set);

		$user = mysqli_fetch_assoc($user_set);

		if ( $user['username'] == "alex" && $user['password'] == 'password') {
			$message = "Hello ". $username . "!";
		} else {
			$message = "Username/password do not match.";
		}
	}
 ?>


<!-- LOG IN FORM -->
<div class="col-12">
	<div class="container-small text-center">
		<h4>Please log in to have admin privileges to the gallery</h4><br>
		
		<!-- DISPLAYIN ERRORS -->
		<?php echo form_errors($errors) ?>
		<?php echo "<h3>$message</h3>"; ?>
		
		<form action="../index.php" method="POST">
			<div class="col-5">
				<input type="text" name="username" value="<?php htmlspecialchars($username); ?>" placeholder="username">
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

<?php session_destroy(); ?>
<?php include '../../incs/layout/footer.php'; ?>