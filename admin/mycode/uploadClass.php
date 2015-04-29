<?php
/**
 * Created by PhpStorm.
 * User: ovidiu
 * Date: 18.03.2015
 * Time: 12:42
 */

class uploadClass {
    private $xls;
    private $carTeh;
    private $products = array();
    function __construct(Spreadsheet_Excel_Reader $xls){
        $this->xls = $xls;
    }

    function isValid(){
        for($i = 1; $i<=22; $i++){
            if ($this->val(1,$i)==null || $this->val(1,$i)==""){
                echo $i;
                return false;
            }
        }
        return true;
    }

    function val($row,$col){
      return $this->xls->val($row,$col);
    }

    function getVersion(){
        //functioneaza deocamdata
        if($this->val(1,18) == 'PDF_CATALOG') return 10;
        return false;
    }

    function  toArr($val){
        if($val){

            $item = trim($val, ", ");
            $item = trim($item, " ,");
            $item = trim($item, " , ");

            return explode(",",$item);
        }
        return '';
    }

    function readData($carTeh){

        $pCount = 0;
        $spCount = 0;

        $this->carTeh = $carTeh;

        for ($i = 2; $i <= $this->xls->rowcount(0); $i++) {

            $subproduct = new SubProduct();
            //campul articol?
            if($this->xls->val($i,1) != ""){
                if($subProducts!=null && sizeof($subProducts)>0){
                    $product->subProducts = $subProducts;
                    $this->products[$pCount] = $product;
                    $pCount++;
                }
                $subProducts = array();
                $spCount = 0;
                $product = new Product();
                $product->nrArticol 	= $this->xls->val($i,1);
                $product->idProducator 	= $this->xls->val($i,3);
                $product->nume 			= $this->xls->val($i,4);
                $product->unitateMasura = $this->xls->val($i,5);
                $product->cantPret2 	= $this->xls->val($i,13)==""?1:$this->xls->val($i,13);
                $product->cantPret3 	= $this->xls->val($i,14)==""?1:$this->xls->val($i,14);
                $product->speciale 		= $this->xls->val($i,15);
                $product->descriere 	= $this->xls->val($i,16);
                $product->poza 			= $this->xls->val($i,17);
                //
                $product->pdfCatalog	= $this->xls->val($i,18);
                $product->pdfBrosura	= $this->xls->val($i,19);
                $product->pdfManual	    = $this->xls->val($i,20);
                $product->pdfSchema	    = $this->xls->val($i,21);
                $product->infoAdit      = $this->xls->val($i,22);
                $product->pdfPM	        = $this->xls->val($i,23);
                $product->accesorii     = $this->xls->val($i,24);
                $product->pieseSchimb   = $this->xls->val($i,25);
                $product->prodAltern    = $this->xls->val($i,26);
                $product->amprenta 		= $this->xls->val($i,28);
                $product->trusa         = $this->toArr($this->xls->val($i,27));
                $product->poze          = $this->toArr($this->xls->val($i,27));
                $product->uTubeLink	= $this->xls->val($i,29);
                $product->seoDesc   = $this->xls->val($i,30); //{ifcts}
            }
            //campul "COD UNIC"
            if($this->xls->val($i,2) != ""){


                $subproduct->codUnic 	= $this->xls->val($i,2);
                $subproduct->unitateMasura = $this->xls->val($i,5);
                $subproduct->carTeh 	= $this->xls->val($i,6);
                $subproduct->carTeh1 	= $this->xls->val($i,7);
                $subproduct->carTeh2 	= $this->xls->val($i,8);
                $subproduct->pret1 		= $this->xls->val($i,9);
                $subproduct->pret2 		= $this->xls->val($i,10)==""?$subproduct->pret1:$this->xls->val($i,10);
                $subproduct->pret3 		= $this->xls->val($i,11)==""?$subproduct->pret1:$this->xls->val($i,11);
                $subproduct->pretSpecial= $this->xls->val($i,12)==""?$subproduct->pret1:(double)$this->xls->val($i,12);
                $subProducts[$spCount] = $subproduct;
                $spCount++;

            }
        }
        $product->subProducts = $subProducts;
        $this->products[$pCount] = $product;

        /*echo '<pre>';
        var_dump($this->products);echo '</pre>';die();*/

    }
    function uploadData($current_category_id){
        $language_id = 1;
        for($i=0;$i<sizeof($this->products);$i++){

            //if only nrArticol then link product into category

            if($this->products[$i]->nrArticol!=null && $this->products[$i]->nrArticol!="" &&
                ($this->products[$i]->idProducator==null || $this->products[$i]->idProducator=="") &&
                ($this->products[$i]->nume==null || $this->products[$i]->nume=="") &&
                ($this->products[$i]->unitateMasura==null || $this->products[$i]->unitateMasura=="")){

                $check_query = tep_db_query("select products_id  from " . TABLE_PRODUCTS . " where nr_articol like '" . $this->products[$i]->nrArticol . "' ");
                $check = tep_db_fetch_array($check_query);

                if ($check==null) {
                    //$error = "Nu exista in baza de date un produs cu numarul de articol: ".$this->products[$i]->nrArticol;
                    $check_query_3 = tep_db_query("select *  from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id like '" . (int)$current_category_id . "' and nr_articol like '" . $this->products[$i]->nrArticol . "' ");
                    $check_3 = tep_db_fetch_array($check_query_3);
                    if ($check_3==null) {
                        tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (categories_id, nr_articol) values ('" . (int)$current_category_id . "', '" . $this->products[$i]->nrArticol . "')");
                    }
                }else{

                    $check_query_2 = tep_db_query("select products_id  from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id like '" . (int)$check['products_id'] . "' and categories_id like '" . (int)$current_category_id . "' ");
                    $check_2 = tep_db_fetch_array($check_query_2);
                    if ($check_2==null) {
                        //echo "insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . (int)$check['products_id'] . "', '" . (int)$current_category_id . "')";
                        tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id, nr_articol) values ('" . (int)$check['products_id'] . "', '" . (int)$current_category_id . "', '" . $this->products[$i]->nrArticol . "')");
                    }
                }
            }else{
                $products_image = new upload();

                if($this->products[$i]->poze[0]!="" && file_exists(DIR_FS_CATALOG_UPLOADED_IMAGES.$this->products[$i]->poze[0])){
                    $spDir = strtoupper($this->products[$i]->poze[0][0]).'/';
                    $products_image->set_thumb($this->products[$i]->poze[0], DIR_FS_CATALOG_UPLOADED_IMAGES, DIR_FS_CATALOG_IMAGES."mici/".$spDir, 90, 50,50);
                    $products_image->set_thumb($this->products[$i]->poze[0], DIR_FS_CATALOG_UPLOADED_IMAGES, DIR_FS_CATALOG_IMAGES."mari/".$spDir, 90, 200,200);

                    $products_image->unlink();
                }

                if($this->products[$i]->poze[1] != null && $this->products[$i]->poze[1]!="" && file_exists(DIR_FS_CATALOG_UPLOADED_IMAGES.$this->products[$i]->poze[1])){
                    $spDir = strtoupper($this->products[$i]->poze[1][0]).'/';
                    $products_image->set_thumb($this->products[$i]->poze[1], DIR_FS_CATALOG_UPLOADED_IMAGES, DIR_FS_CATALOG_IMAGES."mici/".$spDir, 90, 50,50);
                    $products_image->set_thumb($this->products[$i]->poze[1], DIR_FS_CATALOG_UPLOADED_IMAGES, DIR_FS_CATALOG_IMAGES."mari/".$spDir, 90, 200,200);

                    $products_image->unlink();
                }

                if($this->products[$i]->poze[2] != null && $this->products[$i]->poze[2]!="" && file_exists(DIR_FS_CATALOG_UPLOADED_IMAGES.$this->products[$i]->poze[2])){
                    $spDir = strtoupper($this->products[$i]->poze[2][0]).'/';
                    $products_image->set_thumb($this->products[$i]->poze[2], DIR_FS_CATALOG_UPLOADED_IMAGES, DIR_FS_CATALOG_IMAGES."mici/".$spDir, 90, 50,50);
                    $products_image->set_thumb($this->products[$i]->poze[2], DIR_FS_CATALOG_UPLOADED_IMAGES, DIR_FS_CATALOG_IMAGES."mari/".$spDir, 90, 200,200);

                    $products_image->unlink();
                }

                if($this->products[$i]->poze[3] != null && $this->products[$i]->poze[3]!="" && file_exists(DIR_FS_CATALOG_UPLOADED_IMAGES.$this->products[$i]->poze[3])){
                    $spDir = strtoupper($this->products[$i]->poze[3][0]).'/';
                    $products_image->set_thumb($this->products[$i]->poze[3], DIR_FS_CATALOG_UPLOADED_IMAGES, DIR_FS_CATALOG_IMAGES."mici/".$spDir, 90, 50,50);
                    $products_image->set_thumb($this->products[$i]->poze[3], DIR_FS_CATALOG_UPLOADED_IMAGES, DIR_FS_CATALOG_IMAGES."mari/".$spDir, 90, 200,200);

                    $products_image->unlink();
                }

                if($this->products[$i]->amprenta != null && $this->products[$i]->amprenta!="" && file_exists(DIR_FS_CATALOG_UPLOADED_IMAGES.$this->products[$i]->amprenta)){
                    $spDir = strtoupper($this->products[$i]->amprenta[0]).'/';
                    $products_image->set_thumb($this->products[$i]->amprenta, DIR_FS_CATALOG_UPLOADED_IMAGES, DIR_FS_CATALOG_IMAGES."mici/".$spDir, 90, 50,50);

                    //$products_image->unlink();
                }


                //set the product array
                $sql_data_array = array(	'nr_articol' => tep_db_prepare_input($this->products[$i]->nrArticol),
                    'cod_unic' => tep_db_prepare_input($this->products[$i]->subProducts[0]->codUnic),
                    'is_set' => $this->products[$i]->trusa==null?"0":"1",
                    'products_price' => (double)tep_db_prepare_input($this->products[$i]->subProducts[0]->pret1),
                    'products_price_2' => (double)tep_db_prepare_input($this->products[$i]->subProducts[0]->pret2),
                    'products_price_3' => (double)tep_db_prepare_input($this->products[$i]->subProducts[0]->pret3),
                    'cant_pret_2' => (int)tep_db_prepare_input($this->products[$i]->cantPret2),
                    'cant_pret_3' => (int)tep_db_prepare_input($this->products[$i]->cantPret3),
                    'products_um' => tep_db_prepare_input($this->products[$i]->unitateMasura),
                    'products_status' => ($this->products[$i]->subProducts[0]->pret1==null || $this->products[$i]->subProducts[0]->pret1=="")?'0':'1',
                    'products_date_available' => 'now()',
                    'manufacturers_id' => (int)tep_db_prepare_input($this->products[$i]->idProducator),
                    'products_image' => tep_db_prepare_input($this->products[$i]->poze[0]),
                    'products_image_2' => tep_db_prepare_input($this->products[$i]->poze[1]),
                    'products_image_3' => tep_db_prepare_input($this->products[$i]->poze[2]),
                    'products_image_4' => tep_db_prepare_input($this->products[$i]->poze[3]),
                    'products_date_added' => 'now()',
                    'products_specials' => trim(tep_db_prepare_input($this->products[$i]->speciale)),
                    'products_amprenta' => tep_db_prepare_input($this->products[$i]->amprenta),
                    //'products_pdf' => tep_db_prepare_input($this->products[$i]->pdf),
                    'PDF_CATALOG' => tep_db_prepare_input($this->products[$i]->pdfCatalog),
                    'PDF_BROSURA' => tep_db_prepare_input($this->products[$i]->pdfBrosura),
                    'PDF_MANUAL' => tep_db_prepare_input($this->products[$i]->pdfManual),
                    'PDF_SCHEMA' => tep_db_prepare_input($this->products[$i]->pdfSchema),
                    'INFO_ADITIONALE' => tep_db_prepare_input($this->products[$i]->infoAdit),
                    'PDF_PM' => tep_db_prepare_input($this->products[$i]->pdfPM),
                    'ACCESORII' => tep_db_prepare_input($this->products[$i]->accesorii),
                    'PIESE_SCHIMB' => tep_db_prepare_input($this->products[$i]->pieseSchimb),
                    'PROD_ALTERNATIVE' => tep_db_prepare_input($this->products[$i]->prodAltern),
                    'products_youtube' => tep_db_prepare_input($this->products[$i]->uTubeLink));

                //check if product already in db
                //var_dump($this->products[$i]);
                $check_query = tep_db_query("select P.products_id  from " . TABLE_PRODUCTS . " P JOIN " . TABLE_PRODUCTS_TO_CATEGORIES . " PC ON P.products_id=PC.products_id where P.nr_articol like '" . $this->products[$i]->nrArticol . "' and cod_unic like '" . tep_db_prepare_input($this->products[$i]->subProducts[0]->codUnic) . "' and PC.categories_id = " . $current_category_id);
                $check = tep_db_fetch_array($check_query);

                if ($check==null) {
                    tep_db_perform(TABLE_PRODUCTS, $sql_data_array);

                    $products_id = tep_db_insert_id();

                    $query = "insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . (int)$products_id . "', '" . (int)$current_category_id . "')";
                    tep_db_query("UPDATE " . TABLE_PRODUCTS_TO_CATEGORIES . " SET products_id = '" . $products_id . "'WHERE nr_articol='" . $this->products[$i]->nrArticol . "'");

                    tep_db_query($query);
                }else{
                    $products_id = $check['products_id'];
                    tep_db_perform(TABLE_PRODUCTS, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "' and cod_unic like '" . tep_db_prepare_input($this->products[$i]->subProducts[0]->codUnic) . "'");
                }
                //add products to product_Set
                if($this->products[$i]->trusa!=null){

                    $del_query = "delete  from " . TABLE_PRODUCTS_SET . " where product_id = '" . $products_id . "'";
                    tep_db_query($del_query);

                    for($l=0;$l<sizeof($this->products[$i]->trusa);$l++){

                        $component = explode('?', $this->products[$i]->trusa[$l]);

                        if(sizeof($component)>1){
                            $no = $component[0];
                            $cod = $component[1];
                        } else {
                            $no = 1;
                            $cod = $this->products[$i]->trusa[$l];
                        }

                        $query = "insert into " . TABLE_PRODUCTS_SET . " (product_id, set_cod_unic, products_set_no) values ('" . $products_id . "', '" . $cod . "', '" . $no . "')";
                        tep_db_query($query);

                    }
                }
                // add price to products specials
                $sql_data_array = array('products_id' => $products_id,
                    'specials_new_products_price' => $this->products[$i]->subProducts[0]->pretSpecial,
                    'expires_date' => null,
                    'status' => stristr($this->products[$i]->speciale, '01')?'1':'0');

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
                $sql_data_array = array('products_name' => tep_db_prepare_input($this->products[$i]->nume),
                    'products_description' => tep_db_prepare_input($this->products[$i]->descriere),
                    'products_seo_desc'    => tep_db_prepare_input($this->products[$i]->seoDesc),
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
                $check_query = tep_db_query("select products_options_id from " . TABLE_PRODUCTS_OPTIONS . " where products_options_name like '" . $this->carTeh . "'");
                $check = tep_db_fetch_array($check_query);
                if ($check==null) {
                    $query = "insert into " . TABLE_PRODUCTS_OPTIONS . " (products_options_id, language_id, products_options_name) values (null, '" . $language_id . "', '" . $this->carTeh . "')";
                    tep_db_query($query);
                    $products_options_id = tep_db_insert_id();
                }else{
                    $products_options_id = $check['products_options_id'];
                }

                tep_db_query("DELETE FROM products_attributes WHERE products_id='".$products_id."'");

                // add product options values
                if(sizeof($this->products[$i]->subProducts)>0){

                    for($m=0;$m<sizeof($this->products[$i]->subProducts);$m++){

                        $check_query = tep_db_query("select products_options_values_id from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_name like '" . $this->products[$i]->subProducts[$m]->carTeh . "'");
                        $check = tep_db_fetch_array($check_query);
                        if ($check==null) {
                            $query = "insert into " . TABLE_PRODUCTS_OPTIONS_VALUES . " (products_options_values_id, language_id, products_options_values_name) values (null, '" . $language_id . "', '" . $this->products[$i]->subProducts[$m]->carTeh . "')";
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

                        $pret1 = calcPrice($this->products[$i]->subProducts[$m]->pret1, $this->products[$i]->subProducts[0]->pret1);
                        $pret2 = calcPrice($this->products[$i]->subProducts[$m]->pret2, $this->products[$i]->subProducts[0]->pret2);
                        $pret3 = calcPrice($this->products[$i]->subProducts[$m]->pret3, $this->products[$i]->subProducts[0]->pret3);
                        $pretSpecial = calcPrice($this->products[$i]->subProducts[$m]->pretSpecial, $this->products[$i]->subProducts[0]->pretSpecial);



                        //       echo $products_imag


                        // add products to products atributes
                        $sql_data_array = array('products_id' => $products_id,
                            'options_id' => $products_options_id,
                            'options_values_id' => $products_options_values_id,
                            'cod_unic' => $this->products[$i]->subProducts[$m]->codUnic,
                            'um' => $this->products[$i]->subProducts[$m]->unitateMasura,
                            'car_teh_1' => $this->products[$i]->subProducts[$m]->carTeh1,
                            'car_teh_2' => $this->products[$i]->subProducts[$m]->carTeh2,
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
    }

}