<?php
/*
  $Id: shopping_cart.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- shopping_cart //-->
          <tr>
            <td>
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => BOX_HEADING_SHOPPING_CART);

  //new infoBoxHeading($info_box_contents, false, true, tep_href_link(FILENAME_SHOPPING_CART));

  $cart_contents_string = '';
  if ($cart->count_contents() > 0) {
  	$cart_contents_string = '<table width="100%"><tr><td valign="middle" align="center"><img alt="bullet" src="images/cart/bullet.gif"></td><td valign="middle"><a href='.tep_href_link(FILENAME_SHOPPING_CART).' class="infoBoxLink">Vizualizeaza produsele din cos</a></td></tr></table><div class="boxTextLine" style="height:5;width:92%"></div>';
    
    $cart_contents_string .= '<table border="0" width="92%" cellspacing="0" cellpadding="0">';
    $products = $cart->get_products();

//      echo '<!--aici';
//      var_dump($products);
//      echo '-->';
    for ($i=0, $n=sizeof($products); $i<$n; $i++) {
      
      if ((tep_session_is_registered('new_products_id_in_cart')) && ($new_products_id_in_cart == $products[$i]['id'])) {
        $styleClass = 'newItemInCart';
      } else {
        $styleClass = 'infoBoxContents2';
      }
      
      $cart_contents_string .= '<tr><td align="right" valign="top" class="'.$styleClass.'">';
      $cart_contents_string .= '&nbsp;&nbsp;'.$products[$i]['quantity'] . '&nbsp;x&nbsp;</span></td><td valign="top" class="' . $styleClass . '"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '">';
      $cart_contents_string .= $products[$i]['name'] . '</a></td></tr><tr><td colspan="2"><div class="boxTextLine" style="height:5;margin-top:5px"></td></tr>';

      if ((tep_session_is_registered('new_products_id_in_cart')) && ($new_products_id_in_cart == $products[$i]['id'])) {
        tep_session_unregister('new_products_id_in_cart');
      }
    }
    if($easy_discount->total()>0){
	$cart_contents_string .= '<tr><td colspan="2" align="right" class="cartText">Discount: <b>- '.$currencies->format((round($easy_discount->total(),2))).'</b></td></tr>';
    } 
    $cart_contents_string .= '<tr><td colspan="2" align="right" class="cartText">TOTAL (cu TVA): <b>'.$currencies->format($cart->show_total() - round($easy_discount->total(),2)).'</b></td></tr>';
    $cart_contents_string .= '</table>';
  } else {
    //$cart_contents_string .= '<table width="100%"><tr><td valign="middle" align="center"><img src="images/icons/cart_empty.gif"></td><td class="infoBoxLink" valign="middle">Cosul este gol</td></tr></table><div style="height:10"></div>';
  }

  $info_box_contents = array();
  $info_box_contents[] = array('text' => $cart_contents_string);

  if ($cart->count_contents() > 0) {
    $info_box_contents[] = array('text' => '<div class="boxTextLine"></div>');
    $info_box_contents[] = array('align' => 'right',
                                 'text' => 'Discount: <font color="red">- '.$currencies->format((round($easy_discount->total(),2))).'<br><b>'.$currencies->format($cart->show_total() - round($easy_discount->total(),2)).'</b>&nbsp;&nbsp;&nbsp;' );
  }

  //new infoBox($info_box_contents);
?>        

              <table width="100%" border="0" cellpadding="0" cellspacing="0">
              	<tr>
              	  <td><?=tep_image(DIR_WS_IMAGES . 'cart/top_left_bg.gif')?></td>
              	  <td class="cartTop" width="100%">&nbsp;</td>
              	  <td><?=tep_image(DIR_WS_IMAGES . 'cart/top_right_bg.gif')?></td>
              	</tr>
              	<tr>
              	  <td class="cartMiddle" colspan="3"><table><tr><td class="cartText" width="73%"><b>COS DE CUMPARATURI</b></td><td rowspan="2"><?=tep_image(DIR_WS_IMAGES . 'cart/cart.gif')?></td></tr><tr><td class="cartText" style="color:#666;">Aveti in cos: <?=$cart->count_contents()?> produs<?=$cart->count_contents()!=1?"e":""?></td></tr></table></td>
              	</tr>
              	<?if($cart_contents_string!=""){?><tr><td class="cartMiddle" colspan="3"><?=$cart_contents_string?></td></tr><?}?>
              	<tr>
              	  <td class="cartMiddle" colspan="3" align="center" height="35" valign="bottom"><?='<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '">' . tep_image_button('plateste.gif', IMAGE_BUTTON_CHECKOUT) . '</a>'?></td>
              	</tr>
<!--              	<tr>
              	  <td class="cartMiddle" colspan="3" align="left" height="10" valign="middle"><DIV class="boxTextLine" style="width:98%"></div></td>
              	</tr>              	
              	<tr>
              	  <td class="cartMiddle" colspan="3" align="left" height="35" valign="middle">
              	  	<table width="100%" border="0" cellpadding="0" cellspacing="0">
              	  		<tr>
              	  			<td valign="middle" width="60%"><img src="images/icons/epayment.png"></td>
              	  			<td valign="middle"><img src="images/icons/mastercard.png"></td>
              	  			<td valign="middle"><img src="images/icons/visa.png"></td>
              	  		</tr>
              	  	</table>
              	  </td>
              	</tr>-->
              	<tr>
              	  <td><?=tep_image(DIR_WS_IMAGES . 'cart/bottom_left_bg.gif')?></td>
              	  <td class="cartBottom" width="100%">&nbsp;</td>
              	  <td><?=tep_image(DIR_WS_IMAGES . 'cart/bottom_right_bg.gif')?></td>
              	</tr>
              </table>
            </td>
          </tr>
<!-- shopping_cart_eof //-->