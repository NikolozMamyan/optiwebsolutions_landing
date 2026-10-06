<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class NotFoundRedirectSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::EXCEPTION => 'onKernelException'];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        if (!$event->isMainRequest() || !$exception instanceof HttpExceptionInterface || $exception->getStatusCode() !== Response::HTTP_NOT_FOUND) {
            return;
        }

        $request = $event->getRequest();
        $locale = str_starts_with($request->getPathInfo(), '/en/') ? 'en' : $request->getLocale();

        $event->setResponse(new RedirectResponse(
            $this->urlGenerator->generate('app_home', ['_locale' => $locale]),
            Response::HTTP_FOUND,
        ));
    }
}
