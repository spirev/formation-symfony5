<?php

namespace App\Taxes;

class Calculator{
    public function calcul(float $price) : float {
        return $price * (20 / 100);
    }
}
