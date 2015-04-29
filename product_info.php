<?php

/*

  $Id: product_info.php 1739 2007-12-20 00:52:16Z hpdl $



  osCommerce, Open Source E-Commerce Solutions

  http://www.oscommerce.com



  Copyright (c) 2003 osCommerce



  Released under the GNU General Public License

*/

$descSimb = array(

    '',

    'PROMOTIE',

    'IN LIMITA STOCULUI',

    'CU PRECOMANDA',

    'SOLD',

    'NOU',

    'LIVRARE IN 24 H',

    'LIVRARE IN 72 H',

    'LIVRARE IN 48 H',

    'LIVRARE IN 7 ZILE',

    'LIVRARE IN 14 ZILE',

    'LIVRARE IN 30 ZILE',

    'INDISPONIBIL IN STOC',

    'PRODUS NERETURNABIL');


require_once('phpThumb/phpThumb.config.php');
require('includes/application_top.php');
require('page_header.php');


require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO);



$product_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$_GET['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");

$product_check = tep_db_fetch_array($product_check_query);



$product_info_query = tep_db_query("select p.products_image_2,p.products_image_3,p.products_image_4, p.products_pdf, p.nr_articol, p.products_id, p.products_specials, pd.products_name, pd.products_seo_desc, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url,p.products_youtube, p.products_price, products_price_2, products_price_3, cant_pret_2, cant_pret_3, products_um, is_set, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id, tm.manufacturers_name, tm.manufacturers_image from " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " tm on p.manufacturers_id=tm.manufacturers_id, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$_GET['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");

$product_info = tep_db_fetch_array($product_info_query);

//$product_meta_query =  tep_db_query("select products_seo_desc from ". TABLE_PRODUCTS_DESCRIPTION . " WHERE products_id = '" . (int)$_GET['products_id']."'");
//$seo_desc = implode(tep_db_fetch_array($product_meta_query));

insertHeader($product_info['products_name']." - ".TITLE,$product_info['products_seo_desc']);
?>


    <script>

        function zoom(id){
            alert(id);
//	window.open('http://www.toolszone.ro/zoom.php?id='+id,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=100,height=100,screenX=150,screenY=150,top=150,left=150')

        }

        function popupWindow(url) {

            window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=100,height=100,screenX=150,screenY=150,top=150,left=150')

        }

        function printWindow(){

            bV = parseInt(navigator.appVersion)

            if (bV >= 4) window.print()

        }

        function changeTax(size, cant1, cant2){



            if(!cant1) cant1=1;

            if(!cant2) cant2=1;



            var tax = true;



            if(document.getElementById("tax").value == "0")

                tax=false;



            for(var i=1; i<size; i++){



                buff = document.getElementById("price_notax_"+i).innerHTML;

                document.getElementById("price_notax_"+i).innerHTML = document.getElementById("price_"+i).innerHTML;

                document.getElementById("price_"+i).innerHTML = buff;



                buff = document.getElementById("price_2_notax_"+i).innerHTML;

                document.getElementById("price_2_notax_"+i).innerHTML = document.getElementById("price_2_"+i).innerHTML;

                document.getElementById("price_2_"+i).innerHTML = buff;



                buff = document.getElementById("price_3_notax_"+i).innerHTML;

                document.getElementById("price_3_notax_"+i).innerHTML = document.getElementById("price_3_"+i).innerHTML;

                document.getElementById("price_3_"+i).innerHTML = buff;



                if(tax){

                    document.getElementById("tax").value = "0";



                    document.getElementById("tax_button").innerHTML = "Afiseaza preturi cu TVA";



                    document.getElementById("th_price").innerHTML = "Pret fara TVA";

                    document.getElementById("th_price_2").innerHTML = "Pret fara TVA<br> &gt; "+cant1+" buc/1buc";

                    document.getElementById("th_price_3").innerHTML = "Pret fara TVA<br> &gt; "+cant2+" buc/1buc";





                }else{

                    document.getElementById("tax").value = "1";



                    document.getElementById("tax_button").innerHTML = "Afiseaza preturi fara TVA";



                    document.getElementById("th_price").innerHTML = "Pret cu TVA";

                    document.getElementById("th_price_2").innerHTML = "Pret cu TVA<br> &gt; "+cant1+" buc/1buc";

                    document.getElementById("th_price_3").innerHTML = "Pret cu TVA<br> &gt; "+cant2+" buc/1buc";



                }



            }

        }

</script>
<?
require(DIR_WS_INCLUDES . 'header.php');
?>

<?php

    if ($product_check['total'] < 1) {

        ?>

        <?php new infoBox(array(array('text' => TEXT_PRODUCT_NOT_FOUND))); ?>

        <?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?>

        <?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' .
        tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?>

    <?php

    } else {

    $product_info_query = tep_db_query("select p.products_image_2,p.products_image_3,p.products_image_4, p.products_pdf, p.nr_articol, p.products_specials, p.products_id, pd.products_name, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url,p.products_youtube, p.products_price, products_price_2, products_price_3, cant_pret_2, cant_pret_3, products_um, is_set, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id, tm.manufacturers_name, tm.manufacturers_image from " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " tm on p.manufacturers_id=tm.manufacturers_id, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$_GET['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");

    $product_info = tep_db_fetch_array($product_info_query);



    tep_db_query("update " . TABLE_PRODUCTS_DESCRIPTION . " set products_viewed = products_viewed+1 where products_id = '" . (real)$_GET['products_id'] . "' and language_id = '" . (int)$languages_id . "'");



    if ($new_price = tep_get_products_special_price($product_info['products_id'])) {

        $products_price = '<s>' . $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($new_price, tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span>';

    } else {

        $products_price = $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id']));

    }



    if (tep_not_null($product_info['products_model'])) {

        $products_name = $product_info['products_name'] . '<br><span class="smallText">[' . $product_info['products_model'] . ']</span>';

    } else {

        $products_name = $product_info['products_name'];

    }

    /*cautam poze in $product_info['products_description']*/
    function cb($matches) {
        return $matches[1].strtoupper($matches[2][0]).'/'.$matches[2];
    };
    $hay = $product_info['products_description'];
    $parsedForUrls = preg_replace_callback("|(http://www.toolszone.ro/poze/)([0-9a-zA-Z\-_]+)|","cb", $product_info['products_description']);
    ?>
<div class="container">
    <div class="row">
        <div class="col-md-3 padding-right">
            <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
        </div>
        <div class="col-md-9 pzero mainbox">
            <section id="main" class="">
                    <header>
                        breadcrumbs
                    </header>
                    <article>
                        <div class="row">
                            <div class="col-md-7 text">
                                <div class="title"> <h1><?php echo $products_name; ?></h1> </div>
                                <div class="simboluri">
                                    <? if($product_info['products_specials'] != ""){
                                        $specials = explode(",",$product_info['products_specials']);
                                        for($i=0; $i<sizeof($specials);$i++){?>
                                            <img src="<?= DIR_WS_IMAGES . "icons/" . $specials[$i] . ".png" ?>" alt="<?=$descSimb[(int)$specials[$i]]?>" title="<?=$descSimb[(int)$specials[$i]]?>"/>&nbsp;
                                        <?}
                                    }?>
                                </div>
                                <div class="">
                                    <h2>Numar articol: <span><?=$product_info['nr_articol']?></script></span></h2>
                                </div>
                                <div class="">
                                    <span class="fa-stack fa-lg star">
                                        <i class="fa fa-square fa-stack-2x"></i>
                                        <i class="fa fa-star fa-stack-1x"></i>
                                    </span>
                                    <span class="fa-stack fa-lg star">
                                        <i class="fa fa-square fa-stack-2x"></i>
                                        <i class="fa fa-star fa-stack-1x"></i>
                                    </span>
                                    <span class="fa-stack fa-lg star">
                                        <i class="fa fa-square fa-stack-2x"></i>
                                        <i class="fa fa-star fa-stack-1x"></i>
                                    </span>
                                    <span class="fa-stack fa-lg star-g">
                                        <i class="fa fa-square fa-stack-2x"></i>
                                        <i class="fa fa-star fa-stack-1x"></i>
                                    </span>
                                    <span class="fa-stack fa-lg star-g">
                                        <i class="fa fa-square fa-stack-2x"></i>
                                        <i class="fa fa-star fa-stack-1x"></i>
                                    </span>
                                    <span class="nota">3.0</span>
                                    <span class="recenzii"> (1 recenzie)</span>
                                </div>
                                <div class="">
                                    <span class="linkReviews">
                                        <a href="#">Citeste review</a> |
                                        <a href="#">Scrie review</a>
                                    </span>
                                </div>
                                <div class="">
                                    Facebook | Tweet | Pin it | Google +
                                </div>
                                <div class="">
                                    <h2>Descriere produs:</h2>
                                    <?php echo stripslashes($parsedForUrls); ?>
                                </div>
                            </div>
                            <?php
                            if (tep_not_null($product_info['products_image'])) {

                                //$spDir = strtoupper($product_info['products_image'][0]).'/';

                                function spDir($imageName){
                                    return strtoupper($imageName[0]).'/';
                                }



                                //echo DIR_WS_IMAGES . 'mari/'. $spDir . $product_info['products_image'];

                                function getImageUri($fisier,$mare = true){
                                    //echo $fisier.'<br>';
                                    $params = ($mare)?'&w=213&h=213&far=C&fltr[]=wmi|/images/waterMark.png|165x195|25|':'&w=50&h=50&far=C';
                                    $spDir = strtoupper($fisier[0]).'/';
                                    //var_dump(DIR_WS_IMAGES . 'mici/'. $spDir . $fisier);
                                    if (!file_exists(DIR_WS_IMAGES . 'mici/'. $spDir . $fisier)) set_thumb($fisier,DIR_POZE.$spDir, DIR_WS_IMAGES."mici/".$spDir, 90, 50,50);
                                    if (!file_exists(DIR_WS_IMAGES . 'mari/'. $spDir . $fisier)) set_thumb($fisier,DIR_POZE.$spDir, DIR_WS_IMAGES."mari/".$spDir, 90, 200,200);
                                    //echo var_dump(file_exists(DIR_POZE.$spDir.$fisier));
                                    if (!$mare){
                                        if (file_exists(DIR_WS_IMAGES . 'mici/'. $spDir . $fisier)){
                                            return DIR_WS_IMAGES . 'mici/'. $spDir . $fisier;
                                        }
                                    }
                                    if (file_exists(DIR_POZE.$spDir.$fisier) && !preg_match('/\s/',$fisier)){
                                        return DIR_WS_IMAGES . 'mari/'. $spDir . $fisier;
                                        //return  htmlspecialchars(phpThumbURL('src=/'.DIR_POZE.$spDir.$fisier.$params, '/phpThumb/phpThumb.php'));
                                    } elseif(file_exists(DIR_WS_IMAGES . 'mari/'. $spDir . $fisier)) {
                                        return DIR_WS_IMAGES . 'mari/'. $spDir . $fisier;
                                    } else {
                                        return ($mare)?DIR_WS_IMAGES."default_mare.gif":DIR_WS_IMAGES."default_mica.gif";
                                    }

                                }

                                //$image_1_mare = file_exists(DIR_WS_IMAGES . 'mari/'. $spDir . $product_info['products_image'])?(DIR_WS_IMAGES . 'mari/'. $spDir . $product_info['products_image']): DIR_WS_IMAGES."default_mare.gif";

                                $image_1_mare = getImageUri($product_info['products_image']);

                                //$image_1_mica = file_exists(DIR_WS_IMAGES . 'mici/'. spDir($product_info['products_image']) . $product_info['products_image'])?(DIR_WS_IMAGES . 'mici/'. spDir($product_info['products_image']) . $product_info['products_image']): DIR_WS_IMAGES."default.gif";

                                $image_1_mica = getImageUri($product_info['products_image'],false);


                                $image_2_mare = getImageUri($product_info['products_image_2']);

                                //echo DIR_WS_IMAGES . 'mici/'. $spDir . $product_info['products_image_2'];

                                $image_2_mica = getImageUri($product_info['products_image_2'],false);

                                $image_3_mare = getImageUri($product_info['products_image_3']);

                                $image_3_mica = getImageUri($product_info['products_image_3'],false);

                                $image_4_mare = getImageUri($product_info['products_image_4']);

                                $image_4_mica = getImageUri($product_info['products_image_4'],false);

                                $openWindow = "window.open('http://www.toolszone.ro/zoom.php?id=".((int)$_GET['products_id'])."','popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=430,height=690,screenX=50,screenY=50,top=50,left=50')";

                                ?>

                                <div class="simpleLens-gallery-container col-md-5" id="main-pic">
                                    <div class="simpleLens-container">
                                        <div class="simpleLens-big-image-container">
                                            <span class="brand"><?php echo tep_image(DIR_WS_IMAGES_MANUFACTURERES . $product_info['manufacturers_image'], $product_info['manufacturers_name'], 150, 50) ?></span>
                                            <a class="simpleLens-lens-image" data-lens-image="/lib/phpThumb/phpThumb.php?src=/poze/615318.jpg&amp;w=1000&amp;h=1000&amp;far=C" data-bindattr-1628="1628">
                                                <!--<img class="simpleLens-big-image">-->
                                                <?php echo '<img id="image"  onclick="'.$openWindow.'" src="' . $image_1_mare .'" title="'. $product_info['products_name'] .'" width="225" height="225" />'; ?>
                                            </a>
                                            <span class="mareste"><i class="fa fa-search-plus"></i> mareste imaginea</span>
                                        </div>
                                    </div>

                                    <div class="simpleLens-thumbnails-container">
                                        <a onclick="document.getElementById('image').src='<?=$image_1_mare?>'" ><?='<img id="image" border="0px" src="' . $image_1_mica .'" title="'. $product_info['products_name'] .'" width="50" height="50" />'?></a>&nbsp;
                                        <?php
                                        if (tep_not_null($product_info['products_image_2'])) {
                                            ?>
                                            <a onclick="document.getElementById('image').src='<?=$image_2_mare?>'" ><?='<img id="image"  src="' . $image_2_mica .'" title="'. $product_info['products_name'] .'" width="50" height="50" />'?></a>&nbsp;
                                        <?php
                                        }
                                        if (tep_not_null($product_info['products_image_3'])) {
                                            ?>
                                            <a onclick="document.getElementById('image').src='<?=$image_3_mare?>'" ><?='<img id="image"  src="' . $image_3_mica .'" title="'. $product_info['products_name'] .'" width="50" height="50" />'?></a>&nbsp;
                                        <?php
                                        }
                                        if (tep_not_null($product_info['products_image_4'])) {
                                            ?>
                                            <a onclick="document.getElementById('image').src='<?=$image_4_mare?>'" ><?='<img id="image"  src="' . $image_4_mica .'" title="'. $product_info['products_name'] .'" width="50" height="50" />'?></a>&nbsp;
                                        <?php
                                        }
                                        ?>
                                        <!--<a href="#" class="simpleLens-thumbnail-wrapper" data-lens-image="/lib/phpThumb/phpThumb.php?src=/poze/615318.jpg&amp;w=1000&amp;h=1000&amp;far=C" data-bindattr-1631="1631" data-big-image="/lib/phpThumb/phpThumb.php?src=/poze/615318.jpg&amp;w=520&amp;h=520&amp;far=C"></a>-->
                                    </div>
                                </div>


                                <?php




                            }

                            ?>

                        </div>
                    </article>
                    <footer>

                    </footer>
                </section>


    <?php echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . 'action=add_product')); ?>



    <?php

    $products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$_GET['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "'");

    $products_attributes = tep_db_fetch_array($products_attributes_query);

    if ($products_attributes['total'] > 0) {



        $products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$_GET['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "' order by popt.products_options_name");

        while ($products_options_name = tep_db_fetch_array($products_options_name_query)) {



            $products_options_array = array();


            // am codat in zecimala pa.options_values_id fiecare articol non-unic!
            $products_options_query = tep_db_query("select pa.options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix, pa.options_values_price_2 , pa.price_prefix_2 , pa.options_values_price_3 , pa.options_values_price_special , pa.price_prefix_3 , pa.price_prefix_special, pa.cod_unic, pa.um, pa.car_teh_1 , pa.car_teh_2, pa.products_attributes_id from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . (int)$_GET['products_id'] . "' and pa.options_id = '" . (int)$products_options_name['products_options_id'] . "' and FLOOR(pa.options_values_id) = pov.products_options_values_id and pov.language_id = '" . (int)$languages_id . "' order by um DESC, products_attributes_id ASC");
            //print_r("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix, pa.options_values_price_2 , pa.price_prefix_2 , pa.options_values_price_3 , pa.options_values_price_special , pa.price_prefix_3 , pa.price_prefix_special, pa.cod_unic, pa.um, pa.car_teh_1 , pa.car_teh_2, pa.products_attributes_id from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . (int)$_GET['products_id'] . "' and pa.options_id = '" . (int)$products_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . (int)$languages_id . "' order by um DESC, products_attributes_id ASC");
            $i=1;



            $products_options_header[0] = array(	'text'      => $product_info['is_set']?"Nr. componente":($products_options_name['products_options_name']."<br>"),

                'price' 	=> "Pret cu TVA",

                'is_set' 	=> $product_info['is_set'],

                'um' 		=> $product_info['products_um'],

                'price_2' 	=> "Pret cu TVA<br> &gt; ".$product_info['cant_pret_2']." buc/1buc",

                'price_3' 	=> "Pret cu TVA<br> &gt; ".$product_info['cant_pret_3']." buc/1buc",

                'cod_unic' 	=> "Cod",

                'car_teh_1' => "Lungime<br>(mm)",

                'car_teh_2' => "Greutate<br>(g)"

            );

            while ($products_options = tep_db_fetch_array($products_options_query)) {


              //  print_r($products_options);


                if($products_options['price_prefix'] == '+')

                    $price_1 = $product_info['products_price'] + $products_options['options_values_price'];

                else

                    $price_1 = $product_info['products_price'] - $products_options['options_values_price'];





                if($products_options['price_prefix_2'] == '+')

                    $price_2 = $product_info['products_price_2'] + $products_options['options_values_price_2'];

                else

                    $price_2 = $product_info['products_price_2'] - $products_options['options_values_price_2'];





                if($products_options['price_prefix_3'] == '+')

                    $price_3 = $product_info['products_price_3'] + $products_options['options_values_price_3'];

                else

                    $price_3 = $product_info['products_price_3'] - $products_options['options_values_price_3'];

                if($new_price){

                    if($products_options['price_prefix_special'] == '+')

                        $price_special = $new_price + $products_options['options_values_price_special'];

                    else

                        $price_special = $new_price - $products_options['options_values_price_special'];

                }else{

                    $price_special = 0;

                }



                $products_options_array[$i++] = array('id' 			=> $products_options['options_values_id'],

                    'text' 			=> $products_options['products_options_values_name'],

                    'price' 		=> $currencies->display_price($price_1, tep_get_tax_rate($product_info['products_tax_class_id'])),

                    'price_2' 		=> $currencies->display_price($price_2, tep_get_tax_rate($product_info['products_tax_class_id'])),

                    'price_3' 		=> $currencies->display_price($price_3, tep_get_tax_rate($product_info['products_tax_class_id'])),

                    'price_special' => $currencies->display_price($price_special, tep_get_tax_rate($product_info['products_tax_class_id'])),

                    'price_notax'  	=> $currencies->format($price_1),

                    'price_2_notax'	=> $currencies->format($price_2),

                    'price_3_notax'	=> $currencies->format($price_3),

                    'price_special_notax'=> $currencies->format($price_special),

                    'cod_unic' 		=> $products_options['cod_unic'],

                    'um' 		=> $products_options['um'],

                    'car_teh_1' 	=> $products_options['car_teh_1'],

                    'car_teh_2' 	=> $products_options['car_teh_2'],

                    'products_attributes_id' => $products_options['products_attributes_id'],

                    'new_price' 	=> $new_price?1:0

                );

    //          if ($products_options['options_values_price'] != '0') {

    //            $products_options_array[sizeof($products_options_array)-1]['text'] .= ' (' . $products_options['price_prefix'] . $currencies->display_price($products_options['options_values_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) .') ';

    //          }



            }



            if (isset($cart->contents[$_GET['products_id']]['attributes'][$products_options_name['products_options_id']])) {

                $selected_attribute = $cart->contents[$_GET['products_id']]['attributes'][$products_options_name['products_options_id']];

            } else if(isset($_GET["attribute"])){

                $selected_attribute = $_GET["attribute"];

            }else{

                $selected_attribute = false;

            }


            //do some sorting

            function cmp($a, $b)
            {
                return strnatcmp($a['text'], $b['text']);
            }

            function sort_by_um($data)
            {
                $metric = $imperial = array();
                foreach ($data as $article){
                    if($article['um'] == 'mm')
                            {$metric[] = $article;} else
                            {$imperial[] = $article;}
                }
                return array_merge($metric,$imperial);
            }

            usort($products_options_array, 'cmp'); // sortam dupa dimensiune

            $products_options_array = sort_by_um($products_options_array); //intai mm apoi inches

            $products_options_array = array_merge($products_options_header, $products_options_array);



            echo tep_draw_product_table('id[' . $products_options_name['products_options_id'] . ']', $products_options_array, $product_info['products_id'], $selected_attribute);



        }



    }

// trebuie sa vedem despre ce e vorba cu seturile astea...

    if( $product_info['is_set'] ){



        tep_db_query("SET SESSION SQL_BIG_SELECTS=1;");



        $products_options_name_query = tep_db_query("select set_cod_unic, products_set_no from products_set where product_id='" . (int)$_GET['products_id'] . "'");

        $j=0;

        while ($products_options_name = tep_db_fetch_array($products_options_name_query)) {

            $products_options_query = tep_db_query("SELECT products_name, pa.products_id, pov.products_options_values_id , pov.products_options_values_name, p.products_um " .

                "FROM " . TABLE_PRODUCTS_ATTRIBUTES . "  pa " .

                "JOIN " . TABLE_PRODUCTS_DESCRIPTION . "  pd ON pa.products_id = pd.products_id " .

                "JOIN " . TABLE_PRODUCTS_OPTIONS_VALUES . "  pov ON pa.options_values_id = pov.products_options_values_id " .

                "JOIN " . TABLE_PRODUCTS . "  p on pa.products_id = p.products_id " .

                "WHERE pa.cod_unic = '" . $products_options_name['set_cod_unic'] . "' " .

                "LIMIT 0 , 1");



            $products_options = tep_db_fetch_array($products_options_query);

            $products_set_options_array[$j++] = array('id' 		=> $products_options['products_options_values_id'],

                'text' 	=> '<a class="linkMic" href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $products_options['products_id'] . '&attribute=' . $products_options['products_options_values_id']) . '">' .

                    $products_options_name['products_set_no'] . " x <b>" . $products_options_name['set_cod_unic'] . "</b> " . $products_options['products_name'] . " - " . $products_options['products_options_values_name'] . //" (". $products_options['products_um'] .")" .

                    '</a>'

            );

        }

    //    	print_r($products_set_options_array);

        echo tep_draw_product_table_set($products_set_options_array);

    }

    ?>




    <a id="tax_button" href="javascript:changeTax(<?=$i.",".$product_info['cant_pret_2'].",".$product_info['cant_pret_3']?>)">Afiseaza preturi fara TVA</a>

    <a href="<?=tep_href_link(FILENAME_PRODUCT_INFO, 'products_id='.tep_get_next_product((int)$_GET['products_id'])) ?>" >Produsul urmator<img style="padding-left:10px"src="images/produsul_urmator_arrow.gif" alt="Produsul urmator" border="0"></a>

    <a href="javascript:printWindow()"><img src="images/icons/print.gif" align="left" border="0"> Printeaza pagina</a>






    <?php

    if ($product_info['products_pdf'] != "") {

        $pdfs = explode(",",$product_info['products_pdf']);



        ?>


            <p class="productTextTitle"><br>Descarca informatii suplimentare despre produs</p>



                        <?php echo '<a target="_new" href="pdf/' . $pdfs[0] . '"><img src="images/icons/arrow.gif" align="left" border="0">Catalog produs</a>'; ?>

                        <a target="_new" href="http://www.adobe.com/go/EN_US-H-GET-READER"><img border="0" src="images/icons/btn_adobe.png" alt="Get Adobe Reader"></a>



                    <?if(sizeof($pdfs)>1){?>


                    <?php echo '<a target="_new" href="pdf/' . $pdfs[1] . '"><img src="images/icons/arrow.gif" align="left" border="0">Detalii tehnice</a>'; ?>

                    <?}?>


    <?php

    }

    ?>

    <?php

    if ($product_info['products_youtube'] != "") {

        $video = parse_url($product_info['products_youtube'], PHP_URL_QUERY);

        parse_str($video, $output);

        $video = $output['v'];

        ?>

        Video produs

                            <object height="385" width="502"><param name="movie" value="http://www.youtube.com/v/<?=$video?>&amp;hl=en&amp;fs=1"><param name="allowFullScreen" value="true"><embed src="http://www.youtube.com/v/<?=$video?>&amp;hl=en&amp;autoplay=0&amp;fs=1" type="application/x-shockwave-flash" allowfullscreen="true" height="385" width="502"></object>


    <?php

    }

    ?>

    <?php

    $reviews_query = tep_db_query("select count(*) as count, (sum(reviews_rating)/count(*)) as medie from " . TABLE_REVIEWS . " where products_id = '" . (int)$_GET['products_id'] . "'");



    $reviews = tep_db_fetch_array($reviews_query);


            for($i=0; $i < 5; $i++){?><img src="images/icons/<?=$i<$reviews['medie']?"star_gold.gif":"star_silver.gif"?>"><?}?>


    <?php

    if ($reviews['count'] > 0) {

        ?>


                <?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params()) . '"><img src="images/icons/arrow.gif" align="left" border="0">' . TEXT_CURRENT_REVIEWS . ' (' . $reviews['count'] . ')' . '</a>'; ?>


    <?php

    }

    ?>

        <?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, tep_get_all_get_params()) . '"><img src="images/icons/arrow.gif" align="left" border="0">' . TEXT_WRITE_A_REVIEW . '</a>'; ?>




            <?php

            if ((USE_CACHE == 'true') && empty($SID)) {

                echo tep_cache_also_purchased(3600);

            } else {

                include(DIR_WS_MODULES . FILENAME_ALSO_PURCHASED_PRODUCTS);

            }

            }

            ?>


        </div>

        <!--<div class="col-md-3 no-padding"><?php /*require(DIR_WS_INCLUDES . 'column_right.php'); */?></div>-->

    </div>
</div>



<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>


</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php');?>

<section id="tabs-zone" class="tabs-sec row">
    <!--<ul id="productTabs" class="nav nav-tabs"><li  class="active">
            <a href="#">Dimensiuni</a>
            </li><li  class="ember-view">
            <a href="#">Detalii tehnice</a>
            </li><li  class="ember-view">
            <a href="#">Informatii aditionale</a>
            </li><li  class="ember-view">
            <a href="#">Protectia muncii</a>
            </li><li  class="ember-view">
            <a href="#">Accesorii</a>
            </li><li  class="ember-view">
            <a href="#">Piese schimb</a>
            </li><li  class="ember-view">
            <a href="#">Produse alternative</a>
            </li>
    </ul>-->
    <div  class="tab-content">
        <div>
            <table class="optionTable">
                <tbody>
                <tr>
                    <th class="optTh">Cod</th>
                    <th class="optTh">Cuplu maxim<br>Nm</th>
                    <th class="optTh">Lungime<br>(mm)</th>
                    <th class="optTh">Greutate<br>(g)</th>
                    <th class="optTh">Cantitate</th>
                    <th class="optTh">Pret cu TVA</th>
                    <th class="optTh">Pret cu TVA<br> &gt; 1 buc/1buc</th>
                    <th class="optTh">Pret cu TVA<br> &gt; 1 buc/1buc</th>
                    <th class="optTh">&nbsp;</th>
                </tr>

                <tr>
                    <td class="mainProduct">615318</td>
                    <td class="mainProduct">27</td>
                    <td class="mainProduct">165</td>
                    <td class="mainProduct">520</td>
                    <td>
                        <input class="input" type="text" size="1" value="1">
                    </td>
                    <td class="mainPrice">

                        <span class="new-price">491,73 Lei</span>

                    </td>
                    <td class="mainPrice"><span>-</span></td>
                    <td class="mainPrice"><span>-</span></td>
                    <td class="mainPrice"><button data-ember-action="1709" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-shopping-cart"></span> Adauga in cos</button></td>
                </tr>
                </tbody>
            </table>
        </div>
</section>