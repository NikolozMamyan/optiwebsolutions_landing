<?php

namespace App\Controller;

use App\Service\EditorialContent;
use App\Service\ServiceContent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ServiceController extends AbstractController
{
    #[Route(path: ['fr' => '/services/{slug}', 'en' => '/en/services/{slug}'], name: 'app_service', requirements: ['slug' => '[a-z0-9-]+'], methods: ['GET'])]
    public function show(string $slug, Request $request, ServiceContent $services, EditorialContent $editorial): Response
    {
        $service = $services->find($slug, $request->getLocale()) ?? throw $this->createNotFoundException();

        return $this->render('services/show.html.twig', [
            'service' => $service,
            'localized_parameters' => ['fr' => ['slug' => $service['slugs']['fr']], 'en' => ['slug' => $service['slugs']['en']]],
            'articles' => $editorial->articles(), 'cases' => $editorial->cases(),
        ]);
    }
}
