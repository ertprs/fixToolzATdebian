<?php
/*
  $Id: index.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
  require('includes/application_top.php');
  require('page_header.php');

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
 $title = ($category['categories_name'])? $category['categories_name'].' | '.'ToolsZone.ro - Magazin online de scule si unelte profesionale':'ToolsZone.ro - Magazin online de scule si unelte profesionale';
  insertHeader($title,$category['seo']);

if ($category_depth == 'nested' || $category_depth == 'products' || isset($_GET['manufacturers_id'])) {
    $category_query = tep_db_query("select cd.seo, cd.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . (int)$current_category_id . "' and cd.categories_id = '" . (int)$current_category_id . "' and cd.language_id = '" . (int)$languages_id . "'");
    $category = tep_db_fetch_array($category_query);}

?>
<?php
if (!($category_depth == 'nested' || $category_depth == 'products' || isset($_GET['manufacturers_id']))) {
	$cur_page="1";
}

 require(DIR_WS_INCLUDES . 'header.php');
 ?>
<div class="container">
    <div class="row">
        <div class="col-md-3 padding-right">
            <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
        </div>
        <div class="col-md-6 no-padding">
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
                echo '';
                $subcategoriesString = '';
                while ($categories = tep_db_fetch_array($categories_query)) {
                    if(!tep_empty_category($categories['categories_id'])){

                        $rows++;

                        $query_subcat .= " OR p2c.categories_id = '".$categories['categories_id']."'";

                        if($categories['categories_image'] == null || $categories['categories_image'] == ""){
                            $tepImage = tep_get_image($categories['categories_id']);
                            $img = 'images/mici/'.strtoupper($tepImage[0]).'/'.$tepImage;
                        }else{
                            $img = 'images/categories/'.$categories['categories_image'];
                        }


                        $cPath_new = tep_get_path($categories['categories_id']);
                        $width = (int)(100 / MAX_DISPLAY_CATEGORIES_PER_ROW) . '%';

                        if($rows%2)
                            $subcategoriesString .= '';

                        $subcategoriesString .= '<img src="'.$img.'" width="50px" height="50px"/>' .
                            '<a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new) . '">' . $categories['categories_name'] . '</a>' .
                            '<a class="maiMult" href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new) . '"><img align="left" src="'.DIR_WS_IMAGES .'icons/arrow.gif"/> Mai mult</a>' .
                            '';

                        if($rows%2)
                            $subcategoriesString .= '';

                        //      echo '                <a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new) . '">' . tep_image(DIR_WS_IMAGES . $categories['categories_image'], $categories['categories_name'], SUBCATEGORY_IMAGE_WIDTH, SUBCATEGORY_IMAGE_HEIGHT) . '<br>' . $categories['categories_name'] . '</a>' . "\n";
                        //      if ((($rows / MAX_DISPLAY_CATEGORIES_PER_ROW) == floor($rows / MAX_DISPLAY_CATEGORIES_PER_ROW)) && ($rows != $number_of_categories)) {
                        //        echo '              ' . "\n";
                        //        echo '              ' . "\n";
                        //      }
                    }
                }
                if($rows%2)
                    $subcategoriesString .= '';

                $subcategoriesString .= '';

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
                //        echo '            ' . tep_draw_form('filter', FILENAME_DEFAULT, 'get') . TEXT_SHOW . '';
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
                //        echo tep_hide_session_id() . '</form>' . "\n";
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
                <?

                if(file_exists('categories_description/'.$category['categories_id'].'.php') || file_exists('categories_description/'.$category['parent_id'].'.php') ) {
                    if(file_exists('categories_description/'.$category['parent_id'].'.php')){
                        $category_desc_query = tep_db_query("select cd.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . (int)$category['parent_id']. "' and cd.categories_id = '" . (int)$category['parent_id'] . "' and cd.language_id = '" . (int)$languages_id . "'");
                        $category_desc = tep_db_fetch_array($category_desc_query);

                    }else{
                        $category_desc = $category;
                    }
                    ?>


                    <?=$category_desc['categories_name']?>




                    <?include('categories_description/'.$category_desc['categories_id'].'.php')?>



                <?}?>
                <?if($rows>0){?>


                    <?=$category['categories_name']?>

                    
                    <?=$subcategoriesString?>
                    


                <?}else{?>

                    <?=ucfirst(strtolower($category['categories_name']))?>


                    <?php include(DIR_WS_MODULES . FILENAME_PRODUCT_LISTING); ?>




                    <?
                    }
                ?>

                <?php include(DIR_WS_MODULES . FILENAME_NEW_PRODUCTS);?>

            <?php
            } else { // default page
                ?>

                <div class="breadCrumb">
                    <?php echo $breadcrumb->trail(' &raquo; '); ?>
                </div>


                <?php include(DIR_WS_MODULES . FILENAME_BANNERS); ?>


                <?php// include(DIR_WS_MODULES . FILENAME_SCULE); ?>

                <div class="promo">
                    <?php include(DIR_WS_MODULES . FILENAME_SPECIALS);?>

                    <?php include(DIR_WS_MODULES . FILENAME_BEST_SELLERS);?>

                    <?php include(DIR_WS_MODULES . FILENAME_NEW_PRODUCTS);?>
                </div>



            <?php
            }
            ?>
        </div>

        <div class="col-md-3 no-padding"><?php require(DIR_WS_INCLUDES . 'column_right.php'); ?></div>

    </div>


</div>


<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>


</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php');?>
