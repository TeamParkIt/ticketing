<form action="<?php echo SERVERROOT; ?>charge.php" method="post">
	<input type="hidden" name="ticketID" value="<?php echo $_POST['ticketID']; ?>">
  <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
          data-key="<?php echo $pubKey; ?>"
          data-description="Parking Violation"
          data-amount="<?php echo $chargeAmount ?>"
          data-locale="auto"></script>
</form>