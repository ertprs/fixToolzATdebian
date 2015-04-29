<?php
/*
  $Id: stats_customers.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');?>


<?
define(MARI_PATH,'/home1/toolz/www/images/mari/');
define(MICI_PATH,'/home1/toolz/www/images/mici/');
define(POZE_PATH,'/home1/toolz/www/poze/');
define(PDFS_PATH,'/home1/toolz/www/pdf/');
define(DESTERS_PATH,'/home1/toolz/www/images/de-sters/');

define(IMGS_URL,'http://www.toolszone.ro/images/mari/');
define(PDFS_URL,'http://www.toolszone.ro/pdf/');
define(RESULTS_PER_PAGE,100);



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

function  get_fs_images(){
    $images = array();
    if ($handle = opendir(MARI_PATH)) {

        /* This is the correct way to loop over the directory. */
        $i = 0;
        while (false !== ($entry = readdir($handle))) {
            if($entry != '.' && $entry != '..')
                $images[$i++] = $entry;
        }
        closedir($handle);
    }

    return $images;
}

function  get_fs_pdfs(){
    $pdfs = array();
    if ($handle = opendir(PDFS_PATH)) {

        /* This is the correct way to loop over the directory. */
        $i = 0;
        while (false !== ($entry = readdir($handle))) {
            if($entry != '.' && $entry != '..')
                $pdfs[$i++] = $entry;
        }
        closedir($handle);
    }

    return $pdfs;
}


if ($_REQUEST['DELETE'] == 'true'){

    if (file_exists(PDFS_PATH.$_REQUEST['name'])){
        rename(PDFS_PATH.$name,PDFS_PATH.'/de-sters/pdf/'.$name);
        echo 'success';
    }

    if (file_exists(MARI_PATH.$_REQUEST['name'])){
        rename(MARI_PATH.$name,DESTERS_PATH.'/mari/'.$name);
        rename(MICI_PATH.$name,DESTERS_PATH.'/mici/'.$name);
        rename(POZE_PATH.$name,DESTERS_PATH.'/poze/'.$name);

        echo 'success';
    }
    die();

}


if ($_REQUEST['pdfuri'] == 'true'){

    $im = new imagick('../pdf/1_9910.pdf[0]');
    $im->setImageFormat('jpg');
    $im->setResolution( 75, 75 );
    $im->resizeImage(250 ,300, imagick::FILTER_POINT, 1, true);
    $out .= '<img width="100px" src="data:image/jpeg;base64,'.base64_encode($im).'"/>';

}

if ($_REQUEST['pdfuri'] == 'true'){
    $db_pdfs = get_db_pdfs();
    $fs_pdfs = get_fs_pdfs();
    $page = 0;
    if (isset($_REQUEST['page'])) $page = $_REQUEST['page'];
    $out = '<div id="page_'.$page.'" class="page_holder">';
    for($i = 0; $i < (RESULTS_PER_PAGE); $i++){
        $pdf = $i + $page * RESULTS_PER_PAGE;
        $pdf_name = $fs_pdfs[$pdf];
        $out .='<div class="pix_holder">';
        $im = new imagick(PDFS_PATH.$fs_pdfs[$pdf].'[0]');
        $im->setImageFormat('jpg');
        $im->setResolution( 75, 75 );
        /*$im->resizeImage(250 ,300, imagick::FILTER_POINT, 1, true);*/
        if (array_key_exists($fs_pdfs[$pdf],$db_pdfs)){
            if ($_REQUEST['nealocate'] == 'false'){
                $out .= '<img class="pix green_border"  width="100px" data-pid="'.$db_pdfs[$pdf_name]['id'].'" data-cu="'.$db_images[$pdf_name]['cu'].'" src="data:image/jpeg;base64,'.base64_encode($im).'">';
                $out .= '<a class="link" target="_blank" href="/product_info.php?products_id='.$db_pdfs[$pdf_name]['id'].'"><i class="fa fa-search"></i></a> ';}
        } else{
            $out .= '<img class="pix red_border" src="data:image/jpeg;base64,'.base64_encode($im).'">';
        }
        $out .= '<a class="delete" href="#"><i class="fa fa-trash-o"></i></a> ';
        $out .= '<div class="overlayer"><span>'.$fs_pdfs[$pdf].'</span></div>';
        $out .='</div>';
    }
    $out .= '<center>'.(($page * RESULTS_PER_PAGE) + 1).'-'.($image + 1).'</center></div>';
    if($_REQUEST['ajax'] == 'true') {

        echo $out;
        die();
    }

}


if ($_REQUEST['imagini'] == 'true'){
    $db_images = get_db_images();
    $fs_images = get_fs_images();
    $page = 0;
    if (isset($_REQUEST['page'])) $page = $_REQUEST['page'];
    $out = '<div id="page_'.$page.'" class="page_holder">';
    for($i = 0; $i < (RESULTS_PER_PAGE); $i++){
        $image = $i + $page * RESULTS_PER_PAGE;
        $img_name = $fs_images[$image];
        $out .='<div class="pix_holder">';
        if (array_key_exists($fs_images[$image],$db_images)){
            if ($_REQUEST['nealocate'] == 'false'){
                $out .= '<img class="pix green_border" data-pid="'.$db_images[$img_name]['id'].'" data-cu="'.$db_images[$img_name]['cu'].'" src="'.IMGS_URL.$img_name.'">';
                $out .= '<a class="link" target="_blank" href="/product_info.php?products_id='.$db_images[$img_name]['id'].'"><i class="fa fa-search"></i></a> ';}
        } else{
            $out .= '<img class="pix red_border" src="'.IMGS_URL.$fs_images[$image].'">';
        }
        $out .= '<a class="delete" href="#"><i class="fa fa-trash-o"></i></a> ';
        $out .= '<div class="overlayer"><span>'.$fs_images[$image].'</span></div>';
        $out .='</div>';
    }
    $out .= '<center>'.(($page * RESULTS_PER_PAGE) + 1).'-'.($image + 1).'</center></div>';
    if($_REQUEST['ajax'] == 'true') {

        echo $out;
        die();
    }

}

//require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
<!doctype html>
<html <?php echo HTML_PARAMS; ?>>
<head>
<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
<style type="text/css">
@import url(http://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700);

@import url(http://fonts.googleapis.com/css?family=Open+Sans:400,600,300);
@charset 'UTF-8';
/* Base Styles */
#cssmenu,
#cssmenu ul,
#cssmenu li,
#cssmenu a {
    margin: 0;
    padding: 0;
    border: 0;
    list-style: none;
    font-weight: normal;
    text-decoration: none;
    line-height: 1;
    font-family: 'Open Sans', sans-serif;
    font-size: 14px;
    position: relative;
}
#cssmenu {
    width: 250px;
    border-bottom: 4px solid #656659;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
}
#cssmenu a {
    line-height: 1.3;
}
#cssmenu > ul > li:first-child {
    background: #66665e;
    background: -moz-linear-gradient(#66665e 0%, #45463d 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #66665e), color-stop(100%, #45463d));
    background: -webkit-linear-gradient(#66665e 0%, #45463d 100%);
    background: linear-gradient(#66665e 0%, #45463d 100%);
    border: 1px solid #45463d;
    -webkit-border-radius: 3px 3px 0 0;
    -moz-border-radius: 3px 3px 0 0;
    border-radius: 3px 3px 0 0;
}
#cssmenu > ul > li:first-child > a {
    padding: 15px 10px;
    background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEQAAAA6CAYAAAAJO/8DAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyRpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoTWFjaW50b3NoKSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDpCNzUxMjRDQzAyMTAxMUUzOEE4MkZFMEIxNzhDQTMwRiIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDpCNzUxMjRDRDAyMTAxMUUzOEE4MkZFMEIxNzhDQTMwRiI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOkU1Q0IzQUY1MDFGQTExRTM4QTgyRkUwQjE3OENBMzBGIiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOkU1Q0IzQUY2MDFGQTExRTM4QTgyRkUwQjE3OENBMzBGIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+ipW+eQAACtZJREFUeNp8m4uO4zoORElRdvddYP//U3cSW9JOA3ZQOV25AwTTcWJZ4rNYZDIivv6+5t9XXn8//77Ov6+6rv/8P/6+2vX6uRbX9X599+fe47p+X9vks59/K97/3euNa81+Xc/rvoZ7uqxzXOvH9d3/Xtf+J9fntcZ5vb5lrXtfTfb+c56s6+J2/f+QB4/rS00e8rxvlAeGCGBdnyUOea/T5fP7O+tSxJLnprxv8sxD3t+Huve5RLG6j4Ezrev9kuffym8FzdwaWHhwFyvZrwPcApoitAVhLHnwEAFOaFIVMEVwASu8NVpQxCl7voVwC7bLoZvst8l+7/e95CAh0gsRwrccqrDQrY37O6eYZcphQ4TxEDdd4pIhfy9sWgU25FAq/HFZUIdVnXD1+yz6/Pscj7ouaLxIWahwoIF4oIst0fguGizRasIaTzH3zQipRBiq2Ts2TNHwhADVJXWParkL1raXSFOle4pG9RBN3rtAGRCk3qvmGcaXu3x+ImimmH+KGxdikltX964KLgi0Qg54yiInbro/f8gDE6a+jIDUN/XaQHDW2DEvd9K40K/3mxygIKwlQbuLQrt8NhEfhwj9XmvUZd4Nkr6tZcohvyU4UvoBYTmLGNhUSeDbZL0uQXHIfhbWTxFQwLKGyTaawRJnu4XcNAYMiQElm0iksBNmnyKghg3clndIrEj8vRmLOQUXqXlr4B2ydpM1B4LtlLOlZE612pchlGSIFBNtxvfWZU11HWCXDNWujSU0WWazJdfTpMsm8WJKip6Cl1Tjm7j6QoDVQKtY5RThhijs1NycSGWJ4JgS2dVCBsAd/ZWbagjCCc1v8iwV9iZrngjWqsiJuBDAVwGwqeDwDZgF3CfhAopFNuOPh2htx1olD26wIpr0Mjjm53l/5F6NN0tc5wSg60CiXZTdxT1fwLNuyZj01UQAYdyhyWLqFgGE2MTcE3HqFtYuwhwQWsk9iVgS8oz7uTu+l1CwxsGG/Z1lrOAOZiegdsLvuwlMExscgh2WQZmJzTdYkQKzJoclolWky3gVcH+CSlVmK0R8jc6aehv8euBAd6BNg0wf+H5DrDrkwBN+3QyYS4k1TYQ+zRp0vfNS9jAQvt3V7mYe9hBpv2AtgJYCm0P80AG0ZcryQOod8H+tvocJjENNXVw05fsbyo6G5xPEzQL8nahpJoJYCHIMmLPWPPkvrw3ItMN6tLAr1B1aq3wBZf/BHhaEvJBRd3HLW+irBFsENpPwS80AByRNaM5qVa+dqDpP+HgZC2OM6SgddlEeP9tEcSGu9pT9vKzNcQS39A/UDyk3UrNd0i4r6Ae0m4gLE/4/gF8CWaSgeY0ZHcJuhmvZJN5pxuxKAxZQY0ErE4Gyof7QXD/ApO3GBQPxolDGd4OLAshYQeKQvXaANSLuA5akHM8bkbJkk38MYk2kzfGBM03DmQ5Uowva7XA35Va14HteB2L94u5veM6A9WgMvC1mFKA2byAomyLV09QH6uNDNrIjjiSs6kAKpzvo8xqwxAJZvUxiKFNC3BnmUCSbYN0TubwZoLWQTQi+wlST/QNdEBDOU6L/gZS5YX3SDiSS05BVZPGXWPB/bgv5wsIs0BJYIq+Nb/DRB7TawLQvWIL7NwHtQ0ijA1WyE8SE1SjwLAh0GiT+LGxGi7BhBHVcwlB2LQ20JxP3BBhbMPvDgD6tfxRVJtoJT4H15Gg2+e5Cf0fj5ktpWtyVbOqQuiFNxGYRmCjIGuqFRFAbkqWmuG0IPzPQBEuTMU6kf41XZWKhJolpwGSVYaoXQFkDxZ9Ad80w6d1YRIL83ZAyExlFOY4OIS/jWglXL5De06DyAv24Ugjch1jLExYQ/+L3Add6SlyaEHQzzbAlml0fWpWBeugECEt04jakWlKbEy3MYn81oL1C5lHtL1NSs15Ic5iGa4kgq+lbu3XdVKaaMofJLgNoOMQFA6xb06xWgOcTaHFAK+RDh6DRECwyTK9E66Admpt45jL8RSB9E11PUJDxgZkfBkDewjsKzNEyTeAHWhKF1mGgl9tMi1BTZDfkcOHebpi1Dng+wYUoHXqiaCRQ66ZflNq56/DNBRaqiXZDKsQdtc4wNGGYmkGtIkAnLAOvCx3FhTbEP/J5gvE/TF+mmTJhBAjju2l9Im1x9mPhkNrHIXvOUn8A8JVYSUMPphkcpG0DxSj/SAnA2mxJA6tBcCroUsYsUVk2pNcmFW6K5Fk7aN2hwGqgDNgAlKZJ+c2U9gvZLPG8AD2gQXoadFr4+41kLkT2MAwaq9TA9xvS3gQlOGG6AZMm0h0SgEkYTVjNE5ThlOZaE+sJQ1zdcS0bAo82iTZE4yFpNyVAlmlaBYkXw4wtQ/xM0eoBwpnjDhvS+wZrScMXvwVQ05DrBVOMDz3PAZ/fsEFC5cT3/wBIFfjSQJN9B6ye6LgttC/v/TwRE8OQVgUBa/z71bljC1BT8T9SKgd4ytOMP6Rh0+IDmR0oxrqhHwoWOsVKE400df8N3bpl+JbXvjmjob68m8GZYTiMwPyWMmuB5nUKylyoe8hvTMQaTfth6hUKME2p4GKSKnfe1e4O3LFMhzxM8F0C0NLQiIXg9TAWQItpoA61McWhvGXKBkWjHVnqC0N5hep7sSxWs9MgtWNGlGMMidjQTOthM2hxAvkGOokEZgtrLtNgDxSJBYrxlOzzi1aoizpzze6JtuYyZJKSQKrNAxlG0WEZ4rcDVA1DXU5DXwZm0xYsokTwnAw43DBgxfukMgmhaaJ8ASWydkhpHg2Qw8QbBWaeEwMdgVhroISbHLD0CUDImBJoaL1Y9/rQaCZp1CE48hvfEjC7BMFmAvcJtNoMydM/tEd31FrMRNrDJVnUUI0zJmUBH7yhNkz9nIgBbA4FSuxlOIkwQ3sNQ3iKKg/TPjjR5+F0QsMrDOlFMv3V+CpUmu3DaGVA4uQkOKRHFi2MdZSZBOB68aHGIceyTB12dwJ2UJMheKehZ1SFEn6ZG4bpixCJTkwAkehZhvThjwc6SCZOQLLJtcD+65xbgF+ZCKgc43oF5TR9mY66Y4DPXKbnuhCtD4OAJ4DRRKw4YQEDYG+ZRlrE71nUBstJuPyAdb7xsYXUtbCZxKbiA+DSyWP2QxL94EQAnLiPtZOy7N+mj+xaoA2lP90xYIUvKyqw3QRGRH35oQjUvN+AKQKd/w3BtZvpwoQbhrFIxqiFAyaC6ikxLGEhb5r+it+zpY7t0tKaMyKqEf0NyzAjTqcZzjvRp6kPIIyIdYNF1welTaRYsu6pxV2ZQmyY+Y0TGmDhdiIVcrKxDHcxDNdasIwwfSH+2KCBbEpJtTvOtTBGobP1WYj8BGCcE+2GPUvDeU6DSqcZ33RmrQfhgG77wJYV1mmIJU0GCRvwio5DzEJsKDO2kEilzQRc/a3NgVgSH0x+h/YJ8dNA/omu4IR7FdyUPE2ZmbW3HyaVaKLQnyn4HnujgXhyAqmyt0LGfhpmvH+Y7dCh3wTG4C+sOLTHpDHj9w+bltYy3x/QpZsxC/AfSgRvZnCuIz6wzjikPjlxuIDgFWoXqIAJomnE+8j5RKnfzThX3Eh1mpEqPcwpxEqYlkLD7EYBqTZMDhCQbWaebRqM4X6Pq/VRR60yzaSRClhJsalZ5guZoWCibsqY/Y0Rv3/cFwatPk0vJg05NEz9wxn2NC0LZrYdY5nbhy7lq2X6fwEGANmWyP4QH7n6AAAAAElFTkSuQmCC) top left repeat;
    border: none;
    border-top: 1px solid #818176;
    -webkit-border-radius: 3px 3px 0 0;
    -moz-border-radius: 3px 3px 0 0;
    border-radius: 3px 3px 0 0;
    font-family: 'Ubuntu', sans-serif;
    text-align: center;
    font-size: 18px;
    font-weight: 300;
    text-shadow: 0 -1px 1px #000000;
}
#cssmenu > ul > li:first-child > a > span {
    padding: 0;
}
#cssmenu > ul > li:first-child:hover {
    background: #66665e;
    background: -moz-linear-gradient(#66665e 0%, #45463d 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #66665e), color-stop(100%, #45463d));
    background: -webkit-linear-gradient(#66665e 0%, #45463d 100%);
    background: linear-gradient(#66665e 0%, #45463d 100%);
}
#cssmenu > ul > li {
    background: #e94f31;
    background: -moz-linear-gradient(#e94f31 0%, #d13516 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #e94f31), color-stop(100%, #d13516));
    background: -webkit-linear-gradient(#e94f31 0%, #d13516 100%);
    background: linear-gradient(#e94f31 0%, #d13516 100%);
}
#cssmenu > ul > li:hover {
    background: #e84323;
    background: -moz-linear-gradient(#e84323 0%, #c33115 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #e84323), color-stop(100%, #c33115));
    background: -webkit-linear-gradient(#e84323 0%, #c33115 100%);
    background: linear-gradient(#e84323 0%, #c33115 100%);
}
#cssmenu > ul > li > a {
    font-size: 14px;
    display: block;
    background: url() top left repeat;
    color: #ffffff;
    border: 1px solid #ba2f14;
    border-top: none;
    text-shadow: 0 -1px 1px #751d0c;
}
#cssmenu > ul > li > a > span {
    display: block;
    padding: 12px 10px;
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    border-radius: 4px;
}
#cssmenu > ul > li > a:hover {
    text-decoration: none;
}
#cssmenu > ul > li.active {
    border-bottom: none;
}
#cssmenu > ul > li.has-sub > a span {
    background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABEAAAARCAYAAAA7bUf6AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyRpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoTWFjaW50b3NoKSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDpDQUU5RjU5Q0Y3MEYxMUUyQTU5MEYxOERFNDRBOUMyMyIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDpCRjc3RDBDOEY3NDkxMUUyQTU5MEYxOERFNDRBOUMyMyI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOkNBRTlGNTlBRjcwRjExRTJBNTkwRjE4REU0NEE5QzIzIiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOkNBRTlGNTlCRjcwRjExRTJBNTkwRjE4REU0NEE5QzIzIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+vpRZ+gAAARNJREFUeNpiYEAD////jwfi/f8xAUgsngEfACqQB+Lz/wmAnz9/XgGpxWXA+/9Egt+/f38+ffq0Oroh5/+TCN6/f38XqJUTOQywgtbW1koQxiW/efPmdpgh+3EpAkr7ArEwLvkXL15cAsrLMuBzMtQQQmqsWNADua2trQpL5DkAxa1AjKqqqjY0OWEGXF5AwgjFWLwGUs+CJbo3wdiMQADzDr40xgTEB/DI++LT/PLly8swQxYwkAkOHjy4F0i9BXOgSZmcxOYLjmJYsgclZWIN+PXr15eAgIBkoFZ7FKeB8gLIdGJcADXAA57s0QAnKCmDUiK21Lly5cp+qBfskQ1gxBFmslAsjCYOCsTHUAwHAAEGAI2GUTLIEcdHAAAAAElFTkSuQmCC) 96% center no-repeat;
}
#cssmenu > ul > li.has-sub.active > a span {
    background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABEAAAARCAYAAAA7bUf6AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyRpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoTWFjaW50b3NoKSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDpCRjc3RDBDQkY3NDkxMUUyQTU5MEYxOERFNDRBOUMyMyIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDpCRjc3RDBDQ0Y3NDkxMUUyQTU5MEYxOERFNDRBOUMyMyI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOkJGNzdEMEM5Rjc0OTExRTJBNTkwRjE4REU0NEE5QzIzIiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOkJGNzdEMENBRjc0OTExRTJBNTkwRjE4REU0NEE5QzIzIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+M6dsaAAAAPhJREFUeNpi+P//PwMajgfi/f8xwX6oHIp6MEASkAfi8/8JgF+/fl2GqsUwBCT4/j+R4M+fP5/u3Lmjgm7I+f8kgi9fvtwAameDGRL/n0xw4sSJSpgh+8k15P3792eARggz/od7jDzAyMiojmHIggUL0vBpSkhImIVmiAmGISBBIHULlyFA5Z8IGkKGd0yoEbDGTKBgINcVFy5c2AykPoMTGzQpkwQ+f/58E+QKUBTDkz0oKZOS7DMyMnyABmiiZEBQXgAlZWJcADXAADnZI2drNlBSBgUYtkDcv39/PdQLIBewwSKWEUeYCUMxL5r4ZyB+C8VwABBgAE6vkCkkGTdKAAAAAElFTkSuQmCC) 96% center no-repeat;
}
/* Sub menu */
#cssmenu ul ul {
    display: none;
    background: #fff;
    border-right: 1px solid #a2a194;
    border-left: 1px solid #a2a194;
}
#cssmenu ul ul li {
    padding: 0;
    border-bottom: 1px solid #d4d4d4;
    border-top: none;
    background: #f7f7f7;
    background: -moz-linear-gradient(#f7f7f7 0%, #ececec 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #f7f7f7), color-stop(100%, #ececec));
    background: -webkit-linear-gradient(#f7f7f7 0%, #ececec 100%);
    background: linear-gradient(#f7f7f7 0%, #ececec 100%);
}
#cssmenu ul ul li:last-child {
    border-bottom: none;
}
#cssmenu ul ul a {
    padding: 10px 10px 10px 25px;
    display: block;
    color: #676767;
    font-size: 12px;
    font-weight: normal;
}
#cssmenu ul ul a:before {
    content: '»';
    position: absolute;
    left: 10px;
    color: #e94f31;
}
#cssmenu ul ul a:hover {
    color: #e94f31;
}
#wrap {
    width: 1350px;
}
#sidebar {
    width: 250px;
    position: fixed;
}
#main{
    width: 1085px;
    float: right;
}
.pix{
    width: 100px;
    margin: 2px;
}
.red_border {
    border: 2px solid red;
}
.green_border {
    border: 2px solid green;
}
#cssmenu{margin-bottom: 20px;}
.pix_holder{
    display: block;
    position: relative;
    float: left;
    z-index: 0;

}
.overlayer {


}
.pix_holder span{
    position: absolute;

    left: 4px;
    background: rgba(0,0,0,0.4);
    bottom: 11px;
    display: inline;
    font-family: ubuntu;
    font-size: 10px;
    color: white;
    width: 100px;
    text-align: center;
    visibility: hidden;

}
.pix_holder a{
    position: absolute;
    background: rgba(0,0,0,0.4);
    display: inline;
    color: white;
    /* width: 100px; */
    padding: 2px;
    /* text-align: center; */
    cursor: pointer;
    visibility: hidden;
}
.delete {
    right: 4px;
    top: 4px;
}
.link{
    left: 4px;
    top: 4px;
}
.pix_holder:hover span,.pix_holder:hover a{
    visibility: visible;
}

.page_holder {min-height: 1150px;}
</style>

</head>
    <body>
        <div id="wrap">
            <div id="sidebar">
                <div id='cssmenu'>
                    <ul>
                        <li class='active'><a href='#'><span>RESURSE</span></a></li>
                        <li class='has-sub'><a href='#'><span>Imagini</span></a>
                            <ul>
                                <li><a href='<?=$_SERVER["SCRIPT_NAME"]?>?imagini=true&nealocate=false'><span>Toate</span></a></li>
                                <li class='last'><a href='<?=$_SERVER["SCRIPT_NAME"]?>?imagini=true&nealocate=true'><span>Doar imaginile orfane</span></a></li>
                            </ul>
                        </li>
                        <li class='has-sub last'><a href='#'><span>PDF-uri</span></a>
                            <ul>
                                <li><a href='<?=$_SERVER["SCRIPT_NAME"]?>?pdfuri=true&nealocate=false'><span>Toate</span></a></li>
                                <li class='last'><a href='<?=$_SERVER["SCRIPT_NAME"]?>?pdfuri=true&nealocate=true'><span>Doar pdf-urile orfane</span></a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div id="infobox">
                    <img src="http://placehold.it/250x300">
                    <p id="desc">descriere</p>
                </div>
                <div id="nav">
                    <!--<div class="nav_down"><a href="#">pagina <?/*=($page > 10)?$page-10:0*/?></a></div>
                    <div class="nav_up"><a href="#">pagina <?/*=$page+10*/?></a></div>-->
                </div>
            </div>
            <div id="main">
                <div id="postswrapper">
                    <?php echo $out; ?>
                    <?php /*include('resurse/loadmore.php'); */?>

                </div>
                <div id="loadmoreajaxloader" style="display:none;"><center><img src="resurse/ajax-loader.gif" /></center></div>

            </div>
        </div>
        <script src='http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'></script>
        <script type="text/javascript">
            $( document ).ready(function() {
                $('#cssmenu > ul > li > a').click(function() {
                    $('#cssmenu li').removeClass('active');
                    $(this).closest('li').addClass('active');
                    var checkElement = $(this).next();
                    if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
                        $(this).closest('li').removeClass('active');
                        checkElement.slideUp('normal');
                    }
                    if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
                        $('#cssmenu ul ul:visible').slideUp('normal');
                        checkElement.slideDown('normal');
                    }
                    if($(this).closest('li').find('ul').children().length == 0) {
                        return true;
                    } else {
                        return false;
                    }
                });
            });
        </script>
        <script type="text/javascript">
            var page = 1;
            var fired = Date.now();
            $(window).scroll(function(){
                var now = Date.now();
                if(((now - fired) > 300)&&($(window).scrollTop() == $(document).height() - $(window).height())){
                    fired = now;
                    $('div#loadmoreajaxloader').show();
                    $.ajax({
                        url: "<?=$_SERVER['SCRIPT_NAME']?>?imagini=true&ajax=true&nealocate=false&page="+page++,
                        success: function(html){
                            if(html){
                                $("#postswrapper").append(html);
                                $('.page_holder:last .pix_holder').mouseenter(function(){
                                    var src = $(this).children().attr("src");
                                    $('#infobox img').attr("src",src);
                                    var pid = $(this).children().data('pid');
                                    var cu = $(this).children().data('cu');
                                    $('#desc').text('ID:'+pid+','+'CU:'+cu);

                                });
                                $('.page_holder:last .delete').on('click',function(){
                                    var url  = $(this).parent().find('img').attr('src');

                                    var name = url.substr(36);
                                    var yes = confirm('Sterg '+name+' ?');
                                    if (yes) {
                                        $(this).parent().find('img').attr('src','http://placehold.it/104&text=Sters!');
                                        $(this).parent().find('a').remove();
                                        $(this).parent().find('div').remove();
                                        $.ajax({
                                            url: "<?=$_SERVER['SCRIPT_NAME']?>?DELETE=true&ajax=true&name="+name,
                                            success: function(html){
                                                if(html){
                                                    if (html.indexOf('success') == -1) alert('Operatie esuata!');

                                                }else{
                                                    alert('Esec: Raspuns 0 bytes');
                                                }
                                            }
                                        });
                                    }
                                    return false;
                                });
                                var count = $('.page_holder').length;
                                    if (count > 10){
                                        var toRemove = count - 10;
                                        $('.page_holder').each(function(index){
                                            if(index < toRemove) $(this).remove();
                                        })
                                };
                                $('div#loadmoreajaxloader').hide();
                            }else{
                                $('div#loadmoreajaxloader').html('<center>Nu mai sunt rezultate.</center>');
                            }
                        }
                    });
                }
            });

            $('.pix_holder').mouseenter(function(){
                var src = $(this).children().attr("src");
                $('#infobox img').attr("src",src);
                var pid = $(this).children().data('pid');
                var cu = $(this).children().data('cu');
                $('#desc').text('ID:'+pid+','+'CU:'+cu);

            })
            $('.delete').on('click',function(){
                var url  = $(this).parent().find('img').attr('src');

                var name = url.substr(36);
                var yes = confirm('Sterg '+name+' ?');
                if (yes) {
                    $(this).parent().find('img').attr('src','http://placehold.it/104&text=Sters!');
                    $(this).parent().find('a').remove();
                    $(this).parent().find('div').remove();
                    $.ajax({
                        url: "<?=$_SERVER['SCRIPT_NAME']?>?DELETE=true&ajax=true&name="+name,
                        success: function(html){
                            if(html){
                                if (html.indexOf('success') == -1) alert('Operatie esuata!');

                            }else{
                                alert('Esec: Raspuns 0 bytes');
                            }
                        }
                    });
                }
                return false;
            })
        </script>

    </body>
</html>