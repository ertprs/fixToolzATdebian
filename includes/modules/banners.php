<?

function curPageURL() {

 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
 $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}

if( curPageURL() != "http://www.toolszone.ro/newsletter.php" ){

?>


<?}?>

<div style="padding-bottom:8px"><a href="<?=tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=25750') ?>" ><img width="500" alt="Masina de frezat 1800 W turatie variabila penseta 12,7 mm PR 10 EK STAYER" title="Masina de frezat 1800 W turatie variabila penseta 12,7 mm PR 10 EK STAYER" src="images/banners/apr15/01.jpg" /></a></div>
<div style="padding-bottom:8px"><a href="<?=tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=23826') ?>" ><img width="500" alt="Rindea manuala de netezire pentru orice tip de lemn tip Bench lama 39 mm unghi aschiere 45° 2-39 PINIE" title="Rindea manuala de netezire pentru orice tip de lemn tip Bench lama 39 mm unghi aschiere 45° 2-39 PINIE" src="images/banners/apr15/02.jpg" /></a></div>
<div style="padding-bottom:8px"><a href="<?=tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=15711') ?>" ><img width="500" alt="Masina de insurubat cu impact 1/2&quot; DUOPACT 900-1200 Nm 2277 RODCRAFT" title="Masina de insurubat cu impact 1/2&quot; DUOPACT 900-1200 Nm 2277 RODCRAFT" src="images/banners/apr15/03.jpg" /></a></div>
<div style="padding-bottom:8px"><a href="http://www.toolszone.ro/advanced_search_result.php?keywords=PANASONIC&allProducts=1&page=1&sort=4a" ><img width="500" alt="Panasonic" title="Panasonic" src="images/banners/apr15/04.jpg" /></a></div>


<div class="area52">

        <div class="col-sm-4 no-padding">
            <a href="<?=tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=22008') ?>" ><img src="images/banners/m1.jpg" alt="Cleste inel de siguranta, interior, varfuri indoite brunat 538/4 UNIOR" title="Cleste inel de siguranta, interior, varfuri indoite brunat 538/4 UNIOR"></a>
        </div>
        <div class="col-sm-4 no-padding">
            <a href="<?=tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=21998') ?>" ><img src="images/banners/m2.jpg" alt="Cleste industrial pentru tevi cu autoimpanare tip american GGG-W-651 tip II clasa A 490/6 UNIOR" title="Cleste industrial pentru tevi cu autoimpanare tip american GGG-W-651 tip II clasa A 490/6 UNIOR"></a>
        </div>

        <div class="col-sm-4 no-padding">
            <a href="<?=tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=21987') ?>" ><img src="images/banners/m3.jpg" alt="Surubelnita cu profil TORX cu gaura maner ergonomic polipropilena negru 621 CR UNIOR" title="Surubelnita cu profil TORX cu gaura maner ergonomic polipropilena negru 621 CR UNIOR"></a>
        </div>

        <div class="col-sm-4 no-padding">
            <a href="<?=tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=22002') ?>" ><img src="images/banners/m4.jpg" alt="Cheie inelara de soc 184 O UNIOR"></a>
        </div>
        <div class="col-sm-4 no-padding">
            <a href="<?=tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=21991') ?>" ><img src="images/banners/m5.jpg" alt="Surubelnita pentru electricieni izolata la 1000 V cu cap tubular cu 6 laturi maner ergonomic bimaterial 629 VDE BI UNIOR"></a>
        </div>

        <div class="col-sm-4 no-padding">
            <a href="<?=tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=21993') ?>" ><img src="images/banners/m6.jpg" alt="Surubelnita profil Phillips si maner din lemn 616 W UNIOR"></a>
        </div>
        <div class="col-sm-4 no-padding">
            <a href="<?=tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=22000') ?>" ><img src="images/banners/m7.jpg" alt="Cheie cu 3 capete profil TORX exterior 193 TX2 UNIOR"></a>
        </div>
        <div class="col-sm-4 no-padding">
            <a href="<?=tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=21999') ?>" ><img src="images/banners/m8.jpg" alt="Cheie cu 3 capete profil hexagonal exterior 193 HX2 UNIOR"></a>
        </div>
        <div class="col-sm-4 no-padding">
            <a href="<?=tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=21984') ?>" ><img src="images/banners/m9.jpg" alt="Surubelnita pentru electricieni izolata la 1000 V cu profil TORX maner ergonomic bimaterial 621 VDE BI UNIOR"></a>
        </div>

</div>
