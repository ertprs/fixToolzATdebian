<?php
/*
  $Id: new_products.php 1806 2008-01-11 22:48:15Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2008 osCommerce

  Released under the GNU General Public License
*/
function retriveAllSubCategories($category){
	
	$query = "select categories_id from categories where parent_id = " . $category;
	$categories = tep_db_query($query);
	
	if(tep_db_num_rows($categories) > 0){
	
		$return_value = "";
		while ($cat = tep_db_fetch_array($categories)) 
		{
			$returned_cat = retriveAllSubCategories($cat['categories_id']);
			if($returned_cat!=""){
				$return_value .= $returned_cat . ",";
			}
		}
		
		 $return_value = str_replace(",,", ",", $return_value);
		 $return_value = str_replace(",,", ",", $return_value);
		 $return_value = str_replace(",,", ",", $return_value);
		 $return_value = str_replace(",,", ",", $return_value);
		 
		return $return_value;
	}else{
		return $category;
	}
}


  if ( (!isset($new_products_category_id)) || ($new_products_category_id == '0') ) {
    $query = "select p.products_id, p.products_image, p.products_tax_class_id, pd.products_name, if( s.status, s.specials_new_products_price, null ) as specials_new_products_price, products_price from " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' order by p.products_date_added desc limit 4";
  } else {
    $query = "select distinct p.products_id, p.products_image, p.products_tax_class_id, pd.products_name, if( s.status, s.specials_new_products_price, null ) as specials_new_products_price, products_price from " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c where p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and c.categories_id = '" . (int)$new_products_category_id . "' and p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' order by p.products_date_added desc limit 4";
  }
  $new_products_query = tep_db_query($query);
  if(tep_db_num_rows($new_products_query) == 0){
    $query = "select distinct p.products_id, p.products_image, p.products_tax_class_id, pd.products_name, if( s.status, s.specials_new_products_price, null ) as specials_new_products_price, products_price from " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c where p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and CASE WHEN c.parent_id_2 <>0 THEN c.parent_id_2 ELSE c.parent_id END = '" . (int)$new_products_category_id . "' and p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' order by p.products_date_added desc limit 4";
    $new_products_query = tep_db_query($query);
  }
  if(tep_db_num_rows($new_products_query) == 0){
      //WTF - bug?
    //$query = "select distinct p.products_id, p.products_image, p.products_tax_class_id, pd.products_name, if( s.status, s.specials_new_products_price, null ) as specials_new_products_price, products_price from " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c where p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and c.categories_id  in ( " . retriveAllSubCategories($new_products_category_id) . " 78978978978978) and p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' order by p.products_date_added desc limit 4";
    $query = "select distinct p.products_id, p.products_image, p.products_tax_class_id, pd.products_name, if( s.status, s.specials_new_products_price, null ) as specials_new_products_price, products_price from " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c where p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and c.categories_id  in ( " . retriveAllSubCategories($new_products_category_id) . " 78978978978978) and p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' order by p.products_date_added desc limit 4";
    $new_products_query = tep_db_query($query);
  }    
     // echo $query;
  
  $num_rows = tep_db_num_rows($new_products_query);
  if($num_rows != 0){

      ?> <section class="promoGroup  row">
      <h3><a href="<?=tep_href_link(FILENAME_SPECIALS)?>">Produse in promotie</a></h3>
      <?php
      while ($new_products = tep_db_fetch_array($new_products_query)) {

          if (tep_not_null($new_products['specials_new_products_price'])) {
              $price = [$currencies->display_price($new_products['products_price'], tep_get_tax_rate($new_products['products_tax_class_id'])),
                  $currencies->display_price($new_products['specials_new_products_price'], tep_get_tax_rate($new_products['products_tax_class_id']))];
          } else {
              $price  = [$currencies->display_price($new_products['products_price'], tep_get_tax_rate($new_products['products_tax_class_id']))];
          }
          $buyLink = tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $new_products['products_id']);


          $desc = $new_products['products_name'];
          $img = tep_image(DIR_WS_IMAGES ."mari/". strtoupper($new_products['products_image'][0]).'/'. $new_products['products_image'], $new_products['products_name'], 50, 50);
          $url = tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products['products_id']);

          $box = new BoxFragment(['Cele mai vandute',tep_href_link(FILENAME_BEST_SELLERS)],
              $img,[$desc,$url],
              $price,
              ['Adauga in cos',$buyLink]);
          $boxDecorator = new BoxDecorator($box,BoxDecorator::BOX_FRAGMENT);
          echo $boxDecorator;

      }
      ?></section><?php

}