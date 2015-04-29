<?php
/*
  $Id: header_tags_seo_popup_help.php,v 1.0 2009/03/13 13:45:11 devosc Exp $
  produced by Jack_mcs
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
 
  $fp = file($_SERVER['QUERY_STRING']);
  $ctr = 1;
  foreach ($fp as $line)
  {
     $pos = sprintf("%04d&nbsp;&nbsp;", $ctr);
     echo '&nbsp;' . $pos . htmlspecialchars($line) . '<br>';
     $ctr++;
  }   
?>