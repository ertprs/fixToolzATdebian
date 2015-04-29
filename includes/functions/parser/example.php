<?php
// Test CVS

require_once 'Excel/reader.php';
require_once 'product.php';
require_once '../admin/includes/functions/database.php';

define('TABLE_PRODUCTS', 'products');

// ExcelFile($filename, $encoding);
$data = new Spreadsheet_Excel_Reader();



$data->read('produse.xls');

$products = array();

$pCount = 0;
$spCount = 0;

for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
	
	$subproduct = new SubProduct();
	
	if($data->sheets[0]['cells'][$i][1] != ""){
		if($subProducts!=null && sizeof($subProducts)>0){
			$product->subProducts = $subProducts;
			$products[$pCount] = $product;	
			$pCount++;
		}	
		$subProducts = array();	
		
		$spCount = 0;
		$product = new Product();
		$product->nrArticol = $data->sheets[0]['cells'][$i][1];
		$product->idProducator = $data->sheets[0]['cells'][$i][3];
		$product->nume = $data->sheets[0]['cells'][$i][4];
		$product->unitateMasura = $data->sheets[0]['cells'][$i][5];
		$product->cantPret2 = $data->sheets[0]['cells'][$i][13];
		$product->cantPret3 = $data->sheets[0]['cells'][$i][14];
		$product->speciale = $data->sheets[0]['cells'][$i][15];
		$product->descriere = $data->sheets[0]['cells'][$i][16];
		$product->poza = $data->sheets[0]['cells'][$i][17];
		$product->pdf = $data->sheets[0]['cells'][$i][18];	
		$product->trusa = $data->sheets[0]['cells'][$i][19];	
	}	
	
	$subproduct->codUnic = $data->sheets[0]['cells'][$i][2];
	$subproduct->carTeh = $data->sheets[0]['cells'][$i][6];
	$subproduct->lungime = $data->sheets[0]['cells'][$i][7];
	$subproduct->greutate = $data->sheets[0]['cells'][$i][8];
	$subproduct->pret1 = $data->sheets[0]['cells'][$i][10];
	$subproduct->pret2 = $data->sheets[0]['cells'][$i][11];
	$subproduct->pret3 = $data->sheets[0]['cells'][$i][12];
		
	$subProducts[$spCount] = $subproduct;
	$spCount++;
}
for($i=0;$i<sizeof($products);$i++){
	echo(tep_db_prepare_input($products[$i]->subProducts[0]->pret1));
    $sql_data_array = array(	  'products_price' => tep_db_prepare_input($products[$i]->subProducts[0]->pret1),
                                  'products_date_available' => 'now()',
                                  'manufacturers_id' => (int)tep_db_prepare_input($products[$i]->idProducator),
								  'products_image' => tep_db_prepare_input($products[$i]->poza),
								  'products_pdf' => tep_db_prepare_input($products[$i]->pdf));
								  
        $insert_sql_data = array('products_date_added' => 'now()');
        
        $sql_data_array = array_merge($sql_data_array, $insert_sql_data);
        
        print_r($sql_data_array);
		//tep_db_perform(TABLE_PRODUCTS, $sql_data_array);
		
		tep_db_query("insert into " . TABLE_PRODUCTS . " (products_price, products_date_available, manufacturers_id, products_image, products_pdf)" .
					" values ('" . tep_db_prepare_input($products[$i]->subProducts[0]->pret1) . "'," .
							" 'now()'," .
							" '" . (int)tep_db_prepare_input($products[$i]->idProducator) . "'," .
							" '" . tep_db_prepare_input($products[$i]->poza) . "'," .
							" '" . tep_db_prepare_input($products[$i]->pdf) . "')");
		
}
print_r($products);
?>
