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
	define('SERVERROOT', 'https://ticketpay.online/');
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
  function issueTicket($licensePlate, $number, $dateTime, $transConn){
    $conn = $transConn ? $transConn : DataBase::getConnection();

      $stmt = $conn->prepare("UPDATE ticket SET licensePlate=?, dateTime = ? WHERE number=?");
      $stmt->bind_param("sss", $licensePlate, $dateTime, $number);
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
      $stmt->bind_param("s",  $number);
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
  function checkPayment($number, $transConn){
    $conn = $transConn ? $transConn : DataBase::getConnection();

      $stmt = $conn->prepare("SELECT * FROM ticket WHERE number=? AND paid=1");
      $stmt->bind_param("s", $number);
      // Execute
      $stmt->execute();
      $result = $stmt->get_result();
      if($stmt->affected_rows){
        return $result;
      }
      
  }
  function getTicketsByLicensePlate($licensePlate, $transConn){
    $conn = $transConn ? $transConn : DataBase::getConnection();

      $stmt = $conn->prepare("SELECT * FROM ticket WHERE ticket.licensePlate=?");
      $stmt->bind_param("s", $licensePlate);
      // Execute
      $stmt->execute();
      $result = $stmt->get_result();
      return $result;
  }
  function getNumberOfTicketsByPlate($licensePlate, $transConn){
    $conn = $transConn ? $transConn : DataBase::getConnection();

      $stmt = $conn->prepare("SELECT COUNT(*) FROM `ticket` WHERE `licensePlate`= ?");
      $stmt->bind_param("s", $licensePlate);
      // Execute
      $stmt->execute();
      $result = $stmt->get_result()->fetch_assoc()['COUNT(*)'];

      if($stmt->affected_rows){
        return $result;
      }
  }
  function bulkAdd($password, $lowest, $highest, $LID, $charge, $transConn){
    if($password = 'Findmeparking@1'){
      $conn = $transConn ? $transConn : DataBase::getConnection();
      for($i = $lowest; $i <= $highest; $i++){
        $stmt = $conn->prepare("INSERT INTO ticket (fk_lot, charge, number)values (?, ?, ?)");
        $stmt->bind_param("iii", $LID, $charge, $i);
        // Execute
        $stmt->execute();
      }
      
      $result = $stmt->get_result();
      if($stmt->affected_rows){
        return $result;
      }
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

	function getChargeByID($ID){
		$conn = $transConn ? $transConn : DataBase::getConnection();

    	$stmt = $conn->prepare("SELECT * FROM charge WHERE charge.ID=?");
    	$stmt->bind_param("i", $ID);
    	// Execute
    	$stmt->execute();
    	$result = $stmt->get_result();
    	return $result;
	}
}
class Warning {
  function __construct($fk_LID, $licensePlate){
    $this->fk_LID = $fk_LID;
    $this->licensePlate = $licensePlate;
  }
  function getChargeByID($ID){
    $conn = $transConn ? $transConn : DataBase::getConnection();

      $stmt = $conn->prepare("SELECT * FROM warning WHERE warning.ID=?");
      $stmt->bind_param("i", $ID);
      // Execute
      $stmt->execute();
      $result = $stmt->get_result();
      return $result;
  }
  function createWarning($dateTime, $transConn){
    $conn = $transConn ? $transConn : DataBase::getConnection();

      $stmt = $conn->prepare("INSERT INTO warning (fk_LotID, licensePlate, dateTime)values (?, ?, ?)");
      $stmt->bind_param("iss", $this->fk_LID, $this->licensePlate, $dateTime);
      // Execute
      $stmt->execute();
      $result = $stmt->get_result();
      return $result;
  }
  function getWarningsByLicensePlate($licensePlate, $transConn){
    $conn = $transConn ? $transConn : DataBase::getConnection();

      $stmt = $conn->prepare("SELECT * FROM warning WHERE warning.licensePlate=?");
      $stmt->bind_param("s", $licensePlate);
      // Execute
      $stmt->execute();
      $result = $stmt->get_result();
      return $result;
  }
  function getNumberOfWarningsByPlate($licensePlate, $transConn){
    $conn = $transConn ? $transConn : DataBase::getConnection();

      $stmt = $conn->prepare("SELECT COUNT(*) FROM `warning` WHERE `licensePlate`= ?");
      $stmt->bind_param("s", $licensePlate);
      // Execute
      $stmt->execute();
      $result = $stmt->get_result()->fetch_assoc()['COUNT(*)'];

      if($stmt->affected_rows){
        return $result;
      }
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
  function getAddWarningForm(){
    return include(FILEROOT.'templates/warnform.php');
  }
  function getCheckForm(){
    return include(FILEROOT.'templates/checkform.php');
  }
  function getWarningRecord($plateNumber){
    return include(FILEROOT.'templates/warningRecord.php');
  }
  function getTicketRecord($plateNumber){
    return include(FILEROOT.'templates/ticketrecord.php');
  }
  function getTicketOrWarn(){
    return include(FILEROOT.'templates/ticketorwarn.php');
  }
  function getDateTime(){
    return include(FILEROOT.'templates/datetimepicker.php');
  }
  function getAddWhiteList(){
    return include(FILEROOT.'templates/whitelist.php');
  }
}

class WhiteList{
  function insertWhiteListItem($plateNumber, $transConn){
    $conn = $transConn ? $transConn : DataBase::getConnection();

      $stmt = $conn->prepare("INSERT INTO whitelist (licensePlate)values (?)");
      $stmt->bind_param("s", $plateNumber);
      // Execute
      $stmt->execute();
      $result = $stmt->get_result();
      if($stmt->affected_rows){
        return true;
      }
  }
  function deleteWhiteListItem($plateNumber, $transConn){
    $conn = $transConn ? $transConn : DataBase::getConnection();

      $stmt = $conn->prepare("DELETE FROM whitelist WHERE ID = ?");
      $stmt->bind_param("i", $plateNumber);
      // Execute
      $stmt->execute();
      $result = $stmt->get_result();
      return $result;
  }
  function getWhiteList($transConn){
    $conn = $transConn ? $transConn : DataBase::getConnection();

      $stmt = $conn->prepare("SELECT * FROM whitelist");

      // Execute
      $stmt->execute();
      $result = $stmt->get_result();
      return $result;
  }
  function checkWhiteList($plateNumber, $transConn){
    $conn = $transConn ? $transConn : DataBase::getConnection();

      $stmt = $conn->prepare("SELECT * FROM whitelist WHERE licensePlate=?");
      $stmt->bind_param("s", $plateNumber);
      // Execute
      $stmt->execute();
      $result = $stmt->get_result();

      if($stmt->affected_rows){
        return $result;
      }
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