<?php
/**
 * Created by PhpStorm.
 * User: ovidiu
 * Date: 26.04.2015
 * Time: 08:00
 */

class niceInfoBox{
    public $titlu;
    public $continut;
    public $url;

    function __construct($titlu,$continut, array $url){
        $this->titlu = $titlu;
        $this->continut = $continut;
        $this->url = $url;
    }
}
class nicePromoBox{
    public $titlu;
    public $poza;
    public $desc;
    public $pret;
    public $url;
    public $buyNow;

    function __construct(array $titlu, $img, array $desc,array $pret , array $buyNow){
        $this->titlu = $titlu;
        $this->img = $img;
        $this->desc = $desc;
        $this->pret = $pret;
        $this->buyNow = $buyNow;
    }
}

class boxFragment{
    public $titlu;
    public $poza;
    public $desc;
    public $pret;
    public $url;
    public $buyNow;

    function __construct(array $titlu, $img, array $desc,array $pret , array $buyNow){
        $this->titlu = $titlu;
        $this->img = $img;
        $this->desc = $desc;
        $this->pret = $pret;
        $this->buyNow = $buyNow;
    }
}

class BoxDecorator{
    const INFO_BOX = 1;
    const PROMO_BOX = 2;
    const BOX_FRAGMENT = 3;

    protected $Box;
    protected $type;
    protected $out = '';

    public function __construct($box, $type){
        $this->Box = $box;
        $this->type = $type;
    }

    public function __toString(){
        switch ($this->type){
            case self::INFO_BOX:
                $this->out = '<article class="niceInfoBox">';
                $this->out .= '<h3>'.$this->Box->titlu.'</h3>';
                $this->out .= '<hr>';
                $this->out .= '<p>'.$this->Box->continut.'</p>';
                $this->out .= '<a href="'.$this->Box->url[0].'">'.$this->Box->url[1].'</a>';
                $this->out .= '</article>';
                break;
            case self::PROMO_BOX;
                $this->out = '<article class="nicePromoBox">';
                $this->out .= '<h3>'.$this->Box->titlu[0].'</h3>';
                $this->out .= '<a href="'.$this->Box->desc[1].'">'.$this->Box->img.'</a>';
                $this->out .= '<p><a href="'.$this->Box->desc[1].'">'.$this->Box->desc[0].'</a></p>';
                $this->out .= '<div class="col-sm-6">';
                $this->out .= ($this->Box->pret[1])?
                                '<span class="pOld">'.$this->Box->pret[0].'</span><span class="pNew">'.$this->Box->pret[1].'</span>':
                                '<span class="pNew">'.$this->Box->pret[0].'</span>';
                $this->out .= '</div><div class="col-sm-6">';
                $this->out .= '<a class="buyNow" href="'.$this->Box->buyNow[1].'">'.$this->Box->buyNow[0].'</a>';
                $this->out .= '</div>';
                $this->out .= '</article>';
                break;
            case self::BOX_FRAGMENT;
                $this->out = '<article class="boxItem col-sm-3 no-padding">';
                $this->out .= '<a href="'.$this->Box->desc[1].'">'.$this->Box->img.'</a>';
                $this->out .= '<h4><a href="'.$this->Box->desc[1].'">'.$this->Box->desc[0].'</a></h4>';
                $this->out .= '<div class="col-sm-12_">';
                $this->out .= ($this->Box->pret[1])?
                    '<span class="pOld">'.$this->Box->pret[0].'</span><span class="pNew">'.$this->Box->pret[1].'</span>':
                    '<span class="pNew">'.$this->Box->pret[0].'</span>';
                $this->out .= '</div><div class="col-sm-12_">';
                $this->out .= '<a class="buyNow" href="'.$this->Box->buyNow[1].'">'.$this->Box->buyNow[0].'</a>';
                $this->out .= '</div>';
                $this->out .= '</article>';
                break;
        }
        return (string)$this->out;
    }
}
