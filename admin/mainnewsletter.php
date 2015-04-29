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
          
  if (tep_not_null($action)) {
    if($action == 'send' && $title!='' && $addresses!='') {

      if($abonati == "on"){
        $query = "select customers_email_address from customers where customers_newsletter='1'";
        $qaddresses = tep_db_query($query);
        $mailabonati = ""; 
        while($address = tep_db_fetch_array($qaddresses)){
          $mailabonati .= $address['customers_email_address'].";";
        }
        $addresses .= ",".$mailabonati;
      }    
      
      $message = get_data('http://www.toolszone.ro/newsletter.php');
      $css = get_data('http://www.toolszone.ro/stylesheet.css');
      
  
      $message = str_replace('<td class="infoBoxHeading2" height="32" valign="top"><a href="http://www.toolszone.ro/new_products.php" class="infoBoxTitle"><nobr><img src="images/icons/arrow_blue.gif" alt="mai multe" title=" mai multe " width="15" align="left" border="0" height="15">&nbsp;Produse Noi</nobr></a></td>','<td><img src="images/produse_noi_header.png"></td>',$message);
      
      $message = str_replace('<td class="infoBoxHeading2" height="32" valign="top">&nbsp;&nbsp;Cele mai vandute produse</td>','<td><img src="images/produse_vandute_header.png"></td>',$message);
      
      $message = str_replace('<td class="infoBoxHeading2" height="32" valign="top"><a href="http://www.toolszone.ro/specials.php" class="infoBoxTitle"><nobr><img src="images/icons/arrow_blue.gif" alt="mai multe" title=" mai multe " width="15" align="left" border="0" height="15">&nbsp;Produse in promotie</nobr></a></td>','<td><img src="images/produse_promotie_header.png"></td>',$message);

      $message = '<style type="text/css">' . $css . '</style>' . $message;
      
      $message = str_replace('images/','http://www.toolszone.ro/images/',$message);
      $message = str_replace('poze/','http://www.toolszone.ro/poze/',$message);
      
      $message = str_replace('http://www.toolszone.ro/newsletter.php','http://www.toolszone.ro/index.php',$message);
      
      $message = str_replace('href="http://www.toolszone.ro/index.php" >aici</a>','href="http://www.toolszone.ro/newsletter.php" >aici</a>',$message);

      $to  = "";
      
      // subject
      $subject = $title;
      
      
      // To send HTML mail, the Content-type header must be set
      $headers  = 'MIME-Version: 1.0' . "\r\n";
      $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
      
      // Additional headers
      
      $headers .= "Return-path: <office@toolszone.ro> \r\n";
      $headers .= "Reply-To: office@toolszone.ro\r\n";
      $headers .= "To: ToolsZone.ro <office@toolszone.ro> \r\n";
      $headers .= 'From: ToolsZone.ro <office@toolszone.ro>' . "\r\n";
      $headers .= 'Cc: ' . "\r\n";
      $headers .= 'Bcc: ' . $addresses ."\r\n";
      //echo $to, $subject, $headers;
      // Mail it
      if(mail($to, $subject, $message, $headers)) 
        echo "<script>alert('Newsletter trimis')</script>";  
      else                                                
        echo "<script>alert('Newsletter nu a fot trimis')</script>";
    }
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<div id="spiffycalendar" class="text"></div>
<div id="wrapper"><!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top">
    <?php echo tep_draw_form('newsletter', FILENAME_MAIN_NEWSLETTERS, 'action=send'); ?>
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">Trimite Newsletter Promotii</td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
          
          <tr>
          <td>
          <table>
          <tr>
            <td><b>Titlu:</b></td>
            <td><?php echo tep_draw_input_field('title', '', 'size="52"', false); ?></td>
          </tr>
          <tr>
            <td valign="top"><b>Adrese e-mail :</b><br>(separate prin <br>virgula ',')</td>
            <td><?php echo tep_draw_textarea_field('addresses', 'soft', 50, 15, '' ,'class="input"'); ?></td>
          </tr>
          <tr>
            <td>
            </td>
            <td valign="top"><b>Trimite si la abonati</b><input type="checkbox" name="abonati"></td>
            
          </tr>                                      
          <tr>
            <td valign="top"></td>
            <td><input type="submit" value="Trimite">
            </td>
          </tr>
          </table></td></tr>
        </table></td>
      </tr>
    </table></td></form>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</div><div class="boxTextLine" style="size:100%"></div><div class="bottom"><div class="bottom_plus_design "><?include('plus_design.php')?></div></div></body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
