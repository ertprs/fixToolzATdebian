<?php
if(!session_id()){session_start();}
unset ($_SESSION['imageZoom']);
$_SESSION['imageZoom']=array();

$_GET['example'] = 8;
//$_GET['zoomDir'] = 'boutique';
//$_GET['zoomFile'] = 'watch_2.jpg';


	$zoomData = array();
		
		$zoomData[2]['p'] = '/pic/zoom/animals/';
		$zoomData[2]['f'] = 'test_animals2.png';
	
		$zoomData[1]['p'] = '/pic/zoom/animals/'; // Path to image
		$zoomData[1]['f'] = 'test_animals1.png'; // Image filename
		
		$zoomData[2]['p'] = '/pic/zoom/animals/';
		$zoomData[2]['f'] = 'test_animals2.png';

		$zoomData[3]['p'] = '/pic/zoom/boutique/';
		$zoomData[3]['f'] = 'test_boutique1.png';

		$zoomData[4]['p'] = '/pic/zoom/boutique/';
		$zoomData[4]['f'] = 'test_boutique2.png';
		
		$zoomData[5]['p'] = '/pic/zoom/boutique/';
		$zoomData[5]['f'] = 'test_boutique3.png';

		$zoomData[6]['p'] = '/pic/zoom/estate/';
		$zoomData[6]['f'] = 'test_estate1.png';

		$zoomData[7]['p'] = '/pic/zoom/estate/';
		$zoomData[7]['f'] = 'test_estate2.png';
		
		$zoomData[8]['p'] = '/pic/zoom/estate/';
		$zoomData[8]['f'] = 'test_estate3.png';	

		$zoomData[9]['p'] = '/pic/zoom/random/';
		$zoomData[9]['f'] = 'test_random1.png';

		$zoomData[10]['p'] = '/pic/zoom/random/';
		$zoomData[10]['f'] = 'test_random2.png';
		
		$zoomData[11]['p'] = '/pic/zoom/random/';
		$zoomData[11]['f'] = 'test_random3.png';	

		$_GET['zoomData'] = strtr(base64_encode(addslashes(gzcompress(serialize($zoomData),9))), '+/=', '-_,');
		
		
/*
$docRoot = $_SERVER['DOCUMENT_ROOT'];
if (substr($docRoot,-1) == '/'){$docRoot = substr($docRoot,0,-1);}
require ($docRoot.'/axZm/zoomInc.inc.php');
*/

require ('../axZm/zoomInc.inc.php');


// The following function is crap to produce the "lorem impsum" text as placeholder
function lorem($abs = 999){
	$return = '';
	return $return;
}

echo "
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<title>Image Zoom Javascript PHP inLine Implementation</title>
<meta http-equiv=\"X-UA-Compatible\" content=\"IE=EmulateIE7\"> 
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
<meta http-equiv=\"imagetoolbar\" content=\"no\">

";

//echo $axZmH->drawZoomStyle($zoom); 
//echo $axZmH->drawZoomJs($zoom, $exclude = array()); 

?>

<style type="text/css" media="screen"> 
	body {height: 100%;}
	html {font-family: Tahoma, Arial; font-size: 10pt;}
	h2 {padding:0px; margin: 0px 0px 15px 0px; font-size: 16pt;}
	p {text-align: justify; text-justify: newspaper;}
</style>
<link rel="stylesheet" href="../axZm/axZm.css" type="text/css" media="screen">
<link rel="stylesheet" href="../axZm/plugins/demo/lavalamp/lavalamp_test.css" type="text/css" media="screen">

<script type="text/javascript" src="../axZm/plugins/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="../axZm/jquery.axZm.js"></script>

<script type="text/javascript" src="../axZm/plugins/demo/lavalamp/jquery.lavalamp.js"></script>

<?php

echo "
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
	SyntaxHighlighter.all();
	
	function submitNewZoom(menuItem){
		var id = jQuery(menuItem).attr('id').split('zoomSet').join('');
		if (id){
			var data = 'example=<?php echo $_GET['example'];?>&zoomDir='+id;
			jQuery.fn.axZm.loadAjaxSet(data);
		}	
	}
	
	jQuery(window).load(function () {
		
		jQuery("#lavalampMenu").lavaLamp({
			fx: "easeOutBack",
			speed: 750,
			click: function(event, menuItem) {
				submitNewZoom(menuItem);
				return false;
			}
		});	

	});

</script>

<?php


echo "
</head>
<body>
";
	
		echo "<DIV style='clear: both;'>\n";
			//echo "<DIV style='float: right; width: 360px; height: 200px;'></DIV>";
			?>
			
			<?php
			echo "<DIV id='zoomInlineContent' style='margin: 20px 0px 0px 0px; padding: 6px; background-image: url(../axZm/icons/back_inline.png); background-repeat: no-repeat;'>"; // background-color: #E5E5E5
			echo $axZmH->drawZoomBox($zoom, $zoomTmp);
			echo $axZmH->drawZoomJsConf($zoom, $rn = false, $pack = true);
			echo $axZmH->drawZoomJsLoad($zoom, $pack = true, $windowLoad = true);
			echo "</DIV>";
			


			$example = 4;			

		echo "</DIV>\n";
		
	echo "</DIV>\n";
echo "</DIV>\n";
echo "
</body>
</html>
";
?>