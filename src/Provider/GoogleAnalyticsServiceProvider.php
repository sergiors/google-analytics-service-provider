<?php
namespace Inbep\Silex\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Inbep\Silex\Provider\Twig\Google_Analytics_Extension;

class GoogleAnalyticsServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Silex\Application $app
     */
    public function register(Application $app)
    {
        $app['analytics.code'] = null;

        $app['twig.loader.filesystem'] = $app->share(
            $app->extend('twig.loader.filesystem', function ($loader) {
                $loader->addPath(__DIR__.'/../Resources/views/');
                return $loader;
            })
        );

        $app['twig'] = $app->share($app->extend('twig', function ($twig, $app) {
            $code = $app['analytics.code'];
            $twig->addExtension(new Google_Analytics_Extension($twig, $code));
            return $twig;
        }));
    }

    /**
     * @param Silex\Application $app
     */
    public function boot(Application $app)
    {
        if (!isset($app['twig'])) {
            throw new \LogicException('You must register the TwigServiceProvider to use the GoogleAnalyricsServiceProvider');
        }
    }
}
