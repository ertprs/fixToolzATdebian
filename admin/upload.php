<?php

/*

Format tabel excel cu produse

 1 - NR ARTICOL
 2 - COD UNIC
 3 - ID PRODUCATOR
 4 - NUME PRODUS
 5 - UNITATE DE MASURA
 6 - CARACTERISTICI TEHNICE 1
 7 - LUNGIME (MM)
 8 - GREUTATE (G)
 9 - PRET UNITAR
10 - PRET GRUP 1
11 - PRET GRUP 2
12 - PRET SPECIAL
13 - NR GRUP 1
14 - NR GRUP 2
15 - SPECIALE
16 - DESCRIERE
17 - POZA
18 - NUME PDF (nume fisiere despartite prin virgula)
19 - COMPONENTA TRUSA (coduri despartite prin virgula si numarul din trusa despartit prin ? ex: 20?23432 adq 20 de produse de tipul 23432)
20 - AMPRENTA
21 - LINK YOUTUBE
22 - descriere SEO
 */

$qlog = array();  //tinem logurile tranzactiilor

$action1 = (isset($_GET['action']) ? $_GET['action'] : '');
if($action1 == "upload"){

    require_once 'includes/functions/excelParser/excel_reader2.php';
    require_once 'includes/classes/product.php';
    require_once 'includes/functions/database.php';

    //daca xls-ul este versiunea noua nu ne mai intoarcem!
    $new_version = false;
    require 'mycode/upload_v10.php';

// ExcelFile($filename, $encoding);
    $data = new Spreadsheet_Excel_Reader();


    $target_path = "../../tmp/";

    $target_path = $target_path . basename( $_FILES['uploadedfile']['name']);

    //if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
    if(!$new_version) {

//$data->read($target_path);

        $data = new Spreadsheet_Excel_Reader($target_path,true,"ISO-8859-2");

        $products = array();

        $pCount = 0;
        $spCount = 0;
        $validXLS = true;

        for($i = 1; $i<=22; $i++){
            if ($data->val(1,$i)==null || $data->val(1,$i)==""){
                echo $i;
                $validXLS=false;
            }
        }

        if($validXLS){

            $carTeh = ucfirst(strtolower($data->val(1,6)));

            if (strtolower($carTeh) == strtolower("CARACTERISTICI TEHNICE 1")){
                $carTeh = "Dimensiune";
            }

            for ($i = 2; $i <= $data->rowcount(0); $i++) {

                $subproduct = new SubProduct();

                if($data->val($i,1) != ""){
                    if($subProducts!=null && sizeof($subProducts)>0){
                        $product->subProducts = $subProducts;
                        $products[$pCount] = $product;
                        $pCount++;
                    }
                    $subProducts = array();
                    $spCount = 0;
                    $product = new Product();
                    $product->nrArticol 	= $data->val($i,1);
                    $product->idProducator 	= $data->val($i,3);
                    $product->nume 			= $data->val($i,4);
                    $product->unitateMasura = $data->val($i,5);
                    $product->cantPret2 	= $data->val($i,13)==""?1:$data->val($i,13);
                    $product->cantPret3 	= $data->val($i,14)==""?1:$data->val($i,14);
                    $product->speciale 		= $data->val($i,15);
                    $product->descriere 	= $data->val($i,16);
                    $product->poza 			= $data->val($i,17);
                    $product->pdf 			= $data->val($i,18);
                    $product->amprenta 		= $data->val($i,20);
                    if($data->val($i,19) != ""){

                        $trusa = trim($data->val($i,19), ", ");
                        $trusa = trim($trusa, " ,");
                        $trusa = trim($trusa, " , ");

                        $product->trusa = explode(",",$trusa);
                    }
                    //echo $product->nume;
                    if($data->val($i,17) != ""){

                        $poze = trim($data->val($i,17), ", ");
                        $poze = trim($poze, " ,");
                        $poze = trim($poze, " , ");

                        $product->poze = explode(",",$poze);
                    }
                    $product->uTubeLink	= $data->val($i,21);
                    $product->seoDesc   = $data->val($i,22); //{ifcts}
                }

                if($data->val($i,2) != ""){



                    $subproduct->codUnic 	= $data->val($i,2);
                    $subproduct->unitateMasura = $data->val($i,5);
                    $subproduct->carTeh 	= $data->val($i,6);
                    $subproduct->carTeh1 	= $data->val($i,7);
                    $subproduct->carTeh2 	= $data->val($i,8);
                    $subproduct->pret1 		= $data->val($i,9);
                    $subproduct->pret2 		= $data->val($i,10)==""?$subproduct->pret1:$data->val($i,10);
                    $subproduct->pret3 		= $data->val($i,11)==""?$subproduct->pret1:$data->val($i,11);
                    $subproduct->pretSpecial= $data->val($i,12)==""?$subproduct->pret1:(double)$data->val($i,12);
                    $subProducts[$spCount] = $subproduct;
                    $spCount++;

                }
            }
            $product->subProducts = $subProducts;
            $products[$pCount] = $product;

            $current_category_id = $_GET['cID'];
            $language_id = 1;
            /*echo '<pre>';
            var_dump($products);echo '</pre>';die();*/

            //add products to db
            $error = "";
            for($i=0;$i<sizeof($products);$i++){

                //if only nrArticol then link product into category

                if($products[$i]->nrArticol!=null && $products[$i]->nrArticol!="" &&
                    ($products[$i]->idProducator==null || $products[$i]->idProducator=="") &&
                    ($products[$i]->nume==null || $products[$i]->nume=="") &&
                    ($products[$i]->unitateMasura==null || $products[$i]->unitateMasura=="")){

                    $check_query = tep_db_query("select products_id  from " . TABLE_PRODUCTS . " where nr_articol like '" . $products[$i]->nrArticol . "' ");
                    $check = tep_db_fetch_array($check_query);

                    if ($check==null) {
                        //$error = "Nu exista in baza de date un produs cu numarul de articol: ".$products[$i]->nrArticol;
                        $check_query_3 = tep_db_query("select *  from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id like '" . (int)$current_category_id . "' and nr_articol like '" . $products[$i]->nrArticol . "' ");
                        $check_3 = tep_db_fetch_array($check_query_3);
                        if ($check_3==null) {
                            tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (categories_id, nr_articol) values ('" . (int)$current_category_id . "', '" . $products[$i]->nrArticol . "')");
                        }
                    }else{

                        $check_query_2 = tep_db_query("select products_id  from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id like '" . (int)$check['products_id'] . "' and categories_id like '" . (int)$current_category_id . "' ");
                        $check_2 = tep_db_fetch_array($check_query_2);
                        if ($check_2==null) {
                            //echo "insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . (int)$check['products_id'] . "', '" . (int)$current_category_id . "')";
                            tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id, nr_articol) values ('" . (int)$check['products_id'] . "', '" . (int)$current_category_id . "', '" . $products[$i]->nrArticol . "')");
                        }
                    }
                }else{
                    $products_image = new upload();

                    if($products[$i]->poze[0]!="" && file_exists(DIR_FS_CATALOG_UPLOADED_IMAGES.$products[$i]->poze[0])){
                        $spDir = strtoupper($products[$i]->poze[0][0]).'/';
                        $products_image->set_thumb($products[$i]->poze[0], DIR_FS_CATALOG_UPLOADED_IMAGES, DIR_FS_CATALOG_IMAGES."mici/".$spDir, 90, 50,50);
                        $products_image->set_thumb($products[$i]->poze[0], DIR_FS_CATALOG_UPLOADED_IMAGES, DIR_FS_CATALOG_IMAGES."mari/".$spDir, 90, 200,200);

                        $products_image->unlink();
                    }

                    if($products[$i]->poze[1] != null && $products[$i]->poze[1]!="" && file_exists(DIR_FS_CATALOG_UPLOADED_IMAGES.$products[$i]->poze[1])){
                        $spDir = strtoupper($products[$i]->poze[1][0]).'/';
                        $products_image->set_thumb($products[$i]->poze[1], DIR_FS_CATALOG_UPLOADED_IMAGES, DIR_FS_CATALOG_IMAGES."mici/".$spDir, 90, 50,50);
                        $products_image->set_thumb($products[$i]->poze[1], DIR_FS_CATALOG_UPLOADED_IMAGES, DIR_FS_CATALOG_IMAGES."mari/".$spDir, 90, 200,200);

                        $products_image->unlink();
                    }

                    if($products[$i]->poze[2] != null && $products[$i]->poze[2]!="" && file_exists(DIR_FS_CATALOG_UPLOADED_IMAGES.$products[$i]->poze[2])){
                        $spDir = strtoupper($products[$i]->poze[2][0]).'/';
                        $products_image->set_thumb($products[$i]->poze[2], DIR_FS_CATALOG_UPLOADED_IMAGES, DIR_FS_CATALOG_IMAGES."mici/".$spDir, 90, 50,50);
                        $products_image->set_thumb($products[$i]->poze[2], DIR_FS_CATALOG_UPLOADED_IMAGES, DIR_FS_CATALOG_IMAGES."mari/".$spDir, 90, 200,200);

                        $products_image->unlink();
                    }

                    if($products[$i]->poze[3] != null && $products[$i]->poze[3]!="" && file_exists(DIR_FS_CATALOG_UPLOADED_IMAGES.$products[$i]->poze[3])){
                        $spDir = strtoupper($products[$i]->poze[3][0]).'/';
                        $products_image->set_thumb($products[$i]->poze[3], DIR_FS_CATALOG_UPLOADED_IMAGES, DIR_FS_CATALOG_IMAGES."mici/".$spDir, 90, 50,50);
                        $products_image->set_thumb($products[$i]->poze[3], DIR_FS_CATALOG_UPLOADED_IMAGES, DIR_FS_CATALOG_IMAGES."mari/".$spDir, 90, 200,200);

                        $products_image->unlink();
                    }

                    if($products[$i]->amprenta != null && $products[$i]->amprenta!="" && file_exists(DIR_FS_CATALOG_UPLOADED_IMAGES.$products[$i]->amprenta)){
                        $spDir = strtoupper($products[$i]->amprenta[0]).'/';
                        $products_image->set_thumb($products[$i]->amprenta, DIR_FS_CATALOG_UPLOADED_IMAGES, DIR_FS_CATALOG_IMAGES."mici/".$spDir, 90, 50,50);

                        //$products_image->unlink();
                    }


                    //set the product array
                    $sql_data_array = array(	'nr_articol' => tep_db_prepare_input($products[$i]->nrArticol),
                        'cod_unic' => tep_db_prepare_input($products[$i]->subProducts[0]->codUnic),
                        'is_set' => $products[$i]->trusa==null?"0":"1",
                        'products_price' => (double)tep_db_prepare_input($products[$i]->subProducts[0]->pret1),
                        'products_price_2' => (double)tep_db_prepare_input($products[$i]->subProducts[0]->pret2),
                        'products_price_3' => (double)tep_db_prepare_input($products[$i]->subProducts[0]->pret3),
                        'cant_pret_2' => (int)tep_db_prepare_input($products[$i]->cantPret2),
                        'cant_pret_3' => (int)tep_db_prepare_input($products[$i]->cantPret3),
                        'products_um' => tep_db_prepare_input($products[$i]->unitateMasura),
                        'products_status' => ($products[$i]->subProducts[0]->pret1==null || $products[$i]->subProducts[0]->pret1=="")?'0':'1',
                        'products_date_available' => 'now()',
                        'manufacturers_id' => (int)tep_db_prepare_input($products[$i]->idProducator),
                        'products_image' => tep_db_prepare_input($products[$i]->poze[0]),
                        'products_image_2' => tep_db_prepare_input($products[$i]->poze[1]),
                        'products_image_3' => tep_db_prepare_input($products[$i]->poze[2]),
                        'products_image_4' => tep_db_prepare_input($products[$i]->poze[3]),
                        'products_date_added' => 'now()',
                        'products_specials' => trim(tep_db_prepare_input($products[$i]->speciale)),
                        'products_amprenta' => tep_db_prepare_input($products[$i]->amprenta),
                        'products_pdf' => tep_db_prepare_input($products[$i]->pdf),
                        'products_youtube' => tep_db_prepare_input($products[$i]->uTubeLink));

                    //check if product already in db
                    $check_query = tep_db_query("select P.products_id  from " . TABLE_PRODUCTS . " P JOIN " . TABLE_PRODUCTS_TO_CATEGORIES . " PC ON P.products_id=PC.products_id where P.nr_articol like '" . $products[$i]->nrArticol . "' and cod_unic like '" . tep_db_prepare_input($products[$i]->subProducts[0]->codUnic) . "' and PC.categories_id = " . $current_category_id);
                    $check = tep_db_fetch_array($check_query);

                    if ($check==null) {
                        tep_db_perform(TABLE_PRODUCTS, $sql_data_array);

                        $products_id = tep_db_insert_id();

                        $query = "insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . (int)$products_id . "', '" . (int)$current_category_id . "')";
                        tep_db_query("UPDATE " . TABLE_PRODUCTS_TO_CATEGORIES . " SET products_id = '" . $products_id . "'WHERE nr_articol='" . $products[$i]->nrArticol . "'");

                        tep_db_query($query);
                    }else{
                        $products_id = $check['products_id'];
                        tep_db_perform(TABLE_PRODUCTS, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "' and cod_unic like '" . tep_db_prepare_input($products[$i]->subProducts[0]->codUnic) . "'");
                    }
                    //add products to product_Set
                    if($products[$i]->trusa!=null){

                        $del_query = "delete  from " . TABLE_PRODUCTS_SET . " where product_id = '" . $products_id . "'";
                        tep_db_query($del_query);

                        for($l=0;$l<sizeof($products[$i]->trusa);$l++){

                            $component = explode('?', $products[$i]->trusa[$l]);

                            if(sizeof($component)>1){
                                $no = $component[0];
                                $cod = $component[1];
                            } else {
                                $no = 1;
                                $cod = $products[$i]->trusa[$l];
                            }

                            $query = "insert into " . TABLE_PRODUCTS_SET . " (product_id, set_cod_unic, products_set_no) values ('" . $products_id . "', '" . $cod . "', '" . $no . "')";
                            tep_db_query($query);

                        }
                    }
                    // add price to products specials
                    $sql_data_array = array('products_id' => $products_id,
                        'specials_new_products_price' => $products[$i]->subProducts[0]->pretSpecial,
                        'expires_date' => null,
                        'status' => stristr($products[$i]->speciale, '01')?'1':'0');

                    $check_query = tep_db_query("select specials_id from " . TABLE_SPECIALS . " where products_id = '" . $products_id . "'");
                    $check = tep_db_fetch_array($check_query);
                    if ($check==null) {
                        tep_db_perform(TABLE_SPECIALS, $sql_data_array);
                        $products_specials_id = tep_db_insert_id();
                    }else{
                        $products_specials_id = $check['specials_id'];
                        tep_db_perform(TABLE_SPECIALS, $sql_data_array, 'update', "specials_id = " . (int)$products_specials_id);
                    }

                    //set the product description and name
                    $sql_data_array = array('products_name' => tep_db_prepare_input($products[$i]->nume),
                        'products_description' => tep_db_prepare_input($products[$i]->descriere),
                        'products_seo_desc'    => tep_db_prepare_input($products[$i]->seoDesc),
                        'products_id' => $products_id,
                        'language_id' => $language_id);
                    //var_dump($sql_data_array); die();
                    //check if product description and name already in db
                    $check_query = tep_db_query("select products_id from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id =" . $products_id . " and language_id =" . $language_id . " ");
                    $check = tep_db_fetch_array($check_query);
                    if ($check==null) {
                        tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array);
                    }else{
                        tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "' and language_id = '" . (int)$language_id . "'");
                    }
                    // add product options
                    $check_query = tep_db_query("select products_options_id from " . TABLE_PRODUCTS_OPTIONS . " where products_options_name like '" . $carTeh . "'");
                    $check = tep_db_fetch_array($check_query);
                    if ($check==null) {
                        $query = "insert into " . TABLE_PRODUCTS_OPTIONS . " (products_options_id, language_id, products_options_name) values (null, '" . $language_id . "', '" . $carTeh . "')";
                        tep_db_query($query);
                        $products_options_id = tep_db_insert_id();
                    }else{
                        $products_options_id = $check['products_options_id'];
                    }

                    tep_db_query("DELETE FROM products_attributes WHERE products_id='".$products_id."'");

                    // add product options values
                    if(sizeof($products[$i]->subProducts)>0){

                        for($m=0;$m<sizeof($products[$i]->subProducts);$m++){

                            $check_query = tep_db_query("select products_options_values_id from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_name like '" . $products[$i]->subProducts[$m]->carTeh . "'");
                            $check = tep_db_fetch_array($check_query);
                            if ($check==null) {
                                $query = "insert into " . TABLE_PRODUCTS_OPTIONS_VALUES . " (products_options_values_id, language_id, products_options_values_name) values (null, '" . $language_id . "', '" . $products[$i]->subProducts[$m]->carTeh . "')";
                                tep_db_query($query);
                                $products_options_values_id = tep_db_insert_id();
                            }else{
                                $products_options_values_id = $check['products_options_values_id'];
                            }


                            // add products options values to products options
                            $check_query = tep_db_query("select products_options_values_to_products_options_id from " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " where products_options_id = '" . $products_options_id . "' and products_options_values_id  = '" . $products_options_values_id . "' ");
                            $check = tep_db_fetch_array($check_query);
                            if ($check==null) {
                                $query = "insert into " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " (products_options_values_to_products_options_id, products_options_id, products_options_values_id) values (null, '" . $products_options_id . "', '" . $products_options_values_id . "')";
                                tep_db_query($query);
                                $products_options_values_to_products_options_id = tep_db_insert_id();
                            }else{
                                $products_options_values_to_products_options_id = $check['products_options_values_to_products_options_id'];
                            }

                            $pret1 = calcPrice($products[$i]->subProducts[$m]->pret1, $products[$i]->subProducts[0]->pret1);
                            $pret2 = calcPrice($products[$i]->subProducts[$m]->pret2, $products[$i]->subProducts[0]->pret2);
                            $pret3 = calcPrice($products[$i]->subProducts[$m]->pret3, $products[$i]->subProducts[0]->pret3);
                            $pretSpecial = calcPrice($products[$i]->subProducts[$m]->pretSpecial, $products[$i]->subProducts[0]->pretSpecial);



                            //       echo $products_imag


                            // add products to products atributes
                            $sql_data_array = array('products_id' => $products_id,
                                'options_id' => $products_options_id,
                                'options_values_id' => $products_options_values_id,
                                'cod_unic' => $products[$i]->subProducts[$m]->codUnic,
                                'um' => $products[$i]->subProducts[$m]->unitateMasura,
                                'car_teh_1' => $products[$i]->subProducts[$m]->carTeh1,
                                'car_teh_2' => $products[$i]->subProducts[$m]->carTeh2,
                                'options_values_price' => $pret1["pret"],
                                'options_values_price_2' => $pret2["pret"],
                                'options_values_price_3' => $pret3["pret"],
                                'options_values_price_special' => $pretSpecial["pret"],
                                'price_prefix' => $pret1["operator"],
                                'price_prefix_2' => $pret2["operator"],
                                'price_prefix_3' => $pret3["operator"],
                                'price_prefix_special' => $pretSpecial["operator"]);

                            /*
                                            $check_query = tep_db_query("select products_attributes_id  from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . $products_id . "' and options_id = '" . $products_options_id . "' and options_values_id = '" . $products_options_values_id . "' ");
                                            $check = tep_db_fetch_array($check_query);

                                            if ($check!=null) {

                                            }
                            */
                            tep_db_perform(TABLE_PRODUCTS_ATTRIBUTES, $sql_data_array);
                            $products_options_values_to_products_options_id = tep_db_insert_id();

                        }

                    }
                }
            }
        }else{
            echo "Eroare: Numarul de coloane este invalid";
        }
    }

    //apelam scriptul care corecteaza calea imaginilor.
    require_once('./librarian.php');

    if (0) {
        // facem pachet si trimitem
        $url = 'http://www.toolszone.info/upload/up.php';
        $data = array('msg' => urlencode(serialize($qlog)), 'hash' => 'hash');

        // use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data),
            ),
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        //if($result != 'OK') ..

    }

}
function calcPrice($price1, $price2){
    //calculate price and operator
    if($price1 > $price2){
        $operator = "+";
        $pret = $price1 - $price2;
    }elseif($price1 < $price2){
        $operator = "-";
        $pret = $price2 - $price1;
    }else{
        $operator = "+";
        $pret = 0;
    }
    return array("operator" => $operator, "pret" => $pret);
}
?>