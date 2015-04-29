<?php 
/**
* Plugin: jQuery AJAX-ZOOM, xt:Commerce PHP helper file: xtc_head.php
* Copyright: Copyright (c) 2010 Vadim Jacobi
* License Agreement: http://www.ajax-zoom.com/index.php?cid=download
* Version: 3.2.2
* Date: 2011-07-11
* URL: http://www.ajax-zoom.com
* Description: jQuery AJAX-ZOOM plugin - adds zoom & pan functionality to images and image galleries with javascript & PHP
* Documentation: http://www.ajax-zoom.com/index.php?cid=docs
*/

$axZm_BaseUrl = str_replace('\\','/',dirname($_SERVER["SCRIPT_NAME"]));
if ($axZm_BaseUrl == '/' || $axZm_BaseUrl == '\\'){$axZm_BaseUrl='';}
?>

<link rel="stylesheet" href="<?php echo $axZm_BaseUrl;?>/axZm/plugins/demo/jquery.fancybox/jquery.fancybox-1.2.6.css" type="text/css">
<link rel="stylesheet" href="<?php echo $axZm_BaseUrl;?>/axZm/axZm.css" type="text/css">
<style type="text/css" media="screen"> 
.zoomLogHolder{width: 70px;}
</style>

<script type="text/javascript" src="<?php echo $axZm_BaseUrl;?>/axZm/plugins/jquery-1.6.2.min.js"></script>
<script type="text/javascript">
	//jQuery.noConflict(); // Probleme mit thickbox.js in xtcmodified
</script>

<script type="text/javascript">var axZm_BaseUrl = '<?php echo $axZm_BaseUrl;?>';</script>
<script type="text/javascript" src="<?php echo $axZm_BaseUrl;?>/axZm/jquery.axZm.js"></script>
<script type="text/javascript" src="<?php echo $axZm_BaseUrl;?>/axZm/mods/xtc/xtc_axZm.js"></script>
<script type="text/javascript" src="<?php echo $axZm_BaseUrl;?>/axZm/plugins/demo/jquery.fancybox/jquery.fancybox-1.2.6.js"></script>