<?php
/*
  $Id: tell_a_friend.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2006 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if (!tep_session_is_registered('customer_id') && (ALLOW_GUEST_TO_TELL_A_FRIEND == 'false')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_TELL_A_FRIEND);

  if (isset($_GET['action']) && ($_GET['action'] == 'process')) {
    $error = false;

    $to_email_address = tep_db_prepare_input($_POST['to_email_address']);
    $to_name = tep_db_prepare_input($_POST['to_name']);
    $from_email_address = tep_db_prepare_input($_POST['from_email_address']);
    $from_name = tep_db_prepare_input($_POST['from_name']);
    $message = tep_db_prepare_input($_POST['message']);

    if (empty($from_name)) {
      $error = true;

      $messageStack->add('friend', ERROR_FROM_NAME);
    }

    if (!tep_validate_email($from_email_address)) {
      $error = true;

      $messageStack->add('friend', ERROR_FROM_ADDRESS);
    }

    if (empty($to_name)) {
      $error = true;

      $messageStack->add('friend', ERROR_TO_NAME);
    }

    if (!tep_validate_email($to_email_address)) {
      $error = true;

      $messageStack->add('friend', ERROR_TO_ADDRESS);
    }

    if ($error == false) {
      $email_subject = 'Ai primit un mesaj de la '. $from_name.' - '. STORE_NAME;
      
      $email_body .= "Salut $to_name,\n\n".
       
      "Prietenul tau iti recomanda sa vizitezi www.toolszone.ro, cel mai mare magazin online de scule si echipamente menite sa-ti faca munca eficienta si confortabila.\n\n".
      "Oferta Toolszone.ro include un spectru larg de produse accesibile pentru oricine: peste 50 de branduri, peste 20.000 de articole, pentru toate categoriile de utilizatori. Preturi avantajoase si multe nivele de discount:\n".
      "discount de 10% pentru primele 100 de comenzi, grile speciale de discount pentru clientii fideli, discounturi speciale pentru cantitate, discounturi speciale pentru valoare, sistem de bonusare prin vouchere.\n\n";
      
      if (tep_not_null($message)) {
        $email_body .= "Mesajul de la prietenul tau, $from_name:\n\n" .$message . "\n\n";
      }       
                    
      $email_body .=  "Pentru a deschide un cont pe Toolszone.ro, te rugam sa dai click pe urmatorul link:\n\n" . tep_href_link(FILENAME_CREATE_ACCOUNT, 'ref=' . $_SESSION['customer_id'], 'NONSSL', false) . "\n\n" .
                     sprintf("Echipa ToolsZone.ro iti doreste o zi buna." . "\n" . HTTP_SERVER . DIR_WS_CATALOG . "\n");
		  //echo $email_body;
      tep_mail($to_name, $to_email_address, $email_subject, $email_body, $from_name, $from_email_address);

      $messageStack->add_session('header', sprintf(TEXT_EMAIL_SUCCESSFUL_SENT, $product_info['products_name'], tep_output_string_protected($to_name)), 'success');

      tep_redirect(tep_href_link(FILENAME_VOUCHER, ''));
    }
  } elseif (tep_session_is_registered('customer_id')) {
    $account_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customer_id . "'");
    $account = tep_db_fetch_array($account_query);

    $from_name = $account['customers_firstname'] . ' ' . $account['customers_lastname'];
    $from_email_address = $account['customers_email_address'];
  }

  $breadcrumb->add('Voucher', tep_href_link(FILENAME_VOUCHER, ''));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo "Recomanda www.toolszone.ro unui prieten pentru a primi un Voucher - ".TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">

      <tr>
		<td height="32" valign="top" width="8"><img src="images/infobox/corner_left_top.gif" alt="" border="0" height="32" width="8"></td>
		<td colspan="2" class="infoBoxHeading2" height="32" valign="top" align="left">&nbsp;&nbsp;Recomanda ToolsZone.ro</td>
		<td height="32" valign="top" width="8"><img src="images/infobox/corner_right_top.gif" alt="" border="0" height="32" width="8"></td>
	  </tr>
      <tr>
		<td class="boxBgLeft" height="5">&nbsp;</td><td width="10"></td>
        <td>    
    <?php echo tep_draw_form('email_friend', tep_href_link(FILENAME_VOUCHER, 'action=process')); ?>
    
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">Recomanda ToolsZone.ro unui prieten pentru a primi un Voucher</td>
            </tr>
        </table></td>
      </tr>
      <tr>
        <td class="subcateg" style="font-size:8px">&nbsp;</td>
      </tr>
      <tr>
        <td style="font-size:12px">
<?
  if (isset($_GET['action']) && ($_GET['action'] == 'process')) {
?>
<br>
<br>
<center><b style="color:red;font-size:12px">Recomandare efectuata cu succes!</b></center>
<br>

<?      
  }        
?>        
        <br><center><img src="images/voucher.png" align="center"></center><br>
        Doar cu cateva clickuri, recomandand ToolsZone.ro, veti putea beneficia de voucherul nostru de cumparaturi care va pune la dispozitie un bonus in plus destinat sa faca preturile mici, mici, mici. <br><br>In functie de recomandarile facute, valoarea bonusului creste vazand cu ochii.<br><br> Click away!
<br><br>
</td>

      </tr>  
      <tr>
        <td class="subcateg" style="font-size:8px">&nbsp;</td>
      </tr>  
      <tr>
        <td style="font-size:10px">&nbsp;</td>
      </tr>
<?php
  if ($messageStack->size('friend') > 0) {
?>
      <tr>
        <td><?php echo $messageStack->output('friend'); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main"><b><?php echo FORM_TITLE_CUSTOMER_DETAILS; ?></b></td>
                <td class="inputRequirement" align="right"><?php echo FORM_REQUIRED_INFORMATION; ?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
              <tr class="infoBoxContents">
                <td><table border="0" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="main"><?php echo FORM_FIELD_CUSTOMER_NAME; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('from_name','','class="header"'); ?></td>
                  </tr>
                  <tr>
                    <td class="main"><?php echo FORM_FIELD_CUSTOMER_EMAIL; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('from_email_address','','class="header"'); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><b><?php echo FORM_TITLE_FRIEND_DETAILS; ?></b></td>
          </tr>
          <tr>
            <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
              <tr class="infoBoxContents">
                <td><table border="0" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="main"><?php echo FORM_FIELD_FRIEND_NAME; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('to_name','','class="header"') . '&nbsp;<span class="inputRequirement">' . ENTRY_FIRST_NAME_TEXT . '</span>'; ?></td>
                  </tr>
                  <tr>
                    <td class="main"><?php echo FORM_FIELD_FRIEND_EMAIL; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('to_email_address','','class="header"') . '&nbsp;<span class="inputRequirement">' . ENTRY_EMAIL_ADDRESS_TEXT . '</span>'; ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><b><?php echo FORM_TITLE_FRIEND_MESSAGE; ?></b></td>
          </tr>
          <tr>
            <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
              <tr class="infoBoxContents">
                <td class="header"><?php echo tep_draw_textarea_field('message', 'soft', 40, 8); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $_GET['products_id']) . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
                <td align="right"><?php echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></form>
    </td>
        <td class="boxBgRight" height="5">&nbsp;</td>
      </tr>
	  <tr>
		<td><?=tep_image(DIR_WS_IMAGES . 'infobox/corner_left_bottom.gif')?></td>  
		<td colspan="2" width="100%" style="border-bottom: 1px solid #4791d9; font-size:1px;">&nbsp;</td>  
		<td><?=tep_image(DIR_WS_IMAGES . 'infobox/corner_right_bottom.gif')?></td>      
	  </tr> 
    </table>    </td>      </tr>
        </table></td>
<td class="spacingTable">&nbsp;&nbsp;</td><!-- body_text_eof //-->
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->

<!-- footer_eof //-->
<br>
</div><?php require(DIR_WS_INCLUDES . 'footer.php'); ?></body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>