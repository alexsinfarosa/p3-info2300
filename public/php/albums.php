<?php require_once '../../incs/session.php'; ?> 
<?php require_once '../../incs/config.php'; ?> 
<?php require_once '../../incs/functions.php'; ?>
<?php require_once '../../incs/validation_functions.php'; ?>
<?php include '../../incs/layout/header.php'; ?>

<?php  
// ADDING NEW album
if ( isset($_POST["submit"]) ) {
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
		confirm_query($mysqli -> query($query)); // Check if errors in the query	 
	}
} // END add new album

// EDIT album
if (isset($_POST["edit_title"])) {
	$album_date_modified = date("y/m/d h:i:sa");
	$album_id =  $_POST["album_id"];
	$album_title_edited = check_input($_POST["album_title_edited"]);
	$album_title_edited = ucfirst($album_title_edited);

	// Validation
	$fields_required = array("album_title_edited");
	validate_presences($fields_required);

	$fields_with_max_lengths = array("album_title_edited" => 20);
	validate_max_lengths($fields_with_max_lengths); 

	// Errors
	if (!empty($errors)) {
		$_SESSION["errors"] = $errors;
		// redirect_to("albums.php");
	} else {
		$query  = "UPDATE albums SET ";
		$query .= "album_date_modified = '{$album_date_modified}', album_title = '{$album_title_edited}' "; 
		$query .= "WHERE album_id = $album_id";
		confirm_query($mysqli -> query($query)); // Check if errors in the query
	}
} // END edit album

// DELETE album
if (isset($_POST["delete"])) {
$album_id =  $_POST["album_id"];

$query  = "DELETE ";
$query .= "FROM albums ";
$query .= "WHERE album_id = $album_id";
confirm_query($mysqli -> query($query)); // Check if errors in the query
}

?>
<!-- ADD NEW ALBUM -->
<div class="container-small text-center">
	
	<!-- Display errors --> 
	<?php echo form_errors($errors) ?>

	<form id="add_album" action="albums.php" method="POST">
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
	if ($album['album_id'] != 0) {
	?>
		<div class="album text-center">
			<!-- block -->
			<a class="album-title" href="imagesInAlbum.php?album_id=<?php echo $album["album_id"] ?>"><?php echo $album["album_title"]; ?></a><br>
			
			<!-- dates -->
			<span class="dates">Date Created: <?php echo $album["album_date_created"]; ?></span><br>
			<span class="dates">Date Modified: <?php echo $album["album_date_modified"]; ?></span>
			
			<!-- DELETE button -->
			<form action="albums.php" method="POST">
				<div class="col-6 text-left">
					<input class="btn-primary-album" type="submit" name="delete" value="Delete">
					<input type="hidden" name="album_id" value="<?php echo $album["album_id"]; ?>">
				</div>
			</form>
			
			<!-- EDIT button -->
			<div class="col-6 text-right">
				<form action="#" method="POST">
					<input class="edit btn-primary-album" type="submit" name="edit" value="Edit">
					<input type="hidden" name="image_id" value="<?php echo $_SESSION['image_id'] = $image["image_id"]; ?>">
				</form>
			   <!-- <a href="#" class="edit btn-primary-small">Edit</a> -->
			</div>
		   	<div class="col-12 reveal_field">
			   	<form action="albums.php" method="POST">
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
