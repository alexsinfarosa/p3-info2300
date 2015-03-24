<?php require_once '../../incs/session.php'; ?> 
<?php require_once '../../incs/config.php'; ?> 
<?php require_once '../../incs/functions.php'; ?>
<?php require_once '../../incs/validation_functions.php'; ?>
<?php include '../../incs/layout/header.php'; ?>

<?php
if ( isset($_POST["re_edit_image"]) ) {
	$_SESSION['image_id'] = $_POST['hidden_image_id'];
	$image_id = $_SESSION['image_id'];
	$image_caption = $_POST['caption'];
	$image_caption = mysqli_real_escape_string($mysqli, $image_caption);
	$image_caption = ucfirst($image_caption);
	$image_date_taken = $_POST["date"]; // if not selected gives 0000-00-00. Remember to fix it!

	// Getting the old album ids
	$query  = "SELECT DISTINCT album_id ";
	$query .= "FROM images_in_albums ";
	$query .= "WHERE image_id = $image_id";

	$in_albums = $mysqli -> query($query);
	confirm_query($in_albums);

	$old_albums_id = array();
	while ($tony = mysqli_fetch_assoc($in_albums)) {
		$old_albums_id[] = $tony["album_id"];
	}
	print_r($old_albums_id);

	// Passing the selected album ids. Album id=0 if no albums are selected
	$new_albums_id = array();
	if ( !empty($_POST['albumsID']) ) {
		$new_albums_id = $_POST['albumsID'];
	} else {
		array_push($new_albums_id, 0);
	} 
	
	// Validation
	$fields_required = array("caption", "date");
	validate_presences($fields_required);

	$fields_with_max_lengths = array("caption" => 50);
	validate_max_lengths($fields_with_max_lengths);


	if (!empty($errors)) {
		$_SESSION["errors"] = $errors;
		$errors = $_SESSION["errors"];
	} else {
		// Query to update the Date Modified field in the albums table
		$album_date_modified = date("y/m/d h:i:sa");
		for ($i=0; $i < count($new_albums_id) ; $i++) { 
			$query  = "UPDATE albums SET album_date_modified = '{$album_date_modified}' WHERE album_id = {$new_albums_id[$i]}";
			confirm_query($mysqli -> query($query)); // Check for errors in the query
		}

		// Update the images table
		$query  = "UPDATE images SET ";
		$query .= "image_caption = '{$image_caption}', image_date_taken = '{$image_date_taken}' "; 
		$query .= "WHERE image_id = $image_id";
		confirm_query($mysqli -> query($query)); // Check if errors in the query	

		$combined_albums_id = array_merge($old_albums_id, $new_albums_id);
		$combined_albums_id = array_unique($combined_albums_id);

		foreach ($combined_albums_id as $album) {
			if (!in_array($album, $combined_albums_id)) {
				foreach ($new_albums_id as $album_id) {
					$query  = "INSERT INTO images_in_albums (";
					$query .= " image_id, album_id";
					$query .= ") VALUES (";
					$query .= " {$image_id}, {$album_id}";
					$query .= ")";
					confirm_query($mysqli -> query($query)); // Check if errors in the query
				}
				?>
					<div class="container-extra-small text-center">
						<?php echo "Image updated succesfully"; ?>
					</div>
				<?php
			} else {
				foreach ($new_albums_id as $album_id) {
				$query  = "UPDATE images_in_albums SET ";
				$query .= "image_id = {$image_id}, album_id = {$album_id} "; 
				$query .= "WHERE image_id = $image_id";
				confirm_query($mysqli -> query($query)); // Check if errors in the query
				}
				?>
					<div class="container-extra-small text-center">
						<?php echo "Image updated succesfully"; ?>
					</div>
				<?php
			}
		}		 
	}
}// END edit album


?>

<!-- ADDING IMAGES FORM -->
<div class="container-extra-small">
<div class="col-12 text-center">
	<?php echo form_errors($errors) ?>
</div>
	<form action="#" method="POST">
		
		<!-- Caption -->
		<div class="col-12">
			<label class="align-left" for="caption"> Image caption:</label> 
			<textarea name="caption"></textarea>
		</div>

		<!-- Date taken --> 
		<div class="col-12">
		<label class="align-left" for="date"> Date image was taken:</label> 
			<input type="date" name="date">
		</div>

		<input type="hidden" name="hidden_image_id" value="<?php echo $image_id; ?>">

		<!-- Dynamicaaly generating albums -->
		<?php
			$query  = "SELECT * "; 
			$query .= "FROM albums ";
			$query .= "ORDER BY album_title";
			$album_set = $mysqli -> query($query);
			confirm_query($album_set); // Check for query errrors
		?>

		<div class="col-12"> 
			<label class="align-left">Place image in album(s):</label><br>
			<?php 
				while($album = mysqli_fetch_assoc($album_set)){
			 ?>
				<label class="label-align">
					<input type="checkbox" class="align-left" name="albumsID[]" value="<?php echo $album['album_id']; ?>" <?php if( in_array($album['album_id'], $old_albums_id) ) {echo $checked = "checked";} ?>> 
					<span><?php echo $album['album_title'] ?></span>
				</label>
			<?php
				}
			?> 			
		</div> <!-- end col-12 --> 

		<!-- Upload the image --> 
		<div class="col-12"> 
			<input class="btn-primary" type="submit" name="re_edit_image" value="Save Changes" >
		</div>

	</form>
</div>

<?php include '../../incs/layout/footer.php'; ?>