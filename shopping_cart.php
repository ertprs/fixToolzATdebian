<?php
/*
  $Id: shopping_cart.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License
*/
           
  require("includes/application_top.php");

  if ($cart->count_contents() > 0) {
    include(DIR_WS_CLASSES . 'payment.php');
    $payment_modules = new payment;
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_SHOPPING_CART);
  include_once (DIR_WS_FUNCTIONS.'easy_discount.php');

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_SHOPPING_CART));


  

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
<?php echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_SHOPPING_CART, 'action=update_product')); ?>

<?php
  if ($cart->count_contents() > 0) {
?>
      <tr>
        <td>
<?php
	$content .= '<table border="0" width="100%" cellspacing="0" cellpadding="0">';	
	$content .= '<tr><td height="30" class="sortTh" width="5px"></td>';
	
	$content .= '<td width="10%"class="sortTh" width="5px">&nbsp;'.TABLE_HEADING_REMOVE.'</td>';
	$content .= '<td width="60%"class="sortTh" width="5px">'.TABLE_HEADING_PRODUCTS.'</td>';
	$content .= '<td width="15%"class="sortTh" width="5px">'.TABLE_HEADING_QUANTITY.'</td>';
	$content .= '<td width="15%"class="sortTh" width="5px">'.TABLE_HEADING_TOTAL.'</td>';
	
	$content .= '</tr>';
    $info_box_contents = array();
	                                    
    $any_out_of_stock = 0;
    $products = $cart->get_products();
    for ($i=0, $n=sizeof($products); $i<$n; $i++) {
// Push all attributes information in an array
      if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
        while (list($option, $value) = each($products[$i]['attributes'])) {
          echo tep_draw_hidden_field('id[' . $products[$i]['id'] . '][' . $option . ']', $value);
          $attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix
                                      from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                                      where pa.products_id = '" . (int)$products[$i]['id'] . "'
                                       and pa.options_id = '" . (int)$option . "'
                                       and pa.options_id = popt.products_options_id
                                       and pa.options_values_id = '" . (int)$value . "'
                                       and pa.options_values_id = poval.products_options_values_id
                                       and popt.language_id = '" . (int)$languages_id . "'
                                       and poval.language_id = '" . (int)$languages_id . "'");
          $attributes_values = tep_db_fetch_array($attributes);

          $products[$i][$option]['products_options_name'] = $attributes_values['products_options_name'];
          $products[$i][$option]['options_values_id'] = $value;
          $products[$i][$option]['products_options_values_name'] = $attributes_values['products_options_values_name'];
          $products[$i][$option]['options_values_price'] = $attributes_values['options_values_price'];
          $products[$i][$option]['price_prefix'] = $attributes_values['price_prefix'];
        }
      }
    }

    for ($i=0, $n=sizeof($products); $i<$n; $i++) {

      $cur_row = sizeof($info_box_contents) - 1;
      
	  $content .= '<tr>';
	  $content .= '<td class="subcateg">&nbsp;</td><td class="subcateg" valign="middle" align="center">'.tep_draw_checkbox_field('cart_delete[]', $products[$i]['id']).'</td>';
	  
      $products_name = '<table border="0" cellspacing="2" cellpadding="2">' .
                       '  <tr>' .
                       '    <td class="productListing-data" align="center"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '">' . tep_image(DIR_WS_IMAGES . 'mici/'  . $products[$i]['image'], $products[$i]['name'],50 ,50 ) . '</a></td>' .
                       '    <td class="productListing-data" valign="center"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '"><b>' . $products[$i]['name'] . '</b></a>';

      if (STOCK_CHECK == 'true') {
        $stock_check = tep_check_stock($products[$i]['id'], $products[$i]['quantity']);
        if (tep_not_null($stock_check)) {
          $any_out_of_stock = 1;

          $products_name .= $stock_check;
        }
      }

      if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
        reset($products[$i]['attributes']);
        while (list($option, $value) = each($products[$i]['attributes'])) {
          $products_name .= '<br><small><i> - ' . $products[$i][$option]['products_options_name'] . ' ' . $products[$i][$option]['products_options_values_name'] . '</i></small>';
        }
      }

      $products_name .= '    </td>' .
                        '  </tr>' .
                        '</table>';

	  $content .= '<td class="subcateg" valign="middle" align="left">'.$products_name.'</td>';
	  $content .= '<td class="subcateg" valign="middle" align="left">'.tep_draw_input_field('cart_quantity[]', $products[$i]['quantity'], 'class="input" size="4"') . tep_draw_hidden_field('products_id[]', $products[$i]['id']).'</td>';
	  $content .= '<td class="subcateg productListing-total" valign="middle" align="right" style="color:red"><nobr><b>' . $currencies->display_price($products[$i]['final_price'], tep_get_tax_rate($products[$i]['tax_class_id']), $products[$i]['quantity']) . '</b></nobr>&nbsp;&nbsp;&nbsp;</td>';
	  $content .= '</tr>';
                                    
    }
if ($easy_discount->count() > 0) {

	$content .= '<tr>';
	$content .= '<td class="subcateg productListing-total" colspan="5" valign="middle" align="right" style="color:red" height="40">'.easy_discount_display().'</td>';
	$content .= '</tr>'; 
}    
    
	$content .= '<tr>';
	$content .= '<td class="subcateg productListing-total" colspan="5" valign="middle" align="right" style="color:red" height="40">Total: '.$currencies->format($cart->show_total() - $easy_discount->total()).'&nbsp;&nbsp;&nbsp;</td>';
	$content .= '</tr>';  
    
	$content .= '<tr><td colspan="5" height="10">&nbsp;</td></tr>';
    
    $back = sizeof($navigation->path)-2;
	if (isset($navigation->path[$back])) {
		$back_button = '<a href="' . tep_href_link($navigation->path[$back]['page'], tep_array_to_string($navigation->path[$back]['get'], array('action')), $navigation->path[$back]['mode']) . '">' . tep_image_button('button_continue_shopping.gif', IMAGE_BUTTON_CONTINUE_SHOPPING) . '</a>';
	} else {
		$back_button = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';		
	} 
	$content .= '<tr>';
	                                                                                                                               
    $content .= '<td class="subcateg productListing-total" colspan="5" valign="middle" align="right" style="color:red" height="40">'.tep_image_submit('button_update_cart.gif', IMAGE_BUTTON_UPDATE_CART).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$back_button.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '">' . tep_image_button('button_checkout.gif', IMAGE_BUTTON_CHECKOUT) . '</a>&nbsp;&nbsp;</form></td>';
	$content .= '</tr>';    
	$content .= '<tr><td colspan="5" height="10">&nbsp;</td></tr>';
	$content .= '<tr>';
  $content .= '<td class="productListing-total" colspan="5" valign="middle" align="right" style="color:red">';
  $content .= tep_draw_form('cart_voucher', tep_href_link(FILENAME_SHOPPING_CART, ''));
  $content .= 'Adauga Cod Voucher&nbsp;'.tep_draw_input_field('voucher_id', '', 'class="input"' ).'<input type="submit" value="Adauga">';
  $content .= '&nbsp;&nbsp;</td>';
	$content .= '</tr>';
	$content .= '</table>';                                     
	//print_r ($info_box_contents);
    //new productListingBox($info_box_contents);
?>

	<table border="0" width="100%" cellspacing="0" cellpadding="0" >
      <tr>
        <td height="32" valign="top" width="8"><img src="images/infobox/corner_left_top.gif" alt="" border="0" height="32" width="8"></td>
		<td class="infoBoxHeading2" height="32" valign="top" align="left">&nbsp;&nbsp;Cos de cumparaturi</td>
		<td height="32" valign="top" width="8"><img src="images/infobox/corner_right_top.gif" alt="" border="0" height="32" width="8"></td>
      </tr>
      <tr>
        <td class="boxBgLeft boxBgRight" colspan="3" align="center"><?=$content?></td>
      </tr>
      <tr>
		<td><?=tep_image(DIR_WS_IMAGES . 'infobox/corner_left_bottom.gif')?></td>  
		<td width="100%" style="border-bottom: 1px solid #4791d9; font-size:1px;">&nbsp;</td>  
		<td><?=tep_image(DIR_WS_IMAGES . 'infobox/corner_right_bottom.gif')?></td>      
      </tr>      
    </table>  
        </td>
      </tr>
<?php
    $initialize_checkout_methods = $payment_modules->checkout_initialization_method();

    if (!empty($initialize_checkout_methods)) {
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td align="right" class="main" style="padding-right: 50px;"><?php echo TEXT_ALTERNATIVE_CHECKOUT_METHODS; ?></td>
      </tr>
<?php
      reset($initialize_checkout_methods);
      while (list(, $value) = each($initialize_checkout_methods)) {
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td align="right" class="main"><?php echo $value; ?></td>
      </tr>
<?php
      }
    }
  } else {
  	
?>	
	  <tr><td>
	<table border="0" width="100%" cellspacing="0" cellpadding="0" >
      <tr>
        <td height="32" valign="top" width="8"><img src="images/infobox/corner_left_top.gif" alt="" border="0" height="32" width="8"></td>
		<td colspan="2" class="infoBoxHeading2" height="32" valign="top" align="left">&nbsp;&nbsp;Cos gol</td>
		<td height="32" valign="top" width="8"><img src="images/infobox/corner_right_top.gif" alt="" border="0" height="32" width="8"></td>
      </tr>
      <tr>
      	<td class="boxBgLeft" height="5">&nbsp;</td>
        <td>&nbsp;&nbsp;&nbsp;<img src="images/emptycart.gif"></td>
        <td class="fieldKey">Cosul dumneavoastra este gol.</td>
        <td class="boxBgRight" height="5">&nbsp;</td>
      </tr>
      <tr>
      	<td class="boxBgLeft" height="5">&nbsp;</td>
        <td colspan="2" height="50" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td class="boxBgRight" height="5">&nbsp;</td>
      </tr>
      <tr>
		<td><?=tep_image(DIR_WS_IMAGES . 'infobox/corner_left_bottom.gif')?></td>  
		<td colspan="2" width="100%" style="border-bottom: 1px solid #4791d9; font-size:1px;">&nbsp;</td>  
		<td><?=tep_image(DIR_WS_IMAGES . 'infobox/corner_right_bottom.gif')?></td>      
      </tr>      
    </table> 
      </td></tr> 
<?php
  }
?>
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