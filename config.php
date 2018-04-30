<?php
require_once('vendor/autoload.php');


if($_SERVER['DOCUMENT_ROOT']=='C:/xampp2/htdocs'){
	define('SERVERROOT', 'http://localhost/stripeTester/');
	define('FILEROOT', 'C:/xampp2/htdocs/stripeTester/');
	define('IMAGEPATH', FILEROOT.'img/');
	define('UPLOADPATH', FILEROOT);
  define('STRIPE_PK', "pk_test_rgMvynjRhhJUsshWqA0TZo8H");
  define('STRIPE_SK', "sk_test_1XnbTQiZJTwMFiOG6LHDJZqW");

	class DataBase{
		static function getConnection() {
			// Create connection
			return mysqli_connect("localhost", "root", "", "ticket");
	    }
	}	
}elseif($_SERVER['DOCUMENT_ROOT']=='/var/www/html/ticketing'){
	define('SERVERROOT', 'http://ticketpay.online/');
	define('FILEROOT', '');
	define('IMAGEPATH', FILEROOT.'img/');
	define('UPLOADPATH', $_SERVER['DOCUMENT_ROOT']);
  define('STRIPE_PK', 'pk_live_fROuSbVdmheVpp9vdgvzicnh');
  define('STRIPE_SK', 'sk_live_8UauqtToirc5i19k1N7dRhqA');

	class DataBase{
		  function getConnection() {
			// Create connection
			return mysqli_connect("localhost", "rootUser", "%MK?V`9m(", "ticket");
	    }
	}
}else{
	define('SERVERROOT', 'ticketpay.online');
  define('FILEROOT', '');
  define('IMAGEPATH', FILEROOT.'img/');
  define('UPLOADPATH', $_SERVER['DOCUMENT_ROOT']);

  class DataBase{
      function getConnection() {
      // Create connection
      return mysqli_connect("localhost", "rootUser", "%MK?V`9m(", "ticket");
      }
  }
}



$stripe = array(
  "secret_key"      => STRIPE_SK,
  "publishable_key" => STRIPE_PK
);

\Stripe\Stripe::setApiKey($stripe['secret_key']);


class Ticket{
  function getLicenseByTicketID ($ID, $transConn){
    $conn = $transConn ? $transConn : DataBase::getConnection();

      $stmt = $conn->prepare("SELECT * FROM ticket WHERE number=?");
      $stmt->bind_param("s", $ID);
      // Execute
      $stmt->execute();
      $result = $stmt->get_result();
      return $result->fetch_array()['licensePlate'];;
  }
  function issueTicket($licensePlate, $number, $transConn){
    $conn = $transConn ? $transConn : DataBase::getConnection();

      $stmt = $conn->prepare("UPDATE ticket SET licensePlate=? WHERE number=?");
      $stmt->bind_param("ss", $licensePlate, $number);
      // Execute
      if($stmt->execute()){
        return $stmt->affected_rows;
      }else{
        return $stmt->affected_rows;
      }
  }
  function payoffTicket($number, $transConn){
    $conn = $transConn ? $transConn : DataBase::getConnection();

      $stmt = $conn->prepare("UPDATE ticket SET paid=1 WHERE number=?");
      $stmt->bind_param("ss",  $number);
      // Execute
      if($stmt->execute()){
        return ;
      }else{
        return $stmt->affected_rows;
      }
  }
	function getTicketByID($ID, $transConn){
		$conn = $transConn ? $transConn : DataBase::getConnection();

    	$stmt = $conn->prepare("SELECT * FROM ticket WHERE ID=?");
    	$stmt->bind_param("s", $ID);
    	// Execute
    	$stmt->execute();
    	$result = $stmt->get_result();
    	return $result;
	}
  function getTicketByNumber($number, $transConn){
    $conn = $transConn ? $transConn : DataBase::getConnection();

      $stmt = $conn->prepare("SELECT * FROM ticket WHERE number=?");
      $stmt->bind_param("s", $number);
      // Execute
      $stmt->execute();
      $result = $stmt->get_result();
      if($stmt->affected_rows){
        return $result;
      }
      
  }
}
class Lot{
  function getLotByTicketNumber($number, $transConn){
    $conn = $transConn ? $transConn : DataBase::getConnection();

      $stmt = $conn->prepare("SELECT * FROM lot WHERE number=?");
      $stmt->bind_param("s", $number);
      // Execute
      $stmt->execute();
      $result = $stmt->get_result()->fetch_array()['ID'];
      return $result;
  }
  function getAllLots($transConn){
    $conn = $transConn ? $transConn : DataBase::getConnection();

      $stmt = $conn->prepare("SELECT * FROM lot");
      // Execute
      $stmt->execute();
      $result = $stmt->get_result();
      return $result;
  }
}

class Charge{

	function getChargeByID(){
		$conn = $transConn ? $transConn : DataBase::getConnection();

    	$stmt = $conn->prepare("SELECT * FROM charge WHERE charge.ID=?");
    	$stmt->bind_param("i", $ID);
    	// Execute
    	$stmt->execute();
    	$result = $stmt->get_result();
    	return $result;
	}
}

class Includes{
	function getChargeForm($pubKey, $chargeAmount){
		return include(FILEROOT.'templates/chargeform.php');
	}
	function getEntryForm(){
		return include(FILEROOT.'templates/entryform.php');
	}
  function getSelectLot(){
    return include(FILEROOT.'templates/selectlot.php');
  }
  function getHead(){
    return include(FILEROOT.'templates/head.php');
  }
  function getAddTicketForm(){
    return include(FILEROOT.'templates/addform.php');
  }
}


class PaymentManager{
    public $customer;
    public $charge;
  function createCustomer($stripeToken, $stripeEmail){
    $this->customer = \Stripe\Customer::create(array(
      'email' => $stripeEmail,
      'source'  => $stripeToken
    ));
  }
  function setCustomer($customerID){
  	$this->customer= \Stripe\Customer::retrieve($customerID);
  }
  function createCharge($amount){
    $this->charge = \Stripe\Charge::create(array(
      'customer' => $this->customer->id,
      'amount'   => $amount,
      'currency' => 'usd',
      "description" => "ParkingTicket",
      "statement_descriptor" => "ParkingTicket",
  ));
  }
  function getCharge(){
    return ($this->charge->amount)/100;
  }
  function createSubscription($amount, $listingID){
  	\Stripe\Plan::create(array(
	  "amount" => $amount,
	  "interval" => "month",
	  "product" => array(
	    "name" => "Parking Space LID: ".$listingID
	  ),
	  "currency" => "usd",
	));
  }
  function subscribeCustomer($customerID, $planNumber ){
  	$subscription = \Stripe\Subscription::create([
	    'customer' => $customerID,
	    'items' => [['plan' => $planNumber]],
	]);
  }

  function subCust2(){
  	$subscription = \Stripe\Subscription::create(array(
  "customer" => "cus_CO4grAtMZGf0TH",
  "plan" => "pro-monthly",
  "application_fee_percent" => 10), 
  	array("stripe_account" => "acct_1AttSDLNA1T2MNtd"));
  }
}

/******** for cURL requests ***********/
function eSendPOST($url, $headersArr, $paramsArr) {
    ilog($headersArr);
    ilog($paramsArr);

    $fields_string = '';
    // Encode the custom POST params and create url string suffix.
    foreach ($paramsArr as $key => $value){
        $fields_string .= urlencode($key) . '='. urlencode($value) . '&';
    }
    //Remove the trailing & if we have any parameters.
    if (sizeof($paramsArr) >= 1) {
        $fields_string = rtrim($fields_string, '&');
    }

    // Open curl connection.
    $ch = curl_init();

    // Set the url, number of POST vars, and POST data.
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, count($paramsArr));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

    // Set Bearer header
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headersArr);

    // setopt true: return result, setopt false: print result
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute POST request.
    $result = curl_exec($ch);

    // Close connection - Important to keep.
    curl_close($ch);

    $decodedResult = json_decode($result);

    return $decodedResult;
}

?>