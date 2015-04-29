<?php
/*
  $Id: also_purchased_products.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  if (isset($_GET['products_id'])) {
    $new_products_query = tep_db_query("select p.products_id, p.products_image from " . TABLE_ORDERS_PRODUCTS . " opa, " . TABLE_ORDERS_PRODUCTS . " opb, " . TABLE_ORDERS . " o, " . TABLE_PRODUCTS . " p where opa.products_id = '" . (int)$_GET['products_id'] . "' and opa.orders_id = opb.orders_id and opb.products_id != '" . (int)$_GET['products_id'] . "' and opb.products_id = p.products_id and opb.orders_id = o.orders_id and p.products_status = '1' group by p.products_id order by o.date_purchased desc limit 4 ");
  $num_rows = tep_db_num_rows($new_products_query);
  if($num_rows != 0){
?>
<!-- new_products //-->
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td height="32" valign="top" width="8"><img src="images/infobox/corner_left_top.gif" alt="" border="0" height="32" width="8"></td>
		<td class="infoBoxHeading2" height="32" valign="top">&nbsp;&nbsp;Alte produse cumparate de clientii care au cumparat acest produs</td>
		<td height="32" valign="top" width="8"><img src="images/infobox/corner_right_top.gif" alt="" border="0" height="32" width="8"></td>
	</tr>
	<tr>
		<td class="boxBgLeft" height="5">&nbsp;</td>
		<td height="5"></td>
		<td class="boxBgRight" height="5">&nbsp;</td>
	</tr>
	<tr>
		<td class="boxBgLeft" height="5">&nbsp;</td>
		<td align="center">
<?php


  $row = 0;
  $col = 0;
  $info_box_contents = array();
  while ($new_products = tep_db_fetch_array($new_products_query)) {
   	$new_products['products_name'] = tep_get_products_name($new_products['products_id']);
   	$special = "";
   	$price = $new_products['products_price'];
   	if($new_products['specials_new_products_price']){
	    $old_price = '<s class="old_price" style="font-size:12px">' . $currencies->display_price($new_products['products_price'], tep_get_tax_rate($new_products['products_tax_class_id'])) . '</s><br>';
   		$price = $new_products['specials_new_products_price'];
   	}
	    $info_box_contents[$row][$col] = array('align' => 'center',
	                                           'params' => 'width="20%" valign="top"',
	                                           'text' => '<table>' .
	                                           				'<tr>' .
	                                           					'<td colspan="3" align="left" height="110" valign="top">' .
	                                           						'<center><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products['products_id']) . '">' . tep_image(DIR_WS_IMAGES ."mici/" .strtoupper($new_products['products_image'][0]).'/'. $new_products['products_image'], $new_products['products_name']) . '</a></center>' .
	                                           						'<a class="newProd" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products['products_id']) . '">' . $new_products['products_name'] . '</a>' .
	                                           					'</td>' .
	                                           				'</tr>' .
	                                           			'</table>');
   	
    $col++;
    if ($col < 6){
	    if($num_rows > 1 && !($num_rows == 2 && $col==3) && !($num_rows == 3 && $col==5)){
			$info_box_contents[$row][$col] = array('align' => 'center',
			                                           'params' => 'class="vdotline" width="1%" valign="top"',
	    	                                           'text' => '&nbsp;');
	    }
    $col ++;
    }
    if ($col > 6) {
      $col = 0;
      $row ++;
    }
  }
//  print_r($info_box_contents);
  new productListingBox($info_box_contents);
?>
		</td>
		<td class="boxBgRight" height="5">&nbsp;</td>
	</tr>
	<tr>
		<td><?=tep_image(DIR_WS_IMAGES . 'infobox/corner_left_bottom.gif')?></td>
		<td width="100%" style="border-bottom: 1px solid #4791d9; font-size:1px;">&nbsp;</td>
		<td><?=tep_image(DIR_WS_IMAGES . 'infobox/corner_right_bottom.gif')?></td>
	</tr>
</table>
<!-- new_products_eof //-->
<?}}?>
