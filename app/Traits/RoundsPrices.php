<?php

namespace App\Traits;

trait RoundsPrices
{
    public function roundPrice(float $price): float
    {
        return floor(($price) * 100) / 100;
    }
}
