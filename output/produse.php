<?php 


	require('/home/toolz/public_html/includes/application_top.php');
	  
	$products_query = tep_db_query("SELECT pc.categories_id, cd.categories_name, pd.products_name,p.products_id, ".
			" pd.Products_description,p.products_image, ".
			" p.nr_articol,pa.cod_unic, po.products_options_values_name, ". 
			" m.manufacturers_name, ".
			" (CASE  ".
			" WHEN pa.price_prefix = '+' THEN p.products_price + pa.options_values_price ". 
			"  ELSE p.products_price - pa.options_values_price ". 
			"  END) as price ".
			"  FROM products p  ".
			"  LEFT JOIN products_attributes pa on p.products_id = pa.products_id ". 
			"  LEFT JOIN products_description pd on p.products_id = pd.products_id ". 
			"  LEFT JOIN products_options_values po on po.products_options_values_id = pa.options_values_id ". 
			" INNER JOIN products_to_categories pc on pc.products_id = p.products_id ".
			" INNER JOIN categories_description cd on cd.categories_id = pc.categories_id ".
			" INNER JOIN manufacturers m on m.manufacturers_id = p.manufacturers_id");
		  
  
  
	$i=0;
	while($products= tep_db_fetch_array($products_query)){  
	
		$categorie_id = $products['categories_id'];
		
		echo $products['categories_id'];
		
		$categorie_nume = $products[1];
		$nume_produs = $products[2];
		$cod_produs = $products[3];
		$descriere  = $products[4]; 
		$link_poza= $products[5];
		$keyword1= $products[6];
		$keyword2= $products[7];
		$keyword3= $products[8];
		$producator= $products[9];
		$pret= $products[10];
		$moneda= "1";
		/*
		
		echo	'"'.base64_encode($categorie_id).'";';
		echo	'"'.base64_encode($categorie_nume).'";';
		echo	'"'.base64_encode($nume_produs).'";';
		echo	'"'.base64_encode($cod_produs).'";';
		echo	'"'.base64_encode($descriere).'";';
		echo	'"'.base64_encode($link_poza).'";';
		echo	'"'.base64_encode($keyword1).'";';
		echo	'"'.base64_encode($keyword2).'";';
		echo	'"'.base64_encode($keyword3).'";';
		echo '"'.base64_encode($producator).'";';
		echo '"'.base64_encode($pret).'";';
		echo '"'.base64_encode($moneda).'";';
		echo "\r\n";*/
	}

?>