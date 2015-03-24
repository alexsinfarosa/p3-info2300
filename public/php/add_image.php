<?php require_once '../../incs/session.php'; ?> 
<?php require_once '../../incs/config.php'; ?> 
<?php require_once '../../incs/functions.php'; ?>
<?php require_once '../../incs/validation_functions.php'; ?>
<?php include '../../incs/layout/header.php'; ?>

<?php 
	if( isset($_POST['submit']) ) {

		// Getting the form values 
		$image_caption = $_POST['caption'];
		$image_caption = ucfirst($image_caption);
		$image_date_taken = $_POST["date_taken"]; // if not selected gives 0000-00-00. Remember to fix it!

		// Passing the selected album ids. Album id=0 if no albums are selected
		$albums_id = array();
		if ( !empty($_POST['albumsID']) ) {
			$albums_id = $_POST['albumsID'];
		} else {
			array_push($albums_id, 0);
		}

		// Validation
		$fields_required = array("caption", "date_taken");
		validate_presences($fields_required);

		$fields_with_max_lengths = array("caption" => 50);
		validate_max_lengths($fields_with_max_lengths);

		if (!empty($errors)) {
			$_SESSION["errors"] = $errors;
			$errors = $_SESSION["errors"];
		} else { 

			// Getting image attributes
			$image_name 		= $_FILES["file_upload"]["name"];
			$image_tmp_location = $_FILES["file_upload"]["tmp_name"];

			// Escaping before including them into sql. Preventing SQL injections
			$image_caption  	= mysqli_real_escape_string($mysqli, $image_caption);
			$image_name     	= mysqli_real_escape_string($mysqli, $image_name);
			$image_tmp_location = mysqli_real_escape_string($mysqli, $image_tmp_location);

			
			// Checking if user uploaded an image
			if (empty($image_name)) {
				?>
				<div class="container-extra-small text-center">
					<?php $message = "Please select an image to upload"; ?>
				</div>
				<?php
			}

			// Placing image in destination folder
			// $target_path_and_name  = "/home/info230/SP15/users/as898sp15/www/p3/m3/public/img/";
			$target_path_and_name  = "/Applications/MAMP/htdocs$site_root/img/".$image_name; // YOU MUST CHANGE THIS FOR THE CORNELL SERVER

			// Update the images table
			if (move_uploaded_file($image_tmp_location, $target_path_and_name)) {
				
				// Create query to insert new image in the images table
				$query  = "INSERT INTO images (";
				$query .= " image_caption, image_date_taken, image_url";
				$query .= ") VALUES (";
				$query .= " '{$image_caption}', '{$image_date_taken}', '{$image_name}'";
				$query .= ")";
				confirm_query($mysqli -> query($query)); // Check if errors in the query

				// Query to update the Date Modified field in the albums table
				$album_date_modified = date("y/m/d h:i:sa");
				for ($i=0; $i < count($albums_id) ; $i++) { 
					$query  = "UPDATE albums SET album_date_modified = '{$album_date_modified}' WHERE album_id = {$albums_id[$i]}";
					confirm_query($mysqli -> query($query)); // Check for errors in the query
				}

				// Create query to get the last album id
				$query = "SELECT MAX(image_id) FROM images";
				$last_image = $mysqli -> query($query);
				$image_set = mysqli_fetch_assoc($last_image);
				$last_id = $image_set["MAX(image_id)"];
				confirm_query($last_id);

				// Getting the IDs from checkboxes and insert them into images_albums table
				foreach ($albums_id as $album_id) {
					$query  = "INSERT INTO images_in_albums (";
					$query .= " image_id, album_id";
					$query .= ") VALUES (";
					$query .= " {$last_id}, {$album_id}";
					$query .= ")";
					$mysqli -> query($query);
				}
				
				?>
					<div class="container-extra-small text-center">
						<?php echo "File uploaded succesfully"; ?>
					</div>
				<?php
			} // move_uploaded_file
		} 
	}		
?>

<!-- ADDING IMAGES FORM -->
<div class="container-extra-small">
<div class="col-12 text-center">
	<?php echo form_errors($errors) ?>
	<?php echo $message ?>
</div>
	<form action="add_image.php" method="POST" enctype="multipart/form-data">
		
		<!-- Caption -->
		<div class="col-12">
			<label class="align-left" for="caption"> Image caption:</label> 
			<textarea id="caption" name="caption"></textarea>
		</div>

		<!-- Date taken --> 
		<div class="col-12">
		<label class="align-left" for="date_taken"> Date image was taken:</label> 
			<input id="date_taken" type="date" name="date_taken">
		</div>

		<!-- Dynamicaaly generating albums -->
		<?php
			$query  = "SELECT * "; 
			$query .= "FROM albums ";
			$query .= "ORDER BY album_title";
			$album_set = $mysqli -> query($query);

			// Check for query errrors
			confirm_query($album_set);
		?>

		<div class="col-12"> 
			<label class="align-left">(Optional) - Place image in album(s):</label><br>
			<?php 
				while($album = mysqli_fetch_assoc($album_set)){
					if ($album['album_id'] != 0) {
			 ?>
						<label class="label-align">
							<input type="checkbox" class="align-left" name="albumsID[]" value="<?php echo $album['album_id'] ?>"> 
							<span><?php echo $album['album_title'] ?></span>
						</label>
			<?php
					}
				}
			?> 			
		</div> <!-- end col-12 --> 

		<!-- Select locally the image to upload --> 
		<div class="col-12"> 
			<input class="input-file" type="file" name="file_upload">
		</div>

		<!-- Upload the image --> 
		<div class="col-12"> 
			<input class="btn-primary" type="submit" name="submit" value="Upload Image" >
		</div>

	</form>
</div>

<?php include '../../incs/layout/footer.php'; ?>