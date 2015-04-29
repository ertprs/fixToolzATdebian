<?php
/*
  $Id: sitemonitor.php,v 1.2 2006/10/28 by Jack_mcs at oscommerce-solution.com

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce
  Portions Copyright 2009 oscommerce-solution.com

  Released under the GNU General Public License
*/

  $instance = ((isset($_GET['instance']) && (int)$_GET['instance'] >= 0) ? (int)$_GET['instance'] : '0');

  require('sitemonitor_configure' . '_' . $instance  . '.php');
  require('includes/functions/sitemonitor_functions.php');

  if(! (bool)ini_get('safe_mode'))
      set_time_limit(0);

  $logFile = 'sitemonitor_log' . '_' . $instance . '.txt';
  $referenceFile = 'sitemonitor_reference' . '_' . $instance . '.php';


  if ($reference_reset > 0) { //delete the reference file
      if (file_exists($referenceFile)) {
         $r = @stat($referenceFile);
         if (floor((time()- $r[9]) / 86400) > $reference_reset) {
             runSitemonitor($referenceFile, $logFile, $verbose);  //first run the script to catch any late changes

             if (unlink($referenceFile)) { //then remove it
                 if ($verbose) echo 'Reference file deleted due to reset.';
             }
         }
      }
  }

  runSitemonitor($referenceFile, $logFile, $verbose);
?>