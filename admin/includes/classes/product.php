<?php
class Product{
	public $nrArticol;
	public $idProducator;
	public $nume;
	public $unitateMasura;
	public $speciale;
	public $descriere;
	public $poza;
	public $pdf;
	public $trusa = array();
	public $subProducts = array();
	public $poze = array();
	public $cantPret2;
	public $cantPret3;
	public $amprenta;
	public $uTubeLink;
    public $seoDesc;
}

class SubProduct{
	
	public $codUnic;
	public $carTeh;
	public $carTeh1;
	public $carTeh2;
	public $pret1;
	public $pret2;
	public $pret3;
	public $pretSpecial;
}
?>
