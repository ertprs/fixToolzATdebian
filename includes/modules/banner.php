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

<!--                                            

<div style="padding-bottom:8px">

      <script src="../js/AC_RunActiveContent.js" type="text/javascript"></script>
      <script src="../js/AC_ActiveX.js" type="text/javascript"></script>            
      <script type="text/javascript">

      AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0','width','500','height','100','src','../images/flash/discount','quality','high','pluginspage','http://www.macromedia.com/go/getflashplayer','movie','../images/flash/discount' ); //end AC code

      </script><noscript><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="500" height="100">

        <param name="movie" value="../images/flash/discount.swf" />
        <param name="quality" value="high" />

        <embed src="../images/flash/discount.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="500" height="100"></embed>

      </object></noscript> 

-->      

<!--<img border="0" src="images/banners/discount.gif" />-->

<!--

<div style="padding-bottom:8px">

      <script src="../js/AC_RunActiveContent.js" type="text/javascript"></script>
      <script src="../js/AC_ActiveX.js" type="text/javascript"></script>            
      <script type="text/javascript">

      AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0','width','500','height','100','src','../images/flash/discount','quality','high','pluginspage','http://www.macromedia.com/go/getflashplayer','movie','../images/flash/discount' ); //end AC code

      </script><noscript><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="500" height="100">
        <param name="movie" value="../images/flash/discount.swf" />
        <param name="quality" value="high" />
        <embed src="../images/flash/discount.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="500" height="100"></embed>

      </object></noscript> 
-->

<div>
<div style="padding-bottom:8px">
<?
    include(DIR_WS_BOXES . 'banner_flash.php');
?>

</div>

<?}?>

<div style="padding-bottom:8px"><a href="http://www.toolszone.ro/genunchiera-igel8482-irwin-p-11746.html
" ><img border="0" src="images/banners/ad_banner_static_500x225_1.jpg" /></a></div>

<div style="padding-bottom:8px"><a href="http://www.toolszone.ro/set-preducele-33015-piese-sam-p-3662.html
" ><img width="500" border="0" src="images/banners/ad_banner_static_500x225_2.jpg" /></a></div>

<div style="padding-bottom:8px"><a href="http://www.toolszone.ro/pantofi-de-protectie-cu-bombeu-si-lamela-metalica-42192-s1p-sir-safety-p-11368.html
" ><img width="500" border="0" src="images/banners/ad_banner_static_500x225_3.jpg" /></a></div>


<!--

<div style="padding-bottom:8px">

	<a href="<?=tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=1536') ?>" ><img border="0" src="images/banners/ad_500x250.v2_01.png" /></a><a href="<?=tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=1544') ?>" ><img border="0" src="images/banners/ad_500x250.v2_02.png" /></a><a href="<?=tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=1551') ?>" ><img border="0" src="images/banners/ad_500x250.v2_03.png" /></a>


</div>

-->

<table cellpadding="0" cellspacing="3">

  <tr>

    <td><a href="<?=tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=11772') ?>" ><img src="images/banners/rand_1_1.jpg" border="0"></a></td>

    <td><a href="<?=tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=11773') ?>" ><img src="images/banners/rand_1_2.jpg" border="0"></a></td>

    <td><a href="<?=tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=2588') ?>" ><img src="images/banners/rand_1_3.jpg" border="0"></a></td>

  </tr>

  <tr>

    <td><a href="<?=tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=2618') ?>" ><img src="images/banners/rand_2_1.jpg" border="0"></a></td>

    <td><a href="<?=tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=2580') ?>" ><img src="images/banners/rand_2_2.jpg" border="0"></a></td>

    <td><a href="<?=tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=2650') ?>" ><img src="images/banners/rand_2_3.jpg" border="0"></a></td>

  </tr>

  <tr>

    <td><a href="<?=tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=2597') ?>" ><img src="images/banners/rand_3_1.jpg" border="0"></a></td>

    <td><a href="<?=tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=2591') ?>" ><img src="images/banners/rand_3_2.jpg" border="0"></a></td>

    <td><a href="<?=tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=2660') ?>" ><img src="images/banners/rand_3_3.jpg" border="0"></a></td>

  </tr>

</table>

<?

    include(DIR_WS_BOXES . 'spacing.php');

?>