<?php
/*
  $Id: contact_us.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
  $no_products = 30;
  require('includes/application_top.php');


  if (isset($_GET['action'])){
  
    if($_GET['action'] == 'send') {
    
      for ($i=0;$i<=$no_products+1;$i++){
        if( $_POST['cod_'.$i]!="" && $_POST['cant_'.$i]!="" ){
          
          $query = "SELECT products_attributes_id, products_id, options_values_id FROM `products_attributes` WHERE upper(cod_unic) = upper('".$_POST['cod_'.$i]."')";
          $product_query = tep_db_query($query);
          $product = tep_db_fetch_array($product_query);
          
          if (isset($product) and $product['options_values_id'] != "") {
            
            $productArray = array(1 => $product['options_values_id']);
            
                        
            $cart->add_cart($product['products_id'], 
              $cart->get_quantity(tep_get_uprid($product['products_id'],$productArray)) + $_POST['cant_'.$i],
              $productArray); 
          }
        }
      }
      
    }else if($_GET['action'] == 'uploadxls') {
  
      require_once 'includes/functions/excelParser/excel_reader2.php';
      require_once 'includes/functions/database.php';
       
      $data = new Spreadsheet_Excel_Reader();
       
      $target_path = "tmp/";
           
      $target_path = $target_path . basename( $_FILES['uploadedfile']['name']); 
           
      if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
        
      
        $data = new Spreadsheet_Excel_Reader($target_path,true,"ISO-8859-2");    
        
        for ($i = 2; $i <= $data->rowcount(0); $i++) {
             if($data->val($i,1) != NULL && $data->val($i,1) != "" && $data->val($i,2) != NULL && $data->val($i,2) != ""){
                $query = "SELECT products_attributes_id, products_id, options_values_id FROM `products_attributes` WHERE upper(cod_unic) = upper('".$data->val($i,1)."')";
                $product_query = tep_db_query($query);
                $product = tep_db_fetch_array($product_query);
                
                if (isset($product) and $product['options_values_id'] != "") {
                  
                  $productArray = array(1 => $product['options_values_id']);
                  
                              
                  $cart->add_cart($product['products_id'], 
                    $cart->get_quantity(tep_get_uprid($product['products_id'],$productArray)) + $data->val($i,2), 
                    $productArray); 
                }             
             }
        }
        
        unlink($target_path);
      }
    
    }
    
    
    
  }

  $breadcrumb->add('Comanda Rapida', tep_href_link(FILENAME_FAST_BUY));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo "Comanda Rapida - ".TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" onLoad="MM_preloadImages('<?echo $cur_page=="1" ? '' : 'images/menu/menu_01_over.gif';?>','<?echo $cur_page=="2" ? '' : 'images/menu/menu_02_over.gif';?>','<?echo $cur_page=="3" ? '' : 'images/menu/menu_03_over.gif';?>','<?echo $cur_page=="4" ? '' : 'images/menu/menu_04_over.gif';?>','<?echo $cur_page=="5" ? '' : 'images/menu/menu_05_over.gif';?>','<?echo $cur_page=="6" ? '' : 'images/menu/menu_06_over.gif';?>')">
<div id="wrapper"><!-- header //-->
<?php
$cur_page="4";
 require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td><td class="spacingTable">&nbsp;&nbsp;</td>
<!-- body_text //-->
    <td width="100%" valign="top"><?php echo tep_draw_form('fast_buy', tep_href_link(FILENAME_FAST_BUY, 'action=send')); ?>
    
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">

      <tr>
		<td height="32" valign="top" width="8"><img src="images/infobox/corner_left_top.gif" alt="" border="0" height="32" width="8"></td>
		<td class="infoBoxHeading2" height="32" valign="top" align="left">&nbsp;&nbsp;Formular Comanda Rapida</td>
		<td height="32" valign="top" width="8"><img src="images/infobox/corner_right_top.gif" alt="" border="0" height="32" width="8"></td>
	  </tr> 
    <tr>
		<td class="boxBgLeft" height="5">&nbsp;</td>
        <td><br><table class="optionTable" border="0" width="100%" cellspacing="0" cellpadding="0" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td class="optTh" >&nbsp;Cod Produs&nbsp;</td>
              <td class="optTh" >&nbsp;Cantitate&nbsp;</td>
              <td class="optTh" >&nbsp;&nbsp;&nbsp;</td>
              <td class="optTh" >&nbsp;Cod Produs&nbsp;</td>
              <td class="optTh" >&nbsp;Cantitate&nbsp;</td>
            </tr>
            <?for ($i=0;$i<=$no_products;$i=$i+2){?>
              <tr>
                <td class="subcateg" align="center"><b><?php echo tep_draw_input_field('cod_'.$i, '' ,'class="input" size="20"', 'text', false); ?></td>
                <td class="subcateg" align="center"><b><?php echo tep_draw_input_field('cant_'.$i, '1' ,'class="input" size="3"', 'text', false); ?></td>
                <td class="subcateg" align="center">&nbsp;</td>
                <td class="subcateg" align="center"><b><?php echo tep_draw_input_field('cod_'.($i+1), '' ,'class="input" size="20"', 'text', false); ?></td>
                <td class="subcateg" align="center"><b><?php echo tep_draw_input_field('cant_'.($i+1), '1' ,'class="input" size="3"', 'text', false); ?></td>
              </tr> 
            <?}?>
            </table></td>
          </tr>
        </table></td>
        <td class="boxBgRight" height="5">&nbsp;</td>
      </tr>
      <tr>
		<td class="boxBgLeft" height="50">&nbsp;</td>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td align="right"><?php echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></form></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td align="center" height="50"><form action="<?=FILENAME_FAST_BUY?>?action=uploadxls" method="post" enctype="multipart/form-data">Upload Fisier Excel: &nbsp;<input class="input" type="file" name="uploadedfile" onchange="this.form.submit()"></form></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>   
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td align="center" height="50"><a href="formular_comanda_rapida_toolszone.xls">Descarca formular Comanda Rapida</a></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>         
            </table></td>
          </tr>
        </table></td>
        <td class="boxBgRight" height="5">&nbsp;</td>
      </tr>
	  <tr>
		<td><?=tep_image(DIR_WS_IMAGES . 'infobox/corner_left_bottom.gif')?></td>  
		<td width="100%" style="border-bottom: 1px solid #4791d9; font-size:1px;">&nbsp;</td>  
		<td><?=tep_image(DIR_WS_IMAGES . 'infobox/corner_right_bottom.gif')?></td>      
	  </tr> 
    </table></td></tr>
        </table></td>
<td class="spacingTable">&nbsp;&nbsp;</td><!-- body_text_eof //-->
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->

<!-- footer_eof //-->
<br>
</div><?php require(DIR_WS_INCLUDES . 'footer.php'); ?></body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>