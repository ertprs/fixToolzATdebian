<?

  require('includes/application_top.php');
  
  $codes= (isset($_POST['codes']) ? $_POST['codes'] : '');
  if($codes!=''){
  
  $codes =  "'" . str_replace(',', "','", $codes) . "'";
  
  
  $products_query = tep_db_query("SELECT p.products_id,p.nr_articol,pa.cod_unic,p.products_image,".
			"pd.products_name, pd.Products_description, po.products_options_values_name, ".			
			"(CASE ".
			"WHEN pa.price_prefix = '+' THEN p.products_price + pa.options_values_price ".
			" ELSE p.products_price - pa.options_values_price ".
			" END) as price ".			
			" FROM products p ".
			" LEFT JOIN products_attributes pa on p.products_id = pa.products_id ".
			" LEFT JOIN products_description pd on p.products_id = pd.products_id ".
  			" LEFT JOIN products_options_values po on po.products_options_values_id = pa.options_values_id ".
  			" WHERE pa.cod_unic in ($codes) ");
//603316,603319,618370,600112,600111
?>
<style>
table {font-size:12px;font-family:Arial}
.oferta td {border-left:1px solid #000;border-bottom:1px solid #000}
.oferta th {font-size:8px;border-left:1px solid #000;border-top:1px solid #000;border-bottom:1px solid #000}
.right{border-right:1px solid #000;}
.bold{font-weight:bold;}
.no-left{border-left:0px}
input{border:0px}
.align-right{text-align:right}
</style>

<table width="900px">
	<tr>
		<td align="left" valign="top">
			<table width="100%">
				<tr>
					<td width="40%" align="left" valign="top">
						<font style="font-size:20px">S.C. VIRTUAL TOOLS S.R.L.</font><br>
						B-dul. 15 Noiembrie Nr. 80, Bl. C24, Et. 1, Ap. 1<br>
						500102, Brasov, Romania<br>
						Tel. 0368/004.674; 0770/165.655, Mobil: 0727/387.799<br>
						Email: office@toolszone.ro<br>
						Web: www.ToolsZone.ro<br>
					</td>
					<td align="center" valign="middle"><img src="http://www.toolszone.ro/admin/images/oscommerce.png" alt="Logo Toolszone.ro" /></td>
					<td width="40%" align="right" valign="top">
						Cod Fiscal: RO25866810<br>
						Reg. Comertului: J08/1193/07.08.2009<br>
						Banca: PROCREDIT<br>
						Cont: RO38MIRO0000317413440601<br>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td align="center" style="font-size:30px;">
			OFERTA DE PRET
		</td>
	</tr>
	<tr>
		<td align="left" style="padding-left:30px; padding-bottom:20px; padding-top:20px" class="bold">
			<input size="70" type="text" style="border:0px; font-weight:bold" value="Catre: "><br>
			<input size="70" type="text" style="border:0px; font-weight:bold" value="In atentia ">
		</td>
	</tr>
	<tr>
		<td align="left" valign="top" >
			<table class="oferta" width="100%" cellspacing="0" class="oferta">
				<tr>
					<th align="center" valign="middle" class="oferta">1</th>
					<th align="center" valign="middle" class="oferta">2</th>
					<th align="center" valign="middle" class="oferta">3</th>
					<th align="center" valign="middle" class="oferta">4</th>
					<th align="center" valign="middle" class="oferta">5</th>
					<th align="center" valign="middle" class="oferta">6</th>
					<th align="center" valign="middle" class="oferta">7</th>
					<th align="center" valign="middle" class="oferta">8</th>
					<th align="center" valign="middle" class="oferta">9</th>
					<th align="center" valign="middle" class="oferta right">10</th>
				</tr>
				<tr>
					<td align="center" valign="middle" class="bold">Nr.<br>crt</td>
					<td align="center" valign="middle" class="bold">Art</td>
					<td align="center" valign="middle" class="bold">Cod<br>comanda</td>
					<td align="center" valign="middle" class="bold">Denumire produs</td>
					<td align="left" valign="middle" class="bold">Descriere</td>
					<td align="center" valign="middle" class="bold">Caracteristici<br>Tehnice</td>
					<td align="center" valign="middle" class="bold">Cant.</td>
					<td align="center" valign="middle" class="bold">Pret unitar<br>fara TVA</td>
					<td align="center" valign="middle" class="bold">Valoare<br>fara TVA</td>
					<td align="center" valign="middle"class="right bold">Imagine produs</td>
				</tr>
				
<?
			$i=0;
			while($products= tep_db_fetch_array($products_query)){
				
?>
				<tr>
					<td align="center" valign="middle"><?=$i+1 ?></td>
					<td align="center" valign="middle"><?=$products['nr_articol'] ?></td>
					<td align="center" valign="middle"><?=$products['cod_unic'] ?></td>
					<td align="center" valign="middle"><?=$products['products_name'] ?></td>
					<td align="left" valign="middle"><?=$products['Products_description'] ?></td>
					<td align="center" valign="middle"><?=$products['products_options_values_name'] ?></td>
					<td align="center" valign="middle"><input style="text-align:center" size="6" type="text" id="qty_<?=$i?>" value="1" onChange="calculate();"></td>
					<td align="center" valign="middle"><input class="align-right" size="8" type="text" id="price_<?=$i?>" value="<?=$products['price'] ?>" onChange="calculate();"></td>
					<td align="center" valign="middle"><input class="align-right" size="8" type="text" id="val_<?=$i?>" value=""></td>
					<td align="center" valign="middle" class="right"><img src="http://www.toolszone.ro/images/mari/<?=strtoupper($products['products_image'][0]).'/'.$products['products_image'] ?>" border="0" height="130" width="130"></td>
				</tr>
<?
				$i++;
			}
?>				
				<tr>
					<td align="center" valign="middle" colspan="6" style="border:0px">&nbsp;</td>
					<td align="left" valign="middle" colspan="2" class="bold">Total valoare fara TVA</td>
					<td align="right" valign="middle" class="bold" style="border-left:0px"><input class="align-right bold" size="10" type="text" id="total_fara_tva" value=""></td>
					<td align="center" valign="middle" style="border-left:0px; border-right:1px solid #000">&nbsp;</td>
				</tr>
				<tr style="color:red">
					<td align="center" valign="middle" colspan="6" style="border:0px">&nbsp;</td>
					<td align="left" valign="middle" class="bold" colspan="2">Discount <input class="align-right bold" style="color:red" size="1" type="text" id="disc" value="0" onChange="calculate();"> %</td>
					<td align="right" valign="middle" class="bold" style="border-left:0px"><input class="align-right bold" size="10" type="text" id="total_disc" value=""></td>
					<td align="center" valign="middle" style="border-left:0px; border-right:1px solid #000">&nbsp;</td>
				</tr>
				<tr>
					<td align="center" valign="middle" colspan="6" style="border:0px">&nbsp;</td>
					<td align="left" valign="middle" class="bold" colspan="2">Valoare cu discount</td>
					<td align="right" valign="middle" class="bold" style="border-left:0px"><input class="align-right bold" size="10" type="text" id="total_cu_disc" value=""></td>
					<td align="center" valign="middle" style="border-left:0px; border-right:1px solid #000">&nbsp;</td>
				</tr>
				<tr style="color:red">
					<td align="center" valign="middle" colspan="6" style="border:0px">&nbsp;</td>
					<td align="left" valign="middle" class="bold" colspan="2">Total valoare cu TVA</td>
					<td align="right" valign="middle" class="bold" style="border-left:0px"><input class="align-right bold" style="color:red" size="10" type="text" id="total_cu_tva" value=""></td>
					<td align="center" valign="middle" style="border-left:0px; border-right:1px solid #000">&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>	
			<textarea style="overflow: auto;border:0px; font-size:11px;font-family:Arial; width:700px; height: 80px" >Modalitate de plata: OP (vizat de banca) sau plata ramburs la primirea coletului.
Termen de valabilitate al ofertei: 30 zile cu posibilitate de prelungire.
In urma comenzii ferme se poate livra in termen de 10 zile.
Transportul este gratuit.</textarea>
		</td>
	</tr>
	<tr>
		<td align="right">	
			<input size="25" type="text" style="border:0px; font-weight:bold" value="Data, ">
		</td>
	</tr>
	<tr>
		<td align="left" style="padding-left:30px">	
			<input size="70" type="text" style="border:0px; font-weight:bold" value="Intocmit, Luiza Iliescu ">
		</td>
	</tr>
</table>
<script>

function calculate(){

	sum = 0;
	disc = document.getElementById('disc').value
	tva = 24;
	
	for(i=0; i<<?=$i?>; i++){
		
		val = document.getElementById('qty_'+i).value * document.getElementById('price_'+i).value;
		
		sum += val;
		
		document.getElementById('val_'+i).value = myRound(val);
	
	
	}
		
	document.getElementById('total_fara_tva').value = myRound(sum);
	document.getElementById('total_disc').value = myRound(sum*disc/100);
	document.getElementById('total_cu_disc').value = myRound(sum - sum*disc/100);
	document.getElementById('total_cu_tva').value = myRound((sum - sum*disc/100)+(sum - sum*disc/100)*tva/100);
	
}

function myRound(x){
	return Math.round(x*100)/100
}

calculate();
	
</script>
<?
}else{
echo "Eroare: Unul dintre coduri este gresit";
}

?>