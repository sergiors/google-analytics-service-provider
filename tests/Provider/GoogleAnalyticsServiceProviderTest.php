<?php

namespace Sergiors\Silex\Tests\Provider;

use Silex\Application;
use Silex\WebTestCase;
use Silex\Provider\TwigServiceProvider;
use Sergiors\Silex\Provider\GoogleAnalyticsServiceProvider;
use Sergiors\Silex\EventListener\GoogleAnalyticsListener;

class GoogleAnalyticsServiceProviderTest extends WebTestCase
{
    /**
     * @test
     */
    public function register()
    {
        $app = $this->createApplication();
        $app->register(new TwigServiceProvider());
        $app->register(new GoogleAnalyticsServiceProvider());

        $templates = dirname(dirname(__DIR__)).'/src/Resources/views';

        $this->assertEquals($templates, $app['ga.templates_path']);
        $this->assertInstanceOf(GoogleAnalyticsListener::class, $app['ga.listener']);
    }

    public function createApplication()
    {
        $app = new Application();
        $app['debug'] = true;

        return $app;
    }
}
