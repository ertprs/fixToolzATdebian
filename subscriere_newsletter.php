<?php
/*
  $Id: checkout_success.php 1749 2007-12-21 04:23:36Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License
*/ 
  require('includes/application_top.php');

  if (isset($_POST['email'])) {
		
	$to = STORE_OWNER_EMAIL_ADDRESS;
	$subject = "Inscriere Newsletter";
	$from = $_POST['email'];
    $honeypot =  $_POST['name'];
    $companie = $_POST['companie'];
	$message = "Adresa de mail inscrisa la Newsletter: ".$from."\nCompanie: ".$companie;
	$headers = "From: $from";
	if (!$honeypot) {
        mail($to,$subject,$message,$headers);
        $query = "INSERT INTO all_contacts VALUES (null, null, '$companie', null, '$email', null, null, null, 1, 2)"; //1 este te_sunam, 2 newsletter
        $result = mysql_query($query);

        if (!$result) {
            die('Invalid query: ' . mysql_error());
        }

    }
	
  }
?>  
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo "Inscriere Newsletter - ".TITLE; ?></title>
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
    <td width="100%" valign="top">
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td>
      <table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
		<td height="32" valign="top" width="8"><img src="images/infobox/corner_left_top.gif" alt="" border="0" height="32" width="8"></td>
		<td colspan="2" class="infoBoxHeading2" height="32" valign="top" align="left">&nbsp;&nbsp;Inscriere Newsletter</td>
		<td height="32" valign="top" width="8"><img src="images/infobox/corner_right_top.gif" alt="" border="0" height="32" width="8"></td>
	  </tr> 
          <tr>
		<td class="boxBgLeft" height="5">&nbsp;</td><td width="10"></td>
		<td>
		<table border="0" width="100%" cellspacing="0" cellpadding="0"><tr>
            <td valign="top"><?php echo tep_image(DIR_WS_IMAGES . 'ok.gif', HEADING_TITLE); ?></td>
            <td valign="top" class="main"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?><div align="center" class="pageHeading">Inscriere Newsletter</div><br>Felicitari! De azi inainte vei fi la curent cu ultimele promotii de pe ToolsZone.ro prin intermediul Newsletter-ului nostru.<br><br>
            <h3>Iti multumim pentru alegerea facuta!</h3></td></tr></table></td>
        <td class="boxBgRight" height="5">&nbsp;</td>

      </tr>
      <tr>
		<td class="boxBgLeft" height="70">&nbsp;</td><td width="10"></td>
        <td></td>
        <td class="boxBgRight" height="5">&nbsp;</td>
      </tr>
			      <tr>
					<td><?=tep_image(DIR_WS_IMAGES . 'infobox/corner_left_bottom.gif')?></td>  
					<td colspan="2" width="100%" style="border-bottom: 1px solid #4791d9; font-size:1px;">&nbsp;</td>  
					<td><?=tep_image(DIR_WS_IMAGES . 'infobox/corner_right_bottom.gif')?></td>      
			      </tr> 
<?php if (DOWNLOAD_ENABLED == 'true') include(DIR_WS_MODULES . 'downloads.php'); ?>
    </table></td></tr></table></form></td>
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
