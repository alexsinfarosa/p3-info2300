<?php require_once '../../incs/config.php'; ?> 
<?php require_once '../../incs/functions.php'; ?>
<?php include '../../incs/layout/header.php'; ?>


<!-- LOG IN FORM -->
<div class="col-12">
	<div class="container-small text-center">
		<h3>Please log in to have access to the gallery</h3>
		<form action="../index.php" method="POST">
			<div class="col-6">
				<input type="text" name="username" value="" placeholder="username">
			</div>
			<div class="col-6">
				<input type="password" name="password" value="" placeholder="password">
			</div>
		</form>
	</div>
</div>


<?php include '../../incs/layout/footer.php'; ?>