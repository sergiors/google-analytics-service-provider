How to use
----------

```php
// ...
$app->register(new Inbep\Silex\Provider\GoogleAnalyticsServiceProvider(), [
    'analytics.code' => '{your code here}'
]);
```

In your templates file, you can use `{{ ga() }}` to call google analytics javascript code.

**If you not set your google analytics code, when you call `{{ ga() }}`, don't show nothing**

That's it.

License
-------
MIT
