<h2>Check Violations</h2>
<form method="POST">
	<input type="hidden" name="fk_lot">
	<input type="text" name="licensePlate" required="" placeholder="License Plate (no dashes)"><br><br>
	<input type="submit" name="submit" class="button">
</form>

<?php
	if(isset($_POST['submit'])){
		$violationCount = Warning::getNumberOfWarningsByPlate($_POST['licensePlate'], null) + Ticket::getNumberOfTicketsByPlate($_POST['licensePlate'], null);
		if($violationCount >0){
			echo "Number of Warnings: ".Warning::getNumberOfWarningsByPlate($_POST['licensePlate'], null)."<br>";
			echo "Number of Tickets: ".Ticket::getNumberOfTicketsByPlate($_POST['licensePlate'], null)."<br><br>";
			Includes::getWarningRecord($_POST['licensePlate']);
			Includes::getTicketRecord($_POST['licensePlate']);
			Includes::getTicketOrWarn();
			
		}else{
			echo "ticket ID doesn't exist";
		}
}
