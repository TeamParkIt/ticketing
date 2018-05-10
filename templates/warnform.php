<h2>Add a warning</h2>
<form method="POST">
	<div class="container">
	<input type="hidden" name="fk_lot">
	<input type="text" name="licensePlate" required="" placeholder="License Plate (no dashes)"><br><br>
					
	<?php Includes::getDateTime();?>
	<input type="submit" name="submit" class="button">
	</div>
</form>

<?php
	if(isset($_POST['submit'])){
		
			$warning = new Warning($_POST['fk_lot'], $_POST['licensePlate']);
			$warning->createWarning($_POST['dateTime'], null);
			echo "Warning recorded";
			echo '<a href="check.php"><div class="button buttonOrange">Check New Plate</div></a>';
}

?>