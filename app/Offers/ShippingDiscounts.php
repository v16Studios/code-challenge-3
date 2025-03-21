<?php

namespace App\Offers;

use App\Interfaces\Offer;

class ShippingDiscounts implements Offer
{
    protected array $shippingCosts = [
        '49' => 4.95,
        '50' => 2.95,
        '90' => 0,
    ];

    /**
     * @param array $data{subTotal: float}
     * @return float
     */
    public function applyOffer(array $data): float
    {
        krsort($this->shippingCosts);

        foreach ($this->shippingCosts as $threshold => $cost) {
            if ($data['subTotal'] >= (float) $threshold) {
                return $cost;
            }
        }

        return max($this->shippingCosts);
    }
}
