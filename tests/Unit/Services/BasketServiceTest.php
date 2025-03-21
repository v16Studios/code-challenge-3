<?php

namespace Tests\Unit\Services;

use App\Models\Basket;
use App\Models\Product;
use App\Services\BasketService;
use App\Services\ProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BasketServiceTest extends TestCase
{
    use RefreshDatabase;

    protected BasketService $basketService;

    protected static array $seededProducts;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockProductService = $this->createMock(ProductService::class);

        $this->basketService = new BasketService($this->mockProductService);

        $this->setUpSeededProducts();
    }

    public static function setUpSeededProducts(): void
    {
        parent::setUpBeforeClass();

        static::$seededProducts = [
            Product::factory()->create(['id' => 1, 'code' => 'R01', 'name' => 'Red Widget', 'price' => 32.95]),
            Product::factory()->create(['id' => 2, 'code' => 'G01', 'name' => 'Green Widget', 'price' => 24.95]),
            Product::factory()->create(['id' => 3, 'code' => 'B01', 'name' => 'Blue Widget', 'price' => 7.95]),
        ];
    }

    /**
     * Data provider for basket combinations and expected totals.
     *
     * @return array
     */
    public static function basketDataProvider(): array
    {
        return [
            'Products: B01, G01' => [['B01', 'G01'], 37.85],
            'Products: R01, R01' => [['R01', 'R01'], 54.37],
            'Products: R01, G01' => [['R01', 'G01'], 60.85],
            'Products: B01, B01, R01, R01, R01' => [['B01', 'B01', 'R01', 'R01', 'R01'], 98.27],
        ];
    }

    /**
     * @dataProvider basketDataProvider
     */
    public function testGetTotal(array $productCodes, float $expectedTotal)
    {
        $this->mockProductService->method('getProductFromCode')
            ->willReturnCallback(function (string $code) {
                return Product::firstWhere('code', $code);
            });

        /** @var Basket $basket */
        $basket = Basket::factory()->create();

        foreach ($productCodes as $code) {
            $this->basketService->addProduct($code, $basket);
        }

        $total = $this->basketService->getTotal($basket);

        $this->assertEquals($expectedTotal, $total);
    }
}
