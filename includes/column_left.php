<?php
/*
  $Id: column_left.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  if ((USE_CACHE == 'true') && empty($SID)) {
    echo tep_cache_categories_box();
  } else {
    include(DIR_WS_BOXES . 'categories.php');
      }
include_once('classes/myBoxes.php');
    //include(DIR_WS_BOXES . 'telefon.php');
    include(DIR_WS_BOXES . 'phone_me.php');
    include(DIR_WS_BOXES . 'fidelizare_client.php');
    include(DIR_WS_BOXES . 'voucher.php');
    include(DIR_WS_BOXES . 'social_networks.php');
    include(DIR_WS_BOXES . 'newsletter.php');
    //include(DIR_WS_BOXES . 'afiliere.php');

//  if ((USE_CACHE == 'true') && empty($SID)) {
//    echo tep_cache_manufacturers_box();
//  } else {
//    include(DIR_WS_BOXES . 'manufacturers.php');
//    include(DIR_WS_BOXES . 'spacing.php');
//  }}


?>