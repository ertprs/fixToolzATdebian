                            <?php
/*
  $Id: newsletters.php 1751 2007-12-21 05:26:09Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License
*/

      
  /* gets the data from a URL */
  function get_data($url)
  {
  	$ch = curl_init();
  	$timeout = 5;
  	curl_setopt($ch,CURLOPT_URL,$url);
  	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
  	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
  	$data = curl_exec($ch);
  	curl_close($ch);
  	return $data;
  }
      
  require('includes/application_top.php');
  
  $action = (isset($_GET['action']) ? $_GET['action'] : '');
  $title = (isset($_POST['title']) ? $_POST['title'] : '');
  $addresses = (isset($_POST['addresses']) ? $_POST['addresses'] : '');
  $abonati = (isset($_POST['abonati']) ? $_POST['abonati'] : '');
          
      
      $message = get_data('http://www.toolszone.ro/newsletter.php');
      $css = get_data('http://www.toolszone.ro/stylesheet.css');
      
  
      $message = str_replace('<td class="infoBoxHeading2" height="32" valign="top"><a href="http://www.toolszone.ro/new_products.php" class="infoBoxTitle"><nobr><img src="images/icons/arrow_blue.gif" alt="mai multe" title=" mai multe " width="15" align="left" border="0" height="15">&nbsp;Produse Noi</nobr></a></td>','<td><img src="images/produse_noi_header.png"></td>',$message);
      
      $message = str_replace('<td class="infoBoxHeading2" height="32" valign="top">&nbsp;&nbsp;Cele mai vandute produse</td>','<td><img src="images/produse_vandute_header.png"></td>',$message);
      
      $message = str_replace('<td class="infoBoxHeading2" height="32" valign="top"><a href="http://www.toolszone.ro/specials.php" class="infoBoxTitle"><nobr><img src="images/icons/arrow_blue.gif" alt="mai multe" title=" mai multe " width="15" align="left" border="0" height="15">&nbsp;Produse in promotie</nobr></a></td>','<td><img src="images/produse_promotie_header.png"></td>',$message);

      $message = '<style type="text/css">' . $css . '</style>' . $message;
      
      $message = str_replace('images/','http://www.toolszone.ro/images/',$message);
      
      $message = str_replace('http://www.toolszone.ro/newsletter.php','http://www.toolszone.ro/index.php',$message);
      
      $message = str_replace('href="http://www.toolszone.ro/index.php" >aici</a>','href="http://www.toolszone.ro/newsletter.php" >aici</a>',$message);


	echo $message;



?>