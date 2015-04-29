<?php
/*
  $Id: specials.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  if ($random_product = tep_random_select("select p.products_id, pd.products_name, p.products_price, p.products_tax_class_id, p.products_image, s.specials_new_products_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_SPECIALS . " s where p.products_status = '1' and p.products_id = s.products_id and pd.products_id = s.products_id and pd.language_id = '" . (int)$languages_id . "' and s.status = '1' order by s.specials_date_added desc limit " . MAX_RANDOM_SELECT_SPECIALS)) {

      if (tep_not_null($random_product['specials_new_products_price'])) {
          $price = [$currencies->display_price($random_product['products_price'], tep_get_tax_rate($random_product['products_tax_class_id'])),
              $currencies->display_price($random_product['specials_new_products_price'], tep_get_tax_rate($random_product['products_tax_class_id']))];
      } else {
          $price  = [$currencies->display_price($random_product['products_price'], tep_get_tax_rate($random_product['products_tax_class_id']))];
      }
      $buyLink = tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $random_product['products_id']);

      echo $whats_new_price;

      $desc = $random_product['products_name'];
      $img = tep_image(DIR_WS_IMAGES ."mari/". strtoupper($random_product['products_image'][0]).'/'. $random_product['products_image'], $random_product['products_name'], 130, 130);
      $url = tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product['products_id']);


      $box = new nicePromoBox(['Promotii',tep_href_link(FILENAME_BEST_SELLERS)],
          $img,[$desc,$url],
          $price,
          ['Adauga in cos',$buyLink]);
      $boxDecorator = new BoxDecorator($box,BoxDecorator::PROMO_BOX);
      echo $boxDecorator;


  }
