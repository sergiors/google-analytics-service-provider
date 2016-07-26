Google Analytics Service Provider
---------------------------------

Install
-------
```bash
composer require sergiors/google-analytics-service-provider
```

How to use
----------

```php
// ...
$app->register(new Sergiors\Silex\Provider\GoogleAnalyticsServiceProvider(), [
    'ga.options' => [
        'tracking_code' => '{your code here}'
    ]
]);
```

That's it.

License
-------
MIT
