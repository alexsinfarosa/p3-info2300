<?php require_once '../../incs/session.php'; ?> 
<?php include '../../incs/layout/header_pub.php'; ?>

<?php 
if (isset($_SESSION['logged_user'])) {
	$old_user = $_SESSION['logged_user'];
	unset($_SESSION['logged_user']);
} else {
	$old_user = false;
}

if ($old_user) {
?>
	<div class="container text-center">
		<h3>You have successfully logged out <?php echo ucfirst($old_user); ?> !</h3>
		<a id="message" href="../index.php">View the gallery</a>
	</div>
<?php
	
}

?>

<?php include '../../incs/layout/footer.php'; ?>