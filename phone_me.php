<?php
/*
  $Id: contact_us.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  define('KEY',5);

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CONTACT_US);

  $error = false;
  if (isset($_GET['action']) && ($_GET['action'] == 'send')) {
    $numar = tep_db_prepare_input($_POST['numar']);
    $interior = tep_db_prepare_input($_POST['interior']);
    $nume = tep_db_prepare_input($_POST['nume']);
    $subiect = tep_db_prepare_input($_POST['subiect']);
    $detalii = tep_db_prepare_input($_POST['detalii']);
    $email = tep_db_prepare_input($_POST['email']);
    $timp = tep_db_prepare_input($_POST['timp']);
    $raspuns_uman = tep_db_prepare_input($_POST['raspuns']);
    $raspuns_corect = ((int)tep_db_prepare_input($_POST['raspuns_ascuns']) ^ KEY);
    $numar_ok = (is_numeric($numar) && ( 10 == strlen($numar)) && ($numar[0]=='0'));
    $telefon = ($interior)?'('.$interior.')'.$numar:$numar;
    if (tep_validate_email($email) && ($raspuns_uman == $raspuns_corect) && $numar_ok) {
      tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, "Te sunam noi: ".$nume.", ".$subiect, "Nume: ".$nume . "\nNumar: ".$numar . "\nInterior:".$interior. "\nSubiect:".$subiect ."\nCand sa sunam:".$timp . " ore(a)\nDetalii:\n".$detalii, $name , $email );
      $query = "INSERT INTO all_contacts VALUES (null, '$nume', null, '$telefon', '$email', '$subiect', '$detalii', '$timp', 1, 1)"; //1 este te_sunam, 2 newsletter
      $result = mysql_query($query);

        if (!$result) {
            die('Invalid query: ' . mysql_error());
        }

      tep_redirect(tep_href_link(FILENAME_CONTACT_US, 'action=success'));
    } else {
      $error = true;

        if (!tep_validate_email($email)) $messageStack->add('contact', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
        if (!$numar_ok) $messageStack->add('contact', 'Numarul de telefon nu pare valid. ');
        if ($raspuns_uman != $raspuns_corect) $messageStack->add('contact', 'Nu ati raspuns corect la intrebarea de securitate');

    }
  }

  $breadcrumb->add('Te sunam noi', tep_href_link(FILENAME_PHONE_ME));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo HEADING_TITLE." - ".TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
    <style type="text/css">
        td.main input {width: 100%; margin: 2px 0;}
    </style>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" onLoad="MM_preloadImages('<?echo $cur_page=="1" ? '' : 'images/menu/menu_01_over.gif';?>','<?echo $cur_page=="2" ? '' : 'images/menu/menu_02_over.gif';?>','<?echo $cur_page=="3" ? '' : 'images/menu/menu_03_over.gif';?>','<?echo $cur_page=="4" ? '' : 'images/menu/menu_04_over.gif';?>','<?echo $cur_page=="5" ? '' : 'images/menu/menu_05_over.gif';?>','<?echo $cur_page=="6" ? '' : 'images/menu/menu_06_over.gif';?>')">
<div id="wrapper"><!-- header //-->
<?php
$cur_page="4";
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
    <td width="100%" valign="top"><?php echo tep_draw_form('contact_us', tep_href_link(FILENAME_PHONE_ME, 'action=send')); ?>
    
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">

      <tr>
		<td height="32" valign="top" width="8"><img src="images/infobox/corner_left_top.gif" alt="" border="0" height="32" width="8"></td>
		<td colspan="2" class="infoBoxHeading2" height="32" valign="top" align="left">&nbsp;&nbsp;Te sunam noi!</td>
		<td height="32" valign="top" width="8"><img src="images/infobox/corner_right_top.gif" alt="" border="0" height="32" width="8"></td>
	  </tr>

                <?php
                if ($messageStack->size('contact') > 0) {
                    ?>
                    <tr>
                        <td class="boxBgLeft" height="5">&nbsp;</td><td width="10"></td>
                        <td><?php echo $messageStack->output('contact'); ?></td>
                        <td class="boxBgRight" height="5">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="boxBgLeft" height="5">&nbsp;</td>
                        <td colspan="2" align="center" height="5"><div class="boxTextLine" style="width: 470px;"></div></td>
                        <td class="boxBgRight" height="5">&nbsp;</td>
                    </tr>
                <?php
                }

  if (isset($_GET['action']) && ($_GET['action'] == 'success')) {
?>
      <tr>
		<td class="boxBgLeft" height="5">&nbsp;</td><td width="10"></td>
        <td class="main" align="center"><br><br><?php echo /*tep_image(DIR_WS_IMAGES . 'table_background_man_on_board.gif', HEADING_TITLE, '0', '0', 'align="left"') .*/ TEXT_SUCCESS; ?><br><br></td>
        <td class="boxBgRight" height="5">&nbsp;</td>
      </tr>
      <tr>
		<td class="boxBgLeft" height="5">&nbsp;</td><td width="10"></td>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
        <td class="boxBgRight" height="5">&nbsp;</td>
      </tr>
	  <tr>
		<td><?=tep_image(DIR_WS_IMAGES . 'infobox/corner_left_bottom.gif')?></td>  
		<td colspan="2" width="100%" style="border-bottom: 1px solid #4791d9; font-size:1px;">&nbsp;</td>  
		<td><?=tep_image(DIR_WS_IMAGES . 'infobox/corner_right_bottom.gif')?></td>      
	  </tr> 
<?php
  } else {
                    $op1 = rand(0,10);
                    $op2 = rand(0,10);
                    $raspuns_ascuns = ($op1 + $op2) ^ KEY;
                    echo '<input type="hidden" name="raspuns_ascuns" value="'.$raspuns_ascuns.'">';
                    $intrebare = $op1 . ' + ' . $op2 . ' = ';
?>
      <tr>
		<td class="boxBgLeft" height="5">&nbsp;</td><td width="10"></td>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
               <tr><td colspan="2" class="main">
                       <br>
                       Daca ai nevoie de un produs si nu l-ai gasit pe site, un produs special sau vrei sa afli mai multe informatii despre transport sau despre comanda ta, completeaza formularul de mai jos si te vom contacta cat mai curand posibil.
                       <br><br><br>
                       Apelurile catre numarul dvs. de telefon se vor efectua in timpul programului de lucru:
                        <br>
               </td></tr>
               <tr><td class="main"><b>&nbsp;</b></td></tr>
               <tr><td colspan="2" class="main"><b>Luni - Vineri: 09:00 - 17:00</b></td></tr>
               <tr><td colspan="2" class="main"><hr></td></tr>
               <tr><td colspan="2" class="main" style="text-align: right; color: brown; ">* Informatii obligatorii</td></tr>
              <tr>
                <td class="main"><b>Numar telefon:</b></td>
                <td class="main"><b><?php echo tep_draw_input_field('numar', '' ,'class="input" size="60"'); ?></td>
              </tr>

              <tr>
                  <td class="main"></td>
                  <td colspan="2" class="main" style="font-style: italic;">(completeaza tot numarul de telefon, inclusiv prefixul)</td></tr>
              <tr>
                <td class="main"><b>Interior:</b></td>
                <td class="main"><b><?php echo tep_draw_input_field('interior', '' ,'class="input" size="30"'); ?></td>
              </tr>
              <tr>
                <td class="main"><b>Nume contact:</b></td>
                <td class="main"><b><?php echo tep_draw_input_field('nume', '' ,'class="input" size="60"'); ?></td>
              </tr>
              <tr>
                <td class="main"><b>Subiect:</b></td>
                <td class="main"><b><?php echo tep_draw_input_field('subiect', '' ,'class="input" size="60"'); ?></td>
              </tr>
              <tr>  
                <td class="main" valign="top"><b>Detalii:</b></td>
                <td ><?php echo tep_draw_textarea_field('detalii', 'soft', 60, 15, '' ,'class="input"'); ?></td>
              </tr>
                <tr>
                    <td class="main"><b>Email:</b></td>
                    <td class="main"><b><?php echo tep_draw_input_field('email', '' ,'class="input" size="60"'); ?></td>
                </tr>
                    <?php
                    $timp = array();
                    $timp[] = array('id'=>'1','text'=>'1 ora');
                    $timp[] = array('id'=>'4','text'=>'4 ore');
                    $timp[] = array('id'=>'24','text'=>'24 ore');
                    ?>
                <tr>
                    <td class="main" valign="top"><b>Cand sa sunam?</b></td>
                    <td ><?php echo tep_draw_pull_down_menu('timp', $timp, 60, 15, '' ,'class="input"'); ?></td>
                </tr>
                <tr><td colspan="2" class="main"><hr></td></tr>
                <tr><td colspan="2" class="main">Pentru evitarea spam-ului te rugam sa raspunzi la urmatoarea intrebare:</td></tr>
                <tr>
                    <td class="main"><b><?=$intrebare?></b></td>
                    <td class="main"><b><?php echo tep_draw_input_field('raspuns', '' ,'class="input" size="60"'); ?></td>
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
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td align="right"><?php echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
        <td class="boxBgRight" height="5">&nbsp;</td>
      </tr>
	  <tr>
		<td><?=tep_image(DIR_WS_IMAGES . 'infobox/corner_left_bottom.gif')?></td>  
		<td colspan="2" width="100%" style="border-bottom: 1px solid #4791d9; font-size:1px;">&nbsp;</td>  
		<td><?=tep_image(DIR_WS_IMAGES . 'infobox/corner_right_bottom.gif')?></td>      
	  </tr> 
<?php
  }
?>
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