<h2>Check Violations</h2>
<form method="POST">
	<input type="hidden" name="fk_lot">
	<input type="text" name="licensePlate" required="" placeholder="License Plate (no dashes)"><br><br>
	<input type="submit" name="submit" class="button" value="Check Plate">
</form>

<?php
	if(isset($_POST['submit'])){

		$violationCount = Warning::getNumberOfWarningsByPlate($_POST['licensePlate'], null) + Ticket::getNumberOfTicketsByPlate($_POST['licensePlate'], null);
		//check if plate is whitelisted
		if(Whitelist::checkWhiteList($_POST['licensePlate'], null)){echo "<span style='color:#0aec0a; background:darkgreen; padding:7px;'>Plate is on whitelist</span>"."<br>";}

			echo "Number of Warnings: ".Warning::getNumberOfWarningsByPlate($_POST['licensePlate'], null)."<br>";
			echo "Number of Tickets: ".Ticket::getNumberOfTicketsByPlate($_POST['licensePlate'], null)."<br><br>";
			Includes::getWarningRecord($_POST['licensePlate']);
			Includes::getTicketRecord($_POST['licensePlate']);
			Includes::getTicketOrWarn();

}
