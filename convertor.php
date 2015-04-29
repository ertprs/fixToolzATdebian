<?php
/*
  $Id: contact_us.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CONTACT_US);

  $breadcrumb->add("Convertor unitati de masura", tep_href_link(FILENAME_CONVERTOR));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo "Convertor unitati de masura - ".TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<script src="js/conversie.js" type="text/javascript"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" onLoad="MM_preloadImages('<?echo $cur_page=="1" ? '' : 'images/menu/menu_01_over.gif';?>','<?echo $cur_page=="2" ? '' : 'images/menu/menu_02_over.gif';?>','<?echo $cur_page=="3" ? '' : 'images/menu/menu_03_over.gif';?>','<?echo $cur_page=="4" ? '' : 'images/menu/menu_04_over.gif';?>','<?echo $cur_page=="5" ? '' : 'images/menu/menu_05_over.gif';?>','<?echo $cur_page=="6" ? '' : 'images/menu/menu_06_over.gif';?>')">
<div id="wrapper"><!-- header //-->
<?php
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
    <td width="100%" valign="top">
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">

      <tr>
		<td height="32" valign="top" width="8"><img src="images/infobox/corner_left_top.gif" alt="" border="0" height="32" width="8"></td>
		<td colspan="2" class="infoBoxHeading2" height="32" valign="top" align="left">&nbsp;&nbsp;Convertor unitati de masura</td>
		<td height="32" valign="top" width="8"><img src="images/infobox/corner_right_top.gif" alt="" border="0" height="32" width="8"></td>
	  </tr> 
      <tr>
		<td class="boxBgLeft" height="5">&nbsp;</td><td width="10"></td>
        <td>
        	<br>
          <table border="0" cellspacing="0" cellpadding="0" width="95%" >
            <tr>
              <form name="lengthForm" method="post" onsubmit="convertFunc('lengthForm',num_to_change,lengthSelects); return false">
              <td class="productTextTitle" height="20" valign="bottom" colspan="4">&nbsp;Lungime</td>
            </tr> 
            <tr><td colspan="4" class="subcateg" style="font-size: 2px;">&nbsp;</td></tr>
            <tr><td colspan="4" style="font-size: 2px;" height="10">&nbsp;</td></tr>            
            <tr>
              <td>Valoare</td>
              <td align="left" ><input class="input" type="text" size="5" maxlength="12" name="num_to_change" value="0" onChange="convertFunc('lengthForm',num_to_change,lengthSelects)" onFocus="this.select()"></td>
              <td align="center">
                <select class="input" name="lengthSelects" Style = "width:180px" onchange="convertFunc('lengthForm',num_to_change,lengthSelects);" onfocus="tabLen=true">
          				<option>Alege</option>
          				<option></option>
          				<option></option>
          				<option></option>
          				<option></option>
                </select>
              </td>
              <td align="left" ><input class="input" type="text" size="8" name="result" value="Rezultat" onFocus="fnSetTabLen()"></td>
              </form>
            </tr>   
          	<form name="areaForm" method="post" onsubmit="convertFunc('areaForm',num_to_change,areaSelects); return false">
              <td class="productTextTitle" height="40" valign="bottom" colspan="4">&nbsp;Suprafata</td>
            </tr> 
            <tr><td colspan="4" class="subcateg" style="font-size: 2px;">&nbsp;</td></tr>
            <tr><td colspan="4" style="font-size: 2px;" height="10">&nbsp;</td></tr>            
            <tr>      
              <td>Valoare</td>
              <td align="left" ><input class="input" type="text" size="5" maxlength="12" name="num_to_change" value="0" onChange="convertFunc('areaForm',num_to_change,areaSelects)" onfocus="this.select();tabLen=false"></td>
              <td align="center" >
          			<select class="input" name="areaSelects" Style = "width:180px" onchange="convertFunc('areaForm',num_to_change,areaSelects)" onfocus="tabArea=true">
          				<option>Alege</option>
          				<option></option>
          				<option></option>
          				<option></option>
          				<option></option>
          			</select>
              </td>
              <td align="left" ><input class="input" type="text" size="8" name="result" value="Rezultat" onfocus="fnSetTabArea()"></td>
          	</form>
            </tr>
            <tr>    
      	    <form name="volumeForm" method="post" onsubmit="convertFunc('volumeForm',num_to_change,volumeSelects); return false">
              <td class="productTextTitle" height="40" valign="bottom" colspan="4">&nbsp;Volum</td>
            </tr> 
            <tr><td colspan="4" class="subcateg" style="font-size: 2px;">&nbsp;</td></tr>
            <tr><td colspan="4" style="font-size: 2px;" height="10">&nbsp;</td></tr>            
            <tr>      
              <td>Valoare</td>
              <td align="left" ><input class="input" type="text" size="5" maxlength="12" name="num_to_change" value="0" onChange="convertFunc('volumeForm',num_to_change,volumeSelects)" onFocus="this.select();tabArea=false"></td>
              <td align="center" >
          			<select class="input"  name="volumeSelects"  Style = "width:180px" onchange="convertFunc('volumeForm',num_to_change,volumeSelects)" onfocus="tabWt=true">
                  <option>Alege</option>
          				<option></option>
          				<option></option>
          				<option></option>
          				<option></option>
          			</select>
              </td>
              <td align="left" ><input class="input" type="text" size="8" name="result" value="Rezultat" onfocus="fnSetTabVol()"></td>
            </form>
            </tr>
            <tr>    
      	    <form name="weightForm" method="post" onsubmit="convertFunc('weightForm',num_to_change,weightSelects); return false">
              <td class="productTextTitle" height="40" valign="bottom" colspan="4">&nbsp;Greutate</td>
            </tr> 
            <tr><td colspan="4" class="subcateg" style="font-size: 2px;">&nbsp;</td></tr>
            <tr><td colspan="4" style="font-size: 2px;" height="10">&nbsp;</td></tr>            
            <tr>      
              <td>Valoare</td>
              <td align="left" ><input class="input" type="text" size="5" maxlength="12" name="num_to_change" value="0" onChange="convertFunc('weightForm',num_to_change,weightSelects)" onFocus="this.select();tabWt=false"></td>
              <td align="center" >
          			<select class="input" name="weightSelects" Style = "width:180px" onChange="convertFunc('weightForm',num_to_change,weightSelects)">
          				<option>Alege</option>
          				<option></option>
          				<option></option>
          				<option></option>
          				<option></option>
          			</select>
              </td>
              <td align="left" ><input class="input" type="text" size="8" name="result" value="Rezultat" onfocus="document.lengthForm.num_to_change.focus()"></td>
      	    </form>
            </tr>
            <tr>    
      	    <form name="forceForm" method="post" onsubmit="convertFunc('forceForm',num_to_change,forceSelects); return false">
              <td class="productTextTitle" height="40" valign="bottom" colspan="4">&nbsp;Forta</td>
            </tr> 
            <tr><td colspan="4" class="subcateg" style="font-size: 2px;">&nbsp;</td></tr>
            <tr><td colspan="4" style="font-size: 2px;" height="10">&nbsp;</td></tr>            
            <tr>      
              <td>Valoare</td>
              <td align="left" ><input class="input" type="text" size="5" maxlength="12" name="num_to_change" value="0" onChange="convertFunc('forceForm',num_to_change,forceSelects)" onFocus="this.select();tabWt=false"></td>
              <td align="center" >
          			<select class="input" name="forceSelects" Style = "width:180px" onChange="convertFunc('forceForm',num_to_change,forceSelects)">
          				<option>Alege</option>
          				<option></option>
          				<option></option>
          				<option></option>
          				<option></option>
          			</select>
              </td>
              <td align="left" ><input class="input" type="text" size="8" name="result" value="Rezultat" onfocus="document.forceForm.num_to_change.focus()"></td>
      	    </form>
            </tr>
          </table>
          <script>makeSelects();</script>		
          <br><br><br>	 			    
        </td>
        <td class="boxBgRight" height="5">&nbsp;</td>
      </tr>
	  <tr>
		<td><?=tep_image(DIR_WS_IMAGES . 'infobox/corner_left_bottom.gif')?></td>  
		<td colspan="2" width="100%" style="border-bottom: 1px solid #4791d9; font-size:1px;">&nbsp;</td>  
		<td><?=tep_image(DIR_WS_IMAGES . 'infobox/corner_right_bottom.gif')?></td>      
	  </tr> 
    </table></td></tr>  
      <?include(DIR_WS_BOXES . 'spacing.php');?>
      <tr>  
        <td><?php include(DIR_WS_MODULES . FILENAME_SPECIALS); ?></td>
      </tr>
      <tr>  
        <td><?php include(DIR_WS_MODULES . FILENAME_BEST_SELLERS); ?></td>
      </tr>
      <tr>  
        <td><?php include(DIR_WS_MODULES . FILENAME_NEW_PRODUCTS); ?></td>
      </tr>    
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

