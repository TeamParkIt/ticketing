<?php require_once('./config.php'); 
Includes::getHead();
if(isset($stripe['secret_key'])  && isset($_POST['ticketID'])){
	$ticket="";
	if(Ticket::getTicketByNumber($_POST['ticketID'], null)){
		$ticket = Ticket::getTicketByNumber($_POST['ticketID'], null)->fetch_assoc();
		$chargeAmount = $ticket['charge'];
		Includes::getChargeForm($stripe['publishable_key'], $chargeAmount);
	}else{
		echo "no ticket found";
	}
	

}else{
	Includes::getEntryForm();
}


?>