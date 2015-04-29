<?php
/*
  $Id: stats_customers.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
        <?
        $c = 0;
        for($i = 0; $i < 30000; $i++){
            $query = tep_db_query("SELECT products_attributes_id, options_values_id, products_id, cod_unic  FROM products_attributes WHERE products_id=".$i." GROUP BY options_values_id HAVING count(options_values_id) > 1 ");
            while ($p = tep_db_fetch_array($query)) {
                //$arr[] = array($p['products_id'],$p['options_values_id'],$p['cod_unic']);
                 $new_options = (real)$p['options_values_id'] + 0.01;
                $q = tep_db_query('UPDATE products_attributes SET options_values_id = '.$new_options.' WHERE products_attributes_id='.$p['products_attributes_id']);
                echo$q.'<br>';
            }


        }
        print_r($arr);

         require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
