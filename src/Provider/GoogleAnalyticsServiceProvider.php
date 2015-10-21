<?php
namespace Inbep\Silex\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Inbep\Silex\EventListener\GoogleAnalyticsListener;

/**
 * @author Sérgio Rafael Siqueira <sergio@inbep.com.br>
 */
class GoogleAnalyticsServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Application $app
     */
    public function register(Application $app)
    {
        $app['ga.code'] = null;

        $app['ga.listener'] = function ($app) {
            return new GoogleAnalyticsListener($app['twig'], $app['ga.code']);
        };

        $app['twig.loader.filesystem'] = $app->share(
            $app->extend('twig.loader.filesystem', function ($loader, $app) {
                $loader->addPath($app['ga.templates_path'], 'GA');
                return $loader;
            })
        );

        $app['ga.templates_path'] = function () {
            $reflection = new \ReflectionClass(GoogleAnalyticsListener::class);
            
            return dirname(dirname($reflection->getFileName())).'/Resources/views';
        };
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
