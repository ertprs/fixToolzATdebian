<?php
/*
  $Id: column_right.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  //require(DIR_WS_BOXES . 'specials_buttons.php');

  //require(DIR_WS_BOXES . 'shopping_cart.php');

  
  require(DIR_WS_BOXES . 'postIt.php');

  //if (isset($_GET['products_id'])) include(DIR_WS_BOXES . 'manufacturer_info.php');

  if (tep_session_is_registered('customer_id')){ 
  	include(DIR_WS_BOXES . 'order_history.php');
    include(DIR_WS_BOXES . 'spacing.php');
  }
  
  if (isset($_GET['products_id'])) {
    if (tep_session_is_registered('customer_id')) {
      $check_query = tep_db_query("select count(*) as count from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . (int)$customer_id . "' and global_product_notifications = '1'");
      $check = tep_db_fetch_array($check_query);
      if ($check['count'] > 0) {
        include(DIR_WS_BOXES . 'best_sellers.php');
      } else {
        //include(DIR_WS_BOXES . 'product_notifications.php');
      }
    } else {
      //include(DIR_WS_BOXES . 'product_notifications.php');
    }
  } else {
    include(DIR_WS_BOXES . 'best_sellers.php');
  }

  if (!isset($_GET['products_id'])) {
    include(DIR_WS_BOXES . 'specials.php');
  }

  require(DIR_WS_BOXES . 'whats_new.php');
  
  
  
  //require(DIR_WS_BOXES . 'reviews.php');

//  if (substr(basename($PHP_SELF), 0, 8) != 'checkout') {
//    //include(DIR_WS_BOXES . 'languages.php');
//    //include(DIR_WS_BOXES . 'currencies.php');
//  }
//  require(DIR_WS_BOXES . 'search.php');
//  require(DIR_WS_BOXES . 'information.php');
//  include(DIR_WS_BOXES . 'spacing.php');
?>
