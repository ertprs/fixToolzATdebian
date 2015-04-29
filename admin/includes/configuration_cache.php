<?php
/*
$Id: configuration_cache.php,v 1.32 2004/04/06 20:24:09 daemonj Exp $

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2003 osCommerce

Released under the GNU General Public License
*/
$rQuery  = tep_db_query('SELECT `categories_id` FROM `categories`');
$aResult = array();
while ($r = tep_db_fetch_array($rQuery)) {
   $aResult[] = $r['categories_id'];
}
function count_products_in_category($category_id, $include_inactive = false) {
   $products_count = 0;
   if ($include_inactive == true) {
      $products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = p2c.products_id and p2c.categories_id = '" . (int) $category_id . "'");
   } else {
      $products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = p2c.products_id and p.products_status = '1' and p2c.categories_id = '" . (int) $category_id . "'");
   }
   $products = tep_db_fetch_array($products_query);
   $products_count += $products['total'];
   $child_categories_query = tep_db_query("select categories_id from " . TABLE_CATEGORIES . " where parent_id = '" . (int) $category_id . "'");
   if (tep_db_num_rows($child_categories_query)) {
      while ($child_categories = tep_db_fetch_array($child_categories_query)) {
         $products_count += count_products_in_category($child_categories['categories_id'], $include_inactive);
      }
   }
   return $products_count;
}
function count_has_category_subcategories($category_id) {
   $child_category_query = tep_db_query("select count(*) as count from " . TABLE_CATEGORIES . " where parent_id = '" . (int) $category_id . "'");
   $child_category       = tep_db_fetch_array($child_category_query);
   return $child_category['count'];
}
if (isset($config_cache_file) && $config_cache_file != '') {
   $config_cache_output = '<?php' . "\n";
   $configuration_query = tep_db_query('select configuration_key as cfgKey, configuration_value as cfgValue from ' . TABLE_CONFIGURATION);
   while ($configuration = tep_db_fetch_array($configuration_query)) {
      $config_cache_output .= 'define(\'' . $configuration['cfgKey'] . '\',\'' . addslashes($configuration['cfgValue']) . '\'); ' . "\n";
   }
   foreach ($aResult as &$value) {
      $config_cache_output .= 'define(\'' . 'CATCOUNT_' . $value . '\',\'' . addslashes(count_products_in_category($value)) . '\'); ' . "\n";
      $config_cache_output .= 'define(\'' . 'SUBCATCOUNT_' . $value . '\',\'' . addslashes(count_has_category_subcategories($value)) . '\'); ' . "\n";
   }
   $config_cache_output .= '?>';
   if (isset($config_cache_compress) && $config_cache_compress == 'true') {
      if (!isset($config_cache_compression_level) || ($config_cache_compression_level < 0 || $config_cache_compression_level > 4)) {
         // if the compression level is not defined, is negative, or greater than 5 then default to 1
         $config_cache_compression_level = 1;
      }
      $config_cache_output     = gzdeflate("?>$config_cache_output<?", $config_cache_compression_level);
      $config_cache_output     = base64_encode($config_cache_output);
      $new_config_cache_output = '';
      while (strlen($config_cache_output) > 0) {
         $new_config_cache_output .= substr($config_cache_output, 0, 80) . "\n";
         $config_cache_output = substr($config_cache_output, 80);
      }
      $config_cache_output = "<?php eval(gzinflate(base64_decode('\n$new_config_cache_output'))); ?>";
   }
   if (file_exists($config_cache_file))
      unlink($config_cache_file);
   $fp   = fopen($config_cache_file, 'w');
   $fout = fwrite($fp, $config_cache_output);
   fclose($fp);
}
?>