<?php
namespace Sergiors\Silex\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * GoogleAnalyticsListener injects the Google Analytics Tracking Code.
 *
 * @author Sérgio Rafael Siqueira <sergio@inbep.com.br>
 */
class GoogleAnalyticsListener implements EventSubscriberInterface
{
    protected $twig;
    protected $code;

    public function __construct(\Twig_Environment $twig, $code = null)
    {
        $this->twig = $twig;
        $this->code = $code;
    }

    /**
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $response = $event->getResponse();
        $request = $event->getRequest();

        if (!$event->isMasterRequest()) {
            return;
        }

        if ($request->isXmlHttpRequest()) {
            return;
        }

        if ($response->isRedirection()
            || ($response->headers->has('Content-Type')
                && false === strpos($response->headers->get('Content-Type'), 'html'))
            || 'html' !== $request->getRequestFormat()
        ) {
            return;
        }

        $this->injectTrackingCode($response);
    }

    /**
     * @param Response $response
     */
    protected function injectTrackingCode(Response $response)
    {
        if (empty($this->code)) {
            return;
        }

        $content = $response->getContent();
        $pos = strripos($content, '</body>');

        if (false === $pos) {
            return;
        }

        $trackingCode = "\n".$this->twig->render('@GA/ga_js.html.twig', [
            'code' => $this->code
        ])."\n";
        
        $content = substr($content, 0, $pos).$trackingCode.substr($content, $pos);
        $response->setContent($content);
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::RESPONSE => ['onKernelResponse', -128]
        ];
    }
}
