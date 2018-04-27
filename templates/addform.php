<h2>Add a ticket</h2>
<form method="POST">
	<input type="hidden" name="fk_lot">
	<input type="text" name="licensePlate" required="" placeholder="License Plate (no dashes)"><br><br>
	<input type="text" name="ticketNumber" required="" placeholder="Ticket ID"><br><br>
	<input type="submit" name="submit" class="button">
</form>

<?php
	if(isset($_POST['submit'])){
		if(Ticket::issueTicket($_POST['licensePlate'], $_POST['ticketNumber'],null)){
			echo "ticket added";
		}else{
			echo "ticket ID doesn't exist";
		}
}

?>