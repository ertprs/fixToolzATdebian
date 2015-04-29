<?php
/*
  $Id: specials.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_SPECIALS);

  $breadcrumb->add("Categorii", tep_href_link(FILENAME_CATEGORIES));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo "Categorii - ".TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" onLoad="MM_preloadImages('<?echo $cur_page=="1" ? '' : 'images/menu/menu_01_over.gif';?>','<?echo $cur_page=="2" ? '' : 'images/menu/menu_02_over.gif';?>','<?echo $cur_page=="3" ? '' : 'images/menu/menu_03_over.gif';?>','<?echo $cur_page=="4" ? '' : 'images/menu/menu_04_over.gif';?>','<?echo $cur_page=="5" ? '' : 'images/menu/menu_05_over.gif';?>','<?echo $cur_page=="6" ? '' : 'images/menu/menu_06_over.gif';?>')">
<div id="wrapper"><!-- header //-->
<?php
$cur_page="5";
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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">

      <tr>
		<td height="32" valign="top" width="8"><img src="images/infobox/corner_left_top.gif" alt="" border="0" height="32" width="8"></td>
		<td colspan="2" class="infoBoxHeading2" height="32" valign="top" align="left">&nbsp;&nbsp;Categorii ToolsZone.ro</td>
		<td height="32" valign="top" width="8"><img src="images/infobox/corner_right_top.gif" alt="" border="0" height="32" width="8"></td>
	  </tr> 
    <tr>
		<td class="boxBgLeft" height="5">&nbsp;</td><td width="10"></td>
        <td><table border="0" width="100%"cellspacing="0" cellpadding="2">
<?php

        $categories = "SELECT c.categories_id, cd.categories_name from " . TABLE_CATEGORIES . " c left join " . TABLE_CATEGORIES_DESCRIPTION . " cd on (c.categories_id = cd.categories_id) where c.parent_id = '1' and cd.language_id = '" . (int)$languages_id . "' order by c.sort_order ASC";

        $categories_query = tep_db_query($categories);

        while ($category = tep_db_fetch_array($categories_query)){
          if(!tep_empty_category($category['categories_id'])){

            echo '<tr><td colspan="2" class="productTextTitle" height="40" valign="bottom">'.$category['categories_name'].'</td></tr>';
            echo '<tr><td colspan="2" class="subcateg" style="font-size: 2px;">&nbsp;</td></tr>';
            echo '<tr><td colspan="2" style="font-size: 2px;" height="10">&nbsp;</td></tr>';
            $subcategories = "SELECT c.categories_id, cd.categories_name from " . TABLE_CATEGORIES . " c left join " . TABLE_CATEGORIES_DESCRIPTION . " cd on (c.categories_id = cd.categories_id) where c.parent_id = '" . $category['categories_id'] . "' and cd.language_id = '" . (int)$languages_id . "' order by c.sort_order ASC";

            $subcategories_query = tep_db_query($subcategories);

            $count = 1;
            echo "<tr>";
            while ($subcategory = tep_db_fetch_array($subcategories_query)){
                if(!tep_empty_category($subcategory['categories_id'])){

                if($count%2){
                  echo '</tr><tr>';
                }
                echo '<td><a href="' . tep_href_link(FILENAME_DEFAULT, 'cPath='.$category['categories_id'].'_'.$subcategory['categories_id']) . '"><img src="images/icons/arrow.gif" align="left" border="0">'.$subcategory['categories_name'].'</a></td>';

                $count++;
              }
            }
            echo "</tr>";

          }
        }
?>
        </table></td>
        <td class="boxBgRight" height="5">&nbsp;</td>
      </tr>
      <tr>
		<td class="boxBgLeft" height="10">&nbsp;</td><td width="10"></td>
        <td></td>
        <td class="boxBgRight" height="5">&nbsp;</td>
      </tr>
	  <tr>
		<td><?=tep_image(DIR_WS_IMAGES . 'infobox/corner_left_bottom.gif')?></td>  
		<td colspan="2" width="100%" style="border-bottom: 1px solid #4791d9; font-size:1px;">&nbsp;</td>  
		<td><?=tep_image(DIR_WS_IMAGES . 'infobox/corner_right_bottom.gif')?></td>      
	  </tr> 
    </table>
        </td>      </tr>
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