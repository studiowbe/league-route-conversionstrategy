# league-route-conversionstrategy
A smart conversion strategy for league/route. It allows you to return pretty much anything you want from your controllers/handlers. 

**Note** The strategy offers a convenient way to convert multiple types of return values to ResponseInterface objects. Of course you can still return a ResponseInterface object for maximum control. 
## Installation
The recommended way to install the package is composer:
```bash
composer require studiow/league-route-conversionstrategy
```

To use it in your code, use it like any other StrategyInterface:

```php
$router = new \League\Route\Router();
$strategy = new \Studiow\LeagueRoute\Strategy\ConversionStrategy(
    new \Studiow\LeagueRoute\Strategy\Convertor\HtmlConvertor($responseFactory)
);
$router->setStrategy($strategy);
```

## Conversions
The package comes with the following conversions bundled:

### HtmlConvertor
This convertor will convert any text to a text/html response
```php
$router = new \League\Route\Router();
$strategy = new \Studiow\LeagueRoute\Strategy\ConversionStrategy(
    new \Studiow\LeagueRoute\Strategy\Convertor\HtmlConvertor($responseFactory)
);
$router->setStrategy($strategy);

//we can now return text
$router->get('/text', function(){
    return 'Hello world';
});
```
### JsonConvertor
This convertor will convert any array or object to an application/json response
 
 ```php
 $router = new \League\Route\Router();
 $strategy = new \Studiow\LeagueRoute\Strategy\ConversionStrategy(
     new \Studiow\LeagueRoute\Strategy\Convertor\JsonConvertor($responseFactory)
 );
 $router->setStrategy($strategy);
 
 //we can now return arrays
 $router->get('/array', function(){
     return ['foo'=>'bar'];
 });
 
 //or objects (that may or may not implement JsonSerializable
 $router->get('/object', function(){
      return new ArrayObject(['foo'=>'bar']);
 });
 ```
 ### ConvertorCollection
 This convertor allows you to have multiple conversions. The first one that can be used will be used:
 ```php
$router = new \League\Route\Router();

$convertors = new \Studiow\LeagueRoute\Strategy\Convertor\ConvertorCollection([
    new \Studiow\LeagueRoute\Strategy\Convertor\JsonConvertor($responseFactory),
    new \Studiow\LeagueRoute\Strategy\Convertor\HtmlConvertor($responseFactory)
]);

$strategy = new \Studiow\LeagueRoute\Strategy\ConversionStrategy($convertors);
$router->setStrategy($strategy);

//this will be handled by the HtmlConvertor
$router->get('/text', function () {
    return 'Hello world';
});

//this will be handled by the JsonConvertor
$router->get('/array', function () {
    return ['foo' => 'bar'];
});

//this will also be handled by the JsonConvertor
$router->get('/object', function () {
    return new ArrayObject(['foo' => 'bar']);
});
 
//you can always still return a ResponseInterface object. This will be unmodified:
$router->get('/full-response', function () {
    return new Response(...)
});  
 ```