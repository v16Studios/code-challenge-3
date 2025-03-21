### Coding Challenge 3

This is a partial implementation of a full shopping cart system, specifically the Basket component.

Using service classes we are able to create and manipulate a basket's content, as well as apply offers and shipping costs based on basket value, and calculate the final total. This approach makes it easy to use in controllers or API resolvers, e.g. for GrpahQL APIs by injecting the service class where required.

Using the BasketService we first create a new Basket model and then add products to it using the product code:

```php
$basketService = app()->make(App\Services\BasketService::class);

$basket = $basketService->createBasket();

$basketService->addProduct('B01', $basket);
$basketService->addProduct('G01', $basket);

$basketService->getTotal($basket);
```

New offers can be created easily by creating new classes in the App\Offers namespace which implement the Offer interface.  The new offer classes are currently registered in the BasketService e.g. in the lineItemOffers array, and are automatically applied when calculating the total.

By creating self-contained offer classes we are able to apply multiple offers to individual products in the basket as well as use them in different parts of the pipeline for example to retrieve the offer value per product/line item for display purposes.

### Try out the code
You can use the included docker config to build a container and run the project to try out the above code in tinker and run the unit tests.  This assumes you are using a mac and have docker and docker-compose installed:

Terminal:
```
docker-compose build

docker-compose up -d

docker exec -it code-challenge-3 vendor/bin/phpunit

docker exec -it code-challenge-3 php artisan tinker
```
Once in tinker you can run each line of code from the examples above in turn to see everything working.

