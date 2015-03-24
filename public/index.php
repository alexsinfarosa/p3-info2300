<?php require_once '../incs/session.php'; ?>
<?php require_once '../incs/config.php'; ?> 
<?php require_once '../incs/functions.php'; ?>
<?php require_once '../incs/validation_functions.php'; ?>
<?php include '../incs/layout/header.php'; ?>

<?php 
// Perform database query
$query  = "SELECT DISTINCT * ";
$query .= "FROM images";
// $query .= "ORDER BY image_id DESC";

$image_set = $mysqli -> query($query); 
confirm_query($image_set); // Check for query errrors

// EDIT album
if (isset($_POST["save_changes"])) {
	$image_id = $_POST["image_id"];
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
		// Update the images table
		$query  = "UPDATE images SET ";
		$query .= "image_caption = '{$image_caption}', image_date_taken = '{$image_date_taken}' "; 
		$query .= "WHERE image_id = $image_id";
		confirm_query($mysqli -> query($query)); // Check if errors in the query	

		$combined_albums_id = array_merge($old_albums_id, $new_albums_id);
		$combined_albums_id = array_unique($combined_albums_id);

		// Query to update the Date Modified field in the albums table
		$album_date_modified = date("y/m/d h:i:sa");
		for ($i=0; $i < count($combined_albums_id) ; $i++) { 
			$query  = "UPDATE albums SET album_date_modified = '{$album_date_modified}' WHERE album_id = {$combined_albums_id[$i]}";
			confirm_query($mysqli -> query($query)); // Check for errors in the query
		}

		if ($new_albums_id[0] !== 0) {
			foreach ($old_albums_id as $album_id) {
				$query  = "DELETE ";
				$query .= "FROM images_in_albums ";
				$query .= "WHERE album_id = $album_id";
				confirm_query($mysqli -> query($query)); 
			}
			foreach ($new_albums_id as $album_id) {
				$query  = "INSERT INTO images_in_albums (";
				$query .= " image_id, album_id";
				$query .= ") VALUES (";
				$query .= " {$image_id}, {$album_id}";
				$query .= ")";
				confirm_query($mysqli -> query($query)); // Check if errors in the query
			}
		} else {
			foreach ($new_albums_id as $album_id) {
				$query  = "UPDATE images_in_albums SET ";
				$query .= "image_id = {$image_id}, album_id = {$album_id} "; 
				$query .= "WHERE image_id = $image_id";
				confirm_query($mysqli -> query($query)); // Check if errors in the query
			}
		}

		if (empty($errors)) {
			?>
				<div class="container-extra-small text-center">
					<?php echo "Image updated succesfully!"; ?>
				</div>
			<?php
				
			} else {
				?>
				<div class="container-extra-small text-center">
					<?php echo "Image updated failed"; ?>
				</div>
			<?php
		}				 
	}
}// END edit album


?>
<!-- Displaying the images -->
<div class="col-12">
	<div class="container">

	<!-- Display errors -->
	<div class="col-12 text-center">
		<?php echo form_errors($errors) ?>
	</div>

		<?php 
		while($image = mysqli_fetch_assoc($image_set)) {
		?>	
		<div class="col-12 border-bottom">
			<div class="col-12">
				<figure class="text-center">
					<img class="img-responsive round-borders" src="img/<?php echo $_SESSION['image_url'] = $image["image_url"]; ?>" alt="<?php echo $_SESSION['image_caption'] = $image["image_caption"]; ?>">
					<figcaption>
						<span class="author">Image Caption:</span> <?php echo $_SESSION['image_caption'] = $image["image_caption"]; ?>
						<br>
						<span class="author">Date Taken:</span> <?php echo $_SESSION['image_date_taken'] = $image["image_date_taken"]; ?>
						<br>
					</figcaption>
				</figure>
			</div>

			<!-- DELETE button -->
			<div class="col-6 text-right">
				<form action="php/delete_image.php" method="POST">
					<input class="btn-primary-small" type="submit" name="delete" value="Delete">
					<input type="hidden" name="image_id" value="<?php echo $_SESSION['image_id'] = $image["image_id"]; ?>">
				</form>
			</div>

			<!-- EDIT button -->
			<div class="col-6 text-left">
			   <form action="index.php#<?php echo $image["image_id"] ?>" method="POST">
			   		<input class="edit btn-primary-small" type="submit" name="edit" value="Edit">
			   		<input type="hidden" name="image_id" value="<?php echo $_SESSION['image_id'] = $image["image_id"]; ?>">
			   		<input class="mostra" type="submit" style="display:none;"> 	
			   </form>
			</div>
		</div>

		<!-- hidden form -->
	   	<div class="col-12 reveal_field">
		   	<form action="index.php" method="POST">
			   	<div class="col-6">
			   		<input id="<?php echo $image["image_id"] ?>" type="text" name="caption" value="" placeholder="Image Caption">
			   	</div>
			   	<div class="col-3">
			   		<input type="date" name="date">
			   	</div>
			   	<div class="col-3">
			   		<input class="btn-primary" type="submit" name="save_changes" value="Save changes">
			      	<input type="hidden" name="image_id" value="<?php echo $_SESSION['image_id'] = $image["image_id"]; ?>">
			    </div>

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
							if ($album['album_id'] != 0) {
					 ?>
								<label class="label-align-horizontal">
									<input type="checkbox" class="align-left" name="albumsID[]" value="<?php echo $_SESSION['album_id'] = $album['album_id']; ?>"> 
									<span><?php echo $_SESSION['album_title'] = $album['album_title'] ?></span>
								</label>
					<?php
							}
						}
					?> 			
				</div> <!-- end col-12 --> 
		   	</form>
	   </div> <!-- end hidden form --> 	 
		<?php  
		}
		?>
	</div> <!-- end container --> 
</div> <!-- end col-12 --> 

<!-- Back to top button --> 
<div class="container">	
	<ul class="col-12 text-center">
		<li><a class="nav go-top" href="<?php echo($site_root); ?>/index.php">Back to Top</a></li>
	</ul>
</div>

<?php include '../incs/layout/footer.php'; ?>