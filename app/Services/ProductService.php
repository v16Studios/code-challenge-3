<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{
    public function getProductFromCode(string $code): Product
    {
        return Product::where('code', $code)->first();
    }
}
