<div class="container">
	<div class="box">
		<h2>Enter the parking violation number</h2>
		<form method="post">
	  		<input type="text" required="" name="ticketID" placeholder="ID number on ticket" required="">
	  		<input type="submit" name="submitTicket">
		</form>
	</div>
</div>

<?php
if(isset($_POST['submitTicket'])){
	$ticket="";
	if(Ticket::getTicketByNumber($_POST['ticketID'], null)){
		$ticket = Ticket::getTicketByNumber($_POST['ticketID'], null)->fetch_assoc();
		$chargeAmount = $ticket['charge'];
		Includes::getChargeForm($stripe['publishable_key'], $chargeAmount);
	}else{
		echo "no ticket found";
	}
}
?>