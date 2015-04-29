<?php
/* Require database function files */
require_once(dirname(__FILE__)."/includes/functions/database.php");
require_once(dirname(__FILE__)."/admin/includes/configure.php");
require_once(dirname(__FILE__)."/admin/includes/database_tables.php");

/* Internet Payment Notification */
$db_link	= NULL;
tep_db_connect();
$sql 		= " SELECT * FROM ". TABLE_CONFIGURATION ." WHERE configuration_key = 'MODULE_PAYMENT_EPAYMENT_KEY' ";
$result		= tep_db_query($sql);
$result 	= tep_db_fetch_array($result);
$pass 		= $result['configuration_value'];	/* pass to compute HASH */
$result		= ""; 								/* string for compute HASH for received data */
$return		= ""; 								/* string to compute HASH for return result */
$signature	= $_POST["HASH"];					/* HASH received */
$body		= "";

/* read info received */
ob_start();
while(list($key, $val) = each($_POST)){
	$$key=$val;

	/* get values */
	if($key != "HASH"){

		if(is_array($val)) $result .= ArrayExpand($val);
		else{
			$size		= strlen(StripSlashes($val));
			$result	.= $size.StripSlashes($val);
		}

	}

}
$body = ob_get_contents();
ob_end_flush();

$date_return = date("YmdGis");

$return = strlen($_POST["IPN_PID"][0]).$_POST["IPN_PID"][0].strlen($_POST["IPN_PNAME"][0]).$_POST["IPN_PNAME"][0];
$return .= strlen($_POST["IPN_DATE"]).$_POST["IPN_DATE"].strlen($date_return).$date_return;

function ArrayExpand($array){
	$retval = "";
	for($i = 0; $i < sizeof($array); $i++){
		$size		= strlen(StripSlashes($array[$i]));
		$retval	.= $size.StripSlashes($array[$i]);
	}

	return $retval;
}

function hmac ($key, $data){
   $b = 64; // byte length for md5
   if (strlen($key) > $b) {
       $key = pack("H*",md5($key));
   }
   $key  = str_pad($key, $b, chr(0x00));
   $ipad = str_pad('', $b, chr(0x36));
   $opad = str_pad('', $b, chr(0x5c));
   $k_ipad = $key ^ $ipad ;
   $k_opad = $key ^ $opad;
   return md5($k_opad  . pack("H*",md5($k_ipad . $data)));
}

$hash =  hmac($pass, $result); /* HASH for data received */

$body .= $result."\r\n\r\nHash: ".$hash."\r\n\r\nSignature: ".$signature."\r\n\r\nReturnSTR: ".$return;

if($hash == $signature){
	echo "Verified OK!";

    /* ePayment response */
    $result_hash =  hmac($pass, $return);
	echo "<EPAYMENT>".$date_return."|".$result_hash."</EPAYMENT>";

    /* Begin automated procedures (START YOUR CODE)*/
	
	/* Update orders table and setting the order status */
	
	/* Connect to database server */
	$db_link = NULL;
	//$db_link = tep_db_connect();
	tep_db_connect();
	
	/* Get order reference */
	$order_no = $_POST["REFNOEXT"];
	
	/* Build query to update command status */

	$sql = " UPDATE " . TABLE_ORDERS . " SET orders_status = 2 WHERE orders_id = $order_no ";
	
	/* Run query */
	tep_db_query($sql);
	
	

}else{
    /* warning email */
	mail("webmaster@gecad.ro","BAD IPN Signature", $body,"");
}
?>