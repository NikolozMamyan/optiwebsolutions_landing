<?php

namespace App\Controller;

use App\Service\EditorialContent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BlogController extends AbstractController
{
    #[Route(path: ['fr' => '/blog', 'en' => '/en/blog'], name: 'app_blog', methods: ['GET'])]
    public function index(EditorialContent $editorial): Response
    {
        return $this->render('editorial/index.html.twig', [
            'articles' => $editorial->articles(), 'cases' => $editorial->cases(),
        ]);
    }

    #[Route(path: ['fr' => '/blog/{slug}', 'en' => '/en/blog/{slug}'], name: 'app_blog_article', requirements: ['slug' => '[a-z0-9-]+'], methods: ['GET'])]
    public function article(string $slug, Request $request, EditorialContent $editorial): Response
    {
        $entry = $editorial->findArticle($slug, $request->getLocale()) ?? throw $this->createNotFoundException();

        return $this->render('editorial/show.html.twig', [
            'entry' => $entry, 'kind' => 'article',
            'localized_parameters' => ['fr' => ['slug' => $entry['slugs']['fr']], 'en' => ['slug' => $entry['slugs']['en']]],
            'articles' => $editorial->articles(), 'cases' => $editorial->cases(),
        ]);
    }

    #[Route(path: ['fr' => '/etudes-de-cas', 'en' => '/en/case-studies'], name: 'app_case_studies', methods: ['GET'])]
    public function cases(EditorialContent $editorial): Response
    {
        return $this->render('editorial/cases.html.twig', ['cases' => $editorial->cases()]);
    }

    #[Route(path: ['fr' => '/etudes-de-cas/{slug}', 'en' => '/en/case-studies/{slug}'], name: 'app_case_study', requirements: ['slug' => '[a-z0-9-]+'], methods: ['GET'])]
    public function caseStudy(string $slug, Request $request, EditorialContent $editorial): Response
    {
        $entry = $editorial->findCase($slug, $request->getLocale()) ?? throw $this->createNotFoundException();

        return $this->render('editorial/show.html.twig', [
            'entry' => $entry, 'kind' => 'case',
            'localized_parameters' => ['fr' => ['slug' => $entry['slugs']['fr']], 'en' => ['slug' => $entry['slugs']['en']]],
            'articles' => $editorial->articles(), 'cases' => $editorial->cases(),
        ]);
    }
}
