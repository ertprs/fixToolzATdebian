<?php
if(!session_id()){session_start();}
unset ($_SESSION['imageZoom']);
$_SESSION['imageZoom']=array();

/*
$docRoot = $_SERVER['DOCUMENT_ROOT'];
if (substr($docRoot,-1) == '/'){$docRoot = substr($docRoot,0,-1);}
$noObjectsInclude = true;
require ($docRoot.'/axZm/zoomInc.inc.php');
*/

$noObjectsInclude = true;
require ('../axZm/zoomInc.inc.php');

echo "
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<title>Ajax Zoom Demo Lightbox & Co. Examples - Ajax</title>
<meta http-equiv=\"X-UA-Compatible\" content=\"IE=EmulateIE7\"> 
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
<meta http-equiv=\"imagetoolbar\" content=\"no\">";

echo $axZmH->drawZoomStyle($zoom); 

echo "
<link rel=\"stylesheet\" href=\"../axZm/plugins/demo/colorbox/example4/colorbox.css\" media=\"screen\" type=\"text/css\">
<link rel=\"stylesheet\" href=\"../axZm/plugins/demo/jquery.fancybox/jquery.fancybox-1.2.6.css\" media=\"screen\" type=\"text/css\">
";

echo $axZmH->drawZoomJs($zoom, $exclude = array()); 

echo "
<script type=\"text/javascript\" src=\"../axZm/plugins/demo/colorbox/jquery.colorbox.js\"></script>
<script type=\"text/javascript\" src=\"../axZm/plugins/demo/jquery.fancybox/jquery.fancybox-1.2.6.js\"></script>

<link href=\"../axZm/plugins/demo/syntaxhighlighter/styles/shCore.css\" type=\"text/css\" rel=\"stylesheet\" />
<link href=\"../axZm/plugins/demo/syntaxhighlighter/styles/shThemeCustom.css\" type=\"text/css\" rel=\"stylesheet\" />
<script type=\"text/javascript\" src=\"../axZm/plugins/demo/syntaxhighlighter/src/shCore.js\"></script>
<script type=\"text/javascript\" src=\"../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushJScript.js\"></script>
<script type=\"text/javascript\" src=\"../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushPhp.js\"></script>
<script type=\"text/javascript\" src=\"../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushCss.js\"></script>
<script type=\"text/javascript\" src=\"../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushXml.js\"></script>
";

?>
<script type="text/javascript">
jQuery(document).ready(function() {
	
	SyntaxHighlighter.all();

	jQuery(".ajaxExampleColorbox").colorbox({
		initialWidth: 300,
		initialHeight: 300,
		scrolling: false,
		scrollbars: false,
		preloading: false,
		opacity: 0.95,
		preloadIMG: false
	}, function(){jQuery.fn.axZm();});
	
	jQuery(".ajaxExampleFancybox").fancybox({
		padding				: 0,
		overlayShow			: true,
		overlayOpacity		: 0.9,
		zoomSpeedIn			: 0,
		zoomSpeedOut		: 100,
		easingIn			: "swing",
		easingOut			: "swing",
		hideOnContentClick	: false, // Important
		centerOnScroll		: false,
		imageScale			: true,
		autoDimensions		: true,
		callbackOnShow		: function(){
			jQuery.fn.axZm();						
		}
	});	
	
});

</script>
<style type="text/css" media="screen"> 
	body {margin:0px; padding:0px;}
	html {margin:0px; padding:0px; border: 0; font-family: Tahoma, Arial; font-size: 10pt;}
	form {padding:0px; margin:0px}
	h2 {padding:0px; margin: 0px 0px 15px 0px; font-size: 16pt;}
	p {text-align: justify; text-justify: newspaper;}
	.zoomHorGalleryDescr{
		display:none;
	}
</style>

<?php
echo "
</head>
<body>
";
include ('navi.php');
echo "<DIV style='width: 800px; margin: 0px auto;'>\n";
	
	echo "<DIV style='float: left; background-color: #FFFFFF; padding: 10px; margin: 5px; background-image: url(http://www.ajax-zoom.com/pic/zoomp/zoom_shot_1.jpg); background-repeat: no-repeat; background-position: 430px 10px;'>\n";
	
		echo "<h2>Ajax Zoom Lightbox & Co.<br>examples with Ajax content</h2>\n";
		
		echo "<DIV style='float: left; width: 150px;'>\n";
			echo "<strong>Fancybox</strong><br>";
			echo "<a class=\"ajaxExampleFancybox\" href=\"../axZm/zoomLoad.php?zoomLoadAjax=1&zoomDir=4&example=4\">Example 1</a><br>";
			echo "<a class=\"ajaxExampleFancybox\" href=\"../axZm/zoomLoad.php?zoomLoadAjax=1&zoomDir=1&zoomID=4&example=5\">Example 2</a><br>";
			echo "<a class=\"ajaxExampleFancybox\" href=\"../axZm/zoomLoad.php?zoomLoadAjax=1&zoomDir=11&example=6\">Example 3</a><br>";
			echo "<a class=\"ajaxExampleFancybox\" href=\"../axZm/zoomLoad.php?zoomLoadAjax=1&zoomDir=7&zoomID=3&example=5\">Example 4</a><br>";
			echo "<a class=\"ajaxExampleFancybox\" href=\"../axZm/zoomLoad.php?zoomLoadAjax=1&zoomDir=12&zoomID=16&example=7\">Example 5</a>";
		echo "</DIV>\n";
		
		echo "<DIV style='float: left; width: 150px;'>\n";
			echo "<strong>Colorbox</strong><br>";
			echo "<a class=\"ajaxExampleColorbox\" href=\"../axZm/zoomLoad.php?zoomLoadAjax=1&zoomDir=4&example=4\">Example 1</a><br>";
			echo "<a class=\"ajaxExampleColorbox\" href=\"../axZm/zoomLoad.php?zoomLoadAjax=1&zoomDir=1&zoomID=4&example=5\">Example 2</a><br>";
			echo "<a class=\"ajaxExampleColorbox\" href=\"../axZm/zoomLoad.php?zoomLoadAjax=1&zoomDir=11&example=6\">Example 3</a><br>";
			echo "<a class=\"ajaxExampleColorbox\" href=\"../axZm/zoomLoad.php?zoomLoadAjax=1&zoomDir=7&zoomID=3&example=5\">Example 4</a><br>";
			echo "<a class=\"ajaxExampleColorbox\" href=\"../axZm/zoomLoad.php?zoomLoadAjax=1&zoomDir=12&zoomID=16&example=7\">Example 5</a>";
		echo "</DIV>\n";

		echo "<DIV style='clear: both;'>\n";
			echo "<DIV style='float: right; width: 360px; height: 150px;'></DIV>";
			?>
			<p style="padding-top:20px; font-size:120%">
			This example demonstrates how to open multiple zoom galleries with some lightbox clones (please click on the links above). 
			The content is loaded via Ajax requests. 
			Due to "cross scripting" issues this solution does not work "cross domain". 
			For cross domain imaplementation (iframe) see <a href="example2.php">example2</a> and <a href="example13.php">example13</a>.
			</p>
			<p style="font-size:120%">
			Please note, that not all lightbox clones fully suport ajax content. 
			</p>
			<?php

			$example = 3;
			include('syntax.php');

		echo "</DIV>\n";
		
	echo "</DIV>\n";
echo "</DIV>\n";
include('footer.php');
echo "
</body>
</html>
";
?>