<?php require_once '../../incs/session.php'; ?> 
<?php require_once '../../incs/config.php'; ?> 
<?php require_once '../../incs/functions.php'; ?>
<?php include '../../incs/layout/header.php'; ?>

<?php 
// Delete album from database
if ( isset($_GET["image_id"]) ) {

	$image_id = $_GET["image_id"];
	$image_caption = $_GET["image_caption"];
	$image_date_taken = $_GET["image_date_taken"];
	$image_url = $_GET["image_url"];

	$query  = "DELETE ";
	$query .= "FROM images ";
	$query .= "WHERE image_id = $image_id";

	$deleted_image = $mysqli -> query($query);
}
	// Check for query errrors
	confirm_query($deleted_image);

?>
<div class="container text-center">
	<h3>The image below has been deleted.</h3>
	<figure class="text-center bottom-margin">
		<img class="img-responsive round-borders" src="../img/<?php echo $image_url; ?>" alt="<?php echo $image_caption; ?>"> 		
		<figcaption>
			<span class="author">Image Caption:</span> <?php echo $image_caption; ?>
			<br>
			<span class="author">Date Taken:</span> <?php echo $image_date_taken; ?>
		</figcaption>
	</figure>
</div>

<?php include '../../incs/layout/footer.php'; ?>