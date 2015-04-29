<?php


  require('../includes/application_top.php');



if(!session_id()){session_start();}
unset ($_SESSION['imageZoom']);
$_SESSION['imageZoom']=array();

$_GET['example'] = 9;

if (!isset($_GET['zoomDir'])){
	$_GET['zoomDir'] = 'fashion';
	$_GET['zoomFile'] = 'suit_2.jpg';
}

	$zoomData = array();
		
	$zoomData[1]['p'] = '/../poze';
	$zoomData[1]['f'] = '10505372.jpg';

	$_GET['zoomData'] = strtr(base64_encode(addslashes(gzcompress(serialize($zoomData),9))), '+/=', '-_,');


// The following function is crap to produce the "lorem impsum" text as placeholder
function lorem($abs = 999){
	$return = '';
	$filename = 'lorem.txt';
    $ini_handle = fopen($filename, "r");
    $ini_contents = fread($ini_handle, filesize($filename));
	$ini_contents = nl2br ($ini_contents);
	$array = split('<br />',$ini_contents);
	$n=0;
	foreach ($array as $text){
		$n++;
		if ($n <= $abs){
			$return.="<p></p>\n";
		} else{
			break;
		}
	}
	return $return;
}

/*
$docRoot = $_SERVER['DOCUMENT_ROOT'];
if (substr($docRoot,-1) == '/'){$docRoot = substr($docRoot,0,-1);}
require ($docRoot.'/axZm/zoomInc.inc.php');
*/

require ('axZm/zoomInc.inc.php');

echo "
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<title>ToolsZone.ro - </title>
<meta http-equiv=\"X-UA-Compatible\" content=\"IE=EmulateIE7\"> 
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
<meta http-equiv=\"imagetoolbar\" content=\"no\">

";

echo $axZmH->drawZoomStyle($zoom); 
echo $axZmH->drawZoomJs($zoom, $exclude = array()); 
echo "
<link href=\"axZm/plugins/demo/syntaxhighlighter/styles/shCore.css\" type=\"text/css\" rel=\"stylesheet\" />
<link href=\"axZm/plugins/demo/syntaxhighlighter/styles/shThemeCustom.css\" type=\"text/css\" rel=\"stylesheet\" />
<script type=\"text/javascript\" src=\"axZm/plugins/demo/syntaxhighlighter/src/shCore.js\"></script>
<script type=\"text/javascript\" src=\"axZm/plugins/demo/syntaxhighlighter/scripts/shBrushJScript.js\"></script>
<script type=\"text/javascript\" src=\"axZm/plugins/demo/syntaxhighlighter/scripts/shBrushPhp.js\"></script>
<script type=\"text/javascript\" src=\"axZm/plugins/demo/syntaxhighlighter/scripts/shBrushCss.js\"></script>
<script type=\"text/javascript\" src=\"axZm/plugins/demo/syntaxhighlighter/scripts/shBrushXml.js\"></script>
";
?>

<style type="text/css" media="screen"> 
	body {height: 100%;}
	html {font-family: Tahoma, Arial; font-size: 10pt;}
	h2 {padding:0px; margin: 0px 0px 15px 0px; font-size: 16pt;}
	.outerimg{
		margin: 0px 5px 3px 0px;
		border: blue 2px solid;
	}
	.p{text-align: justify; text-justify: newspaper;}
	
	
	/* Override some default styles for the demo
	   For your application you schould change the css file!	
	*/
	.zoomNavigation{
		background-image: none;
		background-color:#FFFFFF;
	}
	.zoomContainer {
		background-color: #FFFFFF;
	}
	
	.zoomLogHolder{
		width: 50px;
		height: 50px;
	}
	
	.zoomLogJustLevel{
		width: 45px;
		color: #444444;
		font-size: 13pt; 
		font-family: Tahoma, Arial;
		margin: 10px 0px 0px 3px;
	}	
</style>

<script type="text/javascript">
	SyntaxHighlighter.all();
	jQuery(window).load(function () {

	});

</script>

</head>
<body>
<div style="position: absolute; top: 11px; left: 11px; width: 370px; height: 155px; background-color: white;z-index:999999999999;">&nbsp;
	<table width="100%" cellspacing="0">
		<tr>
			<td align="left" valign="top" width="180px">&nbsp;&nbsp;<img src="logo.png"></td>
			<td align="left" valign="top">Test</td>
		</tr>
	</table>
</div>
<div style="padding:10px; text-align:left">
	<div style="padding:10px;border:1px solid grey">
	<table width="100%" cellspacing="0">
		<tr>
			<td align="left"><img src="logo.png"></td>
			<td align="left"></td>
		</tr>
		<tr>
			<td colspan="2">
<?php
				echo $axZmH->drawZoomBox($zoom, $zoomTmp);
				echo $axZmH->drawZoomJsConf($zoom, $rn = false, $pack = true);
				echo $axZmH->drawZoomJsLoad($zoom, $pack = true, $windowLoad = true);
?>			
			</td>
		</tr>
	</table>
	</div>
</div>
</body>
</html>