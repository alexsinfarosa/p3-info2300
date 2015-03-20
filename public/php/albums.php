<?php require_once '../../incs/session.php'; ?> 
<?php require_once '../../incs/config.php'; ?> 
<?php require_once '../../incs/functions.php'; ?>
<?php require_once '../../incs/validation_functions.php'; ?>
<?php include '../../incs/layout/header.php'; ?>

<?php  
// Creating new album
if (isset($_POST["submit"])) {

	// Create variables
	$album_title = check_input($_POST["album_title"]);
	$album_title = ucfirst($album_title);
	$album_date_created = date("y/m/d h:i:sa");
	$album_date_modified = date("y/m/d h:i:sa");

	// Validation
	$fields_required = array("album_title");
	validate_presences($fields_required);

	$fields_with_max_lengths = array("album_title" => 20);
	validate_max_lengths($fields_with_max_lengths); 

	// Errors
	if (!empty($errors)) {
		$_SESSION["errors"] = $errors;
		// redirect_to("albums.php");
	} else {
		// Updating the database
		$query  = "INSERT INTO albums (";
		$query .= " album_title, album_date_created, album_date_modified";
		$query .= ") VALUES (";
		$query .= " '{$album_title}','{$album_date_created}','{$album_date_modified}'";
		$query .= ")";

		$new_album = $mysqli -> query($query);
	 
	}
}

?>
<!-- ADD NEW ALBUM -->
<div class="container-small text-center">
	
	<!-- Display errors --> 
	<?php echo form_errors($errors) ?>

	<form action="albums.php" method="POST">
			<div class="col-9">
				<input type="text" name="album_title" value="" placeholder="Album title">
			</div>
			<div class="col-3">
				<input class="btn-primary" type="submit" name="submit" value="Create">
			</div>
	</form>
</div>

<?php
// Perform database query to display albums
	$query  = "SELECT * ";
	$query .= "FROM albums ";
	$query .= "ORDER BY album_title";

	$album_set = mysqli_query($mysqli, $query); 
	confirm_query($album_set);
?>

<div class="container">
	<?php 
	while($album = mysqli_fetch_assoc($album_set)) {	
	?>
		<ul class="album text-center">
			<li><a href="imagesInAlbum.php?album_id=<?php echo $album["album_id"] ?>" class="album-title"><?php echo $album["album_title"]; ?></a></li>
			<small>
				Date Created: <?php echo $album["album_date_created"]; ?>
				<br>
				Date Modified: <?php echo $album["album_date_modified"]; ?>
			</small>
			<li><a href="delete_album.php?
			album_id=<?php echo $album["album_id"] ?>
			&album_title=<?php echo $album["album_title"] ?>
			&album_date_created=<?php echo $album["album_date_created"] ?>
			&album_date_modified=<?php echo $album["album_date_modified"] ?>" 
			class="btn-primary-small"> Delete</a><li>
		</ul>
	<?php  
	}
	?>
</div>


<?php include '../../incs/layout/footer.php'; ?>
