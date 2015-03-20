<?php require_once '../../incs/session.php'; ?> 
<?php require_once '../../incs/config.php'; ?> 
<?php require_once '../../incs/functions.php'; ?>
<?php require_once '../../incs/validation_functions.php'; ?>
<?php include '../../incs/layout/header.php'; ?>


<?php 	
	if ( isset($_POST["submit"]) ) {

		$username = trim($_POST["username"]);
		$password = trim($_POST["password"]);

		// Validation
		$fields_required = array("username", "password");
		validate_presences($fields_required);

		$fields_with_max_lengths = array("username" => 15, "password" => 15);
		validate_max_lengths($fields_with_max_lengths);

		// Try to LOGIN
		if ( empty($errors) ) {
			if ($username == "alex" && $password == "password") {
				// Succesfull login
				$message = "Hello ". $username . "!";
				// redirect_to("#");
			} else {
				$message = "Username/password do not match.";
			}
		}
	} else {
		$username = "";
	}
 ?>


<!-- LOG IN FORM -->
<div class="col-12">
	<div class="container-small text-center">
		<h4>Please log in to have admin privileges to the gallery</h4><br>
		
		<!-- DISPLAYIN ERRORS -->
		<?php echo form_errors($errors) ?>
		<?php echo "<h3>$message</h3>"; ?>
		
		<form action="#" method="POST">
			<div class="col-5">
				<input type="text" name="username" value="<?php htmlspecialchars($username); ?>" placeholder="username">
			</div>
			<div class="col-5">
				<input type="password" name="password" value="" placeholder="password">
			</div>
			<div class="col-2"> 
				<input class="btn-primary" type="submit" name="submit" value="LOGIN" >
			</div>
		</form>
	</div>
</div>


<?php include '../../incs/layout/footer.php'; ?>