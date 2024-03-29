<?  

chdir('includes/classes/phpxls');
require_once 'Writer.php';
chdir('../../..');
    
$workbook = new Spreadsheet_Excel_Writer();
$worksheet =& $workbook->addWorksheet('Produse');
//$worksheet->setColumn(0,0,0)
$workbook->setVersion(8);


 function xlsBOF() {
    echo pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);  
    return;
}

function xlsEOF() {
    echo pack("ss", 0x0A, 0x00);
    return;
}

function xlsWriteNumber($Row, $Col, $Value) {
    echo pack("sssss", 0x203, 14, $Row, $Col, 0x0);
    echo pack("d", $Value);
    return;
}

function xlsWriteLabel($Row, $Col, $Value ) {
  global  $workbook,$worksheet;

  $format_reg =& $workbook->addFormat();
  $format_reg->setColor('black');
  $format_reg->setFontFamily('Arial');
  $format_reg->setSize(8);
  
  $worksheet->write($Row, $Col, $Value, $format_reg);

return;
} 
function xlsWriteHeader($Row, $Col, $Value ) { 
  global  $workbook,$worksheet;

  $format_und =& $workbook->addFormat();
  $format_und->setBottom(2);//thick
  $format_und->setBold();
  $format_und->setColor('black');
  $format_und->setFontFamily('Arial');
  $format_und->setSize(8);
    
  $worksheet->write($Row, $Col, $Value, $format_und);

return;
} 
function calcPrice($price, $delta, $op){

    if($op == "+")
      return  $price + $delta;
    else              
      return  $price - $delta;

}

require('includes/application_top.php');
require(DIR_WS_CLASSES . 'currencies.php');
$currencies = new currencies();
$produs = $_GET['pID'];


$product_info_query = tep_db_query("select p.products_amprenta, p.products_specials, p.products_image_2, p.products_pdf, p.nr_articol, p.products_id, pd.products_name, pd.products_seo_desc, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url,p.products_youtube, p.products_price, products_price_2, products_price_3, p.cant_pret_2, p.cant_pret_3, products_um, is_set, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id, tm.manufacturers_name, tm.manufacturers_image from " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " tm on p.manufacturers_id=tm.manufacturers_id, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$produs . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
$product_info = tep_db_fetch_array($product_info_query);

$products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$produs . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "' order by popt.products_options_name");
$products_options_name = tep_db_fetch_array($products_options_name_query);

    // Send Header
  if(1)
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");;
    header("Content-Disposition: attachment;filename=$produs.xls "); // a1&#129;a&#184;Ya1&#8240;a&#184;&#167;a&#184;&#8482;a&#184;&#181;a1^a&#184;&#129;a1&#8225;a&#184;&#352;a&#184;&#183;a1^a&#184;&#173;a1&#8222;a&#184;Ya&#184;Ya1O
    header("Content-Transfer-Encoding: binary ");

    // XLS Data Cell

	xlsWriteHeader(0,0,"NR ARTICOL");
	xlsWriteHeader(0,1,"COD UNIC");
	xlsWriteHeader(0,2,"ID PRODUCATOR");
	xlsWriteHeader(0,3,"NUME PRODUS");
	xlsWriteHeader(0,4,"UNITATE DE MASURA");
	xlsWriteHeader(0,5,$products_options_name['products_options_name']);
	xlsWriteHeader(0,6,"LUNGIME (MM)");
	xlsWriteHeader(0,7,"GREUTATE (G)");
	xlsWriteHeader(0,8,"PRET UNITAR");
	xlsWriteHeader(0,9,"PRET GRUP 1");
	xlsWriteHeader(0,10,"PRET GRUP 2");
	xlsWriteHeader(0,11,"PRET SPECIAL");
	xlsWriteHeader(0,12,"NR GRUP 1");
	xlsWriteHeader(0,13,"NR GRUP 2");
	xlsWriteHeader(0,14,"SPECIALE");
	xlsWriteHeader(0,15,"DESCRIERE");
	xlsWriteHeader(0,16,"POZA");
	xlsWriteHeader(0,17,"NUME PDF");
	xlsWriteHeader(0,18,"COMPONENTA TRUSA");
	xlsWriteHeader(0,19,"AMPRENTA");
	xlsWriteHeader(0,20,"VIDEO");
	xlsWriteHeader(0,21,"SEO_DESC");

	$xlsRow = 1;

	$query = "select set_cod_unic, products_set_no from products_set where product_id = '" . (int)$produs . "'";
	$sets = tep_db_query($query);
	
	$sets_concat = "";
	
  while($set = tep_db_fetch_array($sets)){
    if($set['products_set_no']>1)
       $sets_concat .= $set['products_set_no']."?".$set['set_cod_unic'].","; 
    else
       $sets_concat .= $set['set_cod_unic'].",";
  }
  
	$query = "select specials_new_products_price from specials where products_id = '" . (int)$produs . "'";
	$specials = tep_db_query($query);
	$special = tep_db_fetch_array($specials);

	$query = "select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix, pa.options_values_price_2 , pa.price_prefix_2 , pa.options_values_price_3 , pa.options_values_price_special , pa.price_prefix_3 , pa.price_prefix_special, pa.cod_unic, pa.um, pa.car_teh_1 , pa.car_teh_2, pa.products_attributes_id from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . (int)$produs . "' and pa.options_id = '" . (int)$products_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . (int)$languages_id . "' order by um DESC, products_attributes_id ASC";
  $products_options_query = tep_db_query($query);
	
	while ($products_options = tep_db_fetch_array($products_options_query)) {
		
		$price_1 = calcPrice($product_info['products_price'],$products_options['options_values_price'],$products_options['price_prefix']);
		$price_2 = calcPrice($product_info['products_price_2'],$products_options['options_values_price_2'],$products_options['price_prefix_2']);
		$price_3 = calcPrice($product_info['products_price_3'],$products_options['options_values_price_3'],$products_options['price_prefix_3']);
		$price_special = calcPrice($special['specials_new_products_price'],$products_options['options_values_price_special'],$products_options['price_prefix_special']);
			
		xlsWriteLabel($xlsRow,0,$xlsRow==1?$product_info['nr_articol']:"");
		xlsWriteLabel($xlsRow,1,$products_options['cod_unic']);
		xlsWriteLabel($xlsRow,2,$xlsRow==1?$product_info['manufacturers_id']:"");
		xlsWriteLabel($xlsRow,3,$xlsRow==1?$product_info['products_name']:"");
		
		xlsWriteLabel($xlsRow,4,$products_options['um']);
		xlsWriteLabel($xlsRow,5,$products_options['products_options_values_name']);
		xlsWriteLabel($xlsRow,6,$products_options['car_teh_1']);
		xlsWriteLabel($xlsRow,7,$products_options['car_teh_2']);
    xlsWriteLabel($xlsRow,8,$price_1);
		
		if($price_1 != $price_2)       xlsWriteLabel($xlsRow,9,$price_2);
		if($price_1 != $price_3)       xlsWriteLabel($xlsRow,10,$price_3);
		if($price_1 != $price_special) xlsWriteLabel($xlsRow,11,$price_special);
		
		xlsWriteLabel($xlsRow,12,$xlsRow==1?$product_info['cant_pret_2']:"");
		xlsWriteLabel($xlsRow,13,$xlsRow==1?$product_info['cant_pret_3']:"");
		xlsWriteLabel($xlsRow,14,$xlsRow==1?"".$product_info['products_specials']."":"");
		xlsWriteLabel($xlsRow,15,$xlsRow==1?$product_info['products_description']:"");
		xlsWriteLabel($xlsRow,16,$xlsRow==1?($product_info['products_image'].($product_info['products_image_2']!=""?",".$product_info['products_image_2']:"")):"");
    xlsWriteLabel($xlsRow,17,$xlsRow==1?$product_info['products_pdf']:"");
		xlsWriteLabel($xlsRow,18,$xlsRow==1?$sets_concat:"");
		xlsWriteLabel($xlsRow,19,$xlsRow==1?$product_info['products_amprenta']:"");
		xlsWriteLabel($xlsRow,20,$xlsRow==1?$product_info['products_youtube']:"");
		xlsWriteLabel($xlsRow,21,$xlsRow==1?$product_info['products_seo_desc']:"");

		$xlsRow ++;
	}

$workbook->send($product_info['nr_articol'].".xls");
$workbook->close();       
?>