<?php

namespace Sergiors\Silex\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Application;
use Silex\Api\BootableProviderInterface;
use Sergiors\Silex\EventListener\GoogleAnalyticsListener;

/**
 * @author Sérgio Rafael Siqueira <sergio@inbep.com.br>
 */
class GoogleAnalyticsServiceProvider implements ServiceProviderInterface, BootableProviderInterface
{
    /**
     * @param Container $app
     */
    public function register(Container $app)
    {
        $app['ga.listener'] = function ($app) {
            return new GoogleAnalyticsListener($app['twig'], $app['ga.tracking_code']);
        };

        $app['twig.loader.filesystem'] = $app->extend('twig.loader.filesystem', function ($loader, $app) {
            $loader->addPath($app['ga.templates_path'], 'GA');

            return $loader;
        });

        $app['ga.templates_path'] = function () {
            $reflection = new \ReflectionClass(GoogleAnalyticsListener::class);

            return dirname(dirname($reflection->getFileName())).'/Resources/views';
        };

        $app['ga.tracking_code'] = null;
    }

    /**
     * @param Application $app
     */
    public function boot(Application $app)
    {
        if (isset($app['ga.listener'])) {
            $app['dispatcher']->addSubscriber($app['ga.listener']);
        }
    }
}
