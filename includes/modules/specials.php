<?php
/*
  $Id: new_products.php 1806 2008-01-11 22:48:15Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2008 osCommerce

  Released under the GNU General Public License
*/

  $specials_query_raw = "select p.products_id, pd.products_name, p.products_price, p.products_tax_class_id, p.products_image, s.specials_new_products_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_SPECIALS . " s where p.products_status = '1' and p.products_id = s.products_id and pd.products_id = s.products_id and pd.language_id = '" . (int)$languages_id . "' and s.status = '1' order by s.specials_date_added desc limit 0,4";

$new_products_query = tep_db_query($specials_query_raw);

$num_rows = tep_db_num_rows($new_products_query);

if(tep_db_num_rows($new_products_query) > 0){

    ?> <section class="promoGroup row">
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
