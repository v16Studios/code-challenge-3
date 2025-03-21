<?php

namespace App\Services;

use App\Models\Basket;
use App\Models\LineItem;
use App\Offers\BuyOneGetOneHalfPrice;
use App\Offers\ShippingDiscounts;
use App\Traits\RoundsPrices;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class BasketService
{
    use RoundsPrices;

    protected Collection $lineItemOffers;

    protected ShippingDiscounts $shippingDiscounts;

    public function __construct(
        protected ProductService $productService
    )
    {
        $this->lineItemOffers = collect([
            new BuyOneGetOneHalfPrice(),
        ]);

        $this->shippingDiscounts = new ShippingDiscounts();
    }

    public function getBasket(int $id): Basket
    {
        return Basket::findOrFail($id);
    }

    public function createBasket(): Basket
    {
        return Basket::create([
            'uuid' => Str::uuid(),
        ]);
    }

    public function addProduct(string $code, Basket $basket): Basket
    {
        $product = $this->productService->getProductFromCode($code);
        $basketLineItems = $basket->lineItems;

        if ($existingLineItem = $basketLineItems->firstWhere('product_id', $product->id)) {
            $existingLineItem->increment('quantity');
        } else {
            $this->addLineItem($basket, new LineItem([
                'product_id' => $product->id,
                'quantity' => 1,
            ]));
        }

        return $basket->refresh();
    }

    public function removeProduct(string $code, Basket $basket): Basket
    {
        $product = $this->productService->getProductFromCode($code);
        $basketLineItems = $basket->lineItems;

        /** @var LineItem $lineItem */
        $lineItem = $basketLineItems->firstWhere('product_id', $product->id);

        if ($lineItem) {
            if ($lineItem->quantity > 1) {
                $lineItem->decrement('quantity');
            } else {
                $this->removeLineItem($lineItem);
            }
        }

        return $basket->refresh();
    }

    public function addLineItem(Basket $basket, LineItem $lineItem): void
    {
        $basket->lineItems()->save($lineItem);
    }

    public function removeLineItem(LineItem $lineItem): void
    {
        $lineItem->delete();
    }

    public function getTotal(Basket $basket): float
    {
        $lineItems = $basket->lineItems;
        $subTotal = 0;

        /**
         * @var \App\Models\LineItem $lineItem
         */
        foreach ($lineItems as $lineItem) {
            $subTotal += $this->lineItemOffers->reduce(function ($total, $offer) use ($lineItem) {

                return $offer->applyOffer(['product' => $lineItem->product->toArray(), 'quantity' => $lineItem->quantity]);
            });
        }

        $shippingCost = $this->shippingDiscounts->applyOffer(['subTotal'=> $subTotal]);

        return $this->roundPrice($subTotal + $shippingCost);
    }
}
