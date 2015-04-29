<?php
/**
 * Created by PhpStorm.
 * User: ovidiu
 * Date: 2/26/14
 * Time: 10:18 PM
 */


require('includes/configure.php');
require(DIR_WS_INCLUDES.'filenames.php');


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

function db_query($query, $limit = 15){
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


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>toolszone manufacturers scroll </title>

    <!--<link rel="stylesheet" href="../lib/global-en.css" type="text/css" media="screen" />-->
    <!--logo-scroll,start-->
    <link rel="Stylesheet" type="text/css" href="/lib/image-scroller/scroll.css" />
    <script type="text/javascript" src="/lib/image-scroller/jquery-1.js"></script>
    <script type="text/javascript" src="/lib/image-scroller/jquery.imageScroller.js"></script>
    <script type="text/javascript" src="/lib/image-scroller/test.js"></script>
    <!--logo-scroll,end-->
</head>

<body><!-- style="margin-top:25px; background:none;"-->
<!--PARTNER logo scroll, start-->
<div class="scroll-logo-bg">
    <!--cat 1 channel, logo scroll, start-->
    <div id='left'>
        <?php
        $mysqli = new mysqli(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD, DB_DATABASE);

        if ($mysqli->connect_errno) {
            printf("Connect failed: %s\n", $mysqli->connect_error);
            exit();
        }
        $query = db_query("SELECT manufacturers_image, manufacturers_name,manufacturers_domain FROM manufacturers where featured=true ORDER BY RAND()", 40);

        while ($brands = db_fetch_array($query))  {
            $img = file_get_contents('./lib/image-scroller/brands/'.$brands['manufacturers_image']);
            echo '<img src="data:image/jpeg;base64,'.base64_encode($img).'" alt="'.$brands['manufacturers_name'].', '.$brands['manufacturers_domain'].'" />';
        }

        ?>
    </div>
    <!--cat 1 channel, logo scroll, end-->
</div>
<!--PARTNER logo scroll, end-->
</body>
</html>