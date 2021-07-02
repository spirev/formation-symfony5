<?php

namespace App\Taxes;

class Detector{

    protected $seuil;

    public function __construct($seuil)
    {
        $this->seuil = $seuil;
    }

    public function detect(int $amount) : bool {
        if ($amount < $this->seuil){
            return false;
        }
        else{
            return true;
        }
    }
}