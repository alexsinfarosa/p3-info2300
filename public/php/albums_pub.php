<?php require_once '../../incs/session.php'; ?> 
<?php require_once '../../incs/config.php'; ?> 
<?php require_once '../../incs/functions.php'; ?>
<?php require_once '../../incs/validation_functions.php'; ?>
<?php include '../../incs/layout/header_pub.php'; ?>


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
	if ($album['album_id'] != 0) {
	?>
		<div class="album text-center">
			<!-- block -->
			<a class="album-title" href="imagesInAlbum_pub.php?album_id=<?php echo $album["album_id"] ?>"><?php echo $album["album_title"]; ?></a><br>
			
			<!-- dates -->
			<span class="dates">Date Created: <?php echo $album["album_date_created"]; ?></span><br>
			<span class="dates">Date Modified: <?php echo $album["album_date_modified"]; ?></span>
			
		   	<div class="col-12 reveal_field">
			   	<form action="#" method="POST">
				   	<div class="col-9">
				   		<input type="text" name="album_title_edited" value="" placeholder="Edit title">
				   	</div>
				   	<div class="col-3">
				   		<input class="btn-primary" type="submit" name="edit_title" value="+">
				      	<input type="hidden" name="album_id" value="<?php echo $album["album_id"]; ?>">
				    </div>
			   	</form>
		   </div>
		</div> <!-- end album --> 
	<?php 
	} 
	}
	?>
</div> <!-- end container --> 
<?php include '../../incs/layout/footer.php'; ?>
