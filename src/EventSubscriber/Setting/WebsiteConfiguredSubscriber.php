<?php

declare(strict_types=1);

namespace App\EventSubscriber\Setting;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class WebsiteConfiguredSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly ParameterBagInterface $params,
        private readonly UrlGeneratorInterface $router,
        private readonly KernelInterface $kernelInterface
    ) {
    }

    /*public function warmUpCaches(): void
    {
        $application = new Application($this->kernelInterface);
        $application->setAutoExit(false);

        $wampupCacheProd = new ArrayInput([
            'command' => 'cache:clear',
            'env' => 'prod',
        ]);

        $outputWampupCacheProd = new NullOutput();
        $application->run($wampupCacheProd, $outputWampupCacheProd);

        $wampupCacheDev = new ArrayInput([
            'command' => 'cache:clear',
            'env' => 'dev',
        ]);

        $outputWampupCacheDev = new NullOutput();
        $application->run($wampupCacheDev, $outputWampupCacheDev);
    }*/

    public function onRequest(RequestEvent $event): void
    {
        if ('0' == $this->params->get('is_website_configured') && false === strpos($event->getRequest()->getPathInfo(), 'install/install.php') && false === strpos($event->getRequest()->getPathInfo(), '_wdt')) {
            // $this->warmUpCaches();
            // $event->setResponse(new RedirectResponse($this->router->generate('installer_install')));
        }
    }

    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onRequest',
        ];
    }
}
