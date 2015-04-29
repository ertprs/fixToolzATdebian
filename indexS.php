<?php
/*
  $Id: index.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');

// the following cPath references come from application_top.php
$category_depth = 'top';
if (isset($cPath) && tep_not_null($cPath)) {
    $categories_products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = '" . (int)$current_category_id . "'");
    $cateqories_products = tep_db_fetch_array($categories_products_query);
//    if ($cateqories_products['total'] > 0) {
    $category_depth = 'products'; // display products
//    } else {
    $category_parent_query = tep_db_query("select count(*) as total from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$current_category_id . "'");
    $category_parent = tep_db_fetch_array($category_parent_query);
//      if ($category_parent['total'] > 0) {
//        $category_depth = 'nested'; // navigate through the categories
//      } else {
//        $category_depth = 'products'; // category has no products, but display the 'no products' message
//      }
//    }
}

require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);

//IFCTS Am adus codul de la #80
if ($category_depth == 'nested' || $category_depth == 'products' || isset($_GET['manufacturers_id'])) {
    $category_query = tep_db_query("select cd.seo, cd.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . (int)$current_category_id . "' and cd.categories_id = '" . (int)$current_category_id . "' and cd.language_id = '" . (int)$languages_id . "'");
    $category = tep_db_fetch_array($category_query);}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?> ">
    <title><?=$category['categories_name']; ?></title>
    <meta name="description" content="<?=$category['seo']?>" >
    <base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
    <!--[if IE]><style type="text/css">
        .productPrice {margin-left:-3px;}
    </style><![endif]-->
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" onLoad="MM_preloadImages('<?echo $cur_page=="1" ? '' : 'images/menu/menu_01_over.gif'?>','<?echo $cur_page=="2" ? '' : 'images/menu/menu_02_over.gif'?>','<?echo $cur_page=="3" ? '' : 'images/menu/menu_03_over.gif'?>','<?echo $cur_page=="4" ? '' : 'images/menu/menu_04_over.gif'?>','<?echo $cur_page=="5" ? '' : 'images/menu/menu_05_over.gif'?>','<?echo $cur_page=="6" ? '' : 'images/menu/menu_06_over.gif'?>')">
<!--<table  cellspacing="0" cellpadding="0">
<tr>
<td rowspan="2" width="50%" valign="top" style="background:#da251d url('images/xmas_bg.png') repeat-x right top;" align="right"><img src="images/xmas_01.jpg"></td>
<td valign="top" bgcolor="#da251d"><img src="images/xmas_02.jpg"></td>
<td rowspan="2" width="50%" valign="top" style="background:#da251d url('images/xmas_bg.png') repeat-x right top;" align="left"><img src="images/xmas_03.jpg"></td>
</tr>
<tr>
<td>-->
<div id="wrapper"><!-- header //-->
<?php
if (!($category_depth == 'nested' || $category_depth == 'products' || isset($_GET['manufacturers_id']))) {
    $cur_page="1";
}

require(DIR_WS_INCLUDES . 'header.php');
?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td width="<?php echo BOX_WIDTH;?> " valign="top"><table border="0" width="<?php echo BOX_WIDTH;?> " cellspacing="0" cellpadding="0">
        <!-- left_navigation //-->
        <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
        <!-- left_navigation_eof //-->
    </table></td><td class="spacingTable">&nbsp;&nbsp;</td>
<!-- body_text //-->
<?php
if ($category_depth == 'nested' || $category_depth == 'products' || isset($_GET['manufacturers_id'])) {
    if (isset($cPath) && strpos('_', $cPath)) {
// check to see if there are deeper categories within the current category
        $category_links = array_reverse($cPath_array);
        for($i=0, $n=sizeof($category_links); $i<$n; $i++) {
            $categories_query = tep_db_query("select count(*) as total from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$category_links[$i] . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "'");
            $categories = tep_db_fetch_array($categories_query);
            if ($categories['total'] < 1) {
                // do nothing, go through the loop
            } else {
                $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$category_links[$i] . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by sort_order, cd.categories_name");
                break; // we've found the deepest category the customer is in
            }
        }
    } else {
        if(tep_is_fake_category((int)$current_category_id)){
            $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id_2 = '" . (int)$current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by sort_order, cd.categories_name");
        }else{
            $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by sort_order, cd.categories_name");
        }
    }

    $number_of_categories = tep_db_num_rows($categories_query);

    $rows = 0;
    // Thumbnails cu subcategorii
    $query_subcat = "";
    echo '<td width="100%" valign="top">';
    $subcategoriesString = '<table width="99%" border="0" width="100%" cellspacing="0" cellpadding="0"><tr>';
    while ($categories = tep_db_fetch_array($categories_query)) {
        if(!tep_empty_category($categories['categories_id'])){

            $rows++;

            $query_subcat .= " OR p2c.categories_id = '".$categories['categories_id']."'";

            if($categories['categories_image'] == null || $categories['categories_image'] == ""){
                $img = 'images/mici/'.tep_get_image($categories['categories_id']);
            }else{
                $img = 'images/categories/'.$categories['categories_image'];
            }


            $cPath_new = tep_get_path($categories['categories_id']);
            $width = (int)(100 / MAX_DISPLAY_CATEGORIES_PER_ROW) . '%';

            if($rows%2)
                $subcategoriesString .= '</tr><tr>';

            $subcategoriesString .= '<td class="subcateg" height="70px" width="57px" align="left"><img src="'.$img.'" width="50px" height="50px"/></td>' .
                '<td class="subcateg" width="38%"><table width="100%" height="70px" border="0"  cellspacing="0" cellpadding="0">' .
                '<tr><td valign="middle"><a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new) . '">' . $categories['categories_name'] . '</a></td></tr>' .
                '<tr><td width="70px" height="6px" valign="bottom" style="padding-bottom:4px"><a class="maiMult" href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new) . '"><img align="left" src="'.DIR_WS_IMAGES .'icons/arrow.gif" border="0"/> Mai mult</a></td></tr>' .
                '</table></td>';

            if($rows%2)
                $subcategoriesString .= '<td class="subcateg" style="border-left:1px solid #c3ccd2">&nbsp;</td>';

            //      echo '                <td align="center" class="smallText" width="' . $width . '" valign="top"><a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new) . '">' . tep_image(DIR_WS_IMAGES . $categories['categories_image'], $categories['categories_name'], SUBCATEGORY_IMAGE_WIDTH, SUBCATEGORY_IMAGE_HEIGHT) . '<br>' . $categories['categories_name'] . '</a></td>' . "\n";
            //      if ((($rows / MAX_DISPLAY_CATEGORIES_PER_ROW) == floor($rows / MAX_DISPLAY_CATEGORIES_PER_ROW)) && ($rows != $number_of_categories)) {
            //        echo '              </tr>' . "\n";
            //        echo '              <tr>' . "\n";
            //      }
        }
    }
    if($rows%2)
        $subcategoriesString .= '<td class="subcateg" colspan="2">&nbsp;</td>';

    $subcategoriesString .= '</tr></table>';

//// needed for the new products module shown below
    $new_products_category_id = $current_category_id;

//  } elseif ($category_depth == 'products' || isset($_GET['manufacturers_id'])) {
// create column list
    $define_list = array('PRODUCT_LIST_MODEL' => PRODUCT_LIST_MODEL,
        'PRODUCT_LIST_NAME' => PRODUCT_LIST_NAME,
        'PRODUCT_LIST_MANUFACTURER' => PRODUCT_LIST_MANUFACTURER,
        'PRODUCT_LIST_PRICE' => PRODUCT_LIST_PRICE,
        'PRODUCT_LIST_QUANTITY' => PRODUCT_LIST_QUANTITY,
        'PRODUCT_LIST_WEIGHT' => PRODUCT_LIST_WEIGHT,
        'PRODUCT_LIST_IMAGE' => PRODUCT_LIST_IMAGE,
        'PRODUCT_LIST_BUY_NOW' => PRODUCT_LIST_BUY_NOW);
    //print_r($define_list);
    asort($define_list);

    $column_list = array();
    reset($define_list);
    while (list($key, $value) = each($define_list)) {
        if ($value > 0) $column_list[] = $key;
    }

    $select_column_list = '';

    for ($i=0, $n=sizeof($column_list); $i<$n; $i++) {
        switch ($column_list[$i]) {
            case 'PRODUCT_LIST_MODEL':
                $select_column_list .= 'p.products_model, ';
                break;
            case 'PRODUCT_LIST_NAME':
                $select_column_list .= 'pd.products_name, ';
                break;
            case 'PRODUCT_LIST_MANUFACTURER':
                $select_column_list .= 'p.nr_articol, ';
                break;
            case 'PRODUCT_LIST_QUANTITY':
                $select_column_list .= 'p.products_quantity, ';
                break;
            case 'PRODUCT_LIST_IMAGE':
                $select_column_list .= 'p.products_image, ';
                break;
            case 'PRODUCT_LIST_WEIGHT':
                $select_column_list .= 'p.products_weight, ';
                break;
        }
    }

// show the products of a specified manufacturer
    if (isset($_GET['manufacturers_id'])) {
        if (isset($_GET['filter_id']) && tep_not_null($_GET['filter_id'])) {
// We are asked to show only a specific category
            $listing_sql = "select " . $select_column_list . " p.products_id, p.nr_articol, p.products_amprenta, p.manufacturers_id, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$_GET['filter_id'] . "'";
        } else {
// We show them all
            $listing_sql = "select " . $select_column_list . " p.products_id, p.nr_articol, p.products_amprenta, p.manufacturers_id, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m where p.products_status = '1' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "'";
        }
    } else {
// show the products in a given categorie
        if (isset($_GET['filter_id']) && tep_not_null($_GET['filter_id'])) {
// We are asked to show only specific catgeory
            $listing_sql = "select p.products_image, p.products_amprenta from " . TABLE_PRODUCTS . " p , " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_status = '1' and p.products_id = p2c.products_id and (p2c.categories_id = '" . (int)$current_category_id . "'".$query_subcat.")";
        } else {
// We show them all
            $listing_sql = "select " . $select_column_list . " p.products_id, p.products_amprenta, p.nr_articol, p.manufacturers_id, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_status = '1' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and (p2c.categories_id = '" . (int)$current_category_id . "'".$query_subcat.")";
        }
    }
    //echo $listing_sql;

    if ( (!isset($_GET['sort'])) || (!ereg('^[1-8][ad]$', $_GET['sort'])) || (substr($_GET['sort'], 0, 1) > sizeof($column_list)) ) {
        for ($i=0, $n=sizeof($column_list); $i<$n; $i++) {
            if ($column_list[$i] == 'PRODUCT_LIST_NAME') {
                //$_GET['sort'] = $i+1 . 'a';
                $listing_sql .= " order by p.products_id";
                break;
            }
        }
    } else {
        $sort_col = substr($_GET['sort'], 0 , 1);
        $sort_order = substr($_GET['sort'], 1);

        switch ($column_list[$sort_col-1]) {
            case 'PRODUCT_LIST_MODEL':
                $listing_sql .= " order by p.products_model " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
                break;
            case 'PRODUCT_LIST_NAME':
                $listing_sql .= " order by pd.products_name " . ($sort_order == 'd' ? 'desc' : '');
                break;
            case 'PRODUCT_LIST_MANUFACTURER':
                $listing_sql .= " order by p.nr_articol " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
                break;
            case 'PRODUCT_LIST_QUANTITY':
                $listing_sql .= " order by p.products_quantity " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
                break;
            case 'PRODUCT_LIST_IMAGE':
                $listing_sql .= " order by pd.products_name";
                break;
            case 'PRODUCT_LIST_WEIGHT':
                $listing_sql .= " order by p.products_weight " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
                break;
            case 'PRODUCT_LIST_PRICE':
                $listing_sql .= " order by final_price " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
                break;
            default:
                echo "test";
                $listing_sql .= " order by p.products_id";
        }
    }
    ?>

<?php
//// optional Product List Filter
//    if (PRODUCT_LIST_FILTER > 0) {
//      if (isset($_GET['manufacturers_id'])) {
//        $filterlist_sql = "select distinct c.categories_id as id, cd.categories_name as name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where p.products_status = '1' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and p2c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and p.manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "' order by cd.categories_name";
//      } else {
//        $filterlist_sql= "select distinct m.manufacturers_id as id, m.manufacturers_name as name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_MANUFACTURERS . " m where p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$current_category_id . "' order by m.manufacturers_name";
//      }
//      $filterlist_query = tep_db_query($filterlist_sql);
//      if (tep_db_num_rows($filterlist_query) > 1) {
//        echo '            <td align="center" class="main">' . tep_draw_form('filter', FILENAME_DEFAULT, 'get') . TEXT_SHOW . '&nbsp;';
//        if (isset($_GET['manufacturers_id'])) {
//          echo tep_draw_hidden_field('manufacturers_id', $_GET['manufacturers_id']);
//          $options = array(array('id' => '', 'text' => TEXT_ALL_CATEGORIES));
//        } else {
//          echo tep_draw_hidden_field('cPath', $cPath);
//          $options = array(array('id' => '', 'text' => TEXT_ALL_MANUFACTURERS));
//        }
//        echo tep_draw_hidden_field('sort', $_GET['sort']);
//        while ($filterlist = tep_db_fetch_array($filterlist_query)) {
//          $options[] = array('id' => $filterlist['id'], 'text' => $filterlist['name']);
//        }
//        echo tep_draw_pull_down_menu('filter_id', $options, (isset($_GET['filter_id']) ? $_GET['filter_id'] : ''), 'onchange="this.form.submit()"');
//        echo tep_hide_session_id() . '</form></td>' . "\n";
//      }
//    }

//// Get the right image for the top-right
//    $image = DIR_WS_IMAGES . 'table_background_list.gif';
//    if (isset($_GET['manufacturers_id'])) {
//      $image = tep_db_query("select manufacturers_image from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "'");
//      $image = tep_db_fetch_array($image);
//      $image = $image['manufacturers_image'];
//    } elseif ($current_category_id) {
//      $image = tep_db_query("select categories_image from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$current_category_id . "'");
//      $image = tep_db_fetch_array($image);
//      $image = $image['categories_image'];
//    }

    ?>
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <?

        if(file_exists('categories_description/'.$category['categories_id'].'.php') || file_exists('categories_description/'.$category['parent_id'].'.php') ) {
            if(file_exists('categories_description/'.$category['parent_id'].'.php')){
                $category_desc_query = tep_db_query("select cd.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . (int)$category['parent_id']. "' and cd.categories_id = '" . (int)$category['parent_id'] . "' and cd.language_id = '" . (int)$languages_id . "'");
                $category_desc = tep_db_fetch_array($category_desc_query);

            }else{
                $category_desc = $category;
            }
            ?>
            <tr><td>
                    <table border="0" width="100%" cellspacing="0" cellpadding="0" >
                        <tr>
                            <td height="32" valign="top" width="8"><img src="images/infobox/corner_left_top.gif" alt="" border="0" height="32" width="8"></td>
                            <td class="infoBoxHeading2" height="32" valign="top" align="left">&nbsp;&nbsp;<?=$category_desc['categories_name']?></td>
                            <td height="32" valign="top" width="8"><img src="images/infobox/corner_right_top.gif" alt="" border="0" height="32" width="8"></td>
                        </tr>
                        <tr>
                            <td class="boxBgLeft" height="5">&nbsp;</td>
                            <td class="fieldKey" align="center">
                                <table>
                                    <tr>
                                        <td style="padding:10px" width="20%"><?=tep_image(DIR_WS_IMAGES . 'categories/'.$category_desc['categories_image'])?></td>
                                        <td><?include('categories_description/'.$category_desc['categories_id'].'.php')?></td>
                                    </tr>
                                </table>
                            </td>
                            <td class="boxBgRight" height="5">&nbsp;</td>
                        </tr>
                        <tr>
                            <td><?=tep_image(DIR_WS_IMAGES . 'infobox/corner_left_bottom.gif')?></td>
                            <td width="100%" style="border-bottom: 1px solid #4791d9; font-size:1px;">&nbsp;</td>
                            <td><?=tep_image(DIR_WS_IMAGES . 'infobox/corner_right_bottom.gif')?></td>
                        </tr>
                    </table>
                </td></tr>
            <?include(DIR_WS_BOXES . 'spacing.php');	?>
        <?}?>
        <?if($rows>0){?>
            <tr><td>
                    <table border="0" width="100%" cellspacing="0" cellpadding="0" >
                        <tr>
                            <td height="32" valign="top" width="8"><img src="images/infobox/corner_left_top.gif" alt="" border="0" height="32" width="8"></td>
                            <td class="infoBoxHeading2" height="32" valign="top" align="left">&nbsp;&nbsp;<?=$category['categories_name']?></td>
                            <td height="32" valign="top" width="8"><img src="images/infobox/corner_right_top.gif" alt="" border="0" height="32" width="8"></td>
                        </tr>
                        <tr>
                            <td class="boxBgLeft" height="5">&nbsp;</td>
                            <td class="fieldKey" align="center"><?=$subcategoriesString?></td>
                            <td class="boxBgRight" height="5">&nbsp;</td>
                        </tr>
                        <tr>
                            <td><?=tep_image(DIR_WS_IMAGES . 'infobox/corner_left_bottom.gif')?></td>
                            <td width="100%" style="border-bottom: 1px solid #4791d9; font-size:1px;">&nbsp;</td>
                            <td><?=tep_image(DIR_WS_IMAGES . 'infobox/corner_right_bottom.gif')?></td>
                        </tr>
                    </table>
                </td></tr>
            <?include(DIR_WS_BOXES . 'spacing.php');?>
        <?}else{?>
            <tr><td>
                    <table border="0" width="100%" cellspacing="0" cellpadding="0" >
                        <tr>
                            <td height="32" valign="top" width="8"><img src="images/infobox/corner_left_top.gif" alt="" border="0" height="32" width="8"></td>
                            <td class="infoBoxHeading2" height="32" valign="top" align="left">&nbsp;&nbsp;<?=ucfirst(strtolower($category['categories_name']))?></td>
                            <td height="32" valign="top" width="8"><img src="images/infobox/corner_right_top.gif" alt="" border="0" height="32" width="8"></td>
                        </tr>
                        <tr>
                            <td colspan="3" class="boxBgLeft boxBgRight" align="left"><?php include(DIR_WS_MODULES . FILENAME_PRODUCT_LISTING); ?></td>
                        </tr>
                        <tr>
                            <td style="background-color:#efefef"><?=tep_image(DIR_WS_IMAGES . 'infobox/corner_left_bottom.gif')?></td>
                            <td width="100%" style="border-bottom: 1px solid #4791d9; font-size:1px;background-color:#efefef">&nbsp;</td>
                            <td style="background-color:#efefef"><?=tep_image(DIR_WS_IMAGES . 'infobox/corner_right_bottom.gif')?></td>
                        </tr>
                    </table>
                </td></tr>

            <?
            include(DIR_WS_BOXES . 'spacing.php');}
        ?>
        <tr><td>
                <?php include(DIR_WS_MODULES . FILENAME_NEW_PRODUCTS);?>
            </td></tr>
    </table>
<?php
} else { // default page
    ?>
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td><?php include(DIR_WS_MODULES . FILENAME_BANNERS); ?></td>
            </tr>
            <tr>
                <td><?php include(DIR_WS_MODULES . FILENAME_SCULE); ?></td>
            </tr>
            <tr>
                <td><?php include(DIR_WS_MODULES . FILENAME_SPECIALS);?> </td>
            </tr>
            <tr>
                <td><?php include(DIR_WS_MODULES . FILENAME_BEST_SELLERS);?> </td>
            </tr>
            <tr>
                <td><?php include(DIR_WS_MODULES . FILENAME_NEW_PRODUCTS);?> </td>
            </tr>
            <tr>
                <td><br><object type="application/x-shockwave-flash" style="width:504px; height:64px;" data="http://www.inimacopiilor.ro/campanie/bs/468x60_moving_donatii_plain.swf"><param name="movie" value="http://www.inimacopiilor.ro/campanie/bs/468x60_moving_donatii_plain.swf" /></object></td>
            </tr>
        </table></td>
<?php
}
?>
<td class="spacingTable">&nbsp;&nbsp;</td><!-- body_text_eof //-->
<td width="<?php echo BOX_WIDTH;?> " valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
        <!-- right_navigation //-->
        <?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
        <!-- right_navigation_eof //-->
    </table></td>
</tr>
</table>
<!-- body_eof //-->
<br>
</div><?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!--</td>
</tr>
</table>-->

</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php');?>
