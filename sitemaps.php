<?php
	/**
	 * Google Sitemap Generator
	 * 
	 * Script to generate a Google sitemap for osCommerce based stores
	 *
	 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
	 * @version 1.2
	 * @link http://www.oscommerce-freelancers.com/ osCommerce-Freelancers
	 * @copyright Copyright 2006, Bobby Easland 
	 * @author Bobby Easland 
	 * @filesource
	 */
  
	/*
	 * Include the application_top.php script
	 */
	 //Modified by misstop.co.uk 01/12/2008
	 
	 include_once('includes/application_top.php');
	 //define('SEARCH_ENGINE_FRIENDLY_URLS', 'false');
	 
	/*
	 * Send the XML content header
	 */
	header('Content-Type: text/xml');
	
	/*
	 * Echo the XML out tag
	 */
	echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
 <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php

	/*
	 * Define the uniform node function 
	 */
	function GenerateNode($data){
		$content = '';
		$content .= "\t" . '<url>' . "\n";
		$content .= "\t\t" . '<loc>'.trim($data['loc']).'</loc>' . "\n";
		$content .= "\t\t" . '<lastmod>'.trim($data['lastmod']).'</lastmod>' . "\n";
		$content .= "\t\t" . '<changefreq>'.trim($data['changefreq']).'</changefreq>' . "\n";
		$content .= "\t\t" . '<priority>'.trim($data['priority']).'</priority>' . "\n";
		$content .= "\t" . '</url>' . "\n";
		return $content;
	} # end function

	/*
	 * Define the SQL for the products query 
	 */
	$sql2 = "SELECT products_id as pID, 
								 products_id as products_id 
					FROM " . TABLE_PRODUCTS . ""; 
	
	$query2 = tep_db_query($sql2);

		$container = array();
		$number = 0;
		$top = 0;
			
			$container = array('loc' => htmlspecialchars(utf8_encode('http://www.toolszone.ro/')),
								 				 'lastmod' => date("Y-m-d"),
								 				 'changefreq' => 'weekly',
								 				 'priority' => '1'
												);

			echo generateNode($container);
      		
		while( $pID = tep_db_fetch_array($query2)){
			$top = max($top, $pID['products_id'], $result['date_added']);
			$location = tep_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . $pID['pID'] . '&'. 'reviews_id=' . $result['rID'], 'NONSSL', false);
			$location = tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $pID['pID']);
			$lastmod = date("Y-m-d"); 
			$changefreq = 'weekly';
			$ratio = ($top > 0) ? ($result['date_added']/$top) : 0;
			$priority = $ratio < .1 ? .1 : number_format($ratio, 1, '.', ''); 
			
			$container = array('loc' => htmlspecialchars(utf8_encode($location)),
								 				 'lastmod' => $lastmod,
								 				 'changefreq' => $changefreq,
								 				 'priority' => '1'
												);

			echo generateNode($container);
		}

	$sql2 = "SELECT categories_id, parent_id
					FROM " . TABLE_CATEGORIES . ""; 
	
	$query2 = tep_db_query($sql2);
  		
		while( $pID = tep_db_fetch_array($query2)){
			$top = max($top, $pID['products_id'], $result['date_added']);
			
      
      $location = tep_href_link(FILENAME_DEFAULT, 'cPath=' . $pID['parent_id'] . '_' . $pID['categories_id']);
			$lastmod = date("Y-m-d"); 
			$changefreq = 'weekly';
			$ratio = ($top > 0) ? ($result['date_added']/$top) : 0;
			$priority = $ratio < .1 ? .1 : number_format($ratio, 1, '.', ''); 
			
			$container = array('loc' => htmlspecialchars(utf8_encode($location)),
								 				 'lastmod' => $lastmod,
								 				 'changefreq' => $changefreq,
								 				 'priority' => '1'
												);

			echo generateNode($container);
		}
	echo '</urlset>';

?>
