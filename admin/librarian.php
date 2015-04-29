<?php
/*
  $Id: stats_customers.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

require_once('includes/application_top.php');?>


<?
define('MARI_PATH','/home1/toolz/www/images/mari/');
define('MICI_PATH','/home1/toolz/www/images/mici/');
define('POZE_PATH','/home1/toolz/www/poze/');
define('PDFS_PATH','/home1/toolz/www/pdf/');
define('DESTERS_PATH','/home1/toolz/www/images/de-sters/');

define('IMGS_URL','http://www.toolszone.ro/images/mari/');
define('PDFS_URL','http://www.toolszone.ro/pdf/');
define('RESULTS_PER_PAGE',100);



function  get_db_pdfs(){
    $pdfs = array();
    $query = tep_db_query("SELECT products_id, cod_unic, products_pdf  FROM products");
    while ($p = tep_db_fetch_array($query)){

        if (file_exists(PDFS_PATH.$p['products_pdf'])){
            $pdfs[$p['products_image']]['id'] = $p['products_id'];
            $pdfs[$p['products_image']]['cu'] = $p['cod_unic'];
        }

    }
    return $pdfs;
}
function  get_db_images(){
    $images = array();
    $query = tep_db_query("SELECT products_id, cod_unic, products_image, products_image_2, products_image_3, products_image_4  FROM products");
    while ($p = tep_db_fetch_array($query)){

        if (file_exists(MARI_PATH.$p['products_image'])){
        /*$images[$p['products_image']]['path'] = 'http://www.toolszone.ro/images/mari/'. $p['products_image'];*/
        $images[$p['products_image']]['id'] = $p['products_id'];
        $images[$p['products_image']]['cu'] = $p['cod_unic'];
        }
        if (file_exists(MARI_PATH.$p['products_image_2'])){
            /*$images[$p['products_image']]['path'] = 'http://www.toolszone.ro/images/mari/'. $p['products_image'];*/
            $images[$p['products_image_2']]['id'] = $p['products_id'];
            $images[$p['products_image_2']]['cu'] = $p['cod_unic'];
        }
        if (file_exists(MARI_PATH.$p['products_image_3'])){
            /*$images[$p['products_image']]['path'] = 'http://www.toolszone.ro/images/mari/'. $p['products_image'];*/
            $images[$p['products_image_3']]['id'] = $p['products_id'];
            $images[$p['products_image_3']]['cu'] = $p['cod_unic'];
        }
        if (file_exists(MARI_PATH.$p['products_image_4'])){
            /*$images[$p['products_image']]['path'] = 'http://www.toolszone.ro/images/mari/'. $p['products_image'];*/
            $images[$p['products_image_4']]['id'] = $p['products_id'];
            $images[$p['products_image_4']]['cu'] = $p['cod_unic'];
        }

    }
    return $images;
}

function  get_images($dir){
    $images = array();
    if ($handle = opendir($dir)) {

        /* This is the correct way to loop over the directory. */
        $i = 0;
        while (false !== ($entry = readdir($handle))) {
            if($entry != '.' && $entry != '..' && $entry != '.htaccess' && $entry != '.ftpquota' && !is_dir(POZE_PATH.$entry) )
                $images[$i++] = $entry;
        }
        closedir($handle);
    }

    return $images;
}






//require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
<!doctype html>
<html <?php echo HTML_PARAMS; ?>>
<head>

</head>
    <body>
        <?
        $pictures_paths = array(POZE_PATH,MARI_PATH,MICI_PATH);
        foreach ($pictures_paths as $path){
            foreach (get_images($path) as $image){
                if (!file_exists($path.strtoupper($image[0]))) {
                    mkdir($path.strtoupper($image[0]), 0755, true);
                }
                echo $image.'...';
                $success = rename($path.$image,$path .strtoupper($image[0]).'/'. $image);
                echo ($success)?'success<br>':'error<br>';
            }
        }
        echo 'all done';
        ?>
    </body>
</html>