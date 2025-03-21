<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LineItem extends Model
{
    protected $fillable = [
        'basket_id',
        'product_id',
        'quantity',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function basket(): BelongsTo
    {
        return $this->belongsTo(Basket::class);
    }
}
