<?php

namespace App\Offers;

use App\Interfaces\Offer;
use App\Traits\RoundsPrices;

class BuyOneGetOneHalfPrice implements Offer
{
    use RoundsPrices;

    /**
     * @var array|string[]
     */
    public array $appliesTo = [
        'R01',
    ];

    /**
     * @param array $data{product: array, quantity: int}
     * @return float
     */
    public function applyOffer(array $data): float
    {
        if (in_array($data['product']['code'], $this->appliesTo)) {
            $quantity = $data['quantity'];
            $price = $data['product']['price'];

            $pairs = floor($quantity / 2);
            $remainingItems = $quantity % 2;

            $pairTotal = $pairs * ($price + ($price / 2));
            $remainingTotal = $remainingItems * $price;

            return $this->roundPrice($pairTotal + $remainingTotal);
        }

        return $this->roundPrice($data['quantity'] * $data['product']['price']);
    }
}
