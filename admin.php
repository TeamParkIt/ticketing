<?php require_once('./config.php'); 
Includes::getHead();
?>
<div class="container">
	<div class="box">
		<form method="POST">
			<input type="password" name="password" placeholder="password" required=""><br>
			<input type="text" name="lowest" placeholder="Lowest Number Ticket" required=""><br>
			<input type="text" name="highest" placeholder="Highest Number Ticket" required=""><br>
			<input type="text" name="charge" placeholder="Charge (in cents)" required=""><br>
			<input type="text" name="LID" placeholder="Lot ID" required=""><br>
			<input type="submit" name="submit"><br>

		</form>
	<?php
	if(isset($_POST['submit'])){
		if(Ticket::bulkAdd($_POST['password'], $_POST['lowest'], $_POST['highest'], $_POST['LID'], $_POST['charge'], null)){
			echo "Success";
		}
	}
	
	?>
	</div>
</div>
