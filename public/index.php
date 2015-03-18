<?php require_once '../incs/config.php'; ?> 
<?php require_once '../incs/functions.php'; ?>
<?php include '../incs/layout/header.php'; ?>

<!-- Perform database query -->
<?php
	$query  = "SELECT * ";
	$query .= "FROM images ";

	$image_set = $mysqli -> query($query); 
	
	// Check for query errrors
	confirm_query($image_set);
?>

<!-- Displaying the images -->
<div class="col-12">
	<div class="container">
		<?php 
		while($image = mysqli_fetch_assoc($image_set)) {	
		?>
			<figure class="text-center bottom-margin">
				<img class="img-responsive round-borders" src="img/<?php echo $image["image_name"]; ?>" alt="<?php echo $image["image_caption"]; ?>">
				<figcaption>
					<span class="author">Image Caption:</span> <?php echo $image["image_caption"]; ?>
					<br>
					<span class="author">Date Taken:</span> <?php echo $image["image_date_taken"]; ?>
				</figcaption>	
			</figure>
		<?php  
		}
		?>
	</div>
</div>

<!-- Back to top button --> 
<div class="container">	
	<ul class="col-12 text-center">
		<li><a class="nav go-top" href="<?php echo($site_root); ?>/index.php">Back to Top</a></li>
	</ul>
</div>

<?php include '../incs/layout/footer.php'; ?>