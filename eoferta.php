<?php 

header("Content-type: text/plain");
header("Content-Disposition: attachment; filename=eoferta.csv");
header("Pragma: no-cache"); 
header("Expires: 0"); 


	require('/home/toolz/public_html/includes/application_top.php');
	/*  
	$products_query = tep_db_query("SELECT pc.categories_id, cd.categories_name, pd.products_name,p.products_id,
			pd.Products_description,p.products_image,
			p.nr_articol,pa.cod_unic, po.products_options_values_name, 
			m.manufacturers_name,
			(CASE 
			WHEN pa.price_prefix = '+' THEN p.products_price + pa.options_values_price 
			 ELSE p.products_price - pa.options_values_price 
			 END) as price
			 FROM products p 
			 LEFT JOIN products_attributes pa on p.products_id = pa.products_id 
			 LEFT JOIN products_description pd on p.products_id = pd.products_id 
			 LEFT JOIN products_options_values po on po.products_options_values_id = pa.options_values_id 
			INNER JOIN products_to_categories pc on pc.products_id = p.products_id
			INNER JOIN categories_description cd on cd.categories_id = pc.categories_id
			INNER JOIN manufacturers m on m.manufacturers_id = p.manufacturers_id");
	*/

function esc($value) {
    $value = str_replace('"', '""', $value); // First off escape all " and make them ""
    if(preg_match('/,/', $value) or preg_match("/\n/", $value) or preg_match('/"/', $value)) { // Check if I have any commas or new lines
        return '"'.$value.'"'; // If I have new lines or commas escape them
    } else {
        return $value; // If no new lines or commas just return the value
    }
}
	  
  $products_query = tep_db_query("SELECT pc.categories_id, cd.categories_name, pd.products_name,p.products_id,
			pd.Products_description,p.products_image,
			p.nr_articol,p.cod_unic,
			m.manufacturers_name,
			 p.products_price as price
			 FROM products p 
			 LEFT JOIN products_description pd on p.products_id = pd.products_id 
			INNER JOIN products_to_categories pc on pc.products_id = p.products_id
			INNER JOIN categories_description cd on cd.categories_id = pc.categories_id
			INNER JOIN manufacturers m on m.manufacturers_id = p.manufacturers_id");
  
	$i=0;
	while($products= tep_db_fetch_array($products_query)){  
	
		$categorie_id = esc($products['categories_id']);
		$categorie_nume = esc($products['categories_name']);
		$nume_produs = esc($products['products_name']);
		$cod_produs = esc($products['products_id']);
		$descriere  = esc($products['Products_description']);
		$link_poza= esc('http://www.toolszone.ro/images/mari/'.$products['products_image']);
		$nr_articol= esc($products['nr_articol']);
		$keyword2= esc($products['cod_unic']);
		$keyword3= esc($products['manufacturers_name']);
		$producator= esc($products['manufacturers_name']);
		$pret = esc($products['price']);

		
		print_row($out = array($categorie_nume,$producator,$nr_articol,$cod_produs,$nume_produs,$descriere,tep_href_link(FILENAME_PRODUCT_INFO, 'products_id='.$cod_produs),$link_poza,number_format(($pret*1.24),2,'.','')));

	}



    function print_row($row){

        echo implode(",", $row).',lei' . "\r\n";
    }

?>