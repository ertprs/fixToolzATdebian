<?php
/*
  $Id: epayment.php,v 2.1 2004/10/27 16:05:14 hpdl Exp $

  ePayment Module for osCommerce ( http://www.oscommerce.com)

  Copyright (c) 2004 GeCAD ePayment International

*/
  
	//$myKey 			= "i5!6m2&c8|8TX5q97+~r";						//set the secret key
	//$myLiveUpdate 	= new LiveUpdate($myKey);				//instantiate the class  
  
  class epayment {
	var $code, $title, $description, $enabled;
	
// class constructor
    function epayment() {
      global $order;

      $this->code = 'epayment';
      $this->title = MODULE_PAYMENT_EPAYMENT_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_EPAYMENT_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_PAYMENT_EPAYMENT_SORT_ORDER;
      $this->enabled = ((MODULE_PAYMENT_EPAYMENT_STATUS == 'True') ? true : false);

      if ((int)MODULE_PAYMENT_EPAYMENT_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_EPAYMENT_ORDER_STATUS_ID;
      }

      if (is_object($order)) $this->update_status();

      //$this->form_action_url = 'https://secure.epayment.ro/order/lu.php';
      $this->form_action_url = 'http://www.toolszone.ro/checkout_process.php?epayment=1';
    }

// class methods
    function update_status() {
      global $order;

      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_EPAYMENT_ZONE > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_EPAYMENT_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
        while ($check = tep_db_fetch_array($check_query)) {
          if ($check['zone_id'] < 1) {
            $check_flag = true;
            break;
          } elseif ($check['zone_id'] == $order->billing['zone_id']) {
            $check_flag = true;
            break;
          }
        }

        if ($check_flag == false) {
          $this->enabled = false;
        }
      }
    }

    function javascript_validation() {
        return false;
    }

    function selection() {
		  return array('id' => $this->code,
                   'module' => $this->title,
				   'title' => $this->description);
    }

    function pre_confirmation_check() {
		return false;
	}


    function confirmation() {

      $confirmation = array('title' => '<b>'.$this->title.'</b>',
                            'fields' => array(array('title' => MODULE_PAYMENT_EPAYMENT_TEXT_DESCRIPTION)));

      return $confirmation;
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
	
    function process_button() {
	global $order, $currencies, $easy_discount;
	$string = "";
	$order_ref = "0";
	$order_date = date("Y-m-d G:i:s");
	
	$string .= strlen(MODULE_PAYMENT_EPAYMENT_MERCHANT).MODULE_PAYMENT_EPAYMENT_MERCHANT;
	$string .= strlen($order_ref).$order_ref;
	$string .= strlen($order_date).$order_date;
	
    $process_button_string = tep_draw_hidden_field('MERCHANT', MODULE_PAYMENT_EPAYMENT_MERCHANT).
										tep_draw_hidden_field('ORDER_REF',htmlspecialchars($order_ref)).
										tep_draw_hidden_field('ORDER_DATE', htmlspecialchars($order_date));
		
		for($i = 0;$i < sizeof($order->products); $i++){
			$process_button_string .= tep_draw_hidden_field('ORDER_PNAME[]', htmlspecialchars($order->products[$i]['name']));
			$string .= strlen(StripSlashes($order->products[$i]['name'])).StripSlashes($order->products[$i]['name']);
		}
		
		for($i = 0;$i < sizeof($order->products); $i++){
			$process_button_string .= tep_draw_hidden_field('ORDER_PCODE[]', htmlspecialchars($order->products[$i]['id']));
			$string .= strlen(StripSlashes($order->products[$i]['id'])).StripSlashes($order->products[$i]['id']);
		}
		
		for ($i = 0; $i<sizeof($order->products); $i++) {
			$PInfo = '';
			for ($j = 0; $j < sizeof($order->products[$i]['attributes']); $j++) {				
				if ($j != 0) $PInfo .= ", ";
				$PInfo .= $order->products[$i]['attributes'][$j]['option'];
				$PInfo .= ": ";				
				$PInfo .= $order->products[$i]['attributes'][$j]['value'];								
			}
			$PInfo = trim($PInfo);
			$process_button_string .= tep_draw_hidden_field('ORDER_PINFO[]', htmlspecialchars($PInfo));
			$string .= strlen(StripSlashes($PInfo)).StripSlashes($PInfo);
		}
		
		
		for($i = 0;$i < sizeof($order->products); $i++){
			$price = $order->products[$i]['price']*$currencies->get_value(MODULE_PAYMENT_EPAYMENT_CURRENCY);
			$process_button_string .= tep_draw_hidden_field('ORDER_PRICE[]', htmlspecialchars($price));
			$string .= strlen(StripSlashes($price)).StripSlashes($price);
		}
		
		for($i = 0;$i < sizeof($order->products); $i++){
			$process_button_string .= tep_draw_hidden_field('ORDER_QTY[]', htmlspecialchars($order->products[$i]['qty']));
			$string .= strlen(StripSlashes($order->products[$i]['qty'])).StripSlashes($order->products[$i]['qty']);
		}
		
		for($i = 0;$i < sizeof($order->products); $i++){
			$tax_percent	= StripSlashes($order->products[$i]['tax']);
			if($tax_percent == "") $tax_percent = 0;
			$process_button_string .= tep_draw_hidden_field('ORDER_VAT[]', htmlspecialchars($tax_percent));
			$string .= strlen($tax_percent).$tax_percent;
		}
		
		$shipping_price = $order->info["shipping_cost"]*$currencies->get_value(MODULE_PAYMENT_EPAYMENT_CURRENCY);
		$process_button_string .= tep_draw_hidden_field('ORDER_SHIPPING', htmlspecialchars($shipping_price));
		$string .= strlen($shipping_price).$shipping_price;
		
		$process_button_string .= tep_draw_hidden_field('PRICES_CURRENCY', strtoupper(MODULE_PAYMENT_EPAYMENT_CURRENCY));
		$string .= strlen(MODULE_PAYMENT_EPAYMENT_CURRENCY).MODULE_PAYMENT_EPAYMENT_CURRENCY;
		
		$process_button_string .= tep_draw_hidden_field('DESTINATION_CITY', htmlspecialchars($order->delivery["city"]));
		$string .= strlen(StripSlashes($order->delivery["city"])).StripSlashes($order->delivery["city"]);
		
		$process_button_string .= tep_draw_hidden_field('DESTINATION_STATE', htmlspecialchars(strtolower($order->delivery["country"]["iso_code_2"])));
		$string .= strlen(StripSlashes(strtolower($order->delivery["country"]["iso_code_2"]))).StripSlashes(strtolower($order->delivery["country"]["iso_code_2"]));
		
		$process_button_string .= tep_draw_hidden_field('DESTINATION_COUNTRY', htmlspecialchars(strtolower($order->delivery["country"]["iso_code_2"])));
		$string .= strlen(StripSlashes(strtolower($order->delivery["country"]["iso_code_2"]))).StripSlashes(strtolower($order->delivery["country"]["iso_code_2"]));
		
		// Uncomment for DEBUG purposes (you will have to contact first the ePayment Technical Department
		$process_button_string .= tep_draw_hidden_field('DEBUG', "1");
    $process_button_string .= tep_draw_hidden_field('TESTORDER"', "TRUE");
		
		// HASH string
		//echo ($string);
		$hash_string = $this->hmac('i5!6m2&c8|8TX5q97+~r', $string);
		$process_button_string .= tep_draw_hidden_field('ORDER_HASH', $hash_string);
		
		/* enable this option if you want to skip all forms generated by ePayment*/
		$process_button_string .= tep_draw_hidden_field('AUTOMODE', 1);
		
		/* interface language */
		$process_button_string .= tep_draw_hidden_field('LANGUAGE', strtolower(MODULE_PAYMENT_EPAYMENT_LANGUAGE));
		
		/* Forms AUTOCOMPLETE */
		$process_button_string .= tep_draw_hidden_field('BILL_FNAME', htmlspecialchars($order->customer["firstname"]));
		$process_button_string .= tep_draw_hidden_field('BILL_LNAME', htmlspecialchars($order->customer["lastname"]));
		$process_button_string .= tep_draw_hidden_field('BILL_COMPANY', htmlspecialchars($order->customer["company"]));
		$process_button_string .= tep_draw_hidden_field('BILL_ADDRESS', htmlspecialchars($order->customer["street_address"]));
		$process_button_string .= tep_draw_hidden_field('BILL_CITY', htmlspecialchars($order->customer["city"]));
		$process_button_string .= tep_draw_hidden_field('BILL_STATE', htmlspecialchars($order->customer["state"]));
		$process_button_string .= tep_draw_hidden_field('BILL_COUNTRYCODE', htmlspecialchars($order->customer["country"]["iso_code_2"]));
		$process_button_string .= tep_draw_hidden_field('BILL_PHONE', htmlspecialchars($order->customer["telephone"]));
		$process_button_string .= tep_draw_hidden_field('BILL_EMAIL', htmlspecialchars($order->customer["email_address"]));
		
		$process_button_string .= tep_draw_hidden_field('DELIVERY_FNAME', htmlspecialchars($order->delivery["firstname"]));
		$process_button_string .= tep_draw_hidden_field('DELIVERY_LNAME', htmlspecialchars($order->delivery["lastname"]));
		$process_button_string .= tep_draw_hidden_field('DELIVERY_COMPANY', htmlspecialchars($order->delivery["company"]));
		$process_button_string .= tep_draw_hidden_field('DELIVERY_ADDRESS', htmlspecialchars($order->delivery["street_address"]));
		$process_button_string .= tep_draw_hidden_field('DELIVERY_ZIPCODE', htmlspecialchars($order->delivery["postcode"]));
		$process_button_string .= tep_draw_hidden_field('DELIVERY_CITY', htmlspecialchars($order->delivery["city"]));
		$process_button_string .= tep_draw_hidden_field('DELIVERY_STATE', htmlspecialchars($order->delivery["state"]));
		$process_button_string .= tep_draw_hidden_field('DELIVERY_COUNTRYCODE', htmlspecialchars($order->delivery["country"]["iso_code_2"]));
		
		$myKey 			= "i5!6m2&c8|8TX5q97+~r";
	  $myLiveUpdate 	= new LiveUpdate($myKey);	
	  
	  $myId			= "VIRTTLS";							//set your ePayment merchant id
	  $myLiveUpdate->setMerchant($myId);		

		//order date
	  $myLiveUpdate->setOrderDate(htmlspecialchars($order_date));


    $PName		= array();										//products name array
    for($i = 0;$i < sizeof($order->products); $i++){
			$PName[$i] = htmlspecialchars($order->products[$i]['name']);
		}
    $myLiveUpdate->setOrderPName($PName);
    
    $PCode		= array();										//products code array
		for($i = 0;$i < sizeof($order->products); $i++){
			$PCode[$i] = htmlspecialchars($order->products[$i]['id']);
		}    
    $myLiveUpdate->setOrderPCode($PCode);
    
    $PPrice		= array();										//products price array
		for($i = 0;$i < sizeof($order->products); $i++){
			$price = $order->products[$i]['price']*$currencies->get_value(MODULE_PAYMENT_EPAYMENT_CURRENCY);
			$PPrice[$i]	= htmlspecialchars($price);
		}    
    $myLiveUpdate->setOrderPrice($PPrice);
       
    $PQTY		= array();										//products qty array
		for($i = 0;$i < sizeof($order->products); $i++){
			$PQTY[$i]		= htmlspecialchars($order->products[$i]['qty']);
		}    
    $myLiveUpdate->setOrderQTY($PQTY);

    $PVAT		= array();										//products vat array
		for($i = 0;$i < sizeof($order->products); $i++){
			$PVAT[$i]		= 19;
		}    
    if(!$myLiveUpdate->setOrderVAT($PVAT)) echo "fail";
    
    if ($easy_discount->count() > 0) {
    	$myLiveUpdate->setDiscount(round($easy_discount->total(),2));
    }
    
    $myLiveUpdate->setPricesCurrency('RON');
        
    $myLiveUpdate->setDestinationState(htmlspecialchars($order->delivery["country"]["iso_code_2"]));
    
    $myLiveUpdate->setDestinationCountry('RO');

    $billing = array(
      "billFName"				=> htmlspecialchars($order->customer["firstname"]),
      "billLName"				=> htmlspecialchars($order->customer["lastname"]),
      "billCompany"			=> '',
      "billFiscalCode" 		=> '',
      "billRegNumber" 		=> '',
      "billBank" 				=> '',
      "billBankAccount" 		=> '',
      "billEmail" 			=> htmlspecialchars($order->customer["email_address"]),
      "billPhone" 			=> htmlspecialchars($order->customer["telephone"]),
      "billAddress1"			=> htmlspecialchars($order->delivery["street_address"]),
      "billState"				=> htmlspecialchars($order->delivery["country"]["title"]),
      "billCountryCode"		=> 'RO'
    );
    $myLiveUpdate->setBilling($billing);


		//$myLiveUpdate->setTestMode(true);
		
    return $myLiveUpdate->getLiveUpdateHTML();
      
    }

    function before_process() {
      return false;
    }

    function after_process() {
      return false;
    }

    function get_error() {
		return false;
    }

    function check() {
		   if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_EPAYMENT_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }
	
	function tep_get_currencies(){
		$html_output	 = "<select name='configuration[MODULE_PAYMENT_EPAYMENT_CURRENCY]'>";
		
		$query	= tep_db_query("SELECT code FROM ".TABLE_CURRENCIES);
		while ($currencies = tep_db_fetch_array($query)){
			$html_output	.= "<option value='".$currencies['code']."'";
			if(MODULE_PAYMENT_EPAYMENT_CURRENCY == $currencies['code']) $html_output .= " selected";
			$html_output	.= ">".$currencies['code']."</option>";
		}
		$html_output	.= "</select>";
		return $html_output;
	}
	
	function tep_get_languages(){
		$html_output	 = "<select name='configuration[MODULE_PAYMENT_EPAYMENT_LANGUAGE]'>";
		$html_output	.= "<option value=\"ro\"";
			if(MODULE_PAYMENT_EPAYMENT_LANGUAGE == 'ro') $html_output .= " selected";
		$html_output	.= ">Romanian</option>";
		$html_output	.= "<option value=\"en\"";
			if(MODULE_PAYMENT_EPAYMENT_LANGUAGE == 'en') $html_output .= " selected";
		$html_output	.= ">English</option>";
		$html_output	.= "</select>";
		return $html_output;
	}
	
    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable ePayment Module', 'MODULE_PAYMENT_EPAYMENT_STATUS', 'True', 'Do you want to accept ePayment payments?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Merchant ID', 'MODULE_PAYMENT_EPAYMENT_MERCHANT', '0', 'Your ePayment Merchant ID', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Secret Key', 'MODULE_PAYMENT_EPAYMENT_KEY', '0', 'Your ePayment Secret Key.', '6', '0', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Prices Currency', 'MODULE_PAYMENT_EPAYMENT_CURRENCY', 'ROL', 'The prices currency used for ePayment', '6', '0', 'epayment::tep_get_currencies(', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Interface language', 'MODULE_PAYMENT_EPAYMENT_LANGUAGE', 'ro', 'The interface language.', '6', '0', 'epayment::tep_get_languages(', now())");
	  
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_EPAYMENT_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'MODULE_PAYMENT_EPAYMENT_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', '6', '2', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Order Status', 'MODULE_PAYMENT_EPAYMENT_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value', '6', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
	  
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_EPAYMENT_STATUS', 'MODULE_PAYMENT_EPAYMENT_MERCHANT', 'MODULE_PAYMENT_EPAYMENT_KEY', 'MODULE_PAYMENT_EPAYMENT_CURRENCY', 'MODULE_PAYMENT_EPAYMENT_LANGUAGE', 'MODULE_PAYMENT_EPAYMENT_SORT_ORDER', 'MODULE_PAYMENT_EPAYMENT_ZONE', 'MODULE_PAYMENT_EPAYMENT_ORDER_STATUS_ID');
    }
  }
?>