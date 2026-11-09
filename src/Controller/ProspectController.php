<?php

namespace App\Controller;

use App\Service\ProspectSubmission;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\Routing\Attribute\Route;

final class ProspectController extends AbstractController
{
    #[Route(path: ['fr' => '/contact/demande', 'en' => '/en/contact/request'], name: 'app_prospect_submit', methods: ['POST'])]
    public function submit(Request $request, ProspectSubmission $submission, RateLimiterFactory $contactLimiter): JsonResponse
    {
        if (!$this->isCsrfTokenValid('contact', $request->request->getString('_token'))) {
            return $this->json(['error' => 'csrf'], 403);
        }
        if (!$contactLimiter->create(hash('sha256', $request->getClientIp() ?? 'unknown'))->consume()->isAccepted()) {
            return $this->json(['error' => 'rate_limit'], 429);
        }

        $errors = $submission->submit($request->request->all());

        return $errors ? $this->json(['errors' => $errors], 422) : $this->json(['success' => true], 201);
    }
}
