<?php 

header("Content-type: text/plain"); 
header("Content-Disposition: attachment; filename=shopmania.txt"); 
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
	
		$categorie_id = $products['categories_id'];
		$categorie_nume = $products['categories_name'];
		$nume_produs = $products['products_name'];
		$cod_produs = $products['products_id'];
		$descriere  = $products['Products_description']; 
		$link_poza= 'http://www.toolszone.ro/images/mari/'.$products['products_image'];
		$nr_articol= $products['nr_articol'];
		$keyword2= $products['cod_unic'];
		$keyword3= $products['manufacturers_name'];
		$producator= $products['manufacturers_name'];
		$pret = $products['price'];
		$moneda= "1";
		
		echo	$categorie_nume.'|';
		echo $producator.'|';
		echo $nr_articol.'|';		
		echo	$cod_produs.'|';
		echo	$nume_produs.'|';
		echo	$descriere.'|';
		echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id='.$cod_produs).'|';
		echo	$link_poza.'|';
		echo number_format(($pret*1.24),2,'.','').'|';
		echo $moneda.'|';
		echo	'0|';
		echo	'In stoc||';
		echo "\r\n";
	}

?>