<?php
/*
  $Id: contact_us.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CONTACT_US);

  $error = false;
  if (isset($_GET['action']) && ($_GET['action'] == 'send')) {
    $name = tep_db_prepare_input($_POST['name']);
    $email_address = tep_db_prepare_input($_POST['email']);
    $enquiry = tep_db_prepare_input($_POST['enquiry']);

    if (tep_validate_email($email_address)) {
      tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, EMAIL_SUBJECT, $enquiry, $name, $email_address);

      tep_redirect(tep_href_link(FILENAME_CONTACT_US, 'action=success'));
    } else {
      $error = true;

      $messageStack->add('contact', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
    }
  }

  $breadcrumb->add("Despre", tep_href_link(FILENAME_ABOUT));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo "Despre toolszone.ro - ".TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" onLoad="MM_preloadImages('<?echo $cur_page=="1" ? '' : 'images/menu/menu_01_over.gif';?>','<?echo $cur_page=="2" ? '' : 'images/menu/menu_02_over.gif';?>','<?echo $cur_page=="3" ? '' : 'images/menu/menu_03_over.gif';?>','<?echo $cur_page=="4" ? '' : 'images/menu/menu_04_over.gif';?>','<?echo $cur_page=="5" ? '' : 'images/menu/menu_05_over.gif';?>','<?echo $cur_page=="6" ? '' : 'images/menu/menu_06_over.gif';?>')">
<div id="wrapper"><!-- header //-->
<?php
$cur_page="2";
 require(DIR_WS_INCLUDES . 'header.php'); ?>
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
    <td width="100%" valign="top"><?php echo tep_draw_form('contact_us', tep_href_link(FILENAME_CONTACT_US, 'action=send')); ?>
    
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">

      <tr>
		<td height="32" valign="top" width="8"><img src="images/infobox/corner_left_top.gif" alt="" border="0" height="32" width="8"></td>
		<td colspan="2" class="infoBoxHeading2" height="32" valign="top" align="left">&nbsp;&nbsp;Despre ToolsZone.ro</td>
		<td height="32" valign="top" width="8"><img src="images/infobox/corner_right_top.gif" alt="" border="0" height="32" width="8"></td>
	  </tr> 
      <tr>
		<td class="boxBgLeft" height="5">&nbsp;</td><td width="10"></td>
        <td class="main">

          <br>Viziunea Toolszone.ro este centrata pe nevoile clientilor, tocmai de aceea incercam sa oferim produse de marca, servicii de exceptie, si preturi avantajoase. 
          <br><br><b>Un spectru larg de produse accesibile pentru oricine:</b>  <br>
          <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;...&nbsp;peste 100 de branduri consacrate
          <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;...&nbsp;peste 20.000 de produse profesionale
          <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;...&nbsp;pentru toate categoriile de utilizatori
          
          <br><br><b>Preturi avantajoase si multe nivele de discount: </b> <br>
          <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;...&nbsp;grile speciale de discount pentru clientii fideli
          <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;...&nbsp;discounturi speciale pentru cantitate
          <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;...&nbsp;discounturi speciale pentru valoare
          <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;...&nbsp;sistem de bonusare prin vouchere
          <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;...&nbsp;promotii saptamanale
          
          <br><br><b>Servicii de exceptie: </b> <br>
          <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;...&nbsp;modalitati variate de plata 
          <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;...&nbsp;fara comanda minima
          <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;...&nbsp;transport gratuit pentru comenzi mai mari de 200 de lei
          
          <br><br><b>Interfata confortabila si usor de utilizat:  </b>  <br>
          <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;...&nbsp;modul de cautare avansata 
          <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;...&nbsp;modul de comada rapida
          <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;...&nbsp;modul de cerere de oferta pentru ce nu gasiti la noi
          <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;...&nbsp;modul pentru propuneri de colaborare<br><br>

        
        </td>
        <td class="boxBgRight" height="5">&nbsp;</td>
      </tr>
	  <tr>
		<td><?=tep_image(DIR_WS_IMAGES . 'infobox/corner_left_bottom.gif')?></td>  
		<td colspan="2" width="100%" style="border-bottom: 1px solid #4791d9; font-size:1px;">&nbsp;</td>  
		<td><?=tep_image(DIR_WS_IMAGES . 'infobox/corner_right_bottom.gif')?></td>      
	  </tr> 
    </table></td></tr>
        </table></form></td>
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
