<?                             
  require('includes/application_top.php'); 
     
  $top_cat_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '0' and c.categories_id = cd.categories_id and cd.language_id='" . (int)$languages_id ."' order by sort_order, cd.categories_name");

?>

	<link rel="stylesheet" href="http://www.toolszone.ro/stylesheet.css" type="text/css" media="screen" />
	<table width="100%" cellpadding="0" cellspacing="0"><tr><td align="center" valign="top">
	<table width="500" cellpadding="0" cellspacing="0">
  	<tr><td align="center" style="font-size:9px">Daca nu puteti vizualiza acest email click <a style="font-size:9px" href="http://www.toolszone.ro/newsletter.php" >aici</a>. Ca sa va asigurati ca emailurile de la ToolsZone.ro<br> ajung la dumneavoastra in inbox va rugam sa adaugati <a style="font-size:9px" href="mailto:office@toolszone.ro" >office@toolszone.ro</a> in lista de adrese. </td></tr>
    <tr><td align="center"><a href="http://www.toolszone.ro/"><img src="images/newsletter.png" border="0" alt="ToolsZone.ro"></a></td></tr>
    <tr>
    	<td align="center">
		  <table border="0" cellpadding="0" cellspacing="0">
		  <tr>       
			<?
			  $j=0;
			  while ($categories = tep_db_fetch_array($top_cat_query))  {
			  	if($categories['categories_id']!=1 && $categories['categories_name']!='Solduri'){
				    $cPath_new = 'cPath=0_' . $categories['categories_id'];
				    $categories_string = '<a href="'.tep_href_link(FILENAME_DEFAULT, $cPath_new) . '" title=""><img border="0px" src="images/tz_'.strtolower($categories['categories_name']).'.png"/></a>'."\n";
					
					echo '<td>'.$categories_string."</td>";
			  		$j++;
			  	}
			  }		
			?>	  
		  </tr>
		  </table>	
		</td>
	  </tr>                                                                                      
	  <tr><td align="center"><?php include(DIR_WS_MODULES . FILENAME_BANNERS); ?></td></tr>
    <tr><td align="center"><?php include(DIR_WS_MODULES . FILENAME_SCULE); ?></td></tr>
    <tr><td align="center"><?php include(DIR_WS_MODULES . FILENAME_SPECIALS); ?></td></tr>
    <tr><td align="center"><?php include(DIR_WS_MODULES . FILENAME_BEST_SELLERS); ?></td></tr>
    <tr><td align="center"><?php include(DIR_WS_MODULES . FILENAME_NEW_PRODUCTS); ?></td></tr>
  	<tr><td align="left" style="font-size:9px"><br>Pentru a facilita accesul la produsele propuse de noi, ToolsZone.ro va ofera 3 modalitati de plata dupa cum urmeaza:<br><br></td></tr>
  	<tr><td align="left" style="font-size:9px"><ul><li>Cu card de credit</li><li>Plata ramburs la primirea produselor</li><li>Plata prin Ordin de Plata. Dupa ce ati primit factura proforma pentru comanda dumneavoastra , puteti achita prin ordin de plata. In momentul in care primim confirmarea de plata, produsele vor fi livrate catre dumneavoastra</li></ul></td></tr>
  	<tr><td align="left" style="font-size:9px"><br>Preturile afisate pe ToolsZone.ro includ TVA (24%), taxele de livrare sunt detaliate pe factura aferenta produselor dumneavoastra pentru comenzi mai mici de 200 RON, iar protocolul de discount este explicat in amanunt aici. </td></tr>
  	<tr><td align="left" style="font-size:9px"><br>Pentru comenzi mai mari de 200 RON, ToolsZone.ro va suporta integral cheltuielile de transport. De asemenea, toate produsele care au preturi mai mari de 200 RON vor beneficia automat de transport gratuit, dupa cum arata si simbolul care le insoteste. </td></tr>
  	<tr><td align="left" style="font-size:9px"><br>Nota:<br>
<br>Ne cerem scuze daca acest mesaj a ajuns la dumneavoastra dintr-o eroare. 
<br><b>Daca nu mai doriti sa primiti informarile noastre, trimiteti-ne (“reply”) un mesaj cu subiectul "Nu"</b> si mentionati adresa de e-mail care sa fie stearsa din baza noastra de date.
<br>In conformitate cu legea 365/2002 privind comertul electronic, acest mesaj nu poate fi considerat spam, deoarece:
<br>-  Contine instructiuni de dezabonare;
<br>-  Acceptarea de primire a ofertei nu va implica financiar;
<br>-  Adresa dumneavoastra de email a fost facuta publica de catre dvs. prin afisari cu caracter publicitar, fie intr-un ghid de afaceri sau am primit-o cu ocazia unor intalniri sau contacte de afaceri, fie a fost selectata dintr-o baza de date la care dvs. ati subscris, sunteti in baza noastra de date ca urmare a unor corespondente anterioare, sau sunteti un client al companiei noastre.
<br>-  Acest mesaj va este adresat cu scopul de a va invita sa beneficiati de serviciile companiei noastre si va este transmis in dorinta de a va tine la curent cu cele mai noi si mai profitabile produse si servicii pe care vi le putem oferi.
</td></tr>
  	<tr><td align="left" style="font-size:9px"><br></td></tr>
  </table>
  </td></tr></table>