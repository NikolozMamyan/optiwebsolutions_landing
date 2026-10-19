<?php

namespace App\EventSubscriber;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class SecurityHeadersSubscriber implements EventSubscriberInterface
{
    public function __construct(
        #[Autowire('%kernel.debug%')] private readonly bool $debug,
        private readonly RequestStack $requests,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::REQUEST => ['onRequest', 128], KernelEvents::RESPONSE => ['onResponse', -200]];
    }

    public function onRequest(RequestEvent $event): void
    {
        $nonce = $this->requests->getMainRequest()?->attributes->get('csp_nonce') ?? base64_encode(random_bytes(18));
        $event->getRequest()->attributes->set('csp_nonce', $nonce);
    }

    public function onResponse(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $response = $event->getResponse();
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000');
        }

        if (!$this->debug && str_starts_with($response->headers->get('Content-Type', ''), 'text/html')) {
            $nonce = $request->attributes->get('csp_nonce');
            $response->headers->set('Content-Security-Policy', implode('; ', [
                "default-src 'self'",
                "base-uri 'self'",
                "object-src 'none'",
                "frame-ancestors 'self'",
                "form-action 'self'",
                "script-src 'self' 'nonce-$nonce' 'strict-dynamic' https://app.cal.com https://cal.com https://www.googletagmanager.com",
                "style-src 'self' 'unsafe-inline'",
                "img-src 'self' data: https://*.google-analytics.com https://*.googletagmanager.com",
                "font-src 'self'",
                "connect-src 'self' https://cal.com https://*.cal.com https://*.google-analytics.com https://*.analytics.google.com https://*.googletagmanager.com",
                "frame-src 'self' https://cal.com https://*.cal.com",
            ]));
        }
    }
}
