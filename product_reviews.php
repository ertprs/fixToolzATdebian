<?php
/*
  $Id: product_reviews.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $product_info_query = tep_db_query("select p.products_id, p.products_model, p.products_image, p.products_price, p.products_tax_class_id, pd.products_name, tm.manufacturers_name, tm.manufacturers_image from " . TABLE_PRODUCTS . "  p left join " . TABLE_MANUFACTURERS . " tm on p.manufacturers_id=tm.manufacturers_id," . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . (int)$_GET['products_id'] . "' and p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'");
  if (!tep_db_num_rows($product_info_query)) {
    tep_redirect(tep_href_link(FILENAME_REVIEWS));
  } else {
    $product_info = tep_db_fetch_array($product_info_query);
  }

  if ($new_price = tep_get_products_special_price($product_info['products_id'])) {
    $products_price = '<s>' . $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($new_price, tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span>';
  } else {
    $products_price = $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id']));
  }

  if (tep_not_null($product_info['products_model'])) {
    $products_name = $product_info['products_name'] . '<br><span class="smallText">[' . $product_info['products_model'] . ']</span>';
  } else {
    $products_name = $product_info['products_name'];
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_REVIEWS);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params()));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo HEADING_TITLE." - ".TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<script language="javascript"><!--
function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=100,height=100,screenX=150,screenY=150,top=150,left=150')
}
//--></script>
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
            <td class="pageHeading" valign="bottom"><?php echo $products_name; ?></td>
            <td class="pageHeading" align="right" valign="top"><?php echo tep_image(DIR_WS_IMAGES_MANUFACTURERES . $product_info['manufacturers_image'], $product_info['manufacturers_name'], 150, 50) ?></td>
        </table></td>
      </tr>
      <tr>
        <td class="subcateg" style="font-size:3px">&nbsp;</td>
      </tr>
		<tr><td>
		<table width="100%">
          <tr>
            <td height="50" align="left"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params()) . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
            <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, tep_get_all_get_params()) . '">' . tep_image_button('button_write_review.gif', IMAGE_BUTTON_WRITE_REVIEW) . '</a>'; ?></td>
          </tr>
        </table></td>
      	</tr>
      <tr>
        <td class="optionTable">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
	      <tr>
	        <td class="reviewTh">Review-uri</td>
	      </tr>
<?php
  $reviews_query_raw = "select r.reviews_id, left(rd.reviews_text, 200) as reviews_text_short, rd.reviews_text, r.reviews_rating, r.date_added, r.customers_name from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd where r.products_id = '" . (int)$product_info['products_id'] . "' and r.reviews_id = rd.reviews_id and rd.languages_id = '" . (int)$languages_id . "' order by r.reviews_id desc";
  $reviews_split = new splitPageResults($reviews_query_raw, MAX_DISPLAY_NEW_REVIEWS);

  if ($reviews_split->number_of_rows > 0) {

    $reviews_query = tep_db_query($reviews_split->sql_query);
    while ($reviews = tep_db_fetch_array($reviews_query)) {
?>
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="5">
              <tr>
                <td class="review"><b><?=tep_output_string_protected($reviews['customers_name']) ?></b> | <?=tep_date_long($reviews['date_added'])?></td>
                <td class="review" align="right">Rating: 
<?
        	for($i=0; $i < 5; $i++){
?>
        		<img src="images/icons/<?=$i<$reviews['reviews_rating']?"star_gold.gif":"star_silver.gif"?>">
<?
			}
?>            
                </td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td class="review">
				<blockquote id="text_<?=$reviews['reviews_id']?>"><?php echo tep_break_string(tep_output_string_protected($reviews['reviews_text_short']), 70, '-<br>') ?></blockquote>
				<div id="text_hidden_<?=$reviews['reviews_id']?>" style="visibility:hidden;font-size:0px"><?=tep_break_string(nl2br(tep_output_string_protected($reviews['reviews_text'])), 60, '-<br>')?></div>
            </td>
          </tr>
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="5">
              <tr>
              	<td width="370">&nbsp;</td>
                <td align="right"><a onclick="document.getElementById('text_<?=$reviews['reviews_id']?>').innerHTML=document.getElementById('text_hidden_<?=$reviews['reviews_id']?>').innerHTML" ><nobr style="cursor:pointer"><img src="images/icons/arrow.gif" align="left" border="0">Citeste mai mult</nobr></a></td>
              </tr>
            </table></td>
          </tr>
	      <tr>
	        <td class="subcateg" style="font-size:3px">&nbsp;</td>
	      </tr>
<?php
    }
?>
<?php
  } else {
?>
          <tr>
            <td><?php new infoBox(array(array('text' => TEXT_NO_REVIEWS))); ?></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
<?php
  }

  if (($reviews_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>

	      <tr>
	        <td class="pageResults" style="background-color:#efefef" colspan="7" height="30" valign="" align="left">&nbsp;&nbsp;&nbsp;<?=$reviews_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y')))?>&nbsp;&nbsp;&nbsp;<?=$reviews_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS)?></td>
	      </tr>
	      
<?php
  }
?>
		</table>
		</td></tr>
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
