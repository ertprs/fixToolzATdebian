<?php
  require('includes/application_top.php');
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
  $oID = tep_db_prepare_input($_GET['oID']);
  $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
  include(DIR_WS_CLASSES . 'order.php');
  $order = new order($oID);
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<style type="text/css">

table {
	font-size : 10px;
	font-family : arial;

	border-width: 1px 1px 1px 1px;
	border-spacing: 2px;
	border-style: outset outset outset outset;
	border-color: gray gray gray gray;
	border-collapse: collapse;
	background-color: white;
}
table th {
	border-width: 1px 1px 1px 1px;
	padding: 1px 1px 1px 1px;
	border-style: inset inset inset inset;
	border-color: gray gray gray gray;
	background-color: white;
	-moz-border-radius: 0px 0px 0px 0px;
}
table td {
	border-width: 1px 1px 1px 1px;
	padding: 1px 1px 1px 1px;
	border-style: inset inset inset inset;
	border-color: gray gray gray gray;
	background-color: white;
	-moz-border-radius: 0px 0px 0px 0px;
}
</style>


<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo "Factura fiscala - www.ToolsZone.ro" ?></title>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<?php


//editeaza aici/////////////////////////////////////////////////////
// Factura A5 versiunea 1

$sigla= '<img src="images/oscommerce.png" width=200>';
$furnizor="<b>Furnizor: </b>";
$cui= "CUI: ";
$regcom= "Reg Com.: ";
$adresa="Adresa: ";
$banca="Banca: ";
$capital="Capital social: ";
$contact="Web: </a><br>Telefon: ";
$cotatva="19";
$serie="SER";
$tarifposta=7;
$intocmit="";
$stampila="";

//gata editarea//////////////////////////////////////////////////////



?>
<table width =99% border=1>
<tr><td width=33%><center><?php echo $sigla; ?></center><br>
<?php echo $furnizor; ?><br>
<?php echo $cui; ?><br>
<?php echo $regcom; ?><br>
<?php echo $adresa; ?><br>
<?php echo $banca; ?><br>
<?php echo $capital; ?><br>
<?php echo $contact; ?><br>
<?php if($_GET[goala]!="1") echo "Cota TVA: ".$cotatva."%"; ?>

</td><td width=33%>
<center>
<?php echo "<h2>Seria ".$serie."</h2>";?>
<h1>FACTURA</h1>
</center>
<br><hr>
Nr. facturii: <?php if ($_GET[goala]!="1") if ($_GET[nr]=="") echo $_GET[oID]; else echo $_GET[nr]; else echo "";
$query  = "SELECT date_purchased FROM orders where orders_id=".$_GET[oID];
$rrw = mysql_query($query);
$row = mysql_fetch_array($rrw, MYSQL_BOTH);
$x=explode(" ",$row[date_purchased]);
$x=explode("-",$x[0]);
?><br>
Data (ziua,luna,anul): <?php if ($_GET[goala]!="1") if ($_GET[data]=="") echo $x[2].".".$x[1].".".$x[0]; else echo $_GET[data]; else echo "";?><br>
Nr. aviz de insotire a marfii:.........................<br>
<center>(daca este cazul)</center>

</td><td width=33%>
<?php 
if ($_GET[goala]!="1") {
echo "<h3>Cumparator: ".strtoupper($order->customer['name'])."</h3>";
$x=explode(">>>",tep_address_format($order->delivery['format_id'], $order->delivery, 1, '','>>>')); 
echo "Adresa:".$x[1]."<br>".$x[2]."<br>".$x[3];
echo "<br>Tel: ".$order->customer['telephone'];
}
else {echo "<h3>Cumparator:</h3>";
echo "CNP/CUI:<br><br>";
echo "Adresa:<br><br><br><br><br>";
echo "Telefon:";
}
 ?>

</td></tr>
</table>
<table width=99% border=1  height="650">
<tr>
<td height="5"><center> <b>Nr.crt</b></center></td>
<td><center> <b>Denumirea produselor</b></center></td>
<td><center> <b>Cod</b></center></td>
<td><center> <b>U.M.</b></center></td>
<td><center> <b>Cantitatea</b></center></td>
<td><center> <b>Pretul unitar<br>(fara TVA) -lei-</b></center></td>
<td><center> <b>Valoarea -lei-</b></center></td>
<td><center> <b>Valoarea TVA -lei-</b></center></td>
</tr>
<tr>
<td height="5"><center>0</center></td>
<td><center>1</center></td>
<td><center>2</center></td>

<td><center>3</center></td>
<td><center>4</center></td>
<td><center>5</center></td>
<td><center>6 (4x5)</center></td>
<td><center>7</center></td>
</tr>
<?php $nrcrt=1;?>


<?php

if ($_GET[goala]=="1") {
			for ($i = 0; $i < 10; $i++)
{$y=$i+1;
echo "<tr><td height=\"5\"><center>".$y."</center></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";}


			}
else{

$vtftva=0;
$vttva=0;

    for ($i = 0, $n = sizeof($order->products); $i < $n; $i++) {
      echo '      <tr>' . "\n" .'<td height="5"><center>'.$nrcrt.'</center></td>'.
           '        <td class="dataTableContent" valign="top" align="left">' . $order->products[$i]['name'];
$nrcrt=$nrcrt+1;
      if (isset($order->products[$i]['attributes']) && (($k = sizeof($order->products[$i]['attributes'])) > 0)) {
        for ($j = 0; $j < $k; $j++) {
          echo '<br><nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' .
$order->products[$i]['attributes'][$j]['value'];
          if ($order->products[$i]['attributes'][$j]['price'] != '0') 
{
echo ' (' . $order->products[$i]['attributes'][$j]['prefix'] .
 $currencies->format($order->products[$i]['attributes'][$j]['price'] * $order->products[$i]['qty'], true, $order->info['currency'],
$order->info['currency_value']) . ')';
$bat=($order->products[$i]['attributes'][$j]['price'] * 100)/100;
}

          echo '</i></small></nobr>';
        }
      }
      echo '        </td>' . "\n" .
         '        <td><center>' . $order->products[$i]['model'] . '</center></td>' . "\n".
 '        <td><center>buc</center></td>' . "\n" .
 '        <td><center>' . $order->products[$i]['qty'] . '</center></td>' . "\n" ;


$pu=($order->products[$i]['final_price']*100)/100;
$vl=$pu*$order->products[$i]['qty'];
$vtftva=$vtftva+$vl;
$vtva=$vl*0.19;
$vttva=$vtva+$vttva;
echo '<td><center>';
printf("%.2f", $pu);
echo '</center></td>' . "\n";      echo '      ' . "\n";
echo '<td><center>';
printf("%.2f", $vl);
echo '</center></td>' . "\n";
      echo '      ' . "\n";
      echo '        <td><center>';
printf("%.2f", $vtva); echo '</center></td>' . "\n";
      echo '      </tr>' . "\n";
    }
    /*
echo "<tr><td><center>".$nrcrt."</center></td><td>Taxe 
transport</td><td><center>-</center></td><td><center>-</center></td><td><center>1</center></td><td><center>";
printf("%.2f", ($tarifposta*100)/119);
echo "</center></td><td><center>";
printf("%.2f", ($tarifposta*100)/119);
echo "</center></td><td><center>";
printf("%.2f", (($tarifposta*100)/119)*0.19);
echo "</center></td></tr>";
$vtftva=$vtftva+($tarifposta*100)/119;
$vttva=$vttva+(($tarifposta*100)/119)*0.19;
       */
}

?>
<tr><td colspan="8" height="*"></td></tr>
</table>
<table width=99% border=1><tr><td>
<?php echo $intocmit; ?></td></tr></table>
<table width=99% border=1><tr>
<td width=180>Semnatura si stampila furnizorului:<br>
<center><img width=90 src="<?php echo $stampila; ?>"></center>
</td>
<td width=225><b>Date privind expeditia</b><br>
Numele delegatului:....................................<br>
Buletinul/cartea de identitate<br>
seria........nr..............eliberat(a)..................
<br>Mijloc de transport<br>
nr..............................................................<br>
Expedierea s-a efectuat in prezenta noastra<br>la
data de..............................ora................
<br>Semnaturile................................................
</td>
<td>
		Total<br>din care:<br> accize<hr>
		Semnatura de primire

</td>
<td>
	<table width=100% height=55 border=1><tr><td>
	                <?php 
if($_GET[goala]=="1") echo "<font color=white>&nbsp;&nbsp;</font>";
echo "<center>";
if($_GET[goala]!="1") printf("%.2f", $vtftva); echo"</center>";?></td><td> <?php; echo "<center>";
if($_GET[goala]!="1") printf("%.2f", $vttva); echo "</center>";?>
</td></tr><tr><td><?php if($_GET[goala]=="1") echo "<font color=white><br></font>"; ?></td><td><?php 
echo "<center>X</center>"; ?></td></tr></table>
Total de plata<br>(col. 6 + col. 7)
		<center><h2>
		<?php 
if ($_GET[goala]!="1" ) echo number_format($vtftva+$vttva,2,",", " ");?></h2></center>
</td>
</tr></table>

</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
