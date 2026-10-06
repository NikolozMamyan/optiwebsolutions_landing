<?php

namespace App\Controller;

use App\Service\EditorialContent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route(path: ['fr' => '/', 'en' => '/en'], name: 'app_home', methods: ['GET'])]
    public function index(EditorialContent $editorial): Response
    {
        return $this->render('home/index.html.twig', ['articles' => $editorial->articles(), 'cases' => $editorial->cases()]);
    }

    #[Route(path: ['fr' => '/contact', 'en' => '/en/contact'], name: 'app_contact', methods: ['GET'])]
    public function contact(): Response
    {
        return $this->render('contact/index.html.twig');
    }

    #[Route(path: ['fr' => '/legal', 'en' => '/en/legal'], name: 'app_legal', methods: ['GET'])]
    public function legal(): Response
    {
        return $this->render('legal/legal.html.twig');
    }

    #[Route(path: ['fr' => '/privacy-policy', 'en' => '/en/privacy-policy'], name: 'app_privacy_policy', methods: ['GET'])]
    public function privacyPolicy(): Response
    {
        return $this->render('legal/privacy.html.twig');
    }

    #[Route(path: ['fr' => '/terms-and-conditions', 'en' => '/en/terms-and-conditions'], name: 'app_terms_and_conditions', methods: ['GET'])]
    public function termsAndConditions(): Response
    {
        return $this->render('legal/terms.html.twig');
    }
}
