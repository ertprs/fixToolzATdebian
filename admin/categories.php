<?php
/*
  $Id: categories.php 1755 2007-12-21 14:02:36Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');

require(DIR_WS_CLASSES . 'currencies.php');
$currencies = new currencies();

require('upload.php');

$action = (isset($_REQUEST['action']) ? $_REQUEST['action'] : '');

if (tep_not_null($action)) {
    // ULTIMATE Seo Urls 5 by FWR Media
    // If the action will affect the cache entries
    if ( $action == 'insert' || $action == 'update' || $action == 'setflag' ) {
        tep_reset_cache_data_seo_urls( 'reset' );
    }
    switch ($action) {
        case 'setflag':
            if ( ($_GET['flag'] == '0') || ($_GET['flag'] == '1') ) {
                if (isset($_GET['pID'])) {
                    tep_set_product_status($_GET['pID'], $_GET['flag']);
                }

                if (USE_CACHE == 'true') {
                    tep_reset_cache_block('categories');
                    tep_reset_cache_block('also_purchased');
                }
            }

            tep_redirect(tep_href_link(
                NAME_CATEGORIES, 'cPath=' . $_GET['cPath'] . '&pID=' . $_GET['pID']));
            break;
        case 'insert_category':
        case 'update_category':
            if (isset($_POST['categories_id'])) $categories_id = tep_db_prepare_input($_POST['categories_id']);
            $sort_order = tep_db_prepare_input($_POST['sort_order']);

            $sql_data_array = array('sort_order' => (int)$sort_order);

            if ($action == 'insert_category') {
                $insert_sql_data = array('parent_id' => $current_category_id,
                    'date_added' => 'now()');

                $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

                tep_db_perform(TABLE_CATEGORIES, $sql_data_array);

                $categories_id = tep_db_insert_id();
            } elseif ($action == 'update_category') {
                $update_sql_data = array('last_modified' => 'now()');

                $sql_data_array = array_merge($sql_data_array, $update_sql_data);

                tep_db_perform(TABLE_CATEGORIES, $sql_data_array, 'update', "categories_id = '" . (int)$categories_id . "'");
            }

            $languages = tep_get_languages();
            for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
                $categories_name_array = $_POST['categories_name'];

                $categories_seo = $_POST['seo'];

                $language_id = $languages[$i]['id'];

                $sql_data_array = array('categories_name' => tep_db_prepare_input($categories_name_array[$language_id]),'seo' => tep_db_prepare_input($categories_seo));

                if ($action == 'insert_category') {
                    $insert_sql_data = array('categories_id' => $categories_id,
                        'language_id' => $languages[$i]['id']);

                    $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

                    tep_db_perform(TABLE_CATEGORIES_DESCRIPTION, $sql_data_array);
                } elseif ($action == 'update_category') {
                    tep_db_perform(TABLE_CATEGORIES_DESCRIPTION, $sql_data_array, 'update', "categories_id = '" . (int)$categories_id . "' and language_id = '" . (int)$languages[$i]['id'] . "'");
                }
            }

            $categories_image = new upload('categories_image');
            $categories_image->set_destination(DIR_FS_CATALOG_IMAGES);

            if ($categories_image->parse() && $categories_image->save()) {
                tep_db_query("update " . TABLE_CATEGORIES . " set categories_image = '" . tep_db_input($categories_image->filename) . "' where categories_id = '" . (int)$categories_id . "'");
            }

            if (USE_CACHE == 'true') {
                tep_reset_cache_block('categories');
                tep_reset_cache_block('also_purchased');
            }

            tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories_id));
            break;
        case 'delete_category_confirm':
            if (isset($_POST['categories_id'])) {
                $categories_id = tep_db_prepare_input($_POST['categories_id']);

                $categories = tep_get_category_tree($categories_id, '', '0', '', true);
                $products = array();
                $products_delete = array();

                for ($i=0, $n=sizeof($categories); $i<$n; $i++) {
                    $product_ids_query = tep_db_query("select products_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = '" . (int)$categories[$i]['id'] . "'");

                    while ($product_ids = tep_db_fetch_array($product_ids_query)) {
                        $products[$product_ids['products_id']]['categories'][] = $categories[$i]['id'];
                    }
                }

                reset($products);
                while (list($key, $value) = each($products)) {
                    $category_ids = '';

                    for ($i=0, $n=sizeof($value['categories']); $i<$n; $i++) {
                        $category_ids .= "'" . (int)$value['categories'][$i] . "', ";
                    }
                    $category_ids = substr($category_ids, 0, -2);

                    $check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$key . "' and categories_id not in (" . $category_ids . ")");
                    $check = tep_db_fetch_array($check_query);
                    if ($check['total'] < '1') {
                        $products_delete[$key] = $key;
                    }
                }

// removing categories can be a lengthy process
                tep_set_time_limit(0);
                for ($i=0, $n=sizeof($categories); $i<$n; $i++) {
                    tep_remove_category($categories[$i]['id']);
                }

                reset($products_delete);
                while (list($key) = each($products_delete)) {
                    tep_remove_product($key);
                }
            }

            if (USE_CACHE == 'true') {
                tep_reset_cache_block('categories');
                tep_reset_cache_block('also_purchased');
            }

            tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath));
            break;
        case 'delete_product_confirm':
            if (isset($_POST['products_id']) && isset($_POST['product_categories']) && is_array($_POST['product_categories'])) {
                $product_id = tep_db_prepare_input($_POST['products_id']);
                $product_categories = $_POST['product_categories'];

                for ($i=0, $n=sizeof($product_categories); $i<$n; $i++) {
                    tep_db_query("delete from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$product_id . "' and categories_id = '" . (int)$product_categories[$i] . "'");
                }

                $product_categories_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$product_id . "'");
                $product_categories = tep_db_fetch_array($product_categories_query);

                if ($product_categories['total'] == '0') {
                    tep_remove_product($product_id);
                }
            }

            if (USE_CACHE == 'true') {
                tep_reset_cache_block('categories');
                tep_reset_cache_block('also_purchased');
            }

            tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath));
            break;
        case 'move_category_confirm':
            if (isset($_POST['categories_id']) && ($_POST['categories_id'] != $_POST['move_to_category_id'])) {
                $categories_id = tep_db_prepare_input($_POST['categories_id']);
                $new_parent_id = tep_db_prepare_input($_POST['move_to_category_id']);

                $path = explode('_', tep_get_generated_category_path_ids($new_parent_id));

                if (in_array($categories_id, $path)) {
                    $messageStack->add_session(ERROR_CANNOT_MOVE_CATEGORY_TO_PARENT, 'error');

                    tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories_id));
                } else {
                    tep_db_query("update " . TABLE_CATEGORIES . " set parent_id = '" . (int)$new_parent_id . "', last_modified = now() where categories_id = '" . (int)$categories_id . "'");

                    if (USE_CACHE == 'true') {
                        tep_reset_cache_block('categories');
                        tep_reset_cache_block('also_purchased');
                    }

                    tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $new_parent_id . '&cID=' . $categories_id));
                }
            }

            break;
        case 'link_category_confirm':
            if (isset($_POST['categories_id']) && ($_POST['categories_id'] != $_POST['link_to_category_id'])) {
                $new_parent_id = tep_db_prepare_input($_POST['categories_id']);
                $categories_id = tep_db_prepare_input($_POST['link_to_category_id']);


                $path = explode('_', tep_get_generated_category_path_ids($new_parent_id));

                if (in_array($categories_id, $path)) {
                    $messageStack->add_session(ERROR_CANNOT_MOVE_CATEGORY_TO_PARENT, 'error');

                    tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $new_parent_id));
                } else {
                    tep_db_query("update " . TABLE_CATEGORIES . " set parent_id_2 = '" . (int)$new_parent_id . "', last_modified = now() where categories_id = '" . (int)$categories_id . "'");

                    if (USE_CACHE == 'true') {
                        tep_reset_cache_block('categories');
                        tep_reset_cache_block('also_purchased');
                    }

                    tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $new_parent_id . '&cID=' . $new_parent_id));
                }
            }

            break;
        case 'unlink_category':
            if (isset($_GET['cID'])) {
                $categories_id = tep_db_prepare_input($_GET['cID']);

                $path = explode('_', tep_get_generated_category_path_ids($new_parent_id));

                if (in_array($categories_id, $path)) {
                    $messageStack->add_session(ERROR_CANNOT_MOVE_CATEGORY_TO_PARENT, 'error');

                    tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $new_parent_id));
                } else {
                    tep_db_query("update " . TABLE_CATEGORIES . " set parent_id_2 = 0, last_modified = now() where categories_id = '" . (int)$categories_id . "'");

                    if (USE_CACHE == 'true') {
                        tep_reset_cache_block('categories');
                        tep_reset_cache_block('also_purchased');
                    }

                    tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $_GET['parent']));
                }
            }

            break;
        case 'move_product_confirm':
            $products_id = tep_db_prepare_input($_POST['products_id']);
            $new_parent_id = tep_db_prepare_input($_POST['move_to_category_id']);

            $duplicate_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$products_id . "' and categories_id = '" . (int)$new_parent_id . "'");
            $duplicate_check = tep_db_fetch_array($duplicate_check_query);
            if ($duplicate_check['total'] < 1) tep_db_query("update " . TABLE_PRODUCTS_TO_CATEGORIES . " set categories_id = '" . (int)$new_parent_id . "' where products_id = '" . (int)$products_id . "' and categories_id = '" . (int)$current_category_id . "'");

            if (USE_CACHE == 'true') {
                tep_reset_cache_block('categories');
                tep_reset_cache_block('also_purchased');
            }

            tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $new_parent_id . '&pID=' . $products_id));
            break;
        case 'insert_product':
        case 'update_product':

            $products_image = new upload('products_image');
            $products_image->set_destination(DIR_FS_CATALOG_IMAGES);
            if ($products_image->parse() && $products_image->save() && $products_image->resize(200, 200) ) {
                $products_image_name = $products_image->filename;
            } else {
                $products_image_name = (isset($_POST['products_previous_image']) ? $_POST['products_previous_image'] : '');
            }
//       echo $products_image_name;


            if (isset($_POST['edit_x']) || isset($_POST['edit_y'])) {
                $action = 'new_product';
            } else {
                if (isset($_GET['pID'])) $products_id = tep_db_prepare_input($_GET['pID']);
                $products_date_available = tep_db_prepare_input($_POST['products_date_available']);

                $products_date_available = (date('Y-m-d') < $products_date_available) ? $products_date_available : 'null';

                $sql_data_array = array('products_quantity' => (int)tep_db_prepare_input($_POST['products_quantity']),
                    'products_model' => tep_db_prepare_input($_POST['products_model']),
                    'products_price' => tep_db_prepare_input($_POST['products_price']),
                    'products_price_2' => tep_db_prepare_input($_POST['products_price_2']),
                    'products_price_3' => tep_db_prepare_input($_POST['products_price_3']),
                    'products_date_available' => $products_date_available,
                    'products_weight' => (float)tep_db_prepare_input($_POST['products_weight']),
                    'products_status' => tep_db_prepare_input($_POST['products_status']),
                    'products_tax_class_id' => tep_db_prepare_input($_POST['products_tax_class_id']),
                    'manufacturers_id' => (int)tep_db_prepare_input($_POST['manufacturers_id']));

//          if (isset($_POST['products_image']) && tep_not_null($_POST['products_image']) && ($_POST['products_image'] != 'none')) {
                $sql_data_array['products_image'] = $products_image_name;
//          }

                if ($action == 'insert_product') {
                    $insert_sql_data = array('products_date_added' => 'now()');

                    $sql_data_array = array_merge($sql_data_array, $insert_sql_data);
                    //print_r($sql_data_array);
                    tep_db_perform(TABLE_PRODUCTS, $sql_data_array);
                    $products_id = tep_db_insert_id();

                    tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . (int)$products_id . "', '" . (int)$current_category_id . "')");
                } elseif ($action == 'update_product') {
                    $update_sql_data = array('products_last_modified' => 'now()');

                    $sql_data_array = array_merge($sql_data_array, $update_sql_data);

                    tep_db_perform(TABLE_PRODUCTS, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "'");
                }

                $languages = tep_get_languages();
                for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
                    $language_id = $languages[$i]['id'];

                    $sql_data_array = array('products_name' => tep_db_prepare_input($_POST['products_name'][$language_id]),
                        'products_description' => tep_db_prepare_input($_POST['products_description'][$language_id]),
                        'products_url' => tep_db_prepare_input($_POST['products_url'][$language_id]));

                    if ($action == 'insert_product') {
                        $insert_sql_data = array('products_id' => $products_id,
                            'language_id' => $language_id);

                        $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

                        tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array);
                    } elseif ($action == 'update_product') {
                        tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "' and language_id = '" . (int)$language_id . "'");
                    }
                }

                if (USE_CACHE == 'true') {
                    tep_reset_cache_block('categories');
                    tep_reset_cache_block('also_purchased');
                }

                tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products_id));
            }
            break;
        case 'copy_to_confirm':
            if (isset($_POST['products_id']) && isset($_POST['categories_id'])) {
                $products_id = tep_db_prepare_input($_POST['products_id']);
                $categories_id = tep_db_prepare_input($_POST['categories_id']);

                if ($_POST['copy_as'] == 'link') {
                    if ($categories_id != $current_category_id) {
                        $check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$products_id . "' and categories_id = '" . (int)$categories_id . "'");
                        $check = tep_db_fetch_array($check_query);
                        if ($check['total'] < '1') {
                            tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . (int)$products_id . "', '" . (int)$categories_id . "')");
                        }
                    } else {
                        $messageStack->add_session(ERROR_CANNOT_LINK_TO_SAME_CATEGORY, 'error');
                    }
                } elseif ($_POST['copy_as'] == 'duplicate') {
                    $product_query = tep_db_query("select products_quantity, products_model, products_image, products_price, products_price_2, products_price_3, products_date_available, products_weight, products_tax_class_id, manufacturers_id from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'");
                    $product = tep_db_fetch_array($product_query);

                    tep_db_query("insert into " . TABLE_PRODUCTS . " (products_quantity, products_model,products_image, products_price, products_price_2, products_price_3, products_date_added, products_date_available, products_weight, products_status, products_tax_class_id, manufacturers_id) values ('" . tep_db_input($product['products_quantity']) . "', '" . tep_db_input($product['products_model']) . "', '" . tep_db_input($product['products_image']) . "', '" . tep_db_input($product['products_price']) . "', '" . tep_db_input($product['products_price_2']) . "', '" . tep_db_input($product['products_price_3']) . "',  now(), " . (empty($product['products_date_available']) ? "null" : "'" . tep_db_input($product['products_date_available']) . "'") . ", '" . tep_db_input($product['products_weight']) . "', '0', '" . (int)$product['products_tax_class_id'] . "', '" . (int)$product['manufacturers_id'] . "')");
                    $dup_products_id = tep_db_insert_id();

                    $description_query = tep_db_query("select language_id, products_name, products_description, products_url from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$products_id . "'");
                    while ($description = tep_db_fetch_array($description_query)) {
                        tep_db_query("insert into " . TABLE_PRODUCTS_DESCRIPTION . " (products_id, language_id, products_name, products_description, products_url, products_viewed) values ('" . (int)$dup_products_id . "', '" . (int)$description['language_id'] . "', '" . tep_db_input($description['products_name']) . "', '" . tep_db_input($description['products_description']) . "', '" . tep_db_input($description['products_url']) . "', '0')");
                    }

                    tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . (int)$dup_products_id . "', '" . (int)$categories_id . "')");
                    $products_id = $dup_products_id;
                }

                if (USE_CACHE == 'true') {
                    tep_reset_cache_block('categories');
                    tep_reset_cache_block('also_purchased');
                }
            }

            tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $categories_id . '&pID=' . $products_id));
            break;
        case 'new_product_preview':
// copy image only if modified
//
//        $products_image = new upload('products_image');
//        $products_image->set_destination(DIR_FS_CATALOG_IMAGES);
//        if ($products_image->parse() && $products_image->save() && $products_image->resize($_POST['products_id']) ) {
//          $products_image_name = $products_image->filename;
//        } else {
//          $products_image_name = (isset($_POST['products_previous_image']) ? $_POST['products_previous_image'] : '');
//        }
//
            break;
    }
}

// check if the catalog image directory exists
if (is_dir(DIR_FS_CATALOG_IMAGES)) {
    if (!is_writeable(DIR_FS_CATALOG_IMAGES)) $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE, 'error');
} else {
    $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST, 'error');
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
    <body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
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
    <?php
    if ($action == 'new_product') {
        $parameters = array('products_name' => '',
            'products_description' => '',
            'products_url' => '',
            'products_id' => '',
            'products_quantity' => '',
            'products_model' => '',
            'products_image' => '',
            'products_price' => '',
            'products_price_2' => '',
            'products_price_3' => '',
            'products_weight' => '',
            'products_date_added' => '',
            'products_last_modified' => '',
            'products_date_available' => '',
            'products_status' => '',
            'products_tax_class_id' => '',
            'manufacturers_id' => '');

        $pInfo = new objectInfo($parameters);

        if (isset($_GET['pID']) && empty($_POST)) {
            $product_query = tep_db_query("select pd.products_name, pd.products_description, pd.products_url, p.products_id, p.products_quantity, p.products_model, p.products_image, p.products_price, p.products_price_2, p.products_price_3, p.products_weight, p.products_date_added, p.products_last_modified, date_format(p.products_date_available, '%Y-%m-%d') as products_date_available, p.products_status, p.products_tax_class_id, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . (int)$_GET['pID'] . "' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'");
            $product = tep_db_fetch_array($product_query);

            $pInfo->objectInfo($product);
        } elseif (tep_not_null($_POST)) {
            $pInfo->objectInfo($_POST);
            $products_name = $_POST['products_name'];
            $products_description = $_POST['products_description'];
            $products_url = $_POST['products_url'];
        }

        $manufacturers_array = array(array('id' => '', 'text' => TEXT_NONE));
        $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
        while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
            $manufacturers_array[] = array('id' => $manufacturers['manufacturers_id'],
                'text' => $manufacturers['manufacturers_name']);
        }

//    $tax_class_array = array(array('id' => '0', 'text' => TEXT_NONE));
        $tax_class_query = tep_db_query("select tax_class_id, tax_class_title from " . TABLE_TAX_CLASS . " order by tax_class_title");
        while ($tax_class = tep_db_fetch_array($tax_class_query)) {
            $tax_class_array[] = array('id' => $tax_class['tax_class_id'],
                'text' => $tax_class['tax_class_title']);
        }

        $languages = tep_get_languages();

        if (!isset($pInfo->products_status)) $pInfo->products_status = '1';
        switch ($pInfo->products_status) {
            case '0': $in_status = false; $out_status = true; break;
            case '1':
            default: $in_status = true; $out_status = false;
        }
        ?>
        <link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">
        <script language="JavaScript" src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>
        <script language="javascript"><!--
            var dateAvailable = new ctlSpiffyCalendarBox("dateAvailable", "new_product", "products_date_available","btnDate1","<?php echo $pInfo->products_date_available; ?>",scBTNMODE_CUSTOMBLUE);
            //--></script>
        <script language="javascript"><!--
            var tax_rates = new Array();
            <?php
                for ($i=0, $n=sizeof($tax_class_array); $i<$n; $i++) {
                  if ($tax_class_array[$i]['id'] > 0) {
                    echo 'tax_rates["' . $tax_class_array[$i]['id'] . '"] = ' . tep_get_tax_rate_value($tax_class_array[$i]['id']) . ';' . "\n";
                  }
                }
            ?>

            function doRound(x, places) {
                return Math.round(x * Math.pow(10, places)) / Math.pow(10, places);
            }

            function getTaxRate() {
                var selected_value = document.getElementById("new_product").products_tax_class_id.selectedIndex;
                var parameterVal = document.getElementById("new_product").products_tax_class_id[selected_value].value;

                if ( (parameterVal > 0) && (tax_rates[parameterVal] > 0) ) {
                    return tax_rates[parameterVal];
                } else {
                    return 0;
                }
            }

            function updateGross() {
                var taxRate = getTaxRate();
                var grossValue = document.getElementById("new_product").products_price.value;
                var grossValue_2 = document.getElementById("new_product").products_price_2.value;
                var grossValue_3 = document.getElementById("new_product").products_price_3.value;

                if (taxRate > 0) {
                    grossValue = grossValue * ((taxRate / 100) + 1);
                    grossValue_2 = grossValue_2 * ((taxRate / 100) + 1);
                    grossValue_3 = grossValue_3 * ((taxRate / 100) + 1);
                }

                document.getElementById("new_product").products_price_gross.value = doRound(grossValue, 2);
                document.getElementById("new_product").products_price_gross_2.value = doRound(grossValue_2, 2);
                document.getElementById("new_product").products_price_gross_3.value = doRound(grossValue_3, 2);
            }

            function updateNet() {
                var taxRate = getTaxRate();
                var netValue = document.getElementById("new_product").products_price_gross.value;
                var netValue_2 = document.getElementById("new_product").products_price_gross_2.value;
                var netValue_3 = document.getElementById("new_product").products_price_gross_3.value;

                if (taxRate > 0) {
                    netValue = netValue / ((taxRate / 100) + 1);
                    netValue_2 = netValue_2 / ((taxRate / 100) + 1);
                    netValue_3 = netValue_3 / ((taxRate / 100) + 1);
                }

                document.getElementById("new_product").products_price.value = doRound(netValue, 2);
                document.getElementById("new_product").products_price_2.value = doRound(netValue_2, 2);
                document.getElementById("new_product").products_price_3.value = doRound(netValue_3, 2);
            }
            //--></script>
        <?php

        $form_action = (isset($_GET['pID'])) ? 'update_product' : 'insert_product';

        echo tep_draw_form($form_action, FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] : '') . '&action=' . $form_action, 'post', 'enctype="multipart/form-data" id="new_product"');


//    echo tep_draw_form('new_product', FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] : '') . '&action=new_product_preview', 'post', 'enctype="multipart/form-data"'); ?>
        <table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
                <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td class="pageHeading"><?php echo sprintf(TEXT_NEW_PRODUCT, tep_output_generated_category_path($current_category_id)); ?></td>
                            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
                        </tr>
                    </table></td>
            </tr>
            <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
            </tr>
            <tr>
                <td><table border="0" cellspacing="0" cellpadding="2">
                        <tr>
                            <td class="main"><?php echo TEXT_PRODUCTS_STATUS; ?></td>
                            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_radio_field('products_status', '1', $in_status) . '&nbsp;' . TEXT_PRODUCT_AVAILABLE . '&nbsp;' . tep_draw_radio_field('products_status', '0', $out_status) . '&nbsp;' . TEXT_PRODUCT_NOT_AVAILABLE; ?></td>
                        </tr>
                        <tr>
                            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                        </tr>
                        <?if(0){?>
                            <tr>
                                <td class="main"><?php echo TEXT_PRODUCTS_DATE_AVAILABLE; ?><br><small>(YYYY-MM-DD)</small></td>
                                <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;'; ?><script language="javascript">dateAvailable.writeControl(); dateAvailable.dateFormat="yyyy-MM-dd";</script></td>
                            </tr>
                            <tr>
                                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                            </tr>
                            <tr>
                                <td class="main"><?php echo TEXT_PRODUCTS_MANUFACTURER; ?></td>
                                <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_pull_down_menu('manufacturers_id', $manufacturers_array, $pInfo->manufacturers_id); ?></td>
                            </tr>
                        <?}?>
                        <tr>
                            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                        </tr>
                        <?php
                        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
                            ?>
                            <tr>
                                <td class="main"><?php if ($i == 0) echo TEXT_PRODUCTS_NAME; ?></td>
                                <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') .'&nbsp;' . tep_draw_input_field('products_name[' . $languages[$i]['id'] . ']', (isset($products_name[$languages[$i]['id']]) ? stripslashes($products_name[$languages[$i]['id']]) : tep_get_products_name($pInfo->products_id, $languages[$i]['id'])),'size="50"'); ?></td>
                            </tr>
                        <?php
                        }
                        ?>
                        <tr>
                            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                        </tr>
                        <tr bgcolor="#ebebff">
                            <td class="main"><?php echo TEXT_PRODUCTS_TAX_CLASS; ?></td>
                            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_pull_down_menu('products_tax_class_id', $tax_class_array, $pInfo->products_tax_class_id, 'onchange="updateGross()"'); ?></td>
                        </tr>
                        <tr bgcolor="#ebebff">
                            <td class="main"><?php echo TEXT_PRODUCTS_PRICE_NET; ?></td>
                            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_price', $pInfo->products_price, 'onKeyUp="updateGross()"'); ?></td>
                        </tr>
                        <tr bgcolor="#ebebff">
                            <td class="main"><?php echo TEXT_PRODUCTS_PRICE_GROSS; ?></td>
                            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_price_gross', $pInfo->products_price, 'OnKeyUp="updateNet()"'); ?></td>
                        </tr>
                        <tr bgcolor="#ebebff">
                            <td class="main"><?php echo TEXT_PRODUCTS_PRICE_NET; ?></td>
                            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_price_2', $pInfo->products_price_2, 'onKeyUp="updateGross()"'); ?></td>
                        </tr>
                        <tr bgcolor="#ebebff">
                            <td class="main"><?php echo TEXT_PRODUCTS_PRICE_GROSS; ?></td>
                            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_price_gross_2', $pInfo->products_price_2, 'OnKeyUp="updateNet()"'); ?></td>
                        </tr>
                        <tr bgcolor="#ebebff">
                            <td class="main"><?php echo TEXT_PRODUCTS_PRICE_NET; ?></td>
                            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_price_3', $pInfo->products_price_3, 'onKeyUp="updateGross()"'); ?></td>
                        </tr>
                        <tr bgcolor="#ebebff">
                            <td class="main"><?php echo TEXT_PRODUCTS_PRICE_GROSS; ?></td>
                            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_price_gross_3', $pInfo->products_price_3, 'OnKeyUp="updateNet()"'); ?></td>
                        </tr>
                        <tr>
                            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                        </tr>
                        <script language="javascript"><!--
                            updateGross();
                            //--></script>
                        <?php
                        if(0){
                            for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
                                ?>
                                <tr>
                                    <td class="main" valign="top"><?php if ($i == 0) echo TEXT_PRODUCTS_DESCRIPTION; ?></td>
                                    <td><table border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
                                                <td class="main"><?php echo tep_draw_textarea_field('products_description[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (isset($products_description[$languages[$i]['id']]) ? stripslashes($products_description[$languages[$i]['id']]) : tep_get_products_description($pInfo->products_id, $languages[$i]['id']))); ?></td>
                                            </tr>
                                        </table></td>
                                </tr>
                            <?php
                            }

                            ?>
                            <tr>
                                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                            </tr>
                            <tr>
                                <td class="main"><?php echo TEXT_PRODUCTS_QUANTITY; ?></td>
                                <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_quantity', $pInfo->products_quantity); ?></td>
                            </tr>
                            <tr>
                                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                            </tr>
                            <tr>
                                <td class="main"><?php echo TEXT_PRODUCTS_MODEL; ?></td>
                                <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_model', $pInfo->products_model); ?></td>
                            </tr>
                            <tr>
                                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                            </tr>
                        <?}?>
                        <tr>
                            <td class="main"><?php echo TEXT_PRODUCTS_IMAGE; ?></td>
                            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_file_field('products_image') . '<br>' . tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . $pInfo->products_image . tep_draw_hidden_field('products_previous_image', $pInfo->products_image); ?></td>
                        </tr>
                        <?if($pInfo->products_image){?>
                            <tr>
                                <td colspan="2"><img src="../images/<?echo strtoupper($pInfo->products_image[0]).'/'. $pInfo->products_image?>"/></td>
                            </tr>
                        <?}?>
                        <tr>
                            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                        </tr>
                        <?php
                        if(0){
                            for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
                                ?>
                                <tr>
                                    <td class="main"><?php if ($i == 0) echo TEXT_PRODUCTS_URL . '<br><small>' . TEXT_PRODUCTS_URL_WITHOUT_HTTP . '</small>'; ?></td>
                                    <td class="main"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('products_url[' . $languages[$i]['id'] . ']', (isset($products_url[$languages[$i]['id']]) ? stripslashes($products_url[$languages[$i]['id']]) : tep_get_products_url($pInfo->products_id, $languages[$i]['id']))); ?></td>
                                </tr>
                            <?php
                            }
                            ?>
                            <tr>
                                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                            </tr>
                            <tr>
                                <td class="main"><?php echo TEXT_PRODUCTS_WEIGHT; ?></td>
                                <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_weight', $pInfo->products_weight); ?></td>
                            </tr>
                        <?}?>
                    </table></td>
            </tr>
            <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
            </tr>
            <tr>
                <td class="main" align="right"><?php echo tep_draw_hidden_field('products_date_added', (tep_not_null($pInfo->products_date_added) ? $pInfo->products_date_added : date('Y-m-d'))) . tep_image_submit('button_insert.gif', IMAGE_PREVIEW) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] : '')) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
            </tr>
        </table></form>
    <?php
    } elseif ($action == 'new_product_preview') {
        if (tep_not_null($_POST)) {
            $pInfo = new objectInfo($_POST);
            $products_name = $_POST['products_name'];
            $products_description = $_POST['products_description'];
            $products_url = $_POST['products_url'];
        } else {
            $product_query = tep_db_query("select p.products_id, pd.language_id, pd.products_name, pd.products_description, pd.products_url, p.products_quantity, p.products_model, p.products_image, p.products_price, p.products_price_2, p.products_price_3, p.products_weight, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p.manufacturers_id,
            p.PDF_CATALOG, p.PDF_BROSURA, p.PDF_MANUAL, p.PDF_SCHEMA, p.INFO_ADITIONALE, p.PDF_PM, p.ACCESORII, p.PIESE_SCHIMB, p.PROD_ALTERNATIVE  from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and p.products_id = '" . (int)$_GET['pID'] . "'");
            $product = tep_db_fetch_array($product_query);

            $pInfo = new objectInfo($product);
            $products_image_name = $pInfo->products_image;
        }

        $form_action = (isset($_GET['pID'])) ? 'update_product' : 'insert_product';

        echo tep_draw_form($form_action, FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] : '') . '&action=' . $form_action, 'post', 'enctype="multipart/form-data"');

        $languages = tep_get_languages();
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
            if (isset($_GET['read']) && ($_GET['read'] == 'only')) {
                $pInfo->products_name = tep_get_products_name($pInfo->products_id, $languages[$i]['id']);
                $pInfo->products_description = tep_get_products_description($pInfo->products_id, $languages[$i]['id']);
                $pInfo->products_url = tep_get_products_url($pInfo->products_id, $languages[$i]['id']);
            } else {
                $pInfo->products_name = tep_db_prepare_input($products_name[$languages[$i]['id']]);
                $pInfo->products_description = tep_db_prepare_input($products_description[$languages[$i]['id']]);
                $pInfo->products_url = tep_db_prepare_input($products_url[$languages[$i]['id']]);
            }
            ?>
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
                <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td class="pageHeading"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . $pInfo->products_name; ?></td>
                            <td class="pageHeading" align="right"><?php echo $currencies->format($pInfo->products_price); ?> - <?php echo $currencies->format($pInfo->products_price_2); ?> - <?php echo $currencies->format($pInfo->products_price_3); ?></td>
                        </tr>
                    </table></td>
            </tr>
            <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
            </tr>
            <tr>
                <td class="main"><?php echo tep_image('../images/mari/'.strtoupper($products_image_name[0]).'/' . $products_image_name, $pInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="right" hspace="5" vspace="5"') . $pInfo->products_description; ?></td>
            </tr>
            <?php
            if ($pInfo->products_url) {
                ?>
                <tr>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                </tr>
                <tr>
                    <td class="main"><?php echo sprintf(TEXT_PRODUCT_MORE_INFORMATION, $pInfo->products_url); ?></td>
                </tr>
            <?php
            }
            ?>
            <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
            </tr>
            <?php
            if ($pInfo->products_date_available > date('Y-m-d')) {
                ?>
                <tr>
                    <td align="center" class="smallText"><?php echo sprintf(TEXT_PRODUCT_DATE_AVAILABLE, tep_date_long($pInfo->products_date_available)); ?></td>
                </tr>
            <?php
            } else {
                ?>
                <tr>
                    <td align="center" class="smallText"><?php echo sprintf(TEXT_PRODUCT_DATE_ADDED, tep_date_long($pInfo->products_date_added)); ?></td>
                </tr>
            <?php
            }
            ?>
            <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
            </tr>
            <?php if($pInfo->PDF_CATALOG):?>
            <table style="width: 800px; font-size: 10px;">
                <tr>
                    <th><?php echo'Catalog'?></th>
                    <th><?php echo'Brosura'?></th>
                    <th><?php echo'Manual'?></th>
                    <th><?php echo'Schema Explodata'?></th>
                    <th><?php echo'Info Aditionale'?></th>
                    <th><?php echo'Protectia Muncii'?></th>
                    <th><?php echo'Accesorii:'?></th>
                    <th><?php echo'Piese Schimb:'?></th>
                    <th><?php echo'Produse Altenative:'?></th>

                </tr>
                <tr>
                    <td><?php echo $pInfo->PDF_CATALOG ?></td>
                    <td><?php echo $pInfo->PDF_BROSURA ?></td>
                    <td><?php echo $pInfo->PDF_MANUAL ?></td>
                    <td><?php echo $pInfo->PDF_SCHEMA ?></td>
                    <td><?php echo $pInfo->INFO_ADITIONALE ?></td>
                    <td><?php echo $pInfo->PDF_PM ?></td>
                    <td><?php echo $pInfo->ACCESORII ?></td>
                    <td><?php echo $pInfo->PIESE_SCHIMB ?></td>
                    <td><?php echo str_replace(',',' ',$pInfo->PROD_ALTERNATIVE) ?></td>
                </tr>
            </table>

        <?php endif;
        }

        if (isset($_GET['read']) && ($_GET['read'] == 'only')) {
            if (isset($_GET['origin'])) {
                $pos_params = strpos($_GET['origin'], '?', 0);
                if ($pos_params != false) {
                    $back_url = substr($_GET['origin'], 0, $pos_params);
                    $back_url_params = substr($_GET['origin'], $pos_params + 1);
                } else {
                    $back_url = $_GET['origin'];
                    $back_url_params = '';
                }
            } else {
                $back_url = FILENAME_CATEGORIES;
                $back_url_params = 'cPath=' . $cPath . '&pID=' . $pInfo->products_id;
            }
            ?>
            <tr>
                <td align="right"><?php echo '<a href="' . tep_href_link($back_url, $back_url_params, 'NONSSL') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
            </tr>
        <?php
        } else {
            ?>
            <tr>
                <td align="right" class="smallText">
                    <?php
                    /* Re-Post all POST'ed variables */
                    reset($_POST);
                    while (list($key, $value) = each($_POST)) {
                        if (!is_array($_POST[$key])) {
                            echo tep_draw_hidden_field($key, htmlspecialchars(stripslashes($value)));
                        }
                    }
                    $languages = tep_get_languages();
                    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
                        echo tep_draw_hidden_field('products_name[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_name[$languages[$i]['id']])));
                        echo tep_draw_hidden_field('products_description[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_description[$languages[$i]['id']])));
                        echo tep_draw_hidden_field('products_url[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_url[$languages[$i]['id']])));
                    }
                    echo tep_draw_hidden_field('products_image', stripslashes($products_image_name));

                    echo tep_image_submit('button_back.gif', IMAGE_BACK, 'name="edit"') . '&nbsp;&nbsp;';

                    if (isset($_GET['pID'])) {
                        echo tep_image_submit('button_update.gif', IMAGE_UPDATE);
                    } else {
                        echo tep_image_submit('button_insert.gif', IMAGE_INSERT);
                    }
                    echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] : '')) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
                    ?></td>
            </tr>
            </table></form>
        <?php
        }
    } else {
        ?>
        <table border="0" width="100%" cellspacing="0" cellpadding="2">
        <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
                        <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
                        <td align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td class="smallText" align="right">
                                        <?php
                                        echo tep_draw_form('search', FILENAME_CATEGORIES, '', 'get');
                                        echo HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('search');
                                        echo tep_hide_session_id() . '</form>';
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="smallText" align="right">
                                        <?php
                                        echo tep_draw_form('goto', FILENAME_CATEGORIES, '', 'get');
                                        echo HEADING_TITLE_GOTO . ' ' . tep_draw_pull_down_menu('cPath', tep_get_category_tree(), $current_category_id, 'onChange="this.form.submit();"');
                                        echo tep_hide_session_id() . '</form>';
                                        ?>
                                    </td>
                                </tr>
                            </table></td>
                    </tr>
                </table></td>
        </tr>
        <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
        <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr class="dataTableHeadingRow">
                    <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CATEGORIES_PRODUCTS; ?></td>
                    <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_STATUS; ?></td>
                    <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
                </tr>
                <?php
                $categories_count = 0;
                $rows = 0;
                if (isset($_GET['search'])) {
                    $search = tep_db_prepare_input($_GET['search']);

                    $categories_query = tep_db_query("select c.categories_id, cd.categories_name, cd.seo, c.parent_id, c.sort_order, c.date_added, c.last_modified from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and cd.categories_name like '%" . tep_db_input($search) . "%' order by c.sort_order, cd.categories_name");
                } else if(tep_is_fake_category($current_category_id)){
                    $categories_query = tep_db_query("select c.categories_id, cd.categories_name, cd.seo, c.parent_id_2, c.sort_order, c.date_added, c.last_modified from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id_2 = '" . (int)$current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by c.sort_order, cd.categories_name");
                } else {
                    $categories_query = tep_db_query("select c.categories_id, cd.categories_name, cd.seo, c.parent_id, c.sort_order, c.date_added, c.last_modified from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by c.sort_order, cd.categories_name");
                }
                while ($categories = tep_db_fetch_array($categories_query)) {
                    $categories_count++;
                    $rows++;

// Get parent_id for subcategories if search
                    if (isset($_GET['search'])) $cPath= $categories['parent_id'];

                    if ((!isset($_GET['cID']) && !isset($_GET['pID']) || (isset($_GET['cID']) && ($_GET['cID'] == $categories['categories_id']))) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
                        $category_childs = array('childs_count' => tep_childs_in_category_count($categories['categories_id']));
                        $category_products = array('products_count' => tep_products_in_category_count($categories['categories_id']));

                        $cInfo_array = array_merge($categories, $category_childs, $category_products);
                        $cInfo = new objectInfo($cInfo_array);
                        // var_dump($categories);
                    }

                    if (isset($cInfo) && is_object($cInfo) && ($categories['categories_id'] == $cInfo->categories_id) ) {
                        echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, tep_get_path($categories['categories_id'])) . '\'">' . "\n";
                    } else {
                        echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories['categories_id']) . '\'">' . "\n";
                    }
                    ?>
                    <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_path($categories['categories_id'])) . '">' . tep_image(DIR_WS_ICONS . 'folder.gif', ICON_FOLDER) . '</a>&nbsp;<b>' . $categories['categories_name'] . '</b>'; ?></td>
                    <td class="dataTableContent" align="center">&nbsp;</td>
                    <td class="dataTableContent" align="right"><?php if (isset($cInfo) && is_object($cInfo) && ($categories['categories_id'] == $cInfo->categories_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories['categories_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
                    </tr>
                <?php
                }

                $products_count = 0;
                if (isset($_GET['search'])) {
                    $products_query = tep_db_query("select p.products_id, pd.products_name, p.products_quantity, p.products_image, p.products_price, p.products_price_2, p.products_price_3, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p2c.categories_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id and pd.products_name like '%" . tep_db_input($search) . "%' order by pd.products_name");
                } else {
                    $products_query = tep_db_query("select p.products_id, pd.products_name, p.products_quantity, p.products_image, p.products_price, p.products_price_2, p.products_price_3, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$current_category_id . "' order by pd.products_name");
                }
                while ($products = tep_db_fetch_array($products_query)) {
                    $products_count++;
                    $rows++;

// Get categories_id for product if search
                    if (isset($_GET['search'])) $cPath = $products['categories_id'];

                    if ( (!isset($_GET['pID']) && !isset($_GET['cID']) || (isset($_GET['pID']) && ($_GET['pID'] == $products['products_id']))) && !isset($pInfo) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
// find out the rating average from customer reviews
                        $reviews_query = tep_db_query("select (avg(reviews_rating) / 5 * 100) as average_rating from " . TABLE_REVIEWS . " where products_id = '" . (int)$products['products_id'] . "'");
                        $reviews = tep_db_fetch_array($reviews_query);
                        $pInfo_array = array_merge($products, $reviews);
                        $pInfo = new objectInfo($pInfo_array);
                    }

                    if (isset($pInfo) && is_object($pInfo) && ($products['products_id'] == $pInfo->products_id) ) {
                        echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=new_product_preview&read=only') . '\'">' . "\n";
                    } else {
                        echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id']) . '\'">' . "\n";
                    }
                    ?>
                    <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=new_product_preview&read=only') . '">' . tep_image(DIR_WS_ICONS . 'preview.gif', ICON_PREVIEW) . '</a>&nbsp;' . $products['products_name']; ?></td>
                    <td class="dataTableContent" align="center">
                        <?php
                        if ($products['products_status'] == '1') {
                            echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflag&flag=0&pID=' . $products['products_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
                        } else {
                            echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflag&flag=1&pID=' . $products['products_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
                        }
                        ?></td>
                    <td class="dataTableContent" align="right"><?php if (isset($pInfo) && is_object($pInfo) && ($products['products_id'] == $pInfo->products_id)) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
                    </tr>
                <?php
                }

                $cPath_back = '';
                if (sizeof($cPath_array) > 0) {
                    for ($i=0, $n=sizeof($cPath_array)-1; $i<$n; $i++) {
                        if (empty($cPath_back)) {
                            $cPath_back .= $cPath_array[$i];
                        } else {
                            $cPath_back .= '_' . $cPath_array[$i];
                        }
                    }
                }

                $cPath_back = (tep_not_null($cPath_back)) ? 'cPath=' . $cPath_back . '&' : '';
                ?>
                <tr>
                    <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                            <tr>
                                <td class="smallText"><?php echo TEXT_CATEGORIES . '&nbsp;' . $categories_count . '<br>' . TEXT_PRODUCTS . '&nbsp;' . $products_count; ?></td>
                                <td align="right" class="smallText"><?php
                                    if(tep_is_fake_category($current_category_id)){
                                        echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&action=link_category') . '">' . tep_image_button('button_link_categ.png', 'Adauga link catre o categorie') . '</a>&nbsp;';
                                    }else{
                                        if (sizeof($cPath_array) > 0)
                                            echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, $cPath_back . 'cID=' . $current_category_id) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>&nbsp;';
                                        if (!isset($_GET['search']))
                                            echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&action=new_category') . '">' . tep_image_button('button_new_category.gif', IMAGE_NEW_CATEGORY) . '</a>&nbsp;' .
                                                //'<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&action=new_product') . '">' . tep_image_button('button_new_product.gif', IMAGE_NEW_PRODUCT) . '</a>'
                                                '';
                                    }
                                    ?>&nbsp;</td>
                            </tr>
                            <tr>
                                <td class="smallText"></td>
                                <td align="right" class="smallText">

                                    <?			if(!tep_is_fake_category($current_category_id)){?>
                                        <form action="categories.php?action=upload&cPath=<?=$cPath?>&cID=<?=$current_category_id?>" method="post" enctype="multipart/form-data">Upload Produse (format xls) <input type="file" name="uploadedfile" onchange="this.form.submit()"></form>
                                    <?}?>
                                </td>

                            </tr>
                        </table></td>
                </tr>
            </table></td>
        <?php
        $heading = array();
        $contents = array();
        switch ($action) {
            case 'new_category':
                $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_CATEGORY . '</b>');

                $contents = array('form' => tep_draw_form('newcategory', FILENAME_CATEGORIES, 'action=insert_category&cPath=' . $cPath, 'post', 'enctype="multipart/form-data"'));
                $contents[] = array('text' => TEXT_NEW_CATEGORY_INTRO);

                $category_inputs_string = '';
                $languages = tep_get_languages();
                for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
                    $category_inputs_string .= '<br>' . tep_draw_input_field('categories_name[' . $languages[$i]['id'] . ']');
                }

                $contents[] = array('text' => '<br>' . TEXT_CATEGORIES_NAME . $category_inputs_string);
//        $contents[] = array('text' => '<br>' . TEXT_CATEGORIES_IMAGE . '<br>' . tep_draw_file_field('categories_image'));
                $contents[] = array('text' => '<br>' . TEXT_SORT_ORDER . '<br>' . tep_draw_input_field('sort_order', '', 'size="2"'));
                $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
                break;
            case 'edit_category':
                $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_CATEGORY . '</b>');

                $contents = array('form' => tep_draw_form('categories', FILENAME_CATEGORIES, 'action=update_category&cPath=' . $cPath, 'post', 'enctype="multipart/form-data"') . tep_draw_hidden_field('categories_id', $cInfo->categories_id));
                $contents[] = array('text' => TEXT_EDIT_INTRO);

                $category_inputs_string = '';
                $languages = tep_get_languages();
                for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
                    $category_inputs_string .= '<br>' . tep_draw_input_field('categories_name[' . $languages[$i]['id'] . ']', tep_get_category_name($cInfo->categories_id, $languages[$i]['id']));
                }

                $contents[] = array('text' => '<br>' . TEXT_EDIT_CATEGORIES_NAME . $category_inputs_string);
//        $contents[] = array('text' => '<br>' . tep_image(DIR_WS_CATALOG_IMAGES . $cInfo->categories_image, $cInfo->categories_name) . '<br>' . DIR_WS_CATALOG_IMAGES . '<br><b>' . $cInfo->categories_image . '</b>');
//        $contents[] = array('text' => '<br>' . TEXT_EDIT_CATEGORIES_IMAGE . '<br>' . tep_draw_file_field('categories_image'));
                $contents[] = array('text' => '<br>' . TEXT_EDIT_SORT_ORDER . '<br>' . tep_draw_input_field('sort_order', $cInfo->sort_order, 'size="2"'));
//ifcts
                $contents[] = array('text' => '<br>SEO:<br>' . tep_draw_textarea_field('seo','soft',50,5,$cInfo->seo));

                $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
                break;
            case 'delete_category':
                $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_CATEGORY . '</b>');

                $contents = array('form' => tep_draw_form('categories', FILENAME_CATEGORIES, 'action=delete_category_confirm&cPath=' . $cPath) . tep_draw_hidden_field('categories_id', $cInfo->categories_id));
                $contents[] = array('text' => TEXT_DELETE_CATEGORY_INTRO);
                $contents[] = array('text' => '<br><b>' . $cInfo->categories_name . '</b>');
                if ($cInfo->childs_count > 0) $contents[] = array('text' => '<br>' . sprintf(TEXT_DELETE_WARNING_CHILDS, $cInfo->childs_count));
                if ($cInfo->products_count > 0) $contents[] = array('text' => '<br>' . sprintf(TEXT_DELETE_WARNING_PRODUCTS, $cInfo->products_count));
                $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
                break;
            case 'move_category':
                $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_MOVE_CATEGORY . '</b>');

                $contents = array('form' => tep_draw_form('categories', FILENAME_CATEGORIES, 'action=move_category_confirm&cPath=' . $cPath) . tep_draw_hidden_field('categories_id', $cInfo->categories_id));
                $contents[] = array('text' => sprintf(TEXT_MOVE_CATEGORIES_INTRO, $cInfo->categories_name));
                $contents[] = array('text' => '<br>' . sprintf(TEXT_MOVE, $cInfo->categories_name) . '<br>' . tep_draw_pull_down_menu('move_to_category_id', tep_get_category_tree(), $current_category_id));
                $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_move.gif', IMAGE_MOVE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
                break;
            case 'link_category':
                $heading[] = array('text' => '<b>Adauga link catre o categorie</b>');

                $contents = array('form' => tep_draw_form('categories', FILENAME_CATEGORIES, 'action=link_category_confirm&cPath=' . $cPath) . tep_draw_hidden_field('categories_id', $current_category_id));
                $contents[] = array('text' => sprintf("", $cInfo->categories_name));
                $contents[] = array('text' => '<br>' . sprintf("Selecteaza o categorie: ", $cInfo->categories_name) . '<br>' . tep_draw_pull_down_menu('link_to_category_id', tep_get_category_tree(), $current_category_id));
                $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $current_category_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
                break;
            case 'delete_product':
                $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_PRODUCT . '</b>');

                $contents = array('form' => tep_draw_form('products', FILENAME_CATEGORIES, 'action=delete_product_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->products_id));
                $contents[] = array('text' => TEXT_DELETE_PRODUCT_INTRO);
                $contents[] = array('text' => '<br><b>' . $pInfo->products_name . '</b>');

                $product_categories_string = '';
                $product_categories = tep_generate_category_path($pInfo->products_id, 'product');
                for ($i = 0, $n = sizeof($product_categories); $i < $n; $i++) {
                    $category_path = '';
                    for ($j = 0, $k = sizeof($product_categories[$i]); $j < $k; $j++) {
                        $category_path .= $product_categories[$i][$j]['text'] . '&nbsp;&gt;&nbsp;';
                    }
                    $category_path = substr($category_path, 0, -16);
                    $product_categories_string .= tep_draw_checkbox_field('product_categories[]', $product_categories[$i][sizeof($product_categories[$i])-1]['id'], true) . '&nbsp;' . $category_path . '<br>';
                }
                $product_categories_string = substr($product_categories_string, 0, -4);

                $contents[] = array('text' => '<br>' . $product_categories_string);
                $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
                break;
            case 'move_product':
                $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_MOVE_PRODUCT . '</b>');

                $contents = array('form' => tep_draw_form('products', FILENAME_CATEGORIES, 'action=move_product_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->products_id));
                $contents[] = array('text' => sprintf(TEXT_MOVE_PRODUCTS_INTRO, $pInfo->products_name));
                $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENT_CATEGORIES . '<br><b>' . tep_output_generated_category_path($pInfo->products_id, 'product') . '</b>');
                $contents[] = array('text' => '<br>' . sprintf(TEXT_MOVE, $pInfo->products_name) . '<br>' . tep_draw_pull_down_menu('move_to_category_id', tep_get_category_tree(), $current_category_id));
                $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_move.gif', IMAGE_MOVE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
                break;
            case 'copy_to':
                $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_COPY_TO . '</b>');

                $contents = array('form' => tep_draw_form('copy_to', FILENAME_CATEGORIES, 'action=copy_to_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->products_id));
                $contents[] = array('text' => TEXT_INFO_COPY_TO_INTRO);
                $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENT_CATEGORIES . '<br><b>' . tep_output_generated_category_path($pInfo->products_id, 'product') . '</b>');
                $contents[] = array('text' => '<br>' . TEXT_CATEGORIES . '<br>' . tep_draw_pull_down_menu('categories_id', tep_get_category_tree(), $current_category_id));
                $contents[] = array('text' => '<br>' . TEXT_HOW_TO_COPY . '<br>' . tep_draw_radio_field('copy_as', 'link', true) . ' ' . TEXT_COPY_AS_LINK . '<br>' . tep_draw_radio_field('copy_as', 'duplicate') . ' ' . TEXT_COPY_AS_DUPLICATE);
                $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_copy.gif', IMAGE_COPY) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
                break;
            default:
                if ($rows > 0) {
                    if (isset($cInfo) && is_object($cInfo)) { // category info box contents
                        $category_path_string = '';
                        $category_path = tep_generate_category_path($cInfo->categories_id);
                        for ($i=(sizeof($category_path[0])-1); $i>0; $i--) {
                            $category_path_string .= $category_path[0][$i]['id'] . '_';
                        }
                        $category_path_string = substr($category_path_string, 0, -1);

                        $heading[] = array('text' => '<b>' . $cInfo->categories_name . '</b>');
                        if(tep_is_fake_category($current_category_id)){
                            $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $category_path_string . '&cID=' . $cInfo->categories_id . '&action=unlink_category&parent=' . $current_category_id) . '">' . tep_image_button('button_delete_link.png','Unlink Category') . '</a>');
                        }else{
                            $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $category_path_string . '&cID=' . $cInfo->categories_id . '&action=edit_category') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> ' .
                            '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $category_path_string . '&cID=' . $cInfo->categories_id . '&action=delete_category') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a> ' .
                            '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $category_path_string . '&cID=' . $cInfo->categories_id . '&action=move_category') . '">' . tep_image_button('button_move.gif', IMAGE_MOVE) . '</a>');
                        }
                        $contents[] = array('text' => '<br>' . TEXT_DATE_ADDED . ' ' . tep_date_short($cInfo->date_added));
                        if (tep_not_null($cInfo->last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . tep_date_short($cInfo->last_modified));
//            $contents[] = array('text' => '<br>' . tep_info_image($cInfo->categories_image, $cInfo->categories_name, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT) . '<br>' . $cInfo->categories_image);
                        $contents[] = array('text' => '<br>' . TEXT_SUBCATEGORIES . ' ' . $cInfo->childs_count . '<br>' . TEXT_PRODUCTS . ' ' . $cInfo->products_count);
                        $contents[] = array('text' => '<br>' . "Pozitia in sortare:" . ' ' . $cInfo->sort_order);
                    } elseif (isset($pInfo) && is_object($pInfo)) { // product info box contents
                        $heading[] = array('text' => '<b>' . tep_get_products_name($pInfo->products_id, $languages_id) . '</b>');

                        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_XLS_FILE, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id ) . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=delete_product') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=move_product') . '">' . tep_image_button('button_move.gif', IMAGE_MOVE) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=copy_to') . '">' . tep_image_button('button_copy_to.gif', IMAGE_COPY_TO) . '</a>');
                        $contents[] = array('text' => '<br>' . TEXT_DATE_ADDED . ' ' . tep_date_short($pInfo->products_date_added));
                        if (tep_not_null($pInfo->products_last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . tep_date_short($pInfo->products_last_modified));
                        if (date('Y-m-d') < $pInfo->products_date_available) $contents[] = array('text' => TEXT_DATE_AVAILABLE . ' ' . tep_date_short($pInfo->products_date_available));
                        $contents[] = array('text' => '<br><img src="../images/mari/'.strtoupper($pInfo->products_image[0]).'/'.$pInfo->products_image.'" ><br>');
                        $contents[] = array('text' => '<br>' . TEXT_PRODUCTS_PRICE_INFO . ' ' . $currencies->format($pInfo->products_price) . '<br>');
                        $contents[] = array('text' => '<br>' . TEXT_PRODUCTS_AVERAGE_RATING . ' ' . number_format($pInfo->average_rating, 2) . '%');
                    }
                } else { // create category/product info
                    $heading[] = array('text' => '<b>' . EMPTY_CATEGORY . '</b>');

                    $contents[] = array('text' => TEXT_NO_CHILD_CATEGORIES_OR_PRODUCTS);
                }
                break;
        }

        if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
            echo '            <td width="25%" valign="top">' . "\n";

            $box = new box;
            echo $box->infoBox($heading, $contents);

            echo '            </td>' . "\n";
        }
        ?>
        </tr>
        </table></td>
        </tr>
        </table>
    <?php
    }
    ?>
    </td>
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