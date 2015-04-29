<?php
/*
  $Id: create_account.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

// needs to be included earlier to set the success message in the messageStack
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ACCOUNT);

  $process = false;
  if (isset($_POST['action']) && ($_POST['action'] == 'process')) {
    $process = true;

    if (ACCOUNT_GENDER == 'true') {
      if (isset($_POST['gender'])) {
        $gender = tep_db_prepare_input($_POST['gender']);
      } else {
        $gender = false;
      }
    }
    $firstname = tep_db_prepare_input($_POST['firstname']);
    $lastname = tep_db_prepare_input($_POST['lastname']);
    if (ACCOUNT_DOB == 'true') $dob = tep_db_prepare_input($_POST['dob']);
    //$cnp = tep_db_prepare_input($_POST['cnp']);
    //$ci = tep_db_prepare_input($_POST['ci']);
    $email_address = tep_db_prepare_input($_POST['email_address']);
    if (ACCOUNT_COMPANY == 'true') $company = tep_db_prepare_input($_POST['company']);
    if (ACCOUNT_COMPANY == 'true') $reg_com = tep_db_prepare_input($_POST['reg_com']);
    if (ACCOUNT_COMPANY == 'true') $cui = tep_db_prepare_input($_POST['cui']);
    if (ACCOUNT_COMPANY == 'true') $iban = tep_db_prepare_input($_POST['iban']);
    $street_address = tep_db_prepare_input($_POST['street_address']);
    if (ACCOUNT_SUBURB == 'true') $suburb = tep_db_prepare_input($_POST['suburb']);
// HERE!
//    $postcode = tep_db_prepare_input($_POST['postcode']);
    $city = tep_db_prepare_input($_POST['city']);
    if (ACCOUNT_STATE == 'true') {
      $state = tep_db_prepare_input($_POST['state']);
      if (isset($_POST['zone_id'])) {
        $zone_id = tep_db_prepare_input($_POST['zone_id']);
      } else {
        $zone_id = false;
      }
    }
    $country = tep_db_prepare_input($_POST['country']);
    $telephone = tep_db_prepare_input($_POST['telephone']);
    $fax = tep_db_prepare_input($_POST['fax']);
    if (isset($_POST['newsletter'])) {
      $newsletter = tep_db_prepare_input($_POST['newsletter']);
    } else {
      $newsletter = false;
    }
    $password = tep_db_prepare_input($_POST['password']);
    $confirmation = tep_db_prepare_input($_POST['confirmation']);

    $error = false;

    if (ACCOUNT_GENDER == 'true') {
      if ( ($gender != 'm') && ($gender != 'f') ) {
        $error = true;

        $messageStack->add('create_account', ENTRY_GENDER_ERROR);
      }
    }

    if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_FIRST_NAME_ERROR);
    }
    /*
	if (strlen($cnp) < 13 || strlen($cnp) > 13 || !valid_cnp($cnp)) {
      $error = true;

      $messageStack->add('create_account', "CNP-ul nu este valid");
    }elseif(!major_cnp($cnp)){
      $error = true;

      $messageStack->add('create_account', "Nu aveti 18 ani");
  	
    }
    
    
    if (strlen($ci) < 8) {
      $error = true;

      $messageStack->add('create_account', "Nu ati introdus Seria si Numarul CI");
    }
*/
    if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_LAST_NAME_ERROR);
    }

    if (ACCOUNT_DOB == 'true') {
      if (checkdate(substr(tep_date_raw($dob), 4, 2), substr(tep_date_raw($dob), 6, 2), substr(tep_date_raw($dob), 0, 4)) == false) {
        $error = true;

        $messageStack->add('create_account', ENTRY_DATE_OF_BIRTH_ERROR);
      }
    }

    if (strlen($email_address) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_ERROR);
    } elseif (tep_validate_email($email_address) == false) {
      $error = true;

      $messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
    } else {
      $check_email_query = tep_db_query("select count(*) as total from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "'");
      $check_email = tep_db_fetch_array($check_email_query);
      if ($check_email['total'] > 0) {
        $error = true;

        $messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_ERROR_EXISTS);
      }
    }

    if (strlen($street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_STREET_ADDRESS_ERROR);
    }

//    if (strlen($postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
//      $error = true;
//
//      $messageStack->add('create_account', ENTRY_POST_CODE_ERROR);
//    }
//
   if (strlen($city) < ENTRY_CITY_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_CITY_ERROR);
    }

    if (is_numeric($country) == false) {
      $error = true;

      $messageStack->add('create_account', ENTRY_COUNTRY_ERROR);
    }

    if (ACCOUNT_STATE == 'true') {
      $zone_id = 0;
      $check_query = tep_db_query("select count(*) as total from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "'");
      $check = tep_db_fetch_array($check_query);
      $entry_state_has_zones = ($check['total'] > 0);
      if ($entry_state_has_zones == true) {
        $zone_query = tep_db_query("select distinct zone_id from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' and (zone_name = '" . tep_db_input($state) . "' or zone_code = '" . tep_db_input($state) . "')");
        if (tep_db_num_rows($zone_query) == 1) {
          $zone = tep_db_fetch_array($zone_query);
          $zone_id = $zone['zone_id'];
        } else {
          $error = true;

          $messageStack->add('create_account', ENTRY_STATE_ERROR_SELECT);
        }
      } else {
        if (strlen($state) < ENTRY_STATE_MIN_LENGTH) {
          $error = true;

          $messageStack->add('create_account', ENTRY_STATE_ERROR);
        }
      }
    }

    if (strlen($telephone) < ENTRY_TELEPHONE_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_TELEPHONE_NUMBER_ERROR);
    }


    if (strlen($password) < ENTRY_PASSWORD_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_PASSWORD_ERROR);
    } elseif ($password != $confirmation) {
      $error = true;

      $messageStack->add('create_account', ENTRY_PASSWORD_ERROR_NOT_MATCHING);
    }

    if ($error == false) {
      
      $ref = isset($_POST['referrer'])?tep_db_prepare_input($_POST['referrer']):'';
      
      $sql_data_array = array('customers_firstname' => $firstname,
                              'customers_lastname' => $lastname,
                              //'customers_cnp' => $cnp,
                              //'customers_ci' => $ci,
                              'customers_email_address' => $email_address,
                              'customers_telephone' => $telephone,
                              'customers_fax' => $fax,
                              'customers_newsletter' => $newsletter,
                              'customers_password_real' => $password,
                              'customers_password' => tep_encrypt_password($password),
                              'customers_referrer' => $ref);

      if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $gender;
      if (ACCOUNT_DOB == 'true') $sql_data_array['customers_dob'] = tep_date_raw($dob);

      tep_db_perform(TABLE_CUSTOMERS, $sql_data_array);

      $customer_id = tep_db_insert_id();

      $sql_data_array = array('customers_id' => $customer_id,
                              'entry_firstname' => $firstname,
                              'entry_lastname' => $lastname,
                              'entry_street_address' => $street_address,
//                              'entry_postcode' => $postcode,
                              'entry_city' => $city,
                              'entry_country_id' => $country);

      if (ACCOUNT_GENDER == 'true') $sql_data_array['entry_gender'] = $gender;
      if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $company;
      if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_reg_com'] = $reg_com;
      if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_cui'] = $cui;
      if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_iban'] = $iban;
      if (ACCOUNT_SUBURB == 'true') $sql_data_array['entry_suburb'] = $suburb;
      if (ACCOUNT_STATE == 'true') {
        if ($zone_id > 0) {
          $sql_data_array['entry_zone_id'] = $zone_id;
          $sql_data_array['entry_state'] = '';
        } else {
          $sql_data_array['entry_zone_id'] = '0';
          $sql_data_array['entry_state'] = $state;
        }
      }

      tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);

      $address_id = tep_db_insert_id();

      tep_db_query("update " . TABLE_CUSTOMERS . " set customers_default_address_id = '" . (int)$address_id . "' where customers_id = '" . (int)$customer_id . "'");

      tep_db_query("insert into " . TABLE_CUSTOMERS_INFO . " (customers_info_id, customers_info_number_of_logons, customers_info_date_account_created, customers_info_ip) values ('" . (int)$customer_id . "', '0', now(),'".$_SERVER["REMOTE_ADDR"]."')");

      if (SESSION_RECREATE == 'True') {
        tep_session_recreate();
      }

      $customer_first_name = $firstname;
      $customer_default_address_id = $address_id;
      $customer_country_id = $country;
      $customer_zone_id = $zone_id;
      tep_session_register('customer_id');
      tep_session_register('customer_first_name');
      tep_session_register('customer_default_address_id');
      tep_session_register('customer_country_id');
      tep_session_register('customer_zone_id');

// restore cart contents
      $cart->restore_contents();

// build the message content
      $name = $firstname . ' ' . $lastname;

      if (ACCOUNT_GENDER == 'true') {
         if ($gender == 'm') {
           $email_text = sprintf(EMAIL_GREET_MR, $lastname);
         } else {
           $email_text = sprintf(EMAIL_GREET_MS, $lastname);
         }
      } else {
        $email_text = sprintf(EMAIL_GREET_NONE, $firstname);
      }

      $email_text .= EMAIL_WELCOME . EMAIL_TEXT . EMAIL_CONTACT . EMAIL_WARNING;
      tep_mail($name, $email_address, EMAIL_SUBJECT, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

      tep_redirect(tep_href_link(FILENAME_CREATE_ACCOUNT_SUCCESS, '', 'SSL'));
    }
  }

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo HEADING_TITLE." - ".TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<?php require('includes/form_check.js.php'); ?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" onLoad="MM_preloadImages('<?echo $cur_page=="1" ? '' : 'images/menu/menu_01_over.gif';?>','<?echo $cur_page=="2" ? '' : 'images/menu/menu_02_over.gif';?>','<?echo $cur_page=="3" ? '' : 'images/menu/menu_03_over.gif';?>','<?echo $cur_page=="4" ? '' : 'images/menu/menu_04_over.gif';?>','<?echo $cur_page=="5" ? '' : 'images/menu/menu_05_over.gif';?>','<?echo $cur_page=="6" ? '' : 'images/menu/menu_06_over.gif';?>')">
<div id="wrapper"><!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td><td class="spacingTable">&nbsp;&nbsp;</td>
<!-- body_text //-->
    <td width="100%" valign="top"><?php echo tep_draw_form('create_account', tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'), 'post', 'onSubmit="return check_form(create_account);"') . tep_draw_hidden_field('action', 'process'); ?>
    <?=isset($_GET['ref'])?tep_draw_hidden_field('referrer',$_GET['ref'] ):'';?>
    <?=isset($_POST['referrer'])?tep_draw_hidden_field('referrer',$_POST['referrer'] ):'';?>
    <table border="0" width="100%" cellspacing="0" cellpadding="0"><tr><td>
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td height="32" valign="top" width="8"><img src="images/infobox/corner_left_top.gif" alt="" border="0" height="32" width="8"></td>
		<td colspan="2" class="infoBoxHeading2" height="32" valign="top" align="left">&nbsp;&nbsp;Cont nou</td>
		<td height="32" valign="top" width="8"><img src="images/infobox/corner_right_top.gif" alt="" border="0" height="32" width="8"></td>
      </tr>
      <tr>
      	<td class="boxBgLeft" height="5">&nbsp;</td><td width="10"></td>
        <td class="smallText"><br><?php echo sprintf(TEXT_ORIGIN_LOGIN, tep_href_link(FILENAME_LOGIN, tep_get_all_get_params(), 'SSL')); ?></td>
      	<td class="boxBgRight" height="5">&nbsp;</td>
      </tr>
<?php
  if ($messageStack->size('create_account') > 0) {
?>
      <tr>
      	<td class="boxBgLeft" height="5">&nbsp;</td>
        <td colspan="2" height="20" align="center"><div class="boxTextLine" style="width:448" ></div></td>
        <td class="boxBgRight" height="5">&nbsp;</td>
      </tr>
      <tr>
      	<td class="boxBgLeft" height="5">&nbsp;</td><td width="10"></td>
        <td><?php echo $messageStack->output('create_account'); ?></td>
        <td class="boxBgRight" height="5">&nbsp;</td>
      </tr>
<?php
  }
?>
      <tr>
      	<td class="boxBgLeft" height="5">&nbsp;</td>
        <td colspan="2" height="20" align="center"><div class="boxTextLine" style="width:448" ></div></td>
        <td class="boxBgRight" height="5">&nbsp;</td>
      </tr>
      <tr>
      	<td class="boxBgLeft" height="5">&nbsp;</td><td width="10"></td>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="main"><b><?php echo CATEGORY_PERSONAL; ?></b></td>
           <td class="inputRequirement" align="right"><?php echo FORM_REQUIRED_INFORMATION; ?></td>
          </tr>
        </table></td>
        <td class="boxBgRight" height="5">&nbsp;</td>
      </tr>
      <tr>
      	<td class="boxBgLeft" height="5">&nbsp;</td><td width="10"></td>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" cellspacing="2" cellpadding="2">
<?php
  if (ACCOUNT_GENDER == 'true') {
?>
              <tr>
                <td class="main"><?php echo ENTRY_GENDER; ?></td>
                <td class="main"><?php echo tep_draw_radio_field('gender', 'm') . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('gender', 'f') . '&nbsp;&nbsp;' . FEMALE . '&nbsp;' . (tep_not_null(ENTRY_GENDER_TEXT) ? '<span class="inputRequirement">' . ENTRY_GENDER_TEXT . '</span>': ''); ?></td>
              </tr>
<?php
  }
?>
              <tr>
                <td class="main"><?php echo ENTRY_FIRST_NAME; ?></td>
                <td class="main"><?php echo tep_draw_input_field('firstname','','class="input" size="35"') . '&nbsp;' . (tep_not_null(ENTRY_FIRST_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_FIRST_NAME_TEXT . '</span>': ''); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo ENTRY_LAST_NAME; ?></td>
                <td class="main"><?php echo tep_draw_input_field('lastname','','class="input" size="35"') . '&nbsp;' . (tep_not_null(ENTRY_LAST_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_LAST_NAME_TEXT . '</span>': ''); ?></td>
              </tr>
<?php
  if (ACCOUNT_DOB == 'true') {
?>
              <tr>
                <td class="main"><?php echo ENTRY_DATE_OF_BIRTH; ?></td>
                <td class="main"><?php echo tep_draw_input_field('dob','','class="input" size="35"') . '&nbsp;' . (tep_not_null(ENTRY_DATE_OF_BIRTH_TEXT) ? '<span class="inputRequirement">' . ENTRY_DATE_OF_BIRTH_TEXT . '</span>': ''); ?></td>
              </tr>
<?php
  }
  if(0){
?>
              <tr>
                <td class="main"><?php echo "CNP:"; ?></td>
                <td class="main"><?php echo tep_draw_input_field('cnp','','class="input" size="35"') . '&nbsp;' . (tep_not_null("*") ? '<span class="inputRequirement">*</span>': ''); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo "Serie si Numar CI:"; ?></td>
                <td class="main"><?php echo tep_draw_input_field('ci','','class="input" size="35"') . '&nbsp;' . (tep_not_null("*") ? '<span class="inputRequirement">*</span>': ''); ?></td>
              </tr>
<?}?>
              <tr>
                <td class="main"><?php echo ENTRY_EMAIL_ADDRESS; ?></td>
                <td class="main"><?php echo tep_draw_input_field('email_address','','class="input" size="35"') . '&nbsp;' . (tep_not_null(ENTRY_EMAIL_ADDRESS_TEXT) ? '<span class="inputRequirement">' . ENTRY_EMAIL_ADDRESS_TEXT . '</span>': ''); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
        <td class="boxBgRight" height="5">&nbsp;</td>
      </tr>
<?php
  if (ACCOUNT_COMPANY == 'true') {
?>
      <tr>
      	<td class="boxBgLeft" height="5">&nbsp;</td>
        <td colspan="2" height="20" align="center"><div class="boxTextLine" style="width:448" ></div></td>
        <td class="boxBgRight" height="5">&nbsp;</td>
      </tr>
      <tr>
      	<td class="boxBgLeft" height="5">&nbsp;</td><td width="10"></td>
        <td class="main"><b><?php echo CATEGORY_COMPANY; ?></b></td>
      	<td class="boxBgRight" height="5">&nbsp;</td>
      </tr>
      <tr>
      	<td class="boxBgLeft" height="5">&nbsp;</td><td width="10"></td>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" cellspacing="2" cellpadding="2">
              <tr>
                <td class="main"><?php echo ENTRY_COMPANY; ?></td>
                <td class="main"><?php echo tep_draw_input_field('company','','class="input" size="35"') . '&nbsp;' . (tep_not_null(ENTRY_COMPANY_TEXT) ? '<span class="inputRequirement">' . ENTRY_COMPANY_TEXT . '</span>': ''); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo "Nr. Registrul Comertului:"; ?></td>
                <td class="main"><?php echo tep_draw_input_field('reg_com','','class="input" size="35"') . '&nbsp;' . (tep_not_null(ENTRY_REG_COM_TEXT) ? '<span class="inputRequirement"></span>': ''); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo "CUI:"; ?></td>
                <td class="main"><?php echo tep_draw_input_field('cui','','class="input" size="35"') . '&nbsp;' . (tep_not_null(ENTRY_CUI_TEXT) ? '<span class="inputRequirement"></span>': ''); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo "IBAN:"; ?></td>
                <td class="main"><?php echo tep_draw_input_field('iban','','class="input" size="35"') . '&nbsp;' . (tep_not_null(ENTRY_IBAN_TEXT) ? '<span class="inputRequirement"></span>': ''); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      	<td class="boxBgRight" height="5">&nbsp;</td>
      </tr>
<?php
  }
?>
      <tr>
      	<td class="boxBgLeft" height="5">&nbsp;</td>
        <td colspan="2" height="20" align="center"><div class="boxTextLine" style="width:448" ></div></td>
        <td class="boxBgRight" height="5">&nbsp;</td>
      </tr>
      <tr>
      	<td class="boxBgLeft" height="5">&nbsp;</td><td width="10"></td>
        <td class="main"><b><?php echo CATEGORY_ADDRESS; ?></b></td>
      	<td class="boxBgRight" height="5">&nbsp;</td>
      </tr>
      <tr>
      	<td class="boxBgLeft" height="5">&nbsp;</td><td width="10"></td>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" cellspacing="2" cellpadding="2">
                    <tr>
                        <td class="main"><?php echo ENTRY_COUNTRY; ?></td>
                        <td class="main"><?php echo tep_get_country_list('country','','class="input"') . '&nbsp;' . (tep_not_null(ENTRY_COUNTRY_TEXT) ? '<span class="inputRequirement">' . ENTRY_COUNTRY_TEXT . '</span>': ''); ?></td>
                    </tr>
                    <tr>
                        <td class="main"><?php echo ENTRY_CITY; ?></td>
                        <td class="main"><?php echo tep_draw_input_field('city','','class="input" size="35"') . '&nbsp;' . (tep_not_null(ENTRY_CITY_TEXT) ? '<span class="inputRequirement">' . ENTRY_CITY_TEXT . '</span>': ''); ?></td>
                    </tr>
              <tr>
                <td class="main" valign="top"><?php echo ENTRY_STREET_ADDRESS; ?></td>
                <td class="main" width="170"><?php echo tep_draw_textarea_field('street_address', 'soft', 20, 5,'','class="input"');?></td>
                <td class="main" valign="top"><?php echo (tep_not_null(ENTRY_STREET_ADDRESS_TEXT) ? '<span class="inputRequirement">' . ENTRY_STREET_ADDRESS_TEXT . '</span>': ''); ?></td>
              </tr>
<?php
  if (ACCOUNT_SUBURB == 'true') {
?>
              <tr>
                <td class="main"><?php echo ENTRY_SUBURB; ?></td>
                <td class="main"><?php echo tep_draw_input_field('suburb','','class="input" size="35"') . '&nbsp;' . (tep_not_null(ENTRY_SUBURB_TEXT) ? '<span class="inputRequirement">' . ENTRY_SUBURB_TEXT . '</span>': ''); ?></td>
              </tr>
<?php
  }
if(0){
?>
              <tr>
                <td class="main"><?php echo ENTRY_POST_CODE; ?></td>
                <td class="main"><?php echo tep_draw_input_field('postcode','','class="input" size="35"') . '&nbsp;' . (tep_not_null(ENTRY_POST_CODE_TEXT) ? '<span class="inputRequirement">' . ENTRY_POST_CODE_TEXT . '</span>': ''); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo ENTRY_CITY; ?></td>
                <td class="main"><?php echo tep_draw_input_field('city','','class="input" size="35"') . '&nbsp;' . (tep_not_null(ENTRY_CITY_TEXT) ? '<span class="inputRequirement">' . ENTRY_CITY_TEXT . '</span>': ''); ?></td>
              </tr>
<?php
}
  if (ACCOUNT_STATE == 'true') {
?>
              <tr>
                <td class="main"><?php echo ENTRY_STATE; ?></td>
                <td class="main">
<?php
    if ($process == true) {
      if ($entry_state_has_zones == true) {
        $zones_array = array();
        $zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' order by zone_name");
        while ($zones_values = tep_db_fetch_array($zones_query)) {
          $zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
        }
        echo tep_draw_pull_down_menu('state', $zones_array);
      } else {
        echo tep_draw_input_field('state');
      }
    } else {
      echo tep_draw_input_field('state');
    }

    if (tep_not_null(ENTRY_STATE_TEXT)) echo '&nbsp;<span class="inputRequirement">' . ENTRY_STATE_TEXT;
?>
                </td>
              </tr>
<?php
  }
?>

            </table></td>
          </tr>
        </table></td>
      	<td class="boxBgRight" height="5">&nbsp;</td>
      </tr>
      <tr>
      	<td class="boxBgLeft" height="5">&nbsp;</td>
        <td colspan="2" height="20" align="center"><div class="boxTextLine" style="width:448" ></div></td>
        <td class="boxBgRight" height="5">&nbsp;</td>
      </tr>
      <tr>
      	<td class="boxBgLeft" height="5">&nbsp;</td><td width="10"></td>
        <td class="main"><b><?php echo CATEGORY_CONTACT; ?></b></td>
      	<td class="boxBgRight" height="5">&nbsp;</td>
      </tr>
      <tr>
      	<td class="boxBgLeft" height="5">&nbsp;</td><td width="10"></td>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" cellspacing="2" cellpadding="2">
              <tr>
                <td class="main"><?php echo ENTRY_TELEPHONE_NUMBER; ?></td>
                <td class="main"><?php echo tep_draw_input_field('telephone','','class="input" size="35"') . '&nbsp;' . (tep_not_null(ENTRY_TELEPHONE_NUMBER_TEXT) ? '<span class="inputRequirement">' . ENTRY_TELEPHONE_NUMBER_TEXT . '</span>': ''); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo ENTRY_FAX_NUMBER; ?></td>
                <td class="main"><?php echo tep_draw_input_field('fax','','class="input" size="35"') . '&nbsp;' . (tep_not_null(ENTRY_FAX_NUMBER_TEXT) ? '<span class="inputRequirement">' . ENTRY_FAX_NUMBER_TEXT . '</span>': ''); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      	<td class="boxBgRight" height="5">&nbsp;</td>
      </tr>
      <tr>
      	<td class="boxBgLeft" height="5">&nbsp;</td>
        <td colspan="2" height="20" align="center"><div class="boxTextLine" style="width:448" ></div></td>
        <td class="boxBgRight" height="5">&nbsp;</td>
      </tr>
      <tr>
      	<td class="boxBgLeft" height="5">&nbsp;</td><td width="10"></td>
        <td class="main"><b><?php echo CATEGORY_OPTIONS; ?></b></td>
      	<td class="boxBgRight" height="5">&nbsp;</td>
      </tr>
      <tr>
      	<td class="boxBgLeft" height="5">&nbsp;</td><td width="10"></td>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" cellspacing="2" cellpadding="2">
              <tr>
                <td class="main"><?php echo ENTRY_NEWSLETTER; ?></td>
                <td class="main"><?php echo tep_draw_checkbox_field('newsletter', '1', true) . '&nbsp;' . (tep_not_null(ENTRY_NEWSLETTER_TEXT) ? '<span class="inputRequirement">' . ENTRY_NEWSLETTER_TEXT . '</span>': ''); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      	<td class="boxBgRight" height="5">&nbsp;</td>
      </tr>
      <tr>
      	<td class="boxBgLeft" height="5">&nbsp;</td>
        <td colspan="2" height="20" align="center"><div class="boxTextLine" style="width:448" ></div></td>
        <td class="boxBgRight" height="5">&nbsp;</td>
      </tr>
      <tr>
      	<td class="boxBgLeft" height="5">&nbsp;</td><td width="10"></td>
        <td class="main"><b><?php echo CATEGORY_PASSWORD; ?></b></td>
      	<td class="boxBgRight" height="5">&nbsp;</td>
      </tr>
      <tr>
      	<td class="boxBgLeft" height="5">&nbsp;</td><td width="10"></td>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" cellspacing="2" cellpadding="2">
              <tr>
                <td class="main"><?php echo ENTRY_PASSWORD; ?></td>
                <td class="main"><?php echo tep_draw_password_field('password','','class="input" size="35"') . '&nbsp;' . (tep_not_null(ENTRY_PASSWORD_TEXT) ? '<span class="inputRequirement">' . ENTRY_PASSWORD_TEXT . '</span>': ''); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo ENTRY_PASSWORD_CONFIRMATION; ?></td>
                <td class="main"><?php echo tep_draw_password_field('confirmation','','class="input" size="35"') . '&nbsp;' . (tep_not_null(ENTRY_PASSWORD_CONFIRMATION_TEXT) ? '<span class="inputRequirement">' . ENTRY_PASSWORD_CONFIRMATION_TEXT . '</span>': ''); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      	<td class="boxBgRight" height="5">&nbsp;</td>
      </tr>
      <tr>
      	<td class="boxBgLeft" height="5">&nbsp;</td>
        <td colspan="2" height="20" align="center"><div class="boxTextLine" style="width:448" ></div></td>
        <td class="boxBgRight" height="5">&nbsp;</td>
      </tr>
      <tr>
      	<td class="boxBgLeft" height="5">&nbsp;</td><td width="10"></td>
        <td class="main"><b>Termeni si conditii</b></td>
      	<td class="boxBgRight" height="5">&nbsp;</td>
      </tr>
      <tr>
      	<td class="boxBgLeft" height="5">&nbsp;</td><td width="10"></td>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" cellspacing="2" cellpadding="2">
              <tr>
                <td class="main"><input type="checkbox" id="terms">&nbsp;&nbsp;Am citit si sunt de acord cu <a href="<?echo tep_href_link(FILENAME_TERMS);?>">Termenii si Conditiile</a> www.toolszone.ro</td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      	<td class="boxBgRight" height="5">&nbsp;</td>
      </tr>
      <tr>
      	<td class="boxBgLeft" height="5">&nbsp;</td><td width="10"></td>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td width="10" height="40"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td align="right" valign="top"><?php echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      	<td class="boxBgRight" height="5">&nbsp;</td>
      </tr>
      <tr>
		<td><?=tep_image(DIR_WS_IMAGES . 'infobox/corner_left_bottom.gif')?></td>  
		<td colspan="2"width="100%" style="border-bottom: 1px solid #4791d9; font-size:1px;">&nbsp;</td>  
		<td><?=tep_image(DIR_WS_IMAGES . 'infobox/corner_right_bottom.gif')?></td>      
      </tr>
    </table></tr></td></table></form></td>
<td class="spacingTable">&nbsp;&nbsp;</td><!-- body_text_eof //-->
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
<!-- right_navigation //-->
<?php include(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<br>
</div><?php require(DIR_WS_INCLUDES . 'footer.php'); ?></body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
