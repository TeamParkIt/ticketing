<?php
  require_once('./config.php');
  Includes::getHead();
$charge = new PaymentManager();
$charge->createCustomer($_POST['stripeToken'], $_POST['stripeEmail']);

$charge->createCharge(Ticket::getTicketByNumber($_POST['ticketID'], null)->fetch_assoc()['charge']);
Ticket::payoffTicket($_POST['ticketID'], null);

?>

<h1>Ticket <?php echo $_POST['ticketID']; ?> Successfully Processed</h1>
<p>We have cleared your license plate of this violation.</p>