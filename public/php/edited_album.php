<?php require_once '../../incs/session.php'; ?> 
<?php require_once '../../incs/config.php'; ?> 
<?php require_once '../../incs/functions.php'; ?>
<?php include '../../incs/layout/header.php'; ?>

<?php 

// Delete album from database
if ( isset($_GET["album_id"]) ) {


	$album_id = $_GET["album_id"];
	$album_title = $_GET["album_title"];
	$album_date_created = $_GET["album_date_created"];
	$album_date_modified = date("y/m/d h:i:sa");

	// Update the images table
	$query  = "UPDATE albums SET ";
	$query .= "album_title = '{$album_title}', album_date_modified = '{$album_date_modified}' "; 
	$query .= "WHERE album_id = $album_id";
	confirm_query($mysqli -> query($query)); // Check if errors in the query

	$edited_album = $mysqli -> query($query);
}
	// Check for query errrors
	confirm_query($deleted_album);

?>
<div class="container text-center">
	<h3>The album below has been edited.</h3>
	<ul class="album-centered text-center">
		<li><a href="" class="album-title"><?php echo $album_title; ?></a></li>
		<small>
			Date Created: <?php echo $album_date_created; ?>
			<br>
			Date Modified: <?php echo $album_date_modified; ?>
		</small>
	</ul>
</div>

<?php include '../../incs/layout/footer.php'; ?>