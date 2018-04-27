<?php
   require_once('stripe-manager.php');
?>

<h1>Connect Payment Method</h1>

<h3>You need to add a payment method before we'll show your spaces.</h3>

<a href="<?php echo StripeManager::getUserExpressRegistrationOauthURL(); ?>">
   <img src="img/stripe-button.png" style="width: 190px; height: 33px;"/>
</a>
