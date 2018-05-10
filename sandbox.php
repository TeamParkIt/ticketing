<?php
require 'config.php';
//PaymentManager::subscribeCustomer('cus_CO4grAtMZGf0TH', 'plan_CY41KkDXrJtQun' );
//PaymentManager::createSubscription(450, 2);
//PaymentManager::subCust2();

Ticket::bulkAdd(40, 52, 2, 2500 ,null);
?>

<a href="https://connect.stripe.com/express/oauth/authorize?redirect_uri=https://stripe.com/connect/default/oauth/test&client_id=ca_CZFkVxd0WpRAh1yJrQwZLhBTHwNJ5fFJ"><div class="button">Connect</div></a>

