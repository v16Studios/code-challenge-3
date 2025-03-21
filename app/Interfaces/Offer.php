<?php

namespace App\Interfaces;

interface Offer
{
    public function applyOffer(array $data): float;
}
