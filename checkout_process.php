<?php
/*
  $Id: checkout_process.php 1750 2007-12-21 05:20:28Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce



 

  Released under the GNU General Public License
*/
if(isset($_GET['epayment']) && $_GET['epayment']=="1"){

?>
<html>
<body>
<form action='https://secure.epayment.ro/order/lu.php' method='post' name='frm'>
<?php
foreach ($_POST as $key => $sub) {
  if($key == "ORDER_PNAME" || $key == "ORDER_PCODE" || $key == "ORDER_PRICE" || $key == "ORDER_QTY" || $key == "ORDER_VAT" ){
    foreach ($_POST[$key] as $id => $prod) {
      
      print("<input type='hidden' name='".$key."[]' value='".str_replace('&quot;', '&amp;quot;', $prod)."'>\n");  
    }
  }else{
    print("<input type='hidden' name='".$key."' value='".$_POST[$key]."'>\n");
  }
}
?>
</form>
<script language="JavaScript">
 document.frm.submit();
</script>
</body> 
</html>
<?  
}
  include('includes/application_top.php');
// GOOGLE ANALYTICS  BOF
// Reset the two variables in case we are processing a second order in the same session.

$_SESSION['google_analytics'] = '';
$_SESSION['google_analytics_code'] ='';
$gaq =array();
$google_analytics = '';

// GOOGLE ANALYTICS  EOF
// if the customer is not logged on, redirect them to the login page
  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot(array('mode' => 'SSL', 'page' => FILENAME_CHECKOUT_PAYMENT));
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

// if there is nothing in the customers cart, redirect them to the shopping cart page
  if ($cart->count_contents() < 1) {
    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
  }

// if no shipping method has been selected, redirect the customer to the shipping method selection page
  if (!tep_session_is_registered('shipping') || !tep_session_is_registered('sendto')) {
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
  }

  if ( (tep_not_null(MODULE_PAYMENT_INSTALLED)) && (!tep_session_is_registered('payment')) ) {
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
 }

// avoid hack attempts during the checkout procedure by checking the internal cartID
  if (isset($cart->cartID) && tep_session_is_registered('cartID')) {
    if ($cart->cartID != $cartID) {
      tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
    }
  }

  include(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_PROCESS);

// load selected payment module
  require(DIR_WS_CLASSES . 'payment.php');
  $payment_modules = new payment($payment);

// load the selected shipping module
  require(DIR_WS_CLASSES . 'shipping.php');
  $shipping_modules = new shipping($shipping);

  require(DIR_WS_CLASSES . 'order.php');
  $order = new order;

// Stock Check
  $any_out_of_stock = false;
  if (STOCK_CHECK == 'true') {
    for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
      if (tep_check_stock($order->products[$i]['id'], $order->products[$i]['qty'])) {
        $any_out_of_stock = true;
      }
    }
    // Out of Stock
    if ( (STOCK_ALLOW_CHECKOUT != 'true') && ($any_out_of_stock == true) ) {
      tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
    }
  }

  $payment_modules->update_status();

  if ( ( is_array($payment_modules->modules) && (sizeof($payment_modules->modules) > 1) && !is_object($$payment) ) || (is_object($$payment) && ($$payment->enabled == false)) ) {
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(ERROR_NO_PAYMENT_MODULE_SELECTED), 'SSL'));
  }

  require(DIR_WS_CLASSES . 'order_total.php');
  $order_total_modules = new order_total;

  $order_totals = $order_total_modules->process();

// load the before_process function from the payment modules
  $payment_modules->before_process();

  $sql_data_array = array('customers_id' => $customer_id,
                          'customers_name' => $order->customer['firstname'] . ' ' . $order->customer['lastname'],
                          'customers_company' => $order->customer['company'],
                          'customers_street_address' => $order->customer['street_address'],
                          'customers_suburb' => $order->customer['suburb'],
                          'customers_city' => $order->customer['city'],
                          'customers_postcode' => $order->customer['postcode'], 
                          'customers_state' => $order->customer['state'], 
                          'customers_country' => $order->customer['country']['title'], 
                          'customers_telephone' => $order->customer['telephone'], 
                          'customers_email_address' => $order->customer['email_address'],
                          'customers_address_format_id' => $order->customer['format_id'], 
                          'delivery_name' => trim($order->delivery['firstname'] . ' ' . $order->delivery['lastname']),
                          'delivery_company' => $order->delivery['company'],
                          'delivery_street_address' => $order->delivery['street_address'], 
                          'delivery_suburb' => $order->delivery['suburb'], 
                          'delivery_city' => $order->delivery['city'], 
                          'delivery_postcode' => $order->delivery['postcode'], 
                          'delivery_state' => $order->delivery['state'], 
                          'delivery_country' => $order->delivery['country']['title'], 
                          'delivery_address_format_id' => $order->delivery['format_id'], 
                          'billing_name' => $order->billing['firstname'] . ' ' . $order->billing['lastname'], 
                          'billing_company' => $order->billing['company'],
                          'billing_street_address' => $order->billing['street_address'], 
                          'billing_suburb' => $order->billing['suburb'], 
                          'billing_city' => $order->billing['city'], 
                          'billing_postcode' => $order->billing['postcode'], 
                          'billing_state' => $order->billing['state'], 
                          'billing_country' => $order->billing['country']['title'], 
                          'billing_address_format_id' => $order->billing['format_id'], 
                          'payment_method' => $order->info['payment_method'], 
                          'cc_type' => $order->info['cc_type'], 
                          'cc_owner' => $order->info['cc_owner'], 
                          'cc_number' => $order->info['cc_number'], 
                          'cc_expires' => $order->info['cc_expires'], 
                          'date_purchased' => 'now()', 
                          'orders_status' => $order->info['order_status'], 
                          'currency' => $order->info['currency'], 
                          'order_ip' => $_SERVER["REMOTE_ADDR"], 
                          'currency_value' => $order->info['currency_value']);
  tep_db_perform(TABLE_ORDERS, $sql_data_array);
  $insert_id = tep_db_insert_id();
  for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {
    $sql_data_array = array('orders_id' => $insert_id,
                            'title' => $order_totals[$i]['title'],
                            'text' => $order_totals[$i]['text'],
                            'value' => $order_totals[$i]['value'], 
                            'class' => $order_totals[$i]['code'], 
                            'sort_order' => $order_totals[$i]['sort_order']);
    tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
  }

  $customer_notification = (SEND_EMAILS == 'true') ? '1' : '0';
  $sql_data_array = array('orders_id' => $insert_id, 
                          'orders_status_id' => $order->info['order_status'], 
                          'date_added' => 'now()', 
                          'customer_notified' => $customer_notification,
                          'comments' => $order->info['comments']);
  tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);

// enter info into coupons table
  if (tep_session_is_registered('coupon_id')) {
      $sql_data_array = array('coupon_id' => $coupon_id,
                              'customer_id' => $customer_id,
                              'order_id' => $insert_id,
                              'redeem_date' => 'now()');
      tep_db_perform(TABLE_COUPONS_TO_CUSTOMER, $sql_data_array);
  }
// initialized for the email confirmation
  $products_ordered = '';
  $subtotal = 0;
  $total_tax = 0;

  for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
// Stock Update - Joao Correia
    if (STOCK_LIMITED == 'true') {
      if (DOWNLOAD_ENABLED == 'true') {
        $stock_query_raw = "SELECT products_quantity, pad.products_attributes_filename 
                            FROM " . TABLE_PRODUCTS . " p
                            LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                             ON p.products_id=pa.products_id
                            LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
                             ON pa.products_attributes_id=pad.products_attributes_id
                            WHERE p.products_id = '" . tep_get_prid($order->products[$i]['id']) . "'";
// Will work with only one option for downloadable products
// otherwise, we have to build the query dynamically with a loop
        $products_attributes = $order->products[$i]['attributes'];
        if (is_array($products_attributes)) {
          $stock_query_raw .= " AND pa.options_id = '" . $products_attributes[0]['option_id'] . "' AND pa.options_values_id = '" . $products_attributes[0]['value_id'] . "'";
        }
        $stock_query = tep_db_query($stock_query_raw);
      } else {
        $stock_query = tep_db_query("select products_quantity from " . TABLE_PRODUCTS . " where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
      }
      if (tep_db_num_rows($stock_query) > 0) {
        $stock_values = tep_db_fetch_array($stock_query);
// do not decrement quantities if products_attributes_filename exists
        if ((DOWNLOAD_ENABLED != 'true') || (!$stock_values['products_attributes_filename'])) {
          $stock_left = $stock_values['products_quantity'] - $order->products[$i]['qty'];
        } else {
          $stock_left = $stock_values['products_quantity'];
        }
        tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = '" . $stock_left . "' where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
        if ( ($stock_left < 1) && (STOCK_ALLOW_CHECKOUT == 'false') ) {
          tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '0' where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
        }
      }
    }

// Update products_ordered (for bestsellers list)
    tep_db_query("update " . TABLE_PRODUCTS . " set products_ordered = products_ordered + " . sprintf('%d', $order->products[$i]['qty']) . " where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");

    $cod_unic_query = tep_db_query("select cod_unic from " . TABLE_PRODUCTS . " where products_id = '" . (int)tep_get_prid($order->products[$i]['id']) . "'");
    $cod_unic= tep_db_fetch_array($cod_unic_query);


    $sql_data_array = array('orders_id' => $insert_id, 
                            'products_id' => tep_get_prid($order->products[$i]['id']), 
                            'cod_unic' => $cod_unic['cod_unic'], 
                            'products_model' => $order->products[$i]['model'], 
                            'products_name' => $order->products[$i]['name'], 
                            'products_price' => $order->products[$i]['price'], 
                            'final_price' => $order->products[$i]['final_price'], 
                            'products_tax' => $order->products[$i]['tax'], 
                            'products_quantity' => $order->products[$i]['qty']);
    tep_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array);
    $order_products_id = tep_db_insert_id();

      // GOOGLE ANALYTICS  BOF

      $category  = 'NULL';

      $prod_category = tep_db_query("select c.categories_name as cname from categories_description as c, products_to_categories as p where p.categories_id = c.categories_id and p.products_id = '". $order->products[$i]['id'] ."' limit 1");

      if (tep_db_num_rows($prod_category) > 0) {
          $pc_values = tep_db_fetch_array($prod_category);
          $category = $pc_values['cname'];
      }

      $gaq[$i] = "['_addItem','".$insert_id."','".$cod_unic['cod_unic']."','".$order->products[$i]['name']."','".$category."','".$order->products[$i]['price']."','".$order->products[$i]['qty']."']";
      $google_analytics .= 'UTM:I|'.$insert_id.'|'.$order->products[$i]['model'].'|'.$order->products[$i]['name'].'|'. $category .'|'. $order->products[$i]['price'].'|'.$order->products[$i]['qty']."\n";


      // GOOGLE ANALYTICS  EOF

      $order->products[$i]['cod_unic'] = $cod_unic['cod_unic'];
//------insert customer choosen option to order--------
    $attributes_exist = '0';
    $products_ordered_attributes = '';
    if (isset($order->products[$i]['attributes'])) {
      
      $attributes_exist = '1';
      for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
        if (DOWNLOAD_ENABLED == 'true') {
          $attributes_query = "select popt.products_options_name, poval.products_options_values_name, pa.cod_unic, pa.options_values_price, pa.price_prefix, pad.products_attributes_maxdays, pad.products_attributes_maxcount , pad.products_attributes_filename 
                               from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa 
                               left join " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
                                on pa.products_attributes_id=pad.products_attributes_id
                               where pa.products_id = '" . $order->products[$i]['id'] . "' 
                                and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "' 
                                and pa.options_id = popt.products_options_id 
                                and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "' 
                                and pa.options_values_id = poval.products_options_values_id 
                                and popt.language_id = '" . $languages_id . "' 
                                and poval.language_id = '" . $languages_id . "'";
          $attributes = tep_db_query($attributes_query);
        } else {
          $attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.cod_unic, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.products_id = '" . $order->products[$i]['id'] . "' and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "' and pa.options_values_id = poval.products_options_values_id and popt.language_id = '" . $languages_id . "' and poval.language_id = '" . $languages_id . "'");
        }
        $attributes_values = tep_db_fetch_array($attributes);

        $sql_data_array = array('orders_id' => $insert_id, 
                                'orders_products_id' => $order_products_id, 
                                'cod_unic' => $attributes_values['cod_unic'], 
                                'products_options' => $attributes_values['products_options_name'],
                                'products_options_values' => $attributes_values['products_options_values_name'], 
                                'options_values_price' => $attributes_values['options_values_price'], 
                                'price_prefix' => $attributes_values['price_prefix']);
        
        
        tep_db_perform(TABLE_ORDERS_PRODUCTS_ATTRIBUTES, $sql_data_array);

        if ((DOWNLOAD_ENABLED == 'true') && isset($attributes_values['products_attributes_filename']) && tep_not_null($attributes_values['products_attributes_filename'])) {
          $sql_data_array = array('orders_id' => $insert_id, 
                                  'orders_products_id' => $order_products_id, 
                                  'orders_products_filename' => $attributes_values['products_attributes_filename'], 
                                  'download_maxdays' => $attributes_values['products_attributes_maxdays'], 
                                  'download_count' => $attributes_values['products_attributes_maxcount']);
          tep_db_perform(TABLE_ORDERS_PRODUCTS_DOWNLOAD, $sql_data_array);
        }
        $products_ordered_attributes .= "\n\t" . $attributes_values['products_options_name'] . ' ' . $attributes_values['products_options_values_name'];
      }
    }
//------insert customer choosen option eof ----
    $total_weight += ($order->products[$i]['qty'] * $order->products[$i]['weight']);
    $total_tax += tep_calculate_tax($total_products_price, $products_tax) * $order->products[$i]['qty'];
    $total_cost += $total_products_price;
    
    $cod_unic = isset($order->products[$i]['attributes'])? $attributes_values['cod_unic'] : $order->products[$i]['cod_unic'];
    $products_ordered .= $order->products[$i]['qty'] . ' x ' . $cod_unic . ' - ' . $order->products[$i]['name'] . ' = ' . $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . $products_ordered_attributes . "\n";
  }

    // GOOGLE ANALYTICS  BOF

    // Peter Anderson added the following line to register the session:
    session_register('google_analytics');

    $_SESSION['google_analytics'] = 'UTM:T|'.
        $insert_id .
        '|Web Sales|'.$order->info['total'].'|'.
        $order->info['tax'].'|'.
        $order->info['shipping_cost'].'|'.
        $order->customer['city'].'|'.
        $order->customer['state'].'|'.
        $order->customer['country']['iso_code_2']."\n".$google_analytics;

    $_SESSION['google_analytics'] = "['_addTrans','". $insert_id."','Web Sales','".$order->info['total']."','".$order->info['tax']."','".$order->info['shipping_cost']."','".$order->customer['city']."','".$order->customer['country']['iso_code_2']."','RO']";
    $_SESSION['google_analytics_code'] = $gaq;

    // GOOGLE ANALYTICS  EOF

// lets start with the email confirmation
  $email_order = "Salut " . $order->customer['firstname'] . ",\n\n" . 
                 "S-a inregistrat comanda ta dupa cum urmeaza:" . "\n" .  
                 EMAIL_SEPARATOR . "\n" . 
                 EMAIL_TEXT_ORDER_NUMBER . ' ' . $insert_id . "\n" .
                 EMAIL_TEXT_INVOICE_URL . ' ' . tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $insert_id, 'SSL', false) . "\n" .
                 EMAIL_TEXT_DATE_ORDERED . ' ' . strftime(DATE_FORMAT_LONG) . "\n\n";
  if ($order->info['comments']) {
    $email_order .= tep_db_output($order->info['comments']) . "\n\n";
  }
  $email_order .= EMAIL_TEXT_PRODUCTS . "\n" . 
                  EMAIL_SEPARATOR . "\n" . 
                  $products_ordered . 
                  EMAIL_SEPARATOR . "\n";

  for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {
    $email_order .= strip_tags($order_totals[$i]['title']) . ' ' . strip_tags($order_totals[$i]['text']) . "\n";
  }

  if ($order->content_type != 'virtual') {
    $email_order .= "\n" . EMAIL_TEXT_DELIVERY_ADDRESS . "\n" . 
                    EMAIL_SEPARATOR . "\n" .
                    tep_address_label($customer_id, $sendto, 0, '', "\n") . "\n";
  }

  $email_order .= "\n" . EMAIL_TEXT_BILLING_ADDRESS . "\n" .
                  EMAIL_SEPARATOR . "\n" .
                  tep_address_label($customer_id, $billto, 0, '', "\n") . "\n\n";
  if (is_object($$payment)) {
    $email_order .= EMAIL_TEXT_PAYMENT_METHOD . "\n" . 
                    EMAIL_SEPARATOR . "\n\n";
    $payment_class = $$payment;
    $email_order .= $order->info['payment_method'] . "\n\n";
    if ($payment_class->email_footer) { 
      $email_order .= $payment_class->email_footer . "\n\n";
    }
    $email_order .= "Iti multumim ca ai folosit serviciile noastre."."\n"."Echipa ToolsZone.ro iti doreste o zi buna.";
  }
  tep_mail($order->customer['firstname'] . ' ' . $order->customer['lastname'], $order->customer['email_address'], EMAIL_TEXT_SUBJECT, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

// send emails to other people
  if (SEND_EXTRA_ORDER_EMAILS_TO != '') {
    tep_mail('', SEND_EXTRA_ORDER_EMAILS_TO, EMAIL_TEXT_SUBJECT, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
  }

// load the after_process function from the payment modules
  $payment_modules->after_process();

  $cart->reset(true);

// unregister session variables used during checkout
  tep_session_unregister('sendto');
  tep_session_unregister('billto');
  tep_session_unregister('shipping');
  tep_session_unregister('payment');
  tep_session_unregister('comments');
// unregister any coupons
  tep_session_unregister('coupon_code');
  tep_session_unregister('coupon_amount');
  tep_session_unregister('coupon_type');
  tep_session_unregister('coupon_id');
  
  tep_redirect(tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL'));

  require(DIR_WS_INCLUDES . 'application_bottom.php');
  
?>