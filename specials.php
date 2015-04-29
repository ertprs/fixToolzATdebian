<?php
/*
  $Id: specials.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_SPECIALS);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_SPECIALS));
  
  $MAX_DISPLAY_SPECIAL_PRODUCTS = isset($_REQUEST['display'])?$_REQUEST['display']:15;
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo HEADING_TITLE." - ".TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
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
    <td width="100%" valign="top">
        <table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td height="32" valign="top" width="8"><img src="images/infobox/corner_left_top.gif" alt="" border="0" height="32" width="8"></td>
                <td colspan="2" class="infoBoxHeading2" height="32" valign="top" align="left">&nbsp;&nbsp; Promotii speciale </td>
                <td height="32" valign="top" width="8"><img src="images/infobox/corner_right_top.gif" alt="" border="0" height="32" width="8"></td>
            </tr>
            <tr>
                <td class="boxBgLeft" height="5">&nbsp;</td><td width="10"></td>
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                        <tbody>
                        <tr>
                            <td align="center" class="boxText"><table><tbody><tr><td colspan="2" align="center">Valabitate:<br> 1 februarie - 31 mai 2015</td></tr><tr><td colspan="2" align="center"><a href="pdf/_promo/ToolsZone.ro-Promotie-carote-Weldon-EUROBOOR.pdf"><img src="images/_promo/ToolsZone.ro-Promotie-carote-Weldon-EUROBOOR.jpg" border="0" alt="Promotie carote Weldon EUROBOOR" title="Promotie carote Weldon EUROBOOR" ></a></td></tr><tr><td colspan="2" align="center"><a href="pdf/_promo/ToolsZone.ro-Promotie-carote-Weldon-EUROBOOR.pdf">Promotie carote pentru metal Weldon EUROBOOR</a></td></tr></tbody></table></td>

<!--                            <td class="vdotline" align="center" valign="top" width="3%"></td>-->
                            <td align="center" class="boxText"><table><tbody><tr><td colspan="2" align="center">Valabitate:<br> 1 februarie - 31 decembrie 2015</td></tr><tr><td colspan="2" align="center"><a href="pdf/_promo/ToolsZone.ro-Promotie-scule-electrice-profesionale-PANASONIC-2015.pdf"><img src="images/_promo/ToolsZone.ro-Promotie-scule-electrice-profesionale-PANASONIC-2015.jpg" border="0" alt="Promotie scule electrice profesionale PANASONIC 2015" title="Promotie scule electrice profesionale PANASONIC 2015" ></a></td></tr><tr><td colspan="2" align="center"><a href="pdf/_promo/ToolsZone.ro-Promotie-scule-electrice-profesionale-PANASONIC-2015.pdf">Promotie scule electrice profesionale PANASONIC 2015</a></td></tr></tbody></table></td>

                            <!--<td class="vdotline" align="center" valign="top" width="3%"></td>
                            <td align="center" class="boxText"><table height="200"><tbody><tr><td colspan="2" align="center">Valabitate:<br> 15 martie - 31 mai 2014</td</tr><tr><td colspan="2" align="center"><a href="pdf/_promo/cat3.pdf"><img src="images/_promo/cat3_resize.jpg" border="0" alt="" title="PROMOTIE POLITE DIN PLASTIC" ></a></td></tr><tr><td colspan="2" align="center"><a href="pdf/_promo/cat3.pdf">PROMOTIE POLITE DIN PLASTIC</a></td></tr></tbody></table></td>-->
                            <td><img src="images/pixel_trans.gif" border="0" alt="" width="5" height="100%"></td>
                        </tr>
                        <tr>
                            <td colspan="5" align="center" height="5"><div class="boxTextLine" style="width: 470px;"></div></td>
                            <td><img src="images/pixel_trans.gif" border="0" alt="" width="5" height="100%"></td>
                        </tr>
                        <tr>
                            <td align="center" class="boxText"><table><tbody><tr><td colspan="2" align="center">Valabitate:<br> 1 februarie - 31 iunie 2015</td></tr><tr><td colspan="2" align="center"><a href="pdf/_promo/ToolsZone.ro-Promotie-unlete-si-echipamente-pentru-constructii-RUBI-2015.pdf"><img src="images/_promo/ToolsZone.ro-Promotie-unlete-si-echipamente-pentru-constructii-RUBI-2015.jpg" border="0" alt="ToolsZone.ro Promotie unlete si echipamente pentru constructii RUBI 2015" title="ToolsZone.ro Promotie unelte si echipamente pentru constructii RUBI 2015" ></a></td></tr><tr><td colspan="2" align="center"><a href="pdf/_promo/ToolsZone.ro-Promotie-unelte-si-echipamente-pentru-constructii-RUBI-2015.pdf">Promotie unelte si echipamente pentru constructii RUBI 2015</a></td></tr></tbody></table></td>

                            <td align="center" class="boxText"><table><tbody><tr><td colspan="2" align="center">Valabitate:<br> 1 februarie - 31 august 2015</td></tr><tr><td colspan="2" align="center"><a href="pdf/_promo/ToolsZone.ro-Promotie-utiliaje-pentru-gardinarit-primavara-vara-2015-FAWORYT.pdf"><img src="images/_promo/ToolsZone.ro-Promotie-utiliaje-pentru-gardinarit-primavara-vara-2015-FAWORYT.jpg" border="0" alt="ToolsZone.ro Promotie utiliaje pentru gardinarit primavara vara 2015 FAWORYT" title="ToolsZone.ro Promotie utiliaje pentru gardinarit primavara vara 2015 FAWORYT" ></a></td></tr><tr><td colspan="2" align="center"><a href="pdf/_promo/ToolsZone.ro-Promotie-utiliaje-pentru-gardinarit-primavara-vara-2015-FAWORYT.pdf">Promotie utiliaje pentru gardinarit primavara vara 2015 FAWORYT</a></td></tr></tbody></table></td>

                            <td><img src="images/pixel_trans.gif" border="0" alt="" width="5" height="100%"></td>
                        </tr>


                        <tr>
                        </tr>
                        </tbody></table></td>
                <td class="boxBgRight" height="5">&nbsp;</td>
            </tr>
            <tr>
                <td class="boxBgLeft" height="10" style="background-color: #efefef;">&nbsp;</td><td width="10"></td>
                <td style="background-color: #efefef;"><table border="0" width="100%" cellspacing="0" cellpadding="0">
                        <tbody><tr>


                        </tr>
                        </tbody></table></td>
                <td class="boxBgRight" height="5" style="background-color: #efefef;">&nbsp;</td>
            </tr>
            <tr>
                <td style="background-color: #efefef;"><img src="images/infobox/corner_left_bottom.gif" border="0" alt="" width="8" height="9"></td>
                <td colspan="2" width="100%" style="border-bottom: 1px solid #4791d9; font-size:1px; background-color: #efefef;">&nbsp;</td>
                <td style="background-color: #efefef;"><img src="images/infobox/corner_right_bottom.gif" border="0" alt="" width="8" height="9"></td>
            </tr>
        </table>
        <br>
        <table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">

      <tr>
		<td height="32" valign="top" width="8"><img src="images/infobox/corner_left_top.gif" alt="" border="0" height="32" width="8"></td>
		<td colspan="2" class="infoBoxHeading2" height="32" valign="top" align="left">&nbsp;&nbsp;<?php echo HEADING_TITLE; ?></td>
		<td height="32" valign="top" width="8"><img src="images/infobox/corner_right_top.gif" alt="" border="0" height="32" width="8"></td>
	  </tr>
<?php
  $specials_query_raw = "select p.products_id, pd.products_name, p.products_price, p.products_tax_class_id, p.products_image, s.specials_new_products_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_SPECIALS . " s where p.products_status = '1' and s.products_id = p.products_id and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and s.status = '1' order by p.products_date_added DESC";
  $specials_split = new splitPageResults($specials_query_raw, $MAX_DISPLAY_SPECIAL_PRODUCTS);

  if (($specials_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>
      <tr>
		<td class="boxBgLeft" height="5">&nbsp;</td><td width="10"></td>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="smallText"><?php echo $specials_split->display_count(TEXT_DISPLAY_NUMBER_OF_SPECIALS); ?></td>
            <td align="right" class="smallText"><?php echo TEXT_RESULT_PAGE . ' ' . $specials_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
          </tr>
        </table></td>
        <td class="boxBgRight" height="5">&nbsp;</td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?>
      <tr>
		<td class="boxBgLeft" height="5">&nbsp;</td><td width="10"></td>
        <td><table border="0" width="100%"cellspacing="0" cellpadding="2">
          <tr>
<?php
    $row = 0;
    $specials_query = tep_db_query($specials_split->sql_query);
    if($specials_split->number_of_rows > 0)
    {
     while ($specials = tep_db_fetch_array($specials_query)) {
      $row++;
      echo '<td align="center" class="boxText">'.
              '<table height="200">'.
              '<tr><td colspan="2" align="center"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $specials['products_id']) . '">' .
                tep_image(DIR_WS_IMAGES . "mari/" .strtoupper($specials['products_image'][0]).'/'.  $specials['products_image'], $specials['products_name'], SMALL_IMAGE_HEIGHT, SMALL_IMAGE_HEIGHT) .
              '</a></td></tr>'.
              '<tr><td colspan="2" align="center"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $specials['products_id']) . '">' .
                $specials['products_name'] .
              '</a></td></tr>'.
              '<tr><td colspan="2" align="center"><s>' . $currencies->display_price($specials['products_price'], tep_get_tax_rate($specials['products_tax_class_id'])) . '</s></td></tr>'.
              '<tr><td  class="price" height="50%">' . $currencies->display_price($specials['specials_new_products_price'], tep_get_tax_rate($specials['products_tax_class_id'])) . '</td><td><a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $specials['products_id']) . '">' . tep_image(DIR_WS_ICONS . 'cart.gif', ICON_CART) . '</a></td></tr>'.
              '</table>'.
            '</td>' . "\n";

      if ((($row / 3) == floor($row / 3))) {
?>
			<td><?php echo tep_draw_separator('pixel_trans.gif', '5', '100%'); ?></td>
          </tr>
          <tr>
          	<td colspan="5" align="center" height="5"><div class="boxTextLine" style="width: 470px;"></div></td>
          	<td><?php echo tep_draw_separator('pixel_trans.gif', '5', '100%'); ?></td>
          </tr>
          <tr>
<?php
      }else{
      		if(($specials_split->number_of_rows != 1) && ($specials_split->number_of_rows != 2 || $row !=2))
      		{ ?>

      			<td class="vdotline" align="center" valign="top" width="3%"></td>
    <?		}
      }
    }}else{?>
    	<td>&nbsp;&nbsp;&nbsp;<img src="images/emptycart.gif"></td>
        <td class="fieldKey">Deocamdata nu sunt promotii.<br>Va rugam reveniti.</td>
    <?}
?>
          </tr>
        </table></td>
        <td class="boxBgRight" height="5">&nbsp;</td>
      </tr>
<?php
  if (($specials_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>
      <tr>
		<td class="boxBgLeft" height="30" style="background-color: #efefef;">&nbsp;</td><td width="10"></td>
        <td style="background-color: #efefef;"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td align="left" class="pageResults"><?php echo  $specials_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
            <td align="right" class="pageResults">
              Afiseaza 
              <a href="<?=tep_href_link(FILENAME_SPECIALS,"display=50")?>" class="pageResults" >50</a> | 
              <a href="<?=tep_href_link(FILENAME_SPECIALS,"display=100")?>" class="pageResults" >100</a> de produse pe pagina</td>
          </tr>
        </table></td>
        <td class="boxBgRight" height="5" style="background-color: #efefef;">&nbsp;</td>
      </tr>
<?php
  }
?>
	  <tr>
		<td style="background-color: #efefef;"><?=tep_image(DIR_WS_IMAGES . 'infobox/corner_left_bottom.gif')?></td>  
		<td colspan="2" width="100%" style="border-bottom: 1px solid #4791d9; font-size:1px; background-color: #efefef;">&nbsp;</td>  
		<td style="background-color: #efefef;"><?=tep_image(DIR_WS_IMAGES . 'infobox/corner_right_bottom.gif')?></td>      
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