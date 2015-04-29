<?php
require_once('uploadClass.php');

$target_path = "../../tmp/" . basename( $_FILES['uploadedfile']['name']);

if(!move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path))
    throw new Exception('Couldn\'t move');

$data = new uploadClass(new Spreadsheet_Excel_Reader($target_path,true,"ISO-8859-2"));

/*
 * verificam daca e versiunea noua
 * */
if ($data->getVersion()){

    if(!$data->isValid()){throw new Exception('Invalid data');}

    $carTeh = ucfirst(strtolower($data->val(1,6)));

    if (strtolower($carTeh) == strtolower("CARACTERISTICI TEHNICE 1")){
        $carTeh = "Dimensiune";
    }
    $data->readData($carTeh);
    $data->uploadData($_GET['cID']);


    $new_version = true;
};
