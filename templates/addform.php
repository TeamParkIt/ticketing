<h2>Add a ticket</h2>
<form method="POST">
	<input type="hidden" name="fk_lot">
	<input type="text" name="licensePlate" required="" placeholder="License Plate (no dashes)"><br><br>
	<input type="text" name="ticketNumber" required="" placeholder="Ticket ID"><br><br>
	<?php Includes::getDateTime();?>
	<input type="submit" name="submit" class="button" value="Add Ticket">
</form>

<?php
	if(isset($_POST['submit'])){
		if(Ticket::issueTicket($_POST['licensePlate'], $_POST['ticketNumber'], $_POST['dateTime'],null)){
			echo 'Ticket Added';
			echo '<a href="check.php"><div class="button buttonOrange">Check New Plate</div></a>';
		}else{
			echo "ticket ID doesn't exist";
		}
}

?>