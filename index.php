<?php require_once('./config.php'); 
Includes::getHead();
?>
<div class="container">
	<div class="box">
<?php
if(isset($stripe['secret_key'])  && isset($_POST['ticketID'])){
	$ticket="";
	if(Ticket::getTicketByNumber($_POST['ticketID'], null)){
		$ticket = Ticket::getTicketByNumber($_POST['ticketID'], null)->fetch_assoc();
		$chargeAmount = $ticket['charge'];
		Includes::getChargeForm($stripe['publishable_key'], $chargeAmount);
	}else{
		echo "<div class='errorMessage'>No ticket found. Check violation number.</div>";
		Includes::getEntryForm();
	}
	

}else{
	Includes::getEntryForm();
}


?>
	</div>
</div>