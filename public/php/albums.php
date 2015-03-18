<?php require_once '../../incs/config.php'; ?> 
<?php require_once '../../incs/functions.php'; ?>
<?php include '../../incs/layout/header.php'; ?>

<!-- ADD NEW ALBUM -->
<div class="container-small">
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
// Creating new album
if (isset($_POST["submit"])) {

	// Create variables
	$album_title = mysqli_real_escape_string($mysqli, $_POST["album_title"]);
	$album_title = ucfirst($album_title);
	$album_date_created = date("y/m/d");
	$album_date_modified = date("y/m/d");

	// Display error if no album title
	if (empty($album_title)) {
		?>
		<div class="container-extra-small text-center">
			<?php echo "Sorry, album title cannot be empty"; ?>
		</div>
		<?php
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

// Perform database query
	$query  = "SELECT * ";
	$query .= "FROM albums ";
	$query .= "ORDER BY album_title";

	// This set is a standard array. Keys are integers
	$album_set = mysqli_query($mysqli, $query); 
	confirm_query($album_set);
?>

<div class="container">
	<?php 
	while($album = mysqli_fetch_assoc($album_set)) {	
	?>
		<ul class="album text-center">
			<li><a href="imagesInAlbum.php?id=<?php echo $album["album_id"] ?>" class="album-title"><?php echo $album["album_title"]; ?></a></li>
			<small>
				Date Created: <?php echo $album["album_date_created"]; ?>
				<br>
				Date Modified: <?php echo $album["album_date_modified"]; ?>
			</small>
			<li><a href="delete_album.php?
			id=<?php echo $album["album_id"] ?>
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
