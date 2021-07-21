<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class NameTransformer implements DataTransformerInterface{
    
    public function transform($value)
    {
        if ($value === null) {
            return;
        }

        return strtoupper($value);
    }
    
    public function reverseTransform($value)
    {
        if ($value === null) {
            return;
        }
    
        return strtolower($value);
    }
}