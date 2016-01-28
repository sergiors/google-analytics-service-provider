<?php

namespace Sergiors\Silex\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class GoogleAnalyticsListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function trackingCodeIsInjected()
    {
        $response = new Response('<html><head></head><body></body></html>');

        $event = new FilterResponseEvent(
            $this->getKernelMock(),
            $this->getRequestMock(),
            HttpKernelInterface::MASTER_REQUEST,
            $response
        );

        $listener = new GoogleAnalyticsListener($this->getTwigMock(), 'fake');
        $listener->onKernelResponse($event);

        $this->assertEquals("<html><head></head><body>\nGA\n</body></html>", $response->getContent());
    }

    protected function getTwigMock($render = 'GA')
    {
        $templating = $this->getMock(\Twig_Environment::class);

        $templating
            ->expects($this->any())
            ->method('render')
            ->will($this->returnValue($render));

        return $templating;
    }

    protected function getRequestMock($isXmlHttpRequest = false, $requestFormat = 'html')
    {
        $request = $this->getMock(Request::class);

        $request
            ->expects($this->any())
            ->method('isXmlHttpRequest')
            ->will($this->returnValue($isXmlHttpRequest));

        $request->expects($this->any())
            ->method('getRequestFormat')
            ->will($this->returnValue($requestFormat));

        return $request;
    }

    protected function getKernelMock()
    {
        return $this->getMock(Kernel::class, [], [], '', false);
    }
}
