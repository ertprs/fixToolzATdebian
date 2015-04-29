
<?php
 
  $specials_query_raw = "(select p.products_image, pd.products_name, p.products_id, p.products_price, p.products_tax_class_id, if( s.status, s.specials_new_products_price, null ) as specials_new_products_price from products_description pd, products p left join specials s on p.products_id = s.products_id, products_to_categories p2c where p.products_status = '1' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '1' and p.products_ordered > 0 order by products_ordered DESC, pd.products_name DESC LIMIT 0,9)";

  if ($random_product = tep_random_select($specials_query_raw)) {

    if (tep_not_null($random_product['specials_new_products_price'])) {
      $price = [$currencies->display_price($random_product['products_price'], tep_get_tax_rate($random_product['products_tax_class_id'])),
      $currencies->display_price($random_product['specials_new_products_price'], tep_get_tax_rate($random_product['products_tax_class_id']))];
    } else {
      $price  = [$currencies->display_price($random_product['products_price'], tep_get_tax_rate($random_product['products_tax_class_id']))];
    }
      $buyLink = tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $random_product['products_id']);

      $desc = $random_product['products_name'];
      $img = tep_image(DIR_WS_IMAGES ."mari/". strtoupper($random_product['products_image'][0]).'/'. $random_product['products_image'], $random_product['products_name'], 130, 130);
      $url = tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product['products_id']);


  $box = new nicePromoBox(['Cele mai vandute',tep_href_link(FILENAME_BEST_SELLERS)],
      $img,[$desc,$url],
      $price,
      ['Adauga in cos',$buyLink]);
  $boxDecorator = new BoxDecorator($box,BoxDecorator::PROMO_BOX);
  echo $boxDecorator;


  }


