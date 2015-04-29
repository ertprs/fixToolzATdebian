<?php
/**
 * Created by PhpStorm.
 * User: ovidiu
 * Date: 11/8/13
 * Time: 3:57 PM
 */

define('QUERY_LIMIT',5);
define('TVA',1.24);

//require('includes/application_top.php');
require('includes/configure.php');
require(DIR_WS_INCLUDES.'filenames.php');
//require(DIR_WS_FUNCTIONS.'general.php');


////
// ULTIMATE Seo Urls 5 by FWR Media
// The HTML href link wrapper function
function tep_href_link($page = '', $parameters = '', $connection = 'NONSSL', $add_session_id = true, $search_engine_safe = true) {
    global $seo_urls, $languages_id, $request_type, $session_started, $sid;
    if ( !is_object($seo_urls) ){
        include_once DIR_WS_MODULES . 'ultimate_seo_urls5' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'usu.php';
        $seo_urls = new usu($languages_id, $request_type, $session_started, $sid);
    }
    return $seo_urls->href_link($page, $parameters, $connection, $add_session_id);
}

function exists($search){
    global $return_arr;
    $ret = false;
    foreach($return_arr as $value){
        if($value['label'] == $search) return true;
    }
    return $ret;
}

function href_link($filename, $parameters){
    return HTTP_SERVER .DIRECTORY_SEPARATOR. $filename .'?' . $parameters;
}
function img_link($filename){
    return 'images/mici/' . $filename;
}

function db_fetch_array($query){
    return $query->fetch_array();
}

function db_num_rows($query){
    return $query->num_rows;
}

function db_query($query, $limit = 10){
    global $mysqli;
    //echo $query;
    $res = $mysqli->query($query .' LIMIT '.$limit);

    if ($mysqli->error) {
        try {
            throw new Exception("MySQL error $mysqli->error <br> Query:<br> $query", $mysqli->errno);
        } catch(Exception $e ) {
            echo "Error No: ".$e->getCode(). " - ". $e->getMessage() . "<br >";
            echo nl2br($e->getTraceAsString());
        }
    }
    return $res;
}

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

$time_start = microtime_float();

$mysqli = new mysqli(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD, DB_DATABASE);

/* check connection */
if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}


if ($_REQUEST['term'] != ''){
    $search_terms = $mysqli->real_escape_string($_REQUEST['term']);
    $terms = explode(' ',$search_terms);
    $prod_search = (sizeof($terms) > 1)? implode("%' AND products_name LIKE '%",$terms)."%'":$terms[0]."%'";

    $query = db_query("SELECT pd.products_name, pd.products_id, p.products_image, p.products_price
                       FROM products p
                       INNER JOIN products_description pd
                       ON p.products_id = pd.products_id
                       WHERE products_name LIKE '%$search_terms%'", QUERY_LIMIT);

    while ($products = db_fetch_array($query))  {
        $product_name = mb_convert_encoding($products['products_name'],'UTF-8');
        $return_arr[] = array('label'=> $product_name,
            'category' =>'Produse:',
            'url' => href_link(FILENAME_PRODUCT_INFO,'products_id='.$products['products_id']),
            'price' => sprintf("%.2f",intval($products['products_price']) * TVA),
            'img' => img_link($products['products_image']));

    }
    if (db_num_rows($query) < QUERY_LIMIT)
        $query = db_query("SELECT pd.products_name, p.products_id, p.products_image, p.products_price
                           FROM products p
                           INNER JOIN products_description pd
                           ON p.products_id = pd.products_id
                           WHERE products_name LIKE '%$prod_search", QUERY_LIMIT - db_num_rows($query) );
    while ($products = db_fetch_array($query))  {
        $product_name = mb_convert_encoding($products['products_name'],'UTF-8');
        $return_arr[] = array('label'=> $product_name,
            'category' =>'Produse:',
            'url' => href_link(FILENAME_PRODUCT_INFO,'products_id='.$products['products_id']),
            'price' => sprintf("%.2f",intval($products['products_price']) * TVA),
            'img' => img_link($products['products_image']));

    }


    $query = db_query("SELECT DISTINCT categories_name, categories_id FROM categories_description WHERE categories_name LIKE '%$search_terms%'", QUERY_LIMIT);

    while ($categories = db_fetch_array($query))  {
        $category_name = mb_convert_encoding($categories['categories_name'],'UTF-8');
        $return_arr[] = array('label' => $category_name,
            'category' => 'Categorii:',
            'url' => href_link(FILENAME_DEFAULT,'cPath='.$categories['categories_id']));

    }


    $cat_search = (sizeof($terms) > 1)? implode("%' AND categories_name LIKE '%",$terms)."%'":$terms[0]."%'";
    //if (sizeof($terms) > 1) {$cat_search = implode("%' or categories_name LIKE '%",$terms)."%'";} else {$cat_search = $terms[0]."%'";}

    if (db_num_rows($query) < QUERY_LIMIT){
        $query = db_query("SELECT DISTINCT categories_name, categories_id FROM categories_description WHERE categories_name LIKE '%$cat_search", QUERY_LIMIT - db_num_rows($query) );
        while ($categories = db_fetch_array($query))  {

            $category_name = mb_convert_encoding($categories['categories_name'],'UTF-8');
            if (!exists($category_name))
                $return_arr[] = array('label' => $category_name,
                    'category' => 'Categorii:',
                    'url' => href_link(FILENAME_DEFAULT,'cPath='.$categories['categories_id']));

        }
    }

    //print_r($return_arr);
    echo json_encode($return_arr);
}else if ($_REQUEST['pix'] != '') {
    $pix = $mysqli->real_escape_string($_REQUEST['pix']);

}

else die('Invalid usage!');

$time_end = microtime_float();
$time = $time_end - $time_start;

//printf("<br>Execution took %01.2f seconds", $time);
